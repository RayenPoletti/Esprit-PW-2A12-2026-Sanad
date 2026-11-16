<?php

namespace App\Models;

/**
 * Entité Programme — Module 3 · Rayen Marzougi
 * Table SQL : programme (voir database/sanad.sql)
 */
class Programme extends Model
{
    private ?int $id = null;
    private ?string $titre = null;
    private ?string $description = null;
    private ?string $niveau = null;
    private ?string $formats = null;

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

    /** Toutes les lignes de la table programme. */
    public static function all(): array
    {
        $rows = self::db()->query('SELECT * FROM programme ORDER BY id DESC')->fetchAll();
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;
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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getFormats(): ?string
    {
        return $this->formats;
    }

    public function setFormats(?string $formats): self
    {
        $this->formats = $formats;
        return $this;
    }
}
