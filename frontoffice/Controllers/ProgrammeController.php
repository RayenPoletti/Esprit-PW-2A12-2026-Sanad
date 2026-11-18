<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module 3 · Rayen Marzougi · Programme ◆— Séance, classe d'association Inscription (Utilisateur ↔ Programme).
 *  - ?page=programmes : programmes ayant au moins une séance ;
 *  - ?page=programme&id= : détail et séances ; le membre connecté s'inscrit, avance séance par séance, ou quitte.
 */
class ProgrammeController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $moi = AuthController::isAuthenticated() ? (int) $_SESSION['user_id'] : 0;
        $stmt = $this->db->prepare(
            'SELECT pr.*, COUNT(s.id) AS nbSeances, SUM(s.dureeMin) AS dureeTotale,
                    (SELECT COUNT(*) FROM inscription i WHERE i.programmeId = pr.id AND i.utilisateurId = :moi) AS inscrit
               FROM programme pr INNER JOIN seance s ON s.programmeId = pr.id
              GROUP BY pr.id, pr.titre, pr.description, pr.niveau, pr.formats
              ORDER BY pr.titre'
        );
        $stmt->execute(['moi' => $moi]);
        $programmes = array_map(fn($r) => ['programme' => Programme::fromArray($r), 'nbSeances' => (int) $r['nbSeances'],
            'duree' => (int) $r['dureeTotale'], 'inscrit' => (bool) $r['inscrit']], $stmt->fetchAll());
        $this->view('programmes', ['programmes' => $programmes]);
    }

    public function detail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $stmt = $this->db->prepare('SELECT * FROM programme WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $s = $this->db->prepare('SELECT * FROM seance WHERE programmeId = :id ORDER BY ordre, id');
        $s->execute(['id' => $id]);
        $seances = array_map([Seance::class, 'fromArray'], $s->fetchAll());
        if (!$row || !$seances) {
            AuthController::flash('danger', 'Programme introuvable.');
            $this->redirect('index.php?page=programmes');
        }
        $inscription = null;
        if (AuthController::isAuthenticated()) {
            $i = $this->db->prepare('SELECT * FROM inscription WHERE programmeId = :p AND utilisateurId = :u');
            $i->execute(['p' => $id, 'u' => (int) $_SESSION['user_id']]);
            $inscription = $i->fetch() ?: null;
        }
        $this->view('programme', ['programme' => Programme::fromArray($row), 'seances' => $seances, 'inscription' => $inscription, 'csrfToken' => $this->csrf()]);
    }

    /** Programme existant ayant des séances, sinon retour à la liste. Renvoie le nombre de séances. */
    private function verifierProgramme(int $id): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM seance WHERE programmeId = :id');
        $stmt->execute(['id' => $id]);
        $nb = (int) $stmt->fetchColumn();
        if ($nb === 0) {
            AuthController::flash('danger', 'Programme introuvable.');
            $this->redirect('index.php?page=programmes');
        }
        return $nb;
    }

    public function inscrire(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->exigerPostValide('index.php?page=programme&id=' . $id);
        $this->verifierProgramme($id);
        $existe = $this->db->prepare('SELECT COUNT(*) FROM inscription WHERE programmeId = :p AND utilisateurId = :u');
        $existe->execute(['p' => $id, 'u' => (int) $_SESSION['user_id']]);
        if ((int) $existe->fetchColumn() === 0) {
            $this->db->prepare('INSERT INTO inscription (utilisateurId, programmeId, dateInscription, progression) VALUES (:u, :p, :d, 0)')
                ->execute(['u' => (int) $_SESSION['user_id'], 'p' => $id, 'd' => date('Y-m-d')]);
        }
        AuthController::flash('success', 'Vous êtes inscrit(e) ! Commencez par la première séance.');
        $this->redirect('index.php?page=programme&id=' . $id);
    }

    /** « J'ai terminé cette séance » : la progression avance d'une séance (sans dépasser le total). */
    public function avancer(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->exigerPostValide('index.php?page=programme&id=' . $id);
        $nb = $this->verifierProgramme($id);
        $this->db->prepare('UPDATE inscription SET progression = CASE WHEN progression < :nb1 THEN progression + 1 ELSE :nb2 END WHERE programmeId = :p AND utilisateurId = :u')
            ->execute(['nb1' => $nb, 'nb2' => $nb, 'p' => $id, 'u' => (int) $_SESSION['user_id']]);
        AuthController::flash('success', 'Bravo, séance terminée !');
        $this->redirect('index.php?page=programme&id=' . $id);
    }

    public function quitter(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $this->exigerPostValide('index.php?page=programme&id=' . $id);
        $this->db->prepare('DELETE FROM inscription WHERE programmeId = :p AND utilisateurId = :u')->execute(['p' => $id, 'u' => (int) $_SESSION['user_id']]);
        AuthController::flash('success', 'Vous avez quitté ce programme.');
        $this->redirect('index.php?page=programmes');
    }
}
