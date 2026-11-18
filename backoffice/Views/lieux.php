<?php
/**
 * Lieux de soin · Module 1 · Rayen Poletti · jointure Lieu ↔ Audit
 * Variables : $lignes (['lieu' => Lieu, 'nbAudits' => int, 'nbValides' => int]), $q, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Lieux de soin') ?>
<?= bandeauFlash($flash) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Lieux de soin', count($lignes) . ' lieu(x) · score calculé à partir des audits validés · Module 1 · Rayen Poletti', 'lieux', $q, 'index.php?page=lieu-form', 'Ajouter un lieu') ?>
  <?= debutTableau(['Lieu', 'Ville', 'Audits (validés)', 'Score', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $l = $ligne['lieu']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="block font-medium text-gray-800 dark:text-white/90"><?= e($l->getNom()) ?></span><span class="text-theme-xs"><?= e($l->getAdresse()) ?></span></td>
        <td class="<?= $td ?>"><?= e($l->getVille()) ?></td>
        <td class="<?= $td ?>"><?= (int) $ligne['nbAudits'] ?> (<?= (int) $ligne['nbValides'] ?>)</td>
        <td class="<?= $td ?>"><?= $l->estVerifie() ? badge(nombre($l->getScoreAccessibilite(), 1) . ' / 5', $l->getScoreAccessibilite() >= 4 ? 'vert' : ($l->getScoreAccessibilite() >= 2.5 ? 'orange' : 'rouge')) : badge('Non vérifié') ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?= boutonModifier('index.php?page=lieu-edit&id=' . (int) $l->getId()) ?>
          <?= boutonAction('index.php?page=lieu-delete', (int) $l->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer « ' . $l->getNom() . ' » et tous ses audits ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 5, $q !== '' ? 'Aucun lieu ne correspond à la recherche.' : 'Aucun lieu pour le moment.') ?>
</div>
