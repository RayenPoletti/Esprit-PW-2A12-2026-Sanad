<?php
require_once __DIR__ . '/Controller.php';

class PasseportController extends Controller
{
    public function index(): void
    {
        $this->view('passeport');
    }
}
