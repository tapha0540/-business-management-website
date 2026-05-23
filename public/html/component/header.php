<?php
$userImage = $user['imgUrl'] ?? null;
$userImageSrc = $userImage ? 'http://localhost:8081/storage/uploads/images/utilisateurs/' . rawurlencode(basename($userImage)) : null;
$userInitials = strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? 'U', 0, 1));
$userFullName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?: 'Utilisateur';
?>
<nav class="dashboard-header">
  <div class="d-flex column-gap-4 justify-content-center align-items-center">

    <span class="app-icon" aria-hidden="true" style="width: 30px; height: 30px;">
      <svg viewBox="0 0 24 24">
        <path d="M3 3v18h18"></path>
        <path d="M7 15l3-3 2 2 5-6"></path>
      </svg>
    </span>
    <h5 class="header-title">
      Dashboard
    </h5>
  </div>

  <div class="header-actions">
    <div class="notification-widget">
      <button class="notification-bell" id="notification-bell" type="button" title="Notifications produits"
        aria-label="Notifications produits" aria-expanded="false" aria-controls="notification-panel">
        <span class="app-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24">
            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"></path>
            <path d="M9 17a3 3 0 0 0 6 0"></path>
          </svg>
        </span>
        <span class="notification-badge d-none" id="notification-badge">0</span>
      </button>

      <div class="notification-panel d-none" id="notification-panel" role="dialog" aria-live="polite">
        <div class="notification-panel-header">
          <div>
            <strong>Stock critique</strong>
            <small id="notification-summary">Aucun produit en alerte</small>
          </div>
          <button class="btn btn-sm btn-outline-primary" id="notification-refresh" type="button">Actualiser</button>
        </div>
        <div class="notification-panel-body" id="notification-list"></div>
      </div>
    </div>


    <div class="dropdown">
      <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-avatar" data-user-avatar>
          <?php if ($userImageSrc): ?>
            <img src="<?= htmlspecialchars($userImageSrc) ?>" alt="Photo de profil">
          <?php else: ?>
            <span data-user-initials><?= htmlspecialchars($userInitials) ?></span>
          <?php endif; ?>
        </div>
        <div class="user-info">
          <h6 data-user-name><?= htmlspecialchars($userFullName) ?></h6>
          <small><?= htmlspecialchars($user['role'] ?? 'user') ?></small>
        </div>
      </div>

      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item btn-logout" href="/html/auth/logout.php"><span class="app-icon"
              aria-hidden="true"><svg viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <path d="m16 17 5-5-5-5"></path>
                <path d="M21 12H9"></path>
              </svg></span> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>