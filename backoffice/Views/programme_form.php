<?php
/**
 * Ajouter / Modifier un programme · Module 3 · Rayen Marzougi
 * Variables : $programme (?Programme), $old, $formats (clés cochées), $erreurs, $csrfToken
 */
$titre  = $programme ? 'Modifier un programme' : 'Ajouter un programme';
$action = $programme ? 'index.php?page=programme-edit&id=' . (int) $programme->getId() : 'index.php?page=programme-form';
?>
<?= filAriane($titre, ['Programmes' => 'index.php?page=programmes']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $programme ? e($programme->getTitre()) : 'Nouveau programme' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 3 · Rayen Marzougi · Après la création, vous ajouterez la première séance.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champ($erreurs, $old, 'titre', 'Titre *', 'placeholder="Yoga sur chaise" data-label="Le titre" data-required data-minlength="3" data-maxlength="100"') ?>
      <?= champListe($erreurs, $old, 'niveau', 'Niveau *', array_combine(Programme::NIVEAUX, Programme::NIVEAUX), 'data-label="Le niveau" data-required') ?>
      <fieldset class="sm:col-span-2 mb-2">
        <legend class="<?= CLASSE_LIBELLE ?>">Formats accessibles *</legend>
        <div class="flex flex-wrap gap-6">
          <?php foreach (Programme::FORMATS as $cle => $libelle): ?><?= caseCochee('formats[]', $libelle, in_array($cle, $formats, true), $cle) ?><?php endforeach; ?>
        </div>
        <?= zoneErreur($erreurs, 'formats') ?>
      </fieldset>
      <?= champZone($erreurs, $old, 'description', 'Description *', 'placeholder="À qui s’adresse ce programme, ce qu’il apporte…" data-label="La description" data-required data-minlength="10" data-maxlength="1000"') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $programme ? 'Enregistrer les modifications' : 'Créer et ajouter une séance' ?></button>
      <a href="index.php?page=programmes" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
