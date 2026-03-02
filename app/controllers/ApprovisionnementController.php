<?php

require_once __DIR__ . '/../models/Approvisionnement.php';
require_once __DIR__ . '/../models/DetailsApprovisionnement.php';

class ApprovisionnementController
{
    private PDO $pdo;
    private Approvisionnement $model;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data)
    {
        $fournisseur_id = $data['fournisseur_id'] ?? null;
        $details = $data['details'] ?? [];

        if (!$fournisseur_id) {
            throw new Exception('Fournisseur requis');
        }

        if (empty($details)) {
            throw new Exception('Au moins un produit requis');
        }

        try {
            $this->pdo->beginTransaction();

            // Create approvisionnement
            $approv = new Approvisionnement($this->pdo, 0, $fournisseur_id, '', '');
            $approv->create();
            $approv_id = $this->pdo->lastInsertId();

            // Create details
            foreach ($details as $detail) {
                $produit_id = $detail['produit_id'] ?? null;
                $quantite = $detail['quantite'] ?? null;
                $prix_achat = $detail['prix_achat'] ?? null;

                if (!$produit_id || !$quantite || $prix_achat === null) {
                    throw new Exception('Données de détail invalides');
                }

                $det = new DetailsApprovisionnement(
                    0,
                    $approv_id,
                    $produit_id,
                    $quantite,
                    $prix_achat,
                    '',
                    ''
                );
                $det->create();
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getAll(int $limit = 10, string $search = '')
    {
        $sql = "SELECT a.*, f.nom as fournisseur_nom, f.email as fournisseur_email 
                FROM approvisionnements a 
                JOIN fournisseurs f ON a.fournisseur_id = f.id";

        if ($search) {
            $sql .= " WHERE f.nom LIKE :search OR f.email LIKE :search";
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);

        if ($search) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(int $id)
    {
        $sql = "SELECT a.*, f.nom as fournisseur_nom, f.email as fournisseur_email 
                FROM approvisionnements a 
                JOIN fournisseurs f ON a.fournisseur_id = f.id 
                WHERE a.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $approv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$approv) {
            return null;
        }

        // Get details
        $sql = "SELECT d.*, p.nom as produit_nom, p.imgUrl as produit_image 
                FROM details_approvisionnement d 
                JOIN produits p ON d.produit_id = p.id 
                WHERE d.approvisionnement_id = :approv_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':approv_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $approv['details'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $approv;
    }

    public function update(int $id, array $data)
    {
        $details = $data['details'] ?? [];

        if (empty($details)) {
            throw new Exception('Au moins un produit requis');
        }

        try {
            $this->pdo->beginTransaction();

            // Delete old details
            $sql = "DELETE FROM details_approvisionnement WHERE approvisionnement_id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Create new details
            foreach ($details as $detail) {
                $produit_id = $detail['produit_id'] ?? null;
                $quantite = $detail['quantite'] ?? null;
                $prix_achat = $detail['prix_achat'] ?? null;

                if (!$produit_id || !$quantite || $prix_achat === null) {
                    throw new Exception('Données de détail invalides');
                }

                $det = new DetailsApprovisionnement(
                    0,
                    $id,
                    $produit_id,
                    $quantite,
                    $prix_achat,
                    '',
                    ''
                );
                $det->create();
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        $approv = new Approvisionnement($this->pdo, $id, 0, '', '');
        return $approv->delete();
    }

    public function deleteMultiple(array $ids)
    {
        if (empty($ids)) {
            throw new Exception('Aucun ID fourni');
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM approvisionnements WHERE id IN ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($ids);
    }
}
