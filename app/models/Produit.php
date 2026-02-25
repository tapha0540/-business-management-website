<?php

class Produit
{
    private PDO $pdo;
    public ?int $id;
    public ?string $nom;
    public ?string $description;
    public ?string $imgUrl;
    public ?int $categorie_id;
    public ?int $prix_vente;
    public ?int $quantite;
    public ?int $seuil_critique;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?string $nom = null,
        ?string $description = null,
        ?string $imgUrl = null,
        ?int $categorie_id = null,
        ?int $prix_vente = null,
        ?int $quantite = null,
        ?int $seuil_critique = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->imgUrl = $imgUrl;
        $this->categorie_id = $categorie_id;
        $this->prix_vente = $prix_vente;
        $this->quantite = $quantite;
        $this->seuil_critique = $seuil_critique;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function create()
    {
        $stmt = $this->pdo->prepare("INSERT INTO produits (nom, description, imgUrl, categorie_id, prix_vente, quantite, seuil_critique)
            VALUES (:nom, :description, :imgUrl, :categorie_id, :prix_vente, :quantite, :seuil_critique)
        ");

        $isCreated = $stmt->execute([
            "nom" => $this->nom,
            "description" => $this->description,
            "imgUrl" => $this->imgUrl,
            "categorie_id" => $this->categorie_id,
            "prix_vente" => $this->prix_vente,
            "quantite" => $this->quantite,
            "seuil_critique" => $this->seuil_critique
        ]);
        $this->id = (int) $this->pdo->lastInsertId();
        return $isCreated;
    }
    /**
     * Summary of 
     * @return null|array{id: int, nom: string, description: string, imgUrl: string, categorie_id: int, prix_vente: float, quantite: int, seuil_critique: int, created_at: string, updated_at: string}
     */
    public function get(int $id): array|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id");

        if (!$stmt->execute(["id" => $id])) {
            return null;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->__construct(
            $this->pdo,
            (int) $id,
            (string) $row['nom'],
            (string) $row['description'],
            (string) $row['imgUrl'],
            (int) $row['categorie_id'],
            (float) $row['prix_vente'],
            (int) $row['quantite'],
            (int) $row['seuil_critique'],
            (string) $row['created_at'],
            (string) $row['updated_at']
        );

        return $row;
    }
    /**
     * Summary of 
     * @return array<array{id: int, nom: string, description: string, imgUrl: string, categorie_id: int, prix_vente: float, quantite: int, seuil_critique: int, created_at: string, updated_at: string}>
     */
    public function update(
        string $new_nom,
        string $new_description,
        string $new_imgUrl,
        int $new_categorie_id,
        int $new_prix_vente,
        int $new_quantite,
        int $new_seuil_critique
    ) {
        $stmt = $this->pdo->prepare("UPDATE produits SET 
            nom = :nom,
            description = :description,
            imgUrl = :imgUrl,
            categorie_id = :categorie_id,
            prix_vente = :prix_vente,
            quantite = :quantite,
            seuil_critique = :seuil_critique,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        $isCreated = $stmt->execute([
            "nom" => $new_nom,
            "description" => $new_description,
            "imgUrl" => $new_imgUrl,
            "categorie_id" => $new_categorie_id,
            "prix_vente" => $new_prix_vente,
            "quantite" => $new_quantite,
            "seuil_critique" => $new_seuil_critique,
            "id" => $this->id
        ]);
        if ($isCreated) {
            $this->nom = (string) $new_nom;
            $this->description = (string) $new_description;
            $this->imgUrl = (string) $new_imgUrl;
            $this->categorie_id = (int) $new_categorie_id;
            $this->prix_vente = (float) $new_prix_vente;
            $this->quantite = (int) $new_quantite;
            $this->seuil_critique = (int) $new_seuil_critique;
        }
        return $isCreated;
    }

    public function delete()
    {
        $stmt = $this->pdo->prepare("DELETE FROM produits WHERE id = :id");

        return $stmt->execute(["id" => $this->id]);
    }
    public static function mostSoldProduct(PDO &$pdo, int $limit, string $from, string $to): array
    {
        $stmt = $pdo->prepare("SELECT 
                                        p.id,
                                        p.nom,
                                        p.imgUrl,
                                        p.quantite,
                                        p.prix_vente,
                                        c.nom AS `Catégorie du produit`,
                                        COALESCE(SUM(d.quantite), 0) AS `Quantités commandées`
                                    FROM produits p
                                    LEFT JOIN details_commande d ON p.id = d.produit_id
                                    LEFT JOIN categories c ON p.categorie_id = c.id
                                    WHERE d.created_at BETWEEN :from AND :to
                                    GROUP BY 
                                        p.id,
                                        p.nom,
                                        p.imgUrl,
                                        p.quantite,
                                        p.prix_vente,
                                        c.nom
                                    ORDER BY `Quantités commandées` DESC
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
    public static function productsAtRiskOfOutOfStock(PDO &$pdo, int $limit): array
    {
        $stmt = $pdo->prepare("SELECT
                                        p.id,
                                        p.imgUrl,
                                        p.nom,
                                        p.quantite AS `Stock`,
                                        p.seuil_critique AS `Seuil Critique`, 
                                        ca.nom AS `Catégorie du produit`,
                                        p.prix_vente AS `Prix du produit`
                                    FROM produits p
                                    JOIN categories ca ON p.categorie_id = ca.id
                                    WHERE p.quantite <= p.seuil_critique
                                    LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getAll(PDO &$pdo, int $limit, string $search): array
    {
        $filter = "";
        $searchNotEmpty = $search && trim($search);

        if ($searchNotEmpty) {
            $filter = "WHERE p.nom LIKE :search OR c.nom LIKE :search";
        }

        $sql = "SELECT 
                p.id,
                p.imgUrl,
                p.nom, 
                p.quantite,
                p.prix_vente, 
                p.seuil_critique,
                p.description, 
                DATE_FORMAT(p.created_at, '%d/%m/%Y') AS created_at, 
                DATE_FORMAT(p.updated_at, '%d/%m/%Y') AS updated_at,
                c.nom AS categorie 
            FROM produits p
            JOIN categories c 
                ON p.categorie_id = c.id
            $filter
            ORDER BY p.created_at DESC
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