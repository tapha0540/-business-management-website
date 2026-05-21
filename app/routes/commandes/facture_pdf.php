<?php

require_once '../../config/app.php';
require_once '../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    exit;
}

try {

    require_once '../../config/database.php';
    require_once '../../models/DetailsCommande.php';
    require_once '../../utils/commandes/generer_facture_pdf.php';

    $commande_id = $_GET['commande_id'];

    $details = DetailsCommande::getFactureInfo(
        $pdo,
        $commande_id
    );

    genererPdf($commande_id, $details);

} catch (Exception $e) {

    error_log(
        "\n {$e->getFile()} -> {$e->getMessage()}",
        3,
        __DIR__ . '/../../storage/logs/error_log.log'
    );

    http_response_code(500);
    exit;
}