<?php
/**
 * Ajouter / Modifier un professionnel · Module 2 · Aziz Kouki
 * Variables : $pro (?Professionnel), $old, $valide, $erreurs, $lieux, $csrfToken
 */
$titre  = $pro ? 'Modifier un professionnel' : 'Ajouter un professionnel';
$action = $pro ? 'index.php?page=professionnel-edit&id=' . (int) $pro->getId() : 'index.php?page=professionnel-form';
?>
<?= filAriane($titre, ['Professionnels' => 'index.php?page=professionnels']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $pro ? e($pro->getNomComplet()) : 'Nouveau professionnel' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 2 · Aziz Kouki · Les champs marqués * sont obligatoires.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champ($erreurs, $old, 'prenom', 'Prénom *', 'placeholder="Leila" data-label="Le prénom" data-required data-rule="nom"') ?>
      <?= champ($erreurs, $old, 'nom', 'Nom *', 'placeholder="Ben Salah" data-label="Le nom" data-required data-rule="nom"') ?>
      <?= champListe($erreurs, $old, 'specialite', 'Spécialité *', array_combine(Professionnel::SPECIALITES, Professionnel::SPECIALITES), 'data-label="La spécialité" data-required') ?>
      <?= champ($erreurs, $old, 'telephone', 'Téléphone *', 'placeholder="+216 71 100 200" data-label="Le téléphone" data-required data-rule="telephone"', 'tel') ?>
      <?= champListe($erreurs, $old, 'lieuId', 'Lieu d’exercice *', $lieux, 'data-label="Le lieu d’exercice" data-required') ?>
      <div class="flex items-center pt-6"><?= caseCochee('valide', 'Professionnel validé (visible sur le site)', $valide) ?></div>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $pro ? 'Enregistrer les modifications' : 'Ajouter le professionnel' ?></button>
      <a href="index.php?page=professionnels" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
