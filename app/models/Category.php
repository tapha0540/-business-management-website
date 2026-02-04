<?php

class Category
{
    private int $id;
    private string $nom;
    private string $created_at;
    private string $updated_at;

    public function __construct(int $id, string $nom, string $created_at, string $updated_at)
    {
        $this->id = (int) $id;
        $this->nom = (string) $nom;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
}