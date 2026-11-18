<?php
/**
 * Accès refusé (HTTP 403) : connecté(e), mais sans le rôle exigé.
 * Inclus par AuthController::requireRole() — variable : $rolesExiges
 */
if (!function_exists('navActive')) {
    require_once __DIR__ . '/includes/helpers.php';
}
$flash = null;
$pageTitle   = 'Accès refusé';
$currentPage = '';
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
              <h1>Accès refusé</h1>
              <p class="mb-0">Cette page est réservée à certains rôles.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container text-center sanad-auth">
        <div class="sanad-card">
          <p class="display-4 fw-bold mb-2" style="color: var(--accent-color)">403</p>
          <p>Vous êtes connecté(e) en tant que <strong><?= e(Utilisateur::LIBELLES_ROLES[$_SESSION['role'] ?? ''] ?? 'invité') ?></strong>.</p>
          <p>Cette page est réservée au rôle : <strong><?= e(implode(', ', array_map(fn($r) => Utilisateur::LIBELLES_ROLES[$r] ?? $r, $rolesExiges ?? []))) ?></strong>.</p>
          <p class="small text-muted">Les ambassadeurs sont des membres choisis par l’équipe Sanad après une courte formation.</p>
          <a class="btn sanad-btn mt-2" href="index.php?page=accueil">Retour à l’accueil</a>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
