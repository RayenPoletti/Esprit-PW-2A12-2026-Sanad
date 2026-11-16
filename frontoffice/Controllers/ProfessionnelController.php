<?php
require_once __DIR__ . '/Controller.php';

class ProfessionnelController extends Controller
{
    public function index(): void
    {
        $this->view('professionnels');
    }

    public function rendezvous(): void
    {
        $this->view('rendezvous');
    }
}
