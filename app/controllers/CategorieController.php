<?php

require_once __DIR__ . '/../models/Category.php';

class CategorieController {
     private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllCategories() {
        return Category::getAll($this->pdo);
    }
}
