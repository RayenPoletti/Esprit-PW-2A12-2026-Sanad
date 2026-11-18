<?php
/**
 * Programmes bien-être · Module 3 · Rayen Marzougi · Programme ◆— Séance
 * Variables : $programmes (['programme' => Programme, 'nbSeances', 'duree', 'inscrit']), $flash
 */
$pageTitle   = 'Programmes bien-être';
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
              <h1>Programmes bien-être</h1>
              <p class="mb-0">Des séances courtes, en vidéo sous-titrée, en audio ou en texte facile à lire.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="services" class="services section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Programmes adaptés à chacun</h2>
        <p>Choisissez un programme et avancez à votre rythme.</p>
        <span class="badge rounded-pill sanad-module">Module 3 · Rayen Marzougi · Programme ↔ Séance</span>
      </div>
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <div class="row gy-4">
          <?php if (empty($programmes)): ?><p class="text-center text-muted">Aucun programme pour le moment.</p><?php endif; ?>
          <?php foreach ($programmes as $l): $p = $l['programme']; ?>
            <div class="col-lg-4 col-md-6">
              <div class="service-item position-relative">
                <div class="icon"><i class="bi bi-heart-pulse"></i></div>
                <a href="index.php?page=programme&amp;id=<?= (int) $p->getId() ?>" class="stretched-link"><h3><?= e($p->getTitre()) ?></h3></a>
                <p class="mb-2"><strong><?= e($p->getNiveau()) ?> · <?= (int) $l['nbSeances'] ?> séance(s) · <?= (int) $l['duree'] ?> min</strong></p>
                <p><?= e($p->getDescription()) ?></p>
                <p class="mt-2"><span class="badge rounded-pill sanad-chip"><?= e(implode(' · ', $p->getListeFormats())) ?></span></p>
                <?php if ($l['inscrit']): ?><p class="mt-2 mb-0"><span class="badge rounded-pill text-bg-success">Inscrit(e)</span></p><?php endif; ?>
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
