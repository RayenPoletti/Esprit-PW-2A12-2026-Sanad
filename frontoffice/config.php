<?php
/**
 * config.php · Front Office
 * Configuration de l'application (workshop « Session & Sécurité ») :
 *   1. connexion à la base : classe Database (Singleton PDO)
 *   2. démarrage sécurisé de la session, expiration après 30 min, en-têtes HTTP
 *   3. fonctions utilitaires e() (anti-XSS) et rediriger()
 * Seules les constantes HOST, DBNAME, USER et PASSWORD sont à adapter à votre XAMPP / WAMP.
 */

// =====================================================================
// 1. Connexion à la base de données
// =====================================================================
class Database
{
    // --- À adapter à votre installation locale (XAMPP / WAMP) ---
    private const HOST     = 'localhost';
    private const DBNAME   = 'sanad';
    private const USER     = 'root';
    private const PASSWORD = '';

    private static ?PDO $pdo = null;

    public static function getConnexion(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=utf8mb4',
                    self::USER,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // une erreur SQL lève une exception
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,                  // vraies requêtes préparées (anti injection SQL)
                    ]
                );
            } catch (PDOException $e) {
                // On n'affiche pas le détail technique à l'utilisateur
                error_log($e->getMessage());
                http_response_code(500);
                die('Erreur : connexion à la base de données impossible. Vérifiez config.php et que MySQL est démarré.');
            }
        }
        return self::$pdo;
    }
}

// =====================================================================
// 2. Session sécurisée et en-têtes HTTP
// =====================================================================
/**
 * @param string $csp politique Content-Security-Policy propre à l'espace (le Back Office charge Alpine.js depuis un CDN)
 */
function demarrerApplication(string $csp): void
{
    // 1. Session sécurisée (les ini_set doivent précéder session_start)
    ini_set('session.use_strict_mode', '1');   // refuse un identifiant de session inventé par un attaquant
    ini_set('session.cookie_httponly', '1');   // cookie PHPSESSID invisible pour JavaScript (vol de session via XSS)
    ini_set('session.cookie_samesite', 'Lax'); // cookie non envoyé depuis un site tiers (CSRF)
    // ini_set('session.cookie_secure', '1');  // à activer si le site est servi en HTTPS
    session_start();

    // 2. Expiration par inactivité
    $dureeSession = 1800; // 30 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $dureeSession)) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['flash'] = ['type' => 'info', 'message' => 'Votre session a expiré. Reconnectez-vous.'];
    }
    $_SESSION['last_activity'] = time();

    // 3. En-têtes HTTP de sécurité
    header('X-Frame-Options: DENY');                               // anti clickjacking
    header('X-Content-Type-Options: nosniff');                     // pas de devinette du type MIME
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: ' . $csp);                    // seconde barrière contre le XSS

    // 4. Le rôle et le statut sont relus en base à chaque requête :
    //    un compte bloqué ou supprimé perd immédiatement l'accès.
    if (AuthController::isAuthenticated()) {
        (new AuthController())->rafraichirSession();
    }
}

/** Échappe une valeur avant affichage (protection XSS). */
if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

/** Redirige puis arrête le script. */
function rediriger(string $url): void
{
    header('Location: ' . $url);
    exit;
}
