<?php

/**
 * Module 4 · responsable : Houssem Hafsi · composition Publication ◆— Commentaire.
 * Modération des publications : liste (filtre par statut), ajout, modification, publier / masquer, suppression.
 */
class PublicationController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'publications';
        $this->title = 'Publications | Sanad Back Office';
        $statut = (string) ($_GET['statut'] ?? '');
        if (!array_key_exists($statut, Publication::STATUTS)) {
            $statut = '';
        }
        $sql = 'SELECT p.*, u.prenom, u.nom, (SELECT COUNT(*) FROM commentaire c WHERE c.publicationId = p.id) AS nbCommentaires
                  FROM publication p INNER JOIN utilisateur u ON u.id = p.utilisateurId';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE p.statut = :statut';
            $params['statut'] = $statut;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY p.datePublication DESC');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['publication' => Publication::fromArray($r), 'auteur' => trim($r['prenom'] . ' ' . $r['nom']),
            'nbCommentaires' => (int) $r['nbCommentaires']], $stmt->fetchAll());
        $this->render('publications', ['lignes' => $lignes, 'statut' => $statut, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM publication WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Publication introuvable.');
            $this->redirect('index.php?page=publications');
        }
        $this->formulaire(Publication::fromArray($row));
    }

    private function formulaire(?Publication $pub): void
    {
        $this->page  = $pub ? 'publications' : 'publication-form';
        $this->title = ($pub ? 'Modifier une publication' : 'Ajouter une publication') . ' | Sanad Back Office';
        $auteurs = [];
        foreach ($this->db->query('SELECT id, prenom, nom FROM utilisateur ORDER BY prenom')->fetchAll() as $r) {
            $auteurs[$r['id']] = $r['prenom'] . ' ' . $r['nom'];
        }
        $old = $pub ? ['utilisateurId' => (string) $pub->getUtilisateurId(), 'contenu' => $pub->getContenu(), 'statut' => $pub->getStatut()]
            : ['utilisateurId' => (string) $_SESSION['user_id'], 'statut' => 'publie'];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['utilisateurId', 'contenu', 'statut']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('utilisateurId', 'L’auteur')->dansListe('utilisateurId', array_map('strval', array_keys($auteurs)), 'L’auteur')
                  ->obligatoire('contenu', 'Le contenu')->longueurMin('contenu', 10, 'Le contenu')->longueurMax('contenu', 2000, 'Le contenu')
                  ->obligatoire('statut', 'Le statut')->dansListe('statut', array_keys(Publication::STATUTS), 'Le statut');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $p = $pub ?? new Publication();
                    $p->setUtilisateurId((int) $v->valeur('utilisateurId'))->setContenu($v->valeur('contenu'))->setStatut($v->valeur('statut'));
                    $donnees = ['u' => $p->getUtilisateurId(), 'c' => $p->getContenu(), 's' => $p->getStatut()];
                    if ($pub === null) {
                        $this->db->prepare('INSERT INTO publication (utilisateurId, contenu, statut, datePublication) VALUES (:u, :c, :s, :d)')->execute($donnees + ['d' => date('Y-m-d H:i:s')]);
                    } else {
                        $this->db->prepare('UPDATE publication SET utilisateurId = :u, contenu = :c, statut = :s WHERE id = :id')->execute($donnees + ['id' => $p->getId()]);
                    }
                    AuthController::flash('success', $pub ? 'Publication modifiée.' : 'Publication ajoutée.');
                    $this->redirect('index.php?page=publications');
                }
            }
        }
        $this->render('publication_form', ['publication' => $pub, 'old' => $old, 'erreurs' => $erreurs, 'auteurs' => $auteurs, 'csrfToken' => $this->csrf()]);
    }

    public function statut(): void
    {
        $this->exigerPostValide('index.php?page=publications');
        $nouveau = (string) ($_POST['statut'] ?? '');
        if (!array_key_exists($nouveau, Publication::STATUTS)) {
            AuthController::flash('danger', 'Statut inconnu.');
            $this->redirect('index.php?page=publications');
        }
        $this->db->prepare('UPDATE publication SET statut = :s WHERE id = :id')->execute(['s' => $nouveau, 'id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Publication : ' . mb_strtolower(Publication::STATUTS[$nouveau]) . '.');
        $this->redirect('index.php?page=publications');
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=publications');
        $this->db->prepare('DELETE FROM publication WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Publication supprimée, avec ses commentaires.');
        $this->redirect('index.php?page=publications');
    }
}
