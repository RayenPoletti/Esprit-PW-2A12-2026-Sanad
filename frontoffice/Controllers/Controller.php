<?php
/**
 * Contrôleur de base du Front Office : affiche une vue du dossier Views/.
 * Chaque vue inclut elle-même ses blocs communs (includes/head.php, navbar.php, ...).
 */
abstract class Controller
{
    protected function view(string $name, array $data = []): void
    {
        // Message flash disponible dans toutes les vues
        $data['flash'] = $data['flash'] ?? AuthController::prendreFlash();
        extract($data);
        require __DIR__ . '/../Views/' . $name . '.php';
    }

    /** Vérifie qu'une action arrive en POST avec un jeton CSRF valide ; sinon message et retour. */
    protected function exigerPostValide(string $urlRetour): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            AuthController::flash('danger', 'Action refusée : requête invalide. Rechargez la page et réessayez.');
            $this->redirect($urlRetour);
        }
    }

    /** Valeurs saisies (pour réafficher le formulaire après une erreur). */
    protected function saisie(array $champs): array
    {
        $old = [];
        foreach ($champs as $champ) {
            $old[$champ] = is_string($_POST[$champ] ?? null) ? trim($_POST[$champ]) : '';
        }
        return $old;
    }

    protected function csrf(): string
    {
        return (new AuthController())->getCsrfToken();
    }

    /** Redirige vers une autre page puis arrête le script. */
    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
