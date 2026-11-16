<?php
require_once __DIR__ . '/Controller.php';

class CommunauteController extends Controller
{
    public function index(): void
    {
        $this->view('communaute');
    }
}
