
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
            <button class="btn btn-primary btn-sm">Ajouter une commande</button>
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
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="processing">En cours</option>
                    <option value="shipped">Expédiée</option>
                    <option value="delivered">Livrée</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm">Exporter</button>
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
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="commandes-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td class="col-id">#ID</td>
                        <td class="col-seller">Vendeur</td>
                        <td class="col-client">Client</td>
                        <td class="col-status"><span class="badge bg-secondary">Statut</span></td>
                        <td class="col-created">--</td>
                        <td class="col-updated">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary btn-edit" title="Éditer">✏️</button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" title="Supprimer">🗑️</button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr class="table-empty">
                        <td colspan="8">
                            <div class="text-center cmd-empty">
                                <p class="mb-2"><strong>Aucune commande trouvée</strong></p>
                                <p class="small">Vous n'avez pas encore de commandes. Cliquez sur "Ajouter une commande"
                                    pour en créer une.</p>
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm">Ajouter une commande</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="commandes-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>