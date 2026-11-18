<?php
/**
 * Audits d'accessibilité · Module 1 · Rayen Poletti · jointure Lieu ↔ Audit
 * Variables : $lignes (['audit' => Audit, 'lieu' => string, 'ambassadeur' => string]), $statut (filtre), $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Audits d’accessibilité') ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <?php foreach (['' => 'Tous'] + Audit::STATUTS as $cle => $libelle): ?>
    <a href="index.php?page=audits<?= $cle !== '' ? '&amp;statut=' . e($cle) : '' ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $statut === $cle ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($libelle) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Audits reçus', count($lignes) . ' audit(s) · valider un audit donne ' . Audit::POINTS_AUDIT . ' points à l’ambassadeur', 'audits', null, 'index.php?page=audit-form', 'Ajouter un audit') ?>
  <?= debutTableau(['Lieu', 'Ambassadeur', 'Date', 'Critères', 'Statut', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $a = $ligne['audit']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="block font-medium text-gray-800 dark:text-white/90"><?= e($ligne['lieu']) ?></span><span class="block max-w-xs truncate text-theme-xs"><?= e($a->getCommentaire()) ?></span></td>
        <td class="<?= $td ?>"><?= e($ligne['ambassadeur']) ?></td>
        <td class="<?= $td ?>"><?= e(dateFr($a->getDateAudit())) ?></td>
        <td class="<?= $td ?>"><?= (int) $a->nbCriteresOK() ?> / <?= count(Audit::CRITERES) ?></td>
        <td class="<?= $td ?>"><?= badge($a->getLibelleStatut(), couleurStatut((string) $a->getStatut())) ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?php if ($a->getStatut() !== 'valide'): ?><?= boutonAction('index.php?page=audit-statut', (int) $a->getId(), $csrfToken, 'Valider', 'succes', '', ['statut' => 'valide']) ?><?php endif; ?>
          <?php if ($a->getStatut() !== 'refuse'): ?><?= boutonAction('index.php?page=audit-statut', (int) $a->getId(), $csrfToken, 'Refuser', 'neutre', 'Refuser cet audit ?', ['statut' => 'refuse']) ?><?php endif; ?>
          <?= boutonModifier('index.php?page=audit-edit&id=' . (int) $a->getId()) ?>
          <?= boutonAction('index.php?page=audit-delete', (int) $a->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer définitivement cet audit ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 6, 'Aucun audit.') ?>
</div>
