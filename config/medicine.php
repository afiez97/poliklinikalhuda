<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Medicine Categories
    |--------------------------------------------------------------------------
    |
    | Define the available medicine categories in the system.
    |
    */
    'categories' => [
        'tablet',
        'capsule',
        'syrup',
        'injection',
        'cream',
        'drops',
        'spray',
        'patch',
    ],

    /*
    |--------------------------------------------------------------------------
    | Medicine Category Labels
    |--------------------------------------------------------------------------
    |
    | Human-readable labels for medicine categories.
    |
    */
    'category_labels' => [
        'tablet' => 'Tablet',
        'capsule' => 'Kapsul',
        'syrup' => 'Sirap',
        'injection' => 'Suntikan',
        'cream' => 'Krim',
        'drops' => 'Titisan',
        'spray' => 'Semburan',
        'patch' => 'Tampalan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Medicine Statuses
    |--------------------------------------------------------------------------
    |
    | Define the available medicine statuses in the system.
    |
    */
    'statuses' => [
        'active',
        'inactive',
        'expired',
    ],

    /*
    |--------------------------------------------------------------------------
    | Medicine Status Labels
    |--------------------------------------------------------------------------
    |
    | Human-readable labels for medicine statuses.
    |
    */
    'status_labels' => [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'expired' => 'Luput',
    ],

    /*
    |--------------------------------------------------------------------------
    | Medicine Status Badges
    |--------------------------------------------------------------------------
    |
    | Badge HTML for medicine statuses.
    |
    */
    'status_badges' => [
        'active' => '<span class="badge badge-success">Aktif</span>',
        'inactive' => '<span class="badge badge-secondary">Tidak Aktif</span>',
        'expired' => '<span class="badge badge-danger">Luput</span>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stock Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for stock management.
    |
    */
    'stock' => [
        'low_stock_threshold_days' => 30,
        'expiry_warning_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Medicine Code Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for auto-generating medicine codes.
    |
    */
    'code' => [
        'prefix' => 'MED',
        'length' => 6,
        'pad_string' => '0',
    ],
];
