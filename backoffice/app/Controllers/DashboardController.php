<?php

namespace App\Controllers;

/**
 * Tableau de bord du Back Office.
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $this->page  = 'dashboard';
        $this->title = 'Tableau de bord | Sanad Back Office';
        $this->render('dashboard', [], 'dashboard_scripts');
    }
}
