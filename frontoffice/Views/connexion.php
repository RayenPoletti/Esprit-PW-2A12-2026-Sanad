<?php
/**
 * Connexion · Module commun. Variables : $erreurs, $email, $csrfToken, $flash
 */
$pageTitle   = 'Connexion';
$currentPage = 'connexion';
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
              <h1>Connexion</h1>
              <p class="mb-0">Connectez-vous pour gérer votre passeport et vos rendez-vous.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container sanad-auth">
        <div class="sanad-card">
          <?= bandeauFlash($flash) ?>
          <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
          <form action="index.php?page=connexion" method="post" data-validate novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <div class="form-group mt-3">
            <label class="form-label" for="email">E-mail *</label>
            <input type="email" class="form-control<?= invalide($erreurs, 'email') ?>" id="email" name="email" value="<?= e($email) ?>" autocomplete="username" data-label="L’e-mail" data-required data-rule="email">
            <?= zoneErreur($erreurs, 'email') ?>
          </div>
            <div class="form-group mt-3">
            <label class="form-label" for="motDePasse">Mot de passe *</label>
            <input type="password" class="form-control<?= invalide($erreurs, 'motDePasse') ?>" id="motDePasse" name="motDePasse" autocomplete="current-password" data-label="Le mot de passe" data-required>
            <?= zoneErreur($erreurs, 'motDePasse') ?>
          </div>
            <div class="text-center mt-4"><button type="submit">Se connecter</button></div>
            <p class="text-center mt-3 mb-0">Pas encore de compte ? <a href="index.php?page=inscription">S’inscrire</a></p>
          </form>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
