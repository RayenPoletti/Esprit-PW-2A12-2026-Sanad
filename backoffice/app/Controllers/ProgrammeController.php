<?php

namespace App\Controllers;

/**
 * Module 3 · responsable : Rayen Marzougi · jointure Programme ↔ Séance.
 */
class ProgrammeController extends Controller
{
    public function index(): void
    {
        $this->page  = 'programmes';
        $this->title = 'Programmes bien-être | Sanad Back Office';
        $this->render('programmes');
    }

    public function create(): void
    {
        $this->page  = 'programme-form';
        $this->title = 'Ajouter un programme | Sanad Back Office';
        $this->render('programme_form');
    }
}
