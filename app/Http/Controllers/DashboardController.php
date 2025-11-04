<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $userId = Auth::id();

        $totalReports = IncidentReport::where('user_id', $userId)->count();
        $pendingReports = IncidentReport::where('user_id', $userId)->where('status', 'Pending')->count();
        $inProgressReports = IncidentReport::where('user_id', $userId)->where('status', 'In Progress')->count();
        $resolvedReports = IncidentReport::where('user_id', $userId)->where('status', 'Resolved')->count();

        return view('user.dashboard', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'resolvedReports'
        ));
    }

    public function responderDashboard()
    {
        $responderId = auth()->id();

        $assignedReports = IncidentReport::where('responder_id', $responderId)
                            ->where('status', 'In Progress')
                            ->count();

        $resolvedReports = IncidentReport::where('responder_id', $responderId)
                            ->where('status', 'Resolved')
                            ->count();

        $pendingReports = IncidentReport::where('responder_id', $responderId)
                            ->where('status', 'Pending')
                            ->count();

        $inProgressReports = IncidentReport::where('responder_id', $responderId)
                            ->where('status', 'In Progress')
                            ->count();

        return view('responder.dashboard', compact(
            'assignedReports', 
            'resolvedReports', 
            'pendingReports', 
            'inProgressReports'
        ));
    }

    public function adminDashboard()
    {
        // Example overview for admins
        $totalReports = IncidentReport::count();
        $pendingReports = IncidentReport::where('status', 'Pending')->count();
        $inProgressReports = IncidentReport::where('status', 'In Progress')->count();
        $resolvedReports = IncidentReport::where('status', 'Resolved')->count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'resolvedReports',
            'totalUsers'
        ));
    }
}
