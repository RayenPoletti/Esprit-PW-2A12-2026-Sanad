<?php
/**
 * Inscription · Module commun. Variables : $erreurs, $old, $csrfToken, $flash
 * Le rôle n'est PAS demandé : il est forcé à « membre » côté serveur.
 */
$pageTitle   = 'Inscription';
$currentPage = 'inscription';
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
              <h1>Inscription</h1>
              <p class="mb-0">Créez votre compte gratuit. Vous pourrez ensuite remplir votre passeport d’accessibilité.</p>
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
          <form action="index.php?page=inscription" method="post" data-validate novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <div class="row">
              <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="prenom">Prénom *</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'prenom') ?>" id="prenom" name="prenom" value="<?= e($old['prenom'] ?? '') ?>" autocomplete="given-name" data-label="Le prénom" data-required data-rule="nom">
            <?= zoneErreur($erreurs, 'prenom') ?>
          </div>
              <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="nom">Nom *</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'nom') ?>" id="nom" name="nom" value="<?= e($old['nom'] ?? '') ?>" autocomplete="family-name" data-label="Le nom" data-required data-rule="nom">
            <?= zoneErreur($erreurs, 'nom') ?>
          </div>
            </div>
            <div class="form-group mt-3">
            <label class="form-label" for="email">E-mail *</label>
            <input type="email" class="form-control<?= invalide($erreurs, 'email') ?>" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" autocomplete="email" data-label="L’e-mail" data-required data-rule="email">
            <?= zoneErreur($erreurs, 'email') ?>
          </div>
            <div class="form-group mt-3">
            <label class="form-label" for="ville">Ville</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'ville') ?>" id="ville" name="ville" value="<?= e($old['ville'] ?? '') ?>" autocomplete="address-level2" data-label="La ville" data-rule="nom">
            <?= zoneErreur($erreurs, 'ville') ?>
          </div>
            <div class="form-group mt-3">
            <label class="form-label" for="motDePasse">Mot de passe *</label>
            <input type="password" class="form-control<?= invalide($erreurs, 'motDePasse') ?>" id="motDePasse" name="motDePasse" autocomplete="new-password" data-label="Le mot de passe" data-required data-rule="motdepasse" aria-describedby="aideMdp">
            <?= zoneErreur($erreurs, 'motDePasse') ?>
          </div>
            <small id="aideMdp" class="text-muted">8 caractères minimum, avec au moins une lettre et un chiffre.</small>
            <div class="form-group mt-3">
            <label class="form-label" for="confirmation">Confirmer le mot de passe *</label>
            <input type="password" class="form-control<?= invalide($erreurs, 'confirmation') ?>" id="confirmation" name="confirmation" autocomplete="new-password" data-label="La confirmation" data-required data-match="motDePasse" data-match-msg="Les deux mots de passe ne sont pas identiques.">
            <?= zoneErreur($erreurs, 'confirmation') ?>
          </div>
            <div class="text-center mt-4"><button type="submit">Créer mon compte</button></div>
            <p class="text-center mt-3 mb-0">Déjà inscrit(e) ? <a href="index.php?page=connexion">Se connecter</a></p>
          </form>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
