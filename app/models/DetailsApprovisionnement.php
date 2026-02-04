<?php

class DetailsApprovisionnement
{
    private int $id;
    private int $approvisionnement_id;
    private int $produit_id;
    private int $quantite;
    private float $prix_achat;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        int $id,
        int $approvisionnement_id,
        int $produit_id,
        int $quantite,
        int $prix_achat,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int) $id;
        $this->approvisionnement_id = (int) $approvisionnement_id;
        $this->produit_id = (int) $produit_id;
        $this->quantite = (float) $quantite;
        $this->prix_achat = (int) $prix_achat;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
}