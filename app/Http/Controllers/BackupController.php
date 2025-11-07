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

            // Connect using mysqli
            $conn = new \mysqli($host, $username, $password, $db);
            if ($conn->connect_error) {
                throw new \Exception('Database connection failed: ' . $conn->connect_error);
            }

            $backupSql = "";
            $tables = [];
            $result = $conn->query("SHOW TABLES");

            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }

            foreach ($tables as $table) {
                // Table structure
                $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch_assoc();
                $backupSql .= "\n\n" . $create['Create Table'] . ";\n\n";

                // Table data
                $rows = $conn->query("SELECT * FROM `$table`");
                while ($row = $rows->fetch_assoc()) {
                    $vals = array_map(function ($v) use ($conn) {
                        return isset($v) ? "'" . $conn->real_escape_string($v) . "'" : "NULL";
                    }, array_values($row));
                    $backupSql .= "INSERT INTO `$table` VALUES(" . implode(',', $vals) . ");\n";
                }
                $backupSql .= "\n\n";
            }

            $conn->close();

            // Save file
            $backupPath = storage_path('app/backups');
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            $fileName = $db . '_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . '/' . $fileName;

            file_put_contents($filePath, $backupSql);

            Backup::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => filesize($filePath),
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

            if (!file_exists($filepath)) {
                throw new \Exception('Backup file not found.');
            }

            $conn = new \mysqli($host, $username, $password, $db);
            if ($conn->connect_error) {
                throw new \Exception('Database connection failed: ' . $conn->connect_error);
            }

            // Load SQL and modify it to prevent "table exists" errors
            $sql = file_get_contents($filepath);

            // Convert CREATE TABLE to CREATE TABLE IF NOT EXISTS
            $sql = preg_replace('/CREATE TABLE `(.*?)`/i', 'CREATE TABLE IF NOT EXISTS `$1`', $sql);

            // Execute line by line (multi_query can break on errors)
            $queries = array_filter(array_map('trim', explode(";\n", $sql)));
            foreach ($queries as $query) {
                if (!$conn->query($query)) {
                    // Skip "duplicate entry" or "table exists" warnings silently
                    if (!str_contains($conn->error, 'exists') && !str_contains($conn->error, 'Duplicate entry')) {
                        throw new \Exception('Restore failed on query: ' . $conn->error);
                    }
                }
            }

            $conn->close();
            return back()->with('success', 'Database restored successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        if (!file_exists($backup->file_path)) {
            return back()->with('error', 'File not found.');
        }
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
