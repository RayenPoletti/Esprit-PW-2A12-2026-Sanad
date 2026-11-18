<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module 1 · Rayen Poletti · Lieu ↔ Audit — espace ambassadeur du Front Office.
 * L'ambassadeur envoie un audit (statut « en attente ») et suit ses audits. L'administrateur les valide dans le Back Office :
 * chaque audit validé rapporte des points solidaires et met à jour le score du lieu.
 */
class AuditController extends Controller
{
    public function index(): void
    {
        $db = Database::getConnexion();
        $moi = (int) $_SESSION['user_id'];

        $lieux = [];
        foreach ($db->query('SELECT id, nom, ville FROM lieu ORDER BY ville, nom')->fetchAll() as $r) {
            $lieux[(string) $r['id']] = $r['nom'] . ' · ' . $r['ville'];
        }
        $old = ['dateAudit' => date('Y-m-d'), 'lieuId' => (string) ($_GET['lieu'] ?? '')];
        $coches = [];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisie(['lieuId', 'dateAudit', 'commentaire']);
            $coches = array_values(array_intersect((array) ($_POST['criteres'] ?? []), array_keys(Audit::CRITERES)));
            if (!(new AuthController())->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->obligatoire('lieuId', 'Le lieu audité')->dansListe('lieuId', array_map('strval', array_keys($lieux)), 'Le lieu audité')
                  ->obligatoire('dateAudit', 'La date de la visite')->datePassee('dateAudit', 'La date de la visite')
                  ->obligatoire('commentaire', 'Le commentaire')->longueurMin('commentaire', 10, 'Le commentaire')->longueurMax('commentaire', 1000, 'Le commentaire');
                $erreurs = $v->erreurs();
                if (empty($erreurs)) {
                    // Objet Audit rempli avec les setters ; l'auteur vient de la session, jamais du formulaire
                    $a = (new Audit())->setLieuId((int) $v->valeur('lieuId'))->setUtilisateurId($moi)
                        ->setDateAudit($v->valeur('dateAudit'))->setCommentaire($v->valeur('commentaire'));
                    $donnees = ['lieuId' => $a->getLieuId(), 'utilisateurId' => $a->getUtilisateurId(),
                        'dateAudit' => $a->getDateAudit(), 'commentaire' => $a->getCommentaire()];
                    foreach (array_keys(Audit::CRITERES) as $c) {
                        $a->setCritere($c, in_array($c, $coches, true));
                        $donnees[$c] = (int) $a->getCritere($c);
                    }
                    $db->prepare('INSERT INTO audit (lieuId, utilisateurId, dateAudit, commentaire, rampe, porteLarge, ascenseur, toilettesAdaptees, parkingPMR, accueilLSF)
                        VALUES (:lieuId, :utilisateurId, :dateAudit, :commentaire, :rampe, :porteLarge, :ascenseur, :toilettesAdaptees, :parkingPMR, :accueilLSF)')
                        ->execute($donnees);
                    AuthController::flash('success', 'Merci ! Votre audit est envoyé. Il sera vérifié par l’équipe Sanad (+' . Audit::POINTS_AUDIT . ' points s’il est validé).');
                    $this->redirect('index.php?page=audits');
                }
            }
        }

        // Mes audits (jointure avec le lieu)
        $stmt = $db->prepare('SELECT a.*, l.nom AS lieuNom FROM audit a INNER JOIN lieu l ON l.id = a.lieuId WHERE a.utilisateurId = :moi ORDER BY a.dateAudit DESC, a.id DESC');
        $stmt->execute(['moi' => $moi]);
        $mesAudits = array_map(fn($r) => ['audit' => Audit::fromArray($r), 'lieu' => $r['lieuNom']], $stmt->fetchAll());

        $stmt = $db->prepare('SELECT points FROM utilisateur WHERE id = :id');
        $stmt->execute(['id' => $moi]);

        $this->view('audits', ['lieux' => $lieux, 'old' => $old, 'coches' => $coches, 'erreurs' => $erreurs,
            'mesAudits' => $mesAudits, 'points' => (int) $stmt->fetchColumn(), 'csrfToken' => $this->csrf()]);
    }
}
