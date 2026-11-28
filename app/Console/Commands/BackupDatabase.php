<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Create a compressed database backup';

    public function handle(): int
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host', '127.0.0.1');
        $port     = config('database.connections.mysql.port', '3306');

        $backupDir = storage_path('app/backups');
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = sprintf('%s-%s.sql.gz', $database, Carbon::now()->format('Y-m-d_H-i-s'));
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Pass password via MYSQL_PWD env var rather than CLI argument to avoid exposure in process lists
        $command = sprintf(
            'mysqldump -h %s -P %s -u %s --single-transaction --routines --triggers %s | gzip > %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        $process = Process::fromShellCommandline($command);
        $process->setEnv(['MYSQL_PWD' => (string) $password]);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Database backup failed: ' . $process->getErrorOutput());
            return self::FAILURE;
        }

        $this->info("Backup created: {$filepath}");
        return self::SUCCESS;
    }
}