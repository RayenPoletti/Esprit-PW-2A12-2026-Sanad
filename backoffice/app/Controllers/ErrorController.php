<?php

namespace App\Controllers;

/**
 * Page affichée quand la clé ?page=... n'existe pas dans routes.php.
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
