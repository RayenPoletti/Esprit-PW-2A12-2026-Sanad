<?php
/**
 * Publications · Module 4 · Houssem Hafsi · composition Publication ◆— Commentaire
 * Variables : $lignes (['publication' => Publication, 'auteur', 'nbCommentaires']), $statut, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Publications') ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <?php foreach (['' => 'Toutes'] + Publication::STATUTS as $cle => $libelle): ?>
    <a href="index.php?page=publications<?= $cle !== '' ? '&amp;statut=' . e($cle) : '' ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $statut === $cle ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($libelle) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Publications de la communauté', count($lignes) . ' publication(s) · les publications « signalées » attendent votre décision · Module 4 · Houssem Hafsi', 'publications', null, 'index.php?page=publication-form', 'Ajouter une publication') ?>
  <?= debutTableau(['Auteur', 'Publication', 'Date', 'Commentaires', 'Statut', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $p = $ligne['publication']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= e($ligne['auteur']) ?></span></td>
        <td class="<?= $td ?>"><span class="block max-w-sm truncate"><?= e($p->getContenu()) ?></span></td>
        <td class="<?= $td ?>"><?= e(dateFr($p->getDatePublication(), true)) ?></td>
        <td class="<?= $td ?>"><?= (int) $ligne['nbCommentaires'] ?></td>
        <td class="<?= $td ?>"><?= badge($p->getLibelleStatut(), couleurStatut((string) $p->getStatut())) ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?php if ($p->getStatut() !== 'publie'): ?><?= boutonAction('index.php?page=publication-statut', (int) $p->getId(), $csrfToken, 'Publier', 'succes', '', ['statut' => 'publie']) ?><?php endif; ?>
          <?php if ($p->getStatut() !== 'masque'): ?><?= boutonAction('index.php?page=publication-statut', (int) $p->getId(), $csrfToken, 'Masquer', 'neutre', '', ['statut' => 'masque']) ?><?php endif; ?>
          <?= boutonModifier('index.php?page=publication-edit&id=' . (int) $p->getId()) ?>
          <?= boutonAction('index.php?page=publication-delete', (int) $p->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer cette publication et ses commentaires ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 6, 'Aucune publication.') ?>
</div>
