<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'op_manager', 'delivery', 'operation'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        $admin = User::updateOrCreate([
            'email' => 'admin.ddcr@water',
        ], [
            'name' => 'Admin',
            'username' => 'admin.ddcr',
            'password' => '@D13g0',
            'email_verified_at' => now(),
        ]);

        $admin->syncRoles(['admin']);

        $this->call(ConceptSeeder::class);

        if (filter_var(env('SEED_DEMO_DATA', false), FILTER_VALIDATE_BOOL)) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
