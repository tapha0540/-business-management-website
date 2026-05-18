const produitsTable = document.getElementById("produits-table");
const produitsTableTbody = produitsTable.querySelector("tbody");
const produitsTableEmptyState = document.getElementById("produits-empty-state");
const produitsTableSelectAllBtn = document.getElementById(
  "select-all-produits",
);
const produitsTableDeleteSelectedBtn = document.getElementById(
  "produits-delete-selected",
);
const produitsTableLimit = document.getElementById("produits-table-limit");
const produitsTableFilter = document.querySelectorAll(".produits-filter");
const produitCategorieSelect = document.getElementById("produit-categorie");
const ajouterProduitForm = document.getElementById("ajouter-produit-form");
const ajouterProduitFormMessage = document.getElementById(
  "ajouter-produit-form-message",
);
const modifierProduitForm = document.getElementById("modifier-produit-form");
const modifierProduitImg = document.getElementById("modifier-produit-img");
const produitsSearchInput = document.getElementById("produits-search");

let produits = [];

const initialsFromText = (text = "") => {
  const cleaned = (text || "").trim();
  if (!cleaned) return "??";
  return cleaned.slice(0, 2).toUpperCase();
};

const getProduitsSelectTagData = async () => {
  const data = await fetchProduitsCategorie();
  data.forEach((categorie) => {
    const option = document.createElement("option");
    option.innerText = categorie.nom;
    option.value = categorie.id;
    produitCategorieSelect.appendChild(option);
  });
};

const fetchProduitsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/produits/get_all.php",
      "POST",
      {
        limit: Number(produitsTableLimit.value),
        search: produitsSearchInput.value,
      },
    );
    produits = serverRes.data || [];

    afficherProduitsTableDonnee(produits);
  } catch (error) {
    console.error("Error fetching produits:", error);
    const errorMsg = document.getElementById("produits-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des produits.";
  }
};

const afficherProduitsTableDonnee = (data) => {
  produitsTableTbody.innerHTML = "";
  if (data && data.length > 0) {
    data.forEach((produit) => {
      const tr = document.createElement("tr");
      tr.classList.add('text-center');
      tr.innerHTML = `
        <td><input type="checkbox" value="${produit.id}" class="produit-table-checkboxes" /> </td>
        <td>
          <div>
            <img class="produit-img" src="http://localhost:8081/storage/uploads/images/produits/${produit.imgUrl || ""}" width="56" height="56" alt="Image produit"/>
            <span class="avatar-fallback produit-avatar-fallback">${initialsFromText(produit.nom)}</span>
          </div>
        </td>
        <td>${produit.nom}</td>
        <td>${produit.categorie}</td>
        <td>${produit.prix_vente}</td>
        <td class="text-success">${produit.quantite}</td>
        <td class="text-danger">${produit.seuil_critique}</td>
        <td>${produit.created_at}</td>
        <td>${produit.updated_at}</td>
        <td class="table-actions-cell">
          <button class="btn btn-outline-danger btn-sm icon-btn" type="button" title="Supprimer" aria-label="Supprimer" onclick="supprimerProduits([${produit.id}])"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
          <button class="btn btn-primary btn-sm icon-btn" type="button" title="Modifier" aria-label="Modifier" data-bs-toggle="modal" data-bs-target="#modifier-produit" onclick="modifierProduit(${produit.id})"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg></span></button>
        </td>
      `;

      const img = tr.querySelector(".produit-img");
      const fallback = tr.querySelector(".produit-avatar-fallback");
      img.onerror = () => {
        img.classList.add("d-none");
        fallback.classList.remove("d-none");
      };

      if (!produit.imgUrl) {
        img.classList.add("d-none");
        fallback.classList.remove("d-none");
      } else {
        img.classList.remove("d-none");
        fallback.classList.add("d-none");
      }

      produitsTableTbody.appendChild(tr);
    });
  } else {
    produitsTableEmptyState.classList.remove("d-none");
  }
};

produitsTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = produitsTableTbody.querySelectorAll(
    ".produit-table-checkboxes",
  );
  checkboxes.forEach((cb) => (cb.checked = produitsTableSelectAllBtn.checked));
});

const ajouterProduit = async (e) => {
  e.preventDefault();

  const file = ajouterProduitForm["produit-img"].files[0];
  const base64Image = file ? await toBase64(file) : null;

  const formData = {
    nom: ajouterProduitForm["produit-nom"].value,
    description: ajouterProduitForm["produit-description"].value,
    image: base64Image,
    categorie_id: ajouterProduitForm["produit-categorie"].value,
    prix_vente: ajouterProduitForm["produit-prix"].value,
    quantite: ajouterProduitForm["produit-quantite"].value,
    seuil_critique: ajouterProduitForm["produit-seuil-critique"].value,
  };

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/create.php",
    "POST",
    formData,
  );
  ajouterProduitFormMessage.classList.remove("text-success", "text-danger");

  ajouterProduitFormMessage.innerText =
    serverRes.message || "Erreur, le serveur ne répond pas !";
  if (serverRes.success) {
    ajouterProduitFormMessage.classList.add("text-success");
    fetchProduitsTableData();
  } else {
    ajouterProduitFormMessage.classList.add("text-danger");
  }
};

const supprimerProduits = async (produits_ids) => {
  if (!confirm("Est ce que tu veux vraiment supprimmer ces produits")) {
    return;
  }
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/delete.php",
    "POST",
    {
      produits_ids,
    },
  );
  alert(serverRes.message || "L'opération a échoué. Erreur côté serveur !");
  if (serverRes.success) {
    fetchProduitsTableData();
  }
};

const supprimmerProduitsSelectionner = async () => {
  const checkboxes = produitsTableTbody.querySelectorAll(
    "input[type='checkbox']",
  );
  const produits_ids = [];
  checkboxes.forEach((checkbox) => {
    if (checkbox.checked) {
      produits_ids.push(Number(checkbox.value));
    }
  });
  if (produits_ids.length > 0) {
    await supprimerProduits(produits_ids);
  }
};

const getProduit = async (id) => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/get.php",
    "POST",
    {
      id,
    },
  );
  if (serverRes.success) {
    return serverRes.data;
  }
  alert(serverRes.message || "Erreur, serveur injoignable !");
  return null;
};

const modifierProduit = async (produitId) => {
  const produit = await getProduit(produitId);
  console.log(produit);

  if (!produit) return;

  modifierProduitForm["produit-nom"].value = produit.nom;
  modifierProduitForm["produit-prix"].value = produit.prix_vente;
  modifierProduitForm["produit-quantite"].value = produit.quantite;
  modifierProduitForm["produit-seuil-critique"].value = produit.seuil_critique;
  modifierProduitForm["produit-categorie"].value = produit.categorie;
  modifierProduitForm["produit-description"].value = produit.description;

  modifierProduitImg.src = `http://localhost:8081/storage/uploads/images/produits/${produit.imgUrl}`;
  modifierProduitForm.dataset.currentId = produit.id;
};

const modifierProduitSubmit = async (e) => {
  e.preventDefault();
  const id = modifierProduitForm.dataset.currentId;
  const file = modifierProduitForm["produit-img"].files[0];
  const base64Image = file ? await toBase64(file) : null;

  const formData = {
    id,
    nom: modifierProduitForm["produit-nom"].value,
    description: modifierProduitForm["produit-description"].value,
    categorie_id: modifierProduitForm["produit-categorie"].value,
    prix_vente: modifierProduitForm["produit-prix"].value,
    quantite: modifierProduitForm["produit-quantite"].value,
    seuil_critique: modifierProduitForm["produit-seuil-critique"].value,
    image: base64Image,
  };

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/update.php",
    "POST",
    formData,
  );

  if (serverRes.success) {
    fetchProduitsTableData();
  } else {
    alert(serverRes.message || "Erreur lors de la mise à jour du produit.");
  }
};

modifierProduitForm.onsubmit = modifierProduitSubmit;

fetchProduitsTableData();
getProduitsSelectTagData();
produitsTableFilter.forEach(
  (each) => (each.onchange = () => fetchProduitsTableData()),
);

ajouterProduitForm.onsubmit = ajouterProduit;
produitsTableDeleteSelectedBtn.onclick = supprimmerProduitsSelectionner;

let isSearching = false;
produitsSearchInput.onchange = async () => {
  if (isSearching) {
    return;
  }
  isSearching = true;
  await fetchProduitsTableData();
  setTimeout(() => (isSearching = false), 250);
};

window.modifierProduit = modifierProduit;
window.supprimerProduits = supprimerProduits;
