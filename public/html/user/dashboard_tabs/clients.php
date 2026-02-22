
<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">👥 Clients</h5>
            <small  class="text-muted">Gestion de votre portefeuille clients</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm">📊 Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="clients-delete-selected" disabled>🗑️ Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm">➕ Ajouter un client</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="clients-search" type="search" class="form-control"
                    placeholder="Rechercher par nom, email, téléphone...">
            </div>

            <div class="d-flex gap-2">
                <select id="clients-filter-status" class="form-select form-select-sm">
                    <option value="">Tous les clients</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm">Exporter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="clients-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-clients" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="clients-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check form-check-input" /></td>
                        <td class="col-name">--</td>
                        <td class="col-email">--</td>
                        <td class="col-phone">--</td>
                        <td class="col-address">--</td>
                        <td class="col-status"><span class="activity-badge activity-active">Actif</span></td>
                        <td class="col-created">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit" title="Éditer ce client">✏️
                                Éditer</button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                title="Supprimer ce client">🗑️ Supprimer</button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr id="clients-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center cli-empty">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                <p class="mb-2"><strong>Aucun client trouvé</strong></p>
                                <p class="small">Vous n'avez pas encore de clients. Cliquez sur "Ajouter un client" pour
                                    en créer un.</p>
                                <div class="mt-4">
                                    <button class="btn btn-primary btn-sm">➕ Ajouter un client</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="clients-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>