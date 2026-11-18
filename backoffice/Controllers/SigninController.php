<?php

/**
 * Connexion et déconnexion du Back Office (réservé au rôle « admin »).
 */
class SigninController extends Controller
{
    public function index(): void
    {
        $this->page  = 'signin';
        $this->title = 'Connexion | Sanad Back Office';
        $auth = new AuthController();

        if (AuthController::hasRole('admin')) {
            $this->redirect('index.php?page=dashboard');
        }

        $erreurs = [];
        $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $erreurs = $auth->login($_POST);
            if (empty($erreurs)) {
                if (AuthController::hasRole('admin')) {
                    $this->redirect('index.php?page=dashboard');
                }
                // Compte valide mais sans droits d'administration : on le renvoie vers le Front Office
                AuthController::flash('info', 'Vous êtes connecté(e). Le Back Office est réservé aux administrateurs.');
                $this->redirect('../frontoffice/index.php?page=accueil');
            }
        }

        $this->renderStandalone('signin', [
            'erreurs'   => $erreurs,
            'email'     => $email,
            'csrfToken' => $auth->getCsrfToken(),
            'flash'     => AuthController::prendreFlash(),
        ]);
    }

    public function logout(): void
    {
        (new AuthController())->logout();
        session_start();
        AuthController::flash('success', 'Vous êtes déconnecté(e).');
        $this->redirect('index.php?page=signin');
    }
}
