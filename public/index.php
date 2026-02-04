<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /html/auth/signin.php');
    exit;
}
$user = $_SESSION['user'];
header('Location: /html/user/dashboard.php');