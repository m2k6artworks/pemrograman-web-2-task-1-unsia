<?php
/**
 * Database Configuration
 * 
 * This file contains database connection settings for both local development
 * and shared hosting environments. The environment is set in env.php.
 */

// Check if env.php exists and include it
$envFile = __DIR__ . '/env.php';
if (file_exists($envFile)) {
    require_once $envFile;
} else {
    // Default environment if env.php doesn't exist
    $environment = 'local';
}

// Database configurations
$config = [
    'local' => [
        'host' => 'localhost',
        'dbname' => 'universitas',
        'username' => 'root',
        'password' => ''
    ],
    'production' => [
        'host' => 'sql106.infinityfree.com',
        'dbname' => 'if0_38946734_universitas', // assuming database name needs a prefix
        'username' => 'if0_38946734',
        'password' => '7iREsA56dWpr2a'
    ]
];

// Select configuration based on environment
$selectedConfig = $config[$environment];

// Extract configuration variables
$host = $selectedConfig['host'];
$dbname = $selectedConfig['dbname'];
$username = $selectedConfig['username'];
$password = $selectedConfig['password'];

try {
    if ($environment === 'local') {
        // For local environment, create database if it doesn't exist
        $conn = new PDO("mysql:host=$host", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if it doesn't exist (only works on local environment)
        $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $conn->exec("USE `$dbname`");
    } else {
        // For production environment, connect directly to the existing database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Create mahasiswa table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS `mahasiswa` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `nama` VARCHAR(255) NOT NULL,
        `nim` VARCHAR(255) NOT NULL,
        `jenis_kelamin` ENUM('laki-laki', 'perempuan') NOT NULL,
        `kelas` VARCHAR(255) NOT NULL,
        `program_studi` VARCHAR(255) NOT NULL,
        `angkatan` VARCHAR(255) NOT NULL
    )";
    $conn->exec($sql);
    
} catch(PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please check your configuration or try again later.");
}
?> 