<?php

class Utilisateur
{
    private int $id;
    private string|null $nom;
    private string|null $email;
    private string|null $mot_de_passe;
    private string $role;
    private string|null $created_at;
    private string|null $updated_at;
    public function __construct(
        int $id,
        string|null $nom = null,
        string $email,
        string|null $mot_de_passe = null,
        string $role,
        string|null $created_at = null,
        string|null $updated_at = null
    ) {
        if (!$id) {
            throw new InvalidArgumentException("Utilisateur id ne doit pas etre null.");
        }
        if ($role != 'admin' && $role != 'vendeur') {
            throw new InvalidArgumentException('Variable role doit etre egal a admin ou vendeur');
        }

        $this->id = (int) $id;
        $this->nom = $nom ? (string) $nom : null;
        $this->email = $email ? (string) $email : null;
        $this->mot_de_passe = $mot_de_passe ?  (string) $mot_de_passe : null;
        $this->role = (string) $role;
        $this->created_at = $created_at ? (string) $created_at : null;
        $this->updated_at = $updated_at ? (string) $updated_at : null;
    }
    
}