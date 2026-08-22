<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthAndRBACTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('admin@artisanwood.sa');
    }

    public function test_super_admin_can_login_and_access_dashboard(): void
    {
        $user = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->post('/admin/login', [
            'email' => 'admin@artisanwood.sa',
            'password' => 'admin123456',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->actingAs($user)->get('/admin');
        $dashboardResponse->assertStatus(200);
    }

    public function test_super_admin_can_access_roles_and_users_management(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $rolesResponse = $this->actingAs($superAdmin)->get('/admin/roles');
        $rolesResponse->assertStatus(200);

        $usersResponse = $this->actingAs($superAdmin)->get('/admin/users');
        $usersResponse->assertStatus(200);
    }

    public function test_content_manager_cannot_access_users_management_due_to_rbac(): void
    {
        $editor = User::where('email', 'editor@artisanwood.sa')->first();

        // Editor does NOT have users.manage permission
        $response = $this->actingAs($editor)->get('/admin/users');
        $response->assertRedirect('/admin');
        $response->assertSessionHas('error');
    }

    public function test_dynamic_role_creation_and_permission_assignment(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->actingAs($superAdmin)->post('/admin/roles', [
            'name_ar' => 'مشرف فرعي للاختبار',
            'name_en' => 'Test Sub Admin',
            'name' => 'test_sub_admin',
            'description_ar' => 'وصف اختباري',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect('/admin/roles');
        $this->assertDatabaseHas('roles', ['name' => 'test_sub_admin']);
    }

    public function test_language_switcher_changes_session_locale(): void
    {
        $response = $this->get('/lang/en');
        $response->assertSessionHas('locale', 'en');

        $responseAr = $this->get('/lang/ar');
        $responseAr->assertSessionHas('locale', 'ar');
    }
}
