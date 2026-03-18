<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_users_page(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_cannot_access_users_page(): void
    {
        Role::create(['name' => 'operation', 'guard_name' => 'web']);

        $user = User::factory()->create();
        $user->assignRole('operation');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_admin_users_can_access_users_page(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertOk();
        $response->assertSee('Usuarios registrados');
    }
}
