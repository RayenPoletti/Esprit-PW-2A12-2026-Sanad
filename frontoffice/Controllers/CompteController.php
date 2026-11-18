<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module commun (toute l'équipe) · comptes des membres dans le Front Office :
 * inscription, connexion, déconnexion, modification du profil, changement de mot de passe.
 */
class CompteController extends Controller
{
    private AuthController $auth;
    private PDO $db;

    public function __construct()
    {
        $this->auth = new AuthController();
        $this->db   = Database::getConnexion();
    }

    // ---------------------------------------------------------------- Inscription
    public function inscription(): void
    {
        if (AuthController::isAuthenticated()) {
            $this->redirect('index.php?page=compte');
        }
        $erreurs = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = ['prenom' => $_POST['prenom'] ?? '', 'nom' => $_POST['nom'] ?? '', 'email' => $_POST['email'] ?? '', 'ville' => $_POST['ville'] ?? ''];
            $erreurs = $this->auth->register($_POST); // rôle forcé à « membre » dans register()
            if (empty($erreurs)) {
                AuthController::flash('success', 'Votre compte est créé. Vous pouvez vous connecter.');
                $this->redirect('index.php?page=connexion');
            }
        }
        $this->view('inscription', ['erreurs' => $erreurs, 'old' => $old, 'csrfToken' => $this->auth->getCsrfToken()]);
    }

    // ---------------------------------------------------------------- Connexion
    public function connexion(): void
    {
        if (AuthController::isAuthenticated()) {
            $this->redirect('index.php?page=compte');
        }
        $erreurs = [];
        $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = (string) ($_POST['email'] ?? '');
            $erreurs = $this->auth->login($_POST);
            if (empty($erreurs)) {
                // Redirection selon le rôle : l'administrateur va au Back Office
                if (AuthController::hasRole('admin')) {
                    $this->redirect('../backoffice/index.php?page=dashboard');
                }
                AuthController::flash('success', 'Bienvenue ' . $_SESSION['prenom'] . ' !');
                $this->redirect('index.php?page=compte');
            }
        }
        $this->view('connexion', ['erreurs' => $erreurs, 'email' => $email, 'csrfToken' => $this->auth->getCsrfToken()]);
    }

    // ---------------------------------------------------------------- Déconnexion
    public function deconnexion(): void
    {
        $this->auth->logout();
        session_start();
        AuthController::flash('success', 'Vous êtes déconnecté(e). À bientôt !');
        $this->redirect('index.php?page=accueil');
    }

    // ---------------------------------------------------------------- Mon compte (profil)
    public function profil(): void
    {
        $moi = $this->auth->findById((int) $_SESSION['user_id']);
        $erreurs = [];
        $old = ['prenom' => $moi->getPrenom(), 'nom' => $moi->getNom(), 'email' => $moi->getEmail(), 'telephone' => $moi->getTelephone(), 'ville' => $moi->getVille()];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach (array_keys($old) as $champ) {
                $old[$champ] = trim((string) ($_POST[$champ] ?? ''));
            }
            if (!$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('prenom', 'Le prénom')->nom('prenom', 'Le prénom')
                  ->obligatoire('nom', 'Le nom')->nom('nom', 'Le nom')
                  ->obligatoire('email', 'L’e-mail')->email('email')
                  ->telephone('telephone')
                  ->nom('ville', 'La ville');
                if (!isset($v->erreurs()['email']) && $this->auth->emailExiste($v->valeur('email'), $moi->getId())) {
                    $v->erreur('email', 'Un autre compte utilise déjà cet e-mail.');
                }
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    // Le membre ne peut modifier QUE ses informations : ni son rôle, ni son statut, ni ses points
                    $this->db->prepare(
                        'UPDATE utilisateur SET prenom = :prenom, nom = :nom, email = :email, telephone = :telephone, ville = :ville WHERE id = :id'
                    )->execute([
                        'prenom' => $v->valeur('prenom'), 'nom' => $v->valeur('nom'), 'email' => mb_strtolower($v->valeur('email')),
                        'telephone' => $v->valeur('telephone'), 'ville' => $v->valeur('ville'), 'id' => $moi->getId(),
                    ]);
                    $_SESSION['prenom'] = $v->valeur('prenom');
                    $_SESSION['nom'] = $v->valeur('nom');
                    AuthController::flash('success', 'Votre profil est mis à jour.');
                    $this->redirect('index.php?page=compte');
                }
            }
        }

        $this->view('compte', [
            'moi' => $moi, 'old' => $old, 'erreurs' => $erreurs, 'erreursMdp' => [],
            'csrfToken' => $this->auth->getCsrfToken(),
        ]);
    }

    // ---------------------------------------------------------------- Changer le mot de passe
    public function motDePasse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?page=compte');
        }
        $moi = $this->auth->findById((int) $_SESSION['user_id']);
        $erreursMdp = [];
        if (!$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            $erreursMdp['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
        } else {
            $v = new Validator($_POST);
            $v->obligatoire('actuel', 'Le mot de passe actuel')
              ->obligatoire('motDePasse', 'Le nouveau mot de passe')->motDePasse('motDePasse')
              ->identique('confirmation', 'motDePasse', 'Les deux mots de passe ne sont pas identiques.');
            if (!isset($v->erreurs()['actuel']) && !password_verify((string) $_POST['actuel'], $moi->getMotDePasse())) {
                $v->erreur('actuel', 'Le mot de passe actuel est incorrect.');
            }
            $erreursMdp = $v->erreurs();
        }
        if (empty($erreursMdp)) {
            $this->db->prepare('UPDATE utilisateur SET motDePasse = :mdp WHERE id = :id')
                ->execute(['mdp' => password_hash((string) $_POST['motDePasse'], PASSWORD_DEFAULT), 'id' => $moi->getId()]);
            session_regenerate_id(true);
            AuthController::flash('success', 'Votre mot de passe est modifié.');
            $this->redirect('index.php?page=compte');
        }
        $this->view('compte', [
            'moi' => $moi, 'erreurs' => [], 'erreursMdp' => $erreursMdp, 'csrfToken' => $this->auth->getCsrfToken(),
            'old' => ['prenom' => $moi->getPrenom(), 'nom' => $moi->getNom(), 'email' => $moi->getEmail(), 'telephone' => $moi->getTelephone(), 'ville' => $moi->getVille()],
        ]);
    }
}
