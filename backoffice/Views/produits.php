<?php
/**
 * Liste des produits · Module 5 · Karim Bousetta · jointure Produit ↔ Commande
 * Variables : $produits (Produit[]), $q (recherche), $csrfToken, $flash
 * Toutes les valeurs venant de la base sont échappées avec e() (protection XSS).
 */
$th  = 'px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400';
$td  = 'px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400';
$btn = 'inline-flex items-center rounded-lg border px-3 py-1.5 text-theme-xs font-medium shadow-theme-xs';
$nb  = fn(?float $n): string => rtrim(rtrim(number_format((float) $n, 2, ',', ' '), '0'), ',');
?>
<?= filAriane('Produits') ?>
<?= bandeauFlash($flash) ?>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Matériel de la boutique solidaire</h3>
      <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400"><?= count($produits) ?> produit(s)<?= $q !== '' ? ' pour « ' . e($q) . ' »' : '' ?> · Module 5 · Karim Bousetta</p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <form action="index.php" method="get" class="w-full sm:w-64">
        <input type="hidden" name="page" value="produits" />
        <input type="search" name="q" value="<?= e($q) ?>" maxlength="100" placeholder="Rechercher…" aria-label="Rechercher un produit" class="<?= CLASSE_CHAMP ?>" />
      </form>
      <a href="index.php?page=produit-form" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 4.167v11.666M4.167 10h11.666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>Ajouter un produit</a>
    </div>
  </div>
  <div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800">
    <table class="min-w-full">
      <thead class="border-b border-gray-100 dark:border-white/5">
        <tr><th class="<?= $th ?>">Produit</th><th class="<?= $th ?>">État</th><th class="<?= $th ?>">Prix</th><th class="<?= $th ?>">Prix en points</th><th class="<?= $th ?>">Stock</th><th class="<?= $th ?>">Poids</th><th class="<?= $th ?>">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-white/5">
        <?php if (empty($produits)): ?>
          <tr><td colspan="7" class="<?= $td ?> py-10 text-center"><?= $q !== '' ? 'Aucun produit ne correspond à la recherche.' : 'Aucun produit pour le moment. Cliquez sur « Ajouter un produit ».' ?></td></tr>
        <?php endif; ?>
        <?php foreach ($produits as $p): ?>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
            <td class="<?= $td ?>">
              <span class="block font-medium text-gray-800 dark:text-white/90"><?= e($p->getNom()) ?></span>
              <span class="block max-w-xs truncate text-theme-xs"><?= e($p->getDescription()) ?></span>
            </td>
            <td class="<?= $td ?>">
              <?php if ($p->estDon()): ?>
                <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 text-theme-xs font-medium text-amber-600 dark:bg-amber-500/15 dark:text-orange-400">Don</span>
              <?php else: ?>
                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-theme-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500"><?= e($p->getEtat()) ?></span>
              <?php endif; ?>
            </td>
            <td class="<?= $td ?>"><?= $p->getPrix() > 0 ? e($nb($p->getPrix())) . ' DT' : 'Gratuit' ?></td>
            <td class="<?= $td ?>"><?= $p->getPrix() > 0 ? (int) $p->getPrixPoints() . ' pts' : '—' ?></td>
            <td class="<?= $td ?>"><?= (int) $p->getStock() === 0 ? '<span class="text-red-500">Épuisé</span>' : (int) $p->getStock() ?></td>
            <td class="<?= $td ?>"><?= $p->getPoidsKg() !== null ? e($nb($p->getPoidsKg())) . ' kg' : '—' ?></td>
            <td class="<?= $td ?>">
              <div class="flex flex-wrap items-center gap-2">
                <a href="index.php?page=produit-edit&amp;id=<?= (int) $p->getId() ?>" class="<?= $btn ?> border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Modifier</a>
                <!-- Suppression : POST + jeton CSRF (jamais un simple lien GET) -->
                <form action="index.php?page=produit-delete" method="post" data-confirm="Supprimer « <?= e($p->getNom()) ?> » de la boutique ?">
                  <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
                  <input type="hidden" name="id" value="<?= (int) $p->getId() ?>" />
                  <button type="submit" class="<?= $btn ?> border-red-200 bg-white text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Supprimer</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<p class="mt-4 text-theme-sm text-gray-500 dark:text-gray-400"><?= Produit::POINTS_PAR_DINAR ?> points solidaires = 1 DT.</p>
