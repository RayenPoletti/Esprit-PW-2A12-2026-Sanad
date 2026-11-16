<?php

namespace App\Models;

/**
 * Entité RendezVous — Module 2 · Aziz Kouki
 * Table SQL : rendez_vous (voir database/sanad.sql)
 */
class RendezVous extends Model
{
    private ?int $id = null;
    private ?string $dateHeure = null;
    private ?string $motif = null;
    private ?string $statut = null;
    private ?bool $passeportTransmis = null;
    private ?int $professionnelId = null;
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

    /** Toutes les lignes de la table rendez_vous. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM rendez_vous ORDER BY id DESC')->fetchAll();
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

    public function getDateHeure(): ?string
    {
        return $this->dateHeure;
    }

    public function setDateHeure(?string $dateHeure): self
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;
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

    public function getPasseportTransmis(): ?bool
    {
        return $this->passeportTransmis;
    }

    public function setPasseportTransmis(?bool $passeportTransmis): self
    {
        $this->passeportTransmis = $passeportTransmis;
        return $this;
    }

    public function getProfessionnelId(): ?int
    {
        return $this->professionnelId;
    }

    public function setProfessionnelId(?int $professionnelId): self
    {
        $this->professionnelId = $professionnelId;
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
