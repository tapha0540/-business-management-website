<?php
class FactureController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function createFacture(int $commande_id, float $montant_total)
    {
        $stmt = $this->pdo->prepare("INSERT INTO factures (commande_id, montant_total) VALUES (?, ?)");
        $isCreated = $stmt->execute([$commande_id, $montant_total]);
        return [
            "message" => $isCreated ? "Facture créée avec succès" : "Échec de la création de la facture",
            "success" => $isCreated
        ];
    }
    public function getFactureByCommandeId(int|null $id = null, int|null $commande_id = null)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM factures WHERE commande_id = ?");
        $stmt->execute([$commande_id]);
        $facture = $stmt->fetch(PDO::FETCH_ASSOC);
        return $facture;
    }
    public function updateFacture(int $commande_id, float $montant_total)
    {
        $stmt = $this->pdo->prepare("UPDATE factures SET montant_total = ? WHERE commande_id = ?");
        $isUpdated = $stmt->execute([$montant_total, $commande_id]);
        return [
            "message" => $isUpdated ? "Facture mise à jour avec succès" : "Échec de la mise à jour de la facture",
            "success" => $isUpdated
        ];
    }
    public function deleteFacture(int|null $id = null, int|null $commande_id = null)
    {
        if ($id !== null) {
            $stmt = $this->pdo->prepare("DELETE FROM factures WHERE id = ?");
            $isDeleted = $stmt->execute([$id]);
        } elseif ($commande_id !== null) {
            $stmt = $this->pdo->prepare("DELETE FROM factures WHERE commande_id = ?");
            $isDeleted = $stmt->execute([$commande_id]);
        } else {
            return [
                "message" => "ID de la facture ou ID de la commande requis pour la suppression",
                "success" => false
            ];
        }
        return [
            "message" => $isDeleted ? "Facture supprimée avec succès" : "Échec de la suppression de la facture",
            "success" => $isDeleted
        ];

    }
}