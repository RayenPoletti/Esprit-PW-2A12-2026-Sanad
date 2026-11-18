<?php

/**
 * Entité Seance — Module 3 · Rayen Marzougi
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : seance (voir database/sanad.sql)
 */
class Seance
{
    private ?int $id = null;
    private ?string $titre = null;
    private ?int $ordre = null;
    private ?int $dureeMin = null;
    private ?string $urlVideo = null;
    private ?string $urlAudio = null;
    private ?int $programmeId = null;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;
        return $this;
    }

    public function getDureeMin(): ?int
    {
        return $this->dureeMin;
    }

    public function setDureeMin(?int $dureeMin): self
    {
        $this->dureeMin = $dureeMin;
        return $this;
    }

    public function getUrlVideo(): ?string
    {
        return $this->urlVideo;
    }

    public function setUrlVideo(?string $urlVideo): self
    {
        $this->urlVideo = $urlVideo;
        return $this;
    }

    public function getUrlAudio(): ?string
    {
        return $this->urlAudio;
    }

    public function setUrlAudio(?string $urlAudio): self
    {
        $this->urlAudio = $urlAudio;
        return $this;
    }

    public function getProgrammeId(): ?int
    {
        return $this->programmeId;
    }

    public function setProgrammeId(?int $programmeId): self
    {
        $this->programmeId = $programmeId;
        return $this;
    }

}
