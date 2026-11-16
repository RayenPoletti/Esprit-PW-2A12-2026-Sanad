<?php

namespace App\Controllers;

/**
 * Module 4 · responsable : Houssem Hafsi · jointure Publication ↔ Commentaire.
 */
class PublicationController extends Controller
{
    public function index(): void
    {
        $this->page  = 'publications';
        $this->title = 'Publications | Sanad Back Office';
        $this->render('publications');
    }

    public function create(): void
    {
        $this->page  = 'publication-form';
        $this->title = 'Ajouter une publication | Sanad Back Office';
        $this->render('publication_form');
    }
}
