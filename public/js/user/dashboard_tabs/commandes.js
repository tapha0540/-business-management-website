const commandesTable = document.getElementById("commandes-table");
const commandesTableThead = commandesTable.querySelector("thead");
const commandesTableTbody = commandesTable.querySelector("tbody");
const commandesTableEmptyState = commandesTable.querySelector(".table-empty");
const commandesTableLimit = document.getElementById("commandes-table-limit");
const commandesFilterStatus = document.getElementById(
  "commandes-filter-status",
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
const selectAll = commandesTableThead.querySelector("input[type='checkbox']");

selectAll.addEventListener("change", () => {
  const checkboxes = commandesTableTbody.querySelectorAll(
    "input[type='checkbox']",
  );
  checkboxes.forEach((each) => (each.checked = selectAll.checked));
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
