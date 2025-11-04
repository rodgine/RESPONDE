<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index()
    {
        $reports = IncidentReport::where('responder_id', Auth::id())
                    ->where('status', '!=', 'Resolved')
                    ->get();

        return view('responder.assigned-incidents', compact('reports'));
    }

    public function store(Request $request, $report_id)
    {
        $request->validate([
            'details' => 'required|string',
            'action_taken' => 'required|string',
            'documentation' => 'required|array|min:1',
            'victims_count' => 'required|integer|min:0',
            'deaths_count' => 'required|integer|min:0',
            'rescued_count' => 'required|integer|min:0',
            'date_resolved' => 'required|date'
        ]);

        $report = IncidentReport::findOrFail($report_id);

        // Save documentation images
        $documentationPaths = $this->saveBase64Images($request->documentation, 'responder_docs');

        // Create incident log record
        $incident = Incident::create([
            'reference_number' => $report->reference_code,
            'responder_id' => Auth::id(),
            'details' => $request->details,
            'action_taken' => $request->action_taken,
            'documentation' => json_encode($documentationPaths),
            'victims_count' => $request->victims_count,
            'deaths_count' => $request->deaths_count,
            'rescued_count' => $request->rescued_count,
            'date_resolved' => $request->date_resolved
        ]);

        $report->update([
            'status' => 'Resolved',
            'number_of_victims' => $request->victims_count,
            'number_of_deaths' => $request->deaths_count,
            'number_of_rescued' => $request->rescued_count,
            'date_resolved' => $request->date_resolved
        ]);

        return response()->json([
            'status' => 'success',
            'title' => 'Incident Resolved',
            'message' => 'Report submitted successfully and marked as resolved.'
        ]);
    }

    protected function saveBase64Images($images, $folder)
    {
        $paths = [];
        foreach($images as $img) {
            // Decode base64
            $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));

            // Generate a unique filename
            $filename = uniqid() . '.jpeg';

            // Save in public disk
            $path = "{$folder}/{$filename}";
            \Storage::disk('public')->put($path, $imgData);

            // Store path relative to storage/app/public
            $paths[] = $path;
        }
        return $paths;
    }

    public function viewGeneratedReport($id)
    {
        $report = IncidentReport::with('incident', 'user')->findOrFail($id);

        return view('responder.generated-report', compact('report'));
    }

    public function completedIncidents()
    {
        $incidents = Incident::with(['report', 'responder'])
            ->whereHas('report', function ($query) {
                $query->where('status', 'Resolved');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Group and summarize by incident type
        $summary = $incidents
            ->groupBy(fn($i) => $i->report->incident_type ?? 'Unknown')
            ->map(function ($group) {
                return [
                    'incident_type' => $group->first()->report->incident_type ?? 'Unknown',
                    'count'         => $group->count(),
                    'victims'       => $group->sum('victims_count'),
                    'deaths'        => $group->sum('deaths_count'),
                    'rescued'       => $group->sum('rescued_count'),
                    'last_resolved' => $group->max('date_resolved'),
                ];
            })
            ->values();

        return view('admin.completed-incidents', compact('incidents', 'summary'));
    }

    public function viewGeneratedReportAdmin($id)
    {
        $report = IncidentReport::with(['incident.responder', 'user'])->findOrFail($id);

        // Fetch counts if incident exists
        $incident = $report->incident;
        $counts = [
            'victims' => $incident->victims_count ?? 0,
            'deaths'  => $incident->deaths_count ?? 0,
            'rescued' => $incident->rescued_count ?? 0,
        ];

        return view('admin.generated-report', compact('report', 'counts'));
    }

    public function generateReportPage()
    {
        $incidents = Incident::with('report', 'responder')->whereHas('report', function($q){
            $q->where('status', 'Resolved');
        })->get();

        return view('admin.generate-report', compact('incidents'));
    }

    public function printReport($id)
    {
        $report = \App\Models\IncidentReport::with(['incident.responder', 'user'])->findOrFail($id);

        $report->landmark_photos = json_decode($report->landmark_photos ?? '[]', true);
        $report->proof_photos = json_decode($report->proof_photos ?? '[]', true);

        // Include counts from related incident
        $incident = $report->incident;
        $counts = [
            'victims' => $incident->victims_count ?? 0,
            'deaths'  => $incident->deaths_count ?? 0,
            'rescued' => $incident->rescued_count ?? 0,
        ];

        return view('admin.print-report', compact('report', 'counts'));
    }

    public function searchCompletedIncidents(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([
                'incident_types' => [],
                'locations' => [],
                'users' => [],
                'responders' => [],
            ]);
        }

        // 🔹 For live suggestions (partial match)
        $incidents = Incident::with(['report.user', 'responder'])
            ->whereHas('report', function ($q) use ($query) {
                $q->where('status', 'Resolved')
                ->where(function ($sub) use ($query) {
                    $sub->where('incident_type', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->orWhereHas('user', function ($u) use ($query) {
                            $u->where('name', 'like', "%{$query}%");
                        });
                });
            })
            ->orWhereHas('responder', function ($r) use ($query) {
                $r->where('name', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        // Separate suggestions
        $incident_types = $incidents->pluck('report.incident_type')->unique()->values();
        $locations = $incidents->pluck('report.location')->unique()->values();
        $users = $incidents->pluck('report.user.name')->unique()->values();
        $responders = $incidents->pluck('responder.name')->unique()->values();

        return response()->json([
            'incident_types' => $incident_types,
            'locations' => $locations,
            'users' => $users,
            'responders' => $responders,
            'results' => $incidents, // optional for search results
        ]);
    }

    public function getAllCompletedJson() {
        $incidents = Incident::with(['report.user', 'responder'])
            ->whereHas('report', fn($q) => $q->where('status', 'Resolved'))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($incidents);
    }
}
