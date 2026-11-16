<?php
require_once __DIR__ . '/Controller.php';

class CompteController extends Controller
{
    public function connexion(): void
    {
        $this->view('connexion');
    }

    public function inscription(): void
    {
        $this->view('inscription');
    }
}
