<?php

/**
 * Module 5 · responsable : Karim Bousetta · jointure Produit ↔ Commande.
 * CRUD des produits de la boutique solidaire : liste (avec recherche), ajout, modification, suppression.
 * Requêtes préparées PDO, jeton CSRF sur chaque formulaire, validation PHP avec Validator.
 */
class ProduitController extends Controller
{
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
        $this->page  = 'produits';
        $this->title = 'Produits | Sanad Back Office';

        $q = trim((string) ($_GET['q'] ?? ''));
        if ($q !== '') {
            $stmt = $this->db->prepare('SELECT * FROM produit WHERE nom LIKE :q1 OR description LIKE :q2 ORDER BY id DESC');
            $stmt->execute(['q1' => '%' . $q . '%', 'q2' => '%' . $q . '%']);
        } else {
            $stmt = $this->db->query('SELECT * FROM produit ORDER BY id DESC');
        }
        $produits = array_map([Produit::class, 'fromArray'], $stmt->fetchAll());

        $this->render('produits', [
            'produits'  => $produits,
            'q'         => $q,
            'csrfToken' => $this->auth->getCsrfToken(),
            'flash'     => AuthController::prendreFlash(),
        ]);
    }

    // ==============================================================
    // AJOUT (Create) et MODIFICATION (Update) : même formulaire
    // ==============================================================
    public function create(): void
    {
        $this->formulaire(null);
    }

    public function edit(): void
    {
        $stmt = $this->db->prepare('SELECT * FROM produit WHERE id = :id');
        $stmt->execute(['id' => (int) ($_GET['id'] ?? 0)]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Produit introuvable.');
            $this->redirect('index.php?page=produits');
        }
        $this->formulaire(Produit::fromArray($row));
    }

    private function formulaire(?Produit $produit): void
    {
        $estModification = $produit !== null;
        $this->page  = $estModification ? 'produits' : 'produit-form';
        $this->title = ($estModification ? 'Modifier un produit' : 'Ajouter un produit') . ' | Sanad Back Office';

        $champs = ['nom', 'etat', 'prix', 'stock', 'poidsKg', 'description'];
        $old = $estModification ? [
            'nom' => $produit->getNom(), 'etat' => $produit->getEtat(), 'prix' => (string) $produit->getPrix(),
            'stock' => (string) $produit->getStock(), 'poidsKg' => (string) ($produit->getPoidsKg() ?? ''),
            'description' => $produit->getDescription(),
        ] : ['stock' => '1'];
        $erreurs = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($champs as $champ) {
                $old[$champ] = trim((string) ($_POST[$champ] ?? ''));
            }
            $erreurs = $this->valider($_POST);
            if (empty($erreurs)) {
                $this->enregistrer($_POST, $produit);
                AuthController::flash('success', $estModification ? 'Produit modifié.' : 'Produit ajouté à la boutique.');
                $this->redirect('index.php?page=produits');
            }
        }

        $this->render('produit_form', [
            'produit'         => $produit,
            'estModification' => $estModification,
            'old'             => $old,
            'erreurs'         => $erreurs,
            'csrfToken'       => $this->auth->getCsrfToken(),
        ]);
    }

    /** @return array<string,string> erreurs par champ */
    private function valider(array $post): array
    {
        if (!$this->auth->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ['_form' => 'Jeton de sécurité invalide. Rechargez la page et réessayez.'];
        }
        $v = new Validator($post);
        $v->obligatoire('nom', 'Le nom du produit')->longueurMin('nom', 2, 'Le nom du produit')->longueurMax('nom', 100, 'Le nom du produit')
          ->obligatoire('etat', 'L’état')->dansListe('etat', Produit::ETATS, 'L’état')
          ->obligatoire('prix', 'Le prix')->decimal('prix', 0, 10000, 'Le prix')
          ->obligatoire('stock', 'Le stock')->entier('stock', 0, 10000, 'Le stock')
          ->decimal('poidsKg', 0, 500, 'Le poids')
          ->obligatoire('description', 'La description')->longueurMin('description', 10, 'La description')->longueurMax('description', 1000, 'La description');

        // Règle métier : un don est toujours gratuit
        if (!isset($v->erreurs()['prix']) && $v->valeur('etat') === 'Don' && $v->nombre('prix') > 0) {
            $v->erreur('prix', 'Un don est gratuit : mettez 0 comme prix.');
        }
        return $v->erreurs();
    }

    private function enregistrer(array $post, ?Produit $produit): void
    {
        $v = new Validator($post);
        // Objet Produit rempli avec les setters, puis enregistré
        $p = $produit ?? new Produit();
        $p->setNom($v->valeur('nom'))
          ->setEtat($v->valeur('etat'))
          ->setPrix($v->nombre('prix'))
          ->setStock((int) $v->valeur('stock'))
          ->setPoidsKg($v->nombre('poidsKg'))
          ->setDescription($v->valeur('description'));

        $donnees = [
            'nom' => $p->getNom(), 'description' => $p->getDescription(), 'etat' => $p->getEtat(),
            'prix' => $p->getPrix(), 'stock' => $p->getStock(), 'poidsKg' => $p->getPoidsKg(),
        ];
        if ($produit === null) {
            $this->db->prepare(
                'INSERT INTO produit (nom, description, etat, prix, stock, poidsKg)
                 VALUES (:nom, :description, :etat, :prix, :stock, :poidsKg)'
            )->execute($donnees);
            return;
        }
        $donnees['id'] = $p->getId();
        $this->db->prepare(
            'UPDATE produit SET nom = :nom, description = :description, etat = :etat,
                    prix = :prix, stock = :stock, poidsKg = :poidsKg WHERE id = :id'
        )->execute($donnees);
    }

    // ==============================================================
    // SUPPRESSION (Delete)
    // ==============================================================
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->auth->verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            AuthController::flash('danger', 'Action refusée : requête invalide.');
            $this->redirect('index.php?page=produits');
        }
        try {
            $stmt = $this->db->prepare('DELETE FROM produit WHERE id = :id');
            $stmt->execute(['id' => (int) ($_POST['id'] ?? 0)]);
            AuthController::flash($stmt->rowCount() ? 'success' : 'danger', $stmt->rowCount() ? 'Produit supprimé.' : 'Produit introuvable.');
        } catch (PDOException $ex) {
            // Clé étrangère RESTRICT : le produit figure dans une commande (LigneCommande)
            AuthController::flash('danger', 'Impossible de supprimer ce produit : il fait partie d’une commande. Mettez son stock à 0.');
        }
        $this->redirect('index.php?page=produits');
    }
}
