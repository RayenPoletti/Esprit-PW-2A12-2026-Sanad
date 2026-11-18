<?php
/**
 * Commandes · Module 5 · Karim Bousetta · Commande 0..* — 1..* Produit (LigneCommande)
 * Variables : $lignes (['commande' => Commande, 'client', 'email', 'articles' => lignes de commande]), $statut, $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
$suivant = ['en_attente' => ['preparation' => 'Préparer'], 'preparation' => ['livree' => 'Marquer livrée'], 'livree' => [], 'annulee' => []];
?>
<?= filAriane('Commandes') ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <?php foreach (['' => 'Toutes'] + Commande::STATUTS as $cle => $libelle): ?>
    <a href="index.php?page=commandes<?= $cle !== '' ? '&amp;statut=' . e($cle) : '' ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $statut === $cle ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($libelle) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Commandes de la boutique solidaire', count($lignes) . ' commande(s) · passées par les membres sur le site · Module 5 · Karim Bousetta', 'commandes') ?>
  <?= debutTableau(['N°', 'Client', 'Articles (LigneCommande)', 'Total', 'Paiement', 'Statut', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $c = $ligne['commande']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= (int) $c->getId() ?></span><br><span class="text-theme-xs"><?= e(dateFr($c->getDateCommande(), true)) ?></span></td>
        <td class="<?= $td ?>"><?= e($ligne['client']) ?><br><span class="text-theme-xs"><?= e($ligne['email']) ?></span></td>
        <td class="<?= $td ?>"><?php foreach ($ligne['articles'] as $a): ?><?= (int) $a['quantite'] ?> × <?= e($a['nom']) ?> <span class="text-theme-xs">(<?= e(nombre((float) $a['prixUnitaire'])) ?> DT)</span><br><?php endforeach; ?></td>
        <td class="<?= $td ?>"><?= e(nombre($c->getMontantTotal())) ?> DT</td>
        <td class="<?= $td ?>"><?= $c->estPayeeEnPoints() ? badge((int) $c->getPointsUtilises() . ' points', 'bleu') : badge('Dinars') ?></td>
        <td class="<?= $td ?>"><?= badge($c->getLibelleStatut(), couleurStatut((string) $c->getStatut())) ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?php foreach ($suivant[$c->getStatut()] ?? [] as $s => $libelle): ?><?= boutonAction('index.php?page=commande-statut', (int) $c->getId(), $csrfToken, $libelle, 'succes', '', ['statut' => $s]) ?><?php endforeach; ?>
          <?php if (in_array($c->getStatut(), ['en_attente', 'preparation'], true)): ?><?= boutonAction('index.php?page=commande-statut', (int) $c->getId(), $csrfToken, 'Annuler', 'neutre', 'Annuler la commande ? Le stock et les points seront rendus.', ['statut' => 'annulee']) ?><?php endif; ?>
          <?php if (in_array($c->getStatut(), ['livree', 'annulee'], true)): ?><?= boutonAction('index.php?page=commande-delete', (int) $c->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer définitivement cette commande ?') ?><?php endif; ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 7, 'Aucune commande.') ?>
</div>
