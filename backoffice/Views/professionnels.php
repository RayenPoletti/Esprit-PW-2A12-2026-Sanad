<?php
/**
 * Professionnels · Module 2 · Aziz Kouki · jointure Professionnel ↔ RendezVous
 * Variables : $lignes (['pro' => Professionnel, 'lieu' => string, 'nbRdv' => int]), $q, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Professionnels') ?>
<?= bandeauFlash($flash) ?>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Professionnels de santé', count($lignes) . ' professionnel(s) · seuls les validés apparaissent sur le site · Module 2 · Aziz Kouki', 'professionnels', $q, 'index.php?page=professionnel-form', 'Ajouter un professionnel') ?>
  <?= debutTableau(['Professionnel', 'Spécialité', 'Lieu d’exercice', 'Téléphone', 'Rendez-vous', 'Validation', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $p = $ligne['pro']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= e($p->getNomComplet()) ?></span></td>
        <td class="<?= $td ?>"><?= e($p->getSpecialite()) ?></td>
        <td class="<?= $td ?>"><?= e($ligne['lieu']) ?></td>
        <td class="<?= $td ?>"><?= e($p->getTelephone()) ?></td>
        <td class="<?= $td ?>"><?= (int) $ligne['nbRdv'] ?></td>
        <td class="<?= $td ?>"><?= $p->isValide() ? badge('Validé', 'vert') : badge('En attente', 'orange') ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?= boutonAction('index.php?page=professionnel-toggle', (int) $p->getId(), $csrfToken, $p->isValide() ? 'Retirer la validation' : 'Valider', $p->isValide() ? 'neutre' : 'succes') ?>
          <?= boutonModifier('index.php?page=professionnel-edit&id=' . (int) $p->getId()) ?>
          <?= boutonAction('index.php?page=professionnel-delete', (int) $p->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer ' . $p->getNomComplet() . ' ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 7, 'Aucun professionnel.') ?>
</div>
