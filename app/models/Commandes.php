<?php

class Commandes
{
    private PDO $pdo;
    private ?int $id;
    private ?int $vendeur_id;
    private ?int $client_id;
    private ?string $date_commande;
    private string $etat;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?int $vendeur_id = null,
        ?int $client_id = null,
        ?string $date_commande = null,
        string $etat = 'en_cours',
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        if (!in_array($etat, ['en_cours', 'cloturee', 'annulee'])) {
            throw new InvalidArgumentException("variable etat doit etre egale en_cours, cloturee ou annulee.");
        }
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->vendeur_id = (int) $vendeur_id;
        $this->client_id = (int) $client_id;
        $this->date_commande = (string) $date_commande;
        $this->etat = (string) $etat;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function get(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM commandes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->__construct(
                $this->pdo,
                (int) $data['id'],
                (int) $data['vendeur_id'],
                (int) $data['client_id'],
                (string) $data['date_commande'],
                (string) $data['etat'],
                $data['created_at'],
                $data['updated_at']
            );
        }
        return $data;
    }
    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO commandes (vendeur_id, client_id, date_commande, etat) VALUES (:vendeur_id, :client_id, :date_commande, :etat)");

        $isCreated = $stmt->execute([
            'vendeur_id' => $this->vendeur_id,
            'client_id' => $this->client_id,
            'date_commande' => $this->date_commande,
            'etat' => $this->etat
        ]);
        if ($isCreated) {
            $this->id = (int) $this->pdo->lastInsertId();
        }
        return $isCreated;
    }
    public function update(string $new_etat)
    {
        if (!in_array($new_etat, ['en_cours', 'cloturee', 'annulee'])) {
            throw new InvalidArgumentException("variable etat doit etre egale en_cours, cloturee ou annulee.");
        }
        $stmt = $this->pdo->prepare("UPDATE commandes SET etat = :etat, updated_at = CURRENT_TIMESTAMP WHERE id = :id");

        $isUpdated = $stmt->execute([
            'etat' => $new_etat,
            'id' => $this->id
        ]);
        if ($isUpdated) {
            $this->etat = $new_etat;
        }
        return $isUpdated;

    }
    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM commandes WHERE id = :id");

        return $stmt->execute(['id' => $this->id]);
    }
    public static function bestOrdersByPrice(PDO $pdo, int $limit, string $from, string $to)
    {
        $stmt = $pdo->prepare("SELECT 
                                            c.id,
                                            f.montant_total
                                        FROM commandes c
                                        JOIN factures f ON f.commande_id = c.id
                                        WHERE c.etat = 'cloturee'
                                        AND f.created_at BETWEEN :from AND :to
                                        ORDER BY f.montant_total DESC
                                        LIMIT :limit;
                                        ");
        $stmt->bindValue(':from', $from);
        $stmt->bindValue(':to', $to);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}