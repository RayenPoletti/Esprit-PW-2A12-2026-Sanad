<?php
/**
 * Ma fiche médicale (patient) · extension du module 2
 * Variables : $fiche (?FicheMedicale), $consultations (Consultation[]), $erreurs, $csrfToken, $flash
 * Le patient remplit ses informations et lit ses antécédents ; seuls les médecins écrivent les consultations.
 */
$pageTitle   = 'Ma fiche médicale';
$currentPage = 'fiche';
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
              <h1>Ma fiche médicale</h1>
              <p class="mb-0">Vos informations de santé et l’historique de vos consultations, pour que chaque médecin connaisse vos antécédents.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
        <div class="row gy-4">
          <div class="col-lg-5">
            <div class="sanad-card">
              <h2 class="h5">Mes informations de santé</h2>
              <form class="sanad-form" action="index.php?page=fiche" method="post" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <div class="form-group mt-3">
                  <label class="form-label" for="groupeSanguin">Groupe sanguin</label>
                  <select class="form-select<?= invalide($erreurs, 'groupeSanguin') ?>" id="groupeSanguin" name="groupeSanguin" data-label="Le groupe sanguin">
                    <option value="">Je ne sais pas</option>
                    <?php foreach (FicheMedicale::GROUPES_SANGUINS as $g): ?>
                      <option value="<?= e($g) ?>" <?= ($fiche && $fiche->getGroupeSanguin() === $g) ? 'selected' : '' ?>><?= e($g) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?= zoneErreur($erreurs, 'groupeSanguin') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="allergies">Allergies</label>
                  <textarea class="form-control<?= invalide($erreurs, 'allergies') ?>" id="allergies" name="allergies" rows="2" placeholder="Ex. : pénicilline, arachides" data-label="Le champ « Allergies »" data-maxlength="1000"><?= e($fiche ? $fiche->getAllergies() : '') ?></textarea>
                  <?= zoneErreur($erreurs, 'allergies') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="maladiesChroniques">Maladies chroniques</label>
                  <textarea class="form-control<?= invalide($erreurs, 'maladiesChroniques') ?>" id="maladiesChroniques" name="maladiesChroniques" rows="2" placeholder="Ex. : diabète de type 2" data-label="Le champ « Maladies chroniques »" data-maxlength="1000"><?= e($fiche ? $fiche->getMaladiesChroniques() : '') ?></textarea>
                  <?= zoneErreur($erreurs, 'maladiesChroniques') ?>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label" for="traitementsEnCours">Traitements en cours</label>
                  <textarea class="form-control<?= invalide($erreurs, 'traitementsEnCours') ?>" id="traitementsEnCours" name="traitementsEnCours" rows="2" placeholder="Médicament, dose, fréquence" data-label="Le champ « Traitements en cours »" data-maxlength="1000"><?= e($fiche ? $fiche->getTraitementsEnCours() : '') ?></textarea>
                  <?= zoneErreur($erreurs, 'traitementsEnCours') ?>
                </div>
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" id="partageAutorise" name="partageAutorise"<?= ($fiche && $fiche->isPartageAutorise()) ? ' checked' : '' ?>>
                  <label class="form-check-label" for="partageAutorise"><strong>J’autorise les professionnels de santé de Sanad</strong> à consulter ma fiche et mes antécédents, et à y ajouter leurs consultations.</label>
                </div>
                <p class="small text-muted mt-2 mb-0">Vous pouvez retirer cette autorisation à tout moment. L’équipe Sanad (administrateurs) n’a pas accès à votre fiche.</p>
                <div class="mt-4"><button type="submit"><?= $fiche && $fiche->getId() ? 'Enregistrer' : 'Créer ma fiche' ?></button></div>
              </form>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="sanad-card">
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0">Mes antécédents</h2>
                <?php if ($fiche && $fiche->getId()): ?>
                  <span class="badge rounded-pill <?= $fiche->isPartageAutorise() ? 'sanad-chip' : 'text-bg-warning' ?>"><?= $fiche->isPartageAutorise() ? 'Partagée avec les professionnels' : 'Non partagée' ?></span>
                <?php endif; ?>
              </div>
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
