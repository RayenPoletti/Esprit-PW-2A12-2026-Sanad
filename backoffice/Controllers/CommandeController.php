<?php

/**
 * Module 5 · responsable : Karim Bousetta · Commande 0..* — 1..* Produit, classe d'association LigneCommande.
 * Suivi des commandes : liste avec le détail des lignes (jointure), changement de statut, suppression.
 * Annuler une commande rend le stock des produits et les points solidaires utilisés.
 */
class CommandeController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function index(): void
    {
        $this->page  = 'commandes';
        $this->title = 'Commandes | Sanad Back Office';
        $statut = (string) ($_GET['statut'] ?? '');
        if (!array_key_exists($statut, Commande::STATUTS)) {
            $statut = '';
        }
        $sql = 'SELECT c.*, u.prenom, u.nom, u.email FROM commande c INNER JOIN utilisateur u ON u.id = c.utilisateurId';
        $params = [];
        if ($statut !== '') {
            $sql .= ' WHERE c.statut = :statut';
            $params['statut'] = $statut;
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY c.dateCommande DESC');
        $stmt->execute($params);
        $lignes = [];
        $detail = $this->db->prepare('SELECT lc.quantite, lc.prixUnitaire, p.nom FROM ligneCommande lc INNER JOIN produit p ON p.id = lc.produitId WHERE lc.commandeId = :id');
        foreach ($stmt->fetchAll() as $r) {
            $detail->execute(['id' => $r['id']]);
            $lignes[] = ['commande' => Commande::fromArray($r), 'client' => trim($r['prenom'] . ' ' . $r['nom']), 'email' => $r['email'], 'articles' => $detail->fetchAll()];
        }
        $this->render('commandes', ['lignes' => $lignes, 'statut' => $statut, 'csrfToken' => $this->csrf(), 'flash' => AuthController::prendreFlash()]);
    }

    private function trouver(int $id): Commande
    {
        $stmt = $this->db->prepare('SELECT * FROM commande WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) {
            AuthController::flash('danger', 'Commande introuvable.');
            $this->redirect('index.php?page=commandes');
        }
        return Commande::fromArray($row);
    }

    public function statut(): void
    {
        $this->exigerPostValide('index.php?page=commandes');
        $nouveau = (string) ($_POST['statut'] ?? '');
        $c = $this->trouver((int) ($_POST['id'] ?? 0));
        if (!array_key_exists($nouveau, Commande::STATUTS) || $c->getStatut() === 'annulee') {
            AuthController::flash('danger', $c->getStatut() === 'annulee' ? 'Une commande annulée ne peut plus changer de statut.' : 'Statut inconnu.');
            $this->redirect('index.php?page=commandes');
        }
        $this->db->beginTransaction();
        $this->db->prepare('UPDATE commande SET statut = :s WHERE id = :id')->execute(['s' => $nouveau, 'id' => $c->getId()]);
        if ($nouveau === 'annulee') {
            $this->rendreStockEtPoints($c);
        }
        $this->db->commit();
        AuthController::flash('success', 'Commande n° ' . $c->getId() . ' : ' . mb_strtolower(Commande::STATUTS[$nouveau])
            . ($nouveau === 'annulee' ? ' (stock et points rendus).' : '.'));
        $this->redirect('index.php?page=commandes');
    }

    private function rendreStockEtPoints(Commande $c): void
    {
        $this->db->prepare('UPDATE produit SET stock = stock + (SELECT lc.quantite FROM ligneCommande lc WHERE lc.commandeId = :c1 AND lc.produitId = produit.id)
                             WHERE id IN (SELECT produitId FROM ligneCommande WHERE commandeId = :c2)')->execute(['c1' => $c->getId(), 'c2' => $c->getId()]);
        if ($c->estPayeeEnPoints()) {
            $this->db->prepare('UPDATE utilisateur SET points = points + :p WHERE id = :id')->execute(['p' => $c->getPointsUtilises(), 'id' => $c->getUtilisateurId()]);
        }
    }

    /** Suppression réservée aux commandes terminées (livrées ou annulées) ; les lignes partent avec (CASCADE). */
    public function delete(): void
    {
        $this->exigerPostValide('index.php?page=commandes');
        $c = $this->trouver((int) ($_POST['id'] ?? 0));
        if (!in_array($c->getStatut(), ['livree', 'annulee'], true)) {
            AuthController::flash('danger', 'Annulez ou livrez la commande avant de la supprimer.');
            $this->redirect('index.php?page=commandes');
        }
        $this->db->prepare('DELETE FROM commande WHERE id = :id')->execute(['id' => $c->getId()]);
        AuthController::flash('success', 'Commande supprimée.');
        $this->redirect('index.php?page=commandes');
    }
}
