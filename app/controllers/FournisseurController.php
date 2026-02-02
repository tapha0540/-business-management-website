<?php

class FournisseurController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createFournisseur(string $nom, string $email, string $telephone)
    {
        if (empty($nom) || empty($email) || empty($telephone)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO fournisseurs (nom, email, telephone) VALUES (?, ?, ?)");
        $isFournisseurCreated = $stmt->execute([$nom, $email, $telephone]);
        return [
            "message" => $isFournisseurCreated ? "Fournisseur créé avec succès" : "Échec de la création du fournisseur",
            "success" => $isFournisseurCreated
        ];
    }
    public function updateFournisseur(string $nom, string $email, string $telephone, int $fournisseurId)
    {
        $stmt = $this->pdo->prepare("UPDATE fournisseurs SET nom=?, email=?, telephone=? WHERE id=?");
        $isFournisseurUpdated = $stmt->execute([$nom, $email, $telephone, $fournisseurId]);
        return [
            "message" => $isFournisseurUpdated ? "Fournisseur mis à jour avec succès" : "Échec de la mise à jour du fournisseur",
            "success" => $isFournisseurUpdated
        ];
    }
    public function deleteFournisseur(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM fournisseurs WHERE id = ?");
        $isFournisseurDeleted = $stmt->execute([$id]);
        return [
            "message" => $isFournisseurDeleted ? "Fournisseur supprimé avec succès" : "Échec de la suppression du fournisseur",
            "success" => $isFournisseurDeleted
        ];
    }
    public function getFournisseur(int|null $id = null, string|null $nom = null, string|null $email = null, string|null $telephone = null)
    {
        $whereClause = [];
        $params = [];

        if ($id !== null) {
            $whereClause[] = "id = ?";
            $params[] = $id;
        }
        if ($nom !== null) {
            $whereClause[] = "nom = ?";
            $params[] = $nom;
        }
        if ($email !== null) {
            $whereClause[] = "email = ?";
            $params[] = $email;
        }
        if ($telephone !== null) {
            $whereClause[] = "telephone = ?";
            $params[] = $telephone;
        }

        $sql = "SELECT * FROM fournisseurs";
        if (!empty($whereClause)) {
            $sql .= " WHERE " . implode(" OR ", $whereClause);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fournisseur = $result[0] ?? null;
        return [
            "message" => $fournisseur ? "Fournisseur récupéré avec succès" : "Échec de la récupération du fournisseur",
            "success" => !empty($fournisseur),
            "fournisseurs" => $result,
        ];
    }
}