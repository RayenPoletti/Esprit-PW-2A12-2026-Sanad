<?php

namespace App\Controllers;

/**
 * Module 3 · responsable : Rayen Marzougi · jointure Programme ↔ Séance.
 */
class SeanceController extends Controller
{
    public function index(): void
    {
        $this->page  = 'seances';
        $this->title = 'Séances | Sanad Back Office';
        $this->render('seances');
    }

    public function create(): void
    {
        $this->page  = 'seance-form';
        $this->title = 'Ajouter une séance | Sanad Back Office';
        $this->render('seance_form');
    }
}
