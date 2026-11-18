<?php
/**
 * Ajouter / Modifier une séance · Module 3 · Rayen Marzougi
 * Variables : $seance (?Seance), $old, $erreurs, $programmes, $csrfToken
 */
$titre  = $seance ? 'Modifier une séance' : 'Ajouter une séance';
$action = $seance ? 'index.php?page=seance-edit&id=' . (int) $seance->getId() : 'index.php?page=seance-form';
?>
<?= filAriane($titre, ['Séances' => 'index.php?page=seances']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $seance ? e($seance->getTitre()) : 'Nouvelle séance' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 3 · Rayen Marzougi · Les liens doivent commencer par http:// ou https://.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champListe($erreurs, $old, 'programmeId', 'Programme *', $programmes, 'data-label="Le programme" data-required') ?>
      <?= champ($erreurs, $old, 'titre', 'Titre *', 'placeholder="Réveil des épaules" data-label="Le titre" data-required data-minlength="3" data-maxlength="100"') ?>
      <?= champ($erreurs, $old, 'ordre', 'Ordre dans le programme *', 'inputmode="numeric" data-label="L’ordre" data-required data-rule="entier" data-min="1" data-max="100"') ?>
      <?= champ($erreurs, $old, 'dureeMin', 'Durée (minutes) *', 'inputmode="numeric" placeholder="10" data-label="La durée" data-required data-rule="entier" data-min="1" data-max="180"') ?>
      <?= champ($erreurs, $old, 'urlVideo', 'Lien de la vidéo (sous-titrée)', 'placeholder="https://…" data-label="Le lien de la vidéo" data-rule="url"', 'url') ?>
      <?= champ($erreurs, $old, 'urlAudio', 'Lien de l’audio', 'placeholder="https://…" data-label="Le lien de l’audio" data-rule="url"', 'url') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $seance ? 'Enregistrer les modifications' : 'Ajouter la séance' ?></button>
      <a href="index.php?page=seances" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
