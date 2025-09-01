<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'name' => 'Civil Engineering Class of 2014 Alumni Association',
            'slug' => 'ce-2014-alumni',
            'description' => 'Official alumni association for Civil Engineering Class of 2014',
        ]);

        Organization::create([
            'name' => 'Demo Organization',
            'slug' => 'demo-org',
            'description' => 'Demo organization for testing purposes',
        ]);
    }
}
