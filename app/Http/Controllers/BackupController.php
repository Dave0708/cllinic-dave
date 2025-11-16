<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql; // <-- Import the class
use Symfony\Component\Process\Exception\ProcessFailedException; // <-- Import the class

class BackupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function downloadBackup(Request $request)
    {
        // Define the filename for the backup
        $fileName = 'clinic' . '.sql';

    
        // Define the full path where the backup will be temporarily stored
        $filePath = storage_path('app/' . $fileName);

        try {
            // Get database credentials from your .env file
            $dbName     = config('database.connections.mysql.database');
            $userName   = config('database.connections.mysql.username');
            $password   = config('database.connections.mysql.password');
            $host       = config('database.connections.mysql.host'); // This is likely 'localhost'
            $port       = config('database.connections.mysql.port');

            // --- This is the core logic ---
            MySql::create()
                ->setDbName($dbName)
                ->setUserName($userName)
                ->setPassword($password)
                // ->setHost($host) // We use the explicit IP below to fix the socket error
                ->setHost('127.0.0.1') // <-- ADDED THIS LINE to force IPv4 connection

                // ** IMPORTANT: Update this path if your XAMPP is not in C:\xampp **
                ->setDumpBinaryPath('C:\xampp\mysql\bin') 

                ->dumpToFile($filePath);
            // --- End of core logic ---

            // Return the file as a download and delete it after sending
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (ProcessFailedException $e) {
            // Handle any errors
            report($e);
            return back()->with('error', 'The database backup failed: ' . $e->getMessage());
        }
    }
}