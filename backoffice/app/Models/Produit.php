<?php

namespace App\Models;

/**
 * Entité Produit — Module 5 · Karim Bousetta
 * Table SQL : produit (voir database/sanad.sql)
 */
class Produit extends Model
{
    private ?int $id = null;
    private ?string $nom = null;
    private ?string $description = null;
    private ?string $etat = null;
    private ?float $prix = null;
    private ?int $stock = null;
    private ?float $poidsKg = null;

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

    /** Toutes les lignes de la table produit. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM produit ORDER BY id DESC')->fetchAll();
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getPoidsKg(): ?float
    {
        return $this->poidsKg;
    }

    public function setPoidsKg(?float $poidsKg): self
    {
        $this->poidsKg = $poidsKg;
        return $this;
    }
}
