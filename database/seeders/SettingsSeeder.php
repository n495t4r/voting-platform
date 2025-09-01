<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'platform_name',
                'value' => ['name' => 'Online Voting Platform'],
                'description' => 'Name of the voting platform',
            ],
            [
                'key' => 'default_token_ttl',
                'value' => ['minutes' => 1440], // 24 hours
                'description' => 'Default token time-to-live in minutes',
            ],
            [
                'key' => 'max_voting_attempts',
                'value' => ['attempts' => 5],
                'description' => 'Maximum voting attempts per IP per time window',
            ],
            [
                'key' => 'voting_rate_limit_minutes',
                'value' => ['minutes' => 15],
                'description' => 'Rate limit time window in minutes',
            ],
            [
                'key' => 'notification_settings',
                'value' => [
                    'default_channel' => 'email',
                    'retry_attempts' => 3,
                    'retry_delay_minutes' => 5,
                ],
                'description' => 'Default notification settings',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
