<?php
return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=laminas_dataemunah;host=localhost;charset=utf8',
        'username' => 'root',
        'password' => '',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
        ],
    ],
    'middleware_pipeline' => [
        'authentication' => [
            'middleware' => \Auth\Middleware\AuthMiddleware::class,
            'priority' => 1000,
            'path' => '/', // aplica a todo
        ],
    ],
];