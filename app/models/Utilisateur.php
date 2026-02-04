<?php

class Utilisateur
{
    private PDO $pdo;
    private int $id;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $mot_de_passe;
    private string $role;
    private string|null $created_at;
    private string|null $updated_at;
    public function __construct(
        PDO $pdo,
        int $id,
        string|null $prenom = null,
        string|null $nom = null,
        string $email,
        string|null $mot_de_passe = null,
        string $role,
        string|null $created_at = null,
        string|null $updated_at = null
    ) {
        if ($role != 'admin' && $role != 'vendeur') {
            throw new InvalidArgumentException('Variable role doit etre egal a admin ou vendeur');
        }
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->prenom = (string) $prenom;
        $this->nom = (string) $nom;
        $this->email = (string) $email;
        $this->mot_de_passe = (string) $mot_de_passe;
        $this->role = (string) $role;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }

    public function create(): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO utilisateurs(prenom, nom, email, mot_de_passe, role) VALUES (:prenom, :nom, :email, :mot_de_passe, :role)');

        
        return $stmt->execute([
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'email' => $this->email,
            'mot_de_passe' => password_hash($this->mot_de_passe, PASSWORD_BCRYPT),
            'role' => $this->role
        ]);
    }
    /**
     * Summary of 
     * @return null|array{id: int, prenom: string, nom: string, email: string, mot_de_passe: string, role: string, created_at: string, updated_at: string}
     */
    public function get(int $id): array|null
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, mot_de_passe, role, created_at, updated_at FROM utilisateurs WHERE id = ?');
        if (!$stmt->execute([$id])) {
            return null;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->__construct(
            $this->pdo,
            (int) $id,
            (string) $row['prenom'],
            (string) $row['nom'],
            (string) $row['email'],
            (string) $row['mot_de_passe'],
            (string) $row['role'],
            (string) $row['created_at'],
            (string) $row['updated_at']
        );
        return $row;
    }
    /**
     * Summary of 
     * @return array<array{id: int, prenom: string, nom: string, email: string, mot_de_passe: string, role: string, created_at: string, updated_at: string}>
     */
    public function getAll()
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, mot_de_passe, role, created_at, updated_at FROM utilisateurs');
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(string $new_prenom, string $new_nom, string $new_email, string $new_mot_de_passe)
    {
        $stmt = $this->pdo->prepare('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :email, mot_de_passe = :mot_de_passe WHERE id = :id');

        $this->__construct(
            $this->pdo,
            $this->id,
            $new_prenom,
            $new_nom,
            $new_email,
            $new_mot_de_passe,
            $this->role,
            $this->created_at,
            $this->updated_at
        );

        return $stmt->execute([
            'prenom' => $new_prenom,
            'nom' => $new_nom,
            'email' => $new_email,
            'mot_de_passe' => password_hash($new_mot_de_passe, PASSWORD_BCRYPT),
            'id' => $this->id
        ]);
    }
    public function delete()
    {
        $stmt = $this->pdo->prepare('DELETE FROM utilisateurs WHERE id = :id');

        return $stmt->execute(['id' => $this->id]);
    }
}