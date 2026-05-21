<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>

            <h5 class="mb-0">
                <img src="../../assets/images/icons/shoppin_bag.svg" width="24px" height="24px" />
                Commandes
            </h5>
            <small class="text-muted">Vue d'ensemble des commandes récentes</small>
        </div>
        <div class="table-actions">
            <!-- <button class="btn btn-outline-secondary btn-sm" type="button">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M3 3v18h18"></path>
                        <rect x="7" y="11" width="3" height="6"></rect>
                        <rect x="12" y="8" width="3" height="9"></rect>
                        <rect x="17" y="6" width="3" height="11"></rect>
                    </svg></span> Statistiques
            </button> -->
            <button class="btn btn-outline-danger btn-sm" id="commandes-delete-selected" type="button" disabled>
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg></span> Supprimer sélection
            </button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#modal-ajouter-commande">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg></span> Ajouter une commande
            </button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0 max-w-300">
                <span class="input-group-text"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg></span></span>
                <input id="commandes-search" type="search" class="form-control" placeholder="Rechercher...">
            </div>

            <div class="d-flex gap-2">
                <select id="commandes-filter-status" class="form-select form-select-sm">
                    <option value="all">Tous les statuts</option>
                    <option value="en_cours">En cours</option>
                    <option value="cloturee">Clôturée</option>
                    <option value="annulee">Annulée</option>
                </select>
                <input type="number" name="commandes-table-limit" id="commandes-table-limit" min="1" value="10" required
                    class="form-control form-control-sm max-w-80" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="commandes-table" class="table table-striped table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" /></th>
                        <th class="col-w-50">Id</th>
                        <th>Vendeur</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th class="col-w-100">Statut</th>
                        <th class="col-w-180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empty state row shown when no data -->
                    <tr class="table-empty">
                        <td colspan="7">
                            <div class="text-center cmd-empty py-5">
                                <p class="mb-2"><strong>Aucune commande trouvée</strong></p>
                                <p class="small text-muted">Vous n'avez pas encore de commandes.</p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div id="commandes-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<!-- Modal pour ajouter une commande -->
<div class="modal fade" id="modal-ajouter-commande" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Créer une nouvelle commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-ajouter-commande">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="commande-client-id" class="form-label">Client <span
                                    class="text-danger">*</span></label>
                            <input type="hidden" class="d-none" name="commande-vendeur"
                                value="<?= (int) $_SESSION['user']['id'] ?>" />
                            <input list="ajouter-commande-client" class="form-control" name="commande-client"
                                placeholder="ID ou nom du client" required>
                            <datalist id="ajouter-commande-client" class="commande-client-datalists">
                                <option value="" disabled>-- Sélectionner un client --</option>
                            </datalist>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Produits <span class="text-danger">*</span></label>
                        <div id="commande-produits-container" class="p-3 max-h-300 overflow-auto">
                            <!-- Les produits seront ajoutés ici -->

                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-ajouter-produit">
                        + Ajouter une ligne produit
                    </button>

                    <div id="form-error-message" class="alert alert-danger d-none mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="form-ajouter-commande" class="btn btn-primary" id="btn-creer-commande">Créer
                    la commande</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir détails commande -->
<div class="modal fade" id="modal-details-commande" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Modifier la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-modifier-commande">
                    <input type="hidden" name="commande-id" />

                    <div class="mb-3">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <input list="modifier-commande-client" class="form-control" name="commande-client"
                            placeholder="ID ou nom du client" required>
                        <datalist id="modifier-commande-client" class="commande-client-datalists">
                            <option value="" disabled>-- Sélectionner un client --</option>
                        </datalist>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mb-2"
                        id="btn-ajouter-produit-modifier">
                        + Ajouter un produit
                    </button>

                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th class="col-w-100">Quantité</th>
                                <th class="col-w-130">Prix unitaire</th>
                                <th class="col-w-130">Total</th>
                                <th class="col-w-52">Action</th>
                            </tr>
                        </thead>
                        <tbody id="details-produits-liste"></tbody>
                    </table>

                    <div class="mt-3 text-end">
                        <strong>Montant total: <span id="commande-montant-total">0</span> FCFA</strong>
                    </div>
                    <select name="product-status" class="form-select form-select-sm mt-3">
                        <option value="en_cours">En cours</option>
                        <option value="cloturee">Clôturée</option>
                        <option value="annulee">Annulée</option>
                    </select>
                    <div id="modifier-commande-message" class="small mt-2"></div>
                </form>
                <a id="telecharger-facture-btn" class="btn btn-sm btn-outline-success" href="#">télécharger la facture</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="form-modifier-commande" class="btn btn-primary">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>