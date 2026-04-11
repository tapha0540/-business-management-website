<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('/html/auth/signup.html');
  exit;
}
/**
 * @var array{id: int, prenom: string, nom: string, email: string, role: string, created_at: string, updated_at: string}
 */
$user = $_SESSION['user'];
$estAdmin = $user['role'] == 'admin';
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/user/dashboard.css">
  <link rel="stylesheet" href="../../css/user/components/header.css">
  <link rel="stylesheet" href="../../css/user/components/footer.css">
  <link rel="stylesheet" href="../../css/user/dashboard_tabs/commandes.css">
  <link rel="stylesheet" href="../../css/user/dashboard_tabs/approvisionnements.css">
  <link rel="stylesheet" href="../../css/user/dashboard_tabs/home.css">
  <link rel="stylesheet" href="../../css/user/dashboard_tabs/settings.css">
  <title>Dashboard</title>
</head>

<body>

  <main class="row bg-light justify-content-center">

    <section class="col h-100 p-0 border-5 border-primary shadow-sm rounded-2 bg-lighter ms-2">
      <h5 class="text-center my-3 text-lighter rounded-3">Gestion Commerciale</h5>
      <div class="nav flex-column nav-pills m-1 justify-content-center align-items-center row-gap-3 bg-lighter mt-4"
        id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <button style="font-size: small;" class="nav-link  fw-light active w-75 p-2 text-dark" id="v-pills-dashboard-tab"
          data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab"
          aria-controls="v-pills-dashboard" aria-selected="true">
          <span class="app-icon app-icon-lg" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M3 3v18h18"></path>
              <path d="M7 15l3-3 2 2 5-6"></path>
            </svg>
          </span>
          Dashboard
        </button>
        <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-orders-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-orders" type="button" role="tab" aria-controls="v-pills-orders"
          aria-selected="false">
          <img src="../../assets/images/icons/shoppin_bag.svg" width="24px" height="24px" class="" />
          Commandes
        </button>
        <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-clients-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-clients" type="button" role="tab" aria-controls="v-pills-clients"
          aria-selected="false">
          <span class="app-icon app-icon-sm" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.9"></path>
              <path d="M16 3.1a4 4 0 0 1 0 7.8"></path>
            </svg>
          </span>
          Clients
        </button>
        <?php if ($estAdmin): ?>
          <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-products-tab" data-bs-toggle="pill"
            data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products"
            aria-selected="false">
            <span class="app-icon app-icon-lg" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-package-icon lucide-package">
                <path
                  d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                <path d="M12 22V12" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <path d="m7.5 4.27 9 5.15" />
              </svg>
            </span>
            Produits
          </button>
          <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-fournisseurs-tab" data-bs-toggle="pill"
            data-bs-target="#v-pills-fournisseurs" type="button" role="tab" aria-controls="v-pills-fournisseurs"
            aria-selected="false">
            <span class="app-icon app-icon-lg" aria-hidden="true">
              <svg viewBox="0 0 24 24">
                <rect x="1" y="3" width="15" height="13"></rect>
                <path d="M16 8h4l3 3v5h-7"></path>
                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                <circle cx="18.5" cy="18.5" r="2.5"></circle>
              </svg>
            </span>
            Fournisseurs
          </button>
          <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-approv-tab" data-bs-toggle="pill"
            data-bs-target="#v-pills-approv" type="button" role="tab" aria-controls="v-pills-approv"
            aria-selected="false">
            <span class="app-icon app-icon-md" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-package-plus-icon lucide-package-plus">
                <path d="M12 22V12" />
                <path d="M16 17h6" />
                <path d="M19 14v6" />
                <path
                  d="M21 10.535V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.675-.955" />
                <path d="M3.29 7 12 12l8.71-5" />
                <path d="m7.5 4.27 8.997 5.148" />
              </svg>
            </span>
            Approvisionnements
          </button>
          <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-users-tab" data-bs-toggle="pill"
            data-bs-target="#v-pills-users" type="button" role="tab" aria-controls="v-pills-users" aria-selected="false">
            <img src="../../assets/images/icons/users.svg" width="24px" height="24px" class="" />
            Utilisateurs
          </button>
        <?php endif; ?>
        <button style="font-size: small;" class="nav-link  fw-light w-75 p-2 text-dark" id="v-pills-settings-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
          aria-selected="false">
          <img src="../../assets/images/icons/settings.svg" width="24px" height="24px" class="" />
          Settings
        </button>
      </div>
    </section>

    <section class="col-10 m-0 rounded-2 d-flex flex-column">
      <?php require_once '../component/header.php' ?>

      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active p-3" id="v-pills-dashboard" role="tabpanel"
          aria-labelledby="v-pills-dashboard-tab">
          <?php require_once 'dashboard_tabs/home.php' ?>
        </div>

        <div class="tab-pane fade p-3 " id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
          <?php require_once 'dashboard_tabs/commandes.php' ?>
        </div>

        <div class="tab-pane fade p-3" id="v-pills-clients" role="tabpanel" aria-labelledby="v-pills-clients-tab">
          <?php require_once 'dashboard_tabs/clients.php' ?>
        </div>


        <!-- Les Onglets pour les admins seulement. Quand un utilisateur n'est pas un admin, 
        il ne sera pas autorisé à accéder à ces fonctionnalités. -->
        <?php if ($estAdmin): ?>
          <div class="tab-pane fade p-3" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
            <?php require_once 'dashboard_tabs/produits.php' ?>
          </div>
          <div class="tab-pane fade p-3" id="v-pills-fournisseurs" role="tabpanel"
            aria-labelledby="v-pills-fournisseurs-tab">
            <?php require_once 'dashboard_tabs/fournisseurs.php' ?>
          </div>
          <div class="tab-pane fade p-3" id="v-pills-approv" role="tabpanel" aria-labelledby="v-pills-approv-tab">
            <?php require_once 'dashboard_tabs/approvisionnements.php' ?>
          </div>
          <div class="tab-pane fade p-3" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab">
            <?php require_once 'dashboard_tabs/users.php' ?>
          </div>
        <?php endif; ?>

        <div class="tab-pane fade p-3" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
          <?php require_once 'dashboard_tabs/settings.php' ?>
        </div>
        <?php require_once '../component/footer.php' ?>
      </div>

    </section>
  </main>

  <script src="../../js/script.js"></script>
  <!-- <script src="../../chart.js-4.5.1/dist/chart.umd.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../js/user/dashboard.js"></script>
  <script src="../../js/user/dashboard_tabs/categories.js"></script>
  <script src="../../js/user/dashboard_tabs/home.js"></script>
  <script src="../../js/user/dashboard_tabs/commandes.js"></script>
  <script src="../../js/user/dashboard_tabs/produits.js"></script>
  <script src="../../js/user/dashboard_tabs/clients.js"></script>
  <script src="../../js/user/dashboard_tabs/approvisionnements.js"></script>
  <script src="../../js/user/dashboard_tabs/fournisseurs.js"></script>
  <script src="../../js/user/dashboard_tabs/users.js"></script>
  <script src="../../js/user/dashboard_tabs/settings.js"></script>
  <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>