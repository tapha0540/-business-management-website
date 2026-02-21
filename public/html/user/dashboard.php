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
  <link rel="stylesheet" href="../../css/dashboard_tabs/commandes.css">
  <title>Dashboard</title>
</head>

<body>

  <style>
    :root {
      --primary-color: #ff4d00;
      --primary-dark: #e84400;
      --bg-sidebar: #0f0f0f;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #f5f5f5 0%, #efefef 100%);
      overflow: hidden;
    }

    main.row {
      flex: 1;
      gap: 1.25rem;
      padding: 1.25rem;
      overflow: visible;
      margin-bottom: 0 !important;
      display: flex;
      min-height: 0;
    }

    section:first-child h5 {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 1.5rem;
      margin: 0 !important;
      font-weight: 700;
      letter-spacing: 0.7px;
      border-bottom: 2px solid var(--primary-color);
    }

    .nav-pills {
      overflow-y: auto;
      flex: 1;
    }

    .nav-pills::-webkit-scrollbar {
      width: 6px;
    }

    .nav-pills::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 10px;
    }

    .nav-link {
      border-radius: 8px;
      transition: all 0.25s ease;
      color: #aaa !important;
    }

    .nav-link:hover {
      /* background: rgba(255, 77, 0, 0.1); */
      color: white !important;
      transform: translateX(4px);
    }

    .nav-link.active {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
      /* color: white !important; */
      box-shadow: 0 4px 15px rgba(255, 77, 0, 0.3);
    }

   

    .tab-content {
      flex: 1;
      overflow-y: auto;
    }

    .tab-content .tab-pane {
      animation: fadeInTab 0.3s ease;
    }

    @keyframes fadeInTab {
      from {
        opacity: 0;
        transform: translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-dark);
    }

    /* Fix flexbox scrolling: ensure flex children can shrink and scroll */
    section {
      min-height: 0;
    }

    section.col-10.d-flex.flex-column {
      min-height: 0;
      height: 100%;
    }

    .tab-content {
      flex: 1 1 auto;
      min-height: 0;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
    }

    .tab-pane {
      min-height: 0;
      overflow: auto;
    }
  </style>

  <main class="row bg-light justify-content-center">

    <section class="col h-100 p-0 m-0 border-5 border-primary shadow-sm rounded-2 bg-lighter">
      <h5 class="text-center my-3 text-lighter rounded-3">Gestion Commerciale</h5>
      <div class="nav flex-column nav-pills m-1 justify-content-center align-items-center row-gap-3 bg-lighter" id="v-pills-tab"
        role="tablist" aria-orientation="vertical">
        <button class="nav-link active w-75 p-2 text-dark fs-6" id="v-pills-dashboard-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard"
          aria-selected="true">
          <img src="../../assets/images/icons/dashboard-layout.svg" width="24px" height="24px" class="" />
          Dashboard
        </button>
        <button class="nav-link w-75 p-2 text-dark fs-6" id="v-pills-orders-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-orders" type="button" role="tab" aria-controls="v-pills-orders"
          aria-selected="false">
          <img src="../../assets/images/icons/shoppin_bag.svg" width="24px" height="24px" class="" />
          Commandes
        </button>
        <button class="nav-link w-75 p-2 text-dark fs-6" id="v-pills-products-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products"
          aria-selected="false">
          <img src="../../assets/images/icons/shopping_cart.svg" width="24px" height="24px" class="" />
          Produits
        </button>
        <button class="nav-link w-75 p-2 text-dark fs-6" id="v-pills-clients-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-clients" type="button" role="tab" aria-controls="v-pills-clients"
          aria-selected="false">
          <img src="../../assets/images/icons/customers.svg" width="24px" height="24px" class="" />
          Clients
        </button>
        <?php if ($estAdmin): ?>
          <button class="nav-link w-75 p-2 text-dark fs-6" id="v-pills-settings-tab" data-bs-toggle="pill"
            data-bs-target="#v-pills-users" type="button" role="tab" aria-controls="v-pills-users" aria-selected="false">
            <img src="../../assets/images/icons/users.svg" width="24px" height="24px" class="" />
            Utilisateurs
          </button>
        <?php endif; ?>
        <button class="nav-link w-75 p-2 text-dark fs-6" id="v-pills-settings-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
          aria-selected="false">
          <img src="../../assets/images/icons/settings.svg" width="24px" height="24px" class="" />
          Settings
        </button>
      </div>
    </section>

    <section class="col-10 p-0 m-0 rounded-2 d-flex flex-column">
      <?php require_once '../component/header.php' ?>

      <div class="tab-content flex-grow-1" id="v-pills-tabContent">
        <div class="tab-pane fade show active p-3 d-flex flex-column row-gap-2" id="v-pills-dashboard" role="tabpanel"
          aria-labelledby="v-pills-dashboard-tab">
          <?php require_once 'dashboard_tabs/home.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2 " id="v-pills-orders" role="tabpanel"
          aria-labelledby="v-pills-orders-tab">
          <?php require_once 'dashboard_tabs/commandes.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2" id="v-pills-clients" role="tabpanel"
          aria-labelledby="v-pills-clients-tab">
          Clients
          <?php require_once 'dashboard_tabs/clients.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2" id="v-pills-products" role="tabpanel"
          aria-labelledby="v-pills-products-tab">
          <?php require_once 'dashboard_tabs/produits.php' ?>
        </div>

        <!-- Les Onglets pour les admins seulement. Quand un utilisateur n'est pas un admin, 
         il ne sera pas autorisé à accéder à ces fonctionnalités. -->
        <?php if ($estAdmin): ?>
          <div class="tab-pane fade p-3 d-flex flex-column row-gap-2" id="v-pills-users" role="tabpanel"
            aria-labelledby="v-pills-users-tab">
            <?php require_once 'dashboard_tabs/users.php' ?>
          </div>
        <?php endif; ?>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2" id="v-pills-settings" role="tabpanel"
          aria-labelledby="v-pills-settings-tab">
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
  <script src="../../js/user/dashboard_tabs/home.js"></script>
  <script src="../../js/user/dashboard_tabs/commandes.js"></script>
  <script src="../../js/user/dashboard_tabs/produits.js"></script>
  <script src="../../js/user/dashboard_tabs/clients.js"></script>
  <script src="../../js/user/dashboard_tabs/users.js"></script>
  <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>