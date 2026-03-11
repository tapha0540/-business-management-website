const approvisionnementFetchUrl = 'http://localhost:8081/routes/approvisionnements/get_all.php';
const approvisionnementGetUrl = 'http://localhost:8081/routes/approvisionnements/get.php';
const approvisionnementCreateUrl = 'http://localhost:8081/routes/approvisionnements/create.php';
const approvisionnementUpdateUrl = 'http://localhost:8081/routes/approvisionnements/update.php';
const approvisionnementDeleteUrl = 'http://localhost:8081/routes/approvisionnements/delete.php';
const produitFetchUrl = 'http://localhost:8081/routes/produits/get_all.php';
const fournisseurFetchUrl = 'http://localhost:8081/routes/fournisseurs/get_all.php';

let approvData = [];
let fournisseurs = [];
let approvProduits = [];

const approvisionnementSearchField = document.getElementById('approvisionnements-search');
const approvisionnementLimitField = document.getElementById('approvisionnements-table-limit');

// Throttle search input
let searchTimeout;
if (approvisionnementSearchField) {
    approvisionnementSearchField.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchApprovisionnementTableData();
        }, 300);
    });
}

// Handle limit changes
document.querySelectorAll('.approvisionnements-filter').forEach(el => {
    el?.addEventListener('change', () => {
        fetchApprovisionnementTableData();
    });
});

async function fetchApprovisionnementTableData() {
    try {
        const limit = approvisionnementLimitField?.value || 10;
        const search = approvisionnementSearchField?.value || '';

        const response = await fetchApi(approvisionnementFetchUrl, 'POST', {
            limit: parseInt(limit),
            search: search
        });

        if (response.success) {
            approvData = response.data || [];
            renderApprovisionnementTable();
            updateDeleteButtonState();
        }
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        const errorEl = document.getElementById('approvisionnements-table-error-message');
        if (errorEl) {
            errorEl.textContent = 'Erreur lors du chargement des approvisionnements';
        }
    }
}

function renderApprovisionnementTable() {
    const tbody = document.querySelector('#approvisionnements-table tbody');
    const emptyState = document.getElementById('approvisionnements-empty-state');

    if (!tbody) return;

    // Remove existing rows (keep empty state)
    document.querySelectorAll('.approvisionnements-row').forEach(r => r.remove());

    if (approvData.length === 0) {
        emptyState?.classList.remove('d-none');
        return;
    }

    emptyState?.classList.add('d-none');
    const template = document.getElementById('approvisionnements-row-template');

    approvData.forEach(approv => {
        const row = template?.content.cloneNode(true);
        if (!row) return;

        const checkbox = row.querySelector('.approvisionnements-checkbox');
        if (checkbox) {
            checkbox.value = approv.id;
        }

        const fournisseurEl = row.querySelector('.approv-fournisseur');
        const nbProduitsEl = row.querySelector('.approv-nb-produits');
        const totalQuantiteEl = row.querySelector('.approv-quantite');
        const totalMontantEl = row.querySelector('.approv-montant');
        const dateEl = row.querySelector('.approv-date');
        const updatedEl = row.querySelector('.approv-updated');

        const nbProduits = Number(approv.nb_produits ?? 0);
        const totalQuantite = Number(approv.total_quantite ?? 0);
        const totalMontant = Number(approv.total_montant ?? 0);

        if (fournisseurEl) fournisseurEl.textContent = approv.fournisseur_nom || '-';
        if (nbProduitsEl) nbProduitsEl.textContent = Number.isFinite(nbProduits) ? nbProduits : 0;
        if (totalQuantiteEl) totalQuantiteEl.textContent = Number.isFinite(totalQuantite) ? totalQuantite : 0;
        if (totalMontantEl) {
            const montant = Number.isFinite(totalMontant) ? totalMontant : 0;
            totalMontantEl.textContent = montant.toFixed(2) + ' FCFA';
        }

        if (dateEl) {
            dateEl.textContent = approv.created_at ? new Date(approv.created_at).toLocaleDateString('fr-FR') : '-';
        }
        if (updatedEl) {
            updatedEl.textContent = approv.updated_at ? new Date(approv.updated_at).toLocaleDateString('fr-FR') : '-';
        }

        tbody.appendChild(row);
    });
}

async function loadFournisseurs() {
    try {
        if (fournisseurs.length > 0) {
            populateFournisseurSelect();
            return;
        }
        const response = await fetchApi(fournisseurFetchUrl, 'POST', {
            limit: 1000,
            search: ''
        });

        if (response.success) {
            fournisseurs = response.data || [];
            populateFournisseurSelect();
        }
    } catch (error) {
        console.error('Erreur chargement fournisseurs:', error);
    }
}

async function loadProduits() {
    try {
        if (approvProduits.length > 0) {
            return;
        }
        const response = await fetchApi(produitFetchUrl, 'POST', {
            limit: 1000,
            search: ''
        });

        if (response.success) {
            approvProduits = response.data || [];
        }
    } catch (error) {
        console.error('Erreur chargement produits:', error);
    }
}

function populateFournisseurSelect() {
    const select = document.getElementById('approvisionnement-fournisseur');
    if (!select) return;

    const selected = select.value;
    select.innerHTML = '<option value="">-- Sélectionner un fournisseur --</option>';

    fournisseurs.forEach(f => {
        const option = document.createElement('option');
        option.value = f.id;
        option.textContent = f.nom + ' (' + f.email + ')';
        select.appendChild(option);
    });

    if (selected) select.value = selected;
}

async function addDetailRow(containerId, preset = null) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (approvProduits.length === 0) {
        await loadProduits();
    }

    const rowDiv = document.createElement('div');
    rowDiv.className = 'approv-detail-row';

    const produitSelect = document.createElement('select');
    produitSelect.className = 'form-control border-primary border-1';
    produitSelect.required = true;
    produitSelect.innerHTML = '<option value="">-- Sélectionner un produit --</option>';

    approvProduits.forEach(p => {
        const option = document.createElement('option');
        option.value = p.id;
        option.textContent = p.nom;
        produitSelect.appendChild(option);
    });

    const quantiteInput = document.createElement('input');
    quantiteInput.type = 'number';
    quantiteInput.placeholder = 'Quantité';
    quantiteInput.className = 'form-control border-primary border-1';
    quantiteInput.required = true;
    quantiteInput.min = '1';

    const prixInput = document.createElement('input');
    prixInput.type = 'number';
    prixInput.placeholder = 'Prix d\'achat';
    prixInput.className = 'form-control border-primary border-1';
    prixInput.required = true;
    prixInput.min = '0';
    prixInput.step = '0.01';

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'btn btn-sm btn-outline-danger icon-btn';
    deleteBtn.title = 'Supprimer la ligne';
    deleteBtn.setAttribute('aria-label', 'Supprimer la ligne');
    deleteBtn.innerHTML = '<span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span>';
    deleteBtn.onclick = (e) => {
        e.preventDefault();
        rowDiv.remove();
    };

    rowDiv.appendChild(produitSelect);
    rowDiv.appendChild(quantiteInput);
    rowDiv.appendChild(prixInput);
    rowDiv.appendChild(deleteBtn);

    if (preset) {
        if (preset.produit_id) {
            produitSelect.value = preset.produit_id;
        }
        if (preset.quantite) {
            quantiteInput.value = preset.quantite;
        }
        if (preset.prix_achat !== undefined && preset.prix_achat !== null) {
            prixInput.value = preset.prix_achat;
        }
    }

    container.appendChild(rowDiv);
}

document.getElementById('ajouter-detail-approv')?.addEventListener('click', (e) => {
    e.preventDefault();
    addDetailRow('approvisionnement-details-container');
});

document.getElementById('ajouter-detail-modifier')?.addEventListener('click', (e) => {
    e.preventDefault();
    addDetailRow('approv-modifier-details-container');
});

document.getElementById('ajouter-approvisionnement-submit')?.addEventListener('click', async () => {
    try {
        const fournisseurId = document.getElementById('approvisionnement-fournisseur')?.value;
        const detailRows = document.querySelectorAll('#approvisionnement-details-container .approv-detail-row');

        if (!fournisseurId) {
            alert('Veuillez sélectionner un fournisseur');
            return;
        }

        if (detailRows.length === 0) {
            alert('Veuillez ajouter au moins un produit');
            return;
        }

        const details = [];
        detailRows.forEach(row => {
            const produitSelect = row.querySelector('select');
            const quantiteInput = row.querySelectorAll('input')[0];
            const prixInput = row.querySelectorAll('input')[1];

            if (produitSelect?.value && quantiteInput?.value && prixInput?.value) {
                details.push({
                    produit_id: parseInt(produitSelect.value),
                    quantite: parseInt(quantiteInput.value),
                    prix_achat: parseFloat(prixInput.value)
                });
            }
        });

        if (details.length === 0) {
            alert('Veuillez remplir tous les champs des produits');
            return;
        }

        const response = await fetchApi(approvisionnementCreateUrl, 'POST', {
            fournisseur_id: parseInt(fournisseurId),
            details: details
        });

        if (response.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('ajouter-approvisionnement'));
            modal?.hide();
            document.getElementById('ajouter-approvisionnement-form')?.reset();
            document.getElementById('approvisionnement-details-container').innerHTML = '';
            await fetchApprovisionnementTableData();
        } else {
            alert(response.message || 'Erreur lors de la création');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la création');
    }
});

async function modifierApprovisionnement(button) {
    try {
        const row = button.closest('tr');
        const checkbox = row.querySelector('input[type="checkbox"]');
        const approvId = parseInt(checkbox.value);

        const response = await fetchApi(approvisionnementGetUrl, 'POST', {
            id: approvId
        });

        if (!response.success) {
            alert('Erreur lors du chargement');
            return;
        }

        const approv = response.data;

        document.getElementById('approv-modifier-id').value = approv.id;
        document.getElementById('approv-modifier-fournisseur').value = approv.fournisseur_nom;

        const container = document.getElementById('approv-modifier-details-container');
        container.innerHTML = '';

        await loadProduits();

        for (const detail of (approv.details || [])) {
            await addDetailRow('approv-modifier-details-container', {
                produit_id: detail.produit_id,
                quantite: detail.quantite,
                prix_achat: detail.prix_achat
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('modifier-approvisionnement'));
        modal.show();
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification');
    }
}

document.getElementById('modifier-approvisionnement-submit')?.addEventListener('click', async () => {
    try {
        const approvId = parseInt(document.getElementById('approv-modifier-id').value);
        const detailRows = document.querySelectorAll('#approv-modifier-details-container .approv-detail-row');

        const details = [];
        detailRows.forEach(row => {
            const produitSelect = row.querySelector('select');
            const quantiteInput = row.querySelectorAll('input')[0];
            const prixInput = row.querySelectorAll('input')[1];

            if (produitSelect?.value && quantiteInput?.value && prixInput?.value) {
                details.push({
                    produit_id: parseInt(produitSelect.value),
                    quantite: parseInt(quantiteInput.value),
                    prix_achat: parseFloat(prixInput.value)
                });
            }
        });

        if (details.length === 0) {
            alert('Veuillez ajouter au moins un produit');
            return;
        }

        const response = await fetchApi(approvisionnementUpdateUrl, 'POST', {
            id: approvId,
            details: details
        });

        if (response.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modifier-approvisionnement'));
            modal?.hide();
            await fetchApprovisionnementTableData();
        } else {
            alert(response.message || 'Erreur lors de la mise à jour');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour');
    }
});

async function supprimerApprovisionnement(button) {
    const row = button.closest('tr');
    const checkbox = row.querySelector('input[type="checkbox"]');
    const approvId = parseInt(checkbox.value);

    if (confirm('Voulez-vous vraiment supprimer cet approvisionnement ?')) {
        try {
            const response = await fetchApi(approvisionnementDeleteUrl, 'POST', {
                approvisionnements_ids: [approvId]
            });

            if (response.success) {
                await fetchApprovisionnementTableData();
            } else {
                alert(response.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        }
    }
}

async function supprimerApprovisionnementSelectionne() {
    const selectedCheckboxes = document.querySelectorAll('.approvisionnements-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        alert('Veuillez sélectionner au moins un approvisionnement');
        return;
    }

    if (confirm(`Voulez-vous vraiment supprimer ${selectedCheckboxes.length} approvisionnement(s) ?`)) {
        try {
            const ids = Array.from(selectedCheckboxes).map(cb => parseInt(cb.value));

            const response = await fetchApi(approvisionnementDeleteUrl, 'POST', {
                approvisionnements_ids: ids
            });

            if (response.success) {
                await fetchApprovisionnementTableData();
            } else {
                alert(response.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        }
    }
}

document.getElementById('approvisionnements-delete-selected')?.addEventListener('click', supprimerApprovisionnementSelectionne);

// Select all checkbox
document.getElementById('select-all-approvisionnements')?.addEventListener('change', (e) => {
    document.querySelectorAll('.approvisionnements-checkbox').forEach(cb => {
        cb.checked = e.target.checked;
    });
    updateDeleteButtonState();
});

// Individual checkbox
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('approvisionnements-checkbox')) {
        updateDeleteButtonState();
    }
});

function updateDeleteButtonState() {
    const anyChecked = document.querySelector('.approvisionnements-checkbox:checked');
    const deleteBtn = document.getElementById('approvisionnements-delete-selected');
    if (deleteBtn) {
        deleteBtn.disabled = !anyChecked;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    await loadFournisseurs();
    await loadProduits();
    await fetchApprovisionnementTableData();
});

// Refresh every 30 seconds if visible
setInterval(() => {
    if (!document.hidden) {
        fetchApprovisionnementTableData();
    }
}, 30000);





window.modifierApprovisionnement = modifierApprovisionnement;
window.supprimerApprovisionnement = supprimerApprovisionnement;






