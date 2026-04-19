<?php
/**
 * db_connect.php — Database connection
 * 
 * Setup instructions:
 * 1. Make sure XAMPP (or WAMP/MAMP) is installed and Apache and MySQL are running.
 * 2. Open phpMyAdmin at (http://localhost/phpmyadmin).
 * 3. Execute the SQL file "database.sql" to create the database and table.
 * 4. Modify the connection details below if they are different from the defaults.
 */

$host     = 'localhost';
$dbname   = 'pawhaven';
$username = 'root';       // Default XAMPP user
$password = '';           // Default XAMPP password (empty)

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Return error in JSON format so the frontend can handle it
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
