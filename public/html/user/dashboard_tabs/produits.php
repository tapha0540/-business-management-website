<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 0 0-2-2H5l-2 4v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"></path><path d="M3 10h18"></path></svg></span> Produits</h5>
            <small class="text-muted">Gestion complète de vos produits</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 3v18h18"></path><rect x="7" y="11" width="3" height="6"></rect><rect x="12" y="8" width="3" height="9"></rect><rect x="17" y="6" width="3" height="11"></rect></svg></span> Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="produits-delete-selected"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span> Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-produit"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg></span> Ajouter un produit</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path></svg></span></span>
                <input id="produits-search" type="search" class="form-control"
                    placeholder="Rechercher par nom, référence, catégorie...">
            </div>

            <div class="d-flex gap-2">
                <select id="produits-filter-stock" class="produits-filter form-select form-select-sm">
                    <option value="">Tout le stock</option>
                    <option value="low">Stock faible</option>
                    <option value="medium">Stock normal</option>
                    <option value="high">Stock élevé</option>
                </select>
                <input type="number" name="produits-table-limit" id="produits-table-limit" min="1" value="10" required
                    class="produits-filter form-control form-control-sm" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="produits-table" class="table table-striped table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-produits"
                                title="Sélectionner tout" /></th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Seuil critique</th>
                        <th>Créé le</th>
                        <th>Modifié le</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Empty state row shown when no data -->
                    <tr id="produits-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center prod-empty">
                                <div style="margin-bottom: 1rem;"><span class="app-icon" style="width:3rem;height:3rem;" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 7h18v10H3z"></path><path d="m3 7 9 7 9-7"></path></svg></span></div>
                                <p class="mb-2"><strong>Aucun produit trouvé</strong></p>
                                <p class="small">Vous n'avez pas encore de produits. Cliquez sur "Ajouter un produit"
                                    pour en créer un.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="produits-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<!-- Popover pour ajouter un Produit -->
<div class="modal fade" id="ajouter-produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter un produit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajouter-produit-form" enctype="multipart/form-data">
                    <div class="form-group p-2">
                        <label for="produit-nom">Nom du produit</label>
                        <input type="text" class="form-control border-primary border-1" id="produit-nom"
                            name="produit-nom" placeholder="Nom du produit" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-prix">Prix en FCFA</label>
                        <input type="number" class="form-control border-primary border-1" id="produit-prix"
                            name="produit-prix" placeholder="Prix" min="1" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-quantite">Quantité</label>
                        <input type="number" class="form-control border-primary border-1" id="produit-quantite"
                            name="produit-quantite" placeholder="Quantité" min="1" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-seuil-critique">Seuil Critique</label>
                        <input type="number" class="form-control border-primary border-1" id="produit-seuil-critique"
                            name="produit-seuil-critique" placeholder="Seuil critque" min="0" value="0" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-categorie">Categorie</label>
                        <select name="produit-categorie" id="produit-categorie" required>
                            <option value="---" disabled selected hidden class="text-muted">Choisir une catégorie pour
                                le produit</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-img">Image du produit</label>
                        <input type="file" class="form-control border-primary border-1" id="produit-img"
                            name="produit-img" placeholder="Seuil critque" accept="image/*" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-description">Description</label>
                        <textarea class="form-control border-primary border-1" id="produit-description"
                            name="produit-description" placeholder="Décrit le produit" required></textarea>
                    </div>
                    <p id="ajouter-produit-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="ajouter-produit-form">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Popover pour Modifier un Produit -->
<div class="modal fade" id="modifier-produit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">modifier un produit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifier-produit-form" enctype="multipart/form-data">
                    <div class="form-group p-2">
                        <label for="produit-nom">Nom du produit</label>
                        <input type="text" class="form-control border-primary border-1" name="produit-nom"
                            placeholder="Nom du produit" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-prix">Prix en FCFA</label>
                        <input type="number" class="form-control border-primary border-1" name="produit-prix"
                            placeholder="Prix" min="1" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-quantite">Quantité</label>
                        <input type="number" class="form-control border-primary border-1" name="produit-quantite"
                            placeholder="Quantité" min="1" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-seuil-critique">Seuil Critique</label>
                        <input type="number" class="form-control border-primary border-1" name="produit-seuil-critique"
                            placeholder="Seuil critque" min="0" value="0" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-categorie">Categorie</label>
                        <select name="produit-categorie" id="produit-categorie" required>
                            <option value="---" disabled selected hidden class="text-muted">Choisir une catégorie pour
                                le produit</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-img">Image du produit</label>
                        <input type="file" class="form-control border-primary border-1" id="produit-img"
                            name="produit-img" placeholder="Seuil critque" accept="image/*" required />
                        <img id="modifier-produit-img"
                            width="100" height="100" class="m-2" />
                    </div>
                    <div class="form-group p-2">
                        <label for="produit-description">Description</label>
                        <textarea class="form-control border-primary border-1" id="produit-description"
                            name="produit-description" placeholder="Décrit le produit" required></textarea>
                    </div>
                    <p id="modifier-produit-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="modifier-produit-form">Modifier</button>
            </div>
        </div>
    </div>
</div>
