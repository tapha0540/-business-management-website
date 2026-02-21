<h4>Commandes</h4>
<div class="d-flex justify-content-between align-content-center p-2 m-2 border-5 bg-lighter border-primary rounded-3">
    <h4>Commandes</h4>
    <div>
        <button class="btn btn-outline-secondary">Statistiques</button>
        <button class="btn btn-primary">Ajouter une commande</button>
    </div>

</div>
<table id="commandes-table"
    class="table table-responsive table-sm table-hover table-borderless p-2 text-center shadow-sm rounded-3">
    <thead>
        <tr>
            <th><input type="checkbox" /></th>
            <th>Id</th>
            <th>Id du vendeur</th>
            <th>Id du client</th>
            <th>Etat</th>
            <th>Créée le</th>
            <th>Modifiée le</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
        <p id="commandes-table-error-message" class="text-danger text-center"></p>
    </tfoot>
</table>