<?php
/**
 * Settings Tab - User Profile & Account Management
 */
$user = $_SESSION['user'] ?? [];
?>

<div class="settings-container">
    <!-- Profile Section -->
    <div class="settings-card d-flex flex-column row-gap-2">
        <h3 class="d-flex align-items-center column-gap-4"><span class="app-icon" aria-hidden="true"><svg
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path>
                </svg></span> Mon Profil</h3>
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
                <div class="upload-overlay"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-2h6l2 2h4a2 2 0 0 1 2 2z">
                            </path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg></span></div>
            </div>
            <input type="file" id="profile-avatar-input" class="d-none" accept="image/*">

            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['prenom'] ?? 'User') . ' ' . htmlspecialchars($user['nom'] ?? ''); ?>
                </h3>
                <p><?php echo htmlspecialchars($user['email'] ?? 'email@example.com'); ?></p>
                <span class="profile-badge"><?php echo ucfirst($user['role'] ?? 'user'); ?></span>
            </div>
        </div>

        <form id="settings-form-group" class="d-flex flex-column row-gap-2  align-items-center">
            <div class="d-flex flex-column row-gap-2">
                <label>Prénom</label>
                <input class="form-control" type="text" name="prenom"
                    value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" placeholder="Votre prénom">
            </div>
            <div class="d-flex flex-column row-gap-2">
                <label>Nom</label>
                <input class="form-control" type="text" name="nom"
                    value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" placeholder="Votre nom">
            </div>

            <div class="d-flex flex-column row-gap-2">
                <label>Email</label>
                <input class="form-control" type="email" name="email"
                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" placeholder="Votre adresse email">
            </div>

            <button type="submit" class="btn btn-primary w-25 m-3"><span class="app-icon" aria-hidden="true"><svg
                        viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                        <path d="M7 3v5h8"></path>
                    </svg></span>
                Modifier</button>
        </form>
    </div>

    <!-- Password Section -->
    <form class="password-form settings-card d-flex flex-column row-gap-2 justify-content-center align-items-center">
        <h4><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg></span> Sécurité</h4>
        <p>Changez votre mot de passe pour sécuriser votre compte</p>

        <div class="settings-form-group d-flex flex-column row-gap-2 justify-content-center align-items-center">
            <div>
                <label>Mot de passe actuel</label>
                <input class="form-control" type="password" id="input-current-password"
                    placeholder="Entrez votre mot de passe actuel">
            </div>
            <div>
                <label>Nouveau mot de passe</label>
                <input class="form-control" type="password" id="input-new-password"
                    placeholder="Entrez un nouveau mot de passe">
            </div>
            <div>
                <label>Confirmer le mot de passe</label>
                <input class="form-control" type="password" id="input-confirm-password"
                    placeholder="Confirmez le nouveau mot de passe">
            </div>
        </div>
        <button class="btn btn-primary" onclick="changePassword()"><span class="app-icon" aria-hidden="true"><svg
                    viewBox="0 0 24 24">
                    <path d="M23 4v6h-6"></path>
                    <path d="M1 20v-6h6"></path>
                    <path d="M3.5 9a9 9 0 0 1 14.1-3.4L23 10"></path>
                    <path d="M20.5 15a9 9 0 0 1-14.1 3.4L1 14"></path>
                </svg></span> Changer le mot de passe</button>
    </form>


    <div class="settings-divider"></div>

    <!-- Danger Zone -->
    <div class="settings-card danger-zone-card">
        <h4 class="danger-zone-title"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                    <path d="M10.3 3.9 1.8 18A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"></path>
                    <path d="M12 9v4"></path>
                    <circle cx="12" cy="17" r="1"></circle>
                </svg></span> Zone de Danger</h4>
        <p class="danger-zone-text">Les actions ci-dessous sont irréversibles. Veuillez être prudent.</p>

        <div class="danger-zone-grid">
            <div>
                <h5 class="danger-zone-heading">Déconnexion de tous les appareils
                </h5>
                <p class="danger-zone-desc">Vous serez déconnecté de tous vos appareils, à
                    l'exception de celui-ci.</p>
                <a class="btn btn-outline-danger" href="/html/auth/logout.php">Déconnecter tous les appareils</a>
            </div>

            <div class="danger-zone-divider">
                <h5 class="danger-zone-heading">Supprimer le compte</h5>
                <p class="danger-zone-desc">Supprimez définitivement votre compte et toutes vos
                    données.</p>
                <button class="btn btn-outline-danger" onclick="deleteAccount()">Supprimer le compte</button>
            </div>
        </div>
    </div>
</div>