<?php
/**
 * Settings Tab - User Profile & Account Management
 */
$user = $_SESSION['user'] ?? [];
?>

<div class="settings-container">
    <!-- Profile Section -->
    <div class="settings-card">
        <h4><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path></svg></span> Mon Profil</h4>
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
                <div class="upload-overlay"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-2h6l2 2h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg></span></div>
            </div>
            <input type="file" id="profile-avatar-input" class="d-none" accept="image/*">

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

        <button class="btn-save" onclick="saveProfile()"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path><path d="M7 3v5h8"></path></svg></span> Enregistrer les modifications</button>
    </div>

    <!-- Password Section -->
    <div class="settings-card">
        <h4><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span> Sécurité</h4>
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
            Votre mot de passe doit contenir au moins 8 caractères, incluant majuscules, minuscules et chiffres.
        </div>

        <button class="btn-save" onclick="changePassword()"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.5 9a9 9 0 0 1 14.1-3.4L23 10"></path><path d="M20.5 15a9 9 0 0 1-14.1 3.4L1 14"></path></svg></span> Changer le mot de passe</button>
    </div>

    <!-- Notification Preferences -->
    <div class="settings-card">
        <h4><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"></path><path d="M9 17a3 3 0 0 0 6 0"></path></svg></span> Préférences de Notifications</h4>
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
        <button class="btn-save" onclick="saveNotificationPreferences()"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"></path></svg></span> Enregistrer les préférences</button>
    </div>

    <!-- Appearance Settings -->
    <div class="settings-card">
        <h4><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 22a10 10 0 1 1 0-20 7 7 0 0 1 0 14h-1a3 3 0 0 0 0 6z"></path></svg></span> Apparence</h4>
        <p>Personnalisez l'apparence de votre tableau de bord</p>

        <div class="settings-form-group">
            <div>
                <label>Thème</label>
                <select id="theme-select">
                    <option value="light">Clair</option>
                    <option value="dark">Sombre</option>
                    <option value="auto">Automatique</option>
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

        <button class="btn-save" onclick="saveAppearanceSettings()"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path><path d="M7 3v5h8"></path></svg></span> Enregistrer l'apparence</button>
    </div>

    <div class="settings-divider"></div>

    <!-- Danger Zone -->
    <div class="settings-card danger-zone-card">
        <h4 class="danger-zone-title"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"></path><path d="M12 9v4"></path><circle cx="12" cy="17" r="1"></circle></svg></span> Zone de Danger</h4>
        <p class="danger-zone-text">Les actions ci-dessous sont irréversibles. Veuillez être prudent.</p>

        <div class="danger-zone-grid">
            <div>
                <h5 class="danger-zone-heading">Déconnexion de tous les appareils
                </h5>
                <p class="danger-zone-desc">Vous serez déconnecté de tous vos appareils, à
                    l'exception de celui-ci.</p>
                <button class="btn-danger-outline" onclick="logoutAllDevices()">Déconnecter tous les appareils</button>
            </div>

            <div class="danger-zone-divider">
                <h5 class="danger-zone-heading">Supprimer le compte</h5>
                <p class="danger-zone-desc">Supprimez définitivement votre compte et toutes vos
                    données.</p>
                <button class="btn-danger-outline" onclick="deleteAccount()">Supprimer le compte</button>
            </div>
        </div>
    </div>
</div>
