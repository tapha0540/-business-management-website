<?php

require_once 'C:\Users\DELL\Dev\php\projet_final\app\models\Utilisateur.php';


class AuthController
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function signup(string $first_name, string $last_name, string $email, string $password, string $role)
    {
        // Logique de creation d'un nouvel utilisateur

        $userModel = new Utilisateur(
            $this->pdo,
            -1,
            $first_name,
            $last_name,
            $email,
            $password,
            $role,
            '',
            ''
        );

        $user = $userModel->get(email: $email);
        if ($user) {
            return [
                'message' => 'Un utilisateur avec un tel email existe deja.',
                'success' => false
            ];
        }
        if ($userModel->create()) {
            return [
                "message" => "utilisateur créé avec succès",
                "success" => true
            ];
        } else {
            return [
                "message" => "échec de la création du compte utilisateur",
                "success" => false
            ];
        }
    }
    public function login(string $email, string $password)
    {
        // logique de connexion d'un utilisateur
        $userModel = new Utilisateur(pdo: $this->pdo, email: $email, mot_de_passe: $password);

        $user = $userModel->get(email: $email);
        if ($user) {
            if (!password_verify($password, $user['mot_de_passe'])) {
                return [
                    "message" => "mot de passe incorrect",
                    "success" => false
                ];
            }
            error_log("\n $password", 3, 'C:\Users\DELL\Dev\php\projet_final\app\storage\logs\error_log.log');

            $lifetime = 60 * 60 * 24 * 60; // 60 jours = 2 mois

            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => '/',
                'domain' => '',
                'secure' => false, // true si HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();

            $_SESSION['user'] = $user;

            return [
                "message" => "connexion reussie",
                "success" => true,
                "user" => $user
            ];

        } else {
            return [
                "message" => "Email incorrect.",
                "success" => false
            ];

        }
    }
    public function logout()
    {
        // Logout logic would go here
        if (session_status() === PHP_SESSION_ACTIVE) {
            return [
                'message' => "Vous n'etes pas connecte.",
                'success' => false
            ];
        }

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