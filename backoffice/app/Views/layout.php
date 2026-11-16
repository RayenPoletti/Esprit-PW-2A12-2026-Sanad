<?php
/**
 * Layout principal du Back Office : inclut tous les blocs communs et injecte
 * le contenu propre à chaque page ($content) au milieu.
 *
 * Variables attendues (fournies par Controller::render()) :
 *   $title         string  titre de l'onglet <title>
 *   $currentPage   string  clé de la page courante (pour la sidebar)
 *   $content       string  HTML de la page (déjà rendu)
 *   $extraScripts  string  JS spécifique à la page (optionnel)
 */
require_once __DIR__ . '/partials/head.php';
?>
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/partials/sidebar.php'; ?>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
      <main id="main-content">
        <?php require_once __DIR__ . '/partials/header.php'; ?>
        <div class="mx-auto max-w-(--breakpoint-2xl) p-4 pb-20 md:p-6 md:pb-6">
          <?= $content ?>
        </div>
      </main>
      <?php require_once __DIR__ . '/partials/footer.php'; ?>
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
