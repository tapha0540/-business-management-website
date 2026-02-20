<?php


require_once __DIR__ . '/../models/Utilisateur.php';


class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* ================= SIGNUP ================= */
    public function signup(
        string $first_name,
        string $last_name,
        string $email,
        string $password,
        string $role = 'vendeur'
    ): array {
        $userModel = new Utilisateur($this->pdo);

        // Vérifier email existant
        if ($userModel->get(email: $email)) {
            return [
                'message' => 'Un utilisateur avec cet email existe déjà.',
                'success' => false
            ];
        }

        // Créer utilisateur
        $userModel = new Utilisateur(
            $this->pdo,
            null,
            $first_name,
            $last_name,
            $email,
            $password,
            $role
        );

        if ($userModel->create()) {
            return [
                'message' => 'Utilisateur créé avec succès',
                'success' => true
            ];
        }

        return [
            'message' => 'Échec de la création du compte',
            'success' => false
        ];
    }

    /* ================= LOGIN ================= */
    public function login(string $email, string $password): array
    {
        $userModel = new Utilisateur($this->pdo);
        $user = $userModel->get(email: $email);

        if (!$user) {
            return [
                'message' => 'Email incorrect.',
                'success' => false
            ];
        }
        error_log((password_verify(trim($password), $user['mot_de_passe']) ? 'True' : 'false'), 3, 'C:\Users\DELL\Dev\php\projet_final\app\storage\logs\error_log.log');
        if (!password_verify($password, $user['mot_de_passe'])) {
            return [
                'message' => 'Mot de passe incorrect.',
                'success' => false
            ];
        }

        // Session 2 mois
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 24 * 60,
            'path' => '/',
            'secure' => false, // true si HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
        session_regenerate_id(true);

        unset($user['mot_de_passe']); // on enleve le mot de passe pour des raisons de sécurité.
        $_SESSION['user'] = $user;

        return [
            'message' => 'Connexion réussie',
            'success' => true,
            'user' => $user
        ];
    }

    /* ================= LOGOUT ================= */
    public function logout(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return [
                'message' => "Vous n'êtes pas connecté.",
                'success' => false
            ];
        }

        session_unset();
        session_destroy();

        return [
            'message' => 'Déconnexion réussie',
            'success' => true
        ];
    }
}
