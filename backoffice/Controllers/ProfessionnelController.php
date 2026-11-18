<?php

/**
 * Module 2 · responsable : Aziz Kouki · jointure Professionnel ↔ RendezVous.
 * CRUD des professionnels de santé. Seuls les professionnels « validés » sont visibles sur le site.
 */
class ProfessionnelController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'professionnels';
        $this->title = 'Professionnels | Sanad Back Office';
        $q = trim((string) ($_GET['q'] ?? ''));
        // Jointure avec le lieu d'exercice + nombre de rendez-vous (Professionnel 1 — 0..* RendezVous)
        $sql = 'SELECT p.*, l.nom AS lieuNom, l.ville AS lieuVille,
                       (SELECT COUNT(*) FROM rendez_vous r WHERE r.professionnelId = p.id) AS nbRdv
                  FROM professionnel p INNER JOIN lieu l ON l.id = p.lieuId';
        $params = [];
        if ($q !== '') {
            $sql .= ' WHERE p.nom LIKE :q1 OR p.prenom LIKE :q2 OR p.specialite LIKE :q3 OR l.ville LIKE :q4';
            $params = ['q1' => "%$q%", 'q2' => "%$q%", 'q3' => "%$q%", 'q4' => "%$q%"];
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY p.nom, p.prenom');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['pro' => Professionnel::fromArray($r), 'lieu' => $r['lieuNom'] . ' · ' . $r['lieuVille'], 'nbRdv' => (int) $r['nbRdv']], $stmt->fetchAll());
        $this->render('professionnels', ['lignes' => $lignes, 'q' => $q, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM professionnel WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Professionnel introuvable.');
            $this->redirect('index.php?page=professionnels');
        }
        $this->formulaire(Professionnel::fromArray($row));
    }

    private function formulaire(?Professionnel $pro): void
    {
        $this->page  = $pro ? 'professionnels' : 'professionnel-form';
        $this->title = ($pro ? 'Modifier un professionnel' : 'Ajouter un professionnel') . ' | Sanad Back Office';
        $lieux = [];
        foreach ($this->db->query('SELECT id, nom, ville FROM lieu ORDER BY ville, nom')->fetchAll() as $r) {
            $lieux[$r['id']] = $r['nom'] . ' · ' . $r['ville'];
        }
        $old = $pro ? ['nom' => $pro->getNom(), 'prenom' => $pro->getPrenom(), 'specialite' => $pro->getSpecialite(),
            'telephone' => $pro->getTelephone(), 'lieuId' => (string) $pro->getLieuId()] : [];
        $valide = $pro ? (bool) $pro->isValide() : false;
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['nom', 'prenom', 'specialite', 'telephone', 'lieuId']);
            $valide = isset($_POST['valide']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('nom', 'Le nom')->nom('nom', 'Le nom')
                  ->obligatoire('prenom', 'Le prénom')->nom('prenom', 'Le prénom')
                  ->obligatoire('specialite', 'La spécialité')->dansListe('specialite', Professionnel::SPECIALITES, 'La spécialité')
                  ->obligatoire('telephone', 'Le téléphone')->telephone('telephone')
                  ->obligatoire('lieuId', 'Le lieu d’exercice')->dansListe('lieuId', array_map('strval', array_keys($lieux)), 'Le lieu d’exercice');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $p = $pro ?? new Professionnel();
                    $p->setNom($v->valeur('nom'))->setPrenom($v->valeur('prenom'))->setSpecialite($v->valeur('specialite'))
                      ->setTelephone($v->valeur('telephone'))->setLieuId((int) $v->valeur('lieuId'))->setValide($valide);
                    $donnees = ['nom' => $p->getNom(), 'prenom' => $p->getPrenom(), 'specialite' => $p->getSpecialite(),
                        'telephone' => $p->getTelephone(), 'lieuId' => $p->getLieuId(), 'valide' => (int) $p->isValide()];
                    if ($pro === null) {
                        $this->db->prepare('INSERT INTO professionnel (nom, prenom, specialite, telephone, lieuId, valide) VALUES (:nom, :prenom, :specialite, :telephone, :lieuId, :valide)')->execute($donnees);
                    } else {
                        $this->db->prepare('UPDATE professionnel SET nom = :nom, prenom = :prenom, specialite = :specialite, telephone = :telephone, lieuId = :lieuId, valide = :valide WHERE id = :id')
                            ->execute($donnees + ['id' => $p->getId()]);
                    }
                    AuthController::flash('success', $pro ? 'Professionnel modifié.' : 'Professionnel ajouté.');
                    $this->redirect('index.php?page=professionnels');
                }
            }
        }
        $this->render('professionnel_form', ['pro' => $pro, 'old' => $old, 'valide' => $valide, 'erreurs' => $erreurs, 'lieux' => $lieux, 'csrfToken' => $this->csrf()]);
    }

    /** Valider / retirer la validation (le professionnel apparaît ou disparaît du site). */
    public function toggle(): void
    {
        $this->exigerPostValide('index.php?page=professionnels');
        $this->db->prepare('UPDATE professionnel SET valide = 1 - valide WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Statut de validation modifié.');
        $this->redirect('index.php?page=professionnels');
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=professionnels');
        try {
            $this->db->prepare('DELETE FROM professionnel WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
            AuthController::flash('success', 'Professionnel supprimé.');
        } catch (PDOException $ex) {
            AuthController::flash('danger', 'Impossible de supprimer ce professionnel : il a des rendez-vous. Retirez plutôt sa validation.');
        }
        $this->redirect('index.php?page=professionnels');
    }
}
