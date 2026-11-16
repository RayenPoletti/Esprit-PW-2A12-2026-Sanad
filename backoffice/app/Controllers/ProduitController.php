<?php

namespace App\Controllers;

/**
 * Module 5 · responsable : Karim Bousetta · jointure Produit ↔ Commande.
 */
class ProduitController extends Controller
{
    public function index(): void
    {
        $this->page  = 'produits';
        $this->title = 'Produits | Sanad Back Office';
        $this->render('produits');
    }

    public function create(): void
    {
        $this->page  = 'produit-form';
        $this->title = 'Ajouter un produit | Sanad Back Office';
        $this->render('produit_form');
    }
}
