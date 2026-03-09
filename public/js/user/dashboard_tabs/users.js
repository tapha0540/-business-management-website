const utilisateursTable = document.getElementById("utilisateurs-table");
const utilisateursTableTbody = utilisateursTable.querySelector("tbody");
const utilisateursTableRowTemplate = document.getElementById(
  "utilisateurs-row-template",
);
const utilisateursTableEmptyState = document.getElementById(
  "utilisateurs-empty-state",
);
const utilisateursTableSelectAllBtn = document.getElementById(
  "select-all-utilisateurs",
);
const utilisateursTableDeleteSelectedBtn = document.getElementById(
  "utilisateurs-delete-selected",
);
const ajouterUtilisateurForm = document.getElementById(
  "ajouter-utilisateur-form",
);
const modifierUtilisateurForm = document.getElementById(
  "modifier-utilisateur-form",
);

const usersGetInitiales = (prenom = "", nom = "") => {
  const p = (prenom || "").trim().charAt(0);
  const n = (nom || "").trim().charAt(0);
  return `${p}${n}`.toUpperCase() || "??";
};

const usersBindAvatarWithFallback = (imgEl, fallbackEl, imgUrl, initiales) => {
  if (!imgEl || !fallbackEl) return;

  fallbackEl.textContent = initiales;
  imgEl.onerror = () => {
    imgEl.classList.add("d-none");
    fallbackEl.classList.remove("d-none");
  };

  if (imgUrl) {
    imgEl.src = `http://localhost:8081/storage/uploads/images/utilisateurs/${imgUrl}`;
    imgEl.classList.remove("d-none");
    fallbackEl.classList.add("d-none");
  } else {
    imgEl.classList.add("d-none");
    fallbackEl.classList.remove("d-none");
  }
};

const utilisateursUpdateDeleteButtonState = () => {
  const checkboxes = utilisateursTableTbody.querySelectorAll(".row-check");
  const anyChecked = Array.from(checkboxes).some((cb) => cb.checked);
  utilisateursTableDeleteSelectedBtn.disabled = !anyChecked;
};

const fetchUtilisateursTableData = async () => {
  try {
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/utilisateurs/get_all.php",
      "GET",
    );

    if (serverRes.data && serverRes.data.length > 0) {
      utilisateursTableTbody.innerHTML = "";
      utilisateursTableEmptyState.classList.add("d-none");

      serverRes.data.forEach((user) => {
        const newRow = utilisateursTableRowTemplate.cloneNode(true);
        newRow.removeAttribute("id");
        newRow.classList.remove("d-none");
        newRow.dataset.id = user.id;

        const checkbox = newRow.querySelector(".row-check");
        checkbox.value = user.id;
        checkbox.onchange = utilisateursUpdateDeleteButtonState;

        newRow.querySelector(".col-prenom").textContent = user.prenom || "--";
        newRow.querySelector(".col-nom").textContent = user.nom || "--";
        newRow.querySelector(".col-email").textContent = user.email || "--";

        usersBindAvatarWithFallback(
          newRow.querySelector(".utilisateur-img"),
          newRow.querySelector(".utilisateur-avatar-fallback"),
          user.imgUrl,
          usersGetInitiales(user.prenom, user.nom),
        );

        const roleEl = newRow.querySelector(".col-role span");
        const role = (user.role || "vendeur").toLowerCase();
        roleEl.textContent = role.charAt(0).toUpperCase() + role.slice(1);
        roleEl.className = `role-badge role-${role}`;

        newRow.querySelector(".col-created").textContent = new Date(
          user.created_at,
        ).toLocaleDateString("fr-FR");

        const editBtn = newRow.querySelector(".btn-edit");
        editBtn.addEventListener("click", (e) => {
          e.preventDefault();
          modifierUtilisateur(user.id);
        });

        const deleteBtn = newRow.querySelector(".btn-delete");
        deleteBtn.addEventListener("click", (e) => {
          e.preventDefault();
          supprimerUtilisateurs([user.id]);
        });

        utilisateursTableTbody.appendChild(newRow);
      });

      utilisateursUpdateDeleteButtonState();
    } else {
      utilisateursTableTbody.innerHTML = "";
      utilisateursTableEmptyState.classList.remove("d-none");
      utilisateursTableDeleteSelectedBtn.disabled = true;
    }
  } catch (error) {
    console.error("Error fetching utilisateurs:", error);
    const errorMsg = document.getElementById(
      "utilisateurs-table-error-message",
    );
    errorMsg.textContent = "Erreur lors du chargement des utilisateurs.";
  }
};

utilisateursTableSelectAllBtn.addEventListener("change", () => {
  const checkboxes = utilisateursTableTbody.querySelectorAll(".row-check");
  checkboxes.forEach(
    (cb) => (cb.checked = utilisateursTableSelectAllBtn.checked),
  );
  utilisateursUpdateDeleteButtonState();
});

const ajouterUtilisateur = async (e) => {
  e.preventDefault();

  const imageFile = ajouterUtilisateurForm["utilisateur-img"]?.files?.[0];
  const formData = {
    prenom: ajouterUtilisateurForm["utilisateur-prenom"].value,
    nom: ajouterUtilisateurForm["utilisateur-nom"].value,
    email: ajouterUtilisateurForm["utilisateur-email"].value,
    mot_de_passe: ajouterUtilisateurForm["utilisateur-mot_de_passe"].value,
    role: ajouterUtilisateurForm["utilisateur-role"].value,
    image: imageFile ? await toBase64(imageFile) : null,
  };

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/utilisateurs/create.php",
    "POST",
    formData,
  );

  const msgEl = document.getElementById("ajouter-utilisateur-form-message");
  msgEl.classList.remove("text-success", "text-danger");
  msgEl.innerText = serverRes.message || "Erreur";
  if (serverRes.success) {
    msgEl.classList.add("text-success");
    ajouterUtilisateurForm.reset();
    fetchUtilisateursTableData();
  } else {
    msgEl.classList.add("text-danger");
  }
};

const supprimerUtilisateurs = async (utilisateurs_ids) => {
  if (!confirm("Êtes-vous sûr de vouloir supprimer ce(s) utilisateur(s) ?")) {
    return;
  }
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/utilisateurs/delete.php",
    "POST",
    { utilisateurs_ids },
  );
  alert(serverRes.message || "L'opération a échoué");
  if (serverRes.success) {
    fetchUtilisateursTableData();
  }
};

const getUtilisateur = async (id) => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/utilisateurs/get.php",
    "POST",
    { id },
  );
  if (serverRes.success) return serverRes.data;
  alert(serverRes.message || "Erreur");
  return null;
};

const modifierUtilisateur = async (utilisateurId) => {
  const user = await getUtilisateur(utilisateurId);
  if (!user) return;

  modifierUtilisateurForm["utilisateur-id"].value = user.id;
  modifierUtilisateurForm["utilisateur-prenom"].value = user.prenom || "";
  modifierUtilisateurForm["utilisateur-nom"].value = user.nom || "";
  modifierUtilisateurForm["utilisateur-email"].value = user.email || "";
  modifierUtilisateurForm["utilisateur-role"].value = user.role || "vendeur";
};

const supprimerUtilisateurSelectionne = async () => {
  const checkboxes = utilisateursTableTbody.querySelectorAll(".row-check");
  const utilisateurs_ids = [];
  checkboxes.forEach((cb) => {
    if (cb.checked) utilisateurs_ids.push(Number(cb.value));
  });
  if (utilisateurs_ids.length > 0)
    await supprimerUtilisateurs(utilisateurs_ids);
};

const modifierUtilisateurSubmit = async (e) => {
  e.preventDefault();
  const id = modifierUtilisateurForm["utilisateur-id"].value;
  const imageFile = modifierUtilisateurForm["utilisateur-img"]?.files?.[0];

  const formData = {
    id,
    prenom: modifierUtilisateurForm["utilisateur-prenom"].value,
    nom: modifierUtilisateurForm["utilisateur-nom"].value,
    email: modifierUtilisateurForm["utilisateur-email"].value,
    role: modifierUtilisateurForm["utilisateur-role"].value,
    image: imageFile ? await toBase64(imageFile) : null,
  };

  const pwdField = modifierUtilisateurForm["utilisateur-mot_de_passe"];
  if (pwdField && pwdField.value) {
    formData.mot_de_passe = pwdField.value;
  }

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/utilisateurs/update.php",
    "POST",
    formData,
  );

  const msgEl = document.getElementById("modifier-utilisateur-form-message");
  msgEl.classList.remove("text-success", "text-danger");
  msgEl.innerText = serverRes.message || "Erreur";
  if (serverRes.success) {
    msgEl.classList.add("text-success");
    fetchUtilisateursTableData();
  } else {
    msgEl.classList.add("text-danger");
  }
};

fetchUtilisateursTableData();
if (ajouterUtilisateurForm)
  ajouterUtilisateurForm.onsubmit = ajouterUtilisateur;
if (modifierUtilisateurForm)
  modifierUtilisateurForm.onsubmit = modifierUtilisateurSubmit;
if (utilisateursTableDeleteSelectedBtn)
  utilisateursTableDeleteSelectedBtn.onclick = supprimerUtilisateurSelectionne;
