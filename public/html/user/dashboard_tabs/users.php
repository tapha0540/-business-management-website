<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.9"></path><path d="M16 3.1a4 4 0 0 1 0 7.8"></path></svg></span> Utilisateurs</h5>
            <small class="text-muted">Gestion complète des utilisateurs</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm" id="utilisateurs-delete-selected">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span> Supprimer sélection
            </button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-utilisateur">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg></span> Ajouter un utilisateur
            </button>
        </div>
    </div>

    <div class="card-body">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path></svg></span></span>
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
                        <td class="text-end table-actions-cell">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit icon-btn" title="Éditer cet utilisateur" aria-label="Éditer cet utilisateur" type="button" data-bs-toggle="modal" data-bs-target="#modifier-utilisateur"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg></span></button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete icon-btn" title="Supprimer cet utilisateur" aria-label="Supprimer cet utilisateur"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
                        </td>
                    </tr>

                    <tr id="utilisateurs-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center">
                                <div><span class="app-icon" style="width:3rem;height:3rem;" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 7h18v10H3z"></path><path d="m3 7 9 7 9-7"></path></svg></span></div>
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