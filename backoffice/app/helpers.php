<?php
/**
 * Petites fonctions utilitaires pour la sidebar (état actif / ouvert).
 * Elles comparent la page courante ($currentPage) avec la clé de chaque lien.
 */

/** Classe d'un lien de sous-menu : actif si la page courante est celle du lien. */
function isActive(string $page, string $currentPage): string
{
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
      'produit-form' => 'Boutique',
      'commandes' => 'Boutique',
    ];
    return $groups[$currentPage] ?? '';
}

/** Échappe une valeur avant de l'afficher dans une vue. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
