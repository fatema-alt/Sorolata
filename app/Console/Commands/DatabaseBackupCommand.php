<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Admin\DatabaseBackupService;
use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--name= : Optional custom label for the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full SQL dump backup of the Sorolata database';

    public function handle(DatabaseBackupService $backupService): int
    {
        $this->info('Starting Sorolata Database Backup...');

        try {
            $customName = $this->option('name');
            $backup = $backupService->createBackup($customName);

            $this->info("✓ Backup generated successfully!");
            $this->line("  Filename: {$backup['filename']}");
            $this->line("  Path:     {$backup['path']}");
            $this->line("  Size:     {$backup['size']}");
            $this->line("  Time:     {$backup['created_at']}");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Backup failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
