// Add animation styles dynamically
const style = document.createElement("style");
style.textContent = `
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
`;
document.head.appendChild(style);

const produitsTable = document.getElementById("produits-table");
const tbody = produitsTable.querySelector("tbody");
const rowTemplate = document.getElementById("produits-row-template");
const emptyState = document.getElementById("produits-empty-state");
const selectAllBtn = document.getElementById("select-all-produits");
const deleteSelectedBtn = document.getElementById("produits-delete-selected");

// Fetch and populate produits
const fetchProduitsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/produits/get_all.php",
      "POST",
      { limit: 10 },
    );
    console.log(serverRes);

    if (serverRes.data && serverRes.data.length > 0) {
      emptyState.classList.add("d-none");
      let delay = 0;

      serverRes.data.forEach((produit) => {
        const newRow = rowTemplate.cloneNode(true);
        newRow.classList.remove("d-none");
        newRow.dataset.id = produit.id;
        newRow.style.animation = `slideIn 0.4s ease forwards ${delay}ms`;

        newRow.querySelector(".col-reference").textContent =
          produit.reference || "--";
        newRow.querySelector(".col-name").textContent = produit.nom || "--";
        newRow.querySelector(".col-category").textContent =
          produit.categorie || "--";
        newRow.querySelector(".col-price").textContent =
          `${parseFloat(produit.prix || 0).toFixed(2)} €`;

        const stockEl = newRow.querySelector(".col-stock span");
        const stock = parseInt(produit.stock || 0);
        stockEl.textContent = stock + " unités";

        if (stock <= 10) {
          stockEl.className = "stock-badge stock-low";
        } else if (stock <= 50) {
          stockEl.className = "stock-badge stock-medium";
        } else {
          stockEl.className = "stock-badge stock-high";
        }

        newRow.querySelector(".col-status").textContent =
          produit.statut || "Actif";

        const editBtn = newRow.querySelector(".btn-edit");
        editBtn.addEventListener("click", (e) => {
          e.preventDefault();
          console.log("Edit produit:", produit.id);
        });

        const deleteBtn = newRow.querySelector(".btn-delete");
        deleteBtn.addEventListener("click", (e) => {
          e.preventDefault();
          deleteProduit(produit.id, newRow);
        });

        tbody.appendChild(newRow);
        delay += 50;
      });
    } else {
      emptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error fetching produits:", error);
    const errorMsg = document.getElementById("produits-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des produits.";
  }
};

const deleteProduit = async (produitId, rowElement) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) return;

  try {
    rowElement.classList.add("fade-out");
    await new Promise((resolve) => setTimeout(resolve, 300));
    rowElement.remove();
    updateDeleteButtonState();

    const allRows = tbody.querySelectorAll("tr:not(.d-none)").length;
    if (allRows === 0) {
      emptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error deleting produit:", error);
    rowElement.classList.remove("fade-out");
  }
};

const deleteSelectedProduits = async () => {
  const checkedBoxes = tbody.querySelectorAll(".row-check:checked");
  if (checkedBoxes.length === 0) return;

  if (
    !confirm(
      `Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} produit(s) ?`,
    )
  )
    return;

  checkedBoxes.forEach((checkbox) => {
    const row = checkbox.closest("tr");
    const produitId = row.dataset.id;
    deleteProduit(produitId, row);
  });
};

const updateDeleteButtonState = () => {
  const anyChecked = tbody.querySelectorAll(".row-check:checked").length > 0;
  deleteSelectedBtn.disabled = !anyChecked;
};

selectAllBtn.addEventListener("change", () => {
  const checkboxes = tbody.querySelectorAll(".row-check");
  checkboxes.forEach((cb) => (cb.checked = selectAllBtn.checked));
  updateDeleteButtonState();
});

tbody.addEventListener("change", (e) => {
  if (e.target.classList.contains("row-check")) {
    const allChecked =
      tbody.querySelectorAll(".row-check:not(:checked)").length === 0;
    selectAllBtn.checked =
      allChecked && tbody.querySelectorAll(".row-check").length > 0;
    updateDeleteButtonState();
  }
});

deleteSelectedBtn.addEventListener("click", deleteSelectedProduits);

fetchProduitsTableData();
