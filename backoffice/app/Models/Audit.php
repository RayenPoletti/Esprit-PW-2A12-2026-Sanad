<?php

namespace App\Models;

/**
 * Entité Audit — Module 1 · Rayen Poletti
 * Table SQL : audit (voir database/sanad.sql)
 */
class Audit extends Model
{
    private ?int $id = null;
    private ?string $dateAudit = null;
    private ?bool $rampe = null;
    private ?bool $porteLarge = null;
    private ?bool $ascenseur = null;
    private ?bool $toilettesAdaptees = null;
    private ?bool $parkingPMR = null;
    private ?bool $accueilLSF = null;
    private ?string $commentaire = null;
    private ?int $nbPhotos = null;
    private ?string $statut = null;
    private ?int $lieuId = null;
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

    /** Toutes les lignes de la table audit. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM audit ORDER BY id DESC')->fetchAll();
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

    public function getDateAudit(): ?string
    {
        return $this->dateAudit;
    }

    public function setDateAudit(?string $dateAudit): self
    {
        $this->dateAudit = $dateAudit;
        return $this;
    }

    public function getRampe(): ?bool
    {
        return $this->rampe;
    }

    public function setRampe(?bool $rampe): self
    {
        $this->rampe = $rampe;
        return $this;
    }

    public function getPorteLarge(): ?bool
    {
        return $this->porteLarge;
    }

    public function setPorteLarge(?bool $porteLarge): self
    {
        $this->porteLarge = $porteLarge;
        return $this;
    }

    public function getAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(?bool $ascenseur): self
    {
        $this->ascenseur = $ascenseur;
        return $this;
    }

    public function getToilettesAdaptees(): ?bool
    {
        return $this->toilettesAdaptees;
    }

    public function setToilettesAdaptees(?bool $toilettesAdaptees): self
    {
        $this->toilettesAdaptees = $toilettesAdaptees;
        return $this;
    }

    public function getParkingPMR(): ?bool
    {
        return $this->parkingPMR;
    }

    public function setParkingPMR(?bool $parkingPMR): self
    {
        $this->parkingPMR = $parkingPMR;
        return $this;
    }

    public function getAccueilLSF(): ?bool
    {
        return $this->accueilLSF;
    }

    public function setAccueilLSF(?bool $accueilLSF): self
    {
        $this->accueilLSF = $accueilLSF;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getNbPhotos(): ?int
    {
        return $this->nbPhotos;
    }

    public function setNbPhotos(?int $nbPhotos): self
    {
        $this->nbPhotos = $nbPhotos;
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

    public function getLieuId(): ?int
    {
        return $this->lieuId;
    }

    public function setLieuId(?int $lieuId): self
    {
        $this->lieuId = $lieuId;
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
