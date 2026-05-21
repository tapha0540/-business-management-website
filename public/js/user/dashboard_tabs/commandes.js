const commandesTable = document.getElementById("commandes-table");
const commandesTableThead = commandesTable.querySelector("thead");
const commandesTableTbody = commandesTable.querySelector("tbody");
const commandesTableEmptyState = commandesTable.querySelector(".table-empty");
const commandesTableLimit = document.getElementById("commandes-table-limit");
const commandesFilterStatus = document.getElementById("commandes-filter-status");
const commandesSearchInput = document.getElementById("commandes-search");
const commandesDeleteSelectedBtn = document.getElementById("commandes-delete-selected");
const clientDatalists = document.querySelectorAll(".commande-client-datalists");
const selecetAllCheckbox = commandesTableThead.querySelector("input[type='checkbox']");
const formAjouterCommande = document.getElementById("form-ajouter-commande");
const formModifierCommande = document.getElementById("form-modifier-commande");
const modifierCommandeMessage = document.getElementById("modifier-commande-message");
const btnAjouterProduit = formAjouterCommande.querySelector("#btn-ajouter-produit");
const btnAjouterProduitModifier = document.getElementById("btn-ajouter-produit-modifier");

let commandes = [];
let commandesSearchTimeout;
let produitsCatalogCache = null;

const filterCommandesByStatus = (data) =>
  data.filter(
    (commande) =>
      commandesFilterStatus.value === "all" ||
      commande.etat === commandesFilterStatus.value,
  );

const updateCommandesDeleteButtonState = () => {
  if (!commandesDeleteSelectedBtn) return;
  const checked = commandesTableTbody.querySelectorAll("input[type='checkbox']:checked");
  commandesDeleteSelectedBtn.disabled = checked.length === 0;
};

const fetchCommandesTableDonnee = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/get_all.php",
    "POST",
    {
      limit: Number(commandesTableLimit.value),
      search: commandesSearchInput?.value || "",
    },
  );

  commandes = serverRes.data || [];
  afficherCommandesTableDonnee(filterCommandesByStatus(commandes));
  updateCommandesDeleteButtonState();
};

const afficherCommandesTableDonnee = (data) => {
  commandesTableTbody.innerHTML = "";

  if (data && data.length > 0) {
    commandesTableEmptyState.style.display = "none";

    data.forEach((commande) => {
      let statusBadge = "";

      if (commande.etat === "en_cours") {
        statusBadge = `<span class="badge bg-warning">En cours</span>`;
      }
      if (commande.etat === "cloturee") {
        statusBadge = `<span class="badge bg-success">Clôturée</span>`;
      }
      if (commande.etat === "annulee") {
        statusBadge = `<span class="badge bg-danger">Annulée</span>`;
      }

      const closeButton =
        commande.etat === "en_cours"
          ? `<button class="btn btn-outline-success btn-sm cloturer-commande icon-btn" data-id="${commande.id}" title="Clôturer" aria-label="Clôturer"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"></path></svg></span></button>`
          : "";

      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td><input type="checkbox" value="${commande.id}" /></td>
        <td>#${commande.id}</td>
        <td>${typeof escapeHtml === "function" ? escapeHtml(commande.vendeur_nom ?? commande.vendeur_id) : (commande.vendeur_nom ?? commande.vendeur_id)}</td>
        <td>${typeof escapeHtml === "function" ? escapeHtml(commande.client_nom ?? commande.client_id) : (commande.client_nom ?? commande.client_id)}</td>
        <td>${typeof formatAppDateTimeHtml === "function" ? formatAppDateTimeHtml(commande.created_at) : commande.created_at}</td>
        <td>${statusBadge}</td>
        <td class="table-actions-cell">
            <button class="btn btn-outline-primary btn-sm voir-commande icon-btn" data-id="${commande.id}" title="Voir" aria-label="Voir" data-bs-toggle="modal" data-bs-target="#modal-details-commande"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg></span></button>
            ${closeButton}
            <button class="btn btn-outline-danger btn-sm supprimer-commande icon-btn" data-id="${commande.id}" title="Supprimer" aria-label="Supprimer"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
        </td>
      `;

      commandesTableTbody.appendChild(tr);
    });
  } else {
    commandesTableTbody.appendChild(commandesTableEmptyState);
    commandesTableEmptyState.style.display = "table-row";
  }
};

const mettreAJourTotalCommandeModal = () => {
  const totalElement = document.getElementById("commande-montant-total");
  const lignes = document.querySelectorAll("#details-produits-liste tr");

  let total = 0;
  lignes.forEach((ligne) => {
    const quantiteInput = ligne.querySelector(".quantite-input");
    const prixCell = ligne.querySelector(".prix-unitaire");
    const totalCell = ligne.querySelector(".ligne-total");

    const quantite = Number(quantiteInput?.value || 0);
    const prixUnitaire = Number(prixCell?.dataset.prix || 0);
    const ligneTotal = quantite * prixUnitaire;

    if (totalCell) {
      totalCell.textContent = ligneTotal.toFixed(2);
    }

    total += ligneTotal;
  });

  if (totalElement) {
    totalElement.textContent = total.toFixed(2);
  }
};

const fetchProduitsCatalog = async () => {
  if (produitsCatalogCache) return produitsCatalogCache;

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/get_all.php",
    "POST",
    { limit: 1000000, search: "" },
  );

  produitsCatalogCache = serverRes.data || [];
  return produitsCatalogCache;
};

const buildProduitOptions = (select, produits) => {
  select.innerHTML = '<option value="">-- Sélectionner un produit --</option>';
  produits.forEach((p) => {
    const option = document.createElement("option");
    option.value = p.id;
    option.dataset.img = p.imgUrl || "";
    option.dataset.prix = p.prix_vente || 0;
    option.textContent = `${p.nom} (Prix: ${p.prix_vente}, Stock: ${p.quantite})`;
    select.appendChild(option);
  });
};

const addProduitRowToCreateModal = async () => {
  const container = document.getElementById("commande-produits-container");
  if (!container) return;

  const produits = await fetchProduitsCatalog();

  const rowDiv = document.createElement("div");
  rowDiv.className = "commande-detail-rows d-flex column-gap-2";

  const produitSelect = document.createElement("select");
  produitSelect.className = "produit-select form-control border-primary border-1";
  produitSelect.required = true;
  buildProduitOptions(produitSelect, produits);

  const preview = document.createElement("div");
  preview.style.marginTop = "10px";
  preview.style.marginBottom = "10px";

  produitSelect.addEventListener("change", () => {
    const option = produitSelect.options[produitSelect.selectedIndex];
    if (option && option.dataset.img) {
      preview.innerHTML = `<img src="http://localhost:8081/storage/uploads/images/produits/${option.dataset.img}" width="100" height="100"/>`;
    } else {
      preview.innerHTML = "";
    }
  });

  const quantiteInput = document.createElement("input");
  quantiteInput.type = "number";
  quantiteInput.placeholder = "Quantité";
  quantiteInput.className = "quantite-input form-control border-primary border-1";
  quantiteInput.required = true;
  quantiteInput.min = "1";

  const deleteBtn = document.createElement("button");
  deleteBtn.type = "button";
  deleteBtn.className = "btn btn-sm btn-outline-danger icon-btn";
  deleteBtn.title = "Supprimer la ligne";
  deleteBtn.setAttribute("aria-label", "Supprimer la ligne");
  deleteBtn.innerHTML = `<span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span>`;

  const card = document.createElement("div");
  card.className = "d-flex flex-column row-gap-2";
  deleteBtn.onclick = (e) => {
    e.preventDefault();
    card.remove();
  };

  rowDiv.appendChild(produitSelect);
  rowDiv.appendChild(quantiteInput);
  rowDiv.appendChild(deleteBtn);
  card.appendChild(preview);
  card.appendChild(rowDiv);
  container.appendChild(card);
};

const addProduitRowToUpdateModal = async () => {
  const tbody = document.getElementById("details-produits-liste");
  if (!tbody) return;

  const produits = await fetchProduitsCatalog();

  const tr = document.createElement("tr");

  const tdProduit = document.createElement("td");
  const select = document.createElement("select");
  select.className = "form-select form-select-sm produit-select-modifier";
  buildProduitOptions(select, produits);
  tdProduit.appendChild(select);

  const tdQuantite = document.createElement("td");
  const quantiteInput = document.createElement("input");
  quantiteInput.type = "number";
  quantiteInput.className = "quantite-input form-control form-control-sm";
  quantiteInput.min = "1";
  quantiteInput.value = "1";
  quantiteInput.required = true;
  tdQuantite.appendChild(quantiteInput);

  const tdPrix = document.createElement("td");
  tdPrix.className = "prix-unitaire";
  tdPrix.dataset.prix = "0";
  tdPrix.textContent = "0";

  const tdTotal = document.createElement("td");
  tdTotal.className = "ligne-total";
  tdTotal.textContent = "0";

  const tdRemove = document.createElement("td");
  const removeBtn = document.createElement("button");
  removeBtn.type = "button";
  removeBtn.className = "btn btn-sm btn-outline-danger icon-btn";
  removeBtn.title = "Supprimer la ligne";
  removeBtn.setAttribute("aria-label", "Supprimer la ligne");
  removeBtn.innerHTML = `<span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span>`;
  removeBtn.addEventListener("click", () => {
    tr.remove();
    mettreAJourTotalCommandeModal();
  });
  tdRemove.appendChild(removeBtn);

  select.addEventListener("change", () => {
    const option = select.options[select.selectedIndex];
    const prix = Number(option?.dataset.prix || 0);
    tdPrix.dataset.prix = `${prix}`;
    tdPrix.textContent = `${prix}`;
    tr.dataset.produitId = option?.value || "";
    mettreAJourTotalCommandeModal();
  });

  tr.appendChild(tdProduit);
  tr.appendChild(tdQuantite);
  tr.appendChild(tdPrix);
  tr.appendChild(tdTotal);
  tr.appendChild(tdRemove);

  tbody.appendChild(tr);
  mettreAJourTotalCommandeModal();
};

commandesTableTbody.addEventListener("click", async (e) => {
  const viewBtn = e.target.closest(".voir-commande");
  const deleteBtn = e.target.closest(".supprimer-commande");
  const closeBtn = e.target.closest(".cloturer-commande");

  if (closeBtn) {
    const commandeIdToClose = Number(closeBtn.getAttribute("data-id"));
    clotureeCommande(commandeIdToClose);
    return;
  }

  if (deleteBtn) {
    const commandeIdToDelete = Number(deleteBtn.getAttribute("data-id"));
    supprimerCommandes([commandeIdToDelete]);
    return;
  }

  if (!viewBtn) return;

  const commandeId = Number(viewBtn.getAttribute("data-id"));

  const [detailsRes, commandeRes] = await Promise.all([
    fetchApi(
      "http://localhost:8081/routes/commandes/get_details.php",
      "POST",
      { commande_id: commandeId },
      true,
    ),
    fetchApi("http://localhost:8081/routes/commandes/get.php", "POST", {
      id: commandeId,
    }),
  ]);

  if (!detailsRes.success || !commandeRes.success) {
    alert("Erreur lors du chargement des details de la commande");
    return;
  }

  const produits = detailsRes.data || [];
  const commande = commandeRes.data || {};

  const tbody = document.getElementById("details-produits-liste");
  tbody.innerHTML = "";

  if (formModifierCommande) {
    formModifierCommande["commande-id"].value = commandeId;
    formModifierCommande["commande-client"].value = commande.client_id || "";
    formModifierCommande["product-status"].value = commande.etat || "en_cours";
    factureTelechargerBtn = document.getElementById('telecharger-facture-btn');
    factureTelechargerBtn.onclick = async () => await telechargerFacture(commandeId);
  }

  produits.forEach((p) => {
    const tr = document.createElement("tr");
    tr.dataset.produitId = p.produit_id;

    tr.innerHTML = `
      <td>${p.produit}</td>
      <td>
        <input
          type="number"
          class="quantite-input form-control form-control-sm"
          min="1"
          value="${p.quantite}"
          required
        >
      </td>
      <td class="prix-unitaire" data-prix="${p.prix_vente}">${p.prix_vente}</td>
      <td class="ligne-total">0</td>
      <td>
        <button type="button" class="btn btn-sm btn-outline-danger icon-btn supprimer-ligne-commande" title="Supprimer la ligne" aria-label="Supprimer la ligne"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
      </td>
    `;

    tbody.appendChild(tr);
  });

  mettreAJourTotalCommandeModal();
});

commandesTableTbody.addEventListener("change", (e) => {
  if (e.target.matches("input[type='checkbox']")) {
    updateCommandesDeleteButtonState();
  }
});

selecetAllCheckbox.addEventListener("change", () => {
  const checkboxes = commandesTableTbody.querySelectorAll("input[type='checkbox']");
  checkboxes.forEach((each) => {
    each.checked = selecetAllCheckbox.checked;
  });
  updateCommandesDeleteButtonState();
});

commandesTableLimit.onchange = () => fetchCommandesTableDonnee();
commandesFilterStatus.onchange = () =>
  afficherCommandesTableDonnee(filterCommandesByStatus(commandes));

if (commandesSearchInput) {
  commandesSearchInput.addEventListener("input", () => {
    clearTimeout(commandesSearchTimeout);
    commandesSearchTimeout = setTimeout(() => {
      fetchCommandesTableDonnee();
    }, 300);
  });
}

clientDatalists.forEach(async (datalist) => {
  const input = document.querySelector(`input[list="${datalist.id}"]`);
  if (!input) return;

  input.addEventListener("input", async () => {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/clients/get_all.php",
      "POST",
      { limit: 25, search: input.value || "" },
    );
    const clients = serverRes.data || [];
    datalist.innerHTML =
      '<option value="" disabled>-- Sélectionner un client --</option>';

    clients.forEach((client) => {
      const option = document.createElement("option");
      option.value = client.id;
      option.textContent = `${client.nom} ${client.prenom} (${client.email}) (${client.telephone})`;
      datalist.appendChild(option);
    });
  });
});

btnAjouterProduit.addEventListener("click", addProduitRowToCreateModal);
btnAjouterProduitModifier?.addEventListener("click", addProduitRowToUpdateModal);

document
  .getElementById("details-produits-liste")
  ?.addEventListener("input", (e) => {
    if (!e.target.classList.contains("quantite-input")) return;
    mettreAJourTotalCommandeModal();
  });

document
  .getElementById("details-produits-liste")
  ?.addEventListener("click", (e) => {
    const removeBtn = e.target.closest(".supprimer-ligne-commande");
    if (!removeBtn) return;
    removeBtn.closest("tr")?.remove();
    mettreAJourTotalCommandeModal();
  });

const AjouterCommande = async () => {
  const details = Array.from(
    document.querySelectorAll("#commande-produits-container .commande-detail-rows"),
  )
    .map((row) => {
      const select = row.querySelector(".produit-select");
      const quantite = row.querySelector(".quantite-input");

      if (!select || !quantite || !select.value || !quantite.value) return null;

      return {
        produit_id: Number(select.value),
        quantite: Number(quantite.value),
      };
    })
    .filter((v) => v);

  if (details.length === 0) {
    alert("Veuillez ajouter au moins un produit à la commande.");
    return;
  }

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/create.php",
    "POST",
    {
      vendeur_id: formAjouterCommande["commande-vendeur"].value,
      client_id: formAjouterCommande["commande-client"].value,
      details,
    },
  );

  if (serverRes.success) {
    alert("Commande ajoutée avec succès");
    formAjouterCommande.reset();
    document.getElementById("commande-produits-container").innerHTML = "";
    fetchCommandesTableDonnee();
    if (typeof fetchLowStockNotifications === "function") {
      fetchLowStockNotifications();
    }
  } else {
    alert(serverRes.message || "Erreur côté serveur lors de l'ajout de la commande");
  }
};

const supprimerCommandes = async (ids) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ces commandes ?")) return;

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/delete.php",
    "POST",
    { ids },
  );

  if (serverRes.success) {
    alert(serverRes.message || "Commandes supprimées avec succès");
    fetchCommandesTableDonnee();
  } else {
    alert(serverRes.message || "Erreur côté serveur lors de la suppression des commandes");
  }
};

const supprimerCommandesSelectionner = () => {
  const checkboxes = commandesTableTbody.querySelectorAll(
    "input[type='checkbox']:checked",
  );
  const ids = Array.from(checkboxes).map((cb) => Number(cb.value));

  if (ids.length === 0) {
    alert("Veuillez sélectionner au moins une commande à supprimer.");
    return;
  }

  supprimerCommandes(ids);
};

const modifierCommande = async () => {
  if (!formModifierCommande) return;

  const commandeId = Number(formModifierCommande["commande-id"].value);
  const clientId = Number(formModifierCommande["commande-client"].value);
  const status = formModifierCommande['product-status'].value;

  const detailsRows = Array.from(
    document.querySelectorAll("#details-produits-liste tr"),
  );

  const details = detailsRows
    .map((row) => {
      const quantiteInput = row.querySelector(".quantite-input");
      const select = row.querySelector(".produit-select-modifier");
      const produitId = Number(row.dataset.produitId || select?.value || 0);
      const quantite = Number(quantiteInput?.value || 0);

      if (!produitId || !quantite) return null;

      return {
        produit_id: produitId,
        quantite,
      };
    })
    .filter((detail) => detail);

  if (!commandeId || !clientId || details.length === 0) {
    alert("Veuillez renseigner le client et les quantités.");
    return;
  }

  if (details.some((detail) => !detail.produit_id || detail.quantite <= 0)) {
    alert("Chaque produit doit avoir une quantité supérieure à 0.");
    return;
  }

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/update.php",
    "POST",
    {
      id: commandeId,
      client_id: clientId,
      details,
      status
    },
  );

  if (modifierCommandeMessage) {
    modifierCommandeMessage.textContent = serverRes.message || "";
    modifierCommandeMessage.classList.remove("text-danger", "text-success");
    modifierCommandeMessage.classList.add(
      serverRes.success ? "text-success" : "text-danger",
    );
  }

  if (serverRes.success) {
    fetchCommandesTableDonnee();
    if (typeof fetchLowStockNotifications === "function") {
      fetchLowStockNotifications();
    }
    const modalEl = document.getElementById("modal-details-commande");
    const modalInstance = bootstrap.Modal.getInstance(modalEl);
    modalInstance?.hide();
  }
};

formModifierCommande?.addEventListener("submit", (e) => {
  e.preventDefault();
  modifierCommande();
});

formAjouterCommande.addEventListener("submit", (e) => {
  e.preventDefault();
  AjouterCommande();
});

const clotureeCommande = async (commandeId) => {
  if (!confirm("Clôturer cette commande et générer la facture ?")) return;

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/cloturee.php",
    "POST",
    {
      id: commandeId,
    },
  );

  if (serverRes.success) {
    alert(serverRes.message || "Commande clôturée avec succès");
    fetchCommandesTableDonnee();
  } else {
    alert(serverRes.message || "Erreur lors de la clôture de la commande");
  }
};

commandesDeleteSelectedBtn.addEventListener("click", supprimerCommandesSelectionner);
fetchCommandesTableDonnee();

async function telechargerFacture(commandeId) {

  const response = await fetch(
    "http://localhost:8081/routes/commandes/facture_pdf.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        id: commandeId
      })
    }
  );

  if (!response.ok) {
    throw new Error("Erreur téléchargement PDF");
  }

  const blob = await response.blob();

  const url = window.URL.createObjectURL(blob);

  const a = document.createElement("a");

  a.href = url;

  a.download = `facture_${commandeId}.pdf`;

  document.body.appendChild(a);

  a.click();

  a.remove();

  window.URL.revokeObjectURL(url);
}