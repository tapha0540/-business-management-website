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
const ajouterClientForm = document.getElementById("ajouter-client-form");
const ajouterClientFormMessage = document.getElementById(
  "ajouter-client-form-message",
);
const modifierClientForm = document.getElementById("modifier-client-form");
// Fetch and populate clients
const fetchClientsTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/clients/get_all.php",
      "POST",
      { limit: clientsTableLimit.value, search: clientsSearch.value },
    );

    if (serverRes.data && serverRes.data.length > 0) {
      clientsTableTbody.innerHTML = "";
      clientsTablEmptyState.classList.add("d-none");

      serverRes.data.forEach((client) => {
        const newRow = clientsTableRowTemplate.cloneNode(true);
        newRow.classList.remove("d-none");
        newRow.dataset.id = client.id;

        newRow.querySelector(".client-checkbox").value = client.id;
        newRow.querySelector(".client-img").src =
          `http://localhost:8081/storage/uploads/images/clients/${client.imgUrl}`;
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
          client.updated_at,
        ).toLocaleDateString("fr-FR");

        const editBtn = newRow.querySelector(".btn-edit");
        editBtn.addEventListener("click", async (e) => {
          e.preventDefault();
          populateModifierClientModal(client);
        });

        const deleteBtn = newRow.querySelector(".btn-delete");
        deleteBtn.addEventListener("click", (e) => {
          e.preventDefault();
          supprimmerClient([client.id]);
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

const ajouterClient = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/clients/create.php",
    "POST",
    {
      prenom: ajouterClientForm["client-prenom"].value,
      nom: ajouterClientForm["client-nom"].value,
      telephone: ajouterClientForm["client-telephone"].value,
      email: ajouterClientForm["client-email"].value,
      image: ajouterClientForm["client-img"].files[0]
        ? await toBase64(ajouterClientForm["client-img"].files[0])
        : null,
    },
  );

  ajouterClientFormMessage.textContent = serverRes.message;
  ajouterClientFormMessage.classList.remove("text-danger", "text-success");
  if (serverRes.success) {
    ajouterClientFormMessage.classList.add("text-success");
    ajouterClientForm.reset();
    fetchClientsTableData();
  } else {
    ajouterClientFormMessage.classList.add("text-danger");
  }
};

const supprimmerClient = async (clientIds) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ce(s) client(s) ?")) {
    return;
  }
  const serverRes = await fetchApi(
    `http://localhost:8081/routes/clients/delete.php`,
    "POST",
    { clients_ids: clientIds },
  );

  if (serverRes.success) {
    fetchClientsTableData();
  }
};

const getClient = async (clientId) => {
  const serverRes = await fetchApi(
    `http://localhost:8081/routes/clients/get.php`,
    "POST",
    { client_id: clientId },
  );

  if (serverRes.success) {
    return serverRes.data;
  } else {
    alert("Erreur lors de la récupération des données du client.");
    return null;
  }
};

const populateModifierClientModal = (client) => {
  if (!client) return;
  modifierClientForm["client-id"].value = client.id;
  modifierClientForm["client-prenom"].value = client.prenom || "";
  modifierClientForm["client-nom"].value = client.nom || "";
  modifierClientForm["client-telephone"].value = client.telephone || "";
  modifierClientForm["client-email"].value = client.email || "";
  if (client.imgUrl) {
    document.getElementById("modifier-client-img").src =
      `http://localhost:8081/storage/uploads/images/clients/${client.imgUrl}`;
  } else {
    document.getElementById("modifier-client-img").src = "";
  }
};

const modifierClient = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/clients/update.php",
    "POST",
    {
      id: modifierClientForm["client-id"].value,
      prenom: modifierClientForm["client-prenom"].value,
      nom: modifierClientForm["client-nom"].value,
      telephone: modifierClientForm["client-telephone"].value,
      email: modifierClientForm["client-email"].value,
      image: modifierClientForm["client-img"].files[0]
        ? await toBase64(modifierClientForm["client-img"].files[0])
        : null,
    },
  );
  console.log(serverRes);
  
  if (serverRes.success) {
    fetchClientsTableData();
  }
};

ajouterClientForm.addEventListener("submit", (e) => {
  e.preventDefault();
  ajouterClient();
});
clientsTablDeleteSelectedBtn.addEventListener("click", () => {
  const selectedClientIds = Array.from(
    clientsTableTbody.querySelectorAll(".client-checkbox:checked"),
  ).map((cb) => cb.value);

  if (selectedClientIds.length > 0) {
    supprimmerClient(selectedClientIds);
  }
});

modifierClientForm.addEventListener("submit", (e) => {
  e.preventDefault();
  modifierClient();
});