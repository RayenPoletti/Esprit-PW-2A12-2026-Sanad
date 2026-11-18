<?php
/**
 * Auditer un lieu (ambassadeurs) · Module 1 · Rayen Poletti · Lieu ↔ Audit
 * Variables : $lieux (id => libellé), $old, $coches, $erreurs, $mesAudits, $points, $csrfToken, $flash
 */
$pageTitle   = 'Auditer un lieu';
$currentPage = 'audits';
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
              <h1>Auditer un lieu</h1>
              <p class="mb-0">Vous êtes ambassadeur : vérifiez sur place l’accessibilité d’un cabinet. Chaque audit validé rapporte <?= Audit::POINTS_AUDIT ?> points solidaires.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="appointment" class="appointment section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Nouvel audit</h2>
        <p>Cochez uniquement ce que vous avez constaté vous-même.</p>
        <span class="badge rounded-pill sanad-module">Module 1 · Rayen Poletti · Lieu ↔ Audit</span>
      </div>
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <div class="row gy-4">
          <div class="col-lg-7">
            <form action="index.php?page=audits" method="post" class="php-email-form" data-validate novalidate>
              <?= champCsrf($csrfToken) ?>
              <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
              <div class="row">
                <div class="col-md-7 form-group">
                  <label class="form-label" for="lieuId">Lieu audité *</label>
                  <select class="form-select<?= invalide($erreurs, 'lieuId') ?>" id="lieuId" name="lieuId" data-label="Le lieu audité" data-required>
                    <option value="">Choisir un cabinet…</option>
                    <?php foreach ($lieux as $id => $libelle): ?>
                      <option value="<?= e($id) ?>" <?= ($old['lieuId'] ?? '') === (string) $id ? 'selected' : '' ?>><?= e($libelle) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?= zoneErreur($erreurs, 'lieuId') ?>
                </div>
                <div class="col-md-5 form-group">
                  <label class="form-label" for="dateAudit">Date de la visite *</label>
                  <input type="date" class="form-control<?= invalide($erreurs, 'dateAudit') ?>" id="dateAudit" name="dateAudit" value="<?= e($old['dateAudit'] ?? '') ?>" max="<?= date('Y-m-d') ?>" data-label="La date de la visite" data-required data-rule="date-passee">
                  <?= zoneErreur($erreurs, 'dateAudit') ?>
                </div>
              </div>
              <fieldset class="row gy-2 mt-2">
                <legend class="fs-6 fw-semibold mb-2">Ce que j’ai constaté sur place</legend>
                <?php foreach (Audit::CRITERES as $cle => $libelle): ?>
                  <div class="col-md-6"><div class="form-check">
                    <input class="form-check-input" type="checkbox" id="c_<?= e($cle) ?>" name="criteres[]" value="<?= e($cle) ?>"<?= in_array($cle, $coches, true) ? ' checked' : '' ?>>
                    <label class="form-check-label" for="c_<?= e($cle) ?>"><?= e($libelle) ?></label>
                  </div></div>
                <?php endforeach; ?>
              </fieldset>
              <div class="form-group mt-3">
                <label class="form-label" for="commentaire">Commentaire *</label>
                <textarea class="form-control<?= invalide($erreurs, 'commentaire') ?>" id="commentaire" name="commentaire" rows="4" placeholder="Largeur de la porte, nombre de marches, accueil…" data-label="Le commentaire" data-required data-minlength="10" data-maxlength="1000"><?= e($old['commentaire'] ?? '') ?></textarea>
                <?= zoneErreur($erreurs, 'commentaire') ?>
              </div>
              <div class="text-center mt-4"><button type="submit">Envoyer l’audit</button></div>
            </form>
          </div>
          <div class="col-lg-5">
            <div class="sanad-card">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="h5 mb-0">Mes audits</h3>
                <span class="badge rounded-pill sanad-chip"><?= (int) $points ?> points</span>
              </div>
              <?php if (empty($mesAudits)): ?><p class="text-muted mb-0">Vous n’avez pas encore envoyé d’audit.</p><?php endif; ?>
              <ul class="list-unstyled mb-0">
                <?php foreach ($mesAudits as $m): $a = $m['audit']; ?>
                  <li class="d-flex justify-content-between gap-2 py-2 border-bottom">
                    <span><strong><?= e($m['lieu']) ?></strong><br><small><?= e(dateFr($a->getDateAudit())) ?> · <?= (int) $a->nbCriteresOK() ?>/<?= count(Audit::CRITERES) ?> critères</small></span>
                    <span class="badge rounded-pill align-self-center <?= ['valide' => 'text-bg-success', 'refuse' => 'text-bg-danger'][$a->getStatut()] ?? 'text-bg-warning' ?>"><?= e($a->getLibelleStatut()) ?><?= $a->getStatut() === 'valide' ? ' · +' . Audit::POINTS_AUDIT . ' pts' : '' ?></span>
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
