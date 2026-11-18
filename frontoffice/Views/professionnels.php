<?php
/**
 * Trouver un professionnel · Module 2 · Aziz Kouki · Professionnel ↔ RendezVous
 * Variables : $resultats (['pro', 'lieu', 'score', 'nbAudits', 'criteres']), $specialite, $ville, $villes, $aPasseport, $appliquer, $exiges
 */
$pageTitle   = 'Professionnels';
$currentPage = 'professionnels';
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
              <h1>Trouver un lieu de soin vérifié</h1>
              <p class="mb-0">Les critères viennent des audits des ambassadeurs, pas des déclarations des cabinets.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="doctors" class="doctors section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Professionnels</h2>
        <p>Filtrez selon vos besoins : seuls les cabinets qui y répondent s’affichent.</p>
        <span class="badge rounded-pill sanad-module">Module 2 · Aziz Kouki · Professionnel ↔ RendezVous</span>
      </div>
      <div class="container">
        <form class="row g-2 mb-3 sanad-filters" action="index.php" method="get" role="search">
          <input type="hidden" name="page" value="professionnels">
          <div class="col-md-4"><label class="visually-hidden" for="specialite">Spécialité</label>
            <select class="form-select" id="specialite" name="specialite"><option value="">Toutes les spécialités</option>
              <?php foreach (Professionnel::SPECIALITES as $s): ?><option <?= $s === $specialite ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-md-3"><label class="visually-hidden" for="ville">Ville</label>
            <select class="form-select" id="ville" name="ville"><option value="">Toutes les villes</option>
              <?php foreach ($villes as $v): ?><option <?= $v === $ville ? 'selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?>
            </select></div>
          <div class="col-md-3 d-flex align-items-center">
            <?php if ($aPasseport): ?>
              <div class="form-check"><input class="form-check-input" type="checkbox" id="monPasseport" name="passeport" value="1"<?= $appliquer ? ' checked' : '' ?>><label class="form-check-label" for="monPasseport">Appliquer mon passeport</label></div>
            <?php else: ?>
              <small class="text-muted"><a href="index.php?page=passeport">Créez votre passeport</a> pour filtrer selon vos besoins.</small>
            <?php endif; ?>
          </div>
          <div class="col-md-2"><button class="btn sanad-btn w-100" type="submit">Rechercher</button></div>
        </form>
        <?php if ($appliquer && $exiges): ?>
          <p class="small text-muted">Filtre passeport : <?= e(implode(', ', array_map(fn($c) => Audit::CRITERES[$c], $exiges))) ?>.</p>
        <?php endif; ?>
        <p class="small"><?= count($resultats) ?> résultat(s)</p>
        <div class="row gy-4">
          <?php if (empty($resultats)): ?>
            <p class="text-center text-muted">Aucun cabinet ne correspond à ces critères pour le moment. Les ambassadeurs auditent de nouveaux lieux chaque semaine.</p>
          <?php endif; ?>
          <?php foreach ($resultats as $r): $p = $r['pro']; ?>
            <div class="col-lg-6">
              <div class="team-member d-flex align-items-start">
                <div class="pic sanad-avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr((string) $p->getPrenom(), 0, 1) . mb_substr((string) $p->getNom(), 0, 1))) ?></div>
                <div class="member-info">
                  <h4><?= e($p->getNomComplet()) ?></h4>
                  <span><?= e($p->getSpecialite()) ?> · <?= e($r['lieu']) ?></span>
                  <p><?php if ($r['score'] !== null): ?><strong>Score d’accessibilité : <?= e(nombre($r['score'], 1)) ?> / 5</strong> · vérifié par <?= (int) $r['nbAudits'] ?> audit(s)<?php else: ?><em>Pas encore audité</em><?php endif; ?></p>
                  <div class="d-flex flex-wrap gap-1 mt-2">
                    <?php foreach ($r['criteres'] as $c): ?><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> <?= e(Audit::CRITERES[$c]) ?></span><?php endforeach; ?>
                  </div>
                  <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="index.php?page=rendezvous&amp;pro=<?= (int) $p->getId() ?>" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                    <?php if (AuthController::hasRole('ambassadeur', 'admin')): ?><a href="index.php?page=audits&amp;lieu=<?= (int) $p->getLieuId() ?>" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a><?php endif; ?>
                  </div>
                </div>
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
