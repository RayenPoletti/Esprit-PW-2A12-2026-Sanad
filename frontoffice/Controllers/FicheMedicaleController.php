<?php
require_once __DIR__ . '/Controller.php';

/**
 * Fiche médicale et antécédents (extension du module 2 · Professionnel ↔ RendezVous).
 *
 *  Côté patient (tout compte connecté) : ?page=fiche
 *    - remplit sa fiche (groupe sanguin, allergies, maladies chroniques, traitements) ;
 *    - choisit s'il autorise les professionnels à consulter ses antécédents ;
 *    - lit ses consultations (il ne peut pas les modifier : elles sont écrites par les médecins).
 *
 *  Côté professionnel (rôle « professionnel ») : ?page=patients, ?page=dossier, ?page=consultation-modifier
 *    - retrouve un patient par son e-mail ;
 *    - ouvre sa fiche UNIQUEMENT si le patient a autorisé le partage (vérifié à chaque requête) ;
 *    - ajoute une consultation ; corrige seulement les siennes. Aucune consultation ne peut être supprimée,
 *      pour que l'historique reste complet pour le médecin suivant.
 *
 *  L'administrateur n'a pas de page pour lire ces données : elles relèvent du secret médical.
 */
class FicheMedicaleController extends Controller
{
    private PDO $db;
    private AuthController $auth;

    public function __construct()
    {
        $this->db   = Database::getConnexion();
        $this->auth = new AuthController();
    }

    // =================================================================
    // Lectures communes
    // =================================================================
    private function ficheDeUtilisateur(int $utilisateurId): ?FicheMedicale
    {
        $stmt = $this->db->prepare('SELECT * FROM ficheMedicale WHERE utilisateurId = :id');
        $stmt->execute(['id' => $utilisateurId]);
        $row = $stmt->fetch();
        return $row ? FicheMedicale::fromArray($row) : null;
    }

    /** @return Consultation[] du plus récent au plus ancien, avec le nom du professionnel (jointure) */
    private function consultations(int $ficheId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, u.prenom AS auteurPrenom, u.nom AS auteurNom
               FROM consultation c
               LEFT JOIN utilisateur u ON u.id = c.professionnelId
              WHERE c.ficheId = :id
              ORDER BY c.dateConsultation DESC, c.id DESC'
        );
        $stmt->execute(['id' => $ficheId]);
        return array_map([Consultation::class, 'fromArray'], $stmt->fetchAll());
    }

    // =================================================================
    // PATIENT : ma fiche médicale
    // =================================================================
    public function maFiche(): void
    {
        $moi = (int) $_SESSION['user_id'];
        $fiche = $this->ficheDeUtilisateur($moi);
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
                $erreurs['_form'] = 'Jeton de sécurité invalide. Rechargez la page et réessayez.';
            } else {
                $v = new Validator($_POST);
                $v->dansListe('groupeSanguin', array_merge([''], FicheMedicale::GROUPES_SANGUINS), 'Le groupe sanguin')
                  ->longueurMax('allergies', 1000, 'Le champ « Allergies »')
                  ->longueurMax('maladiesChroniques', 1000, 'Le champ « Maladies chroniques »')
                  ->longueurMax('traitementsEnCours', 1000, 'Le champ « Traitements en cours »');
                $erreurs = $v->erreurs();

                $f = $fiche ?? (new FicheMedicale())->setUtilisateurId($moi);
                $f->setGroupeSanguin($v->valeur('groupeSanguin'))
                  ->setAllergies($v->valeur('allergies'))
                  ->setMaladiesChroniques($v->valeur('maladiesChroniques'))
                  ->setTraitementsEnCours($v->valeur('traitementsEnCours'))
                  ->setPartageAutorise(isset($_POST['partageAutorise']));

                if (empty($erreurs)) {
                    $donnees = [
                        'groupeSanguin' => $f->getGroupeSanguin(), 'allergies' => $f->getAllergies(),
                        'maladiesChroniques' => $f->getMaladiesChroniques(), 'traitementsEnCours' => $f->getTraitementsEnCours(),
                        'partageAutorise' => (int) $f->isPartageAutorise(), 'utilisateurId' => $moi,
                    ];
                    if ($fiche === null) {
                        $sql = 'INSERT INTO ficheMedicale (groupeSanguin, allergies, maladiesChroniques, traitementsEnCours, partageAutorise, utilisateurId)
                                VALUES (:groupeSanguin, :allergies, :maladiesChroniques, :traitementsEnCours, :partageAutorise, :utilisateurId)';
                    } else {
                        $sql = 'UPDATE ficheMedicale SET groupeSanguin = :groupeSanguin, allergies = :allergies,
                                maladiesChroniques = :maladiesChroniques, traitementsEnCours = :traitementsEnCours,
                                partageAutorise = :partageAutorise, dateMiseAJour = CURRENT_TIMESTAMP
                                WHERE utilisateurId = :utilisateurId';
                    }
                    $this->db->prepare($sql)->execute($donnees);
                    AuthController::flash('success', $fiche ? 'Votre fiche médicale est mise à jour.' : 'Votre fiche médicale est créée.');
                    $this->redirect('index.php?page=fiche');
                }
                $fiche = $f; // réaffiche la saisie avec les erreurs
            }
        }

        $this->view('fiche', [
            'fiche'         => $fiche,
            'consultations' => ($fiche && $fiche->getId()) ? $this->consultations($fiche->getId()) : [],
            'erreurs'       => $erreurs,
            'csrfToken'     => $this->auth->getCsrfToken(),
        ]);
    }

    // =================================================================
    // PROFESSIONNEL : rechercher un patient
    // =================================================================
    public function patients(): void
    {
        $email = trim((string) ($_GET['email'] ?? ''));
        $resultat = null;
        $message = '';

        if ($email !== '') {
            $stmt = $this->db->prepare(
                'SELECT f.id AS ficheId, u.prenom, u.nom, u.email
                   FROM ficheMedicale f INNER JOIN utilisateur u ON u.id = f.utilisateurId
                  WHERE u.email = :email AND f.partageAutorise = 1'
            );
            $stmt->execute(['email' => mb_strtolower($email)]);
            $resultat = $stmt->fetch() ?: null;
            // Même message si le patient n'existe pas ou n'a pas donné son accord (on ne révèle rien)
            if (!$resultat) {
                $message = 'Aucune fiche partagée pour cet e-mail. Le patient doit créer sa fiche et cocher « J’autorise les professionnels… ».';
            }
        }

        // Patients déjà suivis par ce professionnel, et qui partagent toujours leur fiche
        $stmt = $this->db->prepare(
            'SELECT f.id AS ficheId, u.prenom, u.nom, u.email, MAX(c.dateConsultation) AS derniere
               FROM consultation c
               INNER JOIN ficheMedicale f ON f.id = c.ficheId
               INNER JOIN utilisateur u ON u.id = f.utilisateurId
              WHERE c.professionnelId = :moi AND f.partageAutorise = 1
              GROUP BY f.id, u.prenom, u.nom, u.email
              ORDER BY derniere DESC'
        );
        $stmt->execute(['moi' => (int) $_SESSION['user_id']]);

        $this->view('patients', [
            'email' => $email, 'resultat' => $resultat, 'message' => $message, 'suivis' => $stmt->fetchAll(),
        ]);
    }

    // =================================================================
    // PROFESSIONNEL : dossier d'un patient + ajout d'une consultation
    // =================================================================
    /** Charge le dossier si le patient a autorisé le partage, sinon renvoie vers la recherche. */
    private function dossierAutorise(int $ficheId): array
    {
        $stmt = $this->db->prepare(
            'SELECT f.*, u.prenom, u.nom, u.email
               FROM ficheMedicale f INNER JOIN utilisateur u ON u.id = f.utilisateurId
              WHERE f.id = :id AND f.partageAutorise = 1'
        );
        $stmt->execute(['id' => $ficheId]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Cette fiche n’existe pas ou le patient ne la partage plus.');
            $this->redirect('index.php?page=patients');
        }
        return [FicheMedicale::fromArray($row), trim($row['prenom'] . ' ' . $row['nom']), $row['email']];
    }

    public function dossier(): void
    {
        [$fiche, $patient, $email] = $this->dossierAutorise((int) ($_GET['id'] ?? 0));
        $erreurs = [];
        $old = ['dateConsultation' => date('Y-m-d')];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisieConsultation($_POST);
            $erreurs = $this->validerConsultation($_POST);
            if (empty($erreurs)) {
                $c = (new Consultation())->setFicheId($fiche->getId())->setProfessionnelId((int) $_SESSION['user_id']);
                $this->remplir($c, $_POST);
                $this->db->prepare(
                    'INSERT INTO consultation (ficheId, professionnelId, dateConsultation, specialite, motif, diagnostic, traitement, observations)
                     VALUES (:ficheId, :professionnelId, :dateConsultation, :specialite, :motif, :diagnostic, :traitement, :observations)'
                )->execute($this->donnees($c) + ['ficheId' => $c->getFicheId(), 'professionnelId' => $c->getProfessionnelId()]);
                AuthController::flash('success', 'Consultation ajoutée au dossier de ' . $patient . '.');
                $this->redirect('index.php?page=dossier&id=' . $fiche->getId());
            }
        }

        $this->view('dossier', [
            'fiche' => $fiche, 'patient' => $patient, 'email' => $email,
            'consultations' => $this->consultations($fiche->getId()),
            'old' => $old, 'erreurs' => $erreurs, 'moi' => (int) $_SESSION['user_id'],
            'csrfToken' => $this->auth->getCsrfToken(),
        ]);
    }

    public function modifierConsultation(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM consultation WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        $moi = (int) $_SESSION['user_id'];
        if (!$row || !Consultation::fromArray($row)->estEcritePar($moi)) {
            AuthController::flash('danger', 'Vous ne pouvez corriger que vos propres consultations.');
            $this->redirect('index.php?page=patients');
        }
        $c = Consultation::fromArray($row);
        [$fiche, $patient] = $this->dossierAutorise($c->getFicheId());

        $erreurs = [];
        $old = [
            'dateConsultation' => $c->getDateConsultation(), 'specialite' => $c->getSpecialite(), 'motif' => $c->getMotif(),
            'diagnostic' => $c->getDiagnostic(), 'traitement' => $c->getTraitement(), 'observations' => $c->getObservations(),
        ];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = $this->saisieConsultation($_POST);
            $erreurs = $this->validerConsultation($_POST);
            if (empty($erreurs)) {
                $this->remplir($c, $_POST);
                $this->db->prepare(
                    'UPDATE consultation SET dateConsultation = :dateConsultation, specialite = :specialite, motif = :motif,
                            diagnostic = :diagnostic, traitement = :traitement, observations = :observations
                      WHERE id = :id AND professionnelId = :moi'
                )->execute($this->donnees($c) + ['id' => $c->getId(), 'moi' => $moi]);
                AuthController::flash('success', 'Consultation corrigée.');
                $this->redirect('index.php?page=dossier&id=' . $fiche->getId());
            }
        }

        $this->view('consultation_form', [
            'consultation' => $c, 'patient' => $patient, 'ficheId' => $fiche->getId(),
            'old' => $old, 'erreurs' => $erreurs, 'csrfToken' => $this->auth->getCsrfToken(),
        ]);
    }

    // ---- aides pour le formulaire de consultation ----
    private function saisieConsultation(array $post): array
    {
        $old = [];
        foreach (['dateConsultation', 'specialite', 'motif', 'diagnostic', 'traitement', 'observations'] as $champ) {
            $old[$champ] = trim((string) ($post[$champ] ?? ''));
        }
        return $old;
    }

    private function validerConsultation(array $post): array
    {
        if (!$this->auth->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }
        $v = new Validator($post);
        $v->obligatoire('dateConsultation', 'La date')->datePassee('dateConsultation', 'La date')
          ->obligatoire('specialite', 'La spécialité')->longueurMin('specialite', 2, 'La spécialité')->longueurMax('specialite', 60, 'La spécialité')
          ->obligatoire('motif', 'Le motif')->longueurMin('motif', 3, 'Le motif')->longueurMax('motif', 150, 'Le motif')
          ->obligatoire('diagnostic', 'Le diagnostic')->longueurMin('diagnostic', 3, 'Le diagnostic')->longueurMax('diagnostic', 2000, 'Le diagnostic')
          ->longueurMax('traitement', 2000, 'Le traitement')
          ->longueurMax('observations', 2000, 'Le champ « Observations »');
        return $v->erreurs();
    }

    private function remplir(Consultation $c, array $post): void
    {
        $v = new Validator($post);
        $c->setDateConsultation($v->valeur('dateConsultation'))->setSpecialite($v->valeur('specialite'))
          ->setMotif($v->valeur('motif'))->setDiagnostic($v->valeur('diagnostic'))
          ->setTraitement($v->valeur('traitement'))->setObservations($v->valeur('observations'));
    }

    private function donnees(Consultation $c): array
    {
        return [
            'dateConsultation' => $c->getDateConsultation(), 'specialite' => $c->getSpecialite(), 'motif' => $c->getMotif(),
            'diagnostic' => $c->getDiagnostic(), 'traitement' => $c->getTraitement(), 'observations' => $c->getObservations(),
        ];
    }
}
