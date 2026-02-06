<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /html/auth/signin.html');
    exit;
}
$user = $_SESSION['user'];
header('Location: /html/user/dashboard.php');

