<?php

namespace App\Controllers;

/**
 * Module 2 · responsable : Aziz Kouki · jointure Professionnel ↔ RendezVous.
 */
class RendezVousController extends Controller
{
    public function index(): void
    {
        $this->page  = 'rendezvous';
        $this->title = 'Rendez-vous | Sanad Back Office';
        $this->render('rendezvous');
    }

    public function create(): void
    {
        $this->page  = 'rendezvous-form';
        $this->title = 'Ajouter un rendez-vous | Sanad Back Office';
        $this->render('rendezvous_form');
    }
}
