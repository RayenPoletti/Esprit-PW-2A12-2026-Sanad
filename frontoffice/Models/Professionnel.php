<?php

/**
 * Entité Professionnel — Module 2 · Aziz Kouki
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : professionnel (voir database/sanad.sql)
 */
class Professionnel
{
    public const SPECIALITES = ['Cardiologie', 'Dermatologie', 'Médecine générale', 'Ophtalmologie', 'Kinésithérapie', 'Psychologie', 'Pédiatrie', 'Dentisterie'];

    private ?int $id = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?string $specialite = null;
    private ?string $telephone = null;
    private ?bool $valide = null;
    private ?int $lieuId = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): self
    {
        $this->valide = $valide;
        return $this;
    }

    public function getLieuId(): ?int
    {
        return $this->lieuId;
    }

    public function setLieuId(?int $lieuId): self
    {
        $this->lieuId = $lieuId;
        return $this;
    }

    public function getNomComplet(): string
    {
        return 'Dr ' . trim($this->prenom . ' ' . $this->nom);
    }

}
