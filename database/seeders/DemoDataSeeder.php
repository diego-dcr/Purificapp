<?php

namespace Database\Seeders;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Route;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application's database with demo business data.
     */
    public function run(): void
    {
        $manager = User::query()->firstOrCreate(
            ['email' => 'manager.demo@water'],
            [
                'name' => 'Gerente Demo',
                'username' => 'manager.demo',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $deliveryUsers = User::factory()->count(5)->create();

        $routes = collect();
        foreach ($deliveryUsers as $index => $deliveryUser) {
            $routes->push(
                Route::factory()->create([
                    'user_id' => $deliveryUser->id,
                    'name' => 'Ruta ' . ($index + 1),
                    'code' => sprintf('R%03d', $index + 1),
                ])
            );
        }

        $customers = collect();
        foreach ($routes as $route) {
            $customers = $customers->concat(
                Customer::factory()->count(20)->create(['route_id' => $route->id])
            );
        }

        // Keep concept catalog balanced for both income and expense entries.
        if (! Concept::query()->where('type', Concept::TYPE_EXPENSE)->exists()) {
            Concept::factory()->count(8)->create(['type' => Concept::TYPE_EXPENSE]);
        }

        if (! Concept::query()->where('type', Concept::TYPE_INCOME)->exists()) {
            Concept::factory()->count(8)->create(['type' => Concept::TYPE_INCOME]);
        }

        $customersByRoute = $customers->groupBy('route_id');

        foreach ($routes as $route) {
            $routeCustomers = $customersByRoute->get($route->id, collect());

            if ($routeCustomers->isEmpty()) {
                continue;
            }

            Sale::factory()->count(80)->create([
                'user_id' => $route->user_id,
                'route_id' => $route->id,
                'created_by' => $manager->id,
                'customer_id' => fn () => $routeCustomers->random()->id,
            ]);
        }

        Income::factory()->count(50)->create([
            'created_by' => $manager->id,
            'customer_id' => fn () => random_int(1, 100) <= 75 ? $customers->random()->id : null,
            'user_id' => fn () => random_int(1, 100) <= 70 ? $deliveryUsers->random()->id : null,
        ]);

        Expense::factory()->count(60)->create([
            'created_by' => $manager->id,
        ]);
    }
}
