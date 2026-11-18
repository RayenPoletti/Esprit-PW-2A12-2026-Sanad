<?php
/**
 * Point d'entrée du Front Office : index.php?page=accueil, ?page=audits, ...
 *
 * Sécurité (workshop « Session & Sécurité ») :
 *   - session sécurisée, expiration après 30 min, en-têtes HTTP : config.php
 *   - contrôle d'accès par page : la table $acces ci-dessous est vérifiée AVANT d'appeler le contrôleur.
 */
declare(strict_types=1);

// Configuration (base de données, session, sécurité)
require_once __DIR__ . '/config.php';

// Modèles : une classe par entité du diagramme de classes
foreach (glob(__DIR__ . '/Models/*.php') as $modele) {
    require_once $modele;
}
// Contrôleurs communs
require_once __DIR__ . '/Controllers/Validator.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/Controller.php';

// Le Front Office n'exécute que des scripts locaux : pas de script en ligne, pas de CDN.
demarrerApplication(
    "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
    . "font-src 'self' https://fonts.gstatic.com data:; img-src 'self' data:; form-action 'self'; frame-ancestors 'none'"
);

require_once __DIR__ . '/Views/includes/helpers.php';

$routes = [
    'accueil'             => ['HomeController', 'index'],
    'professionnels'      => ['ProfessionnelController', 'index'],
    'programmes'          => ['ProgrammeController', 'index'],
    'programme'           => ['ProgrammeController', 'detail'],
    'programme-inscrire'  => ['ProgrammeController', 'inscrire'],
    'programme-avancer'   => ['ProgrammeController', 'avancer'],
    'programme-quitter'   => ['ProgrammeController', 'quitter'],
    'communaute'           => ['CommunauteController', 'index'],
    'communaute-publier'   => ['CommunauteController', 'publier'],
    'communaute-commenter' => ['CommunauteController', 'commenter'],
    'communaute-signaler'  => ['CommunauteController', 'signaler'],
    'communaute-supprimer' => ['CommunauteController', 'supprimer'],
    'boutique'            => ['BoutiqueController', 'index'],
    'panier'              => ['BoutiqueController', 'panier'],
    'panier-ajouter'      => ['BoutiqueController', 'ajouter'],
    'panier-modifier'     => ['BoutiqueController', 'modifierPanier'],
    'panier-commander'    => ['BoutiqueController', 'commander'],
    'commande-annuler'    => ['BoutiqueController', 'annuler'],
    'rendezvous'          => ['ProfessionnelController', 'rendezvous'],
    'rendezvous-annuler'  => ['ProfessionnelController', 'annuler'],
    'audits'              => ['AuditController', 'index'],
    // Module commun : comptes et passeport
    'connexion'           => ['CompteController', 'connexion'],
    'inscription'         => ['CompteController', 'inscription'],
    'deconnexion'         => ['CompteController', 'deconnexion'],
    'compte'              => ['CompteController', 'profil'],
    'compte-motdepasse'   => ['CompteController', 'motDePasse'],
    'passeport'           => ['PasseportController', 'index'],
    'passeport-supprimer' => ['PasseportController', 'supprimer'],
    // Fiche médicale et antécédents (extension du module 2)
    'fiche'                 => ['FicheMedicaleController', 'maFiche'],
    'patients'              => ['FicheMedicaleController', 'patients'],
    'dossier'               => ['FicheMedicaleController', 'dossier'],
    'consultation-modifier' => ['FicheMedicaleController', 'modifierConsultation'],
];

/**
 * Contrôle d'accès selon le rôle.
 *   absent      → page publique
 *   'connecte'  → il faut être connecté (tous les rôles)
 *   [rôles...]  → il faut avoir l'un de ces rôles (sinon erreur 403)
 */
$acces = [
    'compte'              => 'connecte',
    'compte-motdepasse'   => 'connecte',
    'passeport'           => 'connecte',
    'passeport-supprimer' => 'connecte',
    'rendezvous'          => 'connecte',
    'rendezvous-annuler'  => 'connecte',
    'programme-inscrire'  => 'connecte',
    'programme-avancer'   => 'connecte',
    'programme-quitter'   => 'connecte',
    'communaute-publier'   => 'connecte',
    'communaute-commenter' => 'connecte',
    'communaute-signaler'  => 'connecte',
    'communaute-supprimer' => 'connecte',
    'panier'               => 'connecte',
    'panier-ajouter'       => 'connecte',
    'panier-modifier'      => 'connecte',
    'panier-commander'     => 'connecte',
    'commande-annuler'     => 'connecte',
    'audits'              => ['ambassadeur', 'admin'],
    'fiche'                 => 'connecte',
    'patients'              => ['professionnel'],
    'dossier'               => ['professionnel'],
    'consultation-modifier' => ['professionnel'],
];

$page = (string) ($_GET['page'] ?? 'accueil');
if (!isset($routes[$page])) {
    $page = 'accueil';
}

$regle = $acces[$page] ?? null;
if ($regle === 'connecte') {
    AuthController::requireAuth('index.php?page=connexion');
} elseif (is_array($regle)) {
    AuthController::requireRole($regle, 'index.php?page=connexion', __DIR__ . '/Views/403.php');
}

[$controller, $action] = $routes[$page];
require_once __DIR__ . '/Controllers/' . $controller . '.php';
(new $controller())->$action();
