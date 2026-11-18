<?php
/**
 * Liste des utilisateurs · Module commun (toute l'équipe)
 * Variables : $utilisateurs (Utilisateur[]), $filtres, $total, $pageNum, $nbPages, $parRole, $moi, $csrfToken, $flash
 * Toutes les valeurs venant de la base sont échappées avec e() (protection XSS).
 */
$th = 'px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400';
$td = 'px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400';
$btn = 'inline-flex items-center rounded-lg border px-3 py-1.5 text-theme-xs font-medium shadow-theme-xs';
$lienPage = function (int $p) use ($filtres): string {
    return 'index.php?' . http_build_query(['page' => 'utilisateurs', 'q' => $filtres['q'], 'role' => $filtres['role'], 'statut' => $filtres['statut'], 'p' => $p]);
};
?>
<?= filAriane('Utilisateurs') ?>
<?= bandeauFlash($flash) ?>

<!-- Répartition par rôle (gestion des rôles) -->
<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
  <?php foreach (Utilisateur::LIBELLES_ROLES as $cle => $libelle): ?>
    <a href="index.php?page=utilisateurs&amp;role=<?= e($cle) ?>" class="rounded-2xl border border-gray-200 bg-white p-4 transition hover:border-brand-300 dark:border-gray-800 dark:bg-white/3">
      <span class="block text-theme-xs text-gray-500 dark:text-gray-400"><?= e($libelle) ?>s</span>
      <span class="mt-1 block text-title-sm font-bold text-gray-800 dark:text-white/90"><?= (int) $parRole[$cle] ?></span>
    </a>
  <?php endforeach; ?>
</div>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="flex flex-col gap-4 px-5 py-4 sm:px-6 xl:flex-row xl:items-center xl:justify-between">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Liste des utilisateurs</h3>
      <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400"><?= (int) $total ?> compte(s) trouvé(s)</p>
    </div>
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
      <!-- Recherche + filtres (GET : l'URL peut être partagée) -->
      <form action="index.php" method="get" class="flex flex-col gap-3 sm:flex-row">
        <input type="hidden" name="page" value="utilisateurs" />
        <input type="search" name="q" value="<?= e($filtres['q']) ?>" maxlength="100" placeholder="Nom, e-mail, ville…" aria-label="Rechercher" class="<?= CLASSE_CHAMP ?> sm:w-56" />
        <select name="role" aria-label="Filtrer par rôle" class="<?= CLASSE_CHAMP ?> sm:w-40">
          <option value="">Tous les rôles</option>
          <?php foreach (Utilisateur::LIBELLES_ROLES as $cle => $libelle): ?>
            <option value="<?= e($cle) ?>" <?= $filtres['role'] === $cle ? 'selected' : '' ?>><?= e($libelle) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="statut" aria-label="Filtrer par statut" class="<?= CLASSE_CHAMP ?> sm:w-36">
          <option value="">Tous les statuts</option>
          <option value="actif" <?= $filtres['statut'] === 'actif' ? 'selected' : '' ?>>Actifs</option>
          <option value="bloque" <?= $filtres['statut'] === 'bloque' ? 'selected' : '' ?>>Bloqués</option>
        </select>
        <button type="submit" class="<?= $btn ?> justify-center border-gray-300 bg-white py-3 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Filtrer</button>
      </form>
      <a href="index.php?page=utilisateur-form" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 4.167v11.666M4.167 10h11.666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>Ajouter</a>
    </div>
  </div>

  <div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800">
    <table class="min-w-full">
      <thead class="border-b border-gray-100 dark:border-white/5">
        <tr><th class="<?= $th ?>">Utilisateur</th><th class="<?= $th ?>">Rôle</th><th class="<?= $th ?>">Ville</th><th class="<?= $th ?>">Points</th><th class="<?= $th ?>">Inscrit le</th><th class="<?= $th ?>">Statut</th><th class="<?= $th ?>">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-white/5">
        <?php if (empty($utilisateurs)): ?>
          <tr><td colspan="7" class="<?= $td ?> py-10 text-center">Aucun utilisateur ne correspond à la recherche.</td></tr>
        <?php endif; ?>
        <?php foreach ($utilisateurs as $u): ?>
          <tr class="transition hover:bg-gray-50/50 dark:hover:bg-white/2">
            <td class="<?= $td ?>">
              <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-500 text-theme-xs font-semibold text-white"><?= e(initiales($u->getPrenom(), $u->getNom())) ?></span>
                <div>
                  <span class="block text-theme-sm font-medium text-gray-800 dark:text-white/90"><?= e($u->getNomComplet()) ?><?= $u->getId() === $moi ? ' (vous)' : '' ?></span>
                  <span class="block text-theme-xs text-gray-500 dark:text-gray-400"><?= e($u->getEmail()) ?></span>
                </div>
              </div>
            </td>
            <td class="<?= $td ?>"><?= badgeRole($u->getRole(), $u->getLibelleRole()) ?></td>
            <td class="<?= $td ?>"><?= e($u->getVille() ?: '—') ?></td>
            <td class="<?= $td ?>"><?= (int) $u->getPoints() ?></td>
            <td class="<?= $td ?>"><?= e($u->getDateInscription() ? date('d/m/Y', strtotime($u->getDateInscription())) : '—') ?></td>
            <td class="<?= $td ?>">
              <?php if ($u->estBloque()): ?>
                <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-theme-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">Bloqué</span>
              <?php else: ?>
                <span class="inline-flex rounded-full bg-green-50 px-2.5 py-0.5 text-theme-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">Actif</span>
              <?php endif; ?>
            </td>
            <td class="<?= $td ?>">
              <div class="flex flex-wrap items-center gap-2">
                <a href="index.php?page=utilisateur-edit&amp;id=<?= (int) $u->getId() ?>" class="<?= $btn ?> border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Modifier</a>
                <?php if ($u->getId() !== $moi): ?>
                  <!-- Actions sensibles : POST + jeton CSRF (jamais un simple lien GET) -->
                  <form action="index.php?page=utilisateur-toggle" method="post" data-confirm="<?= $u->estBloque() ? 'Débloquer' : 'Bloquer' ?> le compte de <?= e($u->getNomComplet()) ?> ?">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
                    <input type="hidden" name="id" value="<?= (int) $u->getId() ?>" />
                    <button type="submit" class="<?= $btn ?> border-orange-200 bg-white text-orange-600 hover:bg-orange-50 dark:border-orange-500/30 dark:bg-transparent dark:text-orange-400"><?= $u->estBloque() ? 'Débloquer' : 'Bloquer' ?></button>
                  </form>
                  <form action="index.php?page=utilisateur-delete" method="post" data-confirm="Supprimer définitivement le compte de <?= e($u->getNomComplet()) ?> ? Son passeport sera supprimé aussi.">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
                    <input type="hidden" name="id" value="<?= (int) $u->getId() ?>" />
                    <button type="submit" class="<?= $btn ?> border-red-200 bg-white text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:bg-transparent dark:text-red-400">Supprimer</button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($nbPages > 1): ?>
    <!-- Pagination -->
    <div class="flex items-center justify-between border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
      <span class="text-theme-sm text-gray-500 dark:text-gray-400">Page <?= (int) $pageNum ?> sur <?= (int) $nbPages ?></span>
      <div class="flex gap-2">
        <?php if ($pageNum > 1): ?><a href="<?= e($lienPage($pageNum - 1)) ?>" class="<?= $btn ?> border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Précédent</a><?php endif; ?>
        <?php if ($pageNum < $nbPages): ?><a href="<?= e($lienPage($pageNum + 1)) ?>" class="<?= $btn ?> border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Suivant</a><?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
