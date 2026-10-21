<?php
// Single place for database credentials. Include this wherever you need data.
// Adjust for your setup (XAMPP default: user "root", empty password).
$dbHost = 'localhost';
$dbName = 'moneymaster';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
        ]
    );
} catch (PDOException $ex) {
    // Log the real error, show a generic one (never leak credentials/details)
    error_log('DB connection failed: ' . $ex->getMessage());
    http_response_code(500);
    exit('Sorry, we are having trouble connecting right now. Please try again later.');
}
