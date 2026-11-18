<?php

/**
 * Entité Audit — Module 1 · Rayen Poletti
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Les requêtes préparées sont écrites dans les contrôleurs. Même fichier dans frontoffice/ et backoffice/.
 * Table SQL : audit (voir database/sanad.sql)
 */
class Audit
{
    public const STATUTS = ['en_attente' => 'En attente', 'valide' => 'Validé', 'refuse' => 'Refusé'];

    /** Les 6 critères vérifiés sur place (colonne => libellé) */
    public const CRITERES = [
        'rampe'             => 'Plain-pied ou rampe',
        'porteLarge'        => 'Porte de 90 cm ou plus',
        'ascenseur'         => 'Ascenseur (ou cabinet au rez-de-chaussée)',
        'toilettesAdaptees' => 'Toilettes adaptées',
        'parkingPMR'        => 'Parking PMR',
        'accueilLSF'        => 'Accueil en langue des signes',
    ];

    /** Points solidaires gagnés par l'ambassadeur quand son audit est validé */
    public const POINTS_AUDIT = 50;

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

    public function getDateAudit(): ?string
    {
        return $this->dateAudit;
    }

    public function setDateAudit(?string $dateAudit): self
    {
        $this->dateAudit = $dateAudit;
        return $this;
    }

    public function isRampe(): ?bool
    {
        return $this->rampe;
    }

    public function setRampe(?bool $rampe): self
    {
        $this->rampe = $rampe;
        return $this;
    }

    public function isPorteLarge(): ?bool
    {
        return $this->porteLarge;
    }

    public function setPorteLarge(?bool $porteLarge): self
    {
        $this->porteLarge = $porteLarge;
        return $this;
    }

    public function isAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(?bool $ascenseur): self
    {
        $this->ascenseur = $ascenseur;
        return $this;
    }

    public function isToilettesAdaptees(): ?bool
    {
        return $this->toilettesAdaptees;
    }

    public function setToilettesAdaptees(?bool $toilettesAdaptees): self
    {
        $this->toilettesAdaptees = $toilettesAdaptees;
        return $this;
    }

    public function isParkingPMR(): ?bool
    {
        return $this->parkingPMR;
    }

    public function setParkingPMR(?bool $parkingPMR): self
    {
        $this->parkingPMR = $parkingPMR;
        return $this;
    }

    public function isAccueilLSF(): ?bool
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

    public function getCritere(string $critere): bool
    {
        return array_key_exists($critere, self::CRITERES) && (bool) $this->$critere;
    }

    public function setCritere(string $critere, bool $ok): self
    {
        if (array_key_exists($critere, self::CRITERES)) {
            $this->$critere = $ok;
        }
        return $this;
    }

    /** Nombre de critères respectés (sur 6) */
    public function nbCriteresOK(): int
    {
        return count(array_filter(array_keys(self::CRITERES), fn($c) => $this->getCritere($c)));
    }

    /** Score de cet audit sur 5 */
    public function score(): float
    {
        return round($this->nbCriteresOK() * 5 / count(self::CRITERES), 1);
    }

    public function getLibelleStatut(): string
    {
        return self::STATUTS[$this->statut] ?? (string) $this->statut;
    }

}
