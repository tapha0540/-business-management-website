<?php

class Facture
{
    private PDO $pdo;
    private int $id;
    private int $commande_id;
    private float $montant_total;
    private string $created_at;
    private string $updated_at;
    public function __construct(
        PDO $pdo,
        int $id = 0,
        int $commande_id = 0,
        float $montant_total = 0,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->montant_total = (float) $montant_total;
        $this->commande_id = (int) $commande_id;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }
    public function create()
    {
        $stmt = $this->pdo->prepare('INSERT INTO factures (commande_id, montant_total) VALUES (:commande_id, :montant_total)');

        $stmt->bindParam(':commande_id', $this->commande_id, PDO::PARAM_INT);
        $stmt->bindParam(':montant_total', $this->montant_total, PDO::PARAM_STR);

        $success = $stmt->execute();
        $this->id = (int) $this->pdo->lastInsertId();
        return $success;
    }

    public static function getByCommandeId(PDO $pdo, int $commande_id): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM factures WHERE commande_id = :commande_id ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':commande_id', $commande_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return null;
        }

        $facture = $stmt->fetch(PDO::FETCH_ASSOC);
        return $facture ?: null;
    }

    public static function calculateMontantTotal(PDO $pdo, int $commande_id): float
    {
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(quantite * prix_vente), 0) AS montant_total FROM details_commande WHERE commande_id = :commande_id");
        $stmt->bindValue(':commande_id', $commande_id, PDO::PARAM_INT);
        $stmt->execute();

        return (float) ($stmt->fetch(PDO::FETCH_ASSOC)['montant_total'] ?? 0);
    }

    public static function createOrUpdateForCommande(PDO $pdo, int $commande_id): array
    {
        $montantTotal = self::calculateMontantTotal($pdo, $commande_id);
        $facture = self::getByCommandeId($pdo, $commande_id);

        if ($facture) {
            $factureModel = new self(
                $pdo,
                (int) $facture['id'],
                (int) $facture['commande_id'],
                (float) $facture['montant_total'],
                (string) $facture['created_at'],
                (string) $facture['updated_at']
            );
            $factureModel->update($montantTotal);
            $facture['montant_total'] = $montantTotal;
            $facture['created'] = false;
            return $facture;
        }

        $factureModel = new self($pdo, 0, $commande_id, $montantTotal);
        $factureModel->create();

        return [
            'id' => $pdo->lastInsertId(),
            'commande_id' => $commande_id,
            'montant_total' => $montantTotal,
            'created' => true
        ];
    }
    /**
     * Summary of get
     * @param int $id
     * @return null|array{id: int, commande_id: int, montant_total: float, created_at: string, updated_at: string}
     */
    public function get(int $id): array|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM factures WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return null;
        }

        $facture = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$facture) {
            return null;
        }

        $this->__construct(
            $this->pdo,
            (int) $facture['id'],
            (int) $facture['commande_id'],
            (float) $facture['montant_total'],
            (string) $facture['created_at'],
            (string) $facture['updated_at']
        );

        return $facture;
    }
    /**
     * Summary of getAll
     * @return null|array{id: int, commande_id: int, montant_total: float, created_at: string, updated_at: string}[]
     */
    public function getAll(): array|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM factures");

        if (!$stmt->execute()) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(
        float $montant_total,
    ) {
        $stmt = $this->pdo->prepare("UPDATE factures SET montant_total = :montant_total WHERE id = :id");

        return $stmt->execute([
            ':montant_total' => $montant_total,
            ':id' => $this->id,
        ]);
    }
    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM factures WHERE id = :id");

        return $stmt->execute([':id' => $this->id]);
    }

    static public function getMonthlyRevenues(PDO &$pdo) {
        $stmt = $pdo->prepare("SELECT 
                                        DATE_FORMAT(f.created_at, '%Y-%m') AS mois_annee,
                                        MONTHNAME(f.created_at) AS mois_nom,
                                        SUM(f.montant_total) AS chiffre_affaire
                                    FROM factures f
                                    WHERE f.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                                    GROUP BY mois_annee
                                    ORDER BY mois_annee;
                                    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
