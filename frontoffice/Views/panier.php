<?php
/**
 * Mon panier et mes commandes · Module 5 · Karim Bousetta · Commande ↔ Produit (LigneCommande)
 * Variables : $lignes (['produit', 'quantite', 'sousTotal']), $total, $totalPoints, $points, $commandes, $csrfToken, $flash
 */
$pageTitle   = 'Mon panier';
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
              <h1>Mon panier</h1>
              <p class="mb-0">Matériel reconditionné ou donné, payable en dinars ou en points solidaires (<?= Produit::POINTS_PAR_DINAR ?> points = 1 DT).</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <div class="row gy-4">
          <div class="col-lg-7">
            <div class="sanad-card">
              <h2 class="h5 mb-3">Panier</h2>
              <?php if (empty($lignes)): ?>
                <p class="text-muted">Votre panier est vide. <a href="index.php?page=boutique">Voir la boutique</a></p>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table align-middle">
                    <thead><tr><th scope="col">Produit</th><th scope="col">Prix</th><th scope="col">Quantité</th><th scope="col">Sous-total</th></tr></thead>
                    <tbody>
                      <?php foreach ($lignes as $l): $p = $l['produit']; ?>
                        <tr>
                          <td><?= e($p->getNom()) ?><br><small class="text-muted"><?= e($p->getEtat()) ?></small></td>
                          <td><?= $p->estDon() ? 'Gratuit' : e(nombre($p->getPrix())) . ' DT' ?></td>
                          <td>
                            <form class="d-flex gap-1" action="index.php?page=panier-modifier" method="post">
                              <?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $p->getId() ?>">
                              <label class="visually-hidden" for="q<?= (int) $p->getId() ?>">Quantité</label>
                              <input class="form-control form-control-sm" style="width: 70px" type="number" id="q<?= (int) $p->getId() ?>" name="quantite" value="<?= (int) $l['quantite'] ?>" min="0" max="<?= $p->estDon() ? 1 : (int) $p->getStock() ?>">
                              <button class="btn btn-sm btn-outline-secondary" type="submit">OK</button>
                            </form>
                          </td>
                          <td><?= e(nombre($l['sousTotal'])) ?> DT</td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <p class="fs-5"><strong>Total : <?= e(nombre($total)) ?> DT</strong> <span class="text-muted">ou <?= (int) $totalPoints ?> points</span></p>
                <form action="index.php?page=panier-commander" method="post" data-confirm="Confirmer la commande ?">
                  <?= champCsrf($csrfToken) ?>
                  <fieldset class="mb-3">
                    <legend class="fs-6 fw-semibold">Mode de paiement</legend>
                    <div class="form-check"><input class="form-check-input" type="radio" name="paiement" id="pDinars" value="dinars" checked><label class="form-check-label" for="pDinars">En dinars, au retrait du matériel</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="paiement" id="pPoints" value="points"<?= $points < $totalPoints ? ' disabled' : '' ?>><label class="form-check-label" for="pPoints">Avec mes points solidaires (<?= (int) $points ?> disponibles<?= $points < $totalPoints ? ', insuffisant' : '' ?>)</label></div>
                  </fieldset>
                  <button class="btn sanad-btn" type="submit">Valider la commande</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="sanad-card">
              <h2 class="h5">Mes commandes</h2>
              <?php if (empty($commandes)): ?><p class="text-muted mb-0">Aucune commande.</p><?php endif; ?>
              <ul class="list-unstyled mb-0">
                <?php foreach ($commandes as $cl): $c = $cl['commande']; ?>
                  <li class="py-2 border-bottom">
                    <div class="d-flex justify-content-between gap-2">
                      <strong>N° <?= (int) $c->getId() ?> · <?= e(dateFr($c->getDateCommande())) ?></strong>
                      <span class="badge rounded-pill align-self-start <?= ['livree' => 'text-bg-success', 'annulee' => 'text-bg-secondary', 'preparation' => 'text-bg-info'][$c->getStatut()] ?? 'text-bg-warning' ?>"><?= e($c->getLibelleStatut()) ?></span>
                    </div>
                    <small>
                      <?php foreach ($cl['lignes'] as $lc): ?><?= (int) $lc['quantite'] ?> × <?= e($lc['nom']) ?><br><?php endforeach; ?>
                      Total : <?= e(nombre($c->getMontantTotal())) ?> DT<?= $c->estPayeeEnPoints() ? ' · payé avec ' . (int) $c->getPointsUtilises() . ' points' : '' ?>
                    </small>
                    <?php if ($c->getStatut() === 'en_attente'): ?>
                      <form action="index.php?page=commande-annuler" method="post" data-confirm="Annuler cette commande ?"><?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $c->getId() ?>"><button class="btn btn-link btn-sm p-0 text-danger" type="submit">Annuler</button></form>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
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
