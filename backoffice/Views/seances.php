<?php
/**
 * Séances · Module 3 · Rayen Marzougi · composition Programme ◆— Séance
 * Variables : $lignes (['seance' => Seance, 'programme' => string]), $programmes, $filtre, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Séances', ['Programmes' => 'index.php?page=programmes']) ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <a href="index.php?page=seances" class="rounded-full border px-3 py-1.5 text-theme-sm <?= !$filtre ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>">Tous les programmes</a>
  <?php foreach ($programmes as $id => $titre): ?>
    <a href="index.php?page=seances&amp;programme=<?= (int) $id ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $filtre === (int) $id ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($titre) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Séances', count($lignes) . ' séance(s) · Module 3 · Rayen Marzougi', 'seances', null, 'index.php?page=seance-form' . ($filtre ? '&programme=' . $filtre : ''), 'Ajouter une séance') ?>
  <?= debutTableau(['Séance', 'Programme', 'Ordre', 'Durée', 'Formats', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $s = $ligne['seance']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= e($s->getTitre()) ?></span></td>
        <td class="<?= $td ?>"><?= e($ligne['programme']) ?></td>
        <td class="<?= $td ?>"><?= (int) $s->getOrdre() ?></td>
        <td class="<?= $td ?>"><?= (int) $s->getDureeMin() ?> min</td>
        <td class="<?= $td ?>"><div class="flex flex-wrap gap-1"><?= $s->getUrlVideo() ? badge('Vidéo', 'bleu') : '' ?><?= $s->getUrlAudio() ? badge('Audio', 'vert') : '' ?><?= !$s->getUrlVideo() && !$s->getUrlAudio() ? badge('Texte seul') : '' ?></div></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?= boutonModifier('index.php?page=seance-edit&id=' . (int) $s->getId()) ?>
          <?= boutonAction('index.php?page=seance-delete', (int) $s->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer la séance « ' . $s->getTitre() . ' » ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 6, 'Aucune séance.') ?>
</div>
