const commandesTable = document.getElementById("commandes-table");
const thead = commandesTable.querySelector("thead");
const tbody = commandesTable.querySelector("tbody");

const fetchCommandesTableDonnee = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/commandes/get_all.php",
    "POST",
    {
      limit: 10,
    },
  );
  console.log(serverRes);

  if (serverRes.data && serverRes.data.length > 0) {
    tbody.innerHTML = '';
    serverRes.data.forEach((commande) => {
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
      tbody.appendChild(tr);
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
    tbody.appendChild(tr);
  }
};
fetchCommandesTableDonnee();
const selectAll = thead.querySelector("input[type='checkbox']");
selectAll.addEventListener("change", () => {
  const checkboxes = tbody.querySelectorAll("input[type='checkbox']");
  checkboxes.forEach((each) => (each.checked = selectAll.checked));
});
