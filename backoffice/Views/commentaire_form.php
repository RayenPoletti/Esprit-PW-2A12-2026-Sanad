<?php
/**
 * Modifier un commentaire · Module 4 · Houssem Hafsi
 * Variables : $commentaire (Commentaire), $old, $erreurs, $csrfToken
 */
$titre  = 'Modifier un commentaire';
$action = 'index.php?page=commentaire-edit&id=' . (int) $commentaire->getId();
?>
<?= filAriane($titre, ['Commentaires' => 'index.php?page=commentaires']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= e($titre) ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 4 · Houssem Hafsi · Corriger un commentaire (par exemple retirer des coordonnées personnelles).</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champListe($erreurs, $old, 'statut', 'Statut *', Commentaire::STATUTS, 'data-label="Le statut" data-required', '', '') ?>
      <?= champZone($erreurs, $old, 'contenu', 'Commentaire *', 'data-label="Le commentaire" data-required data-minlength="2" data-maxlength="500"') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Enregistrer</button>
      <a href="index.php?page=commentaires" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
