<?php

namespace App\Controllers;

/**
 * Module 2 · responsable : Aziz Kouki · jointure Professionnel ↔ RendezVous.
 */
class ProfessionnelController extends Controller
{
    public function index(): void
    {
        $this->page  = 'professionnels';
        $this->title = 'Professionnels | Sanad Back Office';
        $this->render('professionnels');
    }

    public function create(): void
    {
        $this->page  = 'professionnel-form';
        $this->title = 'Ajouter un professionnel | Sanad Back Office';
        $this->render('professionnel_form');
    }
}
