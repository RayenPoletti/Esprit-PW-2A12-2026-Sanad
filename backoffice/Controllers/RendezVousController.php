<?php

/**
 * Module 2 · responsable : Aziz Kouki · jointure Professionnel ↔ RendezVous.
 * Gestion des rendez-vous : liste (filtre par statut), ajout pour un patient, modification, confirmation / annulation, suppression.
 */
class RendezVousController extends Controller
{
    /** Créneaux proposés (comme dans le Front Office) */
    public const CRENEAUX = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'rendezvous';
        $this->title = 'Rendez-vous | Sanad Back Office';
        $statut = (string) ($_GET['statut'] ?? '');
        if (!array_key_exists($statut, RendezVous::STATUTS)) {
            $statut = '';
        }
        // Jointure RendezVous → Professionnel et RendezVous → Utilisateur (patient)
        $sql = 'SELECT r.*, p.prenom AS proPrenom, p.nom AS proNom, p.specialite, u.prenom, u.nom
                  FROM rendez_vous r INNER JOIN professionnel p ON p.id = r.professionnelId INNER JOIN utilisateur u ON u.id = r.utilisateurId';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE r.statut = :statut';
            $params['statut'] = $statut;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY r.dateHeure DESC');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['rdv' => RendezVous::fromArray($r), 'patient' => trim($r['prenom'] . ' ' . $r['nom']),
            'pro' => 'Dr ' . trim($r['proPrenom'] . ' ' . $r['proNom']) . ' · ' . $r['specialite']], $stmt->fetchAll());
        $this->render('rendezvous', ['lignes' => $lignes, 'statut' => $statut, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $this->formulaire($this->trouver((int) ($_GET['id'] ?? 0)));
    }

    private function trouver(int $id): RendezVous
    {
        $stmt = $this->db->prepare('SELECT * FROM rendez_vous WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Rendez-vous introuvable.');
            $this->redirect('index.php?page=rendezvous');
        }
        return RendezVous::fromArray($row);
    }

    private function formulaire(?RendezVous $rdv): void
    {
        $this->page  = $rdv ? 'rendezvous' : 'rendezvous-form';
        $this->title = ($rdv ? 'Modifier un rendez-vous' : 'Ajouter un rendez-vous') . ' | Sanad Back Office';
        $patients = [];
        foreach ($this->db->query("SELECT id, prenom, nom, email FROM utilisateur WHERE role <> 'admin' AND statut = 'actif' ORDER BY prenom")->fetchAll() as $r) {
            $patients[$r['id']] = $r['prenom'] . ' ' . $r['nom'] . ' (' . $r['email'] . ')';
        }
        $pros = [];
        foreach ($this->db->query('SELECT id, prenom, nom, specialite FROM professionnel ORDER BY nom')->fetchAll() as $r) {
            $pros[$r['id']] = 'Dr ' . $r['prenom'] . ' ' . $r['nom'] . ' · ' . $r['specialite'];
        }
        $creneaux = array_combine(self::CRENEAUX, self::CRENEAUX);
        $old = $rdv ? ['utilisateurId' => (string) $rdv->getUtilisateurId(), 'professionnelId' => (string) $rdv->getProfessionnelId(),
            'date' => substr((string) $rdv->getDateHeure(), 0, 10), 'heure' => substr((string) $rdv->getDateHeure(), 11, 5),
            'motif' => $rdv->getMotif(), 'statut' => $rdv->getStatut()] : ['statut' => 'en_attente'];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['utilisateurId', 'professionnelId', 'date', 'heure', 'motif', 'statut']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('utilisateurId', 'Le patient')->dansListe('utilisateurId', array_map('strval', array_keys($patients)), 'Le patient')
                  ->obligatoire('professionnelId', 'Le professionnel')->dansListe('professionnelId', array_map('strval', array_keys($pros)), 'Le professionnel')
                  ->obligatoire('date', 'La date')
                  ->obligatoire('heure', 'L’heure')->dansListe('heure', self::CRENEAUX, 'L’heure')
                  ->obligatoire('motif', 'Le motif')->longueurMin('motif', 3, 'Le motif')->longueurMax('motif', 150, 'Le motif')
                  ->obligatoire('statut', 'Le statut')->dansListe('statut', array_keys(RendezVous::STATUTS), 'Le statut');
                // Un nouveau rendez-vous doit être dans le futur ; une modification peut garder une date passée
                if ($rdv === null || $v->valeur('date') !== substr((string) $rdv->getDateHeure(), 0, 10)) {
                    $v->dateFuture('date', 'La date');
                }
                $dateHeure = $v->valeur('date') . ' ' . $v->valeur('heure') . ':00';
                if (empty($v->erreurs()) && $v->valeur('statut') !== 'annule' && $this->creneauPris((int) $v->valeur('professionnelId'), $dateHeure, $rdv ? (int) $rdv->getId() : 0)) {
                    $v->erreur('heure', 'Ce professionnel a déjà un rendez-vous à cette date et à cette heure.');
                }
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $r = $rdv ?? new RendezVous();
                    $r->setUtilisateurId((int) $v->valeur('utilisateurId'))->setProfessionnelId((int) $v->valeur('professionnelId'))
                      ->setDateHeure($dateHeure)->setMotif($v->valeur('motif'))->setStatut($v->valeur('statut'))
                      ->setPasseportTransmis($this->passeportPartage($r->getUtilisateurId()));
                    $donnees = ['utilisateurId' => $r->getUtilisateurId(), 'professionnelId' => $r->getProfessionnelId(), 'dateHeure' => $r->getDateHeure(),
                        'motif' => $r->getMotif(), 'statut' => $r->getStatut(), 'passeportTransmis' => (int) $r->isPasseportTransmis()];
                    if ($rdv === null) {
                        $this->db->prepare('INSERT INTO rendez_vous (utilisateurId, professionnelId, dateHeure, motif, statut, passeportTransmis) VALUES (:utilisateurId, :professionnelId, :dateHeure, :motif, :statut, :passeportTransmis)')->execute($donnees);
                    } else {
                        $this->db->prepare('UPDATE rendez_vous SET utilisateurId = :utilisateurId, professionnelId = :professionnelId, dateHeure = :dateHeure, motif = :motif, statut = :statut, passeportTransmis = :passeportTransmis WHERE id = :id')
                            ->execute($donnees + ['id' => $r->getId()]);
                    }
                    AuthController::flash('success', $rdv ? 'Rendez-vous modifié.' : 'Rendez-vous ajouté.');
                    $this->redirect('index.php?page=rendezvous');
                }
            }
        }
        $this->render('rendezvous_form', ['rdv' => $rdv, 'old' => $old, 'erreurs' => $erreurs, 'patients' => $patients, 'pros' => $pros,
            'creneaux' => $creneaux, 'csrfToken' => $this->csrf()]);
    }

    private function creneauPris(int $proId, string $dateHeure, int $saufId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rendez_vous WHERE professionnelId = :p AND dateHeure = :d AND statut <> 'annule' AND id <> :id");
        $stmt->execute(['p' => $proId, 'd' => $dateHeure, 'id' => $saufId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Le passeport est transmis si le patient en a un et a autorisé le partage. */
    private function passeportPartage(int $utilisateurId): bool
    {
        $stmt = $this->db->prepare('SELECT partageAutorise FROM passeport WHERE utilisateurId = :id');
        $stmt->execute(['id' => $utilisateurId]);
        return (bool) $stmt->fetchColumn();
    }

    public function statut(): void
    {
        $this->exigerPostValide('index.php?page=rendezvous');
        $nouveau = (string) ($_POST['statut'] ?? '');
        if (!array_key_exists($nouveau, RendezVous::STATUTS)) {
            AuthController::flash('danger', 'Statut inconnu.');
            $this->redirect('index.php?page=rendezvous');
        }
        $this->db->prepare('UPDATE rendez_vous SET statut = :s WHERE id = :id')->execute(['s' => $nouveau, 'id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Rendez-vous ' . mb_strtolower(RendezVous::STATUTS[$nouveau]) . '.');
        $this->redirect('index.php?page=rendezvous');
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=rendezvous');
        $this->db->prepare('DELETE FROM rendez_vous WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Rendez-vous supprimé.');
        $this->redirect('index.php?page=rendezvous');
    }
}
