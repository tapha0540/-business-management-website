<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">👤 Utilisateurs</h5>
            <small class="text-muted">Gestion complète des utilisateurs</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm" id="utilisateurs-delete-selected">
                🗑️ Supprimer sélection
            </button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-utilisateur">
                ➕ Ajouter un utilisateur
            </button>
        </div>
    </div>

    <div class="card-body">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="utilisateurs-search" type="search" class="form-control"
                    placeholder="Rechercher par nom, email...">
            </div>

            <input type="number" id="utilisateurs-table-limit" min="1" value="10" class="form-control form-control-sm"
                style="width: 80px;" />
        </div>

        <div class="table-responsive">
            <table id="utilisateurs-table" class="table table-striped table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;">
                            <input type="checkbox" id="select-all-utilisateurs" title="Sélectionner tout" />
                        </th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="utilisateurs-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check" /></td>
                        <td class="col-prenom">--</td>
                        <td class="col-nom">--</td>
                        <td class="col-email">--</td>
                        <td class="col-role"><span></span></td>
                        <td class="col-status"><span></span></td>
                        <td class="col-created">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit"
                                title="Éditer cet utilisateur" type="button" data-bs-toggle="modal"
                                data-bs-target="#modifier-utilisateur">✏️ Éditer</button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                title="Supprimer cet utilisateur">🗑️ Supprimer</button>
                        </td>
                    </tr>

                    <tr id="utilisateurs-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center">
                                <div style="font-size: 3rem;">📭</div>
                                <p><strong>Aucun utilisateur trouvé</strong></p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div id="utilisateurs-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<div class="modal fade" id="ajouter-utilisateur" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="ajouter-utilisateur-form">

                    <div class="form-group p-2">
                        <label>Prénom</label>
                        <input type="text" name="utilisateur-prenom" class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Nom</label>
                        <input type="text" name="utilisateur-nom" class="form-control border-primary border-1" required>
                    </div>

                    <div class="form-group p-2">
                        <label>Email</label>
                        <input type="email" name="utilisateur-email" class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Mot de passe</label>
                        <input type="password" name="utilisateur-mot_de_passe"
                            class="form-control border-primary border-1" required>
                    </div>

                    <div class="form-group p-2">
                        <label>Rôle</label>
                        <select name="utilisateur-role" class="form-control border-primary border-1" required>
                            <option value="vendeur">Vendeur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <p id="ajouter-utilisateur-form-message" class="text-center mt-3"></p>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fermer
                </button>
                <button type="submit" class="btn btn-primary" form="ajouter-utilisateur-form">
                    Ajouter
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modifier-utilisateur" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Modifier utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="modifier-utilisateur-form">

                    <input type="hidden" name="utilisateur-id" />

                    <div class="form-group p-2">
                        <label>Prénom</label>
                        <input type="text" name="utilisateur-prenom" class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Nom</label>
                        <input type="text" name="utilisateur-nom" class="form-control border-primary border-1" required>
                    </div>

                    <div class="form-group p-2">
                        <label>Email</label>
                        <input type="email" name="utilisateur-email" class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="utilisateur-mot_de_passe"
                            class="form-control border-primary border-1">
                    </div>

                    <div class="form-group p-2">
                        <label>Rôle</label>
                        <select name="utilisateur-role" class="form-control border-primary border-1" required>
                            <option value="vendeur">Vendeur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <p id="modifier-utilisateur-form-message" class="text-center mt-3"></p>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Fermer
                </button>
                <button type="submit" class="btn btn-primary" form="modifier-utilisateur-form">
                    Modifier
                </button>
            </div>

        </div>
    </div>
</div>