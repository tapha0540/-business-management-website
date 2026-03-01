<?php

require_once '../../config/app.php';
require_once __DIR__ . '/../../controllers/ClientsController.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Client = new ClientsController($pdo);
    $Client->getAll();
}