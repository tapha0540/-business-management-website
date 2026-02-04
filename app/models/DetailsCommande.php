<?php

class DetailsCommande
{
    private int $id;
    private int $commande_id;
    private int $produit_id;
    private int $quantite;
    private int $prix_vente;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $commande_id,
        int $produit_id,
        int $quantite,
        int $prix_vente,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int)$id;
        $this->commande_id = (int)$commande_id;
        $this->produit_id = (int)$produit_id;
        $this->quantite = (int)$quantite;
        $this->prix_vente = (float)$prix_vente;
        $this->created_at = (string)$created_at;
        $this->updated_at = (string)$updated_at;
    }
}