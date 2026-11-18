<?php
/**
 * Mes patients (rôle professionnel) · extension du module 2
 * Variables : $email (recherche), $resultat (?array), $message, $suivis (patients déjà suivis), $flash
 */
$pageTitle   = 'Mes patients';
$currentPage = 'patients';
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
              <h1>Mes patients</h1>
              <p class="mb-0">Retrouvez la fiche médicale d’un patient qui vous a autorisé à la consulter.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container" style="max-width: 860px">
        <?= bandeauFlash($flash) ?>
        <div class="sanad-card mb-4">
          <h2 class="h5">Rechercher un patient</h2>
          <form class="row g-2 align-items-start" action="index.php" method="get" data-validate novalidate role="search">
            <input type="hidden" name="page" value="patients">
            <div class="col-md-8">
              <label class="visually-hidden" for="email">E-mail du patient</label>
              <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" placeholder="E-mail du patient" data-label="L’e-mail" data-required data-rule="email">
              <?= zoneErreur([], 'email') ?>
            </div>
            <div class="col-md-4"><button class="btn sanad-btn w-100" type="submit">Rechercher</button></div>
          </form>
          <?php if ($resultat): ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-top pt-3 mt-2">
              <div><strong><?= e($resultat['prenom'] . ' ' . $resultat['nom']) ?></strong><br><span class="small text-muted"><?= e($resultat['email']) ?></span></div>
              <a class="btn sanad-btn" href="index.php?page=dossier&amp;id=<?= (int) $resultat['ficheId'] ?>">Ouvrir la fiche</a>
            </div>
          <?php elseif ($message): ?>
            <div class="alert alert-info mt-2 mb-0"><?= e($message) ?></div>
          <?php endif; ?>
        </div>

        <div class="sanad-card">
          <h2 class="h5">Patients que vous avez déjà soignés</h2>
          <?php if (empty($suivis)): ?>
            <p class="text-muted mb-0">Aucun pour le moment.</p>
          <?php else: ?>
            <ul class="list-unstyled mb-0">
              <?php foreach ($suivis as $s): ?>
                <li class="d-flex flex-wrap justify-content-between align-items-center gap-2 py-2 border-bottom">
                  <span><strong><?= e($s['prenom'] . ' ' . $s['nom']) ?></strong> <span class="small text-muted">· dernière consultation le <?= e(date('d/m/Y', strtotime($s['derniere']))) ?></span></span>
                  <a class="btn btn-sm btn-outline-secondary" href="index.php?page=dossier&amp;id=<?= (int) $s['ficheId'] ?>">Voir la fiche</a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
