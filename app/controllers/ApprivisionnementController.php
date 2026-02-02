<?php

class ApprivisionnementController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function createApprivisionnement(int $fournisseurId)
    {
        if (empty($fournisseurId)) {
            throw new Exception("Le fournisseur est obligatoire.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO apprivisionnements (fournisseur_id) VALUES (?)");
        $isApprivisionnementCreated = $stmt->execute([$fournisseurId]);
        return [
            "message" => $isApprivisionnementCreated ? "Apprivisionnement créé avec succès" : "Échec de la création de l'apprivisionnement",
            "success" => $isApprivisionnementCreated
        ];

    }
    public function updateApprivisionnement(int $id, int $fournisseurId)
    {
        if (empty($id) || empty($fournisseurId)) {
            throw new Exception("L'ID de l'apprivisionnement est obligatoire.");
        }
        $stmt = $this->pdo->prepare("UPDATE apprivisionnements SET fournisseur_id=? WHERE id=?");
        $isApprivisionnementUpdated = $stmt->execute([$fournisseurId, $id]);
        return [
            "message" => $isApprivisionnementUpdated ? "Apprivisionnement mis à jour avec succès" : "Échec de la mise à jour de l'apprivisionnement",
            "success" => $isApprivisionnementUpdated
        ];
    }
    public function getApprivisionnement(int $id)
    {
        if (empty($id)) {
            throw new Exception("L'ID de l'apprivisionnement est obligatoire.");
        }
        $stmt = $this->pdo->prepare("SELECT * FROM apprivisionnements WHERE id = ?");
        $stmt->execute([$id]);

        $apprivisionnement = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            "message" => $apprivisionnement ? "Apprivisionnement récupéré avec succès" : "Échec de la récupération de l'apprivisionnement",
            "success" => !empty($apprivisionnement),
            "apprivisionnement" => $apprivisionnement
        ];
    }
    public function deleteApprivisionnement(int $id)
    {
        if (empty($id)) {
            throw new Exception("L'ID de l'apprivisionnement est obligatoire.");
        }
        $stmt = $this->pdo->prepare("DELETE FROM apprivisionnements WHERE id = ?");
        $isApprivisionnementDeleted = $stmt->execute([$id]);
        return [
            "message" => $isApprivisionnementDeleted ? "Apprivisionnement supprimé avec succès" : "Échec de la suppression de l'apprivisionnement",
            "success" => $isApprivisionnementDeleted
        ];
    }
}