<?php
/**
 * Dossier d'un patient (rôle professionnel) · extension du module 2
 * Variables : $fiche (FicheMedicale), $patient, $email, $consultations, $old, $erreurs, $moi, $csrfToken, $flash
 * Accessible uniquement si le patient a autorisé le partage (vérifié par le contrôleur à chaque requête).
 */
$pageTitle   = 'Dossier patient';
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
              <h1>Dossier de <?= e($patient) ?></h1>
              <p class="mb-0">Antécédents partagés par le patient. Ajoutez ce que vous avez fait pour que le prochain médecin le sache.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <p><a href="index.php?page=patients"><span aria-hidden="true">←</span> Retour à mes patients</a></p>
        <div class="row gy-4">
          <div class="col-lg-5">
            <div class="sanad-card mb-4">
              <h2 class="h5">Informations de santé</h2>
              <dl class="row small mb-0">
                <dt class="col-5">Patient</dt><dd class="col-7"><?= e($patient) ?><br><span class="text-muted"><?= e($email) ?></span></dd>
                <dt class="col-5">Groupe sanguin</dt><dd class="col-7"><?= e($fiche->getGroupeSanguin() ?: 'Non renseigné') ?></dd>
                <dt class="col-5">Allergies</dt><dd class="col-7"><?= $fiche->getAllergies() !== '' ? '<strong class="text-danger">' . nl2br(e($fiche->getAllergies())) . '</strong>' : 'Aucune déclarée' ?></dd>
                <dt class="col-5">Maladies chroniques</dt><dd class="col-7"><?= nl2br(e($fiche->getMaladiesChroniques() ?: '—')) ?></dd>
                <dt class="col-5">Traitements en cours</dt><dd class="col-7"><?= nl2br(e($fiche->getTraitementsEnCours() ?: '—')) ?></dd>
              </dl>
            </div>
            <div class="sanad-card">
              <h2 class="h5">Ajouter une consultation</h2>
              <form class="sanad-form" action="index.php?page=dossier&amp;id=<?= (int) $fiche->getId() ?>" method="post" data-validate novalidate>
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
                <div class="mt-4"><button type="submit">Ajouter au dossier</button></div>
              </form>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="sanad-card">
              <h2 class="h5 mb-3">Antécédents (<?= count($consultations) ?>)</h2>
              <?php if (empty($consultations)): ?>
                <p class="text-muted mb-0">Aucune consultation enregistrée pour le moment.</p>
              <?php endif; ?>
              <ol class="sanad-timeline">
                <?php foreach ($consultations as $c): ?>
                  <li>
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                      <h3 class="h6 mb-1"><?= e($c->getMotif()) ?></h3>
                      <span class="small text-muted"><?= e(date('d/m/Y', strtotime($c->getDateConsultation()))) ?></span>
                    </div>
                    <p class="small text-muted mb-2"><?= e($c->getAuteur()) ?> · <?= e($c->getSpecialite()) ?></p>
                    <dl class="row small mb-0">
                      <dt class="col-sm-3">Diagnostic</dt><dd class="col-sm-9"><?= nl2br(e($c->getDiagnostic())) ?></dd>
                      <?php if ($c->getTraitement() !== ''): ?><dt class="col-sm-3">Traitement</dt><dd class="col-sm-9"><?= nl2br(e($c->getTraitement())) ?></dd><?php endif; ?>
                      <?php if ($c->getObservations() !== ''): ?><dt class="col-sm-3">Observations</dt><dd class="col-sm-9"><?= nl2br(e($c->getObservations())) ?></dd><?php endif; ?>
                    </dl>
                    <?php if ($c->estEcritePar($moi)): ?><a class="btn btn-sm btn-outline-secondary mt-2" href="index.php?page=consultation-modifier&amp;id=<?= (int) $c->getId() ?>">Corriger ma consultation</a><?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ol>
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
