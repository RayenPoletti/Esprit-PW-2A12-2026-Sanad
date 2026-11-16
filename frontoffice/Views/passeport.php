<?php
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
      <p>Le cabinet recevra ces informations avant votre venue.</p>
      <span class="badge rounded-pill sanad-module">Module commun · toute l’équipe</span>
    </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <form action="index.php?page=passeport" method="post" class="php-email-form">
          <fieldset class="row gy-2">
            <legend class="fs-6 fw-semibold mb-2">Je déclare que…</legend>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="fauteuil" name="besoins[]" value="fauteuilRoulant"><label class="form-check-label" for="fauteuil">Je me déplace en fauteuil roulant</label></div></div>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="marche" name="besoins[]" value="mobiliteReduite"><label class="form-check-label" for="marche">Je marche difficilement</label></div></div>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="vue" name="besoins[]" value="malvoyance" checked><label class="form-check-label" for="vue">Je suis malvoyant(e)</label></div></div>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="surdite" name="besoins[]" value="surdite"><label class="form-check-label" for="surdite">Je suis sourd(e) ou malentendant(e)</label></div></div>
            <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" id="aidant" name="besoins[]" value="accompagneAidant"><label class="form-check-label" for="aidant">Je viens avec un aidant</label></div></div>
          </fieldset>
          <div class="form-group mt-3">
            <label for="precisions" class="form-label">Précisions pour le cabinet</label>
            <textarea class="form-control" id="precisions" name="precisions" rows="4">Merci de me guider jusqu’à la salle d’attente.</textarea>
          </div>
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="partage" name="partageAutorise" checked>
            <label class="form-check-label" for="partage"><strong>J’accepte que mon passeport soit transmis</strong> au cabinet quand je prends rendez-vous.</label>
          </div>
          <div class="text-center mt-4"><button type="submit">Enregistrer mon passeport</button></div>
        </form>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
