<?php

require_once 'app/controllers/UserController.php';
class AuthController extends UserController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->pdo = $pdo;
    }
    public function signup(string $first_name, string $last_name, string $email, string $password, string $role)
    {
        // Logique de creation d'un nouvel utilisateur
        // 
        return $this->createUser(
            $first_name,
            $last_name,
            $email,
            $password,
            $role ?? 'vendeur' // valeur par defaut 'vendeur' si aucun role n'est fourni
        );
    }
    public function login(string $email, string $password)
    {
        // logique de connexion d'un utilisateur
        $user = $this->getUserProfile(email: $email);
        if ($user) {

            if (!password_verify($password, $user['password'])) {
                return [
                    "message" => "mot de passe incorrect",
                    "success" => false
                ];
            }
            return [
                "message" => "connexion reussie",
                "success" => true,
                "user" => $user
            ];
        } else {
            return [
                "message" => "nom d'utilisateur ou mot de passe incorrect",
                "success" => false
            ];

        }
    }
    public function logout()
    {
        // Logout logic would go here
        if (session_unset() && session_destroy()) {
            return [
                "message" => "deconnexion reussie",
                "success" => true
            ];
        } else {
            return [
                "message" => "echec de la deconnexion",
                "success" => false
            ];
        }
    }
}