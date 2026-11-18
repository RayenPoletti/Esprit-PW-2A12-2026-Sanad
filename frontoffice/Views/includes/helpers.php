<?php
/**
 * Petites fonctions d'affichage du Front Office.
 * (e() pour échapper les valeurs est définie dans config.php.)
 */

/** Renvoie 'active' si le lien correspond à la page en cours (menu). */
function navActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}

/** Classe Bootstrap « is-invalid » si le champ a une erreur côté serveur. */
function invalide(array $erreurs, string $champ): string
{
    return isset($erreurs[$champ]) ? ' is-invalid" aria-invalid="true' : '';
}

/** Zone du message d'erreur d'un champ (remplie par PHP, puis par validation.js). */
function zoneErreur(array $erreurs, string $champ): string
{
    return '<div class="sanad-erreur" data-error-for="' . e($champ) . '">' . e($erreurs[$champ] ?? '') . '</div>';
}

/** Message flash (alerte Bootstrap) affiché une seule fois. */
function bandeauFlash(?array $flash): string
{
    if (!$flash) {
        return '';
    }
    $type = in_array($flash['type'], ['success', 'danger', 'info'], true) ? $flash['type'] : 'info';
    return '<div class="alert alert-' . $type . '" role="alert">' . e($flash['message']) . '</div>';
}

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
    return date($avecHeure ? 'd/m/Y à H:i' : 'd/m/Y', strtotime($date));
}

/** Jeton CSRF caché pour un formulaire. */
function champCsrf(string $csrf): string
{
    return '<input type="hidden" name="csrf_token" value="' . e($csrf) . '">';
}
