<!-- Commandes · Module 5 · Karim Bousetta · jointure Produit ↔ Commande -->
<!-- ===== Fil d’Ariane ===== -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Commandes</h2>
  <nav>
    <ol class="flex items-center gap-1.5">
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      
      <li class="text-sm text-gray-800 dark:text-white/90">Commandes</li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Commandes de la boutique solidaire</h3>
      <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 5 · Karim Bousetta · jointure Produit ↔ Commande</p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <form action="index.php" method="get" class="w-full sm:w-64">
        <input type="hidden" name="page" value="commandes" />
        <input type="text" name="q" placeholder="Rechercher…" aria-label="Rechercher" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" />
      </form>
      
    </div>
  </div>
  <div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800">
    <table class="min-w-full">
      <thead class="border-b border-gray-100 dark:border-white/5">
        <tr><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">N°</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Client</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Articles (LigneCommande)</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Total</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Points utilisés</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Statut</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-white/5">
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="font-medium text-gray-800 dark:text-white/90">CMD-1041</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Salma Kefi</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Déambulateur 4 roues ×1</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">120 DT</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">0</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500">Expédiée</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><a href="#" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Voir</a></div></td></tr>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="font-medium text-gray-800 dark:text-white/90">CMD-1042</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Fatma Riahi</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Loupe électronique ×1</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">70 DT</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">200</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-orange-400">En préparation</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><a href="#" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Voir</a><button type="button" class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Annuler</button></div></td></tr>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="font-medium text-gray-800 dark:text-white/90">CMD-1043</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Amira Ben Ali</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Coussin anti-escarres ×1, Siège de douche ×1</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">45 DT</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">0</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-orange-400">En préparation</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><a href="#" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Voir</a><button type="button" class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Annuler</button></div></td></tr>
      </tbody>
    </table>
  </div>
</div>
<p class="mt-4 text-theme-sm text-gray-500 dark:text-gray-400">Jointure Commande 0..* — 1..* Produit, avec la classe d’association LigneCommande (quantité, prix unitaire).</p>
