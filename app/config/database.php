<?php

$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "gestion_commerciale";
$dbport = 3307;

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;port=$dbport",
        $username,
        $dbpassword
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("\n Database connection failed: " . $e->getMessage(), 3, 'C:\Users\DELL\Dev\php\projet_final\app\storage\logs\error_log.log');
}