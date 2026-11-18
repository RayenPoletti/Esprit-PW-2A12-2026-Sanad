<?php

/**
 * Entité Programme — Module 3 · Rayen Marzougi
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : programme (voir database/sanad.sql)
 */
class Programme
{
    public const NIVEAUX = ['Débutant', 'Intermédiaire', 'Tous niveaux'];
    public const FORMATS = ['video' => 'Vidéo sous-titrée', 'audio' => 'Audio', 'facile' => 'Facile à lire'];

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

    /** @return string[] libellés des formats proposés */
    public function getListeFormats(): array
    {
        $cles = array_filter(explode(',', (string) $this->formats));
        return array_values(array_intersect_key(self::FORMATS, array_flip($cles)));
    }

    public function aFormat(string $format): bool
    {
        return in_array($format, explode(',', (string) $this->formats), true);
    }

}
