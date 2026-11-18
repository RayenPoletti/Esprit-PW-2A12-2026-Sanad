<?php

/**
 * Module 1 · responsable : Rayen Poletti · jointure Lieu ↔ Audit.
 * CRUD des lieux de soin. Le score d'accessibilité n'est pas saisi : il est calculé à partir des audits validés.
 */
class LieuController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'lieux';
        $this->title = 'Lieux de soin | Sanad Back Office';
        $q = trim((string) ($_GET['q'] ?? ''));

        // Jointure Lieu ↔ Audit : nombre d'audits et d'audits validés par lieu
        $sql = "SELECT l.*, COUNT(a.id) AS nbAudits, SUM(CASE WHEN a.statut = 'valide' THEN 1 ELSE 0 END) AS nbValides
                  FROM lieu l LEFT JOIN audit a ON a.lieuId = l.id";
        $params = [];
        if ($q !== '') {
            $sql .= ' WHERE l.nom LIKE :q1 OR l.ville LIKE :q2 OR l.adresse LIKE :q3';
            $params = ['q1' => "%$q%", 'q2' => "%$q%", 'q3' => "%$q%"];
        }
        $stmt = $this->db->prepare($sql . ' GROUP BY l.id, l.nom, l.adresse, l.ville, l.latitude, l.longitude, l.scoreAccessibilite ORDER BY l.nom');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['lieu' => Lieu::fromArray($r), 'nbAudits' => (int) $r['nbAudits'], 'nbValides' => (int) $r['nbValides']], $stmt->fetchAll());

        $this->render('lieux', ['lignes' => $lignes, 'q' => $q, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM lieu WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Lieu introuvable.');
            $this->redirect('index.php?page=lieux');
        }
        $this->formulaire(Lieu::fromArray($row));
    }

    private function formulaire(?Lieu $lieu): void
    {
        $this->page  = $lieu ? 'lieux' : 'lieu-form';
        $this->title = ($lieu ? 'Modifier un lieu' : 'Ajouter un lieu') . ' | Sanad Back Office';
        $champs = ['nom', 'adresse', 'ville', 'latitude', 'longitude'];
        $old = $lieu ? ['nom' => $lieu->getNom(), 'adresse' => $lieu->getAdresse(), 'ville' => $lieu->getVille(),
            'latitude' => (string) ($lieu->getLatitude() ?? ''), 'longitude' => (string) ($lieu->getLongitude() ?? '')] : [];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie($champs);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('nom', 'Le nom du lieu')->longueurMin('nom', 2, 'Le nom du lieu')->longueurMax('nom', 100, 'Le nom du lieu')
                  ->obligatoire('adresse', 'L’adresse')->longueurMin('adresse', 5, 'L’adresse')->longueurMax('adresse', 200, 'L’adresse')
                  ->obligatoire('ville', 'La ville')->nom('ville', 'La ville')
                  ->decimal('latitude', -90, 90, 'La latitude')
                  ->decimal('longitude', -180, 180, 'La longitude');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $l = $lieu ?? new Lieu();
                    $l->setNom($v->valeur('nom'))->setAdresse($v->valeur('adresse'))->setVille($v->valeur('ville'))
                      ->setLatitude($v->nombre('latitude'))->setLongitude($v->nombre('longitude'));
                    $donnees = ['nom' => $l->getNom(), 'adresse' => $l->getAdresse(), 'ville' => $l->getVille(),
                        'latitude' => $l->getLatitude(), 'longitude' => $l->getLongitude()];
                    if ($lieu === null) {
                        $this->db->prepare('INSERT INTO lieu (nom, adresse, ville, latitude, longitude) VALUES (:nom, :adresse, :ville, :latitude, :longitude)')->execute($donnees);
                    } else {
                        $this->db->prepare('UPDATE lieu SET nom = :nom, adresse = :adresse, ville = :ville, latitude = :latitude, longitude = :longitude WHERE id = :id')
                            ->execute($donnees + ['id' => $l->getId()]);
                    }
                    AuthController::flash('success', $lieu ? 'Lieu modifié.' : 'Lieu ajouté.');
                    $this->redirect('index.php?page=lieux');
                }
            }
        }
        $this->render('lieu_form', ['lieu' => $lieu, 'old' => $old, 'erreurs' => $erreurs, 'csrfToken' => $this->csrf()]);
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=lieux');
        try {
            $this->db->prepare('DELETE FROM lieu WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
            AuthController::flash('success', 'Lieu supprimé, avec ses audits.');
        } catch (PDOException $ex) {
            AuthController::flash('danger', 'Impossible de supprimer ce lieu : des professionnels y exercent. Changez d’abord leur lieu.');
        }
        $this->redirect('index.php?page=lieux');
    }
}
