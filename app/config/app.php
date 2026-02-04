<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'C:\Users\DELL\Dev\php\projet_final\storage\logs\error_log.log');
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
$erro_log_path = 'C:\Users\DELL\Dev\php\projet_final\app\storage\logs\error_log.log';