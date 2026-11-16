<?php
// $title est fourni par le Controller (voir app/Controllers/Controller.php)
$title = $title ?? 'Sanad · Back Office';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="icon" href="app/Views/assets/images/favicon.svg" type="image/svg+xml" />

  <!-- ========== CSS (TailAdmin compilé avec Tailwind) ========== -->
  <link rel="stylesheet" href="app/Views/assets/css/style.css" />

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
  <!-- ===== Preloader Start ===== -->
  <div
    x-show="loaded"
    x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
    class="fixed top-0 left-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
  >
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
  </div>
  <!-- ===== Preloader End ===== -->
