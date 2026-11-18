<?php

/**
 * Tableau de bord du Back Office : tous les chiffres viennent de la base (comptes et modules 1 à 5).
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $this->page  = 'dashboard';
        $this->title = 'Tableau de bord | Sanad Back Office';
        $db = Database::getConnexion();

        $parRole = array_fill_keys(Utilisateur::ROLES, 0);
        foreach ($db->query('SELECT role, COUNT(*) AS n FROM utilisateur GROUP BY role')->fetchAll() as $r) {
            $parRole[$r['role']] = (int) $r['n'];
        }
        $bloques    = (int) $db->query("SELECT COUNT(*) FROM utilisateur WHERE statut = 'bloque'")->fetchColumn();
        $passeports = (int) $db->query('SELECT COUNT(*) FROM passeport')->fetchColumn();
        $derniers   = array_map([Utilisateur::class, 'fromArray'],
            $db->query('SELECT * FROM utilisateur ORDER BY id DESC LIMIT 5')->fetchAll());

        $compter = fn(string $sql): int => (int) $db->query($sql)->fetchColumn();
        $stats = [
            'lieux'            => $compter('SELECT COUNT(*) FROM lieu'),
            'lieuxVerifies'    => $compter('SELECT COUNT(*) FROM lieu WHERE scoreAccessibilite IS NOT NULL'),
            'auditsValides'    => $compter("SELECT COUNT(*) FROM audit WHERE statut = 'valide'"),
            'auditsAttente'    => $compter("SELECT COUNT(*) FROM audit WHERE statut = 'en_attente'"),
            'rdvAttente'       => $compter("SELECT COUNT(*) FROM rendez_vous WHERE statut = 'en_attente'"),
            'commandes'        => $compter('SELECT COUNT(*) FROM commande'),
            'commandesAttente' => $compter("SELECT COUNT(*) FROM commande WHERE statut = 'en_attente'"),
            'commandesPrepa'   => $compter("SELECT COUNT(*) FROM commande WHERE statut = 'preparation'"),
            'signalements'     => $compter("SELECT (SELECT COUNT(*) FROM publication WHERE statut = 'signale') + (SELECT COUNT(*) FROM commentaire WHERE statut = 'signale')"),
        ];
        $s = $db->prepare("SELECT COUNT(*) FROM rendez_vous WHERE statut <> 'annule' AND dateHeure >= :maintenant");
        $s->execute(['maintenant' => date('Y-m-d H:i:s')]);
        $stats['rdvAVenir'] = (int) $s->fetchColumn();

        // Audits validés par mois de l'année en cours (calcul en PHP : fonctionne avec toutes les bases)
        $auditsParMois = array_fill(0, 12, 0);
        $s = $db->prepare("SELECT dateAudit FROM audit WHERE statut = 'valide' AND dateAudit >= :debut");
        $s->execute(['debut' => date('Y') . '-01-01']);
        foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $d) {
            $auditsParMois[(int) substr($d, 5, 2) - 1]++;
        }

        $villes = $db->query('SELECT ville, COUNT(*) AS total, SUM(CASE WHEN scoreAccessibilite IS NOT NULL THEN 1 ELSE 0 END) AS verifies
                                FROM lieu GROUP BY ville ORDER BY total DESC, ville')->fetchAll();

        // Activité récente : derniers événements de chaque module, triés par date
        $activite = [];
        foreach ($db->query('SELECT a.dateAudit AS d, u.prenom, l.nom FROM audit a INNER JOIN utilisateur u ON u.id = a.utilisateurId INNER JOIN lieu l ON l.id = a.lieuId ORDER BY a.id DESC LIMIT 3') as $r) {
            $activite[] = ['date' => $r['d'], 'texte' => $r['prenom'] . ' a audité « ' . $r['nom'] . ' »'];
        }
        foreach ($db->query('SELECT r.dateHeure AS d, u.prenom, p.nom FROM rendez_vous r INNER JOIN utilisateur u ON u.id = r.utilisateurId INNER JOIN professionnel p ON p.id = r.professionnelId ORDER BY r.id DESC LIMIT 3') as $r) {
            $activite[] = ['date' => $r['d'], 'texte' => $r['prenom'] . ' a un rendez-vous avec le Dr ' . $r['nom']];
        }
        foreach ($db->query('SELECT c.dateCommande AS d, u.prenom, c.id FROM commande c INNER JOIN utilisateur u ON u.id = c.utilisateurId ORDER BY c.id DESC LIMIT 3') as $r) {
            $activite[] = ['date' => $r['d'], 'texte' => $r['prenom'] . ' a passé la commande n° ' . $r['id']];
        }
        foreach ($db->query('SELECT p.datePublication AS d, u.prenom FROM publication p INNER JOIN utilisateur u ON u.id = p.utilisateurId ORDER BY p.id DESC LIMIT 3') as $r) {
            $activite[] = ['date' => $r['d'], 'texte' => $r['prenom'] . ' a publié dans la communauté'];
        }
        usort($activite, fn($x, $y) => strcmp($y['date'], $x['date']));

        $this->render('dashboard', [
            'parRole'    => $parRole,
            'total'      => array_sum($parRole),
            'bloques'    => $bloques,
            'passeports' => $passeports,
            'derniers'   => $derniers,
            'stats'      => $stats,
            'auditsParMois' => $auditsParMois,
            'villes'     => $villes,
            'activite'   => array_slice($activite, 0, 8),
            'flash'      => AuthController::prendreFlash(),
        ], 'dashboard_scripts');
    }
}
