<?php
/**
 * Petites fonctions utilitaires pour la sidebar (état actif / ouvert).
 * Elles comparent la page courante ($currentPage) avec la clé de chaque lien.
 */

/** Classe d'un lien de sous-menu : actif si la page courante est celle du lien. */
function isActive(string $page, string $currentPage): string
{
    if ($currentPage === 'utilisateur-edit') {
        $currentPage = 'utilisateurs';
    }
    return $page === $currentPage ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive';
}

/** Classe d'un élément de menu principal : actif si une de ses pages est ouverte. */
function isMenuActive(array $pages, string $currentPage): string
{
    return in_array($currentPage, $pages, true) ? 'menu-item-active' : 'menu-item-inactive';
}

/** Classe de l'icône d'un élément de menu principal. */
function isIconActive(array $pages, string $currentPage): string
{
    return in_array($currentPage, $pages, true) ? 'menu-item-icon-active' : 'menu-item-icon-inactive';
}

/** Vrai si le groupe de menu doit être ouvert (sa page est la page courante). */
function groupIsExpanded(array $pages, string $currentPage): bool
{
    return in_array($currentPage, $pages, true);
}

/** Nom du groupe de menu qui contient la page courante ('' si aucun). */
function groupOf(string $currentPage): string
{
    $groups = [
      'utilisateurs' => 'Utilisateurs',
      'utilisateur-form' => 'Utilisateurs',
      'utilisateur-edit' => 'Utilisateurs',
      'passeports' => 'Utilisateurs',
      'lieux' => 'Audits',
      'lieu-form' => 'Audits',
      'audits' => 'Audits',
      'audit-form' => 'Audits',
      'professionnels' => 'Rendez-vous',
      'professionnel-form' => 'Rendez-vous',
      'rendezvous' => 'Rendez-vous',
      'rendezvous-form' => 'Rendez-vous',
      'programmes' => 'Programmes',
      'programme-form' => 'Programmes',
      'seances' => 'Programmes',
      'seance-form' => 'Programmes',
      'publications' => 'Communauté',
      'publication-form' => 'Communauté',
      'commentaires' => 'Communauté',
      'produits' => 'Boutique',
      'produit-edit' => 'Boutique',
      'produit-form' => 'Boutique',
      'commandes' => 'Boutique',
    ];
    return $groups[$currentPage] ?? '';
}

// ------------------------------------------------------------------
// Aides pour les formulaires et les messages (la fonction e() d'échappement
// est définie dans config.php).
// ------------------------------------------------------------------

/** Classes TailAdmin d'un champ ; bordure rouge si aria-invalid="true" (erreur PHP ou JS). */
const CLASSE_CHAMP = 'h-11 w-full min-w-0 rounded-[10px] border border-gray-300 bg-white px-4 py-2.5 text-sm font-normal text-gray-800 shadow-theme-xs outline-hidden transition-colors placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/20 aria-invalid:border-red-500 aria-invalid:focus:ring-red-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-brand-500/40';

/** Attribut aria-invalid si le champ a une erreur côté serveur. */
function ariaInvalide(array $erreurs, string $champ): string
{
    return isset($erreurs[$champ]) ? ' aria-invalid="true"' : '';
}

/** Zone du message d'erreur d'un champ (remplie par PHP, puis par validation.js). */
function zoneErreur(array $erreurs, string $champ): string
{
    return '<p class="mt-1.5 min-h-4 text-theme-xs text-red-500" data-error-for="' . e($champ) . '">'
        . e($erreurs[$champ] ?? '') . '</p>';
}

/** Bandeau d'un message flash (succès, erreur, info). */
function bandeauFlash(?array $flash): string
{
    if (!$flash) {
        return '';
    }
    $styles = [
        'success' => 'border-green-500 bg-green-50 text-green-700 dark:border-green-500/30 dark:bg-green-500/15 dark:text-green-500',
        'danger'  => 'border-red-500 bg-red-50 text-red-700 dark:border-red-500/30 dark:bg-red-500/15 dark:text-red-500',
        'info'    => 'border-sky-500 bg-sky-50 text-sky-700 dark:border-sky-500/30 dark:bg-sky-500/15 dark:text-sky-500',
    ];
    $classe = $styles[$flash['type']] ?? $styles['info'];
    return '<div role="alert" class="mb-6 rounded-xl border p-4 text-sm ' . $classe . '">' . e($flash['message']) . '</div>';
}

/** Fil d'Ariane TailAdmin. $liens = [libellé => url] ; le dernier élément est la page courante. */
function filAriane(string $titre, array $liens = []): string
{
    $fleche = '<svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"><path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" /></svg>';
    $html = '<div class="mb-6 flex flex-wrap items-center justify-between gap-3"><h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">' . e($titre) . '</h2><nav><ol class="flex items-center gap-1.5">';
    $html .= '<li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="index.php?page=dashboard">Accueil ' . $fleche . '</a></li>';
    foreach ($liens as $libelle => $url) {
        $html .= '<li><a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="' . e($url) . '">' . e($libelle) . ' ' . $fleche . '</a></li>';
    }
    return $html . '<li class="text-sm text-gray-800 dark:text-white/90">' . e($titre) . '</li></ol></nav></div>';
}

/** Initiales pour l'avatar (ex. : « Amira Ben Ali » → « AB »). */
function initiales(string $prenom, string $nom): string
{
    return mb_strtoupper(mb_substr($prenom, 0, 1) . mb_substr($nom, 0, 1));
}

/** Badge coloré du rôle. */
function badgeRole(string $role, string $libelle): string
{
    $couleurs = [
        'admin'         => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
        'ambassadeur'   => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400',
        'professionnel' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-500',
        'aidant'        => 'bg-orange-50 text-orange-600 dark:bg-orange-500/15 dark:text-orange-400',
        'membre'        => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
    ];
    return '<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-theme-xs font-medium ' . ($couleurs[$role] ?? $couleurs['membre']) . '">' . e($libelle) . '</span>';
}

// ------------------------------------------------------------------
// Champs de formulaire TailAdmin (libellé + champ + zone d'erreur).
// $attrs reçoit les règles de validation.js : data-required, data-rule, data-min...
// ------------------------------------------------------------------
const CLASSE_LIBELLE = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';

function champ(array $erreurs, array $old, string $name, string $label, string $attrs = '', string $type = 'text', string $col = ''): string
{
    return '<div class="' . $col . '"><label for="' . e($name) . '" class="' . CLASSE_LIBELLE . '">' . e($label) . '</label>'
        . '<input id="' . e($name) . '" name="' . e($name) . '" type="' . e($type) . '" value="' . e($old[$name] ?? '') . '" '
        . $attrs . ' class="' . CLASSE_CHAMP . '"' . ariaInvalide($erreurs, $name) . ' />' . zoneErreur($erreurs, $name) . '</div>';
}

function champZone(array $erreurs, array $old, string $name, string $label, string $attrs = '', string $col = 'sm:col-span-2'): string
{
    return '<div class="' . $col . '"><label for="' . e($name) . '" class="' . CLASSE_LIBELLE . '">' . e($label) . '</label>'
        . '<textarea id="' . e($name) . '" name="' . e($name) . '" rows="4" ' . $attrs . ' class="' . str_replace('h-11', 'min-h-28', CLASSE_CHAMP) . '"'
        . ariaInvalide($erreurs, $name) . '>' . e($old[$name] ?? '') . '</textarea>' . zoneErreur($erreurs, $name) . '</div>';
}

/** @param array<string|int,string> $options valeur => libellé */
function champListe(array $erreurs, array $old, string $name, string $label, array $options, string $attrs = '', string $col = '', string $vide = 'Choisir…'): string
{
    $html = '<div class="' . $col . '"><label for="' . e($name) . '" class="' . CLASSE_LIBELLE . '">' . e($label) . '</label>'
        . '<select id="' . e($name) . '" name="' . e($name) . '" ' . $attrs . ' class="' . CLASSE_CHAMP . '"' . ariaInvalide($erreurs, $name) . '>';
    if ($vide !== '') {
        $html .= '<option value="">' . e($vide) . '</option>';
    }
    foreach ($options as $valeur => $libelle) {
        $sel = (string) ($old[$name] ?? '') === (string) $valeur ? ' selected' : '';
        $html .= '<option value="' . e((string) $valeur) . '"' . $sel . '>' . e($libelle) . '</option>';
    }
    return $html . '</select>' . zoneErreur($erreurs, $name) . '</div>';
}

function caseCochee(string $name, string $label, bool $coche, string $valeur = '1'): string
{
    $id = preg_replace('/[^a-z0-9_-]/i', '_', $name . '_' . $valeur);
    return '<label for="' . e($id) . '" class="flex cursor-pointer items-center gap-3 text-sm text-gray-700 dark:text-gray-400">'
        . '<input id="' . e($id) . '" type="checkbox" name="' . e($name) . '" value="' . e($valeur) . '"' . ($coche ? ' checked' : '')
        . ' class="h-5 w-5 rounded border-gray-300 accent-brand-500" />' . e($label) . '</label>';
}

/** Badge coloré (statuts) : vert, orange, rouge, bleu ou gris. */
function badge(string $libelle, string $couleur = 'gris'): string
{
    $c = [
        'vert'   => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500',
        'orange' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-orange-400',
        'rouge'  => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
        'bleu'   => 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-500',
        'gris'   => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
    ];
    return '<span class="inline-flex rounded-full px-2.5 py-0.5 text-theme-xs font-medium ' . ($c[$couleur] ?? $c['gris']) . '">' . e($libelle) . '</span>';
}

/** Couleur du badge selon le statut (tous modules). */
function couleurStatut(string $statut): string
{
    return [
        'valide' => 'vert', 'confirme' => 'vert', 'publie' => 'vert', 'livree' => 'vert', 'actif' => 'vert',
        'en_attente' => 'orange', 'signale' => 'orange', 'preparation' => 'bleu',
        'refuse' => 'rouge', 'annule' => 'rouge', 'annulee' => 'rouge', 'masque' => 'gris',
    ][$statut] ?? 'gris';
}

/** Bouton « Modifier » (lien) */
function boutonModifier(string $url): string
{
    return '<a href="' . e($url) . '" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Modifier</a>';
}

/**
 * Petit formulaire POST + jeton CSRF pour une action sensible (supprimer, valider, bloquer...).
 * $style : 'danger', 'succes' ou 'neutre'. Champs cachés supplémentaires dans $extra.
 */
function boutonAction(string $action, int $id, string $csrf, string $libelle, string $style = 'neutre', string $confirm = '', array $extra = []): string
{
    $styles = [
        'danger' => 'border-red-200 text-red-600 hover:bg-red-50 dark:border-red-500/30 dark:text-red-400',
        'succes' => 'border-green-200 text-green-700 hover:bg-green-50 dark:border-green-500/30 dark:text-green-400',
        'neutre' => 'border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300',
    ];
    $html = '<form action="' . e($action) . '" method="post"' . ($confirm !== '' ? ' data-confirm="' . e($confirm) . '"' : '') . '>'
        . '<input type="hidden" name="csrf_token" value="' . e($csrf) . '" /><input type="hidden" name="id" value="' . $id . '" />';
    foreach ($extra as $k => $v) {
        $html .= '<input type="hidden" name="' . e($k) . '" value="' . e((string) $v) . '" />';
    }
    return $html . '<button type="submit" class="inline-flex items-center rounded-lg border bg-white px-3 py-1.5 text-theme-xs font-medium shadow-theme-xs dark:bg-transparent '
        . ($styles[$style] ?? $styles['neutre']) . '">' . e($libelle) . '</button></form>';
}

/** En-tête de carte de liste : titre, sous-titre, recherche (optionnelle), bouton Ajouter. */
function enteteListe(string $titre, string $sousTitre, string $page, ?string $q = null, string $lienAjout = '', string $libelleAjout = 'Ajouter'): string
{
    $html = '<div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6"><div>'
        . '<h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">' . e($titre) . '</h3>'
        . '<p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">' . e($sousTitre) . '</p></div>'
        . '<div class="flex flex-col gap-3 sm:flex-row sm:items-center">';
    if ($q !== null) {
        $html .= '<form action="index.php" method="get" class="w-full sm:w-64"><input type="hidden" name="page" value="' . e($page) . '" />'
            . '<input type="search" name="q" value="' . e($q) . '" maxlength="100" placeholder="Rechercher…" aria-label="Rechercher" class="' . CLASSE_CHAMP . '" /></form>';
    }
    if ($lienAjout !== '') {
        $html .= '<a href="' . e($lienAjout) . '" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600">'
            . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 4.167v11.666M4.167 10h11.666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>' . e($libelleAjout) . '</a>';
    }
    return $html . '</div></div>';
}

/** Ouverture / fermeture d'un tableau TailAdmin avec ses en-têtes. */
function debutTableau(array $colonnes): string
{
    $html = '<div class="max-w-full overflow-x-auto border-t border-gray-100 dark:border-gray-800"><table class="min-w-full"><thead class="border-b border-gray-100 dark:border-white/5"><tr>';
    foreach ($colonnes as $c) {
        $html .= '<th class="px-5 py-3 text-start text-theme-xs font-medium text-gray-500 sm:px-6 dark:text-gray-400">' . e($c) . '</th>';
    }
    return $html . '</tr></thead><tbody class="divide-y divide-gray-100 dark:divide-white/5">';
}

function finTableau(int $nbLignes, int $nbColonnes, string $vide = 'Aucun élément pour le moment.'): string
{
    $html = $nbLignes === 0 ? '<tr><td colspan="' . $nbColonnes . '" class="px-5 py-10 text-center text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400">' . e($vide) . '</td></tr>' : '';
    return $html . '</tbody></table></div>';
}

const CLASSE_CELLULE = 'px-5 py-4 text-start text-theme-sm text-gray-500 sm:px-6 dark:text-gray-400';

/** Formate un nombre « à la française » sans zéros inutiles (25,5 ; 350). */
function nombre(?float $n, int $decimales = 2): string
{
    return rtrim(rtrim(number_format((float) $n, $decimales, ',', ' '), '0'), ',');
}

function dateFr(?string $date, bool $avecHeure = false): string
{
    if (!$date) {
        return '—';
    }
    return date($avecHeure ? 'd/m/Y H:i' : 'd/m/Y', strtotime($date));
}
