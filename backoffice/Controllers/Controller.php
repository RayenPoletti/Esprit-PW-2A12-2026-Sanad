<?php

abstract class Controller
{
    /** Clé de la page courante, utilisée par la sidebar pour l'état actif */
    protected string $page = 'dashboard';

    /** Titre affiché dans <title> */
    protected string $title = 'Sanad · Back Office';

    /**
     * Rend une vue de contenu (Views/{$view}.php) dans le layout commun.
     *
     * @param string      $view        nom du fichier de vue (sans .php) dans Views/
     * @param array       $data        variables à extraire dans la vue de contenu
     * @param string|null $scriptsView nom du fichier de script spécifique (sans .php),
     *                                 ex : 'dashboard_scripts' — optionnel
     */
    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $content      = $this->capture(__DIR__ . '/../Views/' . $view . '.php', $data);
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/' . $scriptsView . '.php', $data)
            : '';

        $title       = $this->title;
        $currentPage = $this->page;

        require_once __DIR__ . '/../Views/includes/layout.php';
    }

    /** Rend une page pleine largeur (sans sidebar ni header), ex : connexion. */
    protected function renderStandalone(string $view, array $data = []): void
    {
        $title       = $this->title;
        $currentPage = $this->page;
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    /** Redirige vers une autre page puis arrête le script. */
    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Vérifie qu'une action sensible arrive en POST avec un jeton CSRF valide ; sinon message et retour à $urlRetour.
     */
    protected function exigerPostValide(string $urlRetour): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            AuthController::flash('danger', 'Action refusée : requête invalide.');
            $this->redirect($urlRetour);
        }
    }

    /** Valeurs saisies (pour réafficher le formulaire après une erreur). */
    protected function saisie(array $champs): array
    {
        $old = [];
        foreach ($champs as $champ) {
            $old[$champ] = is_string($_POST[$champ] ?? null) ? trim($_POST[$champ]) : '';
        }
        return $old;
    }

    /** Jeton CSRF de la session (pour les formulaires des vues). */
    protected function csrf(): string
    {
        return (new AuthController())->getCsrfToken();
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
