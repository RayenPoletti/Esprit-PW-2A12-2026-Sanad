<?php
require_once __DIR__ . '/Controller.php';

class ProgrammeController extends Controller
{
    public function index(): void
    {
        $this->view('programmes');
    }
}
