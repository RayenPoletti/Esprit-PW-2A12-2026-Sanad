<?php

namespace App\Controllers;

/**
 * Module 4 · responsable : Houssem Hafsi · jointure Publication ↔ Commentaire.
 */
class CommentaireController extends Controller
{
    public function index(): void
    {
        $this->page  = 'commentaires';
        $this->title = 'Commentaires | Sanad Back Office';
        $this->render('commentaires');
    }
}
