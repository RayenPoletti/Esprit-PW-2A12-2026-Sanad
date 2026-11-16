<?php
$pageTitle   = 'Programmes';
$currentPage = 'programmes';
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
              <h1>Programmes bien-être</h1>
              <p class="mb-0">Chaque programme précise son format : audio, vidéo sous-titrée, langue des signes ou facile à lire.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="services" class="services section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Programmes adaptés à chacun</h2>
      <p>Suggestion d’après votre passeport : Méditation guidée, 100 % audio.</p>
      <span class="badge rounded-pill sanad-module">Module 3 · Rayen Marzougi · Programme ↔ Séance</span>
    </div>
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-person-arms-up"></i></div>
              <h3>Yoga sur chaise</h3>
              <p class="mb-2"><strong>Débutant · 6 séances</strong></p>
              <p>Mobilité douce et respiration, entièrement assis.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Vidéo sous-titrée · Adapté fauteuil</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-wind"></i></div>
              <h3>Gestion du stress</h3>
              <p class="mb-2"><strong>Tous niveaux · 8 séances</strong></p>
              <p>Exercices de cohérence cardiaque et relaxation guidée.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Audio · Facile à lire</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-person-walking"></i></div>
              <h3>Marche douce seniors</h3>
              <p class="mb-2"><strong>Débutant · 5 séances</strong></p>
              <p>Reprendre l’activité physique à son rythme, avec un aidant.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Audio · Texte agrandi</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="250">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-egg-fried"></i></div>
              <h3>Nutrition et diabète</h3>
              <p class="mb-2"><strong>Tous niveaux · 4 séances</strong></p>
              <p>Composer des repas équilibrés avec les produits du marché.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Facile à lire · Vidéo sous-titrée</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-moon-stars"></i></div>
              <h3>Méditation guidée</h3>
              <p class="mb-2"><strong>Tous niveaux · 10 séances</strong></p>
              <p>Dix minutes par jour pour apaiser le mental, sans écran.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Audio</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="350">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-activity"></i></div>
              <h3>Renforcement du dos</h3>
              <p class="mb-2"><strong>Intermédiaire · 6 séances</strong></p>
              <p>Prévenir les douleurs liées à la position assise prolongée.</p>
              <p class="mt-2"><span class="badge rounded-pill sanad-chip">Vidéo sous-titrée · Langue des signes</span></p>
              <a href="index.php?page=programmes" class="btn btn-sm sanad-btn mt-3">Rejoindre</a>
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
