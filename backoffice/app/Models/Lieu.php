<?php

namespace App\Models;

/**
 * Entité Lieu — Module 1 · Rayen Poletti
 * Table SQL : lieu (voir database/sanad.sql)
 */
class Lieu extends Model
{
    private ?int $id = null;
    private ?string $nom = null;
    private ?string $adresse = null;
    private ?string $ville = null;
    private ?float $latitude = null;
    private ?float $longitude = null;
    private ?float $scoreAccessibilite = null;

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

    /** Toutes les lignes de la table lieu. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM lieu ORDER BY id DESC')->fetchAll();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getScoreAccessibilite(): ?float
    {
        return $this->scoreAccessibilite;
    }

    public function setScoreAccessibilite(?float $scoreAccessibilite): self
    {
        $this->scoreAccessibilite = $scoreAccessibilite;
        return $this;
    }
}
