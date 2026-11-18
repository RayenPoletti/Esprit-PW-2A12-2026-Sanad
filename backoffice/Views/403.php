<?php
/**
 * Accès refusé (HTTP 403) : l'utilisateur est connecté mais n'a pas le rôle exigé.
 * Inclus directement par AuthController::requireRole() — variable disponible : $rolesExiges.
 */
$title = 'Accès refusé | Sanad Back Office';
require __DIR__ . '/includes/head.php';
?>
<div class="flex min-h-screen flex-col items-center justify-center bg-white p-6 text-center dark:bg-gray-900">
  <p class="text-6xl font-bold text-brand-500">403</p>
  <h1 class="mt-3 text-xl font-semibold text-gray-800 dark:text-white/90">Accès refusé</h1>
  <p class="mt-2 max-w-md text-theme-sm text-gray-500 dark:text-gray-400">
    Vous êtes connecté(e) en tant que <strong><?= e(Utilisateur::LIBELLES_ROLES[$_SESSION['role'] ?? ''] ?? 'invité') ?></strong>.
    Cette page est réservée au rôle : <?= e(implode(', ', array_map(fn($r) => Utilisateur::LIBELLES_ROLES[$r] ?? $r, $rolesExiges ?? []))) ?>.
  </p>
  <div class="mt-6 flex flex-wrap justify-center gap-3">
    <a href="../frontoffice/index.php?page=accueil" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600">Aller au site Sanad</a>
    <a href="index.php?page=logout" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300">Changer de compte</a>
  </div>
</div>
<?php require __DIR__ . '/includes/scripts.php'; ?>
