<?php

/**
 * Entité Lieu — Module 1 · Rayen Poletti
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : lieu (voir database/sanad.sql)
 */
class Lieu
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

    /** Un lieu est « vérifié » dès qu'au moins un audit a été validé (le score est alors calculé). */
    public function estVerifie(): bool
    {
        return $this->scoreAccessibilite !== null;
    }

}
