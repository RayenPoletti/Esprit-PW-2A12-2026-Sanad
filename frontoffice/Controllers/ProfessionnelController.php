<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module 2 · Aziz Kouki · Professionnel ↔ RendezVous — Front Office.
 *  - ?page=professionnels : professionnels validés, filtrés par spécialité, ville et selon le passeport du membre
 *    (les critères viennent des audits VALIDÉS du lieu, pas des déclarations des cabinets) ;
 *  - ?page=rendezvous : prise de rendez-vous (membre connecté), liste et annulation de ses rendez-vous.
 */
class ProfessionnelController extends Controller
{
    public const CRENEAUX = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];

    /** Critères d'audit exigés par chaque besoin du passeport */
    private const BESOINS_CRITERES = [
        'fauteuilRoulant' => ['rampe', 'porteLarge', 'ascenseur', 'toilettesAdaptees'],
        'mobiliteReduite' => ['rampe', 'ascenseur'],
        'surdite'         => ['accueilLSF'],
    ];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $specialite = (string) ($_GET['specialite'] ?? '');
        $ville = (string) ($_GET['ville'] ?? '');
        $villes = $this->db->query('SELECT DISTINCT l.ville FROM lieu l INNER JOIN professionnel p ON p.lieuId = l.id WHERE p.valide = 1 ORDER BY l.ville')->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array($specialite, Professionnel::SPECIALITES, true)) {
            $specialite = '';
        }
        if (!in_array($ville, $villes, true)) {
            $ville = '';
        }

        // Pour chaque critère : 1 si au moins un audit validé du lieu l'a constaté
        $sousRequetes = [];
        foreach (array_keys(Audit::CRITERES) as $c) {
            $sousRequetes[] = "(SELECT MAX(a.$c) FROM audit a WHERE a.lieuId = l.id AND a.statut = 'valide') AS $c";
        }
        $sql = 'SELECT p.*, l.nom AS lieuNom, l.adresse, l.ville, l.scoreAccessibilite,
                       (SELECT COUNT(*) FROM audit a WHERE a.lieuId = l.id AND a.statut = \'valide\') AS nbAudits, '
            . implode(', ', $sousRequetes) . '
                  FROM professionnel p INNER JOIN lieu l ON l.id = p.lieuId
                 WHERE p.valide = 1';
        $params = [];
        if ($specialite !== '') {
            $sql .= ' AND p.specialite = :specialite';
            $params['specialite'] = $specialite;
        }
        if ($ville !== '') {
            $sql .= ' AND l.ville = :ville';
            $params['ville'] = $ville;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY l.scoreAccessibilite DESC, p.nom');
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        // Filtre « Appliquer mon passeport » (membre connecté qui a un passeport)
        $passeport = null;
        $exiges = [];
        if (AuthController::isAuthenticated()) {
            $s = $this->db->prepare('SELECT * FROM passeport WHERE utilisateurId = :id');
            $s->execute(['id' => (int) $_SESSION['user_id']]);
            $row = $s->fetch();
            $passeport = $row ? Passeport::fromArray($row) : null;
        }
        $appliquer = $passeport && ($_GET['passeport'] ?? (isset($_GET['specialite']) ? '' : '1')) === '1';
        if ($appliquer) {
            foreach (self::BESOINS_CRITERES as $besoin => $criteres) {
                if ($passeport->getBesoin($besoin)) {
                    $exiges = array_merge($exiges, $criteres);
                }
            }
            $exiges = array_unique($exiges);
            $rows = array_filter($rows, function ($r) use ($exiges) {
                foreach ($exiges as $c) {
                    if (empty($r[$c])) {
                        return false;
                    }
                }
                return true;
            });
        }

        $resultats = array_map(fn($r) => ['pro' => Professionnel::fromArray($r), 'lieu' => $r['lieuNom'] . ' · ' . $r['ville'],
            'score' => $r['scoreAccessibilite'] !== null ? (float) $r['scoreAccessibilite'] : null, 'nbAudits' => (int) $r['nbAudits'],
            'criteres' => array_keys(array_filter(array_intersect_key($r, Audit::CRITERES)))], $rows);

        $this->view('professionnels', ['resultats' => array_values($resultats), 'specialite' => $specialite, 'ville' => $ville, 'villes' => $villes,
            'aPasseport' => $passeport !== null, 'appliquer' => $appliquer, 'exiges' => $exiges]);
    }

    // =================================================================
    // Prendre rendez-vous (membre connecté)
    // =================================================================
    public function rendezvous(): void
    {
        $moi = (int) $_SESSION['user_id'];
        $pros = [];
        foreach ($this->db->query('SELECT p.id, p.prenom, p.nom, p.specialite, l.ville FROM professionnel p INNER JOIN lieu l ON l.id = p.lieuId WHERE p.valide = 1 ORDER BY p.specialite, p.nom')->fetchAll() as $r) {
            $pros[$r['id']] = $r['specialite'] . ' · Dr ' . $r['prenom'] . ' ' . $r['nom'] . ' (' . $r['ville'] . ')';
        }
        $s = $this->db->prepare('SELECT partageAutorise FROM passeport WHERE utilisateurId = :id');
        $s->execute(['id' => $moi]);
        $passeport = $s->fetchColumn(); // false = pas de passeport
        $old = ['professionnelId' => (string) ($_GET['pro'] ?? '')];
        $transmettre = $passeport !== false && (bool) $passeport;
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['professionnelId', 'date', 'heure', 'motif']);
            $transmettre = $passeport !== false && isset($_POST['passeportTransmis']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('professionnelId', 'Le professionnel')->dansListe('professionnelId', array_map('strval', array_keys($pros)), 'Le professionnel')
                  ->obligatoire('date', 'La date')->dateFuture('date', 'La date')
                  ->obligatoire('heure', 'L’heure')->dansListe('heure', self::CRENEAUX, 'L’heure')
                  ->obligatoire('motif', 'Le motif')->longueurMin('motif', 3, 'Le motif')->longueurMax('motif', 150, 'Le motif');
                $dateHeure = $v->valeur('date') . ' ' . $v->valeur('heure') . ':00';
                if (empty($v->erreurs())) {
                    $c = $this->db->prepare("SELECT COUNT(*) FROM rendez_vous WHERE professionnelId = :p AND dateHeure = :d AND statut <> 'annule'");
                    $c->execute(['p' => (int) $v->valeur('professionnelId'), 'd' => $dateHeure]);
                    if ((int) $c->fetchColumn() > 0) {
                        $v->erreur('heure', 'Ce créneau est déjà pris. Choisissez une autre heure.');
                    }
                }
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $r = (new RendezVous())->setUtilisateurId($moi)->setProfessionnelId((int) $v->valeur('professionnelId'))
                        ->setDateHeure($dateHeure)->setMotif($v->valeur('motif'))->setStatut('en_attente')->setPasseportTransmis($transmettre);
                    $this->db->prepare('INSERT INTO rendez_vous (utilisateurId, professionnelId, dateHeure, motif, statut, passeportTransmis) VALUES (:u, :p, :d, :m, :s, :t)')
                        ->execute(['u' => $r->getUtilisateurId(), 'p' => $r->getProfessionnelId(), 'd' => $r->getDateHeure(), 'm' => $r->getMotif(),
                            's' => $r->getStatut(), 't' => (int) $r->isPasseportTransmis()]);
                    AuthController::flash('success', 'Votre demande de rendez-vous est envoyée. Le cabinet va la confirmer.');
                    $this->redirect('index.php?page=rendezvous');
                }
            }
        }

        $stmt = $this->db->prepare('SELECT r.*, p.prenom, p.nom, p.specialite, l.nom AS lieuNom, l.adresse, l.ville
                                      FROM rendez_vous r INNER JOIN professionnel p ON p.id = r.professionnelId INNER JOIN lieu l ON l.id = p.lieuId
                                     WHERE r.utilisateurId = :moi ORDER BY r.dateHeure DESC');
        $stmt->execute(['moi' => $moi]);
        $mesRdv = array_map(fn($r) => ['rdv' => RendezVous::fromArray($r), 'pro' => 'Dr ' . $r['prenom'] . ' ' . $r['nom'] . ' · ' . $r['specialite'],
            'lieu' => $r['lieuNom'] . ', ' . $r['adresse'] . ', ' . $r['ville']], $stmt->fetchAll());

        $this->view('rendezvous', ['pros' => $pros, 'creneaux' => self::CRENEAUX, 'old' => $old, 'erreurs' => $erreurs, 'transmettre' => $transmettre,
            'aPasseport' => $passeport !== false, 'mesRdv' => $mesRdv, 'csrfToken' => $this->csrf()]);
    }

    /** Le membre annule SON rendez-vous (POST + CSRF ; l'id du propriétaire est vérifié dans la requête). */
    public function annuler(): void
    {
        $this->exigerPostValide('index.php?page=rendezvous');
        $stmt = $this->db->prepare("UPDATE rendez_vous SET statut = 'annule' WHERE id = :id AND utilisateurId = :moi AND statut <> 'annule' AND dateHeure > :maintenant");
        $stmt->execute(['id' => (int) ($_POST['id'] ?? 0), 'moi' => (int) $_SESSION['user_id'], 'maintenant' => date('Y-m-d H:i:s')]);
        AuthController::flash($stmt->rowCount() ? 'success' : 'danger', $stmt->rowCount() ? 'Rendez-vous annulé.' : 'Ce rendez-vous ne peut pas être annulé.');
        $this->redirect('index.php?page=rendezvous');
    }
}
