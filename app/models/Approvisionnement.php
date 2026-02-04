<?php

class Approvisionnement
{
    private int $id;
    private int $fournisseur_id;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        int $id,
        int $fournisseur_id,
        string $created_at,
        string $updated_at
    ) {
        $this->id = (int) $id;
        $this->fournisseur_id = (int) $fournisseur_id;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
}