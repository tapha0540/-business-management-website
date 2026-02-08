<form
    class="w-100 bg-lighter d-flex flex-row justify-content-around align-content-center p-2 border-3 border-primary shadow-sm rounded-2">
    <p class="text-center text-color my-auto">Les</p>
    <input type="number" value="10" class="form-control w-25" style="max-width: 105px;" />
    <select name="filter" class="form-select w-25" style="max-width: 250px;">
        <option class="form-control">dernières commandes</option>
        <option class="form-control">meilleures commandes par montant</option>
        <option class="form-control">meilleurs vendeurs</option>
        <option class="form-control"> produits les plus vendus</option>
        <option class="form-control">meilleurs clients</option>
        <option class="form-control"> produits en risque de rupture</option>
    </select>
    <p class="text-center text-color my-auto">Du</p>

    <?php
    $date = new DateTime();
    $date->modify("-7 day");
    ?>

    <input type="date" value="<?= $date->format('Y-m-d') ?>" max="<?= date('Y-m-d'); ?>" class="form-control"
        style="max-width: 150px;" />
    <p class="text-center text-color my-auto">à</p>
    <input type="date" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" class="form-control"
        style="max-width: 150px;" />
    <button type="submit" class="btn btn-primary text-center text-color border-primary" style="max-width: 100px;">Afficher</button>
</form>