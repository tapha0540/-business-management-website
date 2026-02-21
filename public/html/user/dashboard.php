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
  <title>Dashboard</title>
</head>

<body>

  <main class="row bg-light justify-content-center">

    <section class="col h-100 p-0 m-0 bg-lighter border-5 border-primary shadow-sm rounded-2">
      <h5 class="text-center my-3 text-primary">Gestion Commerciale</h5>
      <div class="nav flex-column nav-pills m-1 justify-content-center align-items-center row-gap-3" id="v-pills-tab"
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

    <section class="col-10 h-100 p-0 m-0 rounded-2">
      <?php require_once '../component/header.php' ?>

      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active p-3 d-flex flex-column row-gap-2  flex-fill" id="v-pills-dashboard"
          role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
          <?php require_once 'dashboard_tabs/home.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2  flex-fill align-content-start justify-content-start"
          id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
          <?php require_once 'dashboard_tabs/commandes.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2  flex-fill" id="v-pills-clients" role="tabpanel"
          aria-labelledby="v-pills-clients-tab">
          Clients
          <?php require_once 'dashboard_tabs/clients.php' ?>
        </div>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2  flex-fill" id="v-pills-products" role="tabpanel"
          aria-labelledby="v-pills-products-tab">
          <?php require_once 'dashboard_tabs/produits.php' ?>
        </div>

        <!-- Les Onglets pour les admins seulement. Quand un utilisateur n'est pas un admin, 
         il ne sera pas autorisé à accéder à ces fonctionnalités. -->
        <?php if ($estAdmin): ?>
          <div class="tab-pane fade p-3 d-flex flex-column row-gap-2  flex-fill" id="v-pills-users" role="tabpanel"
            aria-labelledby="v-pills-users-tab">
            <?php require_once 'dashboard_tabs/users.php' ?>
          </div>
        <?php endif; ?>

        <div class="tab-pane fade p-3 d-flex flex-column row-gap-2  flex-fill" id="v-pills-settings" role="tabpanel"
          aria-labelledby="v-pills-settings-tab">
          <?php require_once 'dashboard_tabs/settings.php' ?>
        </div>
      </div>

    </section>

  </main>

  <script src="../../js/script.js"></script>
  <!-- <script src="../../chart.js-4.5.1/dist/chart.umd.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../js/user/dashboard.js"></script>
  <script src="../../js/user/dashboard_tabs/home.js"></script>
  <script src="../../js/user/dashboard_tabs/commandes.js"></script>
  <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>