<?php
// app/Config/db.php
return [
    'host' => 'db',          // Имя сервиса из docker-compose
    'dbname' => 'hotel_db',  // Название БД
    'user' => 'user',        // Пользователь
    'pass' => 'root'         // Пароль (должен совпадать с docker-compose.yml)
];