<?php
class ClientController {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createClient(string $name, string $email, string $phone) {
        if (empty($name) || empty($email) || empty($phone)) {
            return [
                "message" => "Tous les champs sont obligatoires",
                "success" => false
            ];  

        }
        $stmt = $this->pdo->prepare("INSERT INTO clients(nom, email, telephone) VALUES (?, ?, ?)");
        $isClientCreated = $stmt->execute([$name, $email, $phone]);
        return [
            "message" => $isClientCreated ? "Client créé avec succès" : "Échec de la création du client",
            "success" => $isClientCreated
        ];
    }
    public function updateClient(string $name, string $email, string $phone) { 
        $stmt = $this->pdo->prepare("UPDATE clients SET nom=?, email=?, telephone=? WHERE id=?");
        $isClientUpdated = $stmt->execute([$name, $email, $phone]);
        return [
            "message" => $isClientUpdated ? "Client mis à jour avec succès" : "Échec de la mise à jour du client",
            "success" => $isClientUpdated
        ];
    }
    public function deleteClient(int $clientId) {
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id = ?");
        $isClientDeleted = $stmt->execute([$clientId]);
        return [
            "message" => $isClientDeleted ? "Client supprimé avec succès" : "Échec de la suppression du client",
            "success" => $isClientDeleted
        ];
    }
    public function getClient(int $clientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$clientId]);

        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            "message" => $client ? "Client récupéré avec succès" : "Échec de la récupération du client",
            "success" => !empty($client),
            "client" => $client
        ];
    }
}