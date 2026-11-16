<?php
require_once __DIR__ . '/Controller.php';

class AuditController extends Controller
{
    public function index(): void
    {
        $this->view('audits');
    }
}
