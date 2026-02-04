<?php

class Approvisionnement
{
    private PDO $pdo;
    private int $id;
    private int $fournisseur_id;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        PDO $pdo,
        int $id,
        int $fournisseur_id,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->fournisseur_id = (int) $fournisseur_id;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO approvisionnements (fournisseur_id) VALUES (:fournisseur_id)");

        $stmt->bindParam(':fournisseur_id', $this->fournisseur_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id = (int) $this->pdo->lastInsertId();
            return true;
        }
        return false;
    }
    public function get(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->id = (int) $data['id'];
            $this->fournisseur_id = (int) $data['fournisseur_id'];
            $this->created_at = (string) $data['created_at'];
            $this->updated_at = (string) $data['updated_at'];
        }
        return $data;
    }
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM approvisionnements WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}