<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Commandes</h5>
            <small class="text-muted">Vue d'ensemble des commandes récentes</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm">Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="commandes-delete-selected" disabled>Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-commande">Ajouter une commande</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="commandes-search" type="search" class="form-control"
                    placeholder="Rechercher par id, client, statut...">
            </div>

            <div class="d-flex gap-2">
                <select id="commandes-filter-status" class="form-select form-select-sm">
                    <option value="all">Tous les statuts</option>
                    <option value="en_cours">En cours</option>
                    <option value="annulee">Annulée</option>
                    <option value="cloturee">Cloturée</option>
                </select>
                <input type="number" name="commandes-table-limit" id="commandes-table-limit" min="1" value="10" required
                    class="form-control form-control-sm" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="commandes-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" /></th>
                        <th>Id</th>
                        <th>Vendeur</th>
                        <th>Client</th>
                        <th>Etat</th>
                        <th>Créée le</th>
                        <th>Modifiée le</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Empty state row shown when no data -->
                    <tr class="table-empty">
                        <td colspan="8">
                            <div class="text-center cmd-empty">
                                <p class="mb-2"><strong>Aucune commande trouvée</strong></p>
                                <p class="small">Vous n'avez pas encore de commandes. Cliquez sur "Ajouter une commande"
                                    pour en créer une.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="commandes-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>


<!-- Popover pour ajouter commande -->
<div class="modal fade" id="ajouter-commande" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Une commande</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="client_id">Choisi un client:</label>
                    <input list="clients" id="client_id" name="client_id" />
                    <datalist id="clients">
                        <option value="Chrome">gg</option>
                        <option value="Firefox"></option>
                        <option value="Opera"></option>
                        <option value="Safari"></option>
                        <option value="Microsoft Edge"></option>
                    </datalist>

                </div>
            </div>
            <div class="modal-body">
                <form id="add-commande-form">
                    <div class="mb-3">
                        <label for="commande_date" class="form-label">Date de commande:</label>
                        <input type="date" class="form-control" id="commande_date" name="commande_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>