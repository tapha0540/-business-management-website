
<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">📦 Produits</h5>
            <small class="text-muted">Gestion complète de vos produits</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm">📊 Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="produits-delete-selected" disabled>🗑️ Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm">➕ Ajouter un produit</button>
        </div>
    </div>

    <div class="card-body">
        <div
           class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="produits-search" type="search" class="form-control"
                    placeholder="Rechercher par nom, référence, catégorie...">
            </div>

            <div class="d-flex gap-2">
                <select id="produits-filter-stock" class="form-select form-select-sm">
                    <option value="">Tout le stock</option>
                    <option value="low">Stock faible</option>
                    <option value="medium">Stock normal</option>
                    <option value="high">Stock élevé</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm">Exporter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="produits-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-produits" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Référence</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="produits-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check form-check-input" /></td>
                        <td class="col-reference">--</td>
                        <td class="col-name">--</td>
                        <td class="col-category">--</td>
                        <td class="col-price">--</td>
                        <td class="col-stock"><span class="stock-badge stock-high">--</span></td>
                        <td class="col-status">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit" title="Éditer ce produit">✏️
                                Éditer</button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                title="Supprimer ce produit">🗑️ Supprimer</button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr id="produits-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center prod-empty">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                <p class="mb-2"><strong>Aucun produit trouvé</strong></p>
                                <p class="small">Vous n'avez pas encore de produits. Cliquez sur "Ajouter un produit"
                                    pour en créer un.</p>
                                <div class="mt-4">
                                    <button class="btn btn-primary btn-sm">➕ Ajouter un produit</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="produits-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>