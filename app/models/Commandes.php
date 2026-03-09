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
                                    DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'Commandé le',
                                    DATE_FORMAT(c.updated_at, '%d/%m/%Y') AS `Cloturée le`
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
                                    DATE_FORMAT(c.created_at, '%d/%m/%Y')AS 'Commandé le',
                                    DATE_FORMAT(c.updated_at, '%d/%m/%Y') AS `Cloturée le`
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
