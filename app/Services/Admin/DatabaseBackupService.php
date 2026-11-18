<?php

declare(strict_types=1);

namespace App\Services\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseBackupService
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    /**
     * Get absolute path to backups directory and ensure it exists.
     */
    public function getBackupDirectory(): string
    {
        $path = storage_path('app/backups');
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }

    /**
     * Get database and backup overview statistics.
     */
    public function getStats(): array
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $databaseName = (string) $connection->getDatabaseName();

        $tablesCount = 0;
        $totalRecords = 0;

        try {
            if ($driver === 'mysql') {
                $tables = $connection->select('SHOW TABLES');
                $tablesCount = count($tables);

                $statusRows = $connection->select('SHOW TABLE STATUS');
                foreach ($statusRows as $status) {
                    $totalRecords += (int) ($status->Rows ?? 0);
                }
            } elseif ($driver === 'sqlite') {
                $tables = $connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                $tablesCount = count($tables);
                foreach ($tables as $t) {
                    $totalRecords += DB::table($t->name)->count();
                }
            }
        } catch (\Throwable) {
            // Graceful fallback
        }

        $backups = $this->listBackups();
        $totalBackupBytes = array_sum(array_column($backups, 'size_bytes'));
        $lastBackup = ! empty($backups) ? $backups[0]['created_at'] : null;

        return [
            'driver' => strtoupper($driver),
            'database' => $databaseName,
            'tables_count' => $tablesCount,
            'total_records' => $totalRecords,
            'backups_count' => count($backups),
            'total_backup_size' => $this->formatBytes($totalBackupBytes),
            'last_backup_at' => $lastBackup,
        ];
    }

    /**
     * List all database backups ordered by newest first.
     */
    public function listBackups(): array
    {
        $dir = $this->getBackupDirectory();
        $files = File::glob($dir . '/*.{sql,sql.gz}', GLOB_BRACE) ?: [];

        $list = [];
        foreach ($files as $filepath) {
            $filename = basename($filepath);
            $sizeBytes = (int) File::size($filepath);
            $modifiedTime = File::lastModified($filepath);
            $createdAt = Carbon::createFromTimestamp($modifiedTime);

            $list[] = [
                'filename' => $filename,
                'path' => $filepath,
                'size_bytes' => $sizeBytes,
                'size' => $this->formatBytes($sizeBytes),
                'created_at' => $createdAt->format('M d, Y h:i A'),
                'relative_time' => $createdAt->diffForHumans(),
                'timestamp' => $modifiedTime,
            ];
        }

        // Sort descending by timestamp
        usort($list, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $list;
    }

    /**
     * Create a new database backup file.
     */
    public function createBackup(?string $customName = null): array
    {
        $dir = $this->getBackupDirectory();
        $timestamp = date('Y-m-d_H-i-s');

        if ($customName) {
            $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '_', trim($customName));
            $filename = "sorolata_backup_{$sanitized}_{$timestamp}.sql";
        } else {
            $filename = "sorolata_backup_{$timestamp}.sql";
        }

        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $created = false;
        $driver = DB::connection()->getDriverName();

        // 1. If MySQL, try mysqldump first
        if ($driver === 'mysql') {
            $mysqldump = $this->findMysqldumpBinary();
            if ($mysqldump) {
                $created = $this->exportWithMysqldump($mysqldump, $filepath);
            }
        }

        // 2. If mysqldump didn't run or failed, use pure PHP PDO exporter
        if (! $created || ! File::exists($filepath) || File::size($filepath) === 0) {
            $this->exportWithPdo($filepath);
        }

        if (! File::exists($filepath) || File::size($filepath) === 0) {
            throw new \RuntimeException('Failed to generate database backup file.');
        }

        $sizeBytes = (int) File::size($filepath);
        $formattedSize = $this->formatBytes($sizeBytes);

        $this->activityLog->log(
            action: 'database.backup_created',
            description: "Generated database backup {$filename} ({$formattedSize})",
            properties: [
                'filename' => $filename,
                'size_bytes' => $sizeBytes,
                'size' => $formattedSize,
            ]
        );

        return [
            'filename' => $filename,
            'path' => $filepath,
            'size_bytes' => $sizeBytes,
            'size' => $formattedSize,
            'created_at' => Carbon::now()->format('M d, Y h:i A'),
        ];
    }

    /**
     * Download a backup file securely.
     */
    public function download(string $filename): BinaryFileResponse
    {
        $safeName = basename($filename);
        $filepath = $this->getBackupDirectory() . DIRECTORY_SEPARATOR . $safeName;

        if (! File::exists($filepath)) {
            throw new \InvalidArgumentException("Backup file '{$safeName}' does not exist.");
        }

        return response()->download($filepath, $safeName, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$safeName}\"",
        ]);
    }

    /**
     * Delete a backup file.
     */
    public function delete(string $filename): bool
    {
        $safeName = basename($filename);
        $filepath = $this->getBackupDirectory() . DIRECTORY_SEPARATOR . $safeName;

        if (! File::exists($filepath)) {
            throw new \InvalidArgumentException("Backup file '{$safeName}' does not exist.");
        }

        $deleted = File::delete($filepath);

        if ($deleted) {
            $this->activityLog->log(
                action: 'database.backup_deleted',
                description: "Deleted database backup {$safeName}",
                properties: ['filename' => $safeName]
            );
        }

        return $deleted;
    }

    /**
     * Locate mysqldump binary on system.
     */
    protected function findMysqldumpBinary(): ?string
    {
        $candidates = [
            'D:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'mysqldump',
            'mysqldump.exe',
        ];

        foreach ($candidates as $bin) {
            if (str_contains($bin, '\\') && File::exists($bin)) {
                return $bin;
            }
        }

        // Check if mysqldump is in PATH
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $checkCmd = $isWindows ? 'where.exe mysqldump 2>nul' : 'which mysqldump 2>/dev/null';
        $output = @shell_exec($checkCmd);
        if ($output && trim($output) !== '') {
            $lines = explode(PHP_EOL, trim($output));
            if (! empty($lines[0]) && (File::exists($lines[0]) || ! str_contains($lines[0], '\\'))) {
                return trim($lines[0]);
            }
        }

        return null;
    }

    /**
     * Export database using mysqldump CLI.
     */
    protected function exportWithMysqldump(string $binaryPath, string $filepath): bool
    {
        try {
            $host = config('database.connections.mysql.host', '127.0.0.1');
            $port = config('database.connections.mysql.port', '3306');
            $database = config('database.connections.mysql.database', 'sorolata');
            $username = config('database.connections.mysql.username', 'root');
            $password = config('database.connections.mysql.password', '');

            $escapedBinary = escapeshellarg($binaryPath);
            $escapedHost = escapeshellarg($host);
            $escapedPort = escapeshellarg((string) $port);
            $escapedUser = escapeshellarg($username);
            $escapedDb = escapeshellarg($database);
            $escapedFile = escapeshellarg($filepath);

            $passArg = $password !== '' ? '--password=' . escapeshellarg($password) : '';

            $cmd = "{$escapedBinary} --host={$escapedHost} --port={$escapedPort} --user={$escapedUser} {$passArg} --single-transaction --quick --skip-lock-tables --default-character-set=utf8mb4 {$escapedDb} > {$escapedFile}";

            $resultCode = 0;
            exec($cmd, $output, $resultCode);

            return $resultCode === 0 && File::exists($filepath) && File::size($filepath) > 0;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Pure PHP PDO database dumper fallback.
     */
    protected function exportWithPdo(string $filepath): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $databaseName = (string) $connection->getDatabaseName();

        $handle = fopen($filepath, 'w');
        if (! $handle) {
            throw new \RuntimeException("Unable to open backup file for writing: {$filepath}");
        }

        $now = date('Y-m-d H:i:s');
        fwrite($handle, "-- ==========================================================\n");
        fwrite($handle, "-- Sorolata Monolith Database Backup\n");
        fwrite($handle, "-- Engine: Sorolata Monolith Architecture\n");
        fwrite($handle, "-- Driver: {$driver}\n");
        fwrite($handle, "-- Database: {$databaseName}\n");
        fwrite($handle, "-- Exported At: {$now}\n");
        fwrite($handle, "-- ==========================================================\n\n");

        if ($driver === 'mysql') {
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET NAMES utf8mb4;\n\n");

            $tables = $connection->select('SHOW TABLES');
            $tableNames = [];
            foreach ($tables as $t) {
                $arr = (array) $t;
                $tableNames[] = (string) reset($arr);
            }
        } else {
            // SQLite
            fwrite($handle, "PRAGMA foreign_keys = OFF;\n\n");
            $tables = $connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tableNames = array_map(fn($t) => (string) $t->name, $tables);
        }

        foreach ($tableNames as $table) {
            fwrite($handle, "\n-- --------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$table}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");

            if ($driver === 'mysql') {
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                $createRows = $connection->select("SHOW CREATE TABLE `{$table}`");
                if (! empty($createRows)) {
                    $createArr = (array) $createRows[0];
                    $createSql = $createArr['Create Table'] ?? end($createArr);
                    fwrite($handle, $createSql . ";\n\n");
                }
            } elseif ($driver === 'sqlite') {
                fwrite($handle, "DROP TABLE IF EXISTS \"{$table}\";\n");
                $createRows = $connection->select("SELECT sql FROM sqlite_master WHERE name = ?", [$table]);
                if (! empty($createRows) && ! empty($createRows[0]->sql)) {
                    fwrite($handle, $createRows[0]->sql . ";\n\n");
                }
            }

            // Dump rows in chunks
            $colQuote = $driver === 'mysql' ? '`' : '"';
            DB::table($table)->orderBy(DB::raw('1'))->chunk(250, function ($rows) use ($handle, $table, $colQuote) {
                if ($rows->isEmpty()) {
                    return;
                }

                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $val) {
                        if (is_null($val)) {
                            $values[] = 'NULL';
                        } elseif (is_numeric($val) && ! is_string($val)) {
                            $values[] = $val;
                        } else {
                            $escaped = str_replace(
                                ["\\", "'", "\r", "\n", "\0"],
                                ["\\\\", "\\'", "\\r", "\\n", "\\0"],
                                (string) $val
                            );
                            $values[] = "'{$escaped}'";
                        }
                    }
                    fwrite($handle, "INSERT INTO {$colQuote}{$table}{$colQuote} VALUES (" . implode(', ', $values) . ");\n");
                }
            });
        }

        if ($driver === 'mysql') {
            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        } else {
            fwrite($handle, "\nPRAGMA foreign_keys = ON;\n");
        }

        fclose($handle);
    }

    /**
     * Format bytes to human readable format.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
