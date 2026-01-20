<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'history_count' => 5, // Cannot reuse last 5 passwords
        'expiry_days' => 90, // Password expires after 90 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Login & Brute Force Protection
    |--------------------------------------------------------------------------
    */
    'login' => [
        'max_attempts' => 5,
        'lockout_minutes' => 30,
        'remember_me_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Management
    |--------------------------------------------------------------------------
    */
    'session' => [
        'single_session' => true, // Only one active session per user
        'idle_timeout_minutes' => 30,
        'absolute_timeout_hours' => 12, // Max session duration
    ],

    /*
    |--------------------------------------------------------------------------
    | MFA Settings
    |--------------------------------------------------------------------------
    */
    'mfa' => [
        'enabled' => true,
        'required_roles' => ['super-admin', 'admin'], // Roles that must use MFA
        'totp_window' => 1, // Accept codes 30 seconds before/after
        'recovery_codes_count' => 10,
        'trusted_device_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist
    |--------------------------------------------------------------------------
    */
    'ip_whitelist' => [
        'enabled' => false, // Enable IP whitelist for admin
        'admin_only' => true, // Only apply to admin roles
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Log
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'enabled' => true,
        'retention_years' => 7, // PDPA requirement
        'log_logins' => true,
        'log_crud' => true,
        'log_exports' => true,
        'log_sensitive_access' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => true,
        'schedule' => '0 2 * * *', // Daily at 2 AM
        'retention_days_local' => 30,
        'retention_days_cloud' => 90,
        'encryption_enabled' => true,
        'encryption_algorithm' => 'AES-256-CBC',
        'disks' => ['local', 's3'], // Backup destinations
    ],

    /*
    |--------------------------------------------------------------------------
    | User Statuses
    |--------------------------------------------------------------------------
    */
    'user_statuses' => ['active', 'inactive', 'suspended', 'pending'],

    'user_status_labels' => [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'suspended' => 'Digantung',
        'pending' => 'Menunggu',
    ],

    'user_status_badges' => [
        'active' => '<span class="badge bg-success">Aktif</span>',
        'inactive' => '<span class="badge bg-secondary">Tidak Aktif</span>',
        'suspended' => '<span class="badge bg-danger">Digantung</span>',
        'pending' => '<span class="badge bg-warning">Menunggu</span>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Action Badges
    |--------------------------------------------------------------------------
    */
    'audit_action_badges' => [
        'create' => '<span class="badge bg-success">Cipta</span>',
        'update' => '<span class="badge bg-info">Kemaskini</span>',
        'delete' => '<span class="badge bg-danger">Padam</span>',
        'login' => '<span class="badge bg-primary">Log Masuk</span>',
        'logout' => '<span class="badge bg-secondary">Log Keluar</span>',
        'export' => '<span class="badge bg-warning">Eksport</span>',
        'import' => '<span class="badge bg-warning">Import</span>',
        'view' => '<span class="badge bg-light text-dark">Lihat</span>',
        'approve' => '<span class="badge bg-success">Lulus</span>',
        'reject' => '<span class="badge bg-danger">Tolak</span>',
        'failed_login' => '<span class="badge bg-danger">Gagal Log Masuk</span>',
        'password_reset' => '<span class="badge bg-info">Reset Kata Laluan</span>',
        'mfa_enabled' => '<span class="badge bg-success">MFA Diaktifkan</span>',
        'mfa_disabled' => '<span class="badge bg-warning">MFA Dinyahaktifkan</span>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Roles
    |--------------------------------------------------------------------------
    */
    'default_roles' => [
        [
            'name' => 'super-admin',
            'display_name' => 'Super Admin',
            'description' => 'Akses penuh ke semua fungsi sistem',
        ],
        [
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Pengurusan sistem dengan akses terhad',
        ],
        [
            'name' => 'doktor',
            'display_name' => 'Doktor',
            'description' => 'Akses modul klinikal dan EMR',
        ],
        [
            'name' => 'jururawat',
            'display_name' => 'Jururawat',
            'description' => 'Akses pendaftaran, vital signs, dan bantuan klinikal',
        ],
        [
            'name' => 'kerani',
            'display_name' => 'Kerani',
            'description' => 'Akses pendaftaran, temujanji, dan billing',
        ],
        [
            'name' => 'farmasi',
            'display_name' => 'Farmasi',
            'description' => 'Akses modul farmasi dan dispensing',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Permissions
    |--------------------------------------------------------------------------
    */
    'default_permissions' => [
        // User Management
        'users.view' => 'Lihat Pengguna',
        'users.create' => 'Tambah Pengguna',
        'users.update' => 'Kemaskini Pengguna',
        'users.delete' => 'Padam Pengguna',
        'users.import' => 'Import Pengguna',
        'users.export' => 'Eksport Pengguna',

        // Role Management
        'roles.view' => 'Lihat Peranan',
        'roles.create' => 'Tambah Peranan',
        'roles.update' => 'Kemaskini Peranan',
        'roles.delete' => 'Padam Peranan',

        // Permission Management
        'permissions.manage' => 'Urus Kebenaran',

        // Audit Log
        'audit.view' => 'Lihat Audit Log',
        'audit.export' => 'Eksport Audit Log',

        // Settings
        'settings.view' => 'Lihat Tetapan',
        'settings.update' => 'Kemaskini Tetapan',

        // Backup
        'backup.view' => 'Lihat Backup',
        'backup.create' => 'Cipta Backup',
        'backup.restore' => 'Pulihkan Backup',
        'backup.delete' => 'Padam Backup',

        // Session Management
        'sessions.view' => 'Lihat Sesi',
        'sessions.terminate' => 'Tamatkan Sesi',

        // MFA Management
        'mfa.reset' => 'Reset MFA Pengguna',
    ],
];
