<?php

/**
 * Module 1 · responsable : Rayen Poletti · jointure Lieu ↔ Audit.
 * Gestion des audits : liste (filtre par statut), ajout, modification, validation / refus, suppression.
 * Règles métier :
 *   - quand un audit passe à « validé », l'ambassadeur gagne Audit::POINTS_AUDIT points (retirés s'il n'est plus validé) ;
 *   - le score d'accessibilité du lieu est recalculé à chaque changement (moyenne des audits validés, sur 5).
 */
class AuditController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'audits';
        $this->title = 'Audits d’accessibilité | Sanad Back Office';
        $statut = (string) ($_GET['statut'] ?? '');
        if (!array_key_exists($statut, Audit::STATUTS)) {
            $statut = '';
        }
        // Jointure Audit → Lieu et Audit → Utilisateur (ambassadeur)
        $sql = 'SELECT a.*, l.nom AS lieuNom, l.ville AS lieuVille, u.prenom, u.nom
                  FROM audit a INNER JOIN lieu l ON l.id = a.lieuId INNER JOIN utilisateur u ON u.id = a.utilisateurId';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE a.statut = :statut';
            $params['statut'] = $statut;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY a.dateAudit DESC, a.id DESC');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['audit' => Audit::fromArray($r), 'lieu' => $r['lieuNom'] . ' · ' . $r['lieuVille'],
            'ambassadeur' => trim($r['prenom'] . ' ' . $r['nom'])], $stmt->fetchAll());

        $this->render('audits', ['lignes' => $lignes, 'statut' => $statut, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $audit = $this->trouver((int) ($_GET['id'] ?? 0));
        $this->formulaire($audit);
    }

    private function trouver(int $id): Audit
    {
        $stmt = $this->db->prepare('SELECT * FROM audit WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Audit introuvable.');
            $this->redirect('index.php?page=audits');
        }
        return Audit::fromArray($row);
    }

    private function formulaire(?Audit $audit): void
    {
        $this->page  = $audit ? 'audits' : 'audit-form';
        $this->title = ($audit ? 'Modifier un audit' : 'Ajouter un audit') . ' | Sanad Back Office';

        $lieux = [];
        foreach ($this->db->query('SELECT id, nom, ville FROM lieu ORDER BY nom')->fetchAll() as $r) {
            $lieux[$r['id']] = $r['nom'] . ' · ' . $r['ville'];
        }
        $ambassadeurs = [];
        foreach ($this->db->query("SELECT id, prenom, nom FROM utilisateur WHERE role = 'ambassadeur' ORDER BY prenom")->fetchAll() as $r) {
            $ambassadeurs[$r['id']] = $r['prenom'] . ' ' . $r['nom'];
        }

        $old = $audit ? ['lieuId' => (string) $audit->getLieuId(), 'utilisateurId' => (string) $audit->getUtilisateurId(),
            'dateAudit' => $audit->getDateAudit(), 'statut' => $audit->getStatut(), 'commentaire' => $audit->getCommentaire()]
            : ['dateAudit' => date('Y-m-d'), 'statut' => 'en_attente'];
        $criteres = [];
        foreach (array_keys(Audit::CRITERES) as $c) {
            $criteres[$c] = $audit ? $audit->getCritere($c) : false;
        }
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['lieuId', 'utilisateurId', 'dateAudit', 'statut', 'commentaire']);
            $coches = array_intersect((array) ($_POST['criteres'] ?? []), array_keys(Audit::CRITERES));
            foreach (array_keys(Audit::CRITERES) as $c) {
                $criteres[$c] = in_array($c, $coches, true);
            }
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('lieuId', 'Le lieu')->dansListe('lieuId', array_map('strval', array_keys($lieux)), 'Le lieu')
                  ->obligatoire('utilisateurId', 'L’ambassadeur')->dansListe('utilisateurId', array_map('strval', array_keys($ambassadeurs)), 'L’ambassadeur')
                  ->obligatoire('dateAudit', 'La date de l’audit')->datePassee('dateAudit', 'La date de l’audit')
                  ->obligatoire('statut', 'Le statut')->dansListe('statut', array_keys(Audit::STATUTS), 'Le statut')
                  ->obligatoire('commentaire', 'Le commentaire')->longueurMin('commentaire', 10, 'Le commentaire')->longueurMax('commentaire', 1000, 'Le commentaire');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $this->enregistrer($audit, $v, $criteres);
                    AuthController::flash('success', $audit ? 'Audit modifié.' : 'Audit ajouté.');
                    $this->redirect('index.php?page=audits');
                }
            }
        }
        $this->render('audit_form', ['audit' => $audit, 'old' => $old, 'criteres' => $criteres, 'erreurs' => $erreurs,
            'lieux' => $lieux, 'ambassadeurs' => $ambassadeurs, 'csrfToken' => $this->csrf()]);
    }

    private function enregistrer(?Audit $ancien, Validator $v, array $criteres): void
    {
        $a = new Audit();
        $a->setLieuId((int) $v->valeur('lieuId'))->setUtilisateurId((int) $v->valeur('utilisateurId'))
          ->setDateAudit($v->valeur('dateAudit'))->setCommentaire($v->valeur('commentaire'));
        foreach ($criteres as $c => $ok) {
            $a->setCritere($c, $ok);
        }
        $donnees = ['lieuId' => $a->getLieuId(), 'utilisateurId' => $a->getUtilisateurId(), 'dateAudit' => $a->getDateAudit(),
            'commentaire' => $a->getCommentaire()];
        foreach (array_keys(Audit::CRITERES) as $c) {
            $donnees[$c] = (int) $a->getCritere($c);
        }

        $this->db->beginTransaction();
        if ($ancien === null) {
            $this->db->prepare('INSERT INTO audit (lieuId, utilisateurId, dateAudit, commentaire, rampe, porteLarge, ascenseur, toilettesAdaptees, parkingPMR, accueilLSF, statut)
                VALUES (:lieuId, :utilisateurId, :dateAudit, :commentaire, :rampe, :porteLarge, :ascenseur, :toilettesAdaptees, :parkingPMR, :accueilLSF, \'en_attente\')')
                ->execute($donnees);
            $ancien = $this->trouver((int) $this->db->lastInsertId());
        } else {
            $this->db->prepare('UPDATE audit SET lieuId = :lieuId, utilisateurId = :utilisateurId, dateAudit = :dateAudit, commentaire = :commentaire,
                rampe = :rampe, porteLarge = :porteLarge, ascenseur = :ascenseur, toilettesAdaptees = :toilettesAdaptees,
                parkingPMR = :parkingPMR, accueilLSF = :accueilLSF WHERE id = :id')->execute($donnees + ['id' => $ancien->getId()]);
            $this->recalculerScore($ancien->getLieuId()); // l'audit a peut-être changé de lieu
        }
        $this->changerStatut($ancien, $v->valeur('statut'));
        $this->recalculerScore($a->getLieuId());
        $this->db->commit();
    }

    /** Valider ou refuser depuis la liste (POST + CSRF). */
    public function statut(): void
    {
        $this->exigerPostValide('index.php?page=audits');
        $nouveau = (string) ($_POST['statut'] ?? '');
        if (!array_key_exists($nouveau, Audit::STATUTS)) {
            AuthController::flash('danger', 'Statut inconnu.');
            $this->redirect('index.php?page=audits');
        }
        $audit = $this->trouver((int) ($_POST['id'] ?? 0));
        $this->db->beginTransaction();
        $this->changerStatut($audit, $nouveau);
        $this->recalculerScore($audit->getLieuId());
        $this->db->commit();
        AuthController::flash('success', 'Audit ' . mb_strtolower(Audit::STATUTS[$nouveau])
            . ($nouveau === 'valide' && $audit->getStatut() !== 'valide' ? ' : +' . Audit::POINTS_AUDIT . ' points pour l’ambassadeur.' : '.'));
        $this->redirect('index.php?page=audits');
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=audits');
        $audit = $this->trouver((int) ($_POST['id'] ?? 0));
        $this->db->beginTransaction();
        $this->changerStatut($audit, 'refuse'); // retire les points si l'audit était validé
        $this->db->prepare('DELETE FROM audit WHERE id = :id')->execute(['id' => $audit->getId()]);
        $this->recalculerScore($audit->getLieuId());
        $this->db->commit();
        AuthController::flash('success', 'Audit supprimé.');
        $this->redirect('index.php?page=audits');
    }

    /** Change le statut et ajuste les points solidaires de l'ambassadeur. */
    private function changerStatut(Audit $audit, string $nouveau): void
    {
        $etaitValide = $audit->getStatut() === 'valide';
        $this->db->prepare('UPDATE audit SET statut = :statut WHERE id = :id')->execute(['statut' => $nouveau, 'id' => $audit->getId()]);
        if (!$etaitValide && $nouveau === 'valide') {
            $this->db->prepare('UPDATE utilisateur SET points = points + :p WHERE id = :id')
                ->execute(['p' => Audit::POINTS_AUDIT, 'id' => $audit->getUtilisateurId()]);
        } elseif ($etaitValide && $nouveau !== 'valide') {
            $this->db->prepare('UPDATE utilisateur SET points = CASE WHEN points >= :p1 THEN points - :p2 ELSE 0 END WHERE id = :id')
                ->execute(['p1' => Audit::POINTS_AUDIT, 'p2' => Audit::POINTS_AUDIT, 'id' => $audit->getUtilisateurId()]);
        }
    }

    /** Score du lieu = moyenne, sur 5, des audits validés (NULL s'il n'y en a aucun). */
    private function recalculerScore(int $lieuId): void
    {
        $this->db->prepare(
            "UPDATE lieu SET scoreAccessibilite = (
                SELECT ROUND(AVG((rampe + porteLarge + ascenseur + toilettesAdaptees + parkingPMR + accueilLSF) * 5.0 / 6), 1)
                  FROM audit WHERE lieuId = :id1 AND statut = 'valide')
             WHERE id = :id2"
        )->execute(['id1' => $lieuId, 'id2' => $lieuId]);
    }
}
