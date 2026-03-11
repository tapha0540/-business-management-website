<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <span class="app-icon app-icon-md" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-package-plus-icon lucide-package-plus">
                        <path d="M12 22V12" />
                        <path d="M16 17h6" />
                        <path d="M19 14v6" />
                        <path
                            d="M21 10.535V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.675-.955" />
                        <path d="M3.29 7 12 12l8.71-5" />
                        <path d="m7.5 4.27 8.997 5.148" />
                    </svg>
                </span>
                Approvisionnements
            </h5>
            <small class="text-muted">Gestion des commandes fournisseurs</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm" id="approvisionnements-delete-selected"><span class="app-icon"
                    aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg></span> Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-approvisionnement"><span class="app-icon" aria-hidden="true"><svg
                        viewBox="0 0 24 24">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg></span> Ajouter un approvisionnement</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg></span></span>
                <input id="approvisionnements-search" type="search" class="form-control"
                    placeholder="Rechercher par fournisseur...">
            </div>

            <div class="d-flex gap-2">
                <input type="number" name="approvisionnements-table-limit" id="approvisionnements-table-limit" min="1"
                    value="10" required class="approvisionnements-filter form-control form-control-sm max-w-80" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="approvisionnements-table" class="table table-striped table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="col-w-36"><input type="checkbox" id="select-all-approvisionnements"
                                title="Sélectionner tout" /></th>
                        <th>Fournisseur</th>
                        <th>Nb produits</th>
                        <th>Quantité totale</th>
                        <th>Montant total</th>
                        <th>Créé le</th>
                        <th>Mis à jour</th>
                        <th class="text-end col-w-140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empty state row shown when no data -->
                    <tr id="approvisionnements-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center prod-empty">
                                <div class="mb-3"><span class="app-icon app-icon-xl"
                                        aria-hidden="true"><svg viewBox="0 0 24 24">
                                            <path d="M3 7h18v10H3z"></path>
                                            <path d="m3 7 9 7 9-7"></path>
                                        </svg></span></div>
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
        <td class="approv-nb-produits">0</td>
        <td class="approv-quantite">0</td>
        <td class="approv-montant">0</td>
        <td class="approv-date">-</td>
        <td class="approv-updated">-</td>
        <td class="text-end table-actions-cell">
            <button class="btn btn-sm btn-outline-primary icon-btn" title="Éditer" aria-label="Éditer"
                onclick="modifierApprovisionnement(this)" type="button"><span class="app-icon" aria-hidden="true"><svg
                        viewBox="0 0 24 24">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                    </svg></span></button>
            <button class="btn btn-sm btn-outline-danger icon-btn" title="Supprimer" aria-label="Supprimer"
                onclick="supprimerApprovisionnement(this)" type="button"><span class="app-icon" aria-hidden="true"><svg
                        viewBox="0 0 24 24">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg></span></button>
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