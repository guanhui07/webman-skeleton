<?php

return [
    'enable' => true,

    //
    'migrations' => [
        'table_storage' => [
            'table_name' => 'migrations',
            'version_column_name' => 'version',
            'version_column_length' => 1024,
            'executed_at_column_name' => 'executed_at',
            'execution_time_column_name' => 'execution_time',
        ],

        'migrations_paths' => [
            'database\migrations' => base_path() . '/database/migrations',
        ],

        'all_or_nothing' => true,
        'transactional' => true,
        'check_database_platform' => true,
        'organize_migrations' => 'none',
        'connection' => null,
        'em' => null,
    ],
    'migrations_db' => [
        'driver' => call_user_func(function (string $driver) {
            $arr = [
                'mysql' => 'pdo_mysql',
            ];
            return $arr[$driver];
        }, env('db_driver', 'mysql')),
        'host' => env('db_host', '127.0.0.1'),
        'dbname' => env('db_database', 'test'),
        'user' => env('db_username', 'root'),
        'password' => env('db_password', 'root'),
    ],
];
