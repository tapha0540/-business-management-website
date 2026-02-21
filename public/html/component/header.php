<style>
  .dashboard-header {
    background: white;
    border-bottom: 2px solid #f0f0f0;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
  }

  .header-title {
    font-weight: 700;
    color: #111827;
    font-size: 1.5rem;
    margin: 0;
    letter-spacing: 0.3px;
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
    font-size: 1.5rem;
    transition: transform 0.2s ease;
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
  <h1 class="header-title">📊 Dashboard</h1>

  <div class="header-search">
    <input type="search" class="form-control" placeholder="🔍 Rechercher...">
  </div>

  <div class="header-actions">
    <div class="notification-bell" title="Notifications">
      🔔
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
        <li><a class="dropdown-item" href="#">👤 Voir le profil</a></li>
        <li><a class="dropdown-item" href="#">⚙️ Paramètres</a></li>
        <li><a class="dropdown-item" href="#">🔒 Changer le mot de passe</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="#">❓ Aide</a></li>
        <li><a class="dropdown-item" href="#">📧 Support</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item btn-logout" href="#">🚪 Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>