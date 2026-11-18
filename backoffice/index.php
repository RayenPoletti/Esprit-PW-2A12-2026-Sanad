<?php
/**
 * Point d'entrée unique du Back Office : index.php?page=dashboard, ?page=utilisateurs, ...
 *
 * Sécurité (workshop « Session & Sécurité ») :
 *   - session sécurisée, expiration après 30 min, en-têtes HTTP : config.php
 *   - contrôle d'accès : TOUTES les pages exigent le rôle « admin », sauf la connexion.
 */
declare(strict_types=1);

// Configuration (base de données, session, sécurité)
require_once __DIR__ . '/config.php';

// Modèles : une classe par entité du diagramme de classes
foreach (glob(__DIR__ . '/Models/*.php') as $modele) {
    require_once $modele;
}
// Contrôleurs de base
require_once __DIR__ . '/Controllers/Validator.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/Controller.php';
// Fonctions d'affichage (isActive, zoneErreur, bandeauFlash...)
require_once __DIR__ . '/Views/includes/helpers.php';

// Le Back Office charge Alpine.js (jsDelivr), qui a besoin de 'unsafe-eval' pour ses expressions x-data.
demarrerApplication(
    "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; "
    . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com data:; "
    . "img-src 'self' data:; connect-src 'self'; form-action 'self'; frame-ancestors 'none'"
);

// Table de routage : clé de page (?page=...) => [Contrôleur, méthode]
$routes = [
    'dashboard' => ['DashboardController', 'index'],
    'signin' => ['SigninController', 'index'],
    'logout' => ['SigninController', 'logout'],

    // Module commun · Toute l’équipe · Utilisateur ↔ Passeport
    'utilisateurs' => ['UtilisateurController', 'index'],
    'utilisateur-form' => ['UtilisateurController', 'create'],
    'utilisateur-edit' => ['UtilisateurController', 'edit'],
    'utilisateur-delete' => ['UtilisateurController', 'delete'],
    'utilisateur-toggle' => ['UtilisateurController', 'toggle'],
    'passeports' => ['PasseportController', 'index'],

    // Module 1 · Rayen Poletti · Lieu ↔ Audit
    'lieux' => ['LieuController', 'index'],
    'lieu-form' => ['LieuController', 'create'],
    'lieu-edit' => ['LieuController', 'edit'],
    'lieu-delete' => ['LieuController', 'delete'],
    'audits' => ['AuditController', 'index'],
    'audit-form' => ['AuditController', 'create'],
    'audit-edit' => ['AuditController', 'edit'],
    'audit-statut' => ['AuditController', 'statut'],
    'audit-delete' => ['AuditController', 'delete'],

    // Module 2 · Aziz Kouki · Professionnel ↔ RendezVous
    'professionnels' => ['ProfessionnelController', 'index'],
    'professionnel-form' => ['ProfessionnelController', 'create'],
    'professionnel-edit' => ['ProfessionnelController', 'edit'],
    'professionnel-toggle' => ['ProfessionnelController', 'toggle'],
    'professionnel-delete' => ['ProfessionnelController', 'delete'],
    'rendezvous' => ['RendezVousController', 'index'],
    'rendezvous-form' => ['RendezVousController', 'create'],
    'rendezvous-edit' => ['RendezVousController', 'edit'],
    'rendezvous-statut' => ['RendezVousController', 'statut'],
    'rendezvous-delete' => ['RendezVousController', 'delete'],

    // Module 3 · Rayen Marzougi · Programme ↔ Séance
    'programmes' => ['ProgrammeController', 'index'],
    'programme-form' => ['ProgrammeController', 'create'],
    'programme-edit' => ['ProgrammeController', 'edit'],
    'programme-delete' => ['ProgrammeController', 'delete'],
    'seances' => ['SeanceController', 'index'],
    'seance-form' => ['SeanceController', 'create'],
    'seance-edit' => ['SeanceController', 'edit'],
    'seance-delete' => ['SeanceController', 'delete'],

    // Module 4 · Houssem Hafsi · Publication ↔ Commentaire
    'publications' => ['PublicationController', 'index'],
    'publication-form' => ['PublicationController', 'create'],
    'publication-edit' => ['PublicationController', 'edit'],
    'publication-statut' => ['PublicationController', 'statut'],
    'publication-delete' => ['PublicationController', 'delete'],
    'commentaires' => ['CommentaireController', 'index'],
    'commentaire-edit' => ['CommentaireController', 'edit'],
    'commentaire-statut' => ['CommentaireController', 'statut'],
    'commentaire-delete' => ['CommentaireController', 'delete'],

    // Module 5 · Karim Bousetta · Produit ↔ Commande
    'produits' => ['ProduitController', 'index'],
    'produit-form' => ['ProduitController', 'create'],
    'produit-edit' => ['ProduitController', 'edit'],
    'produit-delete' => ['ProduitController', 'delete'],
    'commandes' => ['CommandeController', 'index'],
    'commande-statut' => ['CommandeController', 'statut'],
    'commande-delete' => ['CommandeController', 'delete'],

    '404' => ['ErrorController', 'notFound'],
];

$page = (string) ($_GET['page'] ?? 'dashboard');

// Pages accessibles sans être administrateur
$pagesPubliques = ['signin', 'logout'];
if (!in_array($page, $pagesPubliques, true)) {
    AuthController::requireRole(['admin'], 'index.php?page=signin', __DIR__ . '/Views/403.php');
}

if (!isset($routes[$page])) {
    http_response_code(404);
    $page = '404';
}

[$controller, $action] = $routes[$page];
require_once __DIR__ . '/Controllers/' . $controller . '.php';
(new $controller())->$action();
