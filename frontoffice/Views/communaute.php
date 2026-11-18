<?php
/**
 * Communauté d'entraide · Module 4 · Houssem Hafsi · Publication ◆— Commentaire
 * Variables : $publications (['publication', 'auteur', 'initiale', 'commentaires']), $erreurs, $old, $moi, $csrfToken, $flash
 */
$pageTitle   = 'Communauté';
$currentPage = 'communaute';
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
              <h1>Communauté d’entraide</h1>
              <p class="mb-0">Partagez une expérience, posez une question, aidez quelqu’un. Les messages sont modérés.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="community" class="section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Entraide et témoignages</h2>
        <p>Patients et aidants s’entraident.</p>
        <span class="badge rounded-pill sanad-module">Module 4 · Houssem Hafsi · Publication ↔ Commentaire</span>
      </div>
      <div class="container" style="max-width: 820px">
        <?= bandeauFlash($flash) ?>
        <?php if ($moi): ?>
          <form class="sanad-card mb-4" action="index.php?page=communaute-publier" method="post" data-validate novalidate>
            <?= champCsrf($csrfToken) ?>
            <label for="contenu" class="form-label fw-semibold">Partager un témoignage ou poser une question *</label>
            <textarea class="form-control<?= invalide($erreurs, 'contenu') ?>" id="contenu" name="contenu" rows="3" data-label="Votre message" data-required data-minlength="10" data-maxlength="2000" placeholder="Par exemple : quel kinésithérapeute accessible recommandez-vous à Bizerte ?"><?= e($old['contenu'] ?? '') ?></textarea>
            <?= zoneErreur($erreurs, 'contenu') ?>
            <p class="small text-muted mb-0">Ne publiez pas d’informations médicales personnelles ni de coordonnées.</p>
            <button class="btn sanad-btn mt-3" type="submit">Publier</button>
          </form>
        <?php else: ?>
          <div class="sanad-card mb-4"><a href="index.php?page=connexion">Connectez-vous</a> pour publier ou commenter.</div>
        <?php endif; ?>

        <?php if (empty($publications)): ?><p class="text-center text-muted">Aucun message pour le moment. Lancez la discussion !</p><?php endif; ?>
        <?php foreach ($publications as $l): $p = $l['publication']; $pid = (int) $p->getId(); ?>
          <article class="sanad-card mb-4" id="pub-<?= $pid ?>">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="sanad-avatar sm" aria-hidden="true"><?= e($l['initiale']) ?></div>
              <div><strong><?= e($l['auteur']) ?></strong><br><small class="text-muted"><?= e(dateFr($p->getDatePublication(), true)) ?></small></div>
              <?php if ($p->getStatut() === 'signale'): ?><span class="badge rounded-pill text-bg-warning ms-auto">En cours de modération</span><?php endif; ?>
            </div>
            <p><?= nl2br(e($p->getContenu())) ?></p>
            <?php foreach ($l['commentaires'] as $cl): $c = $cl['commentaire']; ?>
              <div class="sanad-comment d-flex flex-wrap justify-content-between gap-2">
                <span><strong><?= e($cl['auteur']) ?></strong> · <?= e($c->getContenu()) ?></span>
                <?php if ($moi && (int) $c->getUtilisateurId() === $moi): ?>
                  <form action="index.php?page=communaute-supprimer" method="post" data-confirm="Supprimer votre commentaire ?"><?= champCsrf($csrfToken) ?><input type="hidden" name="type" value="commentaire"><input type="hidden" name="id" value="<?= (int) $c->getId() ?>"><button class="btn btn-link btn-sm p-0 text-danger" type="submit">Supprimer</button></form>
                <?php elseif ($moi && $c->getStatut() === 'publie'): ?>
                  <form action="index.php?page=communaute-signaler" method="post" data-confirm="Signaler ce commentaire à la modération ?"><?= champCsrf($csrfToken) ?><input type="hidden" name="type" value="commentaire"><input type="hidden" name="id" value="<?= (int) $c->getId() ?>"><button class="btn btn-link btn-sm p-0" type="submit">Signaler</button></form>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
            <?php if ($moi): ?>
              <form class="mt-3" action="index.php?page=communaute-commenter" method="post" data-validate novalidate>
                <?= champCsrf($csrfToken) ?><input type="hidden" name="publicationId" value="<?= $pid ?>">
                <div class="d-flex gap-2">
                  <label class="visually-hidden" for="c-<?= $pid ?>">Votre commentaire</label>
                  <input type="text" class="form-control form-control-sm<?= invalide($erreurs, 'commentaire_' . $pid) ?>" id="c-<?= $pid ?>" name="commentaire" value="<?= e($old['commentaire_' . $pid] ?? '') ?>" placeholder="Répondre…" data-label="Le commentaire" data-required data-minlength="2" data-maxlength="500">
                  <button class="btn btn-sm sanad-btn" type="submit">Envoyer</button>
                </div>
                <div class="sanad-erreur" data-error-for="commentaire"><?= e($erreurs['commentaire_' . $pid] ?? '') ?></div>
              </form>
              <div class="d-flex gap-3">
                <?php if ((int) $p->getUtilisateurId() === $moi): ?>
                  <form action="index.php?page=communaute-supprimer" method="post" data-confirm="Supprimer votre publication et ses commentaires ?"><?= champCsrf($csrfToken) ?><input type="hidden" name="type" value="publication"><input type="hidden" name="id" value="<?= $pid ?>"><button class="btn btn-link btn-sm px-0 text-danger" type="submit">Supprimer ma publication</button></form>
                <?php elseif ($p->getStatut() === 'publie'): ?>
                  <form action="index.php?page=communaute-signaler" method="post" data-confirm="Signaler ce message à la modération ?"><?= champCsrf($csrfToken) ?><input type="hidden" name="type" value="publication"><input type="hidden" name="id" value="<?= $pid ?>"><button class="btn btn-link btn-sm px-0" type="submit">Signaler ce message</button></form>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
