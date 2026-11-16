<?php
require_once __DIR__ . '/Controller.php';

class BoutiqueController extends Controller
{
    public function index(): void
    {
        $this->view('boutique');
    }
}
