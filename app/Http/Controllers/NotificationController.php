<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\ArduinoService;

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        try {
            // Only unread notifications
            $notifications = Notification::where('read', false)
                ->where('type', 'new_incident')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notif = Notification::findOrFail($id);
            $notif->read = true;
            $notif->save();

            $powershellPath = 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe';
            $script = '$port = New-Object System.IO.Ports.SerialPort COM3,9600,None,8,one; ' .
                    '$port.Open(); Start-Sleep -Seconds 2; ' .
                    '$port.WriteLine(\'STOP\'); Start-Sleep -Seconds 1; $port.Close();';

            $cmd = sprintf('%s -ExecutionPolicy Bypass -Command "%s"', $powershellPath, $script);
            shell_exec($cmd . ' 2>&1');

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark notification as read.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchResponderNotifications()
    {
        try {
            $notifications = Notification::where('read', false)
                ->where('type', 'responder_assignment')
                ->where('responder_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch responder notifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markResponderAsRead($id)
    {
        try {
            $notif = Notification::where('id', $id)
                ->where('responder_id', Auth::id())
                ->firstOrFail();

            $notif->read = true;
            $notif->save();

            $powershellPath = 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe';
            $script = '$port = New-Object System.IO.Ports.SerialPort COM3,9600,None,8,one; ' .
                    '$port.Open(); Start-Sleep -Seconds 2; ' .
                    '$port.WriteLine(\'STOP\'); Start-Sleep -Seconds 1; $port.Close();';

            $cmd = sprintf('%s -ExecutionPolicy Bypass -Command "%s"', $powershellPath, $script);
            shell_exec($cmd . ' 2>&1');

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark responder notification as read.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
