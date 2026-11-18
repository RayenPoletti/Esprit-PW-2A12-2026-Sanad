<?php
require_once __DIR__ . '/Controller.php';

/**
 * Module 5 · Karim Bousetta · Commande 0..* — 1..* Produit, classe d'association LigneCommande.
 *  - ?page=boutique : produits lus dans la base ;
 *  - panier gardé en session (produitId => quantité), membre connecté ;
 *  - ?page=panier : valider la commande en dinars ou en points solidaires (10 points = 1 DT),
 *    dans une transaction qui vérifie le stock ; suivi et annulation de ses commandes.
 */
class BoutiqueController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $rows = $this->db->query('SELECT * FROM produit ORDER BY (stock > 0) DESC, id DESC')->fetchAll();
        $this->view('boutique', ['produits' => array_map([Produit::class, 'fromArray'], $rows), 'csrfToken' => $this->csrf(),
            'nbPanier' => array_sum($_SESSION['panier'] ?? [])]);
    }

    private function produit(int $id): ?Produit
    {
        $stmt = $this->db->prepare('SELECT * FROM produit WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? Produit::fromArray($row) : null;
    }

    /** Ajouter un produit au panier (quantité limitée au stock ; un don = 1 exemplaire). */
    public function ajouter(): void
    {
        $this->exigerPostValide('index.php?page=boutique');
        $p = $this->produit((int) ($_POST['id'] ?? 0));
        if (!$p || (int) $p->getStock() < 1) {
            AuthController::flash('danger', 'Ce produit n’est plus disponible.');
            $this->redirect('index.php?page=boutique');
        }
        $panier = $_SESSION['panier'] ?? [];
        $max = $p->estDon() ? 1 : (int) $p->getStock();
        $panier[$p->getId()] = min(($panier[$p->getId()] ?? 0) + 1, $max);
        $_SESSION['panier'] = $panier;
        AuthController::flash('success', '« ' . $p->getNom() . ' » est dans votre panier.');
        $this->redirect('index.php?page=boutique');
    }

    /** Lignes du panier relues dans la base (le prix et le stock viennent toujours de la base, jamais du formulaire). */
    private function lignesPanier(): array
    {
        $lignes = [];
        foreach ($_SESSION['panier'] ?? [] as $id => $qte) {
            $p = $this->produit((int) $id);
            if ($p) {
                $lignes[] = ['produit' => $p, 'quantite' => (int) $qte, 'sousTotal' => (float) $p->getPrix() * (int) $qte];
            }
        }
        return $lignes;
    }

    private function pointsDuMembre(): int
    {
        $stmt = $this->db->prepare('SELECT points FROM utilisateur WHERE id = :id');
        $stmt->execute(['id' => (int) $_SESSION['user_id']]);
        return (int) $stmt->fetchColumn();
    }

    public function panier(): void
    {
        $lignes = $this->lignesPanier();
        $total = array_sum(array_column($lignes, 'sousTotal'));

        $stmt = $this->db->prepare('SELECT * FROM commande WHERE utilisateurId = :moi ORDER BY dateCommande DESC');
        $stmt->execute(['moi' => (int) $_SESSION['user_id']]);
        $commandes = [];
        foreach ($stmt->fetchAll() as $r) {
            // Jointure LigneCommande → Produit pour le détail de chaque commande
            $l = $this->db->prepare('SELECT lc.quantite, lc.prixUnitaire, p.nom FROM ligneCommande lc INNER JOIN produit p ON p.id = lc.produitId WHERE lc.commandeId = :id');
            $l->execute(['id' => $r['id']]);
            $commandes[] = ['commande' => Commande::fromArray($r), 'lignes' => $l->fetchAll()];
        }
        $this->view('panier', ['lignes' => $lignes, 'total' => $total, 'totalPoints' => (int) round($total * Produit::POINTS_PAR_DINAR),
            'points' => $this->pointsDuMembre(), 'commandes' => $commandes, 'csrfToken' => $this->csrf()]);
    }

    /** Changer la quantité d'une ligne (0 = retirer). */
    public function modifierPanier(): void
    {
        $this->exigerPostValide('index.php?page=panier');
        $id = (int) ($_POST['id'] ?? 0);
        $p = $this->produit($id);
        $v = new Validator($_POST);
        $v->obligatoire('quantite', 'La quantité')->entier('quantite', 0, 100, 'La quantité');
        if ($v->echoue() || !$p) {
            AuthController::flash('danger', $v->erreurs()['quantite'] ?? 'Produit introuvable.');
            $this->redirect('index.php?page=panier');
        }
        $qte = min((int) $v->valeur('quantite'), $p->estDon() ? 1 : (int) $p->getStock());
        if ($qte <= 0) {
            unset($_SESSION['panier'][$id]);
        } else {
            $_SESSION['panier'][$id] = $qte;
        }
        $this->redirect('index.php?page=panier');
    }

    public function commander(): void
    {
        $this->exigerPostValide('index.php?page=panier');
        $lignes = $this->lignesPanier();
        if (!$lignes) {
            AuthController::flash('danger', 'Votre panier est vide.');
            $this->redirect('index.php?page=boutique');
        }
        $v = new Validator($_POST);
        $v->obligatoire('paiement', 'Le mode de paiement')->dansListe('paiement', ['dinars', 'points'], 'Le mode de paiement');
        if ($v->echoue()) {
            AuthController::flash('danger', 'Choisissez un mode de paiement.');
            $this->redirect('index.php?page=panier');
        }
        $total = array_sum(array_column($lignes, 'sousTotal'));
        $points = $v->valeur('paiement') === 'points' ? (int) round($total * Produit::POINTS_PAR_DINAR) : 0;
        $moi = (int) $_SESSION['user_id'];

        $this->db->beginTransaction();
        try {
            if ($points > 0) {
                // Débit des points seulement si le solde suffit (vérifié dans la requête)
                $d = $this->db->prepare('UPDATE utilisateur SET points = points - :p1 WHERE id = :id AND points >= :p2');
                $d->execute(['p1' => $points, 'p2' => $points, 'id' => $moi]);
                if ($d->rowCount() === 0) {
                    throw new RuntimeException('Vous n’avez pas assez de points solidaires pour cette commande.');
                }
            }
            $c = (new Commande())->setUtilisateurId($moi)->setMontantTotal($total)->setPointsUtilises($points)->setStatut('en_attente')->setDateCommande(date('Y-m-d H:i:s'));
            $this->db->prepare('INSERT INTO commande (utilisateurId, montantTotal, pointsUtilises, statut, dateCommande) VALUES (:u, :m, :p, :s, :d)')
                ->execute(['u' => $c->getUtilisateurId(), 'm' => $c->getMontantTotal(), 'p' => $c->getPointsUtilises(), 's' => $c->getStatut(), 'd' => $c->getDateCommande()]);
            $commandeId = (int) $this->db->lastInsertId();
            foreach ($lignes as $l) {
                $p = $l['produit'];
                // Le stock est décrémenté seulement s'il suffit encore (évite la vente de deux fois le même article)
                $s = $this->db->prepare('UPDATE produit SET stock = stock - :q1 WHERE id = :id AND stock >= :q2');
                $s->execute(['q1' => $l['quantite'], 'q2' => $l['quantite'], 'id' => $p->getId()]);
                if ($s->rowCount() === 0) {
                    throw new RuntimeException('Stock insuffisant pour « ' . $p->getNom() . ' ». Modifiez votre panier.');
                }
                $this->db->prepare('INSERT INTO ligneCommande (commandeId, produitId, quantite, prixUnitaire) VALUES (:c, :p, :q, :pu)')
                    ->execute(['c' => $commandeId, 'p' => $p->getId(), 'q' => $l['quantite'], 'pu' => $p->getPrix()]);
            }
            $this->db->commit();
        } catch (RuntimeException $ex) {
            $this->db->rollBack();
            AuthController::flash('danger', $ex->getMessage());
            $this->redirect('index.php?page=panier');
        }
        $_SESSION['panier'] = [];
        AuthController::flash('success', 'Commande n° ' . $commandeId . ' enregistrée' . ($points ? ' (' . $points . ' points utilisés).' : '.') . ' Nous vous contactons pour le retrait.');
        $this->redirect('index.php?page=panier');
    }

    /** Annuler SA commande tant qu'elle est « en attente » : le stock et les points sont rendus. */
    public function annuler(): void
    {
        $this->exigerPostValide('index.php?page=panier');
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM commande WHERE id = :id AND utilisateurId = :moi AND statut = 'en_attente'");
        $stmt->execute(['id' => $id, 'moi' => (int) $_SESSION['user_id']]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Cette commande ne peut plus être annulée.');
            $this->redirect('index.php?page=panier');
        }
        $c = Commande::fromArray($row);
        $this->db->beginTransaction();
        $this->db->prepare("UPDATE commande SET statut = 'annulee' WHERE id = :id")->execute(['id' => $id]);
        $this->db->prepare('UPDATE produit SET stock = stock + (SELECT lc.quantite FROM ligneCommande lc WHERE lc.commandeId = :c1 AND lc.produitId = produit.id)
                             WHERE id IN (SELECT produitId FROM ligneCommande WHERE commandeId = :c2)')->execute(['c1' => $id, 'c2' => $id]);
        if ($c->estPayeeEnPoints()) {
            $this->db->prepare('UPDATE utilisateur SET points = points + :p WHERE id = :id')->execute(['p' => $c->getPointsUtilises(), 'id' => $c->getUtilisateurId()]);
        }
        $this->db->commit();
        AuthController::flash('success', 'Commande annulée.' . ($c->estPayeeEnPoints() ? ' Vos points vous sont rendus.' : ''));
        $this->redirect('index.php?page=panier');
    }
}
