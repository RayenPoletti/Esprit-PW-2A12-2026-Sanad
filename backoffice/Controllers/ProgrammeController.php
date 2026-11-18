<?php

/**
 * Module 3 · responsable : Rayen Marzougi · composition Programme ◆— Séance (1 — 1..*).
 * CRUD des programmes. Un programme sans séance n'apparaît pas sur le site (multiplicité 1..*).
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
        $this->page  = 'programmes';
        $this->title = 'Programmes | Sanad Back Office';
        $q = trim((string) ($_GET['q'] ?? ''));
        $sql = 'SELECT pr.*,
                       (SELECT COUNT(*) FROM seance s WHERE s.programmeId = pr.id) AS nbSeances,
                       (SELECT COALESCE(SUM(s.dureeMin), 0) FROM seance s WHERE s.programmeId = pr.id) AS dureeTotale,
                       (SELECT COUNT(*) FROM inscription i WHERE i.programmeId = pr.id) AS nbInscrits
                  FROM programme pr';
        $params = [];
        if ($q !== '') {
            $sql .= ' WHERE pr.titre LIKE :q1 OR pr.description LIKE :q2';
            $params = ['q1' => "%$q%", 'q2' => "%$q%"];
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY pr.titre');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['programme' => Programme::fromArray($r), 'nbSeances' => (int) $r['nbSeances'],
            'duree' => (int) $r['dureeTotale'], 'nbInscrits' => (int) $r['nbInscrits']], $stmt->fetchAll());
        $this->render('programmes', ['lignes' => $lignes, 'q' => $q, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM programme WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Programme introuvable.');
            $this->redirect('index.php?page=programmes');
        }
        $this->formulaire(Programme::fromArray($row));
    }

    private function formulaire(?Programme $programme): void
    {
        $this->page  = $programme ? 'programmes' : 'programme-form';
        $this->title = ($programme ? 'Modifier un programme' : 'Ajouter un programme') . ' | Sanad Back Office';
        $old = $programme ? ['titre' => $programme->getTitre(), 'description' => $programme->getDescription(), 'niveau' => $programme->getNiveau()] : [];
        $formats = $programme ? array_filter(explode(',', (string) $programme->getFormats())) : [];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['titre', 'description', 'niveau']);
            $formats = array_values(array_intersect((array) ($_POST['formats'] ?? []), array_keys(Programme::FORMATS)));
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('titre', 'Le titre')->longueurMin('titre', 3, 'Le titre')->longueurMax('titre', 100, 'Le titre')
                  ->obligatoire('niveau', 'Le niveau')->dansListe('niveau', Programme::NIVEAUX, 'Le niveau')
                  ->obligatoire('description', 'La description')->longueurMin('description', 10, 'La description')->longueurMax('description', 1000, 'La description');
                if (!$formats) {
                    $v->erreur('formats', 'Choisissez au moins un format accessible.');
                }
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $p = $programme ?? new Programme();
                    $p->setTitre($v->valeur('titre'))->setDescription($v->valeur('description'))->setNiveau($v->valeur('niveau'))->setFormats(implode(',', $formats));
                    $donnees = ['titre' => $p->getTitre(), 'description' => $p->getDescription(), 'niveau' => $p->getNiveau(), 'formats' => $p->getFormats()];
                    if ($programme === null) {
                        $this->db->prepare('INSERT INTO programme (titre, description, niveau, formats) VALUES (:titre, :description, :niveau, :formats)')->execute($donnees);
                        AuthController::flash('success', 'Programme ajouté. Ajoutez-lui au moins une séance pour qu’il apparaisse sur le site.');
                        $this->redirect('index.php?page=seance-form&programme=' . (int) $this->db->lastInsertId());
                    }
                    $this->db->prepare('UPDATE programme SET titre = :titre, description = :description, niveau = :niveau, formats = :formats WHERE id = :id')
                        ->execute($donnees + ['id' => $p->getId()]);
                    AuthController::flash('success', 'Programme modifié.');
                    $this->redirect('index.php?page=programmes');
                }
            }
        }
        $this->render('programme_form', ['programme' => $programme, 'old' => $old, 'formats' => $formats, 'erreurs' => $erreurs, 'csrfToken' => $this->csrf()]);
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=programmes');
        // Composition : les séances et les inscriptions sont supprimées avec le programme (ON DELETE CASCADE)
        $this->db->prepare('DELETE FROM programme WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Programme supprimé, avec ses séances et ses inscriptions.');
        $this->redirect('index.php?page=programmes');
    }
}
