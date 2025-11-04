<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Backup;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.backup.index', compact('backups'));
    }

    protected function confirmAdminPassword(?string $plain)
    {
        $user = auth()->user();
        if (!$user) return false;
        if (!$plain) return false;
        return Hash::check($plain, $user->password);
    }

    public function runBackup(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required|string'
        ]);

        if (!$this->confirmAdminPassword($request->input('confirm_password'))) {
            return back()->with('error', 'Incorrect admin password.');
        }

        try {
            $db = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $dumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
            $backupPath = storage_path('app/backups');

            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            $fileName = $db . '_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . '/' . $fileName;

            $command = "\"$dumpPath\" --user=\"$username\" --password=\"$password\" --host=\"$host\" \"$db\" > \"$filePath\"";
            exec($command, $output, $result);

            if ($result !== 0) {
                return back()->with('error', 'Backup failed. Code: ' . $result . ' | ' . implode(' ', $output));
            }

            $fileSize = file_exists($filePath) ? filesize($filePath) : 0;

            Backup::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'created_by' => auth()->id() ?? null,
            ]);

            return back()->with('success', 'Backup created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    public function restore(Request $request, $id)
    {
        $request->validate([
            'confirm_password' => 'required|string'
        ]);

        if (!$this->confirmAdminPassword($request->input('confirm_password'))) {
            return back()->with('error', 'Incorrect admin password.');
        }

        $backup = Backup::findOrFail($id);
        $filepath = $backup->file_path;

        try {
            $db = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $mysql = 'C:\xampp\mysql\bin\mysql.exe';

            $command = "\"$mysql\" --user=\"$username\" --password=\"$password\" --host=\"$host\" \"$db\" < \"$filepath\"";
            exec($command, $output, $result);

            if ($result !== 0) {
                return back()->with('error', 'Restore failed! Code: '.$result.' | '.implode(' ', $output));
            }

            return back()->with('success', 'Database restored successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        return response()->download($backup->file_path);
    }

    public function delete(Request $request, $id)
    {
        $request->validate(['confirm_password' => 'required|string']);

        if (!$this->confirmAdminPassword($request->input('confirm_password'))) {
            return back()->with('error', 'Incorrect admin password.');
        }

        $backup = Backup::findOrFail($id);

        try {
            if (file_exists($backup->file_path)) {
                unlink($backup->file_path);
            }
            $backup->delete();
            return back()->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }
}