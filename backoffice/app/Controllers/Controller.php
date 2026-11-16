<?php

namespace App\Controllers;

abstract class Controller
{
    /** Clé de la page courante, utilisée par la sidebar pour l'état actif */
    protected string $page = 'dashboard';

    /** Titre affiché dans <title> */
    protected string $title = 'Sanad · Back Office';

    /**
     * Rend une vue de contenu (app/Views/pages/{$view}.php) dans le layout commun.
     *
     * @param string      $view        nom du fichier de vue (sans .php) dans Views/pages
     * @param array       $data        variables à extraire dans la vue de contenu
     * @param string|null $scriptsView nom du fichier de script spécifique (sans .php),
     *                                 ex : 'dashboard_scripts' — optionnel
     */
    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $content      = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data);
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data)
            : '';

        $title       = $this->title;
        $currentPage = $this->page;

        require_once __DIR__ . '/../Views/layout.php';
    }

    /** Rend une page pleine largeur (sans sidebar ni header), ex : connexion. */
    protected function renderStandalone(string $view, array $data = []): void
    {
        $title       = $this->title;
        $currentPage = $this->page;
        extract($data);
        require __DIR__ . '/../Views/pages/' . $view . '.php';
    }

    /** Exécute un fichier PHP et renvoie son HTML au lieu de l'afficher. */
    protected function capture(string $file, array $data = []): string
    {
        extract($data);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
