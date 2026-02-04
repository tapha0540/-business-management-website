<?php

class Commandes
{
    private int $id;
    private int $vendeur_id;
    private int $client_id;
    private string $date_commande;
    private string $etat;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $vendeur_id,
        int $client_id,
        string $date_commande,
        string $etat,
        string $created_at,
        string $updated_at
    ) {
        if (!$id) {
            throw new InvalidArgumentException("Commande_id ne doit pas etre null.");
        }
        if (!in_array($etat, ['en_cours', 'cloturee', 'annulee'])) {
            throw new InvalidArgumentException("variable etat doit etre egale en_cours, cloturee ou annulee.");
        }
        $this->id = (int) $id;
        $this->vendeur_id = (int) $vendeur_id;
        $this->client_id = (int) $client_id;
        $this->date_commande = (string) $date_commande;
        $this->etat = (string) $etat;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
}