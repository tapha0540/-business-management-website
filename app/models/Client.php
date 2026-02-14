<?php

class Client
{
    private PDO $pdo;
    private int $id;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $telephone;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        PDO $pdo,
        int $id,
        string $prenom,
        string $nom,
        string $email,
        string $telephone,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->prenom = (string) $prenom;
        $this->nom = (string) $nom;
        $this->email = (string) $email;
        $this->telephone = (string) $telephone;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
    }

    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO clients (prenom, nom, email, telephone) VALUES (:prenom,:nom, :email, :telephone)");
        $isCreated = $stmt->execute([
            "prenom" => $this->prenom,
            "nom" => $this->nom,
            "email" => $this->email,
            "telephone" => $this->telephone
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
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, telephone, created_at, updated_at  FROM clients WHERE id = ?');
        $success = $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Summary of getAll
     * @return array{array{id: int, prenom: string, nom: string, email: string, telephone: string, created_at: string, updated_at: string}}
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT id, prenom, nom, email, telephone, created_at, updated_at FROM clients');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
    static public function bestCustomers(PDO &$pdo, int $limit, string $from, string $to) {
        $stmt = $pdo->prepare("SELECT
                                        cl.id,
                                        cl.prenom,
                                        cl.nom,
                                        cl.email,
                                        cl.telephone,
                                        cl.created_at AS 'Compte crée le',
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
}