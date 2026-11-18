<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Models\ActivityLog;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CommerceEngineSeeder;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminAuthSecuritySuiteTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            CommerceEngineSeeder::class,
            AdminSeeder::class,
        ]);

        $this->adminUser = User::where('email', 'admin@sorolata.com')->firstOrFail();

        $this->customerUser = User::create([
            'name' => 'Regular Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => AdminRole::CUSTOMER,
            'phone' => '01799887766',
        ]);
    }

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_page_renders_with_brand_and_fields(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Sorolata Studio');
        $response->assertSee('Staff Email', false);
        $response->assertSee('Password', false);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@sorolata.com',
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);

        // Verify activity log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'admin.login.success',
        ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@sorolata.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_role_cannot_access_admin_panel(): void
    {
        // 1. Trying to login via admin login form
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'customer@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        // 2. Direct attempt with active customer session
        $directResponse = $this->actingAs($this->customerUser)->get(route('admin.dashboard'));
        $directResponse->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_rate_limiting_protects_against_brute_force(): void
    {
        RateLimiter::clear('admin_login_admin@sorolata.com|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.submit'), [
                'email' => 'admin@sorolata.com',
                'password' => 'wrong-pass',
            ]);
        }

        // 6th attempt should be blocked by rate limiter
        $lockedResponse = $this->post(route('admin.login.submit'), [
            'email' => 'admin@sorolata.com',
            'password' => 'password', // Even if correct password
        ]);

        $lockedResponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_logout_and_session_is_invalidated(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_admin_can_update_profile_and_password(): void
    {
        $profileResponse = $this->actingAs($this->adminUser)->get(route('admin.profile'));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Curator Credentials');

        $updateResponse = $this->actingAs($this->adminUser)->put(route('admin.profile.update'), [
            'name' => 'Kazi Farhan Updated',
            'phone' => '+8801700999999',
            'current_password' => 'password',
            'new_password' => 'new-secure-password',
            'new_password_confirmation' => 'new-secure-password',
        ]);

        $updateResponse->assertRedirect();
        $this->adminUser->refresh();
        $this->assertEquals('Kazi Farhan Updated', $this->adminUser->name);
        $this->assertTrue(Hash::check('new-secure-password', $this->adminUser->password));
    }

    public function test_builder_routes_are_protected_by_admin_middleware(): void
    {
        $guestResponse = $this->get('/admin/builder/page/1');
        $guestResponse->assertRedirect(route('admin.login'));

        $adminResponse = $this->actingAs($this->adminUser)->get('/admin/builder/page/1');
        $adminResponse->assertStatus(200);
    }
}
