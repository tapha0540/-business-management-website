const commandesTable = document.getElementById("commandes-table");
const commandesTableThead = commandesTable.querySelector("thead");
const commandesTableTbody = commandesTable.querySelector("tbody");
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
    data.forEach((commande) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td><input type="checkbox" value="${commande.id}" /> </td>
        <td>${commande.id}</td>
        <td>${commande.vendeur_id}</td>
        <td>${commande.client_id}</td>
        <td>${commande.etat}</td>
        <td>${commande.created_at}</td>
        <td>${commande.updated_at}</td>
        <td class="d-flex column-gap-2">
          <button class="btn btn-outline-danger btn-sm" id="commandes-delete-selected">Supprimer</button>
          <button class="btn btn-primary btn-sm" id="commandes-delete-selected">Modifier</button>
        </td>
      `;
      commandesTableTbody.appendChild(tr);
    });
  } else {
    const tr = document.createElement("tr");
    tr.innerHTML = `
    <!-- Empty state row shown when no data -->
    <tr class="table-empty">
        <td colspan="8">
            <div class="text-center cmd-empty">
                <p class="mb-2"><strong>Aucune commande trouvée</strong></p>
                <p class="small">Vous n'avez pas encore de commandes. Cliquez sur "Ajouter une commande"
                    pour en créer une.</p>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm">Ajouter une commande</button>
                </div>
            </div>
        </td>
    </tr>`;
    commandesTableTbody.appendChild(tr);
  }
};
fetchCommandesTableDonnee();
const selectAll = commandesTableThead.querySelector("input[type='checkbox']");

selectAll.addEventListener("change", () => {
  const checkboxes = commandesTableTbody.querySelectorAll("input[type='checkbox']");
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
