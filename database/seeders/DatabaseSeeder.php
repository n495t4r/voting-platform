<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // OrganizationSeeder::class,
            // FeatureFlagSeeder::class,
            // SettingsSeeder::class,
        ]);

        // Create default super admin if none exists
        if (!User::where('role', 'super_admin')->exists()) {
            User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@voting.local',
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]);

            $this->command->info('Default super admin created: admin@voting.local');
        }
    }
}
