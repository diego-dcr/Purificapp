<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_auth_returns_expected_shape(): void
    {
        $role = Role::findOrCreate('driver', 'web');

        $user = User::factory()->create([
            'username' => 'repartidor.demo',
            'password' => 'secret123',
        ]);
        $user->assignRole($role);

        Route::query()->create([
            'user_id' => $user->id,
            'name' => 'Ruta Demo',
            'zone' => 'Centro',
            'code' => 'p',
        ]);

        $response = $this->postJson('/api/mobile/auth/login', [
            'username' => 'repartidor.demo',
            'password' => 'secret123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('authenticated', true)
            ->assertJsonPath('user.usuario', 'repartidor.demo')
            ->assertJsonPath('user.rol', 'driver')
            ->assertJsonPath('user.ruta', 'p');
    }

    public function test_mobile_input_endpoint_is_idempotent_by_external_id(): void
    {
        $role = Role::findOrCreate('driver', 'web');

        $user = User::factory()->create([
            'username' => 'driver.sync',
        ]);
        $user->assignRole($role);

        $route = Route::query()->create([
            'user_id' => $user->id,
            'name' => 'Ruta Norte',
            'zone' => 'Norte',
            'code' => 'n1',
        ]);

        $customer = Customer::query()->create([
            'route_id' => $route->id,
            'barcode' => '7501005123456',
            'number' => '1001',
            'name' => 'Cliente Demo',
        ]);

        $concept = Concept::query()->create([
            'name' => 'Entrega',
            'code' => '40',
        ]);

        $payload = [
            'id' => 'delivery-123',
            'codigo_usuario' => (string) $user->id,
            'ruta' => $route->code,
            'codigo_cliente' => $customer->barcode,
            'no_cliente' => $customer->number,
            'codigo_concepto' => $concept->code,
            'concepto' => '40-Entrega',
            'monto' => 125,
            'codigos_garrafones' => ['GAR-001', 'GAR-002'],
            'latitud' => '19.4326',
            'longitud' => '-99.1332',
        ];

        $this->postJson('/api/mobile/inputs', $payload)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/mobile/inputs', $payload)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Entrega previamente registrada');

        $this->assertDatabaseCount('inputs', 1);
        $this->assertDatabaseCount('carboy_sales', 2);
    }
}