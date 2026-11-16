<?php

namespace App\Models;

/**
 * Entité Commande — Module 5 · Karim Bousetta
 * Table SQL : commande (voir database/sanad.sql)
 */
class Commande extends Model
{
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
            if (property_exists($o, $key)) {
                $o->$key = $value;
            }
        }
        return $o;
    }

    /** Toutes les lignes de la table commande. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM commande ORDER BY id DESC')->fetchAll();
        return array_map([self::class, 'fromArray'], $rows);
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
}
