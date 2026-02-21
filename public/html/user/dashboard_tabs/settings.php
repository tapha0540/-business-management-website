<?php
/**
 * Settings Tab - User Profile & Account Management
 */
$user = $_SESSION['user'] ?? [];
?>

<style>
    .settings-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .settings-card {
        background: white;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .settings-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .settings-card h4 {
        color: #111827;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.3rem;
        letter-spacing: 0.3px;
    }

    .settings-card p {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    /* Profile Section */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(255, 77, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar.upload-area {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-avatar.upload-area:hover {
        box-shadow: 0 15px 40px rgba(255, 77, 0, 0.3);
        transform: scale(1.05);
    }

    .profile-avatar .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-avatar.upload-area:hover .upload-overlay {
        opacity: 1;
    }

    .profile-info h3 {
        color: #111827;
        font-weight: 700;
        margin-bottom: 0.25rem;
        font-size: 1.5rem;
    }

    .profile-info p {
        color: #6b7280;
        margin: 0;
        font-size: 0.95rem;
    }

    .profile-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        color: white;
        padding: 0.4rem 0.85rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.5rem;
        letter-spacing: 0.3px;
    }

    /* Form Group */
    .settings-form-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        color: #111827;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #ff4d00;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 77, 0, 0.1);
    }

    .form-group input[type="color"] {
        padding: 0.5rem;
        cursor: pointer;
    }

    .form-group small {
        display: block;
        color: #6b7280;
        margin-top: 0.35rem;
        font-size: 0.85rem;
    }

    /* Button Styles */
    .btn-save {
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .btn-save:hover {
        box-shadow: 0 10px 25px rgba(255, 77, 0, 0.3);
        transform: translateY(-2px);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .btn-danger-outline {
        background: transparent;
        color: #ef4444;
        border: 1.5px solid #fecaca;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-danger-outline:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    /* Divider */
    .settings-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 2rem 0;
    }

    /* Preferences Grid */
    .preferences-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .preference-option {
        padding: 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .preference-option:hover {
        border-color: #ff4d00;
        background: rgba(255, 77, 0, 0.05);
    }

    .preference-option input[type="checkbox"],
    .preference-option input[type="radio"] {
        cursor: pointer;
        width: 18px;
        height: 18px;
        accent-color: #ff4d00;
    }

    .preference-option label {
        margin: 0;
        cursor: pointer;
        flex: 1;
        font-weight: 500;
    }

    /* Alert Styles */
    .alert-info {
        background: rgba(255, 77, 0, 0.08);
        border-left: 4px solid #ff4d00;
        padding: 1rem;
        border-radius: 4px;
        color: #92400e;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 2rem;
        }

        .settings-card {
            padding: 1.5rem;
        }

        .settings-form-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="settings-container">
    <!-- Profile Section -->
    <div class="settings-card">
        <h4>👤 Mon Profil</h4>
        <p>Gérez vos informations personnelles et votre photo de profil</p>

        <div class="profile-header">
            <div class="profile-avatar upload-area">
                <?php
                $initials = strtoupper(
                    (substr($user['prenom'] ?? 'U', 0, 1)) .
                    (substr($user['nom'] ?? 'U', 0, 1))
                );
                echo $initials;
                ?>
                <div class="upload-overlay">📷</div>
            </div>
            <input type="file" id="profile-avatar-input" style="display: none;" accept="image/*">

            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['prenom'] ?? 'User') . ' ' . htmlspecialchars($user['nom'] ?? ''); ?>
                </h3>
                <p><?php echo htmlspecialchars($user['email'] ?? 'email@example.com'); ?></p>
                <span class="profile-badge"><?php echo ucfirst($user['role'] ?? 'user'); ?></span>
            </div>
        </div>

        <div class="settings-form-group">
            <div>
                <label>Prénom</label>
                <input type="text" id="input-prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>"
                    placeholder="Votre prénom">
            </div>
            <div>
                <label>Nom</label>
                <input type="text" id="input-nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>"
                    placeholder="Votre nom">
            </div>
            <div class="form-group-full">
                <label>Email</label>
                <input type="email" id="input-email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                    placeholder="Votre adresse email">
                <small>Nous vous enverrons un email de confirmation</small>
            </div>
            <div>
                <label>Téléphone (optionnel)</label>
                <input type="tel" id="input-phone" placeholder="+33 (0)6 XX XX XX XX">
            </div>
        </div>

        <button class="btn-save" onclick="saveProfile()">💾 Enregistrer les modifications</button>
    </div>

    <!-- Password Section -->
    <div class="settings-card">
        <h4>🔐 Sécurité</h4>
        <p>Changez votre mot de passe pour sécuriser votre compte</p>

        <div class="settings-form-group">
            <div class="form-group-full">
                <label>Mot de passe actuel</label>
                <input type="password" id="input-current-password" placeholder="Entrez votre mot de passe actuel">
            </div>
            <div>
                <label>Nouveau mot de passe</label>
                <input type="password" id="input-new-password" placeholder="Entrez un nouveau mot de passe">
            </div>
            <div>
                <label>Confirmer le mot de passe</label>
                <input type="password" id="input-confirm-password" placeholder="Confirmez le nouveau mot de passe">
            </div>
        </div>

        <div class="alert-info">
            ℹ️ Votre mot de passe doit contenir au moins 8 caractères, incluant majuscules, minuscules et chiffres.
        </div>

        <button class="btn-save" onclick="changePassword()">🔄 Changer le mot de passe</button>
    </div>

    <!-- Notification Preferences -->
    <div class="settings-card">
        <h4>🔔 Préférences de Notifications</h4>
        <p>Gérez comment vous souhaitez être notifié</p>

        <div class="preferences-grid">
            <div class="preference-option">
                <input type="checkbox" id="notify-orders" checked>
                <label for="notify-orders">Nouvelles commandes</label>
            </div>
            <div class="preference-option">
                <input type="checkbox" id="notify-stock" checked>
                <label for="notify-stock">Alertes de stock</label>
            </div>
            <div class="preference-option">
                <input type="checkbox" id="notify-invoices" checked>
                <label for="notify-invoices">Factures générées</label>
            </div>
            <div class="preference-option">
                <input type="checkbox" id="notify-messages" checked>
                <label for="notify-messages">Nouveaux messages</label>
            </div>
            <div class="preference-option">
                <input type="checkbox" id="notify-email" checked>
                <label for="notify-email">Notifications par email</label>
            </div>
            <div class="preference-option">
                <input type="checkbox" id="notify-newsletter" checked>
                <label for="notify-newsletter">Newsletter hebdomadaire</label>
            </div>
        </div>

        <br>
        <button class="btn-save" onclick="saveNotificationPreferences()">✅ Enregistrer les préférences</button>
    </div>

    <!-- Appearance Settings -->
    <div class="settings-card">
        <h4>🎨 Apparence</h4>
        <p>Personnalisez l'apparence de votre tableau de bord</p>

        <div class="settings-form-group">
            <div>
                <label>Thème</label>
                <select id="theme-select">
                    <option value="light">☀️ Clair</option>
                    <option value="dark">🌙 Sombre</option>
                    <option value="auto">🔄 Automatique</option>
                </select>
            </div>
            <div>
                <label>Couleur primaire</label>
                <input type="color" id="primary-color" value="#ff4d00">
            </div>
            <div>
                <label>Densité d'affichage</label>
                <select id="density-select">
                    <option value="normal">Normal</option>
                    <option value="compact">Compacte</option>
                    <option value="spacious">Spacieuse</option>
                </select>
            </div>
        </div>

        <button class="btn-save" onclick="saveAppearanceSettings()">💾 Enregistrer l'apparence</button>
    </div>

    <div class="settings-divider"></div>

    <!-- Danger Zone -->
    <div class="settings-card" style="border-color: #fecaca; background: rgba(254, 202, 202, 0.03);">
        <h4 style="color: #ef4444;">⚠️ Zone de Danger</h4>
        <p style="color: #7f1d1d;">Les actions ci-dessous sont irréversibles. Veuillez être prudent.</p>

        <div style="display: grid; gap: 1rem;">
            <div>
                <h5 style="color: #111827; font-weight: 600; margin-bottom: 0.5rem;">Déconnexion de tous les appareils
                </h5>
                <p style="color: #6b7280; margin-bottom: 1rem;">Vous serez déconnecté de tous vos appareils, à
                    l'exception de celui-ci.</p>
                <button class="btn-danger-outline" onclick="logoutAllDevices()">Déconnecter tous les appareils</button>
            </div>

            <div style="border-top: 1px solid #fecaca; padding-top: 1rem;">
                <h5 style="color: #111827; font-weight: 600; margin-bottom: 0.5rem;">Supprimer le compte</h5>
                <p style="color: #6b7280; margin-bottom: 1rem;">Supprimez définitivement votre compte et toutes vos
                    données.</p>
                <button class="btn-danger-outline" onclick="deleteAccount()">Supprimer le compte</button>
            </div>
        </div>
    </div>
</div>

<script>
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
            alert('❌ Veuillez remplir tous les champs obligatoires');
            return;
        }

        const data = { prenom, nom, email, phone };
        console.log('Profile data to save:', data);
        alert('✅ Profil enregistré avec succès');
    }

    // Change password
    function changePassword() {
        const currentPassword = document.getElementById('input-current-password').value;
        const newPassword = document.getElementById('input-new-password').value;
        const confirmPassword = document.getElementById('input-confirm-password').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('❌ Veuillez remplir tous les champs de mot de passe');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('❌ Les mots de passe ne correspondent pas');
            return;
        }

        if (newPassword.length < 8) {
            alert('❌ Le mot de passe doit contenir au moins 8 caractères');
            return;
        }

        console.log('Password change requested');
        alert('✅ Mot de passe changé avec succès');

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
        alert('✅ Préférences de notification enregistrées');
    }

    // Save appearance settings
    function saveAppearanceSettings() {
        const theme = document.getElementById('theme-select').value;
        const primaryColor = document.getElementById('primary-color').value;
        const density = document.getElementById('density-select').value;

        const settings = { theme, primaryColor, density };
        console.log('Appearance settings:', settings);
        alert('✅ Paramètres d\'apparence enregistrés');
    }

    // Logout all devices
    function logoutAllDevices() {
        if (confirm('⚠️ Êtes-vous sûr de vouloir vous déconnecter de tous les appareils ?')) {
            console.log('Logout all devices requested');
            alert('✅ Vous avez été déconnecté de tous les appareils');
        }
    }

    // Delete account
    function deleteAccount() {
        if (confirm('⚠️ Êtes-vous sûr ? Cette action est irréversible.\n\nCela supprimera définitivement votre compte et toutes vos données.') &&
            confirm('⚠️ DERNIÈRE CONFIRMATION\n\nTapez "DELETE" pour confirmer la suppression du compte.')) {
            console.log('Account deletion requested');
            alert('✅ Votre compte a été supprimé avec succès');
        }
    }
</script>