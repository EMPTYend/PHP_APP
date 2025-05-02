<?php

$host = 'db';
$db   = 'hotel_db';
$user = 'user';
$pass = 'secret';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
