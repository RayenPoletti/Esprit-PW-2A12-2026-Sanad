<?php
// $title est fourni par le Controller (voir Controllers/Controller.php)
$title = $title ?? 'Sanad · Back Office';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="icon" href="Views/assets/img/favicon.svg" type="image/svg+xml" />

  <!-- ========== CSS (TailAdmin compilé avec Tailwind) ========== -->
  <link rel="stylesheet" href="Views/assets/css/style.css" />

  <!-- Thème clair / sombre mémorisé (repris du « Theme Manager » de TailAdmin) -->
  <script>
    (function () {
      var stored = localStorage.getItem('darkMode');
      var isDark = stored !== null ? JSON.parse(stored) : false;
      document.documentElement.classList.toggle('dark', isDark);
    })();
  </script>
</head>
<body
  x-data="{ page: '<?= htmlspecialchars($currentPage ?? '') ?>', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
  x-init="
       darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? false;
       $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
  :class="{'dark bg-gray-900': darkMode === true}"
>
  <?php require_once __DIR__ . '/spinner.php'; ?>
