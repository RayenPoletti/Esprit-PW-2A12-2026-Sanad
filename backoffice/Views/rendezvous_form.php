<?php
/**
 * Ajouter / Modifier un rendez-vous · Module 2 · Aziz Kouki
 * Variables : $rdv (?RendezVous), $old, $erreurs, $patients, $pros, $creneaux, $csrfToken
 */
$titre  = $rdv ? 'Modifier un rendez-vous' : 'Ajouter un rendez-vous';
$action = $rdv ? 'index.php?page=rendezvous-edit&id=' . (int) $rdv->getId() : 'index.php?page=rendezvous-form';
?>
<?= filAriane($titre, ['Rendez-vous' => 'index.php?page=rendezvous']) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= e($titre) ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 2 · Aziz Kouki · Le passeport est transmis automatiquement si le patient l’a autorisé.</p>
  </div>
  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>
    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <?= champListe($erreurs, $old, 'utilisateurId', 'Patient *', $patients, 'data-label="Le patient" data-required') ?>
      <?= champListe($erreurs, $old, 'professionnelId', 'Professionnel *', $pros, 'data-label="Le professionnel" data-required') ?>
      <?= champ($erreurs, $old, 'date', 'Date *', 'data-label="La date" data-required', 'date') ?>
      <?= champListe($erreurs, $old, 'heure', 'Heure *', $creneaux, 'data-label="L’heure" data-required') ?>
      <?= champ($erreurs, $old, 'motif', 'Motif *', 'placeholder="Contrôle annuel" data-label="Le motif" data-required data-minlength="3" data-maxlength="150"') ?>
      <?= champListe($erreurs, $old, 'statut', 'Statut *', RendezVous::STATUTS, 'data-label="Le statut" data-required', '', '') ?>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600"><?= $rdv ? 'Enregistrer les modifications' : 'Ajouter le rendez-vous' ?></button>
      <a href="index.php?page=rendezvous" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Annuler</a>
    </div>
  </form>
</div>
