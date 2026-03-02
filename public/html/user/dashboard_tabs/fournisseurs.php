<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">🚚 Fournisseurs</h5>
            <small class="text-muted">Gestion des fournisseurs</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm"
                id="fournisseurs-delete-selected">
                🗑️ Supprimer sélection
            </button>
            <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#ajouter-fournisseur">
                ➕ Ajouter
            </button>
        </div>
    </div>

    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <div class="input-group search-input">
                <span class="input-group-text">🔍</span>
                <input id="fournisseurs-search"
                    type="search"
                    class="form-control"
                    placeholder="Rechercher par nom ou email...">
            </div>

            <input type="number"
                id="fournisseurs-table-limit"
                min="1"
                value="10"
                class="form-control form-control-sm"
                style="width:80px;">
        </div>

        <div class="table-responsive">
            <table id="fournisseurs-table"
                class="table table-striped table-sm table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th style="width:36px;">
                            <input type="checkbox"
                                id="select-all-fournisseurs">
                        </th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Créé le</th>
                        <th>Modifié le</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr id="fournisseurs-empty-state">
                        <td colspan="8" class="text-center">
                            <div style="font-size:3rem;">📭</div>
                            <p><strong>Aucun fournisseur trouvé</strong></p>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <div id="fournisseurs-table-error-message"
            class="text-danger text-center mt-3"></div>

    </div>
</div>

<div class="modal fade"
    id="ajouter-fournisseur"
    tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ajouter fournisseur</h5>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="ajouter-fournisseur-form">

                    <div class="form-group p-2">
                        <label>Nom</label>
                        <input type="text"
                            name="fournisseur-nom"
                            class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Email</label>
                        <input type="email"
                            name="fournisseur-email"
                            class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Téléphone</label>
                        <input type="text"
                            name="fournisseur-telephone"
                            class="form-control border-primary border-1">
                    </div>

                    <div class="form-group p-2">
                        <label>Adresse</label>
                        <textarea name="fournisseur-adresse"
                            class="form-control border-primary border-1"></textarea>
                    </div>

                    <p id="ajouter-fournisseur-form-message"
                        class="text-center mt-3"></p>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Fermer
                </button>
                <button class="btn btn-primary"
                    type="submit"
                    form="ajouter-fournisseur-form">
                    Ajouter
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade"
    id="modifier-fournisseur"
    tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Modifier fournisseur</h5>
                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="modifier-fournisseur-form">

                    <input type="hidden" name="fournisseur-id">

                    <div class="form-group p-2">
                        <label>Nom</label>
                        <input type="text"
                            name="fournisseur-nom"
                            class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Email</label>
                        <input type="email"
                            name="fournisseur-email"
                            class="form-control border-primary border-1"
                            required>
                    </div>

                    <div class="form-group p-2">
                        <label>Téléphone</label>
                        <input type="text"
                            name="fournisseur-telephone"
                            class="form-control border-primary border-1">
                    </div>

                    <div class="form-group p-2">
                        <label>Adresse</label>
                        <textarea name="fournisseur-adresse"
                            class="form-control border-primary border-1"></textarea>
                    </div>

                    <p id="modifier-fournisseur-form-message"
                        class="text-center mt-3"></p>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Fermer
                </button>
                <button class="btn btn-primary"
                    type="submit"
                    form="modifier-fournisseur-form">
                    Modifier
                </button>
            </div>

        </div>
    </div>
</div>