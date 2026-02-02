<?php

class DetailsApprovisionnementController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Summary of createDetailsApprovisionnements
     * @param int $approvisionnementId
     * @param array{array{product_id: int, quantite: int, prix_achat: float}} $detailsApprovisionnements
     * @return array{message: string, success: bool}
     */
    public function createDetailsApprovisionnements(int $approvisionnementId, array $detailsApprovisionnements)
    {
        foreach ($detailsApprovisionnements as $detail) {
            $this->createDetail(
                $approvisionnementId,
                $detail['product_id'],
                $detail['quantite'],
                $detail['prix_achat']
            );
        }
        return [
            "message" => "Détails d'approvisionnement créés avec succès",
            "success" => true
        ];
    }
    private function createDetail(int $approvisionnement_id, int $product_id, int $quantity, float $prix_achat, )
    {
        if (empty($approvisionnement_id) || empty($product_id) || empty($quantity) || empty($prix_achat)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO details_approvisionnements (approvisionnement_id, product_id, quantity, prix_achat) VALUES (?, ?, ?, ?)");
        $isDetailsCreated = $stmt->execute([$approvisionnement_id, $product_id, $quantity, $prix_achat]);
        return [
            "message" => $isDetailsCreated ? "Détail d'approvisionnement créé avec succès" : "Échec de la création du détail d'approvisionnement",
            "success" => $isDetailsCreated
        ];
    }
    public function getDetailsByApprovisionnementId(int $approvisionnement_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM details_approvisionnements WHERE approvisionnement_id = ?");
        $stmt->execute([$approvisionnement_id]);
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "message" => !empty($details) ? "Détails récupérés avec succès" : "Aucun détail trouvé pour cet approvisionnement",
            "success" => !empty($details),
            "details" => $details
        ];
    }
}