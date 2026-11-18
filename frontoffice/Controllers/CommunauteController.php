<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module 4 · Houssem Hafsi · Publication ◆— Commentaire — communauté d'entraide du Front Office.
 * Lecture ouverte à tous ; publier, commenter, signaler et supprimer ses messages : membre connecté.
 * Les messages masqués par la modération (Back Office) n'apparaissent plus.
 */
class CommunauteController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $pubs = $this->db->query(
            "SELECT p.*, u.prenom, u.nom FROM publication p INNER JOIN utilisateur u ON u.id = p.utilisateurId
              WHERE p.statut <> 'masque' ORDER BY p.datePublication DESC LIMIT 30"
        )->fetchAll();
        $coms = $this->db->query(
            "SELECT c.*, u.prenom, u.nom FROM commentaire c INNER JOIN utilisateur u ON u.id = c.utilisateurId
              WHERE c.statut <> 'masque' ORDER BY c.dateCommentaire"
        )->fetchAll();
        $parPublication = [];
        foreach ($coms as $c) {
            $parPublication[(int) $c['publicationId']][] = ['commentaire' => Commentaire::fromArray($c), 'auteur' => $c['prenom'] . ' ' . mb_substr($c['nom'], 0, 1) . '.'];
        }
        $publications = array_map(fn($p) => ['publication' => Publication::fromArray($p), 'auteur' => $p['prenom'] . ' ' . mb_substr($p['nom'], 0, 1) . '.',
            'initiale' => mb_strtoupper(mb_substr($p['prenom'], 0, 1)), 'commentaires' => $parPublication[(int) $p['id']] ?? []], $pubs);

        $this->view('communaute', ['publications' => $publications, 'erreurs' => $_SESSION['erreurs_communaute'] ?? [],
            'old' => $_SESSION['old_communaute'] ?? [], 'moi' => AuthController::isAuthenticated() ? (int) $_SESSION['user_id'] : 0,
            'csrfToken' => $this->csrf()]);
        unset($_SESSION['erreurs_communaute'], $_SESSION['old_communaute']);
    }

    /** Erreurs gardées le temps d'une redirection (motif Post/Redirect/Get). */
    private function retourAvecErreurs(array $erreurs, array $old): void
    {
        $_SESSION['erreurs_communaute'] = $erreurs;
        $_SESSION['old_communaute'] = $old;
        $this->redirect('index.php?page=communaute');
    }

    public function publier(): void
    {
        $this->exigerPostValide('index.php?page=communaute');
        $v = new Validator($_POST);
        $v->obligatoire('contenu', 'Votre message')->longueurMin('contenu', 10, 'Votre message')->longueurMax('contenu', 2000, 'Votre message');
        if ($v->echoue()) {
            $this->retourAvecErreurs($v->erreurs(), ['contenu' => $v->valeur('contenu')]);
        }
        $p = (new Publication())->setContenu($v->valeur('contenu'))->setUtilisateurId((int) $_SESSION['user_id'])->setStatut('publie')->setDatePublication(date('Y-m-d H:i:s'));
        $this->db->prepare('INSERT INTO publication (contenu, utilisateurId, statut, datePublication) VALUES (:c, :u, :s, :d)')
            ->execute(['c' => $p->getContenu(), 'u' => $p->getUtilisateurId(), 's' => $p->getStatut(), 'd' => $p->getDatePublication()]);
        AuthController::flash('success', 'Votre message est publié.');
        $this->redirect('index.php?page=communaute');
    }

    public function commenter(): void
    {
        $this->exigerPostValide('index.php?page=communaute');
        $pubId = (int) ($_POST['publicationId'] ?? 0);
        $champ = 'commentaire_' . $pubId;
        $v = new Validator(['commentaire' => $_POST['commentaire'] ?? '']);
        $v->obligatoire('commentaire', 'Le commentaire')->longueurMin('commentaire', 2, 'Le commentaire')->longueurMax('commentaire', 500, 'Le commentaire');
        $existe = $this->db->prepare("SELECT COUNT(*) FROM publication WHERE id = :id AND statut <> 'masque'");
        $existe->execute(['id' => $pubId]);
        if ((int) $existe->fetchColumn() === 0) {
            AuthController::flash('danger', 'Cette publication n’existe plus.');
            $this->redirect('index.php?page=communaute');
        }
        if ($v->echoue()) {
            $this->retourAvecErreurs([$champ => $v->erreurs()['commentaire']], [$champ => $v->valeur('commentaire')]);
        }
        $c = (new Commentaire())->setContenu($v->valeur('commentaire'))->setPublicationId($pubId)->setUtilisateurId((int) $_SESSION['user_id'])
            ->setStatut('publie')->setDateCommentaire(date('Y-m-d H:i:s'));
        $this->db->prepare('INSERT INTO commentaire (contenu, publicationId, utilisateurId, statut, dateCommentaire) VALUES (:c, :p, :u, :s, :d)')
            ->execute(['c' => $c->getContenu(), 'p' => $c->getPublicationId(), 'u' => $c->getUtilisateurId(), 's' => $c->getStatut(), 'd' => $c->getDateCommentaire()]);
        AuthController::flash('success', 'Commentaire ajouté.');
        $this->redirect('index.php?page=communaute#pub-' . $pubId);
    }

    /** Signaler une publication ou un commentaire d'un autre membre : il passe en « signalé » pour la modération. */
    public function signaler(): void
    {
        $this->exigerPostValide('index.php?page=communaute');
        $table = ($_POST['type'] ?? '') === 'commentaire' ? 'commentaire' : 'publication'; // liste blanche : jamais de nom de table venant du formulaire
        $stmt = $this->db->prepare("UPDATE $table SET statut = 'signale' WHERE id = :id AND statut = 'publie' AND utilisateurId <> :moi");
        $stmt->execute(['id' => (int) ($_POST['id'] ?? 0), 'moi' => (int) $_SESSION['user_id']]);
        AuthController::flash('success', $stmt->rowCount() ? 'Merci, le message a été signalé à l’équipe de modération.' : 'Ce message a déjà été signalé.');
        $this->redirect('index.php?page=communaute');
    }

    /** Supprimer SON message (l'auteur est vérifié dans la requête SQL). */
    public function supprimer(): void
    {
        $this->exigerPostValide('index.php?page=communaute');
        $table = ($_POST['type'] ?? '') === 'commentaire' ? 'commentaire' : 'publication';
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = :id AND utilisateurId = :moi");
        $stmt->execute(['id' => (int) ($_POST['id'] ?? 0), 'moi' => (int) $_SESSION['user_id']]);
        AuthController::flash($stmt->rowCount() ? 'success' : 'danger', $stmt->rowCount() ? 'Message supprimé.' : 'Vous ne pouvez supprimer que vos propres messages.');
        $this->redirect('index.php?page=communaute');
    }
}
