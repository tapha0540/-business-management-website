<?php

class DetailsCommandeController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function createDetailsCommandes(int $commande_id, array $produits)
    {
        try {
            $results = [];
            foreach ($produits as $produit) {
                if (!is_array($produit) || !isset($produit['produit_id'], $produit['quantite'], $produit['prix_vente'])) {
                    throw new InvalidArgumentException("Produit invalide dans la commande");
                }
                $result = $this->create($commande_id, $produit['produit_id'], $produit['quantite'], $produit['prix_vente']);
                $results[] = $result;
            }
            $allCreated = array_reduce($results, fn($carry, $item) => $carry && $item, true);
            return [
                "message" => $allCreated ? "Détails de la commande créés avec succès" : "Échec de la création des détails de la commande",
                "success" => $allCreated
            ];
        } catch (Throwable $e) {
            return [
                "message" => "Erreur lors de la creation des details de la commande",
                "success" => false,
            ];
        }
    }
    private function create(int $commande_id, int $produit_id, int $quantite, float $prix_vente)
    {
        $stmt = $this->pdo->prepare("INSERT INTO details_commandes(commande_id, produit_id, quantite, prix_vente) VALUES (?, ?, ?, ?)");
        $isDetailsCreated = $stmt->execute([$commande_id, $produit_id, $quantite, $prix_vente]);

        return $isDetailsCreated;
    }
    public function updateDetailsCommande(int $commande_id, int $produit_id, int $quantite, float $prix_vente)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE details_commandes SET quantite = ?, prix_vente = ? WHERE commande_id = ? AND produit_id = ?");
            $isDetailsUpdated = $stmt->execute([$quantite, $prix_vente, $commande_id, $produit_id]);

            return [
                "message" => $isDetailsUpdated ? "Détails de la commande mis à jour avec succès" : "Échec de la mise à jour des détails de la commande",
                "success" => $isDetailsUpdated
            ];
        } catch (Throwable $e) {
            return [
                "message" => "Erreur lors de la mise a jour des details de la commande",
                "success" => false,
            ];
        }
    }
    public function deleteDetailsCommande(int $id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM details_commandes WHERE id = ?");
            $isDetailsDeleted = $stmt->execute([$id]);
            return [
                "message" => $isDetailsDeleted ? "Détails de la commande supprimés avec succès" : "Échec de la suppression des détails de la commande",
                "success" => $isDetailsDeleted
            ];
        } catch (Throwable $e) {
            return [
                "message" => "Erreur lors de la suppression des details de la commande",
                "success" => false,
            ];
        }
    }
}