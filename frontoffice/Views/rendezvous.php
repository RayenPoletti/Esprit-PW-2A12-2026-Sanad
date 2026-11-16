<?php
$pageTitle   = 'Prendre rendez-vous';
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
      <p>Choisissez le professionnel, la date et l’heure.</p>
      <span class="badge rounded-pill sanad-module">Module 2 · Aziz Kouki</span>
    </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <form action="index.php?page=rendezvous" method="post" class="php-email-form">
          <div class="row">
            <div class="col-md-4 form-group"><label class="form-label" for="nom">Nom complet</label><input type="text" name="nom" class="form-control" id="nom" required></div>
            <div class="col-md-4 form-group mt-3 mt-md-0"><label class="form-label" for="email">E-mail</label><input type="email" class="form-control" name="email" id="email" required></div>
            <div class="col-md-4 form-group mt-3 mt-md-0"><label class="form-label" for="tel">Téléphone</label><input type="tel" class="form-control" name="telephone" id="tel"></div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mt-3"><label class="form-label" for="date">Date</label><input type="date" name="date" class="form-control" id="date" required></div>
            <div class="col-md-4 form-group mt-3"><label class="form-label" for="pro">Professionnel</label><select name="professionnel" id="pro" class="form-select" required><option value="">Choisir…</option><option>Dr Leila Ben Salah · Cardiologie</option><option>Dr Sonia Gharbi · Médecine générale</option><option>Dr Amel Chaouch · Kinésithérapie</option><option>Dr Karim Jaziri · Ophtalmologie</option><option>Dr Mehdi Trabelsi · Dermatologie</option><option>Dr Youssef Mansour · Psychologie</option></select></div>
            <div class="col-md-4 form-group mt-3"><label class="form-label" for="heure">Heure</label><select name="heure" id="heure" class="form-select"><option>09:00</option><option>10:30</option><option>14:00</option><option>15:30</option><option>17:00</option></select></div>
          </div>
          <div class="form-group mt-3"><label class="form-label" for="motif">Motif (facultatif)</label><textarea class="form-control" name="motif" id="motif" rows="4"></textarea></div>
          <div class="form-check mt-3"><input class="form-check-input" type="checkbox" id="transmettre" name="passeportTransmis" checked><label class="form-check-label" for="transmettre"><strong>Transmettre mon passeport au cabinet</strong> (malvoyance : documents en gros caractères, être guidée à l’arrivée)</label></div>
          <div class="text-center mt-4"><button type="submit">Confirmer le rendez-vous</button></div>
        </form>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
