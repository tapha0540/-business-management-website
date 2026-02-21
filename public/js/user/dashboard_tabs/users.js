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

const usersTable = document.getElementById("users-table");
const tbody = usersTable.querySelector("tbody");
const rowTemplate = document.getElementById("users-row-template");
const emptyState = document.getElementById("users-empty-state");
const selectAllBtn = document.getElementById("select-all-users");
const deleteSelectedBtn = document.getElementById("users-delete-selected");

// Fetch and populate users
const fetchUsersTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/users/get_all.php",
      "POST",
      { limit: 10 },
    );
    console.log(serverRes);

    if (serverRes.data && serverRes.data.length > 0) {
      emptyState.classList.add("d-none");
      let delay = 0;

      serverRes.data.forEach((user) => {
        const newRow = rowTemplate.cloneNode(true);
        newRow.classList.remove("d-none");
        newRow.dataset.id = user.id;
        newRow.style.animation = `slideIn 0.4s ease forwards ${delay}ms`;

        newRow.querySelector(".col-prenom").textContent = user.prenom || "--";
        newRow.querySelector(".col-nom").textContent = user.nom || "--";
        newRow.querySelector(".col-email").textContent = user.email || "--";

        const roleEl = newRow.querySelector(".col-role span");
        const role = (user.role || "user").toLowerCase();
        roleEl.textContent = role.charAt(0).toUpperCase() + role.slice(1);
        roleEl.className = `role-badge role-${role}`;

        const statusEl = newRow.querySelector(".col-status span");
        statusEl.textContent = "Actif";
        statusEl.className = "status-badge status-active";

        newRow.querySelector(".col-created").textContent = new Date(
          user.created_at,
        ).toLocaleDateString("fr-FR");

        const editBtn = newRow.querySelector(".btn-edit");
        editBtn.addEventListener("click", (e) => {
          e.preventDefault();
          console.log("Edit user:", user.id);
        });

        const deleteBtn = newRow.querySelector(".btn-delete");
        deleteBtn.addEventListener("click", (e) => {
          e.preventDefault();
          deleteUser(user.id, newRow);
        });

        tbody.appendChild(newRow);
        delay += 50;
      });
    } else {
      emptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error fetching users:", error);
    const errorMsg = document.getElementById("users-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des utilisateurs.";
  }
};

const deleteUser = async (userId, rowElement) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) return;

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
    console.error("Error deleting user:", error);
    rowElement.classList.remove("fade-out");
  }
};

const deleteSelectedUsers = async () => {
  const checkedBoxes = tbody.querySelectorAll(".row-check:checked");
  if (checkedBoxes.length === 0) return;

  if (
    !confirm(
      `Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} utilisateur(s) ?`,
    )
  )
    return;

  checkedBoxes.forEach((checkbox) => {
    const row = checkbox.closest("tr");
    const userId = row.dataset.id;
    deleteUser(userId, row);
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

deleteSelectedBtn.addEventListener("click", deleteSelectedUsers);

fetchUsersTableData();
