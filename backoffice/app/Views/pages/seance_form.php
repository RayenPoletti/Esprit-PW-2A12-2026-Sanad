<!-- Ajouter une séance · Module 3 · Rayen Marzougi · jointure Programme ↔ Séance -->
<!-- ===== Fil d’Ariane ===== -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter une séance</h2>
  <nav>
    <ol class="flex items-center gap-1.5">
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=seances">Séances <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li class="text-sm text-gray-800 dark:text-white/90">Ajouter une séance</li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Nouvelle séance</h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 3 · Rayen Marzougi · jointure Programme ↔ Séance</p>
  </div>
  <form action="index.php?page=seances" method="post" class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div><label for="programme" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Programme</label><select id="programme" name="programme" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40"><option value="">Choisir…</option><option>Yoga sur chaise</option><option>Gestion du stress</option><option>Marche douce seniors</option><option>Nutrition et diabète</option><option>Méditation guidée</option></select></div>
      <div><label for="titre" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Titre</label><input id="titre" name="titre" type="text" placeholder="Respirer assis" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="ordre" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ordre</label><input id="ordre" name="ordre" type="number" placeholder="1" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="dureeMin" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Durée (minutes)</label><input id="dureeMin" name="dureeMin" type="number" placeholder="15" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="urlVideo" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lien de la vidéo</label><input id="urlVideo" name="urlVideo" type="url" placeholder="https://" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="urlAudio" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lien de l’audio</label><input id="urlAudio" name="urlAudio" type="url" placeholder="https://" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600">Enregistrer</button>
      <a href="index.php?page=seances" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">Annuler</a>
    </div>
  </form>
</div>
