<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.test',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@toko.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_supplier_can_login_and_redirect_to_supplier_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Supplier',
            'email' => 'supplier@toko.test',
            'password' => bcrypt('supplier123'),
            'role' => 'supplier',
        ]);

        $response = $this->post('/login', [
            'email' => 'supplier@toko.test',
            'password' => 'supplier123',
        ]);

        $response->assertRedirect(route('supplier.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_kepala_gudang_can_login_and_redirect_to_gudang_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Kepala Gudang',
            'email' => 'gudang@toko.test',
            'password' => bcrypt('gudang123'),
            'role' => 'kepala_gudang',
        ]);

        $response = $this->post('/login', [
            'email' => 'gudang@toko.test',
            'password' => 'gudang123',
        ]);

        $response->assertRedirect(route('gudang.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_costumer_can_login_and_redirect_to_customer_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Costumer',
            'email' => 'costumer@toko.test',
            'password' => bcrypt('costumer123'),
            'role' => 'costumer',
        ]);

        $response = $this->post('/login', [
            'email' => 'costumer@toko.test',
            'password' => 'costumer123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_dashboard_layout_files_exist_for_role_based_sidebar(): void
    {
        $this->assertFileExists(resource_path('views/layouts/dashboard.blade.php'));
        $this->assertFileExists(resource_path('views/layouts/partials/sidebar.blade.php'));
        $this->assertFileExists(resource_path('views/layouts/partials/topbar.blade.php'));
        $this->assertFileExists(resource_path('views/layouts/partials/footer.blade.php'));
    }
}
