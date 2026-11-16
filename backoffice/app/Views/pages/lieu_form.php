<!-- Ajouter un lieu · Module 1 · Rayen Poletti · jointure Lieu ↔ Audit -->
<!-- ===== Fil d’Ariane ===== -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un lieu</h2>
  <nav>
    <ol class="flex items-center gap-1.5">
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=lieux">Lieux de soin <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li class="text-sm text-gray-800 dark:text-white/90">Ajouter un lieu</li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Nouveau lieu de soin</h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 1 · Rayen Poletti · jointure Lieu ↔ Audit</p>
  </div>
  <form action="index.php?page=lieux" method="post" class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div><label for="nom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nom du lieu</label><input id="nom" name="nom" type="text" placeholder="Cabinet Ben Salah" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="adresse" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Adresse</label><input id="adresse" name="adresse" type="text" placeholder="12 rue de Marseille" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="ville" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ville</label><input id="ville" name="ville" type="text" placeholder="Tunis" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="latitude" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Latitude</label><input id="latitude" name="latitude" type="text" placeholder="36.8008" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="longitude" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Longitude</label><input id="longitude" name="longitude" type="text" placeholder="10.1800" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600">Enregistrer</button>
      <a href="index.php?page=lieux" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">Annuler</a>
    </div>
  </form>
</div>
