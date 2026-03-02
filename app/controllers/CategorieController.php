<?php

require_once __DIR__ . '/../models/Category.php';

class CategorieController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data)
    {
        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;
        if (!$nom) {
            throw new Exception('Nom de catégorie requis');
        }
        $category = new Category($this->pdo, 0, $nom, $description, '', '');
        return $category->create();
    }

    public function get(int $id)
    {
        return (new Category($this->pdo, 0, '', '', '', ''))->get($id);
    }

    public function getAllCategories()
    {
        return Category::getAll($this->pdo);
    }

    public function update(int $id, array $data)
    {
        $new_nom = $data['nom'] ?? null;
        $new_description = $data['description'] ?? null;
        if (!$new_nom) {
            throw new Exception('Nom de catégorie requis');
        }
        $cat = new Category($this->pdo, $id, $new_nom, $new_description, '', '');
        return $cat->update($id, $new_nom, $new_description);
    }

    public function delete(int $id)
    {
        $cat = new Category($this->pdo, $id, '', '', '', '');
        return $cat->delete($id);
    }
}
