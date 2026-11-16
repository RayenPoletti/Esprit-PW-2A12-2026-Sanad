<?php

namespace App\Controllers;

/**
 * Module 1 · responsable : Rayen Poletti · jointure Lieu ↔ Audit.
 */
class AuditController extends Controller
{
    public function index(): void
    {
        $this->page  = 'audits';
        $this->title = 'Audits d’accessibilité | Sanad Back Office';
        $this->render('audits');
    }

    public function create(): void
    {
        $this->page  = 'audit-form';
        $this->title = 'Ajouter un audit | Sanad Back Office';
        $this->render('audit_form');
    }
}
