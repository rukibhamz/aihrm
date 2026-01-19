<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:system-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database and public storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "backup-" . now()->format('Y-m-d_H-i-s') . ".sql";
        $storagePath = storage_path("app/backups");
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $this->info("Starting System Backup...");

        // Database Backup (MySQL/MariaDB)
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $outputPath = $storagePath . "/" . $filename;
        
        // Construct mysqldump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbHost),
            escapeshellarg($dbName),
            escapeshellarg($outputPath)
        );

        $this->info("Backing up database: {$dbName}...");
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Database backup successful: {$filename}");
        } else {
            $this->error("Database backup failed.");
        }

        // Storage Backup (Recursive zip)
        $zipFilename = "storage-" . now()->format('Y-m-d_H-i-s') . ".zip";
        $zipPath = $storagePath . "/" . $zipFilename;
        $sourceFolder = storage_path('app/public');

        if (file_exists($sourceFolder)) {
            $this->info("Backing up storage directory...");
            $zipCommand = sprintf(
                'powershell Compress-Archive -Path %s -DestinationPath %s -Force',
                escapeshellarg($sourceFolder),
                escapeshellarg($zipPath)
            );
            exec($zipCommand, $outputZip, $returnVarZip);
            
            if ($returnVarZip === 0) {
                $this->info("Storage backup successful: {$zipFilename}");
            } else {
                $this->error("Storage backup failed.");
            }
        }

        $this->info("Backup process completed.");
    }
}
