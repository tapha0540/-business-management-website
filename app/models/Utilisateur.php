<?php

class Utilisateur
{
    private PDO $pdo;
    private ?int $id;
    private ?string $prenom;
    private ?string $nom;
    private ?string $email;
    private ?string $mot_de_passe;
    private string $role;
    private ?string $imgUrl;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?string $prenom = null,
        ?string $nom = null,
        ?string $email = null,
        ?string $mot_de_passe = null,
        string $role = 'vendeur',
        ?string $imgUrl = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        if (!in_array($role, ['admin', 'vendeur'])) {
            throw new InvalidArgumentException('Role invalide');
        }

        $this->pdo = $pdo;
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
        $this->imgUrl = $imgUrl;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function create(): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role, imgUrl)
             VALUES (:prenom, :nom, :email, :mot_de_passe, :role, :imgUrl)'
        );

        $ok = $stmt->execute([
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'email' => $this->email,
            'mot_de_passe' => password_hash($this->mot_de_passe, PASSWORD_BCRYPT),
            'role' => $this->role,
            'imgUrl' => $this->imgUrl
        ]);

        if ($ok) {
            $this->id = (int) $this->pdo->lastInsertId();
        }

        return $ok;
    }

    public function get(?int $id = null, ?string $email = null): ?array
    {
        if ($id !== null) {
            $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
            $stmt->execute([$id]);
        } elseif ($email !== null) {
            $stmt = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
            $stmt->execute([$email]);
        } else {
            return null;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $this->id = (int) $row['id'];
        $this->prenom = $row['prenom'];
        $this->nom = $row['nom'];
        $this->email = $row['email'];
        $this->mot_de_passe = $row['mot_de_passe'];
        $this->role = $row['role'];
        $this->imgUrl = $row['imgUrl'] ?? null;
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];

        return $row;
    }

    public function getAll(string $search, int $limit, string $role): array
    {

        $filter = "";
        $searchNotEmpty = $search && trim($search);
        $roleNotEmpty = $role && strlen(trim($role)) != 0;

        if ($searchNotEmpty) {
            $filter .= "WHERE prenom LIKE :search 
            OR nom LIKE :search 
            OR email LIKE :search";
        }

        if ($roleNotEmpty) {
            if ($filter != "") {
                $filter .= "AND role = :role";
            } else {
                $filter .= "WHERE role = :role";
            }
        }

        $sql = "SELECT id, prenom, nom, email, role, imgUrl, created_at, updated_at 
                FROM utilisateurs $filter
                ORDER BY created_at DESC
                LIMIT :limit";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        if ($searchNotEmpty) {
            $searchValue = "%" . trim($search) . "%";
            $stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
        }

        if ($roleNotEmpty) {
            $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        }

        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(
        ?string $prenom = null,
        ?string $nom = null,
        ?string $email = null,
        ?string $mot_de_passe = null,
        ?string $imgUrl = null
    ): bool {
        if ($this->id === null) {
            return false;
        }

        $fields = [];
        $params = ['id' => $this->id];

        if ($prenom !== null) {
            $fields[] = 'prenom = :prenom';
            $params['prenom'] = $prenom;
            $this->prenom = $prenom;
        }

        if ($nom !== null) {
            $fields[] = 'nom = :nom';
            $params['nom'] = $nom;
            $this->nom = $nom;
        }

        if ($email !== null) {
            $fields[] = 'email = :email';
            $params['email'] = $email;
            $this->email = $email;
        }

        if ($mot_de_passe !== null) {
            $fields[] = 'mot_de_passe = :mot_de_passe';
            $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
            $params['mot_de_passe'] = $hash;
            $this->mot_de_passe = $hash;
        }

        if ($imgUrl !== null) {
            $fields[] = 'imgUrl = :imgUrl';
            $params['imgUrl'] = $imgUrl;
            $this->imgUrl = $imgUrl;
        }

        if (empty($fields)) {
            return false;
        }

        $sql = 'UPDATE utilisateurs SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute($params);
        if ($success) {
            $_SESSION['user']['prenom'] = $prenom;
            $_SESSION['user']['nom'] = $nom;
            $_SESSION['user']['email'] = $email;
            if ($imgUrl !== null) {
                $_SESSION['user']['imgUrl'] = $imgUrl;
            }

        }
        return $success;
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $stmt = $this->pdo->prepare('DELETE FROM utilisateurs WHERE id = ?');
        return $stmt->execute([$this->id]);
    }

    static public function bestSellers(PDO &$pdo, int $limit, string $from, string $to): array
    {
        $stmt = $pdo->prepare("SELECT 
                                        u.id AS `Id`,
                                        u.prenom AS `Prénom`,
                                        u.nom AS `Nom`,
                                        u.email AS `Email`,
                                        u.imgUrl,
                                        DATE_FORMAT(u.created_at, '%d/%m/%Y') AS `Compte crée le`,
                                        COUNT(c.id) AS `Nombre de commandes réalisées`
                                    FROM utilisateurs u
                                    LEFT JOIN commandes c ON u.id = c.vendeur_id
                                    WHERE u.role = 'vendeur' AND u.created_at BETWEEN :from AND :to
                                    GROUP BY u.id, u.prenom, u.nom, u.email
                                    ORDER BY `Nombre de commandes réalisées` DESC
                                    LIMIT :limit");

        $stmt->bindValue(':from', (new DateTime($from))->modify('-1 day')->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(':to', (new DateTime($to))->modify('+1 day')->format('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
