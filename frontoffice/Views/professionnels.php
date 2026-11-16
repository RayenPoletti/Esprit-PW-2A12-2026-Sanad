<?php
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
        <form class="row g-2 mb-4 sanad-filters" action="index.php" method="get" role="search">
          <input type="hidden" name="page" value="professionnels">
          <div class="col-md-4"><label class="visually-hidden" for="specialite">Spécialité</label><select class="form-select" id="specialite" name="specialite"><option value="">Toutes les spécialités</option><option>Cardiologie</option><option>Dermatologie</option><option>Médecine générale</option><option>Ophtalmologie</option><option>Kinésithérapie</option><option>Psychologie</option></select></div>
          <div class="col-md-3"><label class="visually-hidden" for="ville">Ville</label><select class="form-select" id="ville" name="ville"><option value="">Toutes les villes</option><option>Tunis</option><option>Béja</option><option>Sousse</option><option>Bizerte</option><option>Sfax</option></select></div>
          <div class="col-md-3 d-flex align-items-center"><div class="form-check"><input class="form-check-input" type="checkbox" id="monPasseport" name="passeport" checked><label class="form-check-label" for="monPasseport">Appliquer mon passeport</label></div></div>
          <div class="col-md-2"><button class="btn sanad-btn w-100" type="submit">Rechercher</button></div>
        </form>
        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">LB</div>
              <div class="member-info">
                <h4>Dr Leila Ben Salah</h4>
                <span>Cardiologie · Tunis</span>
                <p><strong>Score d’accessibilité : 4,2 / 5</strong> · vérifié par 2 ambassadeurs</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Plain-pied ou rampe</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Toilettes adaptées</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Parking PMR</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="150">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">SG</div>
              <div class="member-info">
                <h4>Dr Sonia Gharbi</h4>
                <span>Médecine générale · Béja</span>
                <p><strong>Score d’accessibilité : 4,2 / 5</strong> · vérifié par 1 ambassadeur</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Plain-pied ou rampe</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Toilettes adaptées</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Parking PMR</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">AC</div>
              <div class="member-info">
                <h4>Dr Amel Chaouch</h4>
                <span>Kinésithérapie · Bizerte</span>
                <p><strong>Score d’accessibilité : 4,2 / 5</strong> · vérifié par 1 ambassadeur</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Plain-pied ou rampe</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Parking PMR</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">KJ</div>
              <div class="member-info">
                <h4>Dr Karim Jaziri</h4>
                <span>Ophtalmologie · Sousse</span>
                <p><strong>Score d’accessibilité : 3,3 / 5</strong> · vérifié par 1 ambassadeur</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Plain-pied ou rampe</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Toilettes adaptées</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">MT</div>
              <div class="member-info">
                <h4>Dr Mehdi Trabelsi</h4>
                <span>Dermatologie · Tunis</span>
                <p><strong>Score d’accessibilité : 2,5 / 5</strong> · vérifié par 1 ambassadeur</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Langue des signes</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="350">
            <div class="team-member d-flex align-items-start">
              <div class="pic sanad-avatar" aria-hidden="true">YM</div>
              <div class="member-info">
                <h4>Dr Youssef Mansour</h4>
                <span>Psychologie · Sfax</span>
                <p><strong>Score d’accessibilité : 1,7 / 5</strong> · vérifié par 1 ambassadeur</p>
                <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Ascenseur</span><span class="badge rounded-pill sanad-chip"><i class="bi bi-check2"></i> Langue des signes</span></div>
                <div class="mt-3 d-flex gap-2">
                  <a href="index.php?page=rendezvous" class="btn btn-sm sanad-btn">Prendre rendez-vous</a>
                  <a href="index.php?page=audits" class="btn btn-sm btn-outline-secondary">Auditer ce lieu</a>
                </div>
              </div>
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
