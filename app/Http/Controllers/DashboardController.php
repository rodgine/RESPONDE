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
        // Reports from citizens (IncidentReport table)
        $citizenReports = \App\Models\IncidentReport::count();
    
        // Reports from responders (Incident table)
        $responderReports = \App\Models\Incident::count();
    
        // Combined total reports
        $totalReports = $citizenReports + $responderReports;
    
        // Status-based counts (if used in other dashboard stats)
        $pendingReports = \App\Models\IncidentReport::where('status', 'Pending')->count();
        $inProgressReports = \App\Models\IncidentReport::where('status', 'In Progress')->count();
        $resolvedReports = \App\Models\IncidentReport::where('status', 'Resolved')->count();
    
        // Total users
        $totalUsers = \App\Models\User::count();
    
        return view('admin.dashboard', compact(
            'citizenReports',
            'responderReports',
            'totalReports',
            'pendingReports',
            'inProgressReports',
            'resolvedReports',
            'totalUsers'
        ));
    }
}
