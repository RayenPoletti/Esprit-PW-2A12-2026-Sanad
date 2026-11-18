<?php
/**
 * Prendre rendez-vous · Module 2 · Aziz Kouki · Professionnel ↔ RendezVous
 * Variables : $pros, $creneaux, $old, $erreurs, $transmettre, $aPasseport, $mesRdv, $csrfToken, $flash
 */
$pageTitle   = 'Prendre rendez-vous';
$currentPage = 'rendezvous';
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
              <h1>Prendre rendez-vous</h1>
              <p class="mb-0">Votre passeport d’accessibilité peut être transmis au cabinet avec votre demande.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="appointment" class="appointment section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Nouveau rendez-vous</h2>
        <p>Le cabinet reçoit votre demande et la confirme.</p>
        <span class="badge rounded-pill sanad-module">Module 2 · Aziz Kouki · Professionnel ↔ RendezVous</span>
      </div>
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <div class="row gy-4">
          <div class="col-lg-7">
            <form action="index.php?page=rendezvous" method="post" class="php-email-form" data-validate novalidate>
              <?= champCsrf($csrfToken) ?>
              <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
              <div class="form-group">
                <label class="form-label" for="professionnelId">Professionnel *</label>
                <select class="form-select<?= invalide($erreurs, 'professionnelId') ?>" id="professionnelId" name="professionnelId" data-label="Le professionnel" data-required>
                  <option value="">Choisir…</option>
                  <?php foreach ($pros as $id => $libelle): ?><option value="<?= (int) $id ?>" <?= ($old['professionnelId'] ?? '') === (string) $id ? 'selected' : '' ?>><?= e($libelle) ?></option><?php endforeach; ?>
                </select>
                <?= zoneErreur($erreurs, 'professionnelId') ?>
              </div>
              <div class="row">
                <div class="col-md-6 form-group mt-3">
                  <label class="form-label" for="date">Date *</label>
                  <input type="date" class="form-control<?= invalide($erreurs, 'date') ?>" id="date" name="date" value="<?= e($old['date'] ?? '') ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" data-label="La date" data-required data-rule="date-future">
                  <?= zoneErreur($erreurs, 'date') ?>
                </div>
                <div class="col-md-6 form-group mt-3">
                  <label class="form-label" for="heure">Heure *</label>
                  <select class="form-select<?= invalide($erreurs, 'heure') ?>" id="heure" name="heure" data-label="L’heure" data-required>
                    <option value="">Choisir…</option>
                    <?php foreach ($creneaux as $c): ?><option <?= ($old['heure'] ?? '') === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
                  </select>
                  <?= zoneErreur($erreurs, 'heure') ?>
                </div>
              </div>
              <div class="form-group mt-3">
                <label class="form-label" for="motif">Motif *</label>
                <input type="text" class="form-control<?= invalide($erreurs, 'motif') ?>" id="motif" name="motif" value="<?= e($old['motif'] ?? '') ?>" placeholder="Ex. : contrôle annuel" data-label="Le motif" data-required data-minlength="3" data-maxlength="150">
                <?= zoneErreur($erreurs, 'motif') ?>
              </div>
              <?php if ($aPasseport): ?>
                <div class="form-check mt-3"><input class="form-check-input" type="checkbox" id="passeportTransmis" name="passeportTransmis"<?= $transmettre ? ' checked' : '' ?>><label class="form-check-label" for="passeportTransmis">Transmettre mon passeport d’accessibilité au cabinet</label></div>
              <?php else: ?>
                <p class="small text-muted mt-3"><a href="index.php?page=passeport">Créez votre passeport</a> pour que le cabinet prépare votre venue.</p>
              <?php endif; ?>
              <div class="text-center mt-4"><button type="submit">Envoyer la demande</button></div>
            </form>
          </div>
          <div class="col-lg-5">
            <div class="sanad-card">
              <h3 class="h5">Mes rendez-vous</h3>
              <?php if (empty($mesRdv)): ?><p class="text-muted mb-0">Aucun rendez-vous.</p><?php endif; ?>
              <ul class="list-unstyled mb-0">
                <?php foreach ($mesRdv as $m): $r = $m['rdv']; ?>
                  <li class="py-2 border-bottom">
                    <div class="d-flex justify-content-between gap-2">
                      <strong><?= e(dateFr($r->getDateHeure(), true)) ?></strong>
                      <span class="badge rounded-pill align-self-start <?= ['confirme' => 'text-bg-success', 'annule' => 'text-bg-secondary'][$r->getStatut()] ?? 'text-bg-warning' ?>"><?= e($r->getLibelleStatut()) ?></span>
                    </div>
                    <small><?= e($m['pro']) ?><br><?= e($m['lieu']) ?><br>Motif : <?= e($r->getMotif()) ?><?= $r->isPasseportTransmis() ? ' · passeport transmis' : '' ?></small>
                    <?php if ($r->getStatut() !== 'annule' && !$r->estPasse()): ?>
                      <form class="mt-1" action="index.php?page=rendezvous-annuler" method="post" data-confirm="Annuler ce rendez-vous ?">
                        <?= champCsrf($csrfToken) ?><input type="hidden" name="id" value="<?= (int) $r->getId() ?>">
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0">Annuler</button>
                      </form>
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
