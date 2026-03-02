<?php
require_once __DIR__ . '/../models/Commandes.php';

class CommandeController
{
    private PDO $pdo;
    private Commandes $commandeModel;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->commandeModel = new Commandes($this->pdo);
    }

    public function getAll(int $limit)
    {
        return $this->commandeModel->getAll($limit);
    }

    public function get(int $id)
    {
        return $this->commandeModel->get($id);
    }

    public function create(array $data)
    {
        $vendeur_id = $data['vendeur_id'] ?? null;
        $client_id = $data['client_id'] ?? null;
        $date_commande = $data['date_commande'] ?? null;
        $etat = $data['etat'] ?? 'en_cours';
        if (!$vendeur_id || !$client_id) {
            throw new Exception('vendeur_id et client_id requis');
        }
        $cmd = new Commandes($this->pdo, null, $vendeur_id, $client_id, $date_commande, $etat);
        return $cmd->create();
    }

    public function update(int $id, array $data)
    {
        $new_etat = $data['etat'] ?? null;
        if (!$new_etat) {
            throw new Exception('etat requis');
        }
        $cmd = new Commandes($this->pdo, $id);
        return $cmd->update($new_etat);
    }

    public function delete(int $id)
    {
        $cmd = new Commandes($this->pdo, $id);
        return $cmd->delete();
    }
}