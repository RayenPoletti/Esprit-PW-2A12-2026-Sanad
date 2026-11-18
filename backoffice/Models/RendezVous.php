<?php

/**
 * Entité RendezVous — Module 2 · Aziz Kouki
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : rendez_vous (voir database/sanad.sql)
 */
class RendezVous
{
    public const STATUTS = ['en_attente' => 'En attente', 'confirme' => 'Confirmé', 'annule' => 'Annulé'];

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

    public function isPasseportTransmis(): ?bool
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

    public function getLibelleStatut(): string
    {
        return self::STATUTS[$this->statut] ?? (string) $this->statut;
    }

    public function estPasse(): bool
    {
        return strtotime((string) $this->dateHeure) < time();
    }

}
