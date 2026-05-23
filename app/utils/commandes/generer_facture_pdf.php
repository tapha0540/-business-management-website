<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function genererPdf(int $command_id, array $details_commandes)
{
    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $tbody = "";
    $total = 0;

    foreach ($details_commandes as $details_commande) {

        $nom = htmlspecialchars($details_commande['nom']);
        $categorie = htmlspecialchars($details_commande['categorie']);

        $prix = (float) $details_commande['prix_vente'];
        $quantite = (int) $details_commande['quantite'];

        $sousTotal = $prix * $quantite;

        // $imgUrl = !empty($details_commande['imgUrl'])
        //     ? __DIR__ . '/../../storage/uploads/images/produits/' . $details_commande['imgUrl']
        //     : "";

        // $imageHtml = $imgUrl
        //     ? "<img class='product-image' src='$imgUrl' alt='Produit' width='500' height='500'>"
        //     : "<div class='placeholder'>" . strtoupper($nom[0]) . "</div>";

        $tbody .= "
            <tr>
                
                <td>$nom</td>
                <td>$categorie</td>
                <td>" . number_format($prix, 0, ',', ' ') . " FCFA</td>
                <td>$quantite</td>
                <td>" . number_format($sousTotal, 0, ',', ' ') . " FCFA</td>
            </tr>
        ";

        $total += $sousTotal;
    }

    $date = date('d/m/Y H:i');

    $html = "
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>

        <style>

            body {
                font-family: DejaVu Sans, sans-serif;
                color: #333;
                padding: 20px;
            }

            .header {
                margin-bottom: 30px;
            }

            .title {
                font-size: 32px;
                font-weight: bold;
                color: #2563eb;
                margin-bottom: 10px;
            }

            .info {
                font-size: 14px;
                color: #666;
            }

            .card {
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                overflow: hidden;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead {
                background-color: #2563eb;
                color: white;
            }

            thead th {
                padding: 14px;
                font-size: 14px;
                text-align: left;
            }

            tbody td {
                padding: 12px;
                border-bottom: 1px solid #e5e7eb;
                vertical-align: middle;
                font-size: 13px;
            }

            tbody tr:nth-child(even) {
                background-color: #f9fafb;
            }

            .product-image {
                width: 55px;
                height: 55px;
                border-radius: 8px;
                object-fit: cover;
            }

            .placeholder {
                width: 55px;
                height: 55px;
                line-height: 55px;
                text-align: center;
                border-radius: 8px;
                background-color: #f59e0b;
                color: white;
                font-weight: bold;
            }

            .total-section {
                margin-top: 20px;
                width: 300px;
                margin-left: auto;
            }

            .total-table td {
                padding: 10px;
                border: 1px solid #ddd;
            }

            .grand-total {
                background-color: #2563eb;
                color: white;
                font-weight: bold;
            }

            .footer {
                margin-top: 40px;
                text-align: center;
                font-size: 12px;
                color: #888;
            }

        </style>
    </head>

    <body>

        <div class='header'>
            <div class='title'>FACTURE</div>

            <div class='info'>
                <strong>Commande :</strong> #$command_id <br>
                <strong>Date :</strong> $date
            </div>
        </div>

        <div class='card'>

            <table>

                <thead>
                    <tr>
                        
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Qté</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>

                <tbody>
                    $tbody
                </tbody>

            </table>

        </div>

        <div class='total-section'>

            <table class='total-table'>

                <tr class='grand-total'>
                    <td>Total</td>
                    <td>
                        " . number_format($total, 0, ',', ' ') . " FCFA
                    </td>
                </tr>

            </table>

        </div>

        <div class='footer'>
            Merci pour votre confiance.
        </div>

    </body>
    </html>
    ";

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    header('Content-Type: application/pdf');
    $dompdf->stream();
}
