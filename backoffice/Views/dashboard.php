<?php
/**
 * Tableau de bord du Back Office.
 * Variables : $parRole, $total, $bloques, $passeports, $derniers (Utilisateur[]), $stats, $auditsParMois, $villes, $activite, $flash
 * Tous les chiffres viennent de la base de données.
 */
$carte = 'rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3';
?>
<?= filAriane('Tableau de bord') ?>
<?= bandeauFlash($flash) ?>

<!-- ===== Module commun : comptes et rôles (données réelles) ===== -->
<div class="mb-4 grid grid-cols-12 gap-4 md:mb-6 md:gap-6">
  <div class="col-span-12 xl:col-span-7 <?= $carte ?> p-5 sm:p-6">
    <div class="mb-5 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Comptes par rôle</h3>
      <a href="index.php?page=utilisateurs" class="text-theme-sm font-medium text-brand-500 hover:text-brand-600">Gérer les comptes</a>
    </div>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
      <div><span class="block text-theme-xs text-gray-500 dark:text-gray-400">Comptes</span><span class="text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $total ?></span></div>
      <div><span class="block text-theme-xs text-gray-500 dark:text-gray-400">Ambassadeurs</span><span class="text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $parRole['ambassadeur'] ?></span></div>
      <div><span class="block text-theme-xs text-gray-500 dark:text-gray-400">Passeports</span><span class="text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $passeports ?></span></div>
      <div><span class="block text-theme-xs text-gray-500 dark:text-gray-400">Comptes bloqués</span><span class="text-title-sm font-bold text-red-500"><?= (int) $bloques ?></span></div>
    </div>
    <div class="mt-6 space-y-4">
      <?php foreach (Utilisateur::LIBELLES_ROLES as $cle => $libelle): $pct = $total ? round($parRole[$cle] * 100 / $total) : 0; ?>
        <div>
          <div class="mb-1.5 flex items-center justify-between text-theme-sm"><span class="text-gray-700 dark:text-gray-300"><?= e($libelle) ?></span><span class="font-medium text-gray-800 dark:text-white/90"><?= (int) $parRole[$cle] ?></span></div>
          <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-800"><div class="h-2 rounded-full bg-brand-500" style="width:<?= (int) $pct ?>%"></div></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-span-12 xl:col-span-5 <?= $carte ?> p-5 sm:p-6">
    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Dernières inscriptions</h3>
    <ul class="divide-y divide-gray-100 dark:divide-white/5">
      <?php foreach ($derniers as $u): ?>
        <li class="flex items-center gap-3 py-3">
          <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-500 text-theme-xs font-semibold text-white"><?= e(initiales($u->getPrenom(), $u->getNom())) ?></span>
          <span class="min-w-0 flex-1"><span class="block truncate text-theme-sm font-medium text-gray-800 dark:text-white/90"><?= e($u->getNomComplet()) ?></span><span class="block truncate text-theme-xs text-gray-500 dark:text-gray-400"><?= e($u->getEmail()) ?></span></span>
          <?= badgeRole($u->getRole(), $u->getLibelleRole()) ?>
        </li>
      <?php endforeach; ?>
      <?php if (!$derniers): ?><li class="py-3 text-theme-sm text-gray-500">Aucun compte.</li><?php endif; ?>
    </ul>
  </div>
</div>

<!-- ===== Modules 1 à 5 (données réelles) ===== -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
  <a href="index.php?page=lieux" class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-brand-300 dark:border-gray-800 dark:bg-white/3 md:p-6">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
      <svg class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 3.75 4.75 6.5v5.25c0 4.2 3 7.6 7.25 8.5 4.25-.9 7.25-4.3 7.25-8.5V6.5L12 3.75Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/><path d="m8.75 12 2.25 2.25 4.25-4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    </div>
    <div class="mt-5">
      <span class="text-sm text-gray-500 dark:text-gray-400">Lieux vérifiés sur place</span>
      <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $stats['lieuxVerifies'] ?> / <?= (int) $stats['lieux'] ?></h4>
      <div class="mt-3"><?= badge((int) $stats['auditsValides'] . ' audit(s) validé(s)', 'vert') ?></div>
    </div>
  </a>
  <a href="index.php?page=rendezvous&amp;statut=en_attente" class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-brand-300 dark:border-gray-800 dark:bg-white/3 md:p-6">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
      <svg class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2c.414 0 .75.336.75.75v1h6.5v-1a.75.75 0 0 1 1.5 0v1h1.75c1.243 0 2.25 1.007 2.25 2.25v13c0 1.243-1.007 2.25-2.25 2.25h-13A2.25 2.25 0 0 1 3.25 19V6c0-1.243 1.007-2.25 2.25-2.25h1.75v-1C7.25 2.336 7.586 2 8 2Zm0 3.25H5.5a.75.75 0 0 0-.75.75v2.25h14.5V6a.75.75 0 0 0-.75-.75H8Zm11.25 4.5H4.75V19c0 .414.336.75.75.75h13a.75.75 0 0 0 .75-.75V9.75Z" fill="currentColor"/></svg>
    </div>
    <div class="mt-5">
      <span class="text-sm text-gray-500 dark:text-gray-400">Rendez-vous à venir</span>
      <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $stats['rdvAVenir'] ?></h4>
      <div class="mt-3"><?= badge((int) $stats['rdvAttente'] . ' à confirmer', 'orange') ?></div>
    </div>
  </a>
  <a href="index.php?page=commandes" class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-brand-300 dark:border-gray-800 dark:bg-white/3 md:p-6">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
      <svg class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5.75 8.25h12.5l-.9 11.1a1.5 1.5 0 0 1-1.5 1.4H8.15a1.5 1.5 0 0 1-1.5-1.4l-.9-11.1Zm3.25 0V7a3 3 0 1 1 6 0v1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    </div>
    <div class="mt-5">
      <span class="text-sm text-gray-500 dark:text-gray-400">Commandes solidaires</span>
      <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $stats['commandes'] ?></h4>
      <div class="mt-3"><?= badge((int) $stats['commandesAttente'] . ' en attente · ' . (int) $stats['commandesPrepa'] . ' en préparation', 'bleu') ?></div>
    </div>
  </a>
  <a href="index.php?page=audits&amp;statut=en_attente" class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-brand-300 dark:border-gray-800 dark:bg-white/3 md:p-6">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
      <svg class="size-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4.75 6.5c0-.966.784-1.75 1.75-1.75h11c.966 0 1.75.784 1.75 1.75v8c0 .966-.784 1.75-1.75 1.75H10l-4.25 3.5v-3.5H6.5a1.75 1.75 0 0 1-1.75-1.75v-8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/></svg>
    </div>
    <div class="mt-5">
      <span class="text-sm text-gray-500 dark:text-gray-400">Actions de modération</span>
      <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) ($stats['auditsAttente'] + $stats['signalements']) ?></h4>
      <div class="mt-3"><?= badge((int) $stats['auditsAttente'] . ' audit(s) · ' . (int) $stats['signalements'] . ' signalement(s)', 'rouge') ?></div>
    </div>
  </a>
</div>

<div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6">
  <div class="col-span-12 xl:col-span-8 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3 px-5 pt-5 sm:px-6 sm:pt-6">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Audits validés par mois</h3>
      <span class="text-theme-sm text-gray-500 dark:text-gray-400"><?= date('Y') ?></span>
    </div>
    <!-- Les valeurs du graphique sont lues par dashboard_scripts.php dans l'attribut data-valeurs -->
    <div id="chartAudits" data-valeurs="<?= e(json_encode($auditsParMois)) ?>" class="-ml-4 min-w-[600px] xl:min-w-full pl-2"></div>
  </div>
  <div class="col-span-12 xl:col-span-4 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3 p-5 sm:p-6">
    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90">Lieux vérifiés par ville</h3>
    <div class="space-y-5">
      <?php foreach ($villes as $v): $pct = $v['total'] ? round($v['verifies'] * 100 / $v['total']) : 0; ?>
        <div>
          <div class="mb-1.5 flex items-center justify-between text-theme-sm"><span class="text-gray-700 dark:text-gray-300"><?= e($v['ville']) ?></span><span class="font-medium text-gray-800 dark:text-white/90"><?= (int) $v['verifies'] ?>/<?= (int) $v['total'] ?></span></div>
          <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-800"><div class="h-2 rounded-full bg-brand-500" style="width:<?= (int) $pct ?>%"></div></div>
        </div>
      <?php endforeach; ?>
      <?php if (!$villes): ?><p class="text-theme-sm text-gray-500">Aucun lieu.</p><?php endif; ?>
    </div>
  </div>
  <div class="col-span-12 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3 p-5 sm:p-6">
    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Activité récente</h3>
    <ul class="space-y-3">
      <?php foreach ($activite as $act): ?>
        <li class="flex gap-3"><span class="w-24 shrink-0 text-theme-xs text-gray-500 dark:text-gray-400"><?= e(dateFr($act['date'], true)) ?></span><span class="text-theme-sm text-gray-700 dark:text-gray-300"><?= e($act['texte']) ?></span></li>
      <?php endforeach; ?>
      <?php if (!$activite): ?><li class="text-theme-sm text-gray-500">Aucune activité.</li><?php endif; ?>
    </ul>
  </div>
</div>
