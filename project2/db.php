
<?php

// Load the database settings
require_once __DIR__ . '/settings.php';

try {
    // Connect to the database
    $pdo = new PDO(
        "mysql:host=$host;dbname=$sql_db;charset=utf8mb4",
        $user,
        $pwd,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Catch database errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Get results as arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
        ]
    );

} catch (PDOException $ex) {
    // Log the error without showing details to users
    error_log('Database connection failed: ' . $ex->getMessage());

    http_response_code(500);
    exit('Sorry, we are having trouble connecting right now. Please try again later.');
}
