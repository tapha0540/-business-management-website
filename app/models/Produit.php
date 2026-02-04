<?php

class Produit
{
    public int $id;
    public string $nom;
    public string $description;
    public string $imgUrl;
    public int $categorie_id;
    public int $prix_vente;
    public int $quantite;
    public int $seuil_critique;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        string $nom,
        string $description,
        string $imgUrl,
        int $categorie_id,
        int $prix_vente,
        int $quantite,
        int $seuil_critique,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int)$id;
        $this->nom = (string)$nom;
        $this->description = (string)$description;
        $this->imgUrl = (string)$imgUrl;
        $this->categorie_id = (int)$categorie_id;
        $this->prix_vente = (float)$prix_vente;
        $this->quantite = (int)$quantite;
        $this->seuil_critique = (int)$seuil_critique;
        $this->created_at = (string)$created_at;
        $this->updated_at = (string)$updated_at;
    }
}