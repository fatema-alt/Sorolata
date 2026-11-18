<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Models\User;
use App\Services\Admin\DatabaseBackupService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DatabaseBackupSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $customerUser;
    protected DatabaseBackupService $backupService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            HeritageCommerceSeeder::class,
            AdminSeeder::class,
        ]);

        $this->adminUser = User::where('role', AdminRole::SUPER_ADMIN)->firstOrFail();

        $this->customerUser = User::create([
            'name' => 'General Customer',
            'email' => 'customer@sorolata.com',
            'phone' => '01719999999',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->backupService = app(DatabaseBackupService::class);
    }

    protected function tearDown(): void
    {
        // Clean up test backup files
        $backupDir = storage_path('app/backups');
        if (File::isDirectory($backupDir)) {
            $files = File::glob($backupDir . '/sorolata_backup_test_*.sql');
            foreach ($files as $f) {
                File::delete($f);
            }
        }

        parent::tearDown();
    }

    public function test_admin_settings_renders_backup_tab_and_stats(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.settings.index', ['tab' => 'backup']));

        $response->assertStatus(200);
        $response->assertSee('Database Backup');
        $response->assertSee('Database Engine');
        $response->assertSee('Stored Backup Archives');
        $response->assertSee('Create Database Backup');
    }

    public function test_admin_can_generate_database_backup_file(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.settings.backup.create'), [
                'custom_name' => 'test_feature_run',
            ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'backup']));
        $response->assertSessionHas('success');

        $backups = $this->backupService->listBackups();
        $this->assertNotEmpty($backups);

        $latest = $backups[0];
        $this->assertStringContainsString('sorolata_backup_test_feature_run', $latest['filename']);
        $this->assertFileExists($latest['path']);
        $this->assertGreaterThan(0, $latest['size_bytes']);

        // Check SQL content contains table structure
        $content = File::get($latest['path']);
        $this->assertStringContainsString('Sorolata', $content);
        $this->assertStringContainsString('DROP TABLE IF EXISTS', $content);
    }

    public function test_admin_can_download_database_backup(): void
    {
        $backup = $this->backupService->createBackup('test_download');

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.settings.backup.download', ['filename' => $backup['filename']]));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', "attachment; filename={$backup['filename']}");
    }

    public function test_admin_can_delete_database_backup(): void
    {
        $backup = $this->backupService->createBackup('test_delete');
        $this->assertFileExists($backup['path']);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.settings.backup.delete', ['filename' => $backup['filename']]));

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'backup']));
        $response->assertSessionHas('success');

        $this->assertFileDoesNotExist($backup['path']);
    }

    public function test_artisan_db_backup_command_executes_successfully(): void
    {
        $this->artisan('db:backup', ['--name' => 'test_artisan_run'])
            ->expectsOutputToContain('Backup generated successfully')
            ->assertExitCode(0);
    }

    public function test_customer_cannot_access_backup_endpoints(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->post(route('admin.settings.backup.create'), ['custom_name' => 'unauthorized']);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_unauthenticated_user_is_redirected_from_backup_endpoints(): void
    {
        $response = $this->post(route('admin.settings.backup.create'));

        $response->assertRedirect(route('admin.login'));
    }
}
