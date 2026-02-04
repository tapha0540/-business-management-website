<?php

class Facture
{
    private int $id;
    private int $commande_id;
    private float $montant_total;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        int $id,
        int $commande_id,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int) $id;
        $this->commande_id = (int) $commande_id;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }

}