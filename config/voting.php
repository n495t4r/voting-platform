<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Token Configuration
    |--------------------------------------------------------------------------
    */
    'token_ttl_minutes' => env('VOTING_TOKEN_TTL_MINUTES', 1440), // 24 hours
    'default_max_usage' => env('VOTING_DEFAULT_MAX_USAGE', 1),

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'require_otp' => env('VOTING_REQUIRE_OTP', true),
    'otp_ttl_minutes' => env('VOTING_OTP_TTL_MINUTES', 5),
    'max_otp_attempts' => env('VOTING_MAX_OTP_ATTEMPTS', 5),
    'max_voting_attempts' => env('VOTING_MAX_VOTING_ATTEMPTS', 5),
    'voting_rate_limit_minutes' => env('VOTING_RATE_LIMIT_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | Audit Configuration
    |--------------------------------------------------------------------------
    */
    'audit_retention_days' => env('VOTING_AUDIT_RETENTION_DAYS', 2555), // 7 years
    'enable_ip_logging' => env('VOTING_ENABLE_IP_LOGGING', true),
    'enable_user_agent_logging' => env('VOTING_ENABLE_USER_AGENT_LOGGING', true),

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */
    'notify_channel' => env('NOTIFY_CHANNEL', 'email'), // email|sms|whatsapp
    'notification_retry_attempts' => env('NOTIFICATION_RETRY_ATTEMPTS', 3),
    'notification_retry_delay' => env('NOTIFICATION_RETRY_DELAY', 300), // 5 minutes
    
    /*
    |--------------------------------------------------------------------------
    | Feature Flags Default Values
    |--------------------------------------------------------------------------
    */
    'feature_flags' => [
        'allow_multi_use_link' => false,
        'allow_revote_until_close' => false,
        'require_otp' => true,
        'show_live_turnout' => true,
        'enforce_single_device' => false,
        'ballot_anonymization_delay_sec' => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
    'session_timeout_minutes' => env('VOTING_SESSION_TIMEOUT', 120), // 2 hours
    'force_https' => env('VOTING_FORCE_HTTPS', true),
];
