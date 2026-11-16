<!-- Commentaires · Module 4 · Houssem Hafsi · jointure Publication ↔ Commentaire -->
<!-- ===== Fil d’Ariane ===== -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Commentaires</h2>
  <nav>
    <ol class="flex items-center gap-1.5">
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      
      <li class="text-sm text-gray-800 dark:text-white/90">Commentaires</li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Commentaires des publications</h3>
      <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 4 · Houssem Hafsi · jointure Publication ↔ Commentaire</p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <form action="index.php" method="get" class="w-full sm:w-64">
        <input type="hidden" name="page" value="commentaires" />
        <input type="text" name="q" placeholder="Rechercher…" aria-label="Rechercher" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" />
      </form>
      
    </div>
  </div>
  <div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800">
    <table class="min-w-full">
      <thead class="border-b border-gray-100 dark:border-white/5">
        <tr><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Auteur</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Publication</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Commentaire</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Date</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Statut</th><th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-white/5">
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-700 text-theme-xs font-semibold text-white">HM</span><div><span class="block text-theme-sm font-medium text-gray-800 dark:text-white/90">Hédi Mejri</span></div></div></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Conseils salle de bain</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Une barre d’appui près des toilettes a tout changé chez moi.</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">15/11/2026</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500">Publié</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><button type="button" class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Masquer</button></div></td></tr>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-700 text-theme-xs font-semibold text-white">FR</span><div><span class="block text-theme-sm font-medium text-gray-800 dark:text-white/90">Fatma Riahi</span></div></div></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Conseils salle de bain</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Un siège de douche pliant ! Il y en a un en don dans la boutique.</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">15/11/2026</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500">Publié</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><button type="button" class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Masquer</button></div></td></tr>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2"><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex items-center gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-700 text-theme-xs font-semibold text-white">AB</span><div><span class="block text-theme-sm font-medium text-gray-800 dark:text-white/90">Amira Ben Ali</span></div></div></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Rampe installée à Béja</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">Bravo, ça montre que les audits servent vraiment.</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">14/11/2026</td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500">Publié</span></td><td class="px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400"><div class="flex flex-wrap items-center gap-2"><button type="button" class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-theme-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Masquer</button></div></td></tr>
      </tbody>
    </table>
  </div>
</div>

