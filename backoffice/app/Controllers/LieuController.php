<?php

namespace App\Controllers;

/**
 * Module 1 · responsable : Rayen Poletti · jointure Lieu ↔ Audit.
 */
class LieuController extends Controller
{
    public function index(): void
    {
        $this->page  = 'lieux';
        $this->title = 'Lieux de soin | Sanad Back Office';
        $this->render('lieux');
    }

    public function create(): void
    {
        $this->page  = 'lieu-form';
        $this->title = 'Ajouter un lieu | Sanad Back Office';
        $this->render('lieu_form');
    }
}
