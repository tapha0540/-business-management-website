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
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css">
  <title>Document</title>
</head>

<body>


  <main class="row bg-light column-gap-0">

    <section class="col h-100 bg-lighter">
      <h5 class="text-center my-3 text-primary">Gestion Commerciale</h5>
      <div class="nav flex-column nav-pills m-1 justify-content-center align-items-center row-gap-3" id="v-pills-tab"
        role="tablist" aria-orientation="vertical">
        <button class="nav-link active w-100" id="v-pills-dashboard-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard"
          aria-selected="true">Dashboard</button>
        <button class="nav-link w-100" id="v-pills-order-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order"
          type="button" role="tab" aria-controls="v-pills-order" aria-selected="false">Commandes</button>
        <button class="nav-link w-100" id="v-pills-messages-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages"
          aria-selected="false">Produits</button>
        <button class="nav-link w-100" id="v-pills-settings-tab" data-bs-toggle="pill"
          data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
          aria-selected="false">Clients</button>
        <button class="nav-link w-100" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-"
          type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
      </div>
    </section>

    <section class="col-10 h-100">
      <?php require_once '../component/header.php' ?>

      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel"
          aria-labelledby="v-pills-dashboard-tab">DashBoard</div>
        <div class="tab-pane fade" id="v-pills-order" role="tabpanel" aria-labelledby="v-pills-order-tab">Commandes
        </div>
        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
        </div>
        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...
        </div>
      </div>
      
    </section>

  </main>

  <script src="../../js/script.js"></script>
  <script src="../../js/user/dashboard.js"></script>
  <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>