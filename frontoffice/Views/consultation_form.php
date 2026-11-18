<?php
/**
 * Corriger sa consultation (rôle professionnel) · extension du module 2
 * Variables : $consultation, $patient, $ficheId, $old, $erreurs, $csrfToken, $flash
 */
$pageTitle   = 'Corriger une consultation';
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
              <h1>Corriger une consultation</h1>
              <p class="mb-0">Patient : <?= e($patient) ?>. Seul l’auteur d’une consultation peut la corriger.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container sanad-auth" style="max-width: 760px">
        <p><a href="index.php?page=dossier&amp;id=<?= (int) $ficheId ?>"><span aria-hidden="true">←</span> Retour au dossier</a></p>
        <div class="sanad-card">
          <form class="sanad-form" action="index.php?page=consultation-modifier&amp;id=<?= (int) $consultation->getId() ?>" method="post" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
                <div class="row">
                  <div class="form-group mt-3 col-md-6">
                    <label class="form-label" for="dateConsultation">Date *</label>
                    <input type="date" class="form-control<?= invalide($erreurs, 'dateConsultation') ?>" id="dateConsultation" name="dateConsultation" value="<?= e($old['dateConsultation'] ?? '') ?>" max="<?= date('Y-m-d') ?>" data-label="La date" data-required data-rule="date-passee">
                    <?= zoneErreur($erreurs, 'dateConsultation') ?>
                  </div>
                  <div class="form-group mt-3 col-md-6">
                    <label class="form-label" for="specialite">Spécialité *</label>
                    <input type="text" class="form-control<?= invalide($erreurs, 'specialite') ?>" id="specialite" name="specialite" value="<?= e($old['specialite'] ?? '') ?>" placeholder="Cardiologie" data-label="La spécialité" data-required data-minlength="2" data-maxlength="60">
                    <?= zoneErreur($erreurs, 'specialite') ?>
                  </div>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="motif">Motif de la consultation *</label>
                  <input type="text" class="form-control<?= invalide($erreurs, 'motif') ?>" id="motif" name="motif" value="<?= e($old['motif'] ?? '') ?>" placeholder="Ex. : douleur thoracique" data-label="Le motif" data-required data-minlength="3" data-maxlength="150">
                  <?= zoneErreur($erreurs, 'motif') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="diagnostic">Diagnostic / ce qui a été fait *</label>
                  <textarea class="form-control<?= invalide($erreurs, 'diagnostic') ?>" id="diagnostic" name="diagnostic" rows="3" placeholder="Examens réalisés, conclusion" data-label="Le diagnostic" data-required data-minlength="3" data-maxlength="2000"><?= e($old['diagnostic'] ?? '') ?></textarea>
                  <?= zoneErreur($erreurs, 'diagnostic') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="traitement">Traitement prescrit</label>
                  <textarea class="form-control<?= invalide($erreurs, 'traitement') ?>" id="traitement" name="traitement" rows="2" placeholder="Médicament, dose, durée" data-label="Le traitement" data-maxlength="2000"><?= e($old['traitement'] ?? '') ?></textarea>
                  <?= zoneErreur($erreurs, 'traitement') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="observations">Observations pour le prochain médecin</label>
                  <textarea class="form-control<?= invalide($erreurs, 'observations') ?>" id="observations" name="observations" rows="2" placeholder="Suivi à prévoir, précautions…" data-label="Le champ « Observations »" data-maxlength="2000"><?= e($old['observations'] ?? '') ?></textarea>
                  <?= zoneErreur($erreurs, 'observations') ?>
                </div>
                <div class="mt-4"><button type="submit">Enregistrer la correction</button></div>
              </form>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
