<?php
return [
    'host' => 'db', // Имя сервиса из docker-compose.yml
    'dbname' => 'myapp', // Должно совпадать с MYSQL_DATABASE
    'user' => 'user', // Должно совпадать с MYSQL_USER
    'password' => 'password', // Должно совпадать с MYSQL_PASSWORD
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];