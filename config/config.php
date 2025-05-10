<?php
$host = 'localhost';
$dbname = 'universitas';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $conn->exec("USE `$dbname`");
    
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
    die("Database connection failed: " . $e->getMessage());
}
?> 