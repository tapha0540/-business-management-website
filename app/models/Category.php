<?php

class Category
{
    private PDO $pdo;
    private int $id;
    private string $nom;
    private string $description;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        PDO $pdo,
        int $id,
        string $nom,
        string $description,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->nom = (string) $nom;
        $this->description = (string) $description;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nom, description) VALUES (:nom, :description)");

        $isCreated = $stmt->execute([
            "nom" => $this->nom,
            "description" => $this->description
        ]);
        if ($isCreated) {
            $this->id = (int) $this->pdo->lastInsertId();
        }
        return $isCreated;
    }
    /**
     * 
     * @param int $id
     * @return array{id: int, nom: string, description: string, created_at: string, updated_at: string}
     */
    public function get(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT id, nom, description, created_at, updated_at FROM categories WHERE id = ?');
        $success = $stmt->execute([$id]);

        if ($success) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update(
        int $id,
        string $new_nom,
        string $new_description
    ) {
        $stmt = $this->pdo->prepare("UPDATE categories SET nom = :nom WHERE id = :id");

        $isUpdated = $stmt->execute([
            "nom" => $new_nom,
            "id" => $id
        ]);
        if ($isUpdated) {
            $this->nom = (string) $new_nom;
            $this->description = (string) $new_description;
        }
        return $isUpdated;
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}