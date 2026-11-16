<?php
/**
 * Table de routage : clé de page (?page=...) => [Controller, méthode].
 */
return [
    'dashboard' => ['DashboardController', 'index'],
    'signin' => ['SigninController', 'index'],

    // Module commun · Toute l’équipe · Utilisateur ↔ Passeport
    'utilisateurs' => ['UtilisateurController', 'index'],
    'utilisateur-form' => ['UtilisateurController', 'create'],
    'passeports' => ['PasseportController', 'index'],

    // Module 1 · Rayen Poletti · Lieu ↔ Audit
    'lieux' => ['LieuController', 'index'],
    'lieu-form' => ['LieuController', 'create'],
    'audits' => ['AuditController', 'index'],
    'audit-form' => ['AuditController', 'create'],

    // Module 2 · Aziz Kouki · Professionnel ↔ RendezVous
    'professionnels' => ['ProfessionnelController', 'index'],
    'professionnel-form' => ['ProfessionnelController', 'create'],
    'rendezvous' => ['RendezVousController', 'index'],
    'rendezvous-form' => ['RendezVousController', 'create'],

    // Module 3 · Rayen Marzougi · Programme ↔ Séance
    'programmes' => ['ProgrammeController', 'index'],
    'programme-form' => ['ProgrammeController', 'create'],
    'seances' => ['SeanceController', 'index'],
    'seance-form' => ['SeanceController', 'create'],

    // Module 4 · Houssem Hafsi · Publication ↔ Commentaire
    'publications' => ['PublicationController', 'index'],
    'publication-form' => ['PublicationController', 'create'],
    'commentaires' => ['CommentaireController', 'index'],

    // Module 5 · Karim Bousetta · Produit ↔ Commande
    'produits' => ['ProduitController', 'index'],
    'produit-form' => ['ProduitController', 'create'],
    'commandes' => ['CommandeController', 'index'],

    '404' => ['ErrorController', 'notFound'],
];
