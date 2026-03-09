<style>
  .dashboard-header {
    background: white;
    border-bottom: 2px solid #f0f0f0;
    padding: 0.25rem 0.7rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
  }

  .header-title {
    font-weight: 700;
    color: #111827;
    margin: 0;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .header-search {
    flex: 0 1 300px;
  }

  .header-search .form-control {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.6rem 1rem;
    transition: all 0.2s ease;
  }

  .header-search .form-control:focus {
    border-color: #ff4d00;
    box-shadow: 0 0 0 3px rgba(255, 77, 0, 0.1);
    outline: none;
  }

  .header-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
  }

  .notification-bell {
    position: relative;
    cursor: pointer;
    transition: transform 0.2s ease;
    color: #111827;
  }

  .notification-bell .app-icon {
    width: 1.5rem;
    height: 1.5rem;
  }

  .notification-bell:hover {
    transform: scale(1.1);
  }

  .notification-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
  }

  .user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem 1.25rem;
    background: #f9f9f9;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .user-profile:hover {
    background: #f0f0f0;
  }

  .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
  }

  .user-info h6 {
    margin: 0;
    font-weight: 600;
    color: #111827;
    font-size: 0.95rem;
  }

  .user-info small {
    color: #6b7280;
    font-size: 0.8rem;
  }

  .dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    padding: 0.5rem 0;
  }

  .dropdown-item {
    padding: 0.75rem 1.5rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .dropdown-item:hover {
    background: #f9f9f9;
    color: #ff4d00;
  }

  .dropdown-divider {
    margin: 0.5rem 0;
    border-color: #e5e7eb;
  }

  .btn-logout {
    background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
    color: white;
    border: none;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 77, 0, 0.3);
  }

  @media (max-width: 768px) {
    .dashboard-header {
      flex-direction: column;
      gap: 1rem;
    }

    .header-search {
      width: 100%;
      flex: 1;
    }

    .user-info h6,
    .user-info small {
      display: none;
    }

    .user-profile {
      padding: 0.5rem;
    }
  }
</style>

<nav class="dashboard-header">
  <h5 class="header-title">
    <span class="app-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M7 15l3-3 2 2 5-6"></path></svg>
    </span>
    Dashboard
  </h5>

  <div class="header-search">
    <input type="search" class="form-control" placeholder="Rechercher...">
  </div>

  <div class="header-actions">
    <div class="notification-bell" title="Notifications">
      <span class="app-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24"><path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"></path><path d="M9 17a3 3 0 0 0 6 0"></path></svg>
      </span>
      <span class="notification-badge">3</span>
    </div>

    <div class="dropdown">
      <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-avatar">
          <?php
          $initials = strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? 'U', 0, 1));
          echo $initials;
          ?>
        </div>
        <div class="user-info">
          <h6><?= htmlspecialchars($user['prenom'] ?? 'Utilisateur')  ?></h6>
          <small><?= htmlspecialchars($user['role'] ?? 'user') ?></small>
        </div>
      </div>

      <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header">Mon Profil</li>
        <li><a class="dropdown-item" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path></svg></span> Voir le profil</a></li>
        <li><a class="dropdown-item" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.6V21a2 2 0 1 1-4 0v-.2a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.6-1H3a2 2 0 1 1 0-4h.2a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.6V3a2 2 0 1 1 4 0v.2a1.7 1.7 0 0 0 1 1.6h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v.1a1.7 1.7 0 0 0 1.6 1H21a2 2 0 1 1 0 4h-.2a1.7 1.7 0 0 0-1.6 1z"></path></svg></span> Paramètres</a></li>
        <li><a class="dropdown-item" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span> Changer le mot de passe</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2.5-3 4"></path><circle cx="12" cy="17" r="1"></circle></svg></span> Aide</a></li>
        <li><a class="dropdown-item" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg></span> Support</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item btn-logout" href="#"><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="m16 17 5-5-5-5"></path><path d="M21 12H9"></path></svg></span> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

