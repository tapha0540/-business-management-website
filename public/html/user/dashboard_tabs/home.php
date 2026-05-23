<div class="home-container w-100">
    <div class="home-hero">
        <div>
            <span class="home-eyebrow">Vue principale</span>
            <h2>Bonjour <?= htmlspecialchars($user['prenom'] ?? 'Utilisateur') ?> 👋</h2>
            <p>Suivez les ventes, les commandes et les alertes stock depuis un seul espace.</p>
        </div>
        <div class="home-hero-chip">
            <span>Format régional</span>
            <strong>Français · Sénégal</strong>
        </div>
    </div>

    <div class="chart-card flex-fill">
        <div class="chart-card-header">
            <div>
                <span class="home-eyebrow">Performance</span>
                <h5>Chiffre d'affaires mensuel</h5>
            </div>
        </div>
        <canvas id="home-canvas" width="700"></canvas>
    </div>

    <div class="filter-card">
        <form id="dashboard-form" class="filter-form">
            <div class="filter-group">
                <label>Afficher les</label>
                <input type="number" name="limit" value="10" required />
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

            <button type="submit" class="btn btn-filter">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg></span>
                Afficher
            </button>
        </form>
    </div>

    <?php $spinnerId = 'home-spinner';
    require_once __DIR__ . '/../../component/spinner.php' ?>

    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Dernières commandes</span>
                    <h5>Commandes récentes</h5>
                </div>
            </div>
            <canvas id="latest-orders-canvas"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Meilleures commandes</span>
                    <h5>Top commandes par montant</h5>
                </div>
            </div>
            <canvas id="best-orders-canvas"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Meilleurs vendeurs</span>
                    <h5>Vendeurs les plus performants</h5>
                </div>
            </div>
            <div class="table-card-inner" id="best-sellers-table"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Produits les plus vendus</span>
                    <h5>Quantités commandées</h5>
                </div>
            </div>
            <canvas id="most-sold-products-canvas"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Meilleurs clients</span>
                    <h5>Clients les plus actifs</h5>
                </div>
            </div>
            <div class="table-card-inner" id="best-customers-table"></div>
        </div>
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <span class="home-eyebrow">Risque de rupture</span>
                    <h5>Stock critique</h5>
                </div>
            </div>
            <canvas id="product-at-risk-of-out-of-stock-canvas"></canvas>
        </div>
    </div>
    <div id="home-error-message"></div>
</div>