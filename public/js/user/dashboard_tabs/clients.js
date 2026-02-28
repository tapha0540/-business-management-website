const clientsTable = document.getElementById("clients-table");
const clientsTableTbody = clientsTable.querySelector("tbody");
const clientsTableRowTemplate = document.getElementById("clients-row-template");
const clientsTablEmptyState = document.getElementById("clients-empty-state");
const clientsTableSelectAllBtn = document.getElementById("select-all-clients");
const clientsTablDeleteSelectedBtn = document.getElementById(
  "clients-delete-selected",
);

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
      clientsTablEmptyState.classList.add("d-none");
      let delay = 0;

      serverRes.data.forEach((client) => {
        const newRow = clientsTableRowTemplate.cloneNode(true);
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

        clientsTableTbody.appendChild(newRow);
        delay += 50;
      });
    } else {
      clientsTablEmptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error fetching clients:", error);
    const errorMsg = document.getElementById("clients-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des clients.";
  }
};

clientsTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = clientsTableTbody.querySelectorAll(".row-check");
  checkboxes.forEach((cb) => (cb.checked = clientsTableSelectAllBtn.checked));
  updateDeleteButtonState();
});


fetchClientsTableData();
