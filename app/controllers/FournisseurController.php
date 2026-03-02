<?php

require_once __DIR__ . '/../models/Fournisseur.php';

class FournisseurController
{
    private PDO $pdo;
    private Fournisseur $model;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->model = new Fournisseur($this->pdo, 0, '', '', '', '', '', '');
    }

    public function create(array $data)
    {
        $nom = $data['nom'] ?? null;
        $email = $data['email'] ?? null;
        $telephone = $data['telephone'] ?? null;
        $adresse = $data['adresse'] ?? null;
        if (!$nom || !$email) {
            throw new Exception('Nom et email requis');
        }
        $f = new Fournisseur($this->pdo, 0, $nom, $email, $telephone ?? '', $adresse ?? '', '', '');
        return $f->create();
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
        $nom = $data['nom'] ?? null;
        $email = $data['email'] ?? null;
        $telephone = $data['telephone'] ?? null;
        $adresse = $data['adresse'] ?? null;
        if (!$nom || !$email) {
            throw new Exception('Nom et email requis');
        }
        $this->model = new Fournisseur($this->pdo, $id, $nom, $email, $telephone ?? '', $adresse ?? '', '', '');
        return $this->model->update($nom, $email, $telephone ?? '', $adresse ?? '');
    }

    public function delete(int $id)
    {
        $this->model = new Fournisseur($this->pdo, $id, '', '', '', '', '', '');
        return $this->model->delete();
    }
}
