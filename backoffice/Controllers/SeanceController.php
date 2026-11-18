<?php

/**
 * Module 3 · responsable : Rayen Marzougi · composition Programme ◆— Séance.
 * CRUD des séances (filtrables par programme).
 */
class SeanceController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    private function programmes(): array
    {
        $liste = [];
        foreach ($this->db->query('SELECT id, titre FROM programme ORDER BY titre')->fetchAll() as $r) {
            $liste[$r['id']] = $r['titre'];
        }
        return $liste;
    }

    public function index(): void
    {
        $this->page  = 'seances';
        $this->title = 'Séances | Sanad Back Office';
        $programmes = $this->programmes();
        $filtre = (int) ($_GET['programme'] ?? 0);
        if (!isset($programmes[$filtre])) {
            $filtre = 0;
        }
        $sql = 'SELECT s.*, p.titre AS programmeTitre FROM seance s INNER JOIN programme p ON p.id = s.programmeId';
        $params = [];
        if ($filtre) {
            $sql .= ' WHERE s.programmeId = :p';
            $params['p'] = $filtre;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY p.titre, s.ordre');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['seance' => Seance::fromArray($r), 'programme' => $r['programmeTitre']], $stmt->fetchAll());
        $this->render('seances', ['lignes' => $lignes, 'programmes' => $programmes, 'filtre' => $filtre, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM seance WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Séance introuvable.');
            $this->redirect('index.php?page=seances');
        }
        $this->formulaire(Seance::fromArray($row));
    }

    private function formulaire(?Seance $seance): void
    {
        $this->page  = $seance ? 'seances' : 'seance-form';
        $this->title = ($seance ? 'Modifier une séance' : 'Ajouter une séance') . ' | Sanad Back Office';
        $programmes = $this->programmes();
        if (!$programmes) {
            AuthController::flash('info', 'Créez d’abord un programme.');
            $this->redirect('index.php?page=programme-form');
        }
        $old = $seance ? ['programmeId' => (string) $seance->getProgrammeId(), 'titre' => $seance->getTitre(), 'ordre' => (string) $seance->getOrdre(),
            'dureeMin' => (string) $seance->getDureeMin(), 'urlVideo' => $seance->getUrlVideo(), 'urlAudio' => $seance->getUrlAudio()]
            : ['programmeId' => (string) ($_GET['programme'] ?? ''), 'ordre' => '1'];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['programmeId', 'titre', 'ordre', 'dureeMin', 'urlVideo', 'urlAudio']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('programmeId', 'Le programme')->dansListe('programmeId', array_map('strval', array_keys($programmes)), 'Le programme')
                  ->obligatoire('titre', 'Le titre')->longueurMin('titre', 3, 'Le titre')->longueurMax('titre', 100, 'Le titre')
                  ->obligatoire('ordre', 'L’ordre')->entier('ordre', 1, 100, 'L’ordre')
                  ->obligatoire('dureeMin', 'La durée')->entier('dureeMin', 1, 180, 'La durée')
                  ->url('urlVideo', 'Le lien de la vidéo')
                  ->url('urlAudio', 'Le lien de l’audio');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $s = $seance ?? new Seance();
                    $s->setProgrammeId((int) $v->valeur('programmeId'))->setTitre($v->valeur('titre'))->setOrdre((int) $v->valeur('ordre'))
                      ->setDureeMin((int) $v->valeur('dureeMin'))->setUrlVideo($v->valeur('urlVideo'))->setUrlAudio($v->valeur('urlAudio'));
                    $donnees = ['programmeId' => $s->getProgrammeId(), 'titre' => $s->getTitre(), 'ordre' => $s->getOrdre(), 'dureeMin' => $s->getDureeMin(),
                        'urlVideo' => $s->getUrlVideo(), 'urlAudio' => $s->getUrlAudio()];
                    if ($seance === null) {
                        $this->db->prepare('INSERT INTO seance (programmeId, titre, ordre, dureeMin, urlVideo, urlAudio) VALUES (:programmeId, :titre, :ordre, :dureeMin, :urlVideo, :urlAudio)')->execute($donnees);
                    } else {
                        $this->db->prepare('UPDATE seance SET programmeId = :programmeId, titre = :titre, ordre = :ordre, dureeMin = :dureeMin, urlVideo = :urlVideo, urlAudio = :urlAudio WHERE id = :id')
                            ->execute($donnees + ['id' => $s->getId()]);
                    }
                    AuthController::flash('success', $seance ? 'Séance modifiée.' : 'Séance ajoutée.');
                    $this->redirect('index.php?page=seances&programme=' . $s->getProgrammeId());
                }
            }
        }
        $this->render('seance_form', ['seance' => $seance, 'old' => $old, 'erreurs' => $erreurs, 'programmes' => $programmes, 'csrfToken' => $this->csrf()]);
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=seances');
        $this->db->prepare('DELETE FROM seance WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Séance supprimée.');
        $this->redirect('index.php?page=seances');
    }
}
