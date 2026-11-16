<?php
$pageTitle   = 'Audits';
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
              <h1>Auditer un lieu de soin</h1>
              <p class="mb-0">Rendez-vous sur place et vérifiez chaque critère. Chaque audit validé rapporte 20 points solidaires.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="appointment" class="appointment section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Nouvel audit</h2>
      <p>Ce que vous avez constaté sur place, photos à l’appui.</p>
      <span class="badge rounded-pill sanad-module">Module 1 · Rayen Poletti · Lieu ↔ Audit</span>
    </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-7">
            <form action="index.php?page=audits" method="post" enctype="multipart/form-data" class="php-email-form">
              <div class="form-group"><label class="form-label" for="lieu">Lieu audité</label><select class="form-select" id="lieu" name="lieu" required><option value="">Choisir un cabinet…</option><option>Cabinet Ben Salah · Tunis</option><option>Cabinet Gharbi · Béja</option><option>Centre Jaziri · Sousse</option><option>Cabinet Ferchichi · Sfax</option></select></div>
              <fieldset class="row gy-2 mt-3"><legend class="fs-6 fw-semibold mb-2">Ce que j’ai constaté sur place</legend><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c0" name="criteres[]" value="Entrée de plain-pied ou rampe"><label class="form-check-label" for="c0">Entrée de plain-pied ou rampe</label></div></div><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c1" name="criteres[]" value="Porte d’au moins 90 cm"><label class="form-check-label" for="c1">Porte d’au moins 90 cm</label></div></div><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c2" name="criteres[]" value="Ascenseur ou rez-de-chaussée"><label class="form-check-label" for="c2">Ascenseur ou rez-de-chaussée</label></div></div><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c3" name="criteres[]" value="Toilettes adaptées"><label class="form-check-label" for="c3">Toilettes adaptées</label></div></div><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c4" name="criteres[]" value="Place de parking PMR"><label class="form-check-label" for="c4">Place de parking PMR</label></div></div><div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="c5" name="criteres[]" value="Accueil en langue des signes"><label class="form-check-label" for="c5">Accueil en langue des signes</label></div></div></fieldset>
              <div class="form-group mt-3"><label class="form-label" for="commentaire">Commentaire</label><textarea class="form-control" id="commentaire" name="commentaire" rows="4" placeholder="Ex. : rampe à gauche de l’entrée, sonnette à hauteur de fauteuil."></textarea></div>
              <div class="form-group mt-3"><label class="form-label" for="photos">Photos (facultatif)</label><input class="form-control" type="file" id="photos" name="photos[]" accept="image/*" multiple></div>
              <div class="text-center mt-4"><button type="submit">Envoyer l’audit</button></div>
            </form>
          </div>
          <div class="col-lg-5">
            <div class="sanad-card">
              <h3 class="h5">Mes audits</h3>
              <ul class="list-unstyled mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom"><span><strong>Cabinet Mansour</strong><br><small>19/10 · 2/6 critères</small></span><span class="badge rounded-pill text-bg-success align-self-center">Validé · +20 pts</span></li>
                <li class="d-flex justify-content-between py-2 border-bottom"><span><strong>Clinique El Menzah</strong><br><small>21/10 · 3/6 critères</small></span><span class="badge rounded-pill text-bg-success align-self-center">Validé · +20 pts</span></li>
                <li class="d-flex justify-content-between py-2"><span><strong>Cabinet Ferchichi</strong><br><small>15/11 · 1/6 critère</small></span><span class="badge rounded-pill text-bg-warning align-self-center">En attente</span></li>
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
