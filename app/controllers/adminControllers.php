<?php

class AdminController extends UserController {
    private $pdo;
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->pdo = $pdo;
    }
    public function deleteAUser($userId) {
        return $this->deleteUser($userId);
    }
}