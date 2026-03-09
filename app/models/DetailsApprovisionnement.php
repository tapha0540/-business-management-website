<?php

class DetailsApprovisionnement
{
    private PDO $pdo;
    private int $id;
    private int $approvisionnement_id;
    private int $produit_id;
    private int $quantite;
    private float $prix_achat;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        PDO $pdo,
        int $id,
        int $approvisionnement_id,
        int $produit_id,
        int $quantite,
        int $prix_achat,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->approvisionnement_id = (int) $approvisionnement_id;
        $this->produit_id = (int) $produit_id;
        $this->quantite = (float) $quantite;
        $this->prix_achat = (int) $prix_achat;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }

    public function create()
    {
        $stmt = $this->pdo->prepare('INSERT INTO details_approvisionnement (approvisionnement_id, produit_id, quantite, prix_achat) VALUES (:approvisionnement_id, :produit_id, :quantite, :prix_achat)');

        $success = $stmt->execute([
            ':approvisionnement_id' => $this->approvisionnement_id,
            ':produit_id' => $this->produit_id,
            ':quantite' => $this->quantite,
            ':prix_achat' => $this->prix_achat
        ]);
        $this->id = (int) $this->pdo->lastInsertId();
        return $success;
    }

    public function get(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM details_approvisionnement WHERE id = :id");

        $stmt->execute(['id' => $id]);
        $details = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$details) {
            return null;
        }
        $this->__construct(
            $this->pdo,
            (int) $details['id'],
            (int) $details['approvisionnement_id'],
            (int) $details['produit_id'],
            (float) $details['quantite'],
            (int) $details['prix_achat'],
            (string) $details['created_at'],
            (string) $details['updated_at']
        );
        return $details;
    }
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM details_approvisionnement");

        $stmt->execute();

        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $details;
    }
    public function update($new_quantite, $new_prix_achat)
    {
        $this->quantite = $new_quantite;
        $this->prix_achat = $new_prix_achat;
        $stmt = $this->pdo->prepare('UPDATE details_approvisionnement SET quantite = :quantite, prix_achat = :prix_achat WHERE id = :id');

        return $stmt->execute([
            ':quantite' => $this->quantite,
            ':prix_achat' => $this->prix_achat,
            ':id' => $this->id
        ]);
    }
    public function delete(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM details_approvisionnement WHERE id = :id');

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}