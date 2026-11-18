<?php
/**
 * Ajouter / Modifier un lieu · Module 1 · Rayen Poletti
 * Variables : $lieu (?Lieu), $old, $erreurs, $csrfToken
 */
$titre  = $lieu ? 'Modifier un lieu' : 'Ajouter un lieu';
$action = $lieu ? 'index.php?page=lieu-edit&id=' . (int) $lieu->getId() : 'index.php?page=lieu-form';
?>
<?= filAriane($titre, ['Lieux de soin' => 'index.php?page=lieux']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $lieu ? e($lieu->getNom()) : 'Nouveau lieu de soin' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 1 · Rayen Poletti · Le score d’accessibilité sera calculé à partir des audits validés.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champ($erreurs, $old, 'nom', 'Nom du lieu *', 'placeholder="Cabinet Ben Salah" data-label="Le nom du lieu" data-required data-minlength="2" data-maxlength="100"') ?>
      <?= champ($erreurs, $old, 'adresse', 'Adresse *', 'placeholder="12 rue de Marseille" data-label="L’adresse" data-required data-minlength="5" data-maxlength="200"') ?>
      <?= champ($erreurs, $old, 'ville', 'Ville *', 'placeholder="Tunis" data-label="La ville" data-required data-rule="nom"') ?>
      <div></div>
      <?= champ($erreurs, $old, 'latitude', 'Latitude', 'inputmode="decimal" placeholder="36.8008" data-label="La latitude" data-rule="decimal" data-min="-90" data-max="90"') ?>
      <?= champ($erreurs, $old, 'longitude', 'Longitude', 'inputmode="decimal" placeholder="10.1800" data-label="La longitude" data-rule="decimal" data-min="-180" data-max="180"') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $lieu ? 'Enregistrer les modifications' : 'Ajouter le lieu' ?></button>
      <a href="index.php?page=lieux" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
