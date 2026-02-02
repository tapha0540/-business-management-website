<?php

class CommandesController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCommande(int $client_id, int $vendeur_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO commandes(client_id, vendeur_id) VALUES (?, ?)");
        $isCommandeCreated = $stmt->execute([$client_id, $vendeur_id,]);

        return [
            "message" => $isCommandeCreated ? "Commande créée avec succès" : "Échec de la création de la commande",
            "success" => $isCommandeCreated
        ];
    }
    public function cancelCommande(int $commandeId)
    {
        $stmt = $this->pdo->prepare("UPDATE commandes SET etat='Annulée' WHERE id=?");
        $isCommandeCancelled = $stmt->execute([$commandeId]);

        return [
            "message" => $isCommandeCancelled ? "Commande annulée avec succès" : "Échec de l'annulation de la commande",
            "success" => $isCommandeCancelled
        ];
    }
    public function colutereeCommande(int $commandeId)
    {
        $stmt = $this->pdo->prepare("UPDATE commandes SET etat='Clôturée' WHERE id=?");
        $isCommandeConfirmed = $stmt->execute([$commandeId]);

        return [
            "message" => $isCommandeConfirmed ? "Commande confirmée avec succès" : "Échec de la confirmation de la commande",
            "success" => $isCommandeConfirmed
        ];
    }
}