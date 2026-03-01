const clientsTable = document.getElementById("clients-table");
const clientsTableTbody = clientsTable.querySelector("tbody");
const clientsTableRowTemplate = document.getElementById("clients-row-template");
const clientsTablEmptyState = document.getElementById("clients-empty-state");
const clientsTableSelectAllBtn = document.getElementById("select-all-clients");
const clientsTablDeleteSelectedBtn = document.getElementById(
  "clients-delete-selected",
);
const clientsTableLimit = document.getElementById("clients-table-limit");
const clientsSearch = document.getElementById("clients-search");
const clientsFilter = document.querySelectorAll(".clients-filter");
// Fetch and populate clients
const fetchClientsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/clients/get_all.php",
      "POST",
      { limit: clientsTableLimit.value, search: clientsSearch.value },
    );
    console.log(serverRes);

    if (serverRes.data && serverRes.data.length > 0) {
      clientsTableTbody.innerHTML = "";
      clientsTablEmptyState.classList.add("d-none");

      serverRes.data.forEach((client) => {
        const newRow = clientsTableRowTemplate.cloneNode(true);
        newRow.classList.remove("d-none");
        newRow.dataset.id = client.id;

        newRow.querySelector(".client-checkbox").value = client.id;
        newRow.querySelector(".client-img").src =`http://localhost:8081/storage/uploads/images/clients/${client.imgUrl}`;
        newRow.querySelector(".client-prenom").textContent =
          client.prenom || "--";
        newRow.querySelector(".client-nom").textContent = client.nom || "--";
        newRow.querySelector(".client-telephone").textContent =
          client.telephone || "--";
        newRow.querySelector(".client-email").textContent =
          client.email || "--";

        newRow.querySelector(".client-created-at").textContent = new Date(
          client.created_at,
        ).toLocaleDateString("fr-FR");

        newRow.querySelector(".client-updated-at").textContent = new Date(
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
  const checkboxes = clientsTableTbody.querySelectorAll(".client-checkbox");
  checkboxes.forEach((cb) => (cb.checked = clientsTableSelectAllBtn.checked));
});

fetchClientsTableData();
clientsFilter.forEach((item) => {
  item.onchange = () => fetchClientsTableData();
});

clientsSearch.onchange = () => {
  fetchClientsTableData();
};
