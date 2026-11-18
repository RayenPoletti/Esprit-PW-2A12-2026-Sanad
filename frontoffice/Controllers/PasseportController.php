<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module commun (toute l'équipe) · passeport d'accessibilité du membre connecté.
 * Utilisateur 1 — 0..1 Passeport : création au premier enregistrement, puis modification ; suppression possible.
 * Le membre ne voit et ne modifie QUE son propre passeport (utilisateurId pris dans la session, jamais dans le formulaire).
 */
class PasseportController extends Controller
{
    private AuthController $auth;
    private PDO $db;

    public function __construct()
    {
        $this->auth = new AuthController();
        $this->db   = Database::getConnexion();
    }

    private function monPasseport(): ?Passeport
    {
        $stmt = $this->db->prepare('SELECT * FROM passeport WHERE utilisateurId = :id LIMIT 1');
        $stmt->execute(['id' => (int) $_SESSION['user_id']]);
        $row = $stmt->fetch();
        return $row ? Passeport::fromArray($row) : null;
    }

    public function index(): void
    {
        $passeport = $this->monPasseport();
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $besoinsCoches = array_intersect((array) ($_POST['besoins'] ?? []), array_keys(Passeport::BESOINS));
                $v = new Validator($_POST);
                $v->longueurMax('precisions', 500, 'Le champ « Précisions »');
                if (!$besoinsCoches && $v->valeur('precisions') === '') {
                    $v->erreur('besoins', 'Cochez au moins un besoin ou écrivez une précision.');
                }
                $erreurs = $v->erreurs();

                // Objet Passeport rempli avec les setters, puis enregistré
                $p = $passeport ?? new Passeport();
                foreach (array_keys(Passeport::BESOINS) as $besoin) {
                    $p->setBesoin($besoin, in_array($besoin, $besoinsCoches, true));
                }
                $p->setPrecisions($v->valeur('precisions'));
                $p->setPartageAutorise(isset($_POST['partageAutorise']));

                if (empty($erreurs)) {
                    $this->enregistrer($p, $passeport === null);
                    AuthController::flash('success', $passeport ? 'Votre passeport est mis à jour.' : 'Votre passeport est créé.');
                    $this->redirect('index.php?page=passeport');
                }
                $passeport = $p; // réaffiche les choix du membre avec les erreurs
            }
        }

        $this->view('passeport', ['passeport' => $passeport, 'erreurs' => $erreurs, 'csrfToken' => $this->auth->getCsrfToken()]);
    }

    private function enregistrer(Passeport $p, bool $nouveau): void
    {
        $donnees = ['precisions' => $p->getPrecisions(), 'partageAutorise' => (int) $p->isPartageAutorise(), 'utilisateurId' => (int) $_SESSION['user_id']];
        foreach (array_keys(Passeport::BESOINS) as $besoin) {
            $donnees[$besoin] = (int) $p->getBesoin($besoin);
        }
        if ($nouveau) {
            $sql = 'INSERT INTO passeport (fauteuilRoulant, mobiliteReduite, malvoyance, surdite, accompagneAidant, precisions, partageAutorise, utilisateurId)
                    VALUES (:fauteuilRoulant, :mobiliteReduite, :malvoyance, :surdite, :accompagneAidant, :precisions, :partageAutorise, :utilisateurId)';
        } else {
            $sql = 'UPDATE passeport SET fauteuilRoulant = :fauteuilRoulant, mobiliteReduite = :mobiliteReduite, malvoyance = :malvoyance,
                    surdite = :surdite, accompagneAidant = :accompagneAidant, precisions = :precisions, partageAutorise = :partageAutorise,
                    dateMiseAJour = CURRENT_TIMESTAMP WHERE utilisateurId = :utilisateurId';
        }
        $this->db->prepare($sql)->execute($donnees);
    }

    public function supprimer(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            AuthController::flash('danger', 'Action refusée : requête invalide.');
            $this->redirect('index.php?page=passeport');
        }
        $this->db->prepare('DELETE FROM passeport WHERE utilisateurId = :id')->execute(['id' => (int) $_SESSION['user_id']]);
        AuthController::flash('success', 'Votre passeport est supprimé.');
        $this->redirect('index.php?page=passeport');
    }
}
