<?php

class Client
{
    private PDO $pdo;
    private int $id;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $telephone;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        PDO $pdo,
        int $id,
        string $prenom,
        string $nom,
        string $email,
        string $telephone,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->prenom = (string) $prenom;
        $this->nom = (string) $nom;
        $this->email = (string) $email;
        $this->telephone = (string) $telephone;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }

    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO clients (prenom, nom, email, telephone) VALUES (:prenom,:nom, :email, :telephone)");
        $isCreated = $stmt->execute([
            "prenom" => $this->prenom,
            "nom" => $this->nom,
            "email" => $this->email,
            "telephone" => $this->telephone
        ]);
        if ($isCreated) {
            $this->id = (int) $this->pdo->lastInsertId();
        }
        return $isCreated;
    }
    /**
     * 
     * 
     * @param int $id
     * @return array{id: int, prenom: string, nom: string, email: string, telephone: string, created_at: string, updated_at: string}
     */
    public function get(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, telephone, created_at, updated_at  FROM clients WHERE id = ?');
        $success = $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Summary of getAll
     * @return array{array{id: int, prenom: string, nom: string, email: string, telephone: string, created_at: string, updated_at: string}}
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, telephone, created_at, updated_at FROM clients');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(string $new_prenom, string $new_nom, string $new_email, string $new_telephone): bool
    {

        $stmt = $this->pdo->prepare('UPDATE clients SET prenom = :prenom, nom = :nom, email = :email, telephone = :telephone WHERE id = :id');
       
        $isUpdated = $stmt->execute([
            'prenom' => $new_prenom,
            'nom' => $new_nom,
            'email' => $new_email,
            'telephone' => $new_telephone,
            'id' => $this->id
        ]);

        if ($isUpdated) {
            $this->prenom = $new_prenom;
            $this->nom = $new_nom;
            $this->email = $new_email;
            $this->telephone = $new_telephone;
        }
        return $isUpdated;
    }

    public function delete(): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM clients WHERE id = ?');

        return $stmt->execute([$this->id]);
    }
}