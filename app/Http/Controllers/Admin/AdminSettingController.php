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
        // 1. Process Logo File Uploads
        $logoFields = [
            'store_logo_file' => 'store_logo',
            'manual_bkash_logo_file' => 'manual_bkash_logo',
            'manual_nagad_logo_file' => 'manual_nagad_logo',
            'manual_brac_logo_file' => 'manual_brac_logo',
            'manual_dbbl_logo_file' => 'manual_dbbl_logo',
            'sslcommerz_logo_file' => 'sslcommerz_logo',
        ];

        foreach ($logoFields as $fileInput => $settingKey) {
            if ($request->hasFile($fileInput) && $request->file($fileInput)->isValid()) {
                $file = $request->file($fileInput);
                $ext = $file->getClientOriginalExtension() ?: 'png';
                $prefix = str_replace('_logo', '', $settingKey);
                $filename = $prefix . '_' . time() . '.' . $ext;

                $targetSubdir = str_contains($settingKey, 'store') ? 'images' : 'images/payments';
                $destPath = public_path($targetSubdir);
                if (! is_dir($destPath)) {
                    @mkdir($destPath, 0755, true);
                }
                $file->move($destPath, $filename);

                $webPath = '/' . $targetSubdir . '/' . $filename;
                $group = str_contains($settingKey, 'store') ? 'general' : 'payment';
                $this->settingService->set($settingKey, $webPath, $group);

                // Redundant copy to storage for resilience
                try {
                    $storageDir = storage_path('app/public/' . $targetSubdir);
                    if (! is_dir($storageDir)) {
                        @mkdir($storageDir, 0755, true);
                    }
                    @copy($destPath . '/' . $filename, $storageDir . '/' . $filename);
                } catch (\Throwable) {}
            }
        }

        // 2. Process Boolean Checkbox Toggles
        $toggles = [
            'manual_bkash_enabled',
            'manual_nagad_enabled',
            'manual_brac_enabled',
            'manual_dbbl_enabled',
            'payment_cod_enabled',
            'payment_sslcommerz_enabled',
            'sms_enabled',
            'floating_contact_enabled',
        ];

        foreach ($toggles as $toggle) {
            $group = str_contains($toggle, 'sms_') ? 'general' : 'payment';
            $this->settingService->set($toggle, $request->has($toggle) ? '1' : '0', $group);
        }

        // 3. Process Standard Text Inputs
        $excludedKeys = array_merge(['_token', '_method'], array_keys($logoFields), $toggles);
        $inputs = $request->except($excludedKeys);

        foreach ($inputs as $key => $val) {
            if (is_null($val)) {
                continue;
            }
            $group = (str_contains($key, 'payment_') || str_contains($key, 'manual_') || str_contains($key, 'sslcommerz_') || str_contains($key, 'cod_'))
                ? 'payment' 
                : (str_contains($key, 'shipping_') || str_contains($key, 'steadfast_') ? 'shipping' : 'general');
            
            $this->settingService->set($key, is_array($val) ? json_encode($val) : (string) $val, $group);
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
