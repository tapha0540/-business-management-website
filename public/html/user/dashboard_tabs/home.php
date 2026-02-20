<canvas id="home-canvas" class="align-self-start m-1 p-2 w-75"></canvas>

<form id="dashboard-form"
    class="w-100 bg-lighter d-flex flex-row justify-content-around align-content-center p-2 border-3 border-primary shadow-sm rounded-2">
    <p class="text-center text-color my-auto">Les</p>
    <input type="number" name="limit" value="10" class="form-control w-25" style="max-width: 105px;" required />
    <select name="search" class="form-select w-25" style="max-width: 250px;">
        <option class="form-control" value="latest-orders">dernières commandes</option>
        <option class="form-control" value="best-orders">meilleures commandes par montant</option>
        <option class="form-control" value="best-sellers">meilleurs vendeurs</option>
        <option class="form-control" value="most-sold-products">produits les plus vendus</option>
        <option class="form-control" value="best-customers">meilleurs clients</option>
        <option class="form-control" value="product-at-risk-of-out-of-stock"> produits en risque de rupture</option>
    </select>
    <p class="text-center text-color my-auto">Du</p>

    <?php
    $date = new DateTime();
    $date->modify("-1 year");
    ?>

    <input type="date" name="from" value="<?= $date->format('Y-m-d') ?>" max="<?= date('Y-m-d'); ?>"
        class="form-control" style="max-width: 150px;" required />
    <p class="text-center text-color my-auto">à</p>
    <input type="date" name="to" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" class="form-control"
        style="max-width: 150px;" required />
    <button type="submit" class="btn btn-primary text-center text-color border-primary"
        style="max-width: 100px;">Afficher</button>
</form>

<?php $spinnerId = 'home-spinner';
require_once 'C:\Users\DELL\Dev\php\projet_final\public\html\component\spinner.php' ?>

<table id="home-table" class="table table-responsive table-sm table-hover table-borderless p-2 text-center shadow-sm rounded-3">
    <tfoot>
        <p id="error-message" class="text-danger text-center"></p>
    </tfoot>
</table>
