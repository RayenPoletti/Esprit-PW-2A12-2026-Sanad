<?php
/**
 * Détail d'un programme · Module 3 · Rayen Marzougi · Programme ◆— Séance, Inscription
 * Variables : $programme (Programme), $seances (Seance[]), $inscription (?array), $csrfToken, $flash
 */
$pageTitle   = 'Programme';
$currentPage = 'programmes';
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
              <h1><?= e($programme->getTitre()) ?></h1>
              <p class="mb-0"><?= e($programme->getNiveau()) ?> · <?= e(implode(' · ', $programme->getListeFormats())) ?></p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container" style="max-width: 900px">
        <?= bandeauFlash($flash) ?>
        <p><a href="index.php?page=programmes"><span aria-hidden="true">←</span> Tous les programmes</a></p>
        <div class="sanad-card mb-4">
          <p><?= nl2br(e($programme->getDescription())) ?></p>
          <?php $total = count($seances); $fait = $inscription ? min((int) $inscription['progression'], $total) : 0; ?>
          <?php if (!AuthController::isAuthenticated()): ?>
            <a class="btn sanad-btn" href="index.php?page=connexion">Se connecter pour s’inscrire</a>
          <?php elseif (!$inscription): ?>
            <form action="index.php?page=programme-inscrire" method="post"><?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $programme->getId() ?>"><button class="btn sanad-btn" type="submit">S’inscrire à ce programme</button></form>
          <?php else: ?>
            <p class="mb-1"><strong>Ma progression : <?= $fait ?> / <?= $total ?> séance(s)</strong></p>
            <div class="progress mb-3" role="progressbar" aria-label="Progression" aria-valuenow="<?= $fait ?>" aria-valuemin="0" aria-valuemax="<?= $total ?>"><div class="progress-bar" style="width: <?= $total ? round($fait * 100 / $total) : 0 ?>%; background: var(--accent-color)"></div></div>
            <div class="d-flex flex-wrap gap-2">
              <?php if ($fait < $total): ?>
                <form action="index.php?page=programme-avancer" method="post"><?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $programme->getId() ?>"><button class="btn sanad-btn" type="submit">J’ai terminé la séance <?= $fait + 1 ?></button></form>
              <?php else: ?><span class="badge rounded-pill text-bg-success align-self-center">Programme terminé, bravo !</span><?php endif; ?>
              <form action="index.php?page=programme-quitter" method="post" data-confirm="Quitter ce programme ? Votre progression sera perdue."><?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $programme->getId() ?>"><button class="btn btn-outline-secondary" type="submit">Quitter le programme</button></form>
            </div>
          <?php endif; ?>
        </div>
        <h2 class="h5 mb-3">Séances</h2>
        <ol class="sanad-timeline">
          <?php foreach ($seances as $i => $s): ?>
            <li>
              <div class="d-flex flex-wrap justify-content-between gap-2">
                <h3 class="h6 mb-1"><?= e($s->getTitre()) ?> <?= ($inscription && $i < $fait) ? '<span class="badge rounded-pill sanad-chip">Faite</span>' : '' ?></h3>
                <span class="small text-muted"><?= (int) $s->getDureeMin() ?> min</span>
              </div>
              <div class="d-flex flex-wrap gap-3 small">
                <?php if ($s->getUrlVideo()): ?><a href="<?= e($s->getUrlVideo()) ?>" target="_blank" rel="noopener noreferrer">Regarder la vidéo (sous-titrée)</a><?php endif; ?>
                <?php if ($s->getUrlAudio()): ?><a href="<?= e($s->getUrlAudio()) ?>" target="_blank" rel="noopener noreferrer">Écouter l’audio</a><?php endif; ?>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
