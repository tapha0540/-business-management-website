
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

            <button type="submit" class="btn btn-filter">
                <span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path></svg></span>
                Afficher
            </button>
        </form>
    </div>

    <?php $spinnerId = 'home-spinner';
    require_once 'C:\Users\DELL\Dev\php\projet_final\public\html\component\spinner.php' ?>

    <div class="table-card">
        <h5><span class="app-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M7 14l3-3 3 2 4-5"></path></svg></span> Résultats</h5>
        <div class="table-responsive">
            <table id="home-table" class="table text-center">
                <thead>
                    <tr>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="home-error-message"></div>
    </div>
</div>
