<?php

namespace App\Controllers;

/**
 * Module commun · responsable : Toute l’équipe · jointure Utilisateur ↔ Passeport.
 */
class PasseportController extends Controller
{
    public function index(): void
    {
        $this->page  = 'passeports';
        $this->title = 'Passeports d’accessibilité | Sanad Back Office';
        $this->render('passeports');
    }
}
