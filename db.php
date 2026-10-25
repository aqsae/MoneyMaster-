<?php

require_once __DIR__ . '/settings.php';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$sql_db;charset=utf8mb4",
        $user,
        $pwd,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $ex) {
    error_log('Database connection failed: ' . $ex->getMessage());
    http_response_code(500);
    exit('Sorry, we are having trouble connecting right now. Please try again later.');
}
