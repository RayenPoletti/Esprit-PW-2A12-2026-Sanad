<?php
/**
 * Contrôleur de base du Front Office : affiche une vue du dossier Views/.
 * Chaque vue inclut elle-même ses blocs communs (includes/head.php, navbar.php, ...).
 */
abstract class Controller
{
    protected function view(string $name, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $name . '.php';
    }
}
