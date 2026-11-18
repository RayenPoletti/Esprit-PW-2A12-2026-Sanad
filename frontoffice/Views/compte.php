<?php
/**
 * Mon compte · Module commun.
 * Variables : $moi (Utilisateur), $old, $erreurs (profil), $erreursMdp (mot de passe), $csrfToken, $flash
 */
$pageTitle   = 'Mon compte';
$currentPage = 'compte';
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
              <h1>Mon compte</h1>
              <p class="mb-0">Modifiez vos informations et votre mot de passe.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?= bandeauFlash($flash) ?>
        <div class="row gy-4">
          <div class="col-lg-4">
            <div class="sanad-card text-center">
              <div class="sanad-avatar mx-auto mb-3"><?= e(mb_strtoupper(mb_substr($moi->getPrenom(), 0, 1) . mb_substr($moi->getNom(), 0, 1))) ?></div>
              <h3 class="h5 mb-1"><?= e($moi->getNomComplet()) ?></h3>
              <p class="mb-2"><span class="badge rounded-pill sanad-chip"><?= e($moi->getLibelleRole()) ?></span></p>
              <p class="mb-1"><strong><?= (int) $moi->getPoints() ?></strong> points solidaires</p>
              <p class="small text-muted mb-3">Membre depuis le <?= e(date('d/m/Y', strtotime($moi->getDateInscription()))) ?></p>
              <a class="btn sanad-btn" href="index.php?page=passeport">Mon passeport</a>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="sanad-card mb-4">
              <h2 class="h5">Mes informations</h2>
              <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
              <form class="sanad-form" action="index.php?page=compte" method="post" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <div class="row">
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="prenom">Prénom *</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'prenom') ?>" id="prenom" name="prenom" value="<?= e($old['prenom'] ?? '') ?>" data-label="Le prénom" data-required data-rule="nom">
            <?= zoneErreur($erreurs, 'prenom') ?>
          </div>
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="nom">Nom *</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'nom') ?>" id="nom" name="nom" value="<?= e($old['nom'] ?? '') ?>" data-label="Le nom" data-required data-rule="nom">
            <?= zoneErreur($erreurs, 'nom') ?>
          </div>
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="email">E-mail *</label>
            <input type="email" class="form-control<?= invalide($erreurs, 'email') ?>" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" data-label="L’e-mail" data-required data-rule="email">
            <?= zoneErreur($erreurs, 'email') ?>
          </div>
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="telephone">Téléphone</label>
            <input type="tel" class="form-control<?= invalide($erreurs, 'telephone') ?>" id="telephone" name="telephone" value="<?= e($old['telephone'] ?? '') ?>" data-label="Le téléphone" data-rule="telephone">
            <?= zoneErreur($erreurs, 'telephone') ?>
          </div>
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="ville">Ville</label>
            <input type="text" class="form-control<?= invalide($erreurs, 'ville') ?>" id="ville" name="ville" value="<?= e($old['ville'] ?? '') ?>" data-label="La ville" data-rule="nom">
            <?= zoneErreur($erreurs, 'ville') ?>
          </div>
                </div>
                <div class="mt-4"><button type="submit">Enregistrer</button></div>
              </form>
            </div>
            <div class="sanad-card">
              <h2 class="h5">Changer mon mot de passe</h2>
              <?php if (isset($erreursMdp['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreursMdp['_form']]) ?><?php endif; ?>
              <form class="sanad-form" action="index.php?page=compte-motdepasse" method="post" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <div class="form-group mt-3">
            <label class="form-label" for="actuel">Mot de passe actuel *</label>
            <input type="password" class="form-control<?= invalide($erreursMdp, 'actuel') ?>" id="actuel" name="actuel" autocomplete="current-password" data-label="Le mot de passe actuel" data-required>
            <?= zoneErreur($erreursMdp, 'actuel') ?>
          </div>
                <div class="row">
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="motDePasse">Nouveau mot de passe *</label>
            <input type="password" class="form-control<?= invalide($erreursMdp, 'motDePasse') ?>" id="motDePasse" name="motDePasse" autocomplete="new-password" data-label="Le nouveau mot de passe" data-required data-rule="motdepasse">
            <?= zoneErreur($erreursMdp, 'motDePasse') ?>
          </div>
                  <div class="form-group mt-3 col-md-6">
            <label class="form-label" for="confirmation">Confirmation *</label>
            <input type="password" class="form-control<?= invalide($erreursMdp, 'confirmation') ?>" id="confirmation" name="confirmation" autocomplete="new-password" data-label="La confirmation" data-required data-match="motDePasse" data-match-msg="Les deux mots de passe ne sont pas identiques.">
            <?= zoneErreur($erreursMdp, 'confirmation') ?>
          </div>
                </div>
                <div class="mt-4"><button type="submit">Changer le mot de passe</button></div>
              </form>
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
