<?php
/**
 * Programmes bien-être · Module 3 · Rayen Marzougi · composition Programme ◆— Séance
 * Variables : $lignes (['programme' => Programme, 'nbSeances', 'duree', 'nbInscrits']), $q, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Programmes') ?>
<?= bandeauFlash($flash) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Programmes bien-être', count($lignes) . ' programme(s) · un programme doit avoir au moins une séance pour être visible · Module 3 · Rayen Marzougi', 'programmes', $q, 'index.php?page=programme-form', 'Ajouter un programme') ?>
  <?= debutTableau(['Programme', 'Niveau', 'Formats', 'Séances', 'Inscrits', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $p = $ligne['programme']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="block font-medium text-gray-800 dark:text-white/90"><?= e($p->getTitre()) ?></span><span class="block max-w-xs truncate text-theme-xs"><?= e($p->getDescription()) ?></span></td>
        <td class="<?= $td ?>"><?= e($p->getNiveau()) ?></td>
        <td class="<?= $td ?>"><?= e(implode(' · ', $p->getListeFormats())) ?></td>
        <td class="<?= $td ?>"><?php if ($ligne['nbSeances']): ?><a class="text-brand-500 hover:underline" href="index.php?page=seances&amp;programme=<?= (int) $p->getId() ?>"><?= (int) $ligne['nbSeances'] ?> séance(s) · <?= (int) $ligne['duree'] ?> min</a><?php else: ?><?= badge('Aucune séance : invisible', 'orange') ?><?php endif; ?></td>
        <td class="<?= $td ?>"><?= (int) $ligne['nbInscrits'] ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <a href="index.php?page=seance-form&amp;programme=<?= (int) $p->getId() ?>" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">+ Séance</a>
          <?= boutonModifier('index.php?page=programme-edit&id=' . (int) $p->getId()) ?>
          <?= boutonAction('index.php?page=programme-delete', (int) $p->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer « ' . $p->getTitre() . ' », ses séances et ses inscriptions ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 6, 'Aucun programme.') ?>
</div>
