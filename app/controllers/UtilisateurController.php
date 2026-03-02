<?php

require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController
{
    private PDO $pdo;
    private Utilisateur $model;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->model = new Utilisateur($pdo);
    }

    public function create(array $data)
    {
        $prenom = $data['prenom'] ?? null;
        $nom = $data['nom'] ?? null;
        $email = $data['email'] ?? null;
        $mot_de_passe = $data['mot_de_passe'] ?? null;
        $role = $data['role'] ?? 'vendeur';

        if (!$prenom || !$nom || !$email || !$mot_de_passe) {
            throw new Exception('Prénom, nom, email et mot de passe requis');
        }

        // Check if email already exists
        $existing = $this->model->get(email: $email);
        if ($existing) {
            throw new Exception('Cet email existe déjà');
        }

        $user = new Utilisateur($this->pdo, null, $prenom, $nom, $email, $mot_de_passe, $role);

        $image = $data['image'] ?? null;
        $imgUrl = null;

        if ($image) {
            require_once __DIR__ . '/../utils/utilisateurs/enregistrerUtilisateurImg.php';
            $imgUrl = EnregistrerUtilisateurImg($image);
        }

        // Create new Utilisateur with image
        $user = new Utilisateur($this->pdo, null, $prenom, $nom, $email, $mot_de_passe, $role, $imgUrl);
        return $user->create();
    }

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function get(int $id)
    {
        return $this->model->get($id);
    }

    public function update(int $id, array $data)
    {
        $user = $this->model->get($id);
        if (!$user) {
            throw new Exception('Utilisateur introuvable');
        }

        $prenom = $data['prenom'] ?? null;
        $nom = $data['nom'] ?? null;
        $email = $data['email'] ?? null;
        $mot_de_passe = $data['mot_de_passe'] ?? null;

        // Check if new email already exists (if different from current)
        if ($email && $email !== $user['email']) {
            $existing = $this->model->get(email: $email);
            if ($existing) {
                throw new Exception('Cet email existe déjà');
            }
        }

        $userModel = new Utilisateur($this->pdo, $id);

        $image = $data['image'] ?? null;
        $imgUrl = null;

        if ($image) {
            require_once __DIR__ . '/../utils/utilisateurs/enregistrerUtilisateurImg.php';
            require_once __DIR__ . '/../utils/utilisateurs/deleteUtilisateurImg.php';

            // Delete old image if exists
            if ($user['imgUrl']) {
                deleteUtilisateurImage($user['imgUrl']);
            }

            $imgUrl = EnregistrerUtilisateurImg($image);
        }

        return $userModel->update($prenom, $nom, $email, $mot_de_passe, $imgUrl);
    }

    public function delete(int $id)
    {
        $user = new Utilisateur($this->pdo, $id);
        return $user->delete();
    }
}
