<?php
/**
 * Passeports d'accessibilité · Module commun · jointure Utilisateur 1 — 0..1 Passeport
 * Variables : $passeports (liste de ['passeport' => Passeport, 'membre' => string, 'email' => string]), $flash
 */
$th = 'px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400';
$td = 'px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400';
?>
<?= filAriane('Passeports d’accessibilité', ['Utilisateurs' => 'index.php?page=utilisateurs']) ?>
<?= bandeauFlash($flash) ?>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Passeports d’accessibilité</h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400"><?= count($passeports) ?> passeport(s). Chaque membre crée et modifie le sien depuis le Front Office.</p>
  </div>
  <div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800">
    <table class="min-w-full">
      <thead class="border-b border-gray-100 dark:border-white/5">
        <tr><th class="<?= $th ?>">Membre</th><th class="<?= $th ?>">Besoins déclarés</th><th class="<?= $th ?>">Précisions</th><th class="<?= $th ?>">Partage au cabinet</th><th class="<?= $th ?>">Mis à jour</th></tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-white/5">
        <?php if (empty($passeports)): ?>
          <tr><td colspan="5" class="<?= $td ?> py-10 text-center">Aucun passeport pour le moment.</td></tr>
        <?php endif; ?>
        <?php foreach ($passeports as $ligne): $p = $ligne['passeport']; ?>
          <tr>
            <td class="<?= $td ?>"><span class="block font-medium text-gray-800 dark:text-white/90"><?= e($ligne['membre']) ?></span><span class="text-theme-xs"><?= e($ligne['email']) ?></span></td>
            <td class="<?= $td ?>">
              <div class="flex flex-wrap gap-1.5">
                <?php foreach ($p->getBesoinsDeclares() as $besoin): ?>
                  <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-theme-xs text-brand-600 dark:bg-brand-500/15 dark:text-brand-400"><?= e($besoin) ?></span>
                <?php endforeach; ?>
                <?php if (!$p->getBesoinsDeclares()): ?>—<?php endif; ?>
              </div>
            </td>
            <td class="<?= $td ?> max-w-xs"><?= e($p->getPrecisions() ?: '—') ?></td>
            <td class="<?= $td ?>"><?= $p->isPartageAutorise() ? 'Oui' : 'Non' ?></td>
            <td class="<?= $td ?>"><?= e($p->getDateMiseAJour() ? date('d/m/Y', strtotime($p->getDateMiseAJour())) : '—') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
