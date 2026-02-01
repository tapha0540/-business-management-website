<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_commerce";
$dbport = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;port=$dbport",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("\n Database connection failed: " . $e->getMessage(), 3, 'C:\Users\DELL\Dev\php\projet_final\storage\logs\error_log.log');
}