<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
$erro_log_path = __DIR__ . '/../storage/logs/error_log.log';
ini_set('error_log', $erro_log_path);
error_reporting(E_ALL);

// header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');