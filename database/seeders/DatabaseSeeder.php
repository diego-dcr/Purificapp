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

        $this->seedBaseData();

        if ($this->shouldSeedDemoData()) {
            $this->call(DemoDataSeeder::class);
        }
    }

    private function seedBaseData(): void
    {
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
    }

    private function shouldSeedDemoData(): bool
    {
        $profile = strtolower((string) env('SEED_PROFILE', 'base'));

        if (in_array($profile, ['demo', 'all'], true)) {
            return true;
        }

        // Backward compatibility with previous toggle.
        return filter_var(env('SEED_DEMO_DATA', false), FILTER_VALIDATE_BOOL);
    }
}
