<?php

namespace App\Models;

/**
 * Entité Passeport — Module commun · toute l’équipe
 * Table SQL : passeport (voir database/sanad.sql)
 */
class Passeport extends Model
{
    private ?int $id = null;
    private ?bool $fauteuilRoulant = null;
    private ?bool $mobiliteReduite = null;
    private ?bool $malvoyance = null;
    private ?bool $surdite = null;
    private ?bool $accompagneAidant = null;
    private ?string $precisions = null;
    private ?bool $partageAutorise = null;
    private ?string $dateMiseAJour = null;
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

    /** Toutes les lignes de la table passeport. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM passeport ORDER BY id DESC')->fetchAll();
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

    public function getFauteuilRoulant(): ?bool
    {
        return $this->fauteuilRoulant;
    }

    public function setFauteuilRoulant(?bool $fauteuilRoulant): self
    {
        $this->fauteuilRoulant = $fauteuilRoulant;
        return $this;
    }

    public function getMobiliteReduite(): ?bool
    {
        return $this->mobiliteReduite;
    }

    public function setMobiliteReduite(?bool $mobiliteReduite): self
    {
        $this->mobiliteReduite = $mobiliteReduite;
        return $this;
    }

    public function getMalvoyance(): ?bool
    {
        return $this->malvoyance;
    }

    public function setMalvoyance(?bool $malvoyance): self
    {
        $this->malvoyance = $malvoyance;
        return $this;
    }

    public function getSurdite(): ?bool
    {
        return $this->surdite;
    }

    public function setSurdite(?bool $surdite): self
    {
        $this->surdite = $surdite;
        return $this;
    }

    public function getAccompagneAidant(): ?bool
    {
        return $this->accompagneAidant;
    }

    public function setAccompagneAidant(?bool $accompagneAidant): self
    {
        $this->accompagneAidant = $accompagneAidant;
        return $this;
    }

    public function getPrecisions(): ?string
    {
        return $this->precisions;
    }

    public function setPrecisions(?string $precisions): self
    {
        $this->precisions = $precisions;
        return $this;
    }

    public function getPartageAutorise(): ?bool
    {
        return $this->partageAutorise;
    }

    public function setPartageAutorise(?bool $partageAutorise): self
    {
        $this->partageAutorise = $partageAutorise;
        return $this;
    }

    public function getDateMiseAJour(): ?string
    {
        return $this->dateMiseAJour;
    }

    public function setDateMiseAJour(?string $dateMiseAJour): self
    {
        $this->dateMiseAJour = $dateMiseAJour;
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
