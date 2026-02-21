<style>
    .usr-card {
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .usr-card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12) !important;
        transform: translateY(-2px);
    }

    .usr-card .card-header {
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .usr-card .card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .usr-card .card-header h5 {
        color: white;
        font-weight: 700;
        font-size: 1.35rem;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .usr-card .card-header small {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
    }

    .usr-card .card-body {
        padding: 2rem;
        background: white;
    }

    .usr-card .search-input {
        max-width: 420px;
    }

    .usr-card .search-input .input-group-text {
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-right: none;
        transition: all 0.3s ease;
    }

    .usr-card .search-input .form-control {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .usr-card .search-input .form-control:focus {
        border-color: #ff4d00;
        box-shadow: 0 0 0 3px rgba(255, 77, 0, 0.1);
    }

    .usr-card .form-select {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .usr-card .form-select:focus {
        border-color: #ff4d00;
        box-shadow: 0 0 0 3px rgba(255, 77, 0, 0.1);
    }

    .usr-card .table {
        margin-bottom: 0;
    }

    .usr-card .table thead th {
        vertical-align: middle;
        font-weight: 700;
        color: #111827;
        border-top: none;
        border-bottom: 2px solid #e5e7eb;
        background: #f9f9f9;
        padding: 1rem;
    }

    .usr-card .table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
    }

    .usr-card .table tbody tr {
        transition: all 0.25s ease;
    }

    .usr-card .table tbody tr:hover {
        background-color: #f9f9f9 !important;
        box-shadow: inset 4px 0 0 #ff4d00;
    }

    .usr-card .table tbody tr.fade-out {
        opacity: 0;
        transform: translateX(-10px);
    }

    .usr-empty {
        padding: 60px 24px;
        color: #6b7280;
    }

    .usr-empty strong {
        color: #111827;
        font-size: 1.1rem;
    }

    .table-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .table-actions .btn {
        white-space: nowrap;
        font-weight: 600;
        border-width: 2px;
        transition: all 0.2s ease;
    }

    .table-actions .btn-primary {
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        border: 2px solid transparent;
    }

    .table-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 77, 0, 0.3);
    }

    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background-color: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }

    .role-user {
        background-color: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }

    .role-seller {
        background-color: rgba(34, 197, 94, 0.1);
        color: #166534;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }

    .status-inactive {
        background-color: rgba(107, 114, 128, 0.1);
        color: #374151;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        border-width: 2px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: scale(1.05);
    }

    .btn-edit {
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-edit:hover {
        background-color: #3b82f6;
        color: white;
    }

    .btn-delete {
        color: #ef4444;
        border-color: #ef4444;
    }

    .btn-delete:hover {
        background-color: #ef4444;
        color: white;
    }

    .form-check-input {
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 1.25rem;
        height: 1.25rem;
    }

    .form-check-input:hover {
        border-color: #ff4d00;
    }

    .form-check-input:checked {
        background-color: #ff4d00;
        border-color: #ff4d00;
    }
</style>

<div class="card usr-card shadow mb-4 rounded-3 border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h5 class="mb-0">⚙️ Utilisateurs</h5>
            <small>Gestion des utilisateurs et permissions</small>
        </div>
        <div class="table-actions">
            <button class="btn btn-outline-secondary btn-sm">📊 Statistiques</button>
            <button class="btn btn-outline-danger btn-sm" id="users-delete-selected" disabled>🗑️ Supprimer
                sélection</button>
            <button class="btn btn-primary btn-sm">➕ Ajouter un utilisateur</button>
        </div>
    </div>

    <div class="card-body">
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
            <div class="input-group search-input">
                <span class="input-group-text border-0">🔍</span>
                <input id="users-search" type="search" class="form-control border-0"
                    placeholder="Rechercher par nom, email, rôle...">
            </div>

            <div class="d-flex gap-2">
                <select id="users-filter-role" class="form-select form-select-sm">
                    <option value="">Tous les rôles</option>
                    <option value="admin">Admin</option>
                    <option value="seller">Vendeur</option>
                    <option value="user">Utilisateur</option>
                </select>
                <button class="btn btn-outline-secondary btn-sm">⬇️ Exporter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="users-table" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-users" class="form-check-input"
                                title="Sélectionner tout" /></th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th style="width:140px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row template (hidden) to clone when populating rows via JS -->
                    <tr id="users-row-template" class="d-none">
                        <td><input type="checkbox" class="row-check form-check-input" /></td>
                        <td class="col-prenom">--</td>
                        <td class="col-nom">--</td>
                        <td class="col-email">--</td>
                        <td class="col-role"><span class="role-badge role-user">User</span></td>
                        <td class="col-status"><span class="status-badge status-active">Actif</span></td>
                        <td class="col-created">--</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-info btn-action btn-edit"
                                title="Éditer cet utilisateur">✏️ Éditer</button>
                            <button class="btn btn-sm btn-outline-danger btn-action btn-delete"
                                title="Supprimer cet utilisateur">🗑️ Supprimer</button>
                        </td>
                    </tr>

                    <!-- Empty state row shown when no data -->
                    <tr id="users-empty-state" class="table-empty">
                        <td colspan="8">
                            <div class="text-center usr-empty">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                                <p class="mb-2"><strong>Aucun utilisateur trouvé</strong></p>
                                <p class="small">Vous devez ajouter au moins un utilisateur. Cliquez sur "Ajouter un
                                    utilisateur" pour en créer un.</p>
                                <div class="mt-4">
                                    <button class="btn btn-primary btn-sm">➕ Ajouter un utilisateur</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="users-table-error-message" class="text-danger text-center mt-3"></div>
    </div>
</div>