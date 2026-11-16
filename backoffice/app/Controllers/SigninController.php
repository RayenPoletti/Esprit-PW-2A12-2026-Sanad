<?php

namespace App\Controllers;

/**
 * Page de connexion (pleine largeur, sans layout).
 */
class SigninController extends Controller
{
    public function index(): void
    {
        $this->page  = 'signin';
        $this->title = 'Connexion | Sanad Back Office';
        $this->renderStandalone('signin');
    }
}
