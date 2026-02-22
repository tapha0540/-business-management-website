<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">⚙️ Utilisateurs</h5>
            <small class="text-muted">Gestion des utilisateurs et permissions</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm">📊 Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="users-delete-selected" disabled>🗑️ Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm">➕ Ajouter un utilisateur</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="users-search" type="search" class="form-control"
                    placeholder="Rechercher par nom, email, rôle...">
            </div>

            <div class="d-flex gap-2">
                <select id="users-filter-role" class="form-select form-select-sm">
                    <option value="">Tous les rôles</option>
                    <option value="admin">Admin</option>
                    <option value="seller">Vendeur</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm">Exporter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="users-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-users" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="users-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check form-check-input" /></td>
                        <td class="col-prenom">--</td>
                        <td class="col-nom">--</td>
                        <td class="col-email">--</td>
                        <td class="col-role"><span class="role-badge role-user">User</span></td>
                        <td class="col-status"><span class="status-badge status-active">Actif</span></td>
                        <td class="col-created">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit"
                                title="Éditer cet utilisateur">✏️ Éditer</button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                title="Supprimer cet utilisateur">🗑️ Supprimer</button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr id="users-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center usr-empty">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                                <p class="mb-2"><strong>Aucun utilisateur trouvé</strong></p>
                                <p class="small">Vous devez ajouter au moins un utilisateur. Cliquez sur "Ajouter un
                                    utilisateur" pour en créer un.</p>
                                <div class="mt-4">
                                    <button class="btn btn-primary btn-sm">➕ Ajouter un utilisateur</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="users-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>