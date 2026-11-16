<?php

namespace App\Controllers;

/**
 * Module commun · responsable : Toute l’équipe · jointure Utilisateur ↔ Passeport.
 */
class UtilisateurController extends Controller
{
    public function index(): void
    {
        $this->page  = 'utilisateurs';
        $this->title = 'Utilisateurs | Sanad Back Office';
        $this->render('utilisateurs');
    }

    public function create(): void
    {
        $this->page  = 'utilisateur-form';
        $this->title = 'Ajouter un utilisateur | Sanad Back Office';
        $this->render('utilisateur_form');
    }
}
