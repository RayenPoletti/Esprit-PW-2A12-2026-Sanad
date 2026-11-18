<?php

/**
 * Module commun (toute l'équipe) · gestion complète des utilisateurs dans le Back Office :
 * liste avec recherche, filtres et pagination, ajout, modification, suppression, blocage.
 * Toutes les requêtes sont préparées ; chaque formulaire est protégé par un jeton CSRF.
 */
class UtilisateurController extends Controller
{
    private const PAR_PAGE = 10;

    private PDO $db;
    private AuthController $auth;

    public function __construct()
    {
        $this->db   = Database::getConnexion();
        $this->auth = new AuthController();
    }

    // ==============================================================
    // LISTE (Read)
    // ==============================================================
    public function index(): void
    {
        $this->page  = 'utilisateurs';
        $this->title = 'Utilisateurs | Sanad Back Office';

        $filtres = [
            'q'      => trim((string) ($_GET['q'] ?? '')),
            'role'   => (string) ($_GET['role'] ?? ''),
            'statut' => (string) ($_GET['statut'] ?? ''),
        ];
        if (!in_array($filtres['role'], Utilisateur::ROLES, true)) {
            $filtres['role'] = '';
        }
        if (!in_array($filtres['statut'], Utilisateur::STATUTS, true)) {
            $filtres['statut'] = '';
        }
        [$where, $params] = $this->construireFiltres($filtres);

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateur $where");
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        $nbPages = max(1, (int) ceil($total / self::PAR_PAGE));
        $pageNum = min(max(1, (int) ($_GET['p'] ?? 1)), $nbPages);

        $stmt = $this->db->prepare("SELECT * FROM utilisateur $where ORDER BY id DESC LIMIT :limite OFFSET :decalage");
        foreach ($params as $cle => $valeur) {
            $stmt->bindValue(':' . $cle, $valeur);
        }
        $stmt->bindValue(':limite', self::PAR_PAGE, PDO::PARAM_INT);
        $stmt->bindValue(':decalage', ($pageNum - 1) * self::PAR_PAGE, PDO::PARAM_INT);
        $stmt->execute();
        $utilisateurs = array_map([Utilisateur::class, 'fromArray'], $stmt->fetchAll());

        $parRole = array_fill_keys(Utilisateur::ROLES, 0);
        foreach ($this->db->query('SELECT role, COUNT(*) AS n FROM utilisateur GROUP BY role')->fetchAll() as $r) {
            $parRole[$r['role']] = (int) $r['n'];
        }

        $this->render('utilisateurs', [
            'utilisateurs' => $utilisateurs,
            'filtres'      => $filtres,
            'total'        => $total,
            'pageNum'      => $pageNum,
            'nbPages'      => $nbPages,
            'parRole'      => $parRole,
            'moi'          => (int) $_SESSION['user_id'],
            'csrfToken'    => $this->auth->getCsrfToken(),
            'flash'        => AuthController::prendreFlash(),
        ]);
    }

    /** Construit la clause WHERE avec des paramètres nommés distincts (requêtes préparées natives). */
    private function construireFiltres(array $f): array
    {
        $conditions = [];
        $params = [];
        if ($f['q'] !== '') {
            $conditions[] = '(nom LIKE :q1 OR prenom LIKE :q2 OR email LIKE :q3 OR ville LIKE :q4)';
            $like = '%' . $f['q'] . '%';
            $params += ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        if ($f['role'] !== '') {
            $conditions[] = 'role = :role';
            $params['role'] = $f['role'];
        }
        if ($f['statut'] !== '') {
            $conditions[] = 'statut = :statut';
            $params['statut'] = $f['statut'];
        }
        return [$conditions ? 'WHERE ' . implode(' AND ', $conditions) : '', $params];
    }

    // ==============================================================
    // AJOUT (Create) et MODIFICATION (Update)
    // ==============================================================
    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $utilisateur = $this->auth->findById((int) ($_GET['id'] ?? 0));
        if (!$utilisateur) {
            AuthController::flash('danger', 'Utilisateur introuvable.');
            $this->redirect('index.php?page=utilisateurs');
        }
        $this->formulaire($utilisateur);
    }

    private function formulaire(?Utilisateur $u): void
    {
        $estModification = $u !== null;
        $this->page  = $estModification ? 'utilisateurs' : 'utilisateur-form';
        $this->title = ($estModification ? 'Modifier un utilisateur' : 'Ajouter un utilisateur') . ' | Sanad Back Office';

        $erreurs = [];
        $old = $estModification ? [
            'prenom' => $u->getPrenom(), 'nom' => $u->getNom(), 'email' => $u->getEmail(), 'telephone' => $u->getTelephone(),
            'ville' => $u->getVille(), 'role' => $u->getRole(), 'statut' => $u->getStatut(), 'points' => (string) $u->getPoints(),
        ] : ['role' => 'membre', 'statut' => 'actif', 'points' => '0'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach (['prenom', 'nom', 'email', 'telephone', 'ville', 'role', 'statut', 'points'] as $champ) {
                $old[$champ] = trim((string) ($_POST[$champ] ?? ''));
            }
            $erreurs = $this->valider($_POST, $u);
            if (empty($erreurs)) {
                $this->enregistrer($_POST, $u);
                AuthController::flash('success', $estModification ? 'Utilisateur modifié.' : 'Utilisateur ajouté.');
                $this->redirect('index.php?page=utilisateurs');
            }
        }

        $this->render('utilisateur_form', [
            'utilisateur'     => $u,
            'estModification' => $estModification,
            'old'             => $old,
            'erreurs'         => $erreurs,
            'csrfToken'       => $this->auth->getCsrfToken(),
        ]);
    }

    /** @return array<string,string> */
    private function valider(array $post, ?Utilisateur $u): array
    {
        if (!$this->auth->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }
        $v = new Validator($post);
        $v->obligatoire('prenom', 'Le prénom')->nom('prenom', 'Le prénom')
          ->obligatoire('nom', 'Le nom')->nom('nom', 'Le nom')
          ->obligatoire('email', 'L’e-mail')->email('email')
          ->telephone('telephone')
          ->nom('ville', 'La ville')
          ->dansListe('role', Utilisateur::ROLES, 'Le rôle')
          ->dansListe('statut', Utilisateur::STATUTS, 'Le statut')
          ->obligatoire('points', 'Le nombre de points')->entier('points', 0, 100000, 'Le nombre de points');

        // Mot de passe : obligatoire à la création, facultatif à la modification
        if ($u === null || ($post['motDePasse'] ?? '') !== '') {
            $v->obligatoire('motDePasse', 'Le mot de passe')->motDePasse('motDePasse')
              ->identique('confirmation', 'motDePasse', 'Les deux mots de passe ne sont pas identiques.');
        }
        if (!isset($v->erreurs()['email']) && $this->auth->emailExiste($v->valeur('email'), $u ? $u->getId() : 0)) {
            $v->erreur('email', 'Un autre compte utilise déjà cet e-mail.');
        }
        // Un administrateur ne peut pas se retirer ses propres droits ni se bloquer
        if ($u !== null && $u->getId() === (int) $_SESSION['user_id']) {
            if ($v->valeur('role') !== 'admin') {
                $v->erreur('role', 'Vous ne pouvez pas retirer votre propre rôle d’administrateur.');
            }
            if ($v->valeur('statut') !== 'actif') {
                $v->erreur('statut', 'Vous ne pouvez pas bloquer votre propre compte.');
            }
        }
        return $v->erreurs();
    }

    private function enregistrer(array $post, ?Utilisateur $u): void
    {
        $v = new Validator($post);
        $donnees = [
            'nom'       => $v->valeur('nom'),
            'prenom'    => $v->valeur('prenom'),
            'email'     => mb_strtolower($v->valeur('email')),
            'telephone' => $v->valeur('telephone'),
            'ville'     => $v->valeur('ville'),
            'role'      => $v->valeur('role'),
            'statut'    => $v->valeur('statut'),
            'points'    => (int) $v->valeur('points'),
        ];
        if ($u === null) {
            $donnees['motDePasse'] = password_hash((string) $post['motDePasse'], PASSWORD_DEFAULT);
            $stmt = $this->db->prepare(
                'INSERT INTO utilisateur (nom, prenom, email, telephone, ville, role, statut, points, motDePasse)
                 VALUES (:nom, :prenom, :email, :telephone, :ville, :role, :statut, :points, :motDePasse)'
            );
            $stmt->execute($donnees);
            return;
        }
        $sql = 'UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone,
                ville = :ville, role = :role, statut = :statut, points = :points';
        if (($post['motDePasse'] ?? '') !== '') {
            $sql .= ', motDePasse = :motDePasse';
            $donnees['motDePasse'] = password_hash((string) $post['motDePasse'], PASSWORD_DEFAULT);
        }
        $donnees['id'] = $u->getId();
        $this->db->prepare($sql . ' WHERE id = :id')->execute($donnees);
    }

    // ==============================================================
    // SUPPRESSION (Delete) et BLOCAGE
    // ==============================================================
    public function delete(): void
    {
        $cible = $this->cibleAction();
        try {
            // Le passeport est supprimé avec le compte (ON DELETE CASCADE)
            $this->db->prepare('DELETE FROM utilisateur WHERE id = :id')->execute(['id' => $cible->getId()]);
            AuthController::flash('success', 'Le compte de ' . $cible->getNomComplet() . ' a été supprimé.');
        } catch (PDOException $ex) {
            // Clé étrangère RESTRICT : le compte a des audits, rendez-vous, publications ou commandes
            AuthController::flash('danger', 'Impossible de supprimer ' . $cible->getNomComplet()
                . ' : ce compte a des données liées (audits, rendez-vous, publications…). Bloquez-le plutôt.');
        }
        $this->redirect('index.php?page=utilisateurs');
    }

    public function toggle(): void
    {
        $cible = $this->cibleAction();
        $nouveau = $cible->estBloque() ? 'actif' : 'bloque';
        $this->db->prepare('UPDATE utilisateur SET statut = :statut WHERE id = :id')->execute(['statut' => $nouveau, 'id' => $cible->getId()]);
        AuthController::flash('success', $cible->getNomComplet() . ($nouveau === 'bloque' ? ' est bloqué(e) : il ou elle ne peut plus se connecter.' : ' est débloqué(e).'));
        $this->redirect('index.php?page=utilisateurs');
    }

    /** Vérifie la méthode POST, le jeton CSRF et la cible d'une action sensible. */
    private function cibleAction(): Utilisateur
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            AuthController::flash('danger', 'Action refusée : requête invalide.');
            $this->redirect('index.php?page=utilisateurs');
        }
        $cible = $this->auth->findById((int) ($_POST['id'] ?? 0));
        if (!$cible) {
            AuthController::flash('danger', 'Utilisateur introuvable.');
            $this->redirect('index.php?page=utilisateurs');
        }
        if ($cible->getId() === (int) $_SESSION['user_id']) {
            AuthController::flash('danger', 'Vous ne pouvez pas faire cette action sur votre propre compte.');
            $this->redirect('index.php?page=utilisateurs');
        }
        return $cible;
    }
}
