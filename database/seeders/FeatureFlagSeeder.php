<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flags = [
            [
                'key' => 'allow_multi_use_link',
                'value' => ['enabled' => false],
                'scope' => 'global',
                'description' => 'Allow voters to use their voting link multiple times',
            ],
            [
                'key' => 'allow_revote_until_close',
                'value' => ['enabled' => false],
                'scope' => 'global',
                'description' => 'Allow voters to change their vote until election closes',
            ],
            [
                'key' => 'require_otp',
                'value' => ['enabled' => true],
                'scope' => 'global',
                'description' => 'Require OTP verification for voting',
            ],
            [
                'key' => 'show_live_turnout',
                'value' => ['enabled' => true],
                'scope' => 'global',
                'description' => 'Show live turnout statistics to admins',
            ],
            [
                'key' => 'enforce_single_device',
                'value' => ['enabled' => false],
                'scope' => 'global',
                'description' => 'Restrict voting to a single device per voter',
            ],
        ];

        foreach ($flags as $flag) {
            FeatureFlag::updateOrCreate(
                [
                    'key' => $flag['key'],
                    'scope' => $flag['scope'],
                    'election_id' => null,
                ],
                $flag
            );
        }
    }
}
