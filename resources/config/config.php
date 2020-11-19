<?php

$config = [
    'database' => [
        'driver' => 'pgsql',
        'host' => getenv('DB_HOST'),
        'name' => getenv('DB_NAME'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'port' => 5432,
    ],
];

return $config;
