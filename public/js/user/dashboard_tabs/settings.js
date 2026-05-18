const settingsFormGroup = document.getElementById("settings-form-group");
const profileAvatarInput = document.getElementById("profile-avatar-input");
const profileAvatarPreview = document.getElementById("profile-avatar-preview");
const profileAvatarInitials = document.querySelector(".profile-avatar-initials");
const settingsProfileMessage = document.getElementById("settings-profile-message");

const setProfileMessage = (message, isSuccess = true) => {
  if (!settingsProfileMessage) return;
  settingsProfileMessage.textContent = message;
  settingsProfileMessage.classList.remove("text-success", "text-danger");
  settingsProfileMessage.classList.add(isSuccess ? "text-success" : "text-danger");
};

const getProfileInitials = (prenom = "", nom = "") =>
  `${prenom.trim().charAt(0)}${nom.trim().charAt(0)}`.toUpperCase() || "??";

const getUtilisateurImageUrl = (imgUrl) =>
  imgUrl
    ? `http://localhost:8081/storage/uploads/images/utilisateurs/${encodeURIComponent(imgUrl)}`
    : "";

const updateAvatarPreview = (src) => {
  if (!profileAvatarPreview || !profileAvatarInitials) return;

  if (src) {
    profileAvatarPreview.src = src;
    profileAvatarPreview.classList.remove("d-none");
    profileAvatarInitials.classList.add("d-none");
  } else {
    profileAvatarPreview.removeAttribute("src");
    profileAvatarPreview.classList.add("d-none");
    profileAvatarInitials.classList.remove("d-none");
  }
};

const updateHeaderProfile = (user) => {
  const headerName = document.querySelector("[data-user-name]");
  const headerAvatar = document.querySelector("[data-user-avatar]");
  const initials = getProfileInitials(user?.prenom || "", user?.nom || "");
  const imageUrl = getUtilisateurImageUrl(user?.imgUrl);

  if (headerName && user?.prenom) {
    headerName.textContent = `${user.prenom || ""} ${user.nom || ""}`.trim();
  }

  if (profileAvatarInitials) {
    profileAvatarInitials.textContent = initials;
  }

  if (!headerAvatar) return;

  headerAvatar.innerHTML = imageUrl
    ? `<img src="${imageUrl}" alt="Photo de profil">`
    : `<span data-user-initials>${initials}</span>`;
};

document.querySelector(".profile-avatar.upload-area")?.addEventListener("click", () => {
  profileAvatarInput?.click();
});

profileAvatarInput?.addEventListener("change", async () => {
  const imageFile = profileAvatarInput.files?.[0];
  if (!imageFile) return;

  updateAvatarPreview(await toBase64(imageFile));
  await saveProfile();
});

settingsFormGroup?.addEventListener("submit", saveProfile);

async function saveProfile(event) {
  event?.preventDefault();

  const prenom = settingsFormGroup["prenom"].value.trim();
  const nom = settingsFormGroup["nom"].value.trim();
  const email = settingsFormGroup["email"].value.trim();
  const imageFile = profileAvatarInput?.files?.[0];

  if (!prenom || !nom || !email) {
    setProfileMessage("Veuillez remplir tous les champs obligatoires.", false);
    return;
  }

  const payload = { prenom, nom, email };

  if (imageFile) {
    payload.image = await toBase64(imageFile);
  }

  const serverRes = await fetchApi(
    "http://localhost:8081/routes/utilisateurs/update.php",
    "POST",
    payload,
    true,
  );

  if (serverRes.success) {
    setProfileMessage("Profil enregistré avec succès.");
    updateHeaderProfile(serverRes.data || payload);

    if (serverRes.data?.imgUrl) {
      updateAvatarPreview(getUtilisateurImageUrl(serverRes.data.imgUrl));
      profileAvatarInput.value = "";
    }
  } else {
    setProfileMessage(
      serverRes.message || "La modification des données de compte a échoué.",
      false,
    );
  }
}

function changePassword() {
  const currentPassword = document.getElementById(
    "input-current-password",
  ).value;
  const newPassword = document.getElementById("input-new-password").value;
  const confirmPassword = document.getElementById(
    "input-confirm-password",
  ).value;

  if (!currentPassword || !newPassword || !confirmPassword) {
    alert("Veuillez remplir tous les champs de mot de passe");
    return;
  }

  if (newPassword !== confirmPassword) {
    alert("Les mots de passe ne correspondent pas");
    return;
  }

  if (newPassword.length < 8) {
    alert("Le mot de passe doit contenir au moins 8 caractères");
    return;
  }

  alert("Mot de passe changé avec succès");

  document.getElementById("input-current-password").value = "";
  document.getElementById("input-new-password").value = "";
  document.getElementById("input-confirm-password").value = "";
}

function saveNotificationPreferences() {
  const preferences = {
    orders: document.getElementById("notify-orders")?.checked,
    stock: document.getElementById("notify-stock")?.checked,
    invoices: document.getElementById("notify-invoices")?.checked,
    messages: document.getElementById("notify-messages")?.checked,
    email: document.getElementById("notify-email")?.checked,
    newsletter: document.getElementById("notify-newsletter")?.checked,
  };

  console.log("Notification preferences:", preferences);
  alert("Préférences de notification enregistrées");
}

function saveAppearanceSettings() {
  const theme = document.getElementById("theme-select")?.value;
  const primaryColor = document.getElementById("primary-color")?.value;
  const density = document.getElementById("density-select")?.value;

  const settings = { theme, primaryColor, density };
  console.log("Appearance settings:", settings);
  alert("Paramètres d'apparence enregistrés");
}

function logoutAllDevices() {
  if (
    confirm("Êtes-vous sûr de vouloir vous déconnecter de tous les appareils ?")
  ) {
    alert("Vous avez été déconnecté de tous les appareils");
  }
}

function deleteAccount() {
  if (
    confirm(
      "Êtes-vous sûr ? Cette action est irréversible.\n\nCela supprimera définitivement votre compte et toutes vos données.",
    ) &&
    confirm(
      'DERNIÈRE CONFIRMATION\n\nTapez "DELETE" pour confirmer la suppression du compte.',
    )
  ) {
    alert("Votre compte a été supprimé avec succès");
  }
}
