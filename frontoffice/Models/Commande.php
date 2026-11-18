<?php

/**
 * Entité Commande — Module 5 · Karim Bousetta
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : commande (voir database/sanad.sql)
 */
class Commande
{
    public const STATUTS = ['en_attente' => 'En attente', 'preparation' => 'En préparation', 'livree' => 'Livrée', 'annulee' => 'Annulée'];

    private ?int $id = null;
    private ?string $dateCommande = null;
    private ?float $montantTotal = null;
    private ?int $pointsUtilises = null;
    private ?string $statut = null;
    private ?int $utilisateurId = null;

    /** Crée l'objet à partir d'une ligne de la base (tableau associatif). */
    public static function fromArray(array $row): self
    {
        $o = new self();
        foreach ($row as $key => $value) {
            if (property_exists($o, $key) && $value !== null) {
                $o->$key = $value;
            }
        }
        return $o;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDateCommande(): ?string
    {
        return $this->dateCommande;
    }

    public function setDateCommande(?string $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(?float $montantTotal): self
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getPointsUtilises(): ?int
    {
        return $this->pointsUtilises;
    }

    public function setPointsUtilises(?int $pointsUtilises): self
    {
        $this->pointsUtilises = $pointsUtilises;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?int $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;
        return $this;
    }

    public function getLibelleStatut(): string
    {
        return self::STATUTS[$this->statut] ?? (string) $this->statut;
    }

    public function estPayeeEnPoints(): bool
    {
        return (int) $this->pointsUtilises > 0;
    }

}
