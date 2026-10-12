<?php
return [
    'default'      => 'mysql',
    'connections'  => [
        'mysql' => [
            'type'            => 'mysql',
            'hostname'        => env('database.hostname', '127.0.0.1'),
            'database'        => env('database.database', 'entweb'),
            'username'        => env('database.username', 'root'),
            'password'        => env('database.password', ''),
            'hostport'        => env('database.hostport', '3306'),
            'dsn'             => '',
            'params'          => [],
            'charset'         => env('database.charset', 'utf8mb4'),
            'prefix'          => env('database.prefix', 'lh_'),
            'deploy'          => 0,
            'rw_separate'     => false,
            'master_num'      => 1,
            'slave_no'        => '',
            'fields_strict'   => true,
            'break_reconnect' => false,
            'break_match_str' => [],
        ],
    ],
    'fields_cache' => false,
    'trigger_sql'  => true,
    'debug'        => true,
];