<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <span class="app-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 3v18h18"></path>
                        <rect x="7" y="11" width="3" height="6"></rect>
                        <rect x="12" y="8" width="3" height="9"></rect>
                        <rect x="17" y="6" width="3" height="11"></rect>
                    </svg>
                </span> Catégories
            </h5>
            <small class="text-muted">Gestion des catégories de produits</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-danger btn-sm" id="categories-delete-selected"><span class="app-icon"
                    aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg></span> Supprimer sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-categorie"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg></span> Ajouter une catégorie</button>
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
                <input id="categories-search" type="search" class="form-control form-control-sm"
                    placeholder="Rechercher par nom ou description...">
            </div>
            <div class="d-flex gap-2">
                <input type="number" name="categories-table-limit" id="categories-table-limit" min="1" value="10"
                    required class="categories-filter form-control form-control-sm" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="categories-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="col-w-36"><input type="checkbox" id="select-all-categories" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Créé le</th>
                        <th>Modifié le</th>
                        <th class="text-end col-w-140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="categories-row-template" class="d-none">
                        <td><input type="checkbox" class="category-checkbox" /></td>
                        <td class="category-nom">--</td>
                        <td class="category-description">--</td>
                        <td class="category-created-at">--</td>
                        <td class="category-updated-at">--</td>
                        <td class="text-end table-actions-cell">
                            <button type="button" class="btn btn-sm btn-outline-info btn-action icon-btn"
                                title="Modifier" aria-label="Modifier" data-bs-toggle="modal"
                                data-bs-target="#modifier-categorie"><span class="app-icon" aria-hidden="true"><svg
                                        viewBox="0 0 24 24">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                    </svg></span></button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action icon-btn"
                                title="Supprimer" aria-label="Supprimer"><span class="app-icon" aria-hidden="true"><svg
                                        viewBox="0 0 24 24">
                                        <path d="M3 6h18"></path>
                                        <path d="M8 6V4h8v2"></path>
                                        <path d="M19 6l-1 14H6L5 6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                    </svg></span></button>
                        </td>
                    </tr>
                    <tr id="categories-empty-state" class="table-empty">
                        <td colspan="6">
                            <div class="text-center cat-empty">
                                <div class="mb-3"><span class="app-icon app-icon-xl" aria-hidden="true"><svg
                                            viewBox="0 0 24 24">
                                            <path d="M3 7h18v10H3z"></path>
                                            <path d="m3 7 9 7 9-7"></path>
                                        </svg></span></div>
                                <p class="mb-2"><strong>Aucune catégorie trouvée</strong></p>
                                <p class="small">Créez une catégorie pour organiser vos produits.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="categories-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<div class="modal fade" id="ajouter-categorie" tabindex="-1" aria-labelledby="ajouterCategorieLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ajouterCategorieLabel">Ajouter une catégorie</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajouter-categorie-form">
                    <div class="form-group p-2">
                        <label for="categorie-nom">Nom de la catégorie</label>
                        <input type="text" class="form-control border-primary border-1" id="categorie-nom"
                            name="categorie-nom" placeholder="Nom de la catégorie" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="categorie-description">Description</label>
                        <textarea class="form-control border-primary border-1" id="categorie-description"
                            name="categorie-description" placeholder="Description de la catégorie"></textarea>
                    </div>
                    <p id="ajouter-categorie-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="ajouter-categorie-form">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modifier-categorie" tabindex="-1" aria-labelledby="modifierCategorieLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modifierCategorieLabel">Modifier la catégorie</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifier-categorie-form">
                    <input type="hidden" name="categorie-id" />
                    <div class="form-group p-2">
                        <label for="modifier-categorie-nom">Nom de la catégorie</label>
                        <input type="text" class="form-control border-primary border-1" id="modifier-categorie-nom"
                            name="categorie-nom" placeholder="Nom de la catégorie" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="modifier-categorie-description">Description</label>
                        <textarea class="form-control border-primary border-1" id="modifier-categorie-description"
                            name="categorie-description" placeholder="Description de la catégorie"></textarea>
                    </div>
                    <p id="modifier-categorie-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="modifier-categorie-form">Modifier</button>
            </div>
        </div>
    </div>
</div>