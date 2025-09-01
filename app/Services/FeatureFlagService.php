<?php

namespace App\Services;

use App\Models\Election;
use App\Models\FeatureFlag;
use App\Models\Setting;

class FeatureFlagService
{
    /**
     * Check if a feature flag is enabled.
     */
    public function isEnabled(string $key, ?Election $election = null): bool
    {
        // Check election-specific flag first
        if ($election) {
            $electionFlag = FeatureFlag::where('key', $key)
                ->where('scope', 'election')
                ->where('election_id', $election->id)
                ->first();

            if ($electionFlag) {
                return $electionFlag->isEnabled();
            }
        }

        // Check global flag
        $globalFlag = FeatureFlag::where('key', $key)
            ->where('scope', 'global')
            ->first();

        if ($globalFlag) {
            return $globalFlag->isEnabled();
        }

        // Return default values for known flags
        return $this->getDefaultValue($key);
    }

    /**
     * Get feature flag value (not just boolean).
     */
    public function getValue(string $key, ?Election $election = null, mixed $default = null): mixed
    {
        // Check election-specific flag first
        if ($election) {
            $electionFlag = FeatureFlag::where('key', $key)
                ->where('scope', 'election')
                ->where('election_id', $election->id)
                ->first();

            if ($electionFlag) {
                return $electionFlag->value;
            }
        }

        // Check global flag
        $globalFlag = FeatureFlag::where('key', $key)
            ->where('scope', 'global')
            ->first();

        if ($globalFlag) {
            return $globalFlag->value;
        }

        return $default ?? $this->getDefaultValue($key);
    }

    /**
     * Set a feature flag value.
     */
    public function set(string $key, mixed $value, ?Election $election = null, ?string $description = null): void
    {
        $scope = $election ? 'election' : 'global';
        $electionId = $election?->id;

        FeatureFlag::updateOrCreate(
            [
                'key' => $key,
                'scope' => $scope,
                'election_id' => $electionId,
            ],
            [
                'value' => is_array($value) ? $value : ['enabled' => (bool) $value],
                'description' => $description,
            ]
        );
    }

    /**
     * Get default values for known feature flags.
     */
    private function getDefaultValue(string $key): mixed
    {
        return match ($key) {
            'allow_multi_use_link' => false,
            'allow_revote_until_close' => false,
            'require_otp' => true,
            'show_live_turnout' => true,
            'enforce_single_device' => false,
            'otp_channel' => 'email',
            'ballot_anonymization_delay_sec' => 0,
            default => false,
        };
    }
}
