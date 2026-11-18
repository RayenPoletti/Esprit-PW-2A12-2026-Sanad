<?php

/**
 * Entité Produit — Module 5 · Karim Bousetta
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans le contrôleur.
 * Table SQL : produit (voir database/sanad.sql)
 */
class Produit
{
    /** États possibles d’un produit de la boutique solidaire */
    public const ETATS = ['Reconditionné', 'Don'];

    /** Taux de conversion : 10 points solidaires = 1 DT */
    public const POINTS_PAR_DINAR = 10;

    private ?int $id = null;
    private ?string $nom = null;
    private ?string $description = null;
    private ?string $etat = null;
    private ?float $prix = null;
    private ?int $stock = null;
    private ?float $poidsKg = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    /** Un don est gratuit. */
    public function estDon(): bool
    {
        return $this->etat === 'Don';
    }

    /** Prix équivalent en points solidaires. */
    public function getPrixPoints(): int
    {
        return (int) round(($this->prix ?? 0) * self::POINTS_PAR_DINAR);
    }

    public function getPoidsKg(): ?float
    {
        return $this->poidsKg;
    }

    public function setPoidsKg(?float $poidsKg): self
    {
        $this->poidsKg = $poidsKg;
        return $this;
    }
}
