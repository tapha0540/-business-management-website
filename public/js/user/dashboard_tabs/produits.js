const produitsTable = document.getElementById("produits-table");
const produitsTableTbody = produitsTable.querySelector("tbody");
const produitsTableRowTemplate = document.getElementById(
  "produits-row-template",
);
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
// Fetch and populate produits
const fetchProduitsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/produits/get_all.php",
      "POST",
      { limit: Number(produitsTableLimit.value) },
    );
    produits = serverRes.data;

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
      tr.innerHTML = `
        <td><input type="checkbox" value="${produit.id}" class="produit-table-checkboxes" /> </td>
        <td><img src="http://localhost:8081/storage/uploads/images/produits/${produit.imgUrl}" width="100" height="100"/></td>
        <td>${produit.nom}</td>
        <td>${produit.categorie}</td>
        <td>${produit.prix_vente}</td>
        <td>${produit.quantite}</td>
        <td>${produit.seuil_critique}</td>
        <td>${produit.created_at}</td>
        <td>${produit.updated_at}</td>
        <td class="d-flex column-gap-2">
          <button class="btn btn-outline-danger btn-sm" id="commandes-delete-selected">Supprimer</button>
          <button class="btn btn-primary btn-sm" id="commandes-delete-selected">Modifier</button>
        </td>
      `;
      produitsTableTbody.appendChild(tr);
    });
  } else {
    produitsTableEmptyState.classList.remove("d-none");
  }
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
produitsTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = produitsTableTbody.querySelectorAll(
    ".produit-table-checkboxes",
  );
  checkboxes.forEach((cb) => (cb.checked = produitsTableSelectAllBtn.checked));
  updateDeleteButtonState();
});

const ajouterProduit = async (e) => {
  e.preventDefault();

  const file = document.getElementById("produit-img").files[0];

    let base64Image = null;

    if (file) {
        base64Image = await toBase64(file);
    }

  const formData = {
    nom: ajouterProduitForm["produit-nom"].value,
    description: ajouterProduitForm["produit-description"].value,
    image: base64Image,  // image en base64
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
    serverRes.message || "Erreur, Le serveur ne repond pas !";
  if (serverRes.success) {
    ajouterProduitFormMessage.classList.add("text-success");
    fetchProduitsTableData();
  } else {
    ajouterProduitFormMessage.classList.add("text-danger");
  }
};

fetchProduitsTableData();
getProduitsSelectTagData();
produitsTableFilter.forEach(
  (each) => (each.onchange = () => fetchProduitsTableData()),
);
ajouterProduitForm.onsubmit = ajouterProduit;
