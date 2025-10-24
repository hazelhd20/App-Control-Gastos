<?php

return [
    'app' => [
        'name' => 'Control de Gastos Personales',
        'url' => 'http://localhost/App-Control-Gastos/public',
        'timezone' => 'America/Mexico_City',
        'locale' => 'es_MX',
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'control_gastos',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'mail' => [
        'from' => [
            'address' => 'no-reply@controlgastos.local',
            'name' => 'Control de Gastos',
        ],
        'driver' => 'log', // log | smtp
        'log_path' => __DIR__ . '/../storage/emails',
        'smtp' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => '',
            'password' => '',
            'encryption' => 'tls',
        ],
    ],
    'security' => [
        'password_cost' => 12,
        'reset_token_lifetime' => 300, // 5 minutes
    ],
];
