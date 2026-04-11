 // Avatar upload click handler
    document.querySelector('.profile-avatar.upload-area').addEventListener('click', function () {
        document.getElementById('profile-avatar-input').click();
    });

    // Save profile
    function saveProfile() {
        const prenom = document.getElementById('input-prenom').value;
        const nom = document.getElementById('input-nom').value;
        const email = document.getElementById('input-email').value;
        const phone = document.getElementById('input-phone').value;

        if (!prenom || !nom || !email) {
            alert('Veuillez remplir tous les champs obligatoires');
            return;
        }

        const data = { prenom, nom, email, phone };
        console.log('Profile data to save:', data);
        alert('Profil enregistré avec succès');
    }

    // Change password
    function changePassword() {
        const currentPassword = document.getElementById('input-current-password').value;
        const newPassword = document.getElementById('input-new-password').value;
        const confirmPassword = document.getElementById('input-confirm-password').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Veuillez remplir tous les champs de mot de passe');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('Les mots de passe ne correspondent pas');
            return;
        }

        if (newPassword.length < 8) {
            alert('Le mot de passe doit contenir au moins 8 caractères');
            return;
        }

        console.log('Password change requested');
        alert('Mot de passe changé avec succès');

        // Clear password fields
        document.getElementById('input-current-password').value = '';
        document.getElementById('input-new-password').value = '';
        document.getElementById('input-confirm-password').value = '';
    }

    // Save notification preferences
    function saveNotificationPreferences() {
        const preferences = {
            orders: document.getElementById('notify-orders').checked,
            stock: document.getElementById('notify-stock').checked,
            invoices: document.getElementById('notify-invoices').checked,
            messages: document.getElementById('notify-messages').checked,
            email: document.getElementById('notify-email').checked,
            newsletter: document.getElementById('notify-newsletter').checked
        };

        console.log('Notification preferences:', preferences);
        alert('Préférences de notification enregistrées');
    }

    // Save appearance settings
    function saveAppearanceSettings() {
        const theme = document.getElementById('theme-select').value;
        const primaryColor = document.getElementById('primary-color').value;
        const density = document.getElementById('density-select').value;

        const settings = { theme, primaryColor, density };
        console.log('Appearance settings:', settings);
        alert('Paramètres d\'apparence enregistrés');
    }

    // Logout all devices
    function logoutAllDevices() {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter de tous les appareils ?')) {
            console.log('Logout all devices requested');
            alert('Vous avez été déconnecté de tous les appareils');
        }
    }

    // Delete account
    function deleteAccount() {
        if (confirm('Êtes-vous sûr ? Cette action est irréversible.\n\nCela supprimera définitivement votre compte et toutes vos données.') &&
            confirm('DERNIÈRE CONFIRMATION\n\nTapez "DELETE" pour confirmer la suppression du compte.')) {
            console.log('Account deletion requested');
            alert('Votre compte a été supprimé avec succès');
        }
    }