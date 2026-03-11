<?php

require_once __DIR__ . '/../models/Commandes.php';
require_once __DIR__ . '/../models/DetailsCommande.php';
require_once __DIR__ . '/../models/Produit.php';

class CommandeController
{
    private PDO $pdo;
    private Commandes $commandeModel;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->commandeModel = new Commandes($this->pdo);
    }

    public function getAll(int $limit, string $search = '')
    {
        return $this->commandeModel->getAll($limit, $search);
    }

    public function get(int $id)
    {
        return $this->commandeModel->get($id);
    }

    public function create(array $data)
    {
        $vendeur_id = $data['vendeur_id'] ?? null;
        $client_id = $data['client_id'] ?? null;
        $date_commande = $data['date_commande'] ?? null;
        $etat = $data['etat'] ?? 'en_cours';
        if (!$vendeur_id || !$client_id) {
            throw new Exception('vendeur_id et client_id requis');
        }
        $cmd = new Commandes($this->pdo, null, $vendeur_id, $client_id, $etat);
        return $cmd->create();
    }

    public function update(int $id, array $data)
    {
        $cmd = new Commandes($this->pdo, $id);

        $new_client_id = $data['client_id'] ?? null;
        $details = $data['details'] ?? null;

        if ($new_client_id !== null || $details !== null) {
            if (!$new_client_id || !is_array($details)) {
                throw new Exception('client_id et details requis');
            }
            return $cmd->updateClientAndDetails((int) $new_client_id, $details);
        }

        $new_etat = $data['etat'] ?? null;
        if (!$new_etat) {
            throw new Exception('etat requis');
        }

        return $cmd->update($new_etat);
    }

    public function delete(int $id)
    {
        $cmd = new Commandes($this->pdo, $id);
        return $cmd->delete();
    }

    /**
     * Créer une commande avec ses détails et décrémenter les stocks
     * @param int $vendeur_id ID du vendeur
     * @param int $client_id ID du client
     * @param string $date_commande Date de la commande
     * @param array $details Tableau de détails : [{'produit_id': int, 'quantite': int, 'prix_vente': float}, ...]
     * @return array ['success' => bool, 'commande_id' => int]
     * @throws Exception si validation échoue
     */
    public function createWithDetails(int $vendeur_id, int $client_id, array $details)
    {
        if (!$vendeur_id || !$client_id) {
            throw new Exception('vendeur_id et client_id requis');
        }

        if (empty($details)) {
            throw new Exception('Au moins un produit requis');
        }

        try {
            $this->pdo->beginTransaction();

            // Vérifier stocks disponibles AVANT de créer la commande
            foreach ($details as $detail) {
                $produit_id = $detail['produit_id'] ?? null;
                $quantite = $detail['quantite'] ?? null;

                if (!$produit_id || !$quantite) {
                    throw new Exception('Données de détail invalides (produit_id et quantite requis)');
                }

                $produit = new Produit($this->pdo);
                $produitData = $produit->get($produit_id);

                if (!$produitData) {
                    throw new Exception("Produit avec ID {$produit_id} non trouvé");
                }

                if ($produitData['quantite'] < $quantite) {
                    throw new Exception("Stock insuffisant pour le produit '{$produitData['nom']}' (disponible: {$produitData['quantite']}, demandé: {$quantite})");
                }
            }

            // Créer la commande
            $cmd = new Commandes($this->pdo, null, $vendeur_id, $client_id, 'en_cours');
            $cmd->create();
            $commande_id = $this->pdo->lastInsertId();

            // Créer les détails et décrémenter stocks
            foreach ($details as $detail) {
                $produit_id = $detail['produit_id'] ?? null;
                $quantite = $detail['quantite'] ?? null;


                $produit = new Produit($this->pdo);
                $produit->get($produit_id);
                $prix_vente = $produit->getPrixVente() ?? null;

                if (!$prix_vente || !$quantite || !$produit_id) {
                    throw new Exception('Données de détail invalides (produit_id, quantite et prix_vente requis)');
                }
                // 
                if ($produit->quantite < $quantite) {
                    echo json_encode([
                        'message' => "Stock insuffisant pour le produit '{$produit->nom}' (disponible: {$produit->quantite}, demandé: {$quantite})",
                        'success' => false
                    ]);
                    $this->pdo->rollBack();
                    exit;
                }
                // Créer détail commande
                $detCmd = new DetailsCommande(
                    $this->pdo,
                    0,
                    $commande_id,
                    $produit_id,
                    $quantite,
                    $prix_vente, // Utiliser le prix de vente actuel du produit pour plus de sécurité
                    '',
                    ''
                );
                $detCmd->create($this->pdo);

                // Décrémenter stock du produit
                $produit->updateQuantity(-(int) $quantite);
            }

            $this->pdo->commit();
            return ['success' => true, 'commande_id' => $commande_id];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Annuler une commande et restaurer les stocks
     * @param int $commande_id ID de la commande à annuler
     * @return bool True si succès
     * @throws Exception
     */
    public function cancelOrder(int $commande_id)
    {
        try {
            $this->pdo->beginTransaction();

            // Récupérer tous les détails de la commande
            $sql = "SELECT produit_id, quantite FROM details_commande WHERE commande_id = :commande_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':commande_id', $commande_id, PDO::PARAM_INT);
            $stmt->execute();
            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($details)) {
                throw new Exception('Commande non trouvée ou vide');
            }

            // Restaurer stocks pour chaque produit
            foreach ($details as $detail) {
                $produit = new Produit($this->pdo);
                $produit->get($detail['produit_id']);
                // Restaurer : ajouter la quantité annulée au stock
                $produit->updateQuantity((int) $detail['quantite']);
            }

            // Mettre à jour statut commande à "annulee"
            $cmd = new Commandes($this->pdo, $commande_id);
            $cmd->update('annulee');

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    public function getDetails(int $commande_id)
    {
        return DetailsCommande::getByCommandeId($this->pdo, $commande_id);

    }
}