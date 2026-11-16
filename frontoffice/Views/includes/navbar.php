<?php
// $currentPage doit être défini dans la page appelante (ex : $currentPage = 'accueil';)
if (!isset($currentPage)) {
    $currentPage = '';
}

// Renvoie 'active' si le lien correspond à la page en cours
if (!function_exists('navActive')) {
    function navActive(string $page, string $currentPage): string
    {
        return $page === $currentPage ? 'active' : '';
    }
}
?>
<header id="header" class="header sticky-top">

  <div class="topbar d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><span>contact@sanad.tn</span></i>
        <i class="bi bi-universal-access d-flex align-items-center ms-4"><span>Site conforme WCAG AA</span></i>
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        <a href="#" class="facebook" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
      </div>
    </div>
  </div><!-- End Top Bar -->

  <div class="branding d-flex align-items-center">
    <div class="container position-relative d-flex align-items-center justify-content-end">
      <a href="index.php?page=accueil" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/sanad-icon.svg" alt="">
        <h1 class="sitename">Sanad</h1>
      </a>

      <nav id="navmenu" class="navmenu" aria-label="Navigation principale">
        <ul>
          <li><a href="index.php?page=accueil" class="<?= navActive('accueil', $currentPage) ?>">Accueil</a></li>
          <li><a href="index.php?page=passeport" class="<?= navActive('passeport', $currentPage) ?>">Mon passeport</a></li>
          <li><a href="index.php?page=professionnels" class="<?= navActive('professionnels', $currentPage) ?>">Professionnels</a></li>
          <li><a href="index.php?page=audits" class="<?= navActive('audits', $currentPage) ?>">Audits</a></li>
          <li><a href="index.php?page=programmes" class="<?= navActive('programmes', $currentPage) ?>">Programmes</a></li>
          <li><a href="index.php?page=communaute" class="<?= navActive('communaute', $currentPage) ?>">Communauté</a></li>
          <li><a href="index.php?page=boutique" class="<?= navActive('boutique', $currentPage) ?>">Boutique</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="cta-btn d-none d-sm-block" href="index.php?page=connexion">Se connecter</a>
    </div>
  </div>

</header>
