<?php

class Produit
{
    private PDO $pdo;
    public int $id;
    public string $nom;
    public string $description;
    public string $imgUrl;
    public int $categorie_id;
    public int $prix_vente;
    public int $quantite;
    public int $seuil_critique;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        PDO $pdo,
        int $id,
        string $nom,
        string $description,
        string $imgUrl,
        int $categorie_id,
        int $prix_vente,
        int $quantite,
        int $seuil_critique,
        string $created_at,
        string $updated_at
    ) {
        $this->pdo = $pdo;
        $this->id = (int) $id;
        $this->nom = (string) $nom;
        $this->description = (string) $description;
        $this->imgUrl = (string) $imgUrl;
        $this->categorie_id = (int) $categorie_id;
        $this->prix_vente = (float) $prix_vente;
        $this->quantite = (int) $quantite;
        $this->seuil_critique = (int) $seuil_critique;
        $this->created_at = (string) $created_at;
        $this->updated_at = (string) $updated_at;
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
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produits");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
    static public function mostSoldProduct(PDO $pdo, int $limit, string $from, string $to): array
    {
        $stmt = $pdo->prepare("SELECT 
                                        p.id,
                                        p.nom,
                                        p.imgUrl,
                                        p.quantite,
                                        p.prix_vente,
                                        c.nom AS categorie_produit,
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
}