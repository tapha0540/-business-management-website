<?php
require_once __DIR__ . '/Produit.php';


class Commandes
{
    private PDO $pdo;
    private ?int $id;
    private ?int $vendeur_id;
    private ?int $client_id;
    private string $etat;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?int $vendeur_id = null,
        ?int $client_id = null,
        ?string $etat = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->vendeur_id = (int) $vendeur_id;
        $this->client_id = (int) $client_id;
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
                (string) $data['etat'],
                $data['created_at'],
                $data['updated_at']
            );
        }
        return $data;
    }
    public function getAll(int $limit, string $search = '')
    {
        $search = trim($search);
        $filter = '';

        if ($search !== '') {
            $filter = "WHERE CAST(commandes.id AS CHAR) LIKE :search
                        OR commandes.etat LIKE :search
                        OR CONCAT(utilisateurs.prenom, ' ', utilisateurs.nom) LIKE :search
                        OR CONCAT(clients.prenom, ' ', clients.nom) LIKE :search";
        }

        $sql = "SELECT
                    commandes.id,
                    commandes.etat,
                    commandes.created_at,
                    CONCAT(utilisateurs.prenom,' ',utilisateurs.nom) AS vendeur_nom,
                    CONCAT(clients.prenom,' ',clients.nom) AS client_nom
                FROM commandes
                JOIN utilisateurs ON utilisateurs.id = commandes.vendeur_id
                JOIN clients ON clients.id = commandes.client_id
                $filter
                ORDER BY commandes.created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);

        if ($search !== '') {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO commandes (vendeur_id, client_id) VALUES (:vendeur_id, :client_id)");

        $isCreated = $stmt->execute([
            'vendeur_id' => $this->vendeur_id,
            'client_id' => $this->client_id,
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
        $stmt = $this->pdo->prepare("UPDATE commandes SET etat = :etat WHERE id = :id");

        $isUpdated = $stmt->execute([
            'etat' => $new_etat,
            'id' => $this->id
        ]);
        if ($isUpdated) {
            $this->etat = $new_etat;
        }
        return $isUpdated;

    }
    public function updateClientAndDetails(int $new_client_id, array $details): bool
    {
        if ($new_client_id <= 0) {
            throw new InvalidArgumentException("client_id invalide");
        }
        if (empty($details)) {
            throw new InvalidArgumentException("details requis");
        }

        try {
            $this->pdo->beginTransaction();

            $updateCommandeStmt = $this->pdo->prepare("UPDATE commandes SET client_id = :client_id, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $isCommandeUpdated = $updateCommandeStmt->execute([
                'client_id' => $new_client_id,
                'id' => $this->id,
            ]);

            if (!$isCommandeUpdated) {
                throw new Exception("Echec de la mise a jour du client de la commande");
            }

            $currentDetailsStmt = $this->pdo->prepare("SELECT produit_id, quantite FROM details_commande WHERE commande_id = :commande_id");
            $currentDetailsStmt->execute([':commande_id' => $this->id]);
            $currentDetails = $currentDetailsStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($currentDetails)) {
                throw new Exception("Aucun detail trouve pour cette commande");
            }

            $currentByProduit = [];
            foreach ($currentDetails as $detail) {
                $currentByProduit[(int) $detail['produit_id']] = (int) $detail['quantite'];
            }

            $submittedByProduit = [];
            foreach ($details as $detail) {
                $produitId = (int) ($detail['produit_id'] ?? 0);
                $quantite = (int) ($detail['quantite'] ?? 0);

                if ($produitId <= 0 || $quantite <= 0) {
                    throw new InvalidArgumentException("Donnees details invalides");
                }
                if (isset($submittedByProduit[$produitId])) {
                    throw new InvalidArgumentException("Produit duplique dans details");
                }
                if (!array_key_exists($produitId, $currentByProduit)) {
                    throw new InvalidArgumentException("Produit hors commande");
                }

                $submittedByProduit[$produitId] = $quantite;
            }

            if (count($submittedByProduit) !== count($currentByProduit)) {
                throw new InvalidArgumentException("Tous les produits de la commande doivent etre envoyes");
            }

            foreach ($currentByProduit as $produitId => $_) {
                if (!array_key_exists($produitId, $submittedByProduit)) {
                    throw new InvalidArgumentException("Produit manquant dans details");
                }
            }

            $updateDetailStmt = $this->pdo->prepare("UPDATE details_commande SET quantite = :quantite, updated_at = CURRENT_TIMESTAMP WHERE commande_id = :commande_id AND produit_id = :produit_id");

            foreach ($submittedByProduit as $produitId => $newQuantite) {
                $oldQuantite = $currentByProduit[$produitId];
                $delta = $newQuantite - $oldQuantite;

                if ($delta !== 0) {
                    $produit = new Produit($this->pdo);
                    $produitData = $produit->get((int) $produitId);
                    if (!$produitData) {
                        throw new Exception("Produit introuvable pour mise a jour stock");
                    }
                    // Increase order quantity => decrease stock, and vice-versa.
                    $produit->updateQuantity(-$delta);
                }

                $updated = $updateDetailStmt->execute([
                    ':quantite' => $newQuantite,
                    ':commande_id' => $this->id,
                    ':produit_id' => $produitId,
                ]);
                if (!$updated) {
                    throw new Exception("Echec de la mise a jour des details commande");
                }
            }

            $this->client_id = $new_client_id;
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM commandes WHERE id = :id");

        return $stmt->execute(['id' => $this->id]);
    }
    public static function bestOrdersByPrice(PDO $pdo, int $limit, string $from, string $to)
    {
        $stmt = $pdo->prepare("SELECT 
                                    c.id AS `Id`,
                                    f.montant_total AS `Montant Total`,
                                    c.etat AS `Etat`,
                                    DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'CommandÃ© le',
                                    DATE_FORMAT(c.updated_at, '%d/%m/%Y') AS `CloturÃ©e le`
                                FROM commandes c
                                JOIN factures f ON f.commande_id = c.id
                                WHERE c.etat = 'cloturee'
                                AND f.created_at BETWEEN :from AND :to
                                ORDER BY f.montant_total DESC
                                LIMIT :limit");

        $stmt->bindValue(':from', (new DateTime($from))
            ->modify('-1 day')
            ->format('Y-m-d'), PDO::PARAM_STR);

        $stmt->bindValue(':to', (new DateTime($to))
            ->modify('+1 day')
            ->format('Y-m-d'), PDO::PARAM_STR);

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function latestOrders(PDO $pdo, int $limit, string $from, string $to)
    {
        $stmt = $pdo->prepare("SELECT 
                                    c.id AS `Id`,
                                    f.montant_total AS `Montant Total`,
                                    c.etat AS `Etat`,
                                    DATE_FORMAT(c.created_at, '%d/%m/%Y')AS 'CommandÃ© le',
                                    DATE_FORMAT(c.updated_at, '%d/%m/%Y') AS `CloturÃ©e le`
                                FROM commandes c
                                JOIN factures f ON f.commande_id = c.id
                                WHERE c.created_at BETWEEN :from AND :to
                                ORDER BY c.created_at DESC
                                LIMIT :limit");

        $stmt->bindValue(':from', (new DateTime($from))
            ->modify('-1 day')
            ->format('Y-m-d'), PDO::PARAM_STR);

        $stmt->bindValue(':to', (new DateTime($to))
            ->modify('+1 day')
            ->format('Y-m-d'), PDO::PARAM_STR);

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
