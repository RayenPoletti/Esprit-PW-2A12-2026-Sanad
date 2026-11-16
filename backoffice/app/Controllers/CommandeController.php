<?php

namespace App\Controllers;

/**
 * Module 5 · responsable : Karim Bousetta · jointure Produit ↔ Commande.
 */
class CommandeController extends Controller
{
    public function index(): void
    {
        $this->page  = 'commandes';
        $this->title = 'Commandes | Sanad Back Office';
        $this->render('commandes');
    }
}
