<?php
/**
 * Point d'entrée du Front Office : index.php?page=accueil, ?page=audits, ...
 * Le Controller choisi affiche la vue correspondante (dossier Views/).
 */
$routes = [
    'accueil' => ['HomeController', 'index'],
    'passeport' => ['PasseportController', 'index'],
    'professionnels' => ['ProfessionnelController', 'index'],
    'audits' => ['AuditController', 'index'],
    'programmes' => ['ProgrammeController', 'index'],
    'communaute' => ['CommunauteController', 'index'],
    'boutique' => ['BoutiqueController', 'index'],
    'rendezvous' => ['ProfessionnelController', 'rendezvous'],
    'connexion' => ['CompteController', 'connexion'],
    'inscription' => ['CompteController', 'inscription'],
];

$page = $_GET['page'] ?? 'accueil';
if (!isset($routes[$page])) {
    $page = 'accueil';
}

[$controller, $action] = $routes[$page];
require_once __DIR__ . '/Controllers/' . $controller . '.php';
(new $controller())->$action();
