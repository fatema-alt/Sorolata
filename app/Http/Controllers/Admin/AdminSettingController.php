<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ActivityLogService;
use App\Services\Admin\AdminSettingService;
use App\Services\Admin\DatabaseBackupService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminSettingController extends Controller
{
    public function __construct(
        protected AdminSettingService $settingService,
        protected ActivityLogService $activityLog,
        protected DatabaseBackupService $backupService
    ) {}

    public function index(Request $request): View
    {
        $settings = $this->settingService->all();
        $backupStats = $this->backupService->getStats();
        $backups = $this->backupService->listBackups();

        return view('admin.settings.index', [
            'settings' => $settings,
            'backupStats' => $backupStats,
            'backups' => $backups,
            'activeTab' => $request->query('tab', 'general'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $val) {
            $group = str_contains($key, 'payment_') ? 'payment' : (str_contains($key, 'shipping_') ? 'shipping' : 'general');
            $this->settingService->set($key, $val, $group);
        }

        $this->activityLog->log(
            action: 'settings.updated',
            description: 'Updated store configuration parameters'
        );

        return back()->with('success', 'Storefront settings updated successfully.');
    }

    /**
     * Generate a new database backup.
     */
    public function createBackup(Request $request): RedirectResponse|BinaryFileResponse
    {
        try {
            $backup = $this->backupService->createBackup($request->input('custom_name'));

            if ($request->boolean('download_now')) {
                return $this->backupService->download($backup['filename']);
            }

            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('success', "Database backup created successfully: {$backup['filename']} ({$backup['size']})");
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download an existing backup file.
     */
    public function downloadBackup(string $filename): BinaryFileResponse|RedirectResponse
    {
        try {
            return $this->backupService->download($filename);
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an existing backup file.
     */
    public function deleteBackup(string $filename): RedirectResponse
    {
        try {
            $this->backupService->delete($filename);

            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('success', "Database backup file '{$filename}' was deleted successfully.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('error', $e->getMessage());
        }
    }
}
