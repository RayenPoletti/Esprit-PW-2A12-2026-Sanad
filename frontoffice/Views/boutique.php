<?php
$pageTitle   = 'Boutique solidaire';
$currentPage = 'boutique';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>

<body class="starter-page-page">

<?php require_once __DIR__ . '/includes/spinner.php'; ?>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

  <main class="main">
    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>Boutique solidaire</h1>
              <p class="mb-0">Du matériel adapté vérifié, désinfecté et remis en circulation. Payez en dinars ou avec vos points solidaires.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="shop" class="section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Matériel adapté</h2>
      <p>10 points solidaires = 1 DT. Les dons sont gratuits.</p>
      <span class="badge rounded-pill sanad-module">Module 5 · Karim Bousetta · Produit ↔ Commande</span>
    </div>
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill sanad-chip align-self-start">Reconditionné</span>
              <h3 class="h5 mt-3">Fauteuil roulant pliant</h3>
              <p class="fs-4 fw-bold mb-0">350 DT</p>
              <p class="text-muted small">ou 3 500 points</p>
              <button type="button" class="btn sanad-btn mt-auto">Ajouter au panier</button>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill sanad-chip align-self-start">Reconditionné</span>
              <h3 class="h5 mt-3">Déambulateur 4 roues</h3>
              <p class="fs-4 fw-bold mb-0">120 DT</p>
              <p class="text-muted small">ou 1 200 points</p>
              <button type="button" class="btn sanad-btn mt-auto">Ajouter au panier</button>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill text-bg-warning align-self-start">Don</span>
              <h3 class="h5 mt-3">Siège de douche pliant</h3>
              <p class="fs-4 fw-bold mb-0">Gratuit</p>
              <p class="text-muted small">Réservé aux membres</p>
              <button type="button" class="btn sanad-btn mt-auto">Réserver ce don</button>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="250">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill sanad-chip align-self-start">Reconditionné</span>
              <h3 class="h5 mt-3">Loupe électronique</h3>
              <p class="fs-4 fw-bold mb-0">90 DT</p>
              <p class="text-muted small">ou 900 points</p>
              <button type="button" class="btn sanad-btn mt-auto">Ajouter au panier</button>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill text-bg-warning align-self-start">Don</span>
              <h3 class="h5 mt-3">Lit médicalisé</h3>
              <p class="fs-4 fw-bold mb-0">Gratuit</p>
              <p class="text-muted small">Réservé aux membres</p>
              <button type="button" class="btn sanad-btn mt-auto">Réserver ce don</button>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="350">
            <div class="sanad-card h-100 d-flex flex-column">
              <span class="badge rounded-pill sanad-chip align-self-start">Reconditionné</span>
              <h3 class="h5 mt-3">Coussin anti-escarres</h3>
              <p class="fs-4 fw-bold mb-0">45 DT</p>
              <p class="text-muted small">ou 450 points</p>
              <button type="button" class="btn sanad-btn mt-auto">Ajouter au panier</button>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
