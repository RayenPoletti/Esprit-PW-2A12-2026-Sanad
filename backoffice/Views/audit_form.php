<?php
/**
 * Ajouter / Modifier un audit · Module 1 · Rayen Poletti
 * Variables : $audit (?Audit), $old, $criteres (critère => bool), $erreurs, $lieux, $ambassadeurs, $csrfToken
 */
$titre  = $audit ? 'Modifier un audit' : 'Ajouter un audit';
$action = $audit ? 'index.php?page=audit-edit&id=' . (int) $audit->getId() : 'index.php?page=audit-form';
?>
<?= filAriane($titre, ['Audits' => 'index.php?page=audits']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= e($titre) ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 1 · Rayen Poletti · Les audits sont normalement envoyés par les ambassadeurs depuis le site.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champListe($erreurs, $old, 'lieuId', 'Lieu audité *', $lieux, 'data-label="Le lieu" data-required') ?>
      <?= champListe($erreurs, $old, 'utilisateurId', 'Ambassadeur *', $ambassadeurs, 'data-label="L’ambassadeur" data-required') ?>
      <?= champ($erreurs, $old, 'dateAudit', 'Date de l’audit *', 'max="' . date('Y-m-d') . '" data-label="La date de l’audit" data-required data-rule="date-passee"', 'date') ?>
      <?= champListe($erreurs, $old, 'statut', 'Statut *', Audit::STATUTS, 'data-label="Le statut" data-required', '', '') ?>
      <fieldset class="sm:col-span-2 mb-4">
        <legend class="<?= CLASSE_LIBELLE ?>">Critères constatés sur place</legend>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <?php foreach (Audit::CRITERES as $cle => $libelle): ?><?= caseCochee('criteres[]', $libelle, $criteres[$cle], $cle) ?><?php endforeach; ?>
        </div>
      </fieldset>
      <?= champZone($erreurs, $old, 'commentaire', 'Commentaire *', 'placeholder="Ce qui a été constaté sur place…" data-label="Le commentaire" data-required data-minlength="10" data-maxlength="1000"') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $audit ? 'Enregistrer les modifications' : 'Ajouter l’audit' ?></button>
      <a href="index.php?page=audits" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
