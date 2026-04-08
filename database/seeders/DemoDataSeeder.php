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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application's database with demo business data.
     */
    public function run(): void
    {
        $hasIncomesTable = Schema::hasTable('incomes');
        $hasExpensesTable = Schema::hasTable('expenses');
        $salesHasCreatedBy = Schema::hasColumn('sales', 'created_by');
        $salesHasTimestamp = Schema::hasColumn('sales', 'timestamp');
        $incomesHasCreatedBy = $hasIncomesTable && Schema::hasColumn('incomes', 'created_by');
        $incomesHasTimestamp = $hasIncomesTable && Schema::hasColumn('incomes', 'timestamp');
        $incomesHasCustomerId = $hasIncomesTable && Schema::hasColumn('incomes', 'customer_id');
        $incomesHasUserId = $hasIncomesTable && Schema::hasColumn('incomes', 'user_id');
        $expensesHasCreatedBy = $hasExpensesTable && Schema::hasColumn('expenses', 'created_by');
        $expensesHasTimestamp = $hasExpensesTable && Schema::hasColumn('expenses', 'timestamp');

        $manager = User::query()->firstOrCreate(
            ['email' => 'manager.demo@water'],
            [
                'name' => 'Gerente Demo',
                'username' => 'manager.demo',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $deliveryUsers = collect();
        for ($i = 1; $i <= 5; $i++) {
            $deliveryUsers->push(
                User::query()->updateOrCreate(
                    ['email' => 'delivery.demo' . $i . '@water'],
                    [
                        'name' => 'Repartidor Demo ' . $i,
                        'username' => 'delivery.demo' . $i,
                        'password' => 'password',
                        'email_verified_at' => now(),
                    ],
                )
            );
        }

        $routes = collect();
        foreach ($deliveryUsers as $index => $deliveryUser) {
            $routes->push(
                Route::query()->updateOrCreate([
                    'code' => sprintf('R%03d', $index + 1),
                ], [
                    'user_id' => $deliveryUser->id,
                    'name' => 'Ruta ' . ($index + 1),
                    'zone' => 'Zona ' . ($index + 1),
                    'code' => sprintf('R%03d', $index + 1),
                ])
            );
        }

        $customers = collect();
        foreach ($routes as $route) {
            for ($i = 1; $i <= 20; $i++) {
                $customerNumber = 'R' . $route->id . 'C' . str_pad((string) $i, 3, '0', STR_PAD_LEFT);
                $customer = Customer::query()->updateOrCreate(
                    ['number' => $customerNumber],
                    [
                        'route_id' => $route->id,
                        'barcode' => '7' . str_pad((string) ($route->id * 1000 + $i), 12, '0', STR_PAD_LEFT),
                        'number' => $customerNumber,
                        'name' => 'Cliente Demo ' . $route->id . '-' . $i,
                    ],
                );

                $customers->push($customer);
            }
        }

        // Keep concept catalog balanced for both income and expense entries.
        if (! Concept::query()->where('type', Concept::TYPE_EXPENSE)->exists()) {
            for ($i = 1; $i <= 8; $i++) {
                Concept::query()->updateOrCreate(
                    ['code' => 'E' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                    [
                        'name' => 'Egreso demo ' . $i,
                        'type' => Concept::TYPE_EXPENSE,
                        'allows_carboy' => false,
                    ],
                );
            }
        }

        if (! Concept::query()->where('type', Concept::TYPE_INCOME)->exists()) {
            for ($i = 1; $i <= 8; $i++) {
                Concept::query()->updateOrCreate(
                    ['code' => 'I' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                    [
                        'name' => 'Ingreso demo ' . $i,
                        'type' => Concept::TYPE_INCOME,
                        'allows_carboy' => false,
                    ],
                );
            }
        }

        $incomeConceptIds = Concept::query()->where('type', Concept::TYPE_INCOME)->pluck('id')->all();
        $expenseConceptIds = Concept::query()->where('type', Concept::TYPE_EXPENSE)->pluck('id')->all();

        $customersByRoute = $customers->groupBy('route_id');

        foreach ($routes as $route) {
            $routeCustomers = $customersByRoute->get($route->id, collect());

            if ($routeCustomers->isEmpty()) {
                continue;
            }

            for ($i = 1; $i <= 80; $i++) {
                $customer = $routeCustomers->random();

                $saleData = [
                    'user_id' => $route->user_id,
                    'route_id' => $route->id,
                    'customer_id' => $customer->id,
                    'cost' => random_int(30, 1500),
                    'concept_id' => $incomeConceptIds[array_rand($incomeConceptIds)],
                    'external_id' => 'S-' . Str::upper(Str::random(16)),
                    'latitude' => (string) (19 + (random_int(0, 350000) / 1000000)),
                    'longitude' => (string) (-99 - (random_int(0, 350000) / 1000000)),
                ];

                if ($salesHasCreatedBy) {
                    $saleData['created_by'] = $manager->id;
                }

                if ($salesHasTimestamp) {
                    $saleData['timestamp'] = now()->subDays(random_int(0, 90))->subMinutes(random_int(0, 1440));
                }

                Sale::query()->create($saleData);
            }
        }

        if ($hasIncomesTable) {
            for ($i = 1; $i <= 50; $i++) {
                $incomeData = [
                    'concept_id' => $incomeConceptIds[array_rand($incomeConceptIds)],
                    'amount' => random_int(20, 5000),
                    'description' => 'Ingreso demo ' . $i,
                ];

                if ($incomesHasCustomerId) {
                    $incomeData['customer_id'] = random_int(1, 100) <= 75 ? $customers->random()->id : null;
                }

                if ($incomesHasUserId) {
                    $incomeData['user_id'] = random_int(1, 100) <= 70 ? $deliveryUsers->random()->id : null;
                }

                if ($incomesHasCreatedBy) {
                    $incomeData['created_by'] = $manager->id;
                }

                if ($incomesHasTimestamp) {
                    $incomeData['timestamp'] = now()->subDays(random_int(0, 90))->subMinutes(random_int(0, 1440));
                }

                Income::query()->create($incomeData);
            }
        }

        if ($hasExpensesTable) {
            for ($i = 1; $i <= 60; $i++) {
                $expenseData = [
                    'concept_id' => $expenseConceptIds[array_rand($expenseConceptIds)],
                    'amount' => random_int(20, 3000),
                    'description' => 'Egreso demo ' . $i,
                ];

                if ($expensesHasCreatedBy) {
                    $expenseData['created_by'] = $manager->id;
                }

                if ($expensesHasTimestamp) {
                    $expenseData['timestamp'] = now()->subDays(random_int(0, 90))->subMinutes(random_int(0, 1440));
                }

                Expense::query()->create($expenseData);
            }
        }
    }
}
