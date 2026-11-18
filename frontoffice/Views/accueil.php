<?php
$pageTitle   = 'Accueil';
$currentPage = 'accueil';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>

<body class="index-page">

<?php require_once __DIR__ . '/includes/spinner.php'; ?>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">
      <img src="Views/assets/img/hero-bg.jpg" alt="" data-aos="fade-in">
      <div class="container position-relative">
        <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
          <h2>Chaque lieu de soin, vérifié sur place</h2>
          <p>Des patients ambassadeurs auditent l’accessibilité des cabinets. Votre passeport d’accessibilité est transmis au cabinet avant chaque rendez-vous.</p>
        </div>

        <div class="content row gy-4">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
              <h3>Pourquoi Sanad ?</h3>
              <p>Aujourd’hui, aucun site ne dit si un cabinet est vraiment accessible. Sur Sanad, ce sont ceux qui vivent le handicap qui le vérifient. « Sanad » veut dire « soutien » en arabe.</p>
              <div class="text-center">
                <a href="index.php?page=professionnels" class="more-btn"><span>Trouver un lieu vérifié</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="d-flex flex-column justify-content-center">
              <div class="row gy-4">
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-person-vcard"></i>
                    <h4>Passeport d’accessibilité</h4>
                    <p>Vos besoins, déclarés une fois, envoyés au cabinet avant votre venue.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                    <i class="bi bi-patch-check"></i>
                    <h4>Audits par des ambassadeurs</h4>
                    <p>Rampe, ascenseur, toilettes adaptées : vérifiés sur place, photos à l’appui.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                    <i class="bi bi-gift"></i>
                    <h4>Points solidaires</h4>
                    <p>Chaque audit rapporte des points, échangeables contre du matériel adapté.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Content -->
      </div>
    </section><!-- /Hero Section -->

    <!-- Services Section -->
    <section id="services" class="services section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Nos services</h2>
      <p>Tout ce dont vous avez besoin pour vous soigner sans obstacle.</p>
      <span class="badge rounded-pill sanad-module">Six modules</span>
    </div>
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-search-heart"></i></div>
              <a href="index.php?page=professionnels" class="stretched-link"><h3>Trouver un professionnel</h3></a>
              <p>Filtrez les cabinets selon votre passeport et prenez rendez-vous en ligne.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-clipboard2-check"></i></div>
              <a href="index.php?page=audits" class="stretched-link"><h3>Auditer un lieu</h3></a>
              <p>Devenez ambassadeur : vérifiez un cabinet et gagnez des points solidaires.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-heart-pulse"></i></div>
              <a href="index.php?page=programmes" class="stretched-link"><h3>Programmes bien-être</h3></a>
              <p>Yoga sur chaise, gestion du stress, nutrition : en audio, sous-titré ou facile à lire.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-chat-heart"></i></div>
              <a href="index.php?page=communaute" class="stretched-link"><h3>Communauté d’entraide</h3></a>
              <p>Patients et aidants partagent conseils et témoignages, avec modération.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-bag-heart"></i></div>
              <a href="index.php?page=boutique" class="stretched-link"><h3>Boutique solidaire</h3></a>
              <p>Matériel reconditionné ou donné, payable en dinars ou en points.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-person-vcard"></i></div>
              <a href="index.php?page=passeport" class="stretched-link"><h3>Mon passeport</h3></a>
              <p>Déclarez vos besoins une fois pour que chaque cabinet se prépare à votre venue.</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Services Section -->

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
