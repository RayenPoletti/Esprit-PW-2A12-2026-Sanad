<?php
/**
 * Controllers/AuthController.php — Module commun (toute l'équipe). Même fichier dans frontoffice/ et backoffice/.
 * Authentification, sessions, rôles et jeton CSRF, partagés par le Front Office et le Back Office.
 * Toutes les requêtes SQL sont préparées (protection contre l'injection SQL).
 */
class AuthController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    // ==============================================================
    // ÉTAT DE LA SESSION ET RÔLES
    // ==============================================================
    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    /** Vrai si l'utilisateur connecté a l'un des rôles donnés. */
    public static function hasRole(string ...$roles): bool
    {
        return self::isAuthenticated() && in_array($_SESSION['role'] ?? '', $roles, true);
    }

    /** Arrête la page si personne n'est connecté (redirection vers la connexion). */
    public static function requireAuth(string $urlConnexion): void
    {
        if (!self::isAuthenticated()) {
            // Garde le message « session expirée » s'il existe déjà
            $_SESSION['flash'] = $_SESSION['flash'] ?? ['type' => 'info', 'message' => 'Connectez-vous pour accéder à cette page.'];
            header('Location: ' . $urlConnexion);
            exit;
        }
    }

    /**
     * Arrête la page si l'utilisateur n'a pas l'un des rôles exigés (erreur 403).
     * Ce contrôle est refait sur CHAQUE page sensible, jamais seulement à la connexion.
     */
    public static function requireRole(array $roles, string $urlConnexion, string $vue403): void
    {
        self::requireAuth($urlConnexion);
        if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
            http_response_code(403);
            $rolesExiges = $roles;
            require $vue403;
            exit;
        }
    }

    /** Relit le compte en base : un compte supprimé ou bloqué est déconnecté immédiatement. */
    public function rafraichirSession(): void
    {
        $user = $this->findById((int) $_SESSION['user_id']);
        if (!$user || $user->estBloque()) {
            $this->logout();
            session_start();
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Votre compte n’est plus accessible. Contactez l’administrateur.'];
            return;
        }
        $_SESSION['role']   = $user->getRole();
        $_SESSION['prenom'] = $user->getPrenom();
        $_SESSION['nom']    = $user->getNom();
    }

    // ==============================================================
    // ACCÈS AUX DONNÉES (requêtes préparées)
    // ==============================================================
    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? Utilisateur::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? Utilisateur::fromArray($row) : null;
    }

    /** Vrai si l'e-mail est déjà pris par un autre compte que $saufId. */
    public function emailExiste(string $email, int $saufId = 0): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM utilisateur WHERE email = :email AND id <> :id LIMIT 1');
        $stmt->execute(['email' => mb_strtolower(trim($email)), 'id' => $saufId]);
        return (bool) $stmt->fetch();
    }

    // ==============================================================
    // INSCRIPTION (Front Office)
    // ==============================================================
    /** @return array<string,string> erreurs par champ (tableau vide = succès) */
    public function register(array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }

        $v = new Validator($post);
        $v->obligatoire('prenom', 'Le prénom')->nom('prenom', 'Le prénom')
          ->obligatoire('nom', 'Le nom')->nom('nom', 'Le nom')
          ->obligatoire('email', 'L’e-mail')->email('email')
          ->nom('ville', 'La ville')
          ->obligatoire('motDePasse', 'Le mot de passe')->motDePasse('motDePasse')
          ->identique('confirmation', 'motDePasse', 'Les deux mots de passe ne sont pas identiques.');
        if (!$v->echoue() && $this->emailExiste($v->valeur('email'))) {
            $v->erreur('email', 'Un compte existe déjà avec cet e-mail.');
        }
        if ($v->echoue()) {
            return $v->erreurs();
        }

        // Rôle forcé à « membre » : un visiteur ne peut jamais se créer un compte administrateur
        $stmt = $this->db->prepare(
            'INSERT INTO utilisateur (nom, prenom, email, motDePasse, ville, role, statut, points)
             VALUES (:nom, :prenom, :email, :motDePasse, :ville, :role, :statut, 0)'
        );
        $stmt->execute([
            'nom'        => $v->valeur('nom'),
            'prenom'     => $v->valeur('prenom'),
            'email'      => mb_strtolower($v->valeur('email')),
            'motDePasse' => password_hash($post['motDePasse'], PASSWORD_DEFAULT),
            'ville'      => $v->valeur('ville'),
            'role'       => 'membre',
            'statut'     => 'actif',
        ]);
        return [];
    }

    // ==============================================================
    // CONNEXION / DÉCONNEXION
    // ==============================================================
    /** @return array<string,string> erreurs (tableau vide = succès) */
    public function login(array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }
        $email = mb_strtolower(trim((string) ($post['email'] ?? '')));
        $motDePasse = (string) ($post['motDePasse'] ?? '');
        if ($email === '' || $motDePasse === '') {
            return ['_form' => 'Saisissez votre e-mail et votre mot de passe.'];
        }

        $user = $this->findByEmail($email);
        // Message volontairement général : on ne dit jamais si c'est l'e-mail ou le mot de passe qui est faux
        if (!$user || !password_verify($motDePasse, $user->getMotDePasse())) {
            return ['_form' => 'E-mail ou mot de passe incorrect.'];
        }
        if ($user->estBloque()) {
            return ['_form' => 'Ce compte est bloqué. Contactez l’administrateur.'];
        }

        session_regenerate_id(true); // nouvel identifiant : protège contre la fixation de session
        $_SESSION['user_id']       = $user->getId();
        $_SESSION['prenom']        = $user->getPrenom();
        $_SESSION['nom']           = $user->getNom();
        $_SESSION['role']          = $user->getRole();
        $_SESSION['last_activity'] = time();
        return [];
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    // ==============================================================
    // CSRF
    // ==============================================================
    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function verifyCsrfToken(?string $token): bool
    {
        if (!$token || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // ==============================================================
    // MESSAGES FLASH (affichés une seule fois après une redirection)
    // ==============================================================
    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function prendreFlash(): ?array
    {
        $f = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $f;
    }
}
