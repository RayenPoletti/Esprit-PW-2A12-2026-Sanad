<?php

/**
 * Module commun · liste des passeports d'accessibilité.
 * Jointure Utilisateur 1 — 0..1 Passeport (INNER JOIN sur utilisateurId).
 */
class PasseportController extends Controller
{
    public function index(): void
    {
        $this->page  = 'passeports';
        $this->title = 'Passeports d’accessibilité | Sanad Back Office';

        $rows = Database::getConnexion()->query(
            'SELECT p.*, u.prenom, u.nom, u.email
               FROM passeport p
               INNER JOIN utilisateur u ON u.id = p.utilisateurId
              ORDER BY p.dateMiseAJour DESC'
        )->fetchAll();

        $passeports = array_map(fn(array $r) => [
            'passeport' => Passeport::fromArray($r),
            'membre'    => trim($r['prenom'] . ' ' . $r['nom']),
            'email'     => $r['email'],
        ], $rows);

        $this->render('passeports', ['passeports' => $passeports, 'flash' => AuthController::prendreFlash()]);
    }
}
