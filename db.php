<?php
$host = 'localhost';
$dbname = 'harigopinathfi26';
$username = 'root';
$password = ''; 

try {
    // Connect to local MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Graceful error handling if DB goes offline
    die("Database connection failed: " . $e->getMessage());
}
?>
