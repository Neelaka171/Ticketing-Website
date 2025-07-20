<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'ticketing_system';

try {
    // Connect to MySQL server (without selecting DB)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");

    // Connect to the newly created database
    $pdo->exec("USE `$db`");

    // Create users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user'
    )");

} catch (PDOException $e) {
    die("DB Setup Failed: " . $e->getMessage());
}
?>
