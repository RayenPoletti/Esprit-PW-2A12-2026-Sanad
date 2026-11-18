<?php
/**
 * Boutique solidaire · Module 5 · Karim Bousetta
 * Variables : $produits (Produit[]) lus dans la base par BoutiqueController — ajoutés depuis le Back Office ; $csrfToken, $nbPanier, $flash
 */
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
        <?= bandeauFlash($flash) ?>
        <?php if ($nbPanier > 0): ?>
          <div class="alert alert-info d-flex justify-content-between align-items-center"><span><?= (int) $nbPanier ?> article(s) dans votre panier.</span><a class="btn btn-sm sanad-btn" href="index.php?page=panier">Voir mon panier</a></div>
        <?php endif; ?>
        <div class="row gy-4">
          <?php if (empty($produits)): ?>
            <p class="text-center">Aucun produit disponible pour le moment.</p>
          <?php endif; ?>
          <?php foreach ($produits as $p): ?>
            <div class="col-lg-4 col-md-6">
              <div class="sanad-card h-100 d-flex flex-column">
                <span class="badge rounded-pill <?= $p->estDon() ? 'text-bg-warning' : 'sanad-chip' ?> align-self-start"><?= e($p->getEtat()) ?></span>
                <h3 class="h5 mt-3"><?= e($p->getNom()) ?></h3>
                <p class="small"><?= e($p->getDescription()) ?></p>
                <?php if ($p->estDon()): ?>
                  <p class="fs-4 fw-bold mb-0">Gratuit</p>
                  <p class="text-muted small">Réservé aux membres</p>
                <?php else: ?>
                  <p class="fs-4 fw-bold mb-0"><?= e(rtrim(rtrim(number_format((float) $p->getPrix(), 2, ',', ' '), '0'), ',')) ?> DT</p>
                  <p class="text-muted small">ou <?= e(number_format($p->getPrixPoints(), 0, ',', ' ')) ?> points</p>
                <?php endif; ?>
                <?php if ((int) $p->getStock() > 0): ?>
                  <form class="mt-auto" action="index.php?page=panier-ajouter" method="post">
                    <?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $p->getId() ?>">
                    <button type="submit" class="btn sanad-btn w-100"><?= $p->estDon() ? 'Réserver ce don' : 'Ajouter au panier' ?></button>
                  </form>
                <?php else: ?>
                  <button type="button" class="btn btn-outline-secondary mt-auto" disabled>Épuisé</button>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
