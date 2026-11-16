<?php
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
              <p class="mb-0">Créez votre compte Sanad en une minute.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <section id="appointment" class="appointment section">
      <div class="container" style="max-width: 560px" data-aos="fade-up">
        <form action="index.php?page=inscription" method="post" class="php-email-form">
          <div class="form-group mt-3"><label class="form-label" for="nom">Nom</label><input type="text" class="form-control" id="nom" name="nom" required></div><div class="form-group mt-3"><label class="form-label" for="prenom">Prénom</label><input type="text" class="form-control" id="prenom" name="prenom" required></div><div class="form-group mt-3"><label class="form-label" for="email">E-mail</label><input type="email" class="form-control" id="email" name="email" required></div><div class="form-group mt-3"><label class="form-label" for="motDePasse">Mot de passe</label><input type="password" class="form-control" id="motDePasse" name="motDePasse" required></div><div class="form-group mt-3"><label class="form-label" for="ville">Ville</label><input type="text" class="form-control" id="ville" name="ville" required></div>
          <div class="text-center mt-4"><button type="submit">Créer mon compte</button></div>
          <p class="text-center mt-3 mb-0">Déjà inscrit ? <a href="index.php?page=connexion">Se connecter</a></p>
        </form>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>

</body>

</html>
