<?php
/**
 * Mon passeport d'accessibilité · Module commun (Utilisateur 1 — 0..1 Passeport)
 * Variables : $passeport (?Passeport), $erreurs, $csrfToken, $flash
 */
$coche = fn(string $b): string => ($passeport && $passeport->getBesoin($b)) ? ' checked' : '';
$pageTitle   = 'Mon passeport';
$currentPage = 'passeport';
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
              <h1>Mon passeport d’accessibilité</h1>
              <p class="mb-0">Déclarez vos besoins une seule fois. Avec votre accord, ils sont transmis au cabinet à chaque rendez-vous.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="appointment" class="appointment section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Mes besoins</h2>
        <p><?= $passeport ? 'Dernière mise à jour : ' . e(date('d/m/Y', strtotime($passeport->getDateMiseAJour() ?: 'now'))) : 'Vous n’avez pas encore de passeport : remplissez-le ci-dessous.' ?></p>
      </div>
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
        <div class="sanad-card">
          <form class="sanad-form" action="index.php?page=passeport" method="post" data-validate novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <fieldset class="row gy-2">
              <legend class="fs-6 fw-semibold mb-2">Je déclare que…</legend>
              <?php foreach (Passeport::BESOINS as $cle => $libelle): ?>
                <div class="col-md-6"><div class="form-check">
                  <input class="form-check-input" type="checkbox" id="<?= e($cle) ?>" name="besoins[]" value="<?= e($cle) ?>"<?= $coche($cle) ?>>
                  <label class="form-check-label" for="<?= e($cle) ?>"><?= e($libelle) ?></label>
                </div></div>
              <?php endforeach; ?>
            </fieldset>
            <?= zoneErreur($erreurs, 'besoins') ?>
            <div class="form-group mt-3">
              <label for="precisions" class="form-label">Précisions pour le cabinet</label>
              <textarea class="form-control<?= invalide($erreurs, 'precisions') ?>" id="precisions" name="precisions" rows="4" data-label="Le champ « Précisions »" data-maxlength="500" placeholder="Ex. : merci de me guider jusqu’à la salle d’attente."><?= e($passeport ? $passeport->getPrecisions() : '') ?></textarea>
              <?= zoneErreur($erreurs, 'precisions') ?>
            </div>
            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="partage" name="partageAutorise"<?= ($passeport && $passeport->isPartageAutorise()) ? ' checked' : '' ?>>
              <label class="form-check-label" for="partage"><strong>J’accepte que mon passeport soit transmis</strong> au cabinet quand je prends rendez-vous.</label>
            </div>
            <div class="text-center mt-4"><button type="submit"><?= $passeport ? 'Mettre à jour mon passeport' : 'Créer mon passeport' ?></button></div>
          </form>
        </div>

        <?php if ($passeport && $passeport->getId()): ?>
          <form class="text-center mt-4" action="index.php?page=passeport-supprimer" method="post" data-confirm="Supprimer votre passeport d’accessibilité ? Les cabinets ne recevront plus vos besoins.">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer mon passeport</button>
          </form>
        <?php endif; ?>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
