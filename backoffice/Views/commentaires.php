<?php
/**
 * Commentaires · Module 4 · Houssem Hafsi · composition Publication ◆— Commentaire
 * Variables : $lignes (['commentaire' => Commentaire, 'auteur', 'publication']), $statut, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Commentaires', ['Publications' => 'index.php?page=publications']) ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <?php foreach (['' => 'Tous'] + Commentaire::STATUTS as $cle => $libelle): ?>
    <a href="index.php?page=commentaires<?= $cle !== '' ? '&amp;statut=' . e($cle) : '' ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $statut === $cle ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($libelle) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Commentaires', count($lignes) . ' commentaire(s) · écrits par les membres sur le site · Module 4 · Houssem Hafsi', 'commentaires') ?>
  <?= debutTableau(['Auteur', 'Commentaire', 'Sur la publication', 'Date', 'Statut', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $p = $ligne['commentaire']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= e($ligne['auteur']) ?></span></td>
        <td class="<?= $td ?>"><span class="block max-w-sm truncate"><?= e($p->getContenu()) ?></span></td>
        <td class="<?= $td ?>"><em><?= e($ligne['publication']) ?></em></td>
        <td class="<?= $td ?>"><?= e(dateFr($p->getDateCommentaire(), true)) ?></td>
        <td class="<?= $td ?>"><?= badge($p->getLibelleStatut(), couleurStatut((string) $p->getStatut())) ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?php if ($p->getStatut() !== 'publie'): ?><?= boutonAction('index.php?page=commentaire-statut', (int) $p->getId(), $csrfToken, 'Publier', 'succes', '', ['statut' => 'publie']) ?><?php endif; ?>
          <?php if ($p->getStatut() !== 'masque'): ?><?= boutonAction('index.php?page=commentaire-statut', (int) $p->getId(), $csrfToken, 'Masquer', 'neutre', '', ['statut' => 'masque']) ?><?php endif; ?>
          <?= boutonModifier('index.php?page=commentaire-edit&id=' . (int) $p->getId()) ?>
          <?= boutonAction('index.php?page=commentaire-delete', (int) $p->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer ce commentaire ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 6, 'Aucun commentaire.') ?>
</div>
