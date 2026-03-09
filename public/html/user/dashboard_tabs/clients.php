<div class="card cmd-card shadow-sm mb-4 rounded-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.9"></path><path d="M16 3.1a4 4 0 0 1 0 7.8"></path></svg></span> Clients</h5>
            <small class="text-muted">Gestion de votre portefeuille clients</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 3v18h18"></path><rect x="7" y="11" width="3" height="6"></rect><rect x="12" y="8" width="3" height="9"></rect><rect x="17" y="6" width="3" height="11"></rect></svg></span> Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="clients-delete-selected"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span> Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#ajouter-client"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg></span> Ajouter un client</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="input-group search-input mb-2 mb-md-0">
                <span class="input-group-text"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path></svg></span></span>
                <input id="clients-search" type="search" class="form-control form-control-sm"
                    placeholder="Rechercher par prenom, nom, email, téléphone...">
            </div>

            <div class="d-flex gap-2">
                <input type="number" name="clients-table-limit" id="clients-table-limit" min="1" value="10" required
                    class="clients-filter form-control form-control-sm" />
            </div>
        </div>

        <div class="table-responsive">
            <table id="clients-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-clients" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Profile</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>créé le</th>
                        <th>Modifiéé le</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="clients-row-template" class="d-none">
                        <td><input type="checkbox" class="client-checkbox" /></td>
                        <td><img class="client-img" width="100" height="100" /></td>
                        <td class="client-prenom">--</td>
                        <td class="client-nom">--</td>
                        <td class="client-telephone">--</td>
                        <td class="client-email">--</td>
                        <td class="client-created-at">--</td>
                        <td class="client-updated-at">--</td>
                        <td class="text-end table-actions-cell">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit icon-btn" title="Éditer ce client" aria-label="Éditer ce client" type="button" data-bs-toggle="modal" data-bs-target="#modifier-client"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg></span></button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete icon-btn" title="Supprimer ce client" aria-label="Supprimer ce client"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr id="clients-empty-state" class="table-empty">
                        <td colspan="9">
                            <div class="text-center cli-empty">
                                <div style="margin-bottom: 1rem;"><span class="app-icon" style="width:3rem;height:3rem;" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 7h18v10H3z"></path><path d="m3 7 9 7 9-7"></path></svg></span></div>
                                <p class="mb-2"><strong>Aucun client trouvé</strong></p>
                                <p class="small">Vous n'avez pas encore de clients. Cliquez sur "Ajouter un client" pour
                                    en créer un.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="clients-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>

<!-- Popover pour ajouter un client -->
<div class="modal fade" id="ajouter-client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter un client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajouter-client-form" enctype="multipart/form-data">
                    <div class="form-group p-2">
                        <label for="client-prenom">Prénom</label>
                        <input type="text" class="form-control border-primary border-1" id="client-prenom"
                            name="client-prenom" placeholder="Prénom" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="client-nom">Nom</label>
                        <input type="text" class="form-control border-primary border-1" id="client-nom"
                            name="client-nom" placeholder="Nom" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="client-telephone">Téléphone</label>
                        <input type="tel" class="form-control border-primary border-1" id="client-telephone"
                            name="client-telephone" placeholder="Téléphone" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="client-email">Email</label>
                        <input type="email" class="form-control border-primary border-1" id="client-email"
                            name="client-email" placeholder="Email" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="client-img">Image du client</label>
                        <input type="file" class="form-control border-primary border-1" id="client-img"
                            name="client-img" accept="image/*" />
                    </div>
                    <p id="ajouter-client-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="ajouter-client-form">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Popover pour Modifier un client -->
<div class="modal fade" id="modifier-client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">modifier un client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modifier-client-form" enctype="multipart/form-data">
                    <input type="hidden" name="client-id" />
                    <div class="form-group p-2">
                        <label for="modifier-client-prenom">Prénom</label>
                        <input type="text" class="form-control border-primary border-1" name="client-prenom"
                            placeholder="Prénom" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="modifier-client-nom">Nom</label>
                        <input type="text" class="form-control border-primary border-1" name="client-nom"
                            placeholder="Nom" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="modifier-client-telephone">Téléphone</label>
                        <input type="tel" class="form-control border-primary border-1" name="client-telephone"
                            placeholder="Téléphone" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="modifier-client-email">Email</label>
                        <input type="email" class="form-control border-primary border-1" name="client-email"
                            placeholder="Email" required />
                    </div>
                    <div class="form-group p-2">
                        <label for="modifier-client-img-input">Image profile du client</label>
                        <input type="file" class="form-control border-primary border-1" id="modifier-client-img-input"
                            name="client-img" accept="image/*" />
                        <img id="modifier-client-img" width="100" height="100" class="m-2" />
                    </div>
                    <p id="modifier-client-form-message" class="text-center mt-3"></p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" form="modifier-client-form">Modifier</button>
            </div>
        </div>
    </div>
</div>