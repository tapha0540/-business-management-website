<?php

require_once 'C:\Users\DELL\Dev\php\projet_final\app\models\Utilisateur.php';


class AuthController
{
    private PDO $pdo;
    private Utilisateur $userModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function signup(string $first_name, string $last_name, string $email, string $password, string $role)
    {
        // Logique de creation d'un nouvel utilisateur

        $this->userModel = new Utilisateur(
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

        if ($this->userModel->create()) {
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
        $user = $this->userModel->get(email: $email);
        if ($user) {

            if (!password_verify($password, $user['mot_de_passe'])) {
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