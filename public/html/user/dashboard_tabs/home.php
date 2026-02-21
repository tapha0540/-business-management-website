<style>
    .home-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .chart-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .chart-card h5 {
        color: #111827;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        letter-spacing: 0.3px;
    }

    #home-canvas {
        max-width: 100%;
        height: auto !important;
        margin: 0 auto !important;
    }

    /* Filter Form Card */
    .filter-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        border: 1px solid #f0f0f0;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-group label {
        color: #666;
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        margin: 0;
    }

    .filter-group input,
    .filter-group select {
        border: 2px solid #e5e7eb !important;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #ff4d00 !important;
        box-shadow: 0 0 0 3px rgba(255, 77, 0, 0.1) !important;
        outline: none;
    }

    .filter-group input[type="number"],
    .filter-group input[type="date"] {
        min-width: 130px;
    }

    .filter-group select {
        min-width: 200px;
    }

    .btn-filter {
        background: linear-gradient(135deg, #ff4d00 0%, #e84400 100%);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.75rem;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 77, 0, 0.3);
    }

    .btn-filter:active {
        transform: translateY(0);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .table-card h5 {
        color: #111827;
        font-weight: 700;
        padding: 2rem 2rem 1rem;
        margin: 0;
        font-size: 1.25rem;
        letter-spacing: 0.3px;
    }

    #home-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    #home-table thead th {
        background: #f9f9f9;
        border-bottom: 2px solid #e5e7eb;
        color: #111827;
        font-weight: 700;
        padding: 1rem;
        text-align: center;
        border-top: none;
    }

    #home-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    #home-table tbody tr {
        transition: all 0.2s ease;
    }

    #home-table tbody tr:hover {
        background-color: #f9f9f9;
        box-shadow: inset 4px 0 0 #ff4d00;
    }

    #home-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Error Message */
    #error-message {
        padding: 1rem;
        margin: 0;
        font-weight: 500;
    }

    /* Spinner */
    .spinner-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .filter-form {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-group {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
        }

        .btn-filter {
            width: 100%;
        }
    }
</style>

<div class="home-container w-100">
    <!-- Chart Section -->
    
    <!-- Filter Section -->
    <div class="filter-card">
        <form id="dashboard-form" class="filter-form">
            <div class="filter-group">
                <label>Afficher les</label>
                <input type="number" name="limit" value="10" required />
            </div>

            <div class="filter-group">
                <select name="search" required>
                    <option value="latest-orders">dernières commandes</option>
                    <option value="best-orders">meilleures commandes par montant</option>
                    <option value="best-sellers">meilleurs vendeurs</option>
                    <option value="most-sold-products">produits les plus vendus</option>
                    <option value="best-customers">meilleurs clients</option>
                    <option value="product-at-risk-of-out-of-stock">produits en risque de rupture</option>
                </select>
            </div>

            <div class="filter-group">
                <label>du</label>
                <?php
                $date = new DateTime();
                $date->modify("-1 year");
                ?>
                <input type="date" name="from" value="<?= $date->format('Y-m-d') ?>" max="<?= date('Y-m-d'); ?>"
                    required />
            </div>

            <div class="filter-group">
                <label>au</label>
                <input type="date" name="to" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" required />
            </div>

            <button type="submit" class="btn btn-filter">🔍 Afficher</button>
        </form>
    </div>

    <?php $spinnerId = 'home-spinner';
    require_once 'C:\Users\DELL\Dev\php\projet_final\public\html\component\spinner.php' ?>

    <!-- Table Section -->
    <div class="table-card">
        <h5>📈 Résultats</h5>
        <div class="table-responsive">
            <table id="home-table" class="table text-center">
                <thead>
                    <tr>
                        <!-- Headers will be added dynamically by JS -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
        <div id="error-message"></div>
    </div>
    <div class="chart-card flex-fill">
        <canvas id="home-canvas" width="700"></canvas>
    </div>
</div>