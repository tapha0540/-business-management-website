const commandesTable = document.getElementById("commandes-table");
const commandesTableThead = commandesTable.querySelector("thead");
const commandesTableTbody = commandesTable.querySelector("tbody");
const commandesTableEmptyState = commandesTable.querySelector(".table-empty");
const commandesTableLimit = document.getElementById("commandes-table-limit");
const commandesFilterStatus = document.getElementById(
  "commandes-filter-status",
);
const clientDatalists = document.querySelectorAll(".commande-client-datalists");
const selecetAllCheckbox = commandesTableThead.querySelector(
  "input[type='checkbox']",
);
const voirsCommandesBtns = document.querySelectorAll(".voir-commande");
const formAjouterCommande = document.getElementById("form-ajouter-commande");
const btnAjouterProduit = formAjouterCommande.querySelector(
  "#btn-ajouter-produit",
);
let commandes = [];

const fetchCommandesTableDonnee = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/get_all.php",
    "POST",
    {
      limit: Number(commandesTableLimit.value),
    },
  );

  commandes = serverRes.data;
  afficherCommandesTableDonnee(commandes);
};

const afficherCommandesTableDonnee = (data) => {
  commandesTableTbody.innerHTML = "";

  if (data && data.length > 0) {
    commandesTableEmptyState.style.display = "none";

    data.forEach((commande) => {
      let statusBadge = "";

      if (commande.etat === "en_cours")
        statusBadge = `<span class="badge bg-warning">En cours</span>`;
      if (commande.etat === "cloturee")
        statusBadge = `<span class="badge bg-success">Cloturée</span>`;
      if (commande.etat === "annulee")
        statusBadge = `<span class="badge bg-danger">Annulée</span>`;

      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td><input type="checkbox" value="${commande.id}" /></td>
        <td>#${commande.id}</td>
        <td>${commande.vendeur_nom ?? commande.vendeur_id}</td>
        <td>${commande.client_nom ?? commande.client_id}</td>
        <td>${commande.created_at}</td>
        <td>${statusBadge}</td>
        <td class="d-flex column-gap-2">
            <button class="btn btn-outline-primary btn-sm voir-commande" data-id="${commande.id}" data-bs-toggle="modal" data-bs-target="#modal-details-commande">
                Voir
            </button>
            <button class="btn btn-outline-danger btn-sm supprimer-commande" data-id="${commande.id}">
                Supprimer
            </button>
        </td>
      `;

      commandesTableTbody.appendChild(tr);
    });
  } else {
    commandesTableTbody.appendChild(commandesTableEmptyState);
    commandesTableEmptyState.style.display = "table-row";
  }
};
fetchCommandesTableDonnee();

selecetAllCheckbox.addEventListener("change", () => {
  const checkboxes = commandesTableTbody.querySelectorAll(
    "input[type='checkbox']",
  );
  checkboxes.forEach((each) => (each.checked = selecetAllCheckbox.checked));
});

commandesTableLimit.onchange = () => fetchCommandesTableDonnee();
commandesFilterStatus.onchange = () =>
  afficherCommandesTableDonnee(
    commandes.filter(
      (commande) =>
        commandesFilterStatus.value == "all" ||
        commande.etat == commandesFilterStatus.value,
    ),
  );

commandesTableTbody.addEventListener("click", async (e) => {
  if (!e.target.classList.contains("voir-commande")) return;

  const commandeId = e.target.getAttribute("data-id");

  const res = await fetchApi(
    "http://localhost:8081/routes/commandes/get_details.php",
    "POST",
    { commande_id: Number(commandeId) },
    true
  );
  console.log(res.data);

  const produits = res.data;

  const tbody = document.getElementById("details-produits-liste");
  const totalElement = document.getElementById("commande-montant-total");

  tbody.innerHTML = "";

  let total = 0;

  produits.forEach((p) => {
    const ligneTotal = p.quantite * p.prix_vente;
    total += ligneTotal;

    const tr = document.createElement("tr");

    tr.innerHTML = `
      <td>${p.produit}</td>
      <td><input type="number" class="quantite-input" value="${p.quantite}"></td>
      <td>${p.prix_vente}</td>
      <td class="ligne-total">${ligneTotal}</td>
    `;

    tbody.appendChild(tr);
  });

  totalElement.textContent = total;
});

clientDatalists.forEach(async (datalist) => {
  const input = document.querySelector(`input[list="${datalist.id}"]`);
  input.addEventListener("input", async () => {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/clients/get_all.php",
      "POST",
      { limit: 25, search: input.value || "" },
    );
    const clients = serverRes.data;
    datalist.innerHTML = `<option value="" disabled>-- Sélectionner un client --</option>`;

    clients.forEach((client) => {
      const option = document.createElement("option");
      option.value = client.id;
      option.innerHTML = `${client.nom} ${client.prenom} <small>(${client.email}) (${client.telephone})</small>`;
      datalist.appendChild(option);
    });
  });
});
btnAjouterProduit.addEventListener("click", async () => {
  const container = document.getElementById("commande-produits-container");
  if (!container) return;

  const rowDiv = document.createElement("div");
  rowDiv.className = "commande-detail-rows d-flex column-gap-2";

  const produitSelect = document.createElement("select");
  produitSelect.className = "produit-select form-control border-primary border-1";
  produitSelect.required = true;
  produitSelect.innerHTML =
    '<option value="">-- Sélectionner un produit --</option>';
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/get_all.php",
    "POST",
    { limit: 1000000, search: "" },
  );
  const produits = serverRes.data;
  const preview = document.createElement("div");
  preview.style.marginTop = "10px";
  preview.style.marginBottom = "10px";
  produits.forEach((p) => {
    const option = document.createElement("option");
    option.value = p.id;
    option.dataset.img = p.imgUrl;
    option.innerHTML = `
            <div>
              ${p.nom} <small>Prix: ${p.prix_vente}, Stock: ${p.quantite}</small>
            </div>
        `;
    produitSelect.appendChild(option);
  });
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
  deleteBtn.className = "btn btn-sm btn-outline-danger";
  deleteBtn.textContent = "🗑️";
  const div = document.createElement("div");
  deleteBtn.onclick = (e) => {
    e.preventDefault();
    div.remove();
  };

  rowDiv.appendChild(produitSelect);
  rowDiv.appendChild(quantiteInput);
  rowDiv.appendChild(deleteBtn);
  div.className = "d-flex flex-column row-gap-2";
  div.appendChild(preview);
  div.appendChild(rowDiv);
  container.appendChild(div);
});

const AjouterCommande = async () => {
  const details = Array.from(
  document.querySelectorAll("#commande-produits-container .commande-detail-rows")
).map(row => {
  const select = row.querySelector(".produit-select");
  const quantite = row.querySelector(".quantite-input");

  if (!select || !quantite || !select.value || !quantite.value) return null;

  return {
    produit_id: select.value,
    quantite: Number(quantite.value),
  };
}).filter(v => v);


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

  console.log(serverRes);
  
  if (serverRes.success) {
    alert("Commande ajoutée avec succès");
    formAjouterCommande.reset();
    document.getElementById("commande-produits-container").innerHTML = "";
    fetchCommandesTableDonnee();
  } else {
    alert(serverRes.message || "Erreur coté, lors de l'ajout de la commande");
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
    alert(
      serverRes.message || "Erreur coté, lors de la suppression des commandes",
    );
  }
};

const supprimerCommandesSelectionner = () => {
  const checkboxes = commandesTableTbody.querySelectorAll(
    "input[type='checkbox']:checked",
  );
  const ids = Array.from(checkboxes).map((cb) => cb.dataset.id);
  if (ids.length === 0) {
    alert("Veuillez sélectionner au moins une commande à supprimer.");
    return;
  }
  supprimerCommandes(ids);
};

formAjouterCommande.addEventListener("submit", (e) => {
  e.preventDefault();
  AjouterCommande();
});
