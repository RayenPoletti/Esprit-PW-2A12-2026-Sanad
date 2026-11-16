<?php
// $pageTitle doit être défini dans la page appelante avant l'inclusion
$pageTitle = $pageTitle ?? 'Sanad';
?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= htmlspecialchars($pageTitle) ?> · Sanad</title>
  <meta name="description" content="Sanad : la santé accessible, vérifiée par ceux qui la vivent.">

  <!-- Favicons -->
  <link href="assets/img/sanad-icon.svg" rel="icon" type="image/svg+xml">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files (Medilab) -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File (Medilab) -->
  <link href="assets/css/main.css" rel="stylesheet">
  <!-- Charte graphique Sanad (surcharge les couleurs de Medilab) -->
  <link href="assets/css/sanad.css" rel="stylesheet">
