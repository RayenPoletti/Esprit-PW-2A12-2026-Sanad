<?php
/**
 * Rendez-vous · Module 2 · Aziz Kouki · jointure Professionnel ↔ RendezVous
 * Variables : $lignes (['rdv' => RendezVous, 'patient' => string, 'pro' => string]), $statut (filtre), $csrfToken, $flash
 */
$td = CLASSE_CELLULE;
?>
<?= filAriane('Rendez-vous') ?>
<?= bandeauFlash($flash) ?>
<div class="mb-4 flex flex-wrap gap-2">
  <?php foreach (['' => 'Tous'] + RendezVous::STATUTS as $cle => $libelle): ?>
    <a href="index.php?page=rendezvous<?= $cle !== '' ? '&amp;statut=' . e($cle) : '' ?>" class="rounded-full border px-3 py-1.5 text-theme-sm <?= $statut === $cle ? 'border-brand-500 bg-brand-50 text-brand-600' : 'border-gray-300 text-gray-600 dark:border-gray-700 dark:text-gray-400' ?>"><?= e($libelle) ?></a>
  <?php endforeach; ?>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <?= enteteListe('Rendez-vous', count($lignes) . ' rendez-vous · Module 2 · Aziz Kouki', 'rendezvous', null, 'index.php?page=rendezvous-form', 'Ajouter un rendez-vous') ?>
  <?= debutTableau(['Patient', 'Professionnel', 'Date et heure', 'Motif', 'Passeport transmis', 'Statut', 'Actions']) ?>
    <?php foreach ($lignes as $ligne): $r = $ligne['rdv']; ?>
      <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
        <td class="<?= $td ?>"><span class="font-medium text-gray-800 dark:text-white/90"><?= e($ligne['patient']) ?></span></td>
        <td class="<?= $td ?>"><?= e($ligne['pro']) ?></td>
        <td class="<?= $td ?>"><?= e(dateFr($r->getDateHeure(), true)) ?></td>
        <td class="<?= $td ?>"><?= e($r->getMotif()) ?></td>
        <td class="<?= $td ?>"><?= $r->isPasseportTransmis() ? badge('Oui', 'vert') : badge('Non') ?></td>
        <td class="<?= $td ?>"><?= badge($r->getLibelleStatut(), couleurStatut((string) $r->getStatut())) ?></td>
        <td class="<?= $td ?>"><div class="flex flex-wrap items-center gap-2">
          <?php if ($r->getStatut() === 'en_attente'): ?><?= boutonAction('index.php?page=rendezvous-statut', (int) $r->getId(), $csrfToken, 'Confirmer', 'succes', '', ['statut' => 'confirme']) ?><?php endif; ?>
          <?php if ($r->getStatut() !== 'annule'): ?><?= boutonAction('index.php?page=rendezvous-statut', (int) $r->getId(), $csrfToken, 'Annuler', 'neutre', 'Annuler ce rendez-vous ?', ['statut' => 'annule']) ?><?php endif; ?>
          <?= boutonModifier('index.php?page=rendezvous-edit&id=' . (int) $r->getId()) ?>
          <?= boutonAction('index.php?page=rendezvous-delete', (int) $r->getId(), $csrfToken, 'Supprimer', 'danger', 'Supprimer définitivement ce rendez-vous ?') ?>
        </div></td>
      </tr>
    <?php endforeach; ?>
  <?= finTableau(count($lignes), 7, 'Aucun rendez-vous.') ?>
</div>
