const fournisseurGetAllUrl =
  "http://localhost:8081/routes/fournisseurs/get_all.php";
const fournisseurGetUrl = "http://localhost:8081/routes/fournisseurs/get.php";
const fournisseurCreateUrl =
  "http://localhost:8081/routes/fournisseurs/create.php";
const fournisseurUpdateUrl =
  "http://localhost:8081/routes/fournisseurs/update.php";
const fournisseurDeleteUrl =
  "http://localhost:8081/routes/fournisseurs/delete.php";

let fournisseurData = [];

const fournisseurSearchField = document.getElementById("fournisseurs-search");
const fournisseurLimitField = document.getElementById(
  "fournisseurs-table-limit",
);

// Throttle search input
let fournisseurSearchTimeout;
if (fournisseurSearchField) {
  fournisseurSearchField.addEventListener("input", () => {
    clearTimeout(fournisseurSearchTimeout);
    fournisseurSearchTimeout = setTimeout(() => {
      fetchFournisseurTableData();
    }, 300);
  });
}

// Handle limit changes
if (fournisseurLimitField) {
  fournisseurLimitField.addEventListener("change", () => {
    fetchFournisseurTableData();
  });
}

async function fetchFournisseurTableData() {
  try {
    const limit = fournisseurLimitField?.value || 10;
    const search = fournisseurSearchField?.value || "";

    const response = await fetchApi(fournisseurGetAllUrl, "POST", {
      limit: parseInt(limit),
      search: search,
    });

    if (response.success) {
      fournisseurData = response.data || [];
      renderFournisseurTable();
      updateFournisseurDeleteButtonState();
    }
  } catch (error) {
    console.error("Erreur lors du chargement:", error);
    const errorEl = document.getElementById("fournisseurs-table-error-message");
    if (errorEl) {
      errorEl.textContent = "Erreur lors du chargement des fournisseurs";
    }
  }
}

function renderFournisseurTable() {
  const tbody = document.querySelector("#fournisseurs-table tbody");
  const emptyState = document.getElementById("fournisseurs-empty-state");

  if (!tbody) return;

  // Remove existing rows (keep empty state)
  document.querySelectorAll(".fournisseur-row").forEach((r) => r.remove());

  if (fournisseurData.length === 0) {
    emptyState?.classList.remove("d-none");
    return;
  }

  emptyState?.classList.add("d-none");

  fournisseurData.forEach((fournisseur) => {
    const row = document.createElement("tr");
    row.className = "fournisseur-row";

    row.innerHTML = `
            <td><input type="checkbox" class="fournisseur-checkbox" value="${fournisseur.id}" /></td>
            <td>${fournisseur.nom || "-"}</td>
            <td>${fournisseur.email || "-"}</td>
            <td>${fournisseur.telephone || "-"}</td>
            <td>${fournisseur.adresse || "-"}</td>
            <td>${fournisseur.created_at ? new Date(fournisseur.created_at).toLocaleDateString() : "-"}</td>
            <td>${fournisseur.updated_at ? new Date(fournisseur.updated_at).toLocaleDateString() : "-"}</td>
            <td class="text-end table-actions-cell">
                <button class="btn btn-sm btn-outline-primary icon-btn" title="Éditer" aria-label="Éditer" onclick="modifierFournisseur(this)" type="button"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg></span></button>
                <button class="btn btn-sm btn-outline-danger icon-btn" title="Supprimer" aria-label="Supprimer" onclick="supprimerFournisseur(this)" type="button"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg></span></button>
            </td>
        `;

    tbody.appendChild(row);
  });

  // Reattach checkbox listeners
  document.querySelectorAll(".fournisseur-checkbox").forEach((cb) => {
    cb.addEventListener("change", updateFournisseurDeleteButtonState);
  });
}

// Handle add form submission
const ajouterFournisseurForm = document.getElementById(
  "ajouter-fournisseur-form",
);
if (ajouterFournisseurForm) {
  ajouterFournisseurForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const nom = ajouterFournisseurForm.querySelector(
        '[name="fournisseur-nom"]',
      )?.value;
      const email = ajouterFournisseurForm.querySelector(
        '[name="fournisseur-email"]',
      )?.value;
      const telephone =
        ajouterFournisseurForm.querySelector('[name="fournisseur-telephone"]')
          ?.value || "";
      const adresse =
        ajouterFournisseurForm.querySelector('[name="fournisseur-adresse"]')
          ?.value || "";

      if (!nom || !email) {
        alert("Le nom et l'email sont requis");
        return;
      }

      const response = await fetchApi(fournisseurCreateUrl, "POST", {
        nom: nom,
        email: email,
        telephone: telephone,
        adresse: adresse,
      });

      if (response.success) {
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("ajouter-fournisseur"),
        );
        modal?.hide();
        ajouterFournisseurForm.reset();
        await fetchFournisseurTableData();
      } else {
        alert(response.message || "Erreur lors de la création");
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de la création");
    }
  });
}

async function modifierFournisseur(button) {
  try {
    const row = button.closest("tr");
    const checkbox = row.querySelector('input[type="checkbox"]');
    const fournisseurId = parseInt(checkbox.value);

    const response = await fetchApi(fournisseurGetUrl, "POST", {
      id: fournisseurId,
    });

    if (!response.success) {
      alert("Erreur lors du chargement");
      return;
    }

    const fournisseur = response.data;
    const form = document.getElementById("modifier-fournisseur-form");

    form.querySelector('[name="fournisseur-id"]').value = fournisseur.id;
    form.querySelector('[name="fournisseur-nom"]').value = fournisseur.nom;
    form.querySelector('[name="fournisseur-email"]').value = fournisseur.email;
    form.querySelector('[name="fournisseur-telephone"]').value =
      fournisseur.telephone || "";
    form.querySelector('[name="fournisseur-adresse"]').value =
      fournisseur.adresse || "";

    const modal = new bootstrap.Modal(
      document.getElementById("modifier-fournisseur"),
    );
    modal.show();
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de la modification");
  }
}

const modifierFournisseurForm = document.getElementById(
  "modifier-fournisseur-form",
);
if (modifierFournisseurForm) {
  modifierFournisseurForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const id = modifierFournisseurForm.querySelector(
        '[name="fournisseur-id"]',
      )?.value;
      const nom = modifierFournisseurForm.querySelector(
        '[name="fournisseur-nom"]',
      )?.value;
      const email = modifierFournisseurForm.querySelector(
        '[name="fournisseur-email"]',
      )?.value;
      const telephone =
        modifierFournisseurForm.querySelector('[name="fournisseur-telephone"]')
          ?.value || "";
      const adresse =
        modifierFournisseurForm.querySelector('[name="fournisseur-adresse"]')
          ?.value || "";

      if (!nom || !email) {
        alert("Le nom et l'email sont requis");
        return;
      }

      const response = await fetchApi(fournisseurUpdateUrl, "POST", {
        id: parseInt(id),
        nom: nom,
        email: email,
        telephone: telephone,
        adresse: adresse,
      });

      if (response.success) {
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("modifier-fournisseur"),
        );
        modal?.hide();
        await fetchFournisseurTableData();
      } else {
        alert(response.message || "Erreur lors de la mise à jour");
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de la mise à jour");
    }
  });
}

async function supprimerFournisseur(button) {
  const row = button.closest("tr");
  const checkbox = row.querySelector('input[type="checkbox"]');
  const fournisseurId = parseInt(checkbox.value);

  if (confirm("Voulez-vous vraiment supprimer ce fournisseur ?")) {
    try {
      const response = await fetchApi(fournisseurDeleteUrl, "POST", {
        id: fournisseurId,
      });

      if (response.success) {
        await fetchFournisseurTableData();
      } else {
        alert(response.message || "Erreur lors de la suppression");
      }
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de la suppression");
    }
  }
}

async function supprimerFournisseurSelectionne() {
  const selectedCheckboxes = document.querySelectorAll(
    ".fournisseur-checkbox:checked",
  );

  if (selectedCheckboxes.length === 0) {
    alert("Veuillez sélectionner au moins un fournisseur");
    return;
  }

  if (
    confirm(
      `Voulez-vous vraiment supprimer ${selectedCheckboxes.length} fournisseur(s) ?`,
    )
  ) {
    try {
      // Delete each one individually (API expects single ID per request)
      const promises = Array.from(selectedCheckboxes).map((cb) =>
        fetchApi(fournisseurDeleteUrl, "POST", {
          id: parseInt(cb.value),
        }),
      );

      await Promise.all(promises);
      await fetchFournisseurTableData();
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de la suppression");
    }
  }
}

document
  .getElementById("fournisseurs-delete-selected")
  ?.addEventListener("click", supprimerFournisseurSelectionne);

// Select all checkbox
document
  .getElementById("select-all-fournisseurs")
  ?.addEventListener("change", (e) => {
    document.querySelectorAll(".fournisseur-checkbox").forEach((cb) => {
      cb.checked = e.target.checked;
    });
    updateFournisseurDeleteButtonState();
  });

function updateFournisseurDeleteButtonState() {
  const anyChecked = document.querySelector(".fournisseur-checkbox:checked");
  const deleteBtn = document.getElementById("fournisseurs-delete-selected");
  if (deleteBtn) {
    deleteBtn.disabled = !anyChecked;
  }
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", async () => {
  await fetchFournisseurTableData();
});

// Refresh every 30 seconds if page is visible
setInterval(() => {
  if (!document.hidden) {
    fetchFournisseurTableData();
  }
}, 30000);
