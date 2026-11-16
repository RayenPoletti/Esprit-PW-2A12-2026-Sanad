<!-- Ajouter un professionnel · Module 2 · Aziz Kouki · jointure Professionnel ↔ RendezVous -->
<!-- ===== Fil d’Ariane ===== -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un professionnel</h2>
  <nav>
    <ol class="flex items-center gap-1.5">
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=professionnels">Professionnels <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg></a></li>
      <li class="text-sm text-gray-800 dark:text-white/90">Ajouter un professionnel</li>
    </ol>
  </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Nouveau professionnel de santé</h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 2 · Aziz Kouki · jointure Professionnel ↔ RendezVous</p>
  </div>
  <form action="index.php?page=professionnels" method="post" class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div><label for="nom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nom</label><input id="nom" name="nom" type="text" placeholder="Ben Salah" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="prenom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prénom</label><input id="prenom" name="prenom" type="text" placeholder="Leila" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="specialite" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Spécialité</label><select id="specialite" name="specialite" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40"><option value="">Choisir…</option><option>Cardiologie</option><option>Dermatologie</option><option>Médecine générale</option><option>Ophtalmologie</option><option>Kinésithérapie</option><option>Psychologie</option></select></div>
      <div><label for="telephone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Téléphone</label><input id="telephone" name="telephone" type="tel" placeholder="+216 ..." class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40" /></div>
      <div><label for="lieu" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Lieu d’exercice</label><select id="lieu" name="lieu" class="h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40"><option value="">Choisir…</option><option>Cabinet Ben Salah</option><option>Clinique El Menzah</option><option>Cabinet Gharbi</option><option>Centre Jaziri</option><option>Cabinet Chaouch</option><option>Cabinet Mansour</option><option>Cabinet Ferchichi</option></select></div>
      <fieldset class="sm:col-span-2"><legend class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Validation</legend><div class="grid grid-cols-1 gap-3 sm:grid-cols-2"><label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-400"><input type="checkbox" name="valide[]" value="Profil validé par l’administrateur" class="size-5 rounded-md border-gray-300 text-brand-500 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900" /> Profil validé par l’administrateur</label></div></fieldset>
    </div>
    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600">Enregistrer</button>
      <a href="index.php?page=professionnels" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">Annuler</a>
    </div>
  </form>
</div>
