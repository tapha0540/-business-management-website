<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('/html/auth/signup.html');
  exit;
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../css/style.css">
  <title>Document</title>
</head>

<body>


  <main class="row bg-light justify-content-center">

    <section class="col h-100 p-0 m-0 bg-lighter border-5 border-primary">
      <h5 class="text-center my-3 text-primary">Gestion Commerciale</h5>
      <div class="nav flex-column nav-pills m-1 justify-content-center align-items-center row-gap-3" id="v-pills-tab"
        role="tablist" aria-orientation="vertical">
        <button class="nav-link active w-75 p-2" id="v-pills-dashboard-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard"
          aria-selected="true">
          <img src="../../assets/images/icons/dashboard-layout.svg" width="24px" height="24px" class="" />
          Dashboard
        </button>
        <button class="nav-link w-75 p-2" id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders"
          type="button" role="tab" aria-controls="v-pills-orders" aria-selected="false">
          <img src="../../assets/images/icons/shoppin_bag.svg" width="24px" height="24px" class="" />
          Commandes
        </button>
        <button class="nav-link w-75 p-2" id="v-pills-products-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products"
          aria-selected="false">
          <img src="../../assets/images/icons/shopping_cart.svg" width="24px" height="24px" class="" />
          Produits
        </button>
        <button class="nav-link w-75 p-2" id="v-pills-clients-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-clients" type="button" role="tab" aria-controls="v-pills-clients"
          aria-selected="false">
          <img src="../../assets/images/icons/customers.svg" width="24px" height="24px" class="" />
          Clients
        </button>
        <button class="nav-link w-75 p-2" id="v-pills-settings-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
          aria-selected="false">
          <img src="../../assets/images/icons/settings.svg" width="24px" height="24px" class="" />
          Settings
        </button>
      </div>
    </section>

    <section class="col-10 h-100 p-0 m-0">
      <?php require_once '../component/header.php' ?>

      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel"
          aria-labelledby="v-pills-dashboard-tab">DashBoard</div>
        <div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">Commandes
        </div>
        <div class="tab-pane fade" id="v-pills-clients" role="tabpanel" aria-labelledby="v-pills-clients-tab">Clients
        </div>
        <div class="tab-pane fade" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
          product
        </div>
        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
          settings
        </div>
      </div>

    </section>

  </main>

  <script src="../../js/script.js"></script>
  <script src="../../js/user/dashboard.js"></script>
  <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>