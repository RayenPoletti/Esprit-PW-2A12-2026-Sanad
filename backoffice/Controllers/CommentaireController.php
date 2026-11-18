<?php

/**
 * Module 4 · responsable : Houssem Hafsi · composition Publication ◆— Commentaire.
 * Modération des commentaires (écrits par les membres sur le site) : liste, modification, publier / masquer, suppression.
 */
class CommentaireController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'commentaires';
        $this->title = 'Commentaires | Sanad Back Office';
        $statut = (string) ($_GET['statut'] ?? '');
        if (!array_key_exists($statut, Commentaire::STATUTS)) {
            $statut = '';
        }
        // Jointure Commentaire → Publication et Commentaire → Utilisateur
        $sql = 'SELECT c.*, u.prenom, u.nom, p.contenu AS publicationContenu
                  FROM commentaire c INNER JOIN utilisateur u ON u.id = c.utilisateurId INNER JOIN publication p ON p.id = c.publicationId';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE c.statut = :statut';
            $params['statut'] = $statut;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY c.dateCommentaire DESC');
        $stmt->execute($params);
        $lignes = array_map(fn($r) => ['commentaire' => Commentaire::fromArray($r), 'auteur' => trim($r['prenom'] . ' ' . $r['nom']),
            'publication' => mb_strimwidth($r['publicationContenu'], 0, 60, '…')], $stmt->fetchAll());
        $this->render('commentaires', ['lignes' => $lignes, 'statut' => $statut, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    public function edit(): void
    {
        $this->page  = 'commentaires';
        $this->title = 'Modifier un commentaire | Sanad Back Office';
        $stmt = $this->db->prepare('SELECT * FROM commentaire WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Commentaire introuvable.');
            $this->redirect('index.php?page=commentaires');
        }
        $com = Commentaire::fromArray($row);
        $old = ['contenu' => $com->getContenu(), 'statut' => $com->getStatut()];
        $erreurs = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['contenu', 'statut']);
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('contenu', 'Le commentaire')->longueurMin('contenu', 2, 'Le commentaire')->longueurMax('contenu', 500, 'Le commentaire')
                  ->obligatoire('statut', 'Le statut')->dansListe('statut', array_keys(Commentaire::STATUTS), 'Le statut');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    $com->setContenu($v->valeur('contenu'))->setStatut($v->valeur('statut'));
                    $this->db->prepare('UPDATE commentaire SET contenu = :c, statut = :s WHERE id = :id')
                        ->execute(['c' => $com->getContenu(), 's' => $com->getStatut(), 'id' => $com->getId()]);
                    AuthController::flash('success', 'Commentaire modifié.');
                    $this->redirect('index.php?page=commentaires');
                }
            }
        }
        $this->render('commentaire_form', ['commentaire' => $com, 'old' => $old, 'erreurs' => $erreurs, 'csrfToken' => $this->csrf()]);
    }

    public function statut(): void
    {
        $this->exigerPostValide('index.php?page=commentaires');
        $nouveau = (string) ($_POST['statut'] ?? '');
        if (!array_key_exists($nouveau, Commentaire::STATUTS)) {
            AuthController::flash('danger', 'Statut inconnu.');
            $this->redirect('index.php?page=commentaires');
        }
        $this->db->prepare('UPDATE commentaire SET statut = :s WHERE id = :id')->execute(['s' => $nouveau, 'id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Commentaire : ' . mb_strtolower(Commentaire::STATUTS[$nouveau]) . '.');
        $this->redirect('index.php?page=commentaires');
    }

    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=commentaires');
        $this->db->prepare('DELETE FROM commentaire WHERE id = :id')->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        AuthController::flash('success', 'Commentaire supprimé.');
        $this->redirect('index.php?page=commentaires');
    }
}
