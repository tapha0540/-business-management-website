<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">📦 Approvisionnements</h5>
            <small class="text-muted">Gestion des commandes fournisseurs</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm" id="approvisionnements-delete-selected">🗑️ Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-approvisionnement">➕ Ajouter un approvisionnement</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text">🔍</span>
                <input id="approvisionnements-search" type="search" class="form-control"
                    placeholder="Rechercher par fournisseur...">
            </div>

            <div class="d-flex gap-2">
                <input type="number" name="approvisionnements-table-limit" id="approvisionnements-table-limit" min="1"
                    value="10" required class="approvisionnements-filter form-control form-control-sm"
                    style="max-width: 80px;" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="approvisionnements-table" class="table table-striped table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-approvisionnements"
                                title="Sélectionner tout" /></th>
                        <th>Fournisseur</th>
                        <th>Produits</th>
                        <th>Quantité totale</th>
                        <th>Montant</th>
                        <th>Créé le</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empty state row shown when no data -->
                    <tr id="approvisionnements-empty-state" class="table-empty">
                        <td colspan="7">
                            <div class="text-center prod-empty">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                <p class="mb-2"><strong>Aucun approvisionnement trouvé</strong></p>
                                <p class="small">Créez un approvisionnement pour ajouter des produits depuis un
                                    fournisseur.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="approvisionnements-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<!-- Template for table rows -->
<template id="approvisionnements-row-template">
    <tr class="approvisionnements-row">
        <td><input type="checkbox" class="approvisionnements-checkbox" value="0" /></td>
        <td class="approv-fournisseur">-</td>
        <td class="approv-produits">-</td>
        <td class="approv-quantite">0</td>
        <td class="approv-montant">0</td>
        <td class="approv-date">-</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-primary" title="Éditer" onclick="modifierApprovisionnement(this)"
                type="button">✏️</button>
            <button class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="supprimerApprovisionnement(this)"
                type="button">🗑️</button>
        </td>
    </tr>
</template>

<!-- Modal pour ajouter un Approvisionnement -->
<div class="modal fade" id="ajouter-approvisionnement" tabindex="-1" aria-labelledby="ajouterApprovisionnementLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ajouterApprovisionnementLabel">Ajouter un approvisionnement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajouter-approvisionnement-form">
                    <div class="form-group p-2">
                        <label for="approvisionnement-fournisseur">Fournisseur</label>
                        <select class="form-control border-primary border-1" id="approvisionnement-fournisseur"
                            name="approvisionnement-fournisseur" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                        </select>
                    </div>

                    <div class="p-2">
                        <label>Produits</label>
                        <div id="approvisionnement-details-container">
                            <!-- Product rows will be added here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="ajouter-detail-approv">+
                            Ajouter un produit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="ajouter-approvisionnement-submit">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer un Approvisionnement -->
<div class="modal fade" id="modifier-approvisionnement" tabindex="-1" aria-labelledby="modifierApprovisionnementLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modifierApprovisionnementLabel">Éditer l'approvisionnement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifier-approvisionnement-form">
                    <input type="hidden" id="approv-modifier-id" />
                    <div class="form-group p-2">
                        <label>Fournisseur</label>
                        <input type="text" class="form-control" id="approv-modifier-fournisseur" readonly />
                    </div>

                    <div class="p-2">
                        <label>Produits</label>
                        <div id="approv-modifier-details-container">
                            <!-- Product rows will be added here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="ajouter-detail-modifier">+
                            Ajouter un produit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="modifier-approvisionnement-submit">Mettre à
                    jour</button>
            </div>
        </div>
    </div>
</div>

<style>
    .approv-detail-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-end;
    }

    .approv-detail-row input,
    .approv-detail-row select {
        flex: 1;
        font-size: 0.9rem;
    }

    .approv-detail-row button {
        flex-shrink: 0;
    }
</style>