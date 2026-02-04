<?php

class Client
{
    private int $id;
    private string $nom;
    private string $email;
    private string $telephone;
    private string $adresse;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $nom,
        string $email,
        string $telephone,
        string $adresse,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int) $id;
        $this->nom = (string) $nom;
        $this->email = (string) $email;
        $this->telephone = (string) $telephone;
        $this->adresse = (string) $adresse;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
}