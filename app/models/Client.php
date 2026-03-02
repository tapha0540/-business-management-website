<?php

class Clients
{
    private PDO $pdo;
    private ?int $id;
    private ?string $prenom;
    private ?string $nom;
    private ?string $email;
    private ?string $telephone;
    private ?string $imgUrl;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?string $prenom = null,
        ?string $nom = null,
        ?string $email = null,
        ?string $telephone = null,
        ?string $imgUrl = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->imgUrl = $imgUrl;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO clients (prenom, nom, email, telephone, imgUrl) VALUES (:prenom,:nom, :email, :telephone, :imgUrl)");
        $isCreated = $stmt->execute([
            "prenom" => $this->prenom,
            "nom" => $this->nom,
            "email" => $this->email,
            "telephone" => $this->telephone,
            "imgUrl" => $this->imgUrl
        ]);
        if ($isCreated) {
            $this->id = (int) $this->pdo->lastInsertId();
        }
        return $isCreated;
    }

    /**
     * 
     * 
     * @param int $id
     * @return array{id: int, prenom: string, nom: string, email: string, telephone: string, created_at: string, updated_at: string}
     */
    public function get(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, telephone, created_at, updated_at FROM clients WHERE id = ?');
        $success = $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Summary of getAll
     * @return array{array{id: int, prenom: string, nom: string, email: string, telephone: string, created_at: string, updated_at: string}}
     */

    public function update(string $new_prenom, string $new_nom, string $new_email, string $new_telephone): bool
    {

        $stmt = $this->pdo->prepare('UPDATE clients SET prenom = :prenom, nom = :nom, email = :email, telephone = :telephone WHERE id = :id');

        $isUpdated = $stmt->execute([
            'prenom' => $new_prenom,
            'nom' => $new_nom,
            'email' => $new_email,
            'telephone' => $new_telephone,
            'id' => $this->id
        ]);

        if ($isUpdated) {
            $this->prenom = $new_prenom;
            $this->nom = $new_nom;
            $this->email = $new_email;
            $this->telephone = $new_telephone;
        }
        return $isUpdated;
    }

    public function delete(): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM clients WHERE id = ?');

        return $stmt->execute([$this->id]);
    }
    static public function bestCustomers(PDO &$pdo, int $limit, string $from, string $to)
    {
        $stmt = $pdo->prepare("SELECT
                                        cl.id,
                                        cl.prenom,
                                        cl.nom,
                                        cl.email,
                                        cl.telephone,
                                        DATE_FORMAT(cl.created_at, '%d/%m/%Y') AS 'Compte crée le',
                                        COUNT(co.id) AS `Nombre de commandes faites`
                                    FROM clients cl
                                    LEFT JOIN commandes co ON cl.id = co.client_id
                                    WHERE co.created_at BETWEEN :from AND :to
                                    GROUP BY cl.id,
                                        cl.prenom,
                                        cl.nom,
                                        cl.email,
                                        cl.telephone,
                                        cl.created_at
                                    ORDER BY `Nombre de commandes faites` DESC
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
    public static function getAll(PDO &$pdo, int $limit, string $search): array
    {
        $filter = "";
        $searchNotEmpty = $search && trim($search);

        if ($searchNotEmpty) {
            $filter = "WHERE prenom LIKE :search 
                        OR nom LIKE :search 
                        OR email LIKE :search 
                        OR telephone LIKE :search";
        }

        $sql = "SELECT 
               id, prenom, nom, email, telephone, imgUrl, created_at, updated_at
            FROM clients
            $filter
            ORDER BY created_at DESC
            LIMIT :limit";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        if ($searchNotEmpty) {
            $searchValue = "%" . trim($search) . "%";
            $stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}