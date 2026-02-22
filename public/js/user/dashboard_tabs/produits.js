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
const produitCategorieSelect = document.getElementById('produit-categorie');


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
        <td><input type="checkbox" value="${produit.id}" /> </td>
        <td><img src="${produit.imgUrl}" width="100" height="100"/></td>
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
  data.forEach( categorie => {
    const option = document.createElement('option');
    option.innerText = categorie.nom;
    produitCategorieSelect.appendChild(option);
  });
}
produitsTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = produitsTableTbody.querySelectorAll(".row-check");
  checkboxes.forEach((cb) => (cb.checked = produitsTableSelectAllBtn.checked));
  updateDeleteButtonState();
});

fetchProduitsTableData();
getProduitsSelectTagData();
produitsTableFilter.forEach(
  (each) => (each.onchange = () => fetchProduitsTableData()),
);