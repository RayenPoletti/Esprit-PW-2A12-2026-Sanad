<?php

/**
 * Page affichée quand la clé ?page=... n'existe pas dans la table $routes de index.php.
 */
class ErrorController extends Controller
{
    public function notFound(): void
    {
        $this->page  = '404';
        $this->title = 'Page introuvable | Sanad Back Office';
        $this->render('404');
    }
}
