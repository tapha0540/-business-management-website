<?php

class DetailsCommande
{
    private PDO $pdo;
    private int $id;
    private int $commande_id;
    private int $produit_id;
    private int $quantite;
    private int $prix_vente;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        PDO $pdo,
        int $id,
        int $commande_id,
        int $produit_id,
        int $quantite,
        int $prix_vente,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->commande_id = (int) $commande_id;
        $this->produit_id = (int) $produit_id;
        $this->quantite = (int) $quantite;
        $this->prix_vente = (float) $prix_vente;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function create(PDO $pdo)
    {
        $stmt = $pdo->prepare('INSERT INTO details_commande (commande_id, produit_id, quantite, prix_vente) VALUES (:commande_id, :produit_id, :quantite, :prix_vente)');

        $success = $stmt->execute([
            ':commande_id' => $this->commande_id,
            ':produit_id' => $this->produit_id,
            ':quantite' => $this->quantite,
            ':prix_vente' => $this->prix_vente
        ]);
        $this->id = (int) $this->pdo->lastInsertId();

        return $success;
    }
    public function get()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM details_commande WHERE id = :id');

        $stmt->execute([':id' => $this->id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->__construct(
            $this->pdo,
            (int) $row['id'],
            (int) $row['commande_id'],
            (int) $row['produit_id'],
            (int) $row['quantite'],
            (float) $row['prix_vente'],
            (string) $row['created_at'],
            (string) $row['updated_at']
        );
        return $row;
    }
    public function update($new_quantite, $new_prix_vente)
    {
        $this->quantite = $new_quantite;
        $this->prix_vente = $new_prix_vente;
        $stmt = $this->pdo->prepare('UPDATE details_commande SET quantite = :quantite, prix_vente = :prix_vente WHERE id = :id');

        return $stmt->execute([
            ':quantite' => $this->quantite,
            ':prix_vente' => $this->prix_vente,
            ':id' => $this->id
        ]);
    }
    public function delete(PDO $pdo)
    {
        $stmt = $pdo->prepare('DELETE FROM details_commande WHERE id = :id');

        return $stmt->execute([
            ':id' => $this->id
        ]);
    }
    public static function getByCommandeId(PDO $pdo, int $commande_id)
    {


        $stmt = $pdo->prepare('SELECT 
                                        details_commande.produit_id,
                                        produits.nom AS produit,
                                        details_commande.quantite,
                                        details_commande.prix_vente
                                        FROM details_commande
                                        JOIN produits ON produits.id = details_commande.produit_id
                                        WHERE details_commande.commande_id = :commande_id');

        $stmt->execute([':commande_id' => $commande_id]);

        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $details;
    }
    /**
     * Getters pour accéder aux propriétés privées
     */
    public function getId(): int
    {
        return $this->id;
    }
    public function getCommandeId(): int
    {
        return $this->commande_id;
    }
    public function getProduitId(): int
    {
        return $this->produit_id;
    }
    public function getQuantite(): int
    {
        return $this->quantite;
    }
    public function getPrixVente(): float
    {
        return $this->prix_vente;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
}


