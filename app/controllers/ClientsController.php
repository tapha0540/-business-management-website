<?php

require_once __DIR__ . '/../models/Client.php';


class ClientsController
{
    private PDO $pdo;
    private Clients $clientsModel;
    public function __construct(PDO &$pdo)
    {
        $this->pdo = $pdo;
        $this->clientsModel = new Clients($this->pdo);
    }
    public function getAll()
    {
        try {
            $reqData = json_decode(file_get_contents('php://input'), true);
            $limit = $reqData['limit'];
            $search = $reqData['search'];

            $clients = $this->clientsModel::getAll($this->pdo, $limit, $search);

            echo json_encode([
                'message' => 'Opération réussie',
                'success' => true,
                'data' => $clients
            ]);
        } catch (Exception $e) {
            error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../storage/logs/error_log.log');
            echo json_encode([
                'message' => 'Erreur cote serveur',
                'success' => false
            ]);
        }
    }
}
