<?php
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
              <p class="mb-0">Partagez votre expérience. Les messages signalés sont vérifiés par un modérateur.</p>
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
        <form class="sanad-card mb-4" action="index.php?page=communaute" method="post">
          <label for="publication" class="form-label fw-semibold">Partager un témoignage ou poser une question</label>
          <textarea class="form-control" id="publication" name="contenu" rows="3" placeholder="Par exemple : quel kinésithérapeute accessible recommandez-vous à Bizerte ?"></textarea>
          <button class="btn sanad-btn mt-3" type="submit">Publier</button>
        </form>
          <article class="sanad-card mb-4" data-aos="fade-up">
            <div class="d-flex align-items-center gap-3 mb-2"><div class="sanad-avatar sm" aria-hidden="true">S</div><div><strong>Salma K.</strong><br><small class="text-muted">il y a 2 h</small></div></div>
            <p>Ma mère a 78 ans et se déplace avec un déambulateur. Avez-vous des conseils pour adapter la salle de bain sans gros travaux ?</p>
            <div class="sanad-comment"><strong>Hédi M.</strong> · Une barre d’appui près des toilettes et un tapis antidérapant ont tout changé chez moi.</div><div class="sanad-comment"><strong>Fatma R.</strong> · Un siège de douche pliant ! Il y en a un en don dans la boutique solidaire.</div>
            <form class="d-flex gap-2 mt-3" action="index.php?page=communaute" method="post"><label class="visually-hidden" for="c-S125">Votre commentaire</label><input class="form-control" id="c-S125" name="commentaire" placeholder="Répondre avec bienveillance…"><button class="btn sanad-btn" type="submit">Commenter</button></form>
            <button type="button" class="btn btn-link btn-sm px-0 mt-2">Signaler ce message</button>
          </article>
          <article class="sanad-card mb-4" data-aos="fade-up">
            <div class="d-flex align-items-center gap-3 mb-2"><div class="sanad-avatar sm" aria-hidden="true">H</div><div><strong>Hédi M.</strong><br><small class="text-muted">hier</small></div></div>
            <p>Le cabinet de Béja que j’ai audité la semaine dernière a installé une rampe ! Je viens de refaire l’audit avec des photos.</p>
            <div class="sanad-comment"><strong>Amira B.</strong> · Bravo, ça montre que les audits servent vraiment.</div>
            <form class="d-flex gap-2 mt-3" action="index.php?page=communaute" method="post"><label class="visually-hidden" for="c-H122">Votre commentaire</label><input class="form-control" id="c-H122" name="commentaire" placeholder="Répondre avec bienveillance…"><button class="btn sanad-btn" type="submit">Commenter</button></form>
            <button type="button" class="btn btn-link btn-sm px-0 mt-2">Signaler ce message</button>
          </article>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
