<?php
class UserController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function createUser($firstName, $lastName, $email, $password, $role)
    {
        // Logic to create a new user with provided data
        $stmt = $this->pdo->prepare("INSERT INTO users(first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $isUserCreated = $stmt->execute([$firstName, $lastName, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

        if ($isUserCreated) {
            return [
                "message" => "Utilisateur créé avec succès",
                "success" => true
            ];
        } else {
            return [
                "message" => "Échec de la création de l'utilisateur",
                "success" => false
            ];
        }

    }

    public function getUserProfile(int|null $userId = null, string|null $email = null)
    {

        // Logic to retrieve user profile by user ID or email
        if ($userId !== null) {
            $stmt = $this->pdo->prepare("SELECT id, first_name, last_name, email, password, role, created_at, updated_at FROM users WHERE id = ?");
            $stmt->execute([$userId]);
        } else if ($email !== null) {
            $stmt = $this->pdo->prepare("SELECT id, first_name, last_name, email, password, role, created_at, updated_at FROM users WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            return [
                "message" => "Aucun identifiant d'utilisateur ou email fourni",
                "success" => false
            ];
        }
        return [
            "message" => "Profil utilisateur récupéré avec succès",
            "success" => true,
            "user" => $stmt->fetch(PDO::FETCH_ASSOC)
        ];
    }

    public function updateUserProfile($userId, $data)
    {
        // Logic to update user profile with provided data
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (!empty($fields)) {
            $values[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $isProfileUpdated = $stmt->execute($values);

            if ($isProfileUpdated) {
                return [
                    "message" => "Profil utilisateur mis à jour avec succès",
                    "success" => true
                ];
            } else {
                return [
                    "message" => "Échec de la mise à jour du profil utilisateur",
                    "success" => false
                ];

            }

        } else {
            return [
                "message" => "Aucune donnée à mettre à jour",
                "success" => false
            ];
        }

    }

    public function deleteUser($userId)
    {
        // Logic to delete user by user ID
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $isUserDeleted = $stmt->execute([$userId]);
        return [
            "message" => $isUserDeleted ? "Utilisateur supprimé avec succès" : "Échec de la suppression de l'utilisateur",
            "success" => $isUserDeleted
        ];
    }
}