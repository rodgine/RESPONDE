<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IncidentReport;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ArduinoService;
//for sending SMS to
use Twilio\Rest\Client;

class IncidentReportController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'incident_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'landmark_photos' => 'required|array|min:2',
            'proof_photos' => 'required|array|min:2',
        ]);

        try {
            $landmarkPaths = $this->saveBase64Images($request->landmark_photos, 'landmarks');
            $proofPaths = $this->saveBase64Images($request->proof_photos, 'proofs');

            $landmarkPaths = array_map(fn($p) => 'storage/' . $p, $landmarkPaths);
            $proofPaths = array_map(fn($p) => 'storage/' . $p, $proofPaths);

            $report = IncidentReport::create([
                'incident_type'   => $request->incident_type,
                'location'        => $request->location,
                'landmark_photos' => json_encode($landmarkPaths),
                'proof_photos'    => json_encode($proofPaths),
                'status'          => 'Pending',
                'user_id'         => Auth::id(),
                'reference_code'  => strtoupper(Str::random(8)),
                'date_reported'   => now(),
            ]);

            // Log the alert
            Notification::create([
                'type' => 'new_incident',
                'message' => "New incident reported: {$report->incident_type} at {$report->location}",
                'data' => [
                    'report_id' => $report->id,
                    'incident_type' => $report->incident_type,
                    'details' => $request->details,
                    'location' => $report->location,
                    'responder' => auth()->user()->name,
                    'reported_by' => $report->user?->name ?? '—'
                ]
            ]);

            $powershellPath = 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe';
            $script = '$port = New-Object System.IO.Ports.SerialPort COM3,9600,None,8,one; ' .
                    '$port.Open(); Start-Sleep -Seconds 2; ' .
                    '$port.WriteLine(\'START\'); Start-Sleep -Seconds 1; $port.Close();';

            $cmd = sprintf('%s -ExecutionPolicy Bypass -Command "%s"', $powershellPath, $script);
            $output = shell_exec($cmd . ' 2>&1');

            \Log::info('Arduino START command executed');
            \Log::info('Command: ' . $cmd);
            \Log::info('Output: ' . $output);

            return response()->json([
                'status' => 'success',
                'title' => 'Report Submitted!',
                'message' => 'Your incident report has been submitted successfully.',
                'reference' => $report->reference_code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'title' => 'Submission Failed',
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function saveBase64Images(array $images, string $folder): array
    {
        $paths = [];

        foreach ($images as $imgData) {
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $type = strtolower($type[1]);
                $imgData = base64_decode($imgData);

                $fileName = Str::random(10) . '.' . $type;
                $filePath = "reports/{$folder}/" . $fileName;

                Storage::disk('public')->put($filePath, $imgData);
                $paths[] = $filePath;
            }
        }

        return $paths;
    }

    public function myReports()
    {
        $reports = IncidentReport::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user.myreports', compact('reports'));
    }

    public function adminIndex(Request $request)
    {
        $query = IncidentReport::with('user');
        $responders = User::where('role', 'responder')->get();

        // Default: only Pending and In Progress
        if (!$request->status) {
            $query->whereIn('status', ['Pending', 'In Progress']);
        } elseif ($request->status && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('date_reported', $request->date);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        return view('admin.incident-reports', compact('reports', 'responders'));
    }

    public function assignResponder(Request $request, $id)
    {
        $request->validate([
            'responder_id' => 'required|exists:users,id',
        ]);

        try {
            $report = IncidentReport::findOrFail($id);
            $report->responder_id = $request->responder_id;
            $report->status = 'In Progress';
            $report->save();

            // Fetch responder phone number
            $responder = User::find($request->responder_id);

            if ($responder && $responder->phone_number) {
                try {
                    $client = new Client(
                        config('services.twilio.sid'),
                        config('services.twilio.token')
                    );

                    $client->messages->create(
                        'whatsapp:'.$responder->phone_number, 
                        [
                            'from' => config('services.twilio.from'),
                            'body' =>
                                "🚨 Incident Alert 🚨\n".
                                "Incident: {$report->incident_type}\n".
                                "Location: {$report->location}\n".
                                "Status: In Progress\n\n".
                                "🔗 More details:\n".
                                "Login to the responder site:\n".
                                "https://jeannette-unbenefited-belle.ngrok-free.dev"
                        ]
                    );

                    \Log::info("WhatsApp message sent to responder: {$responder->phone_number}");
                } catch (\Exception $e) {
                    \Log::error("WhatsApp sending failed: " . $e->getMessage());
                }
            }

            \App\Models\Notification::create([
                'type' => 'responder_assignment',
                'message' => "🚨 You’ve been assigned to a new incident: {$report->incident_type} at {$report->location}. Immediate response required.",
                'data' => json_encode([
                    'report_id' => $report->id,
                    'incident_type' => $report->incident_type,
                    'location' => $report->location,
                    'status' => $report->status,
                    'assigned_by' => auth()->user()->name,
                ]),
                'responder_id' => $request->responder_id, 
                'read' => false,
            ]);

            // Keep your Arduino code unchanged...

            return response()->json([
                'status' => 'success',
                'title' => 'Assigned!',
                'message' => 'Responder assigned successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'title' => 'Assignment Failed',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function assignedIncidents()
    {
        $reports = IncidentReport::where('responder_id', auth()->id())
            ->where('status', 'In Progress')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('responder.assigned-incidents', compact('reports'));
    }

    public function completedIncidents()
    {
        $reports = IncidentReport::where('responder_id', auth()->id())
                    ->where('status', 'Resolved')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('responder.completed-incidents', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);

        $report = IncidentReport::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return response()->json([
            'status' => 'success',
            'title' => 'Status Updated!',
            'message' => "Incident marked as {$request->status}."
        ]);
    }
}