<?php

class Fournisseur
{
    private PDO $pdo;
    private int $id;
    private string $nom;
    private string $email;
    private string $telephone;
    private string $adresse;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        PDO $pdo,
        int $id,
        string $nom,
        string $email,
        string $telephone,
        string $adresse,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->nom = (string) $nom;
        $this->email = (string) $email;
        $this->telephone = (string) $telephone;
        $this->adresse = (string) $adresse;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO fournisseurs (nom, email, telephone, adresse)
            VALUES (:nom, :email, :telephone, :adresse)
        ");

        $isCreated = $stmt->execute([
            "nom" => $this->nom,
            "email" => $this->email,
            "telephone" => $this->telephone,
            "adresse" => $this->adresse
        ]);
        $this->id = (int) $this->pdo->lastInsertId();
        return $isCreated;
    }
    public function get(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT id, nom, email, telephone, adresse, created_at, updated_at FROM fournisseurs WHERE id = ?');
        if (!$stmt->execute([$id])) {
            return null;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->__construct(
            $this->pdo,
            (int) $id,
            (string) $row['nom'],
            (string) $row['email'],
            (string) $row['telephone'],
            (string) $row['adresse'],
            (string) $row['created_at'],
            (string) $row['updated_at']
        );
        return $row;
    }
    public function getAll()
    {
        $stmt = $this->pdo->prepare('SELECT id, nom, email, telephone, adresse, created_at, updated_at FROM fournisseurs');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllWithSearch(PDO &$pdo, int $limit, string $search): array
    {
        $filter = "";
        $searchNotEmpty = $search && trim($search);

        if ($searchNotEmpty) {
            $filter = "WHERE nom LIKE :search OR email LIKE :search";
        }

        $sql = "SELECT id, nom, email, telephone, adresse, created_at, updated_at FROM fournisseurs
            $filter
            ORDER BY created_at DESC
            LIMIT :limit";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        if ($searchNotEmpty) {
            $searchValue = "%" . trim($search) . "%";
            $stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(
        string $new_nom,
        string $new_email,
        string $new_telephone,
        string $new_adresse
    ) {
        $stmt = $this->pdo->prepare("UPDATE fournisseurs SET 
            nom = :nom,
            email = :email,
            telephone = :telephone,
            adresse = :adresse
            WHERE id = :id
        ");
        return $stmt->execute([
            "nom" => $new_nom,
            "email" => $new_email,
            "telephone" => $new_telephone,
            "adresse" => $new_adresse,
            "id" => $this->id
        ]);
    }
    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM fournisseurs WHERE id = :id");
        return $stmt->execute(["id" => $this->id]);
    }
}