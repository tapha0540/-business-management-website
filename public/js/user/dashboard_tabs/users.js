
const usersTable = document.getElementById("users-table");
const usersTableTbody = usersTable.querySelector("tbody");
const usersTableRowTemplate = document.getElementById("users-row-template");
const usersTableEmptyState = document.getElementById("users-empty-state");
const usersTableSelectAllBtn = document.getElementById("select-all-users");
const usersTableDeleteSelectedBtn = document.getElementById("users-delete-selected");

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
      usersTableEmptyState.classList.add("d-none");
      let delay = 0;

      serverRes.data.forEach((user) => {
        const newRow = usersTableRowTemplate.cloneNode(true);
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

        usersTableTbody.appendChild(newRow);
        delay += 50;
      });
    } else {
      usersTableEmptyState.classList.remove("d-none");
    }
  } catch (error) {
    console.error("Error fetching users:", error);
    const errorMsg = document.getElementById("users-table-error-message");
    errorMsg.textContent = "Erreur lors du chargement des utilisateurs.";
  }
};

usersTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = usersTableTbody.querySelectorAll(".row-check");
  checkboxes.forEach((cb) => (cb.checked = usersTableSelectAllBtn.checked));
  updateDeleteButtonState();
});


// fetchUsersTableData();
