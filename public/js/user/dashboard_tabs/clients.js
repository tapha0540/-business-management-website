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

const clientsTable = document.getElementById("clients-table");
const tbody = clientsTable.querySelector("tbody");
const rowTemplate = document.getElementById("clients-row-template");
const emptyState = document.getElementById("clients-empty-state");
const selectAllBtn = document.getElementById("select-all-clients");
const deleteSelectedBtn = document.getElementById("clients-delete-selected");

// Fetch and populate clients
const fetchClientsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/clients/get_all.php",
      "POST",
      { limit: 10 },
    );
    console.log(serverRes);

    if (serverRes.data && serverRes.data.length > 0) {
      emptyState.classList.add("d-none");
      let delay = 0;

      serverRes.data.forEach((client) => {
        const newRow = rowTemplate.cloneNode(true);
        newRow.classList.remove("d-none");
        newRow.dataset.id = client.id;
        newRow.style.animation = `slideIn 0.4s ease forwards ${delay}ms`;

        newRow.querySelector(".col-name").textContent = client.nom || "--";
        newRow.querySelector(".col-email").textContent = client.email || "--";
        newRow.querySelector(".col-phone").textContent =
          client.telephone || "--";
        newRow.querySelector(".col-address").textContent =
          client.adresse || "--";

        const statusEl = newRow.querySelector(".col-status span");
        const isActive = client.statut === "actif" || client.actif === 1;
        statusEl.textContent = isActive ? "Actif" : "Inactif";
        statusEl.className = `activity-badge ${isActive ? "activity-active" : "activity-inactive"}`;

        newRow.querySelector(".col-created").textContent = new Date(
          client.created_at,
        ).toLocaleDateString("fr-FR");

        const editBtn = newRow.querySelector(".btn-edit");
        editBtn.addEventListener("click", (e) => {
          e.preventDefault();
          console.log("Edit client:", client.id);
        });

        const deleteBtn = newRow.querySelector(".btn-delete");
        deleteBtn.addEventListener("click", (e) => {
          e.preventDefault();
          deleteClient(client.id, newRow);
        });

        tbody.appendChild(newRow);
        delay += 50;
      });
    } else {
      emptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error fetching clients:", error);
    const errorMsg = document.getElementById("clients-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des clients.";
  }
};

const deleteClient = async (clientId, rowElement) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ce client ?")) return;

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
    console.error("Error deleting client:", error);
    rowElement.classList.remove("fade-out");
  }
};

const deleteSelectedClients = async () => {
  const checkedBoxes = tbody.querySelectorAll(".row-check:checked");
  if (checkedBoxes.length === 0) return;

  if (
    !confirm(
      `Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} client(s) ?`,
    )
  )
    return;

  checkedBoxes.forEach((checkbox) => {
    const row = checkbox.closest("tr");
    const clientId = row.dataset.id;
    deleteClient(clientId, row);
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

deleteSelectedBtn.addEventListener("click", deleteSelectedClients);

fetchClientsTableData();
