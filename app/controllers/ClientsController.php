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
    public function get()
    {
        try {
            $reqData = json_decode(file_get_contents('php://input'), true);
            $id = $reqData['client_id'];

            $client = $this->clientsModel->get($id);

            echo json_encode([
                'message' => 'Opération réussie',
                'success' => true,
                'data' => $client
            ]);
        } catch (Exception $e) {
            error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../storage/logs/error_log.log');
            echo json_encode([
                'message' => 'Erreur cote serveur',
                'success' => false
            ]);
        }
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
    public function create()
    {
        try {
            $reqData = json_decode(file_get_contents('php://input'), true);
            $prenom = $reqData['prenom'];
            $nom = $reqData['nom'];
            $telephone = $reqData['telephone'];
            $email = $reqData['email'];
            $image = $reqData['image'];

            if (!$prenom || !$nom || !$telephone || !$email) {
                throw new Exception("Données incomplètes");
            }

            $clientModel = new Clients($this->pdo, null, $prenom, $nom, $email, $telephone);
            require_once __DIR__ . '/../utils/clients/enregistrerClientImg.php';
            $imgName = EnregistrerClientImg($image);
            if (!$imgName) {
                error_log('\n ' . __FILE__ . ' -> Erreur lors de l\'enregistrement de l\'image du client', 3, __DIR__ . '/../storage/logs/error_log.log');
            }
            $clientModel->setImgUrl($imgName);
            if ($clientModel->create()) {
                echo json_encode([
                    'message' => 'Client créé avec succès',
                    'success' => true
                ]);
            } else {
                throw new Exception("Erreur lors de la création du client");
            }
        } catch (Exception $e) {
            error_log('\n ' . $e->getFile() . ' -> ' . $e->getMessage(), 3, __DIR__ . '/../storage/logs/error_log.log');
            echo json_encode([
                'message' => 'Erreur cote serveur',
                'success' => false
            ]);
        }
    }
    public function delete()
    {
        try {
            $reqData = json_decode(file_get_contents('php://input'), true);
            $clientsIds = $reqData['clients_ids'];

            $success = true;
            foreach ($clientsIds as $id) {
                $client = new Clients($this->pdo, $id);
                if (!$client->delete()) {
                    $success = false;
                    error_log('\n ' . __FILE__ . " -> Erreur lors de la suppression du client d'id: $id", 3, __DIR__ . '/../storage/logs/error_log.log');
                }
            }

            echo json_encode([
                'message' => $success ? 'Opération réussie' : 'Erreur lors de la suppression des clients',
                'success' => $success
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
