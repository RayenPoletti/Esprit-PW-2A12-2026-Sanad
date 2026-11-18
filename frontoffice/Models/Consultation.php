<?php
/**
 * Models/Consultation.php — Antécédent écrit par un professionnel de santé (FicheMedicale 1 — 0..* Consultation).
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Table SQL : consultation (voir database/sanad.sql)
 */
class Consultation
{
    private ?int $id = null;
    private ?int $ficheId = null;
    private ?int $professionnelId = null;
    private string $dateConsultation = '';
    private string $specialite = '';
    private string $motif = '';
    private string $diagnostic = '';
    private string $traitement = '';
    private string $observations = '';
    private ?string $dateCreation = null;
    /** Nom de l'auteur (rempli par la jointure avec utilisateur, pour l'affichage) */
    private string $auteur = '';

    public function getId(): ?int { return $this->id; }
    public function getFicheId(): ?int { return $this->ficheId; }
    public function getProfessionnelId(): ?int { return $this->professionnelId; }
    public function getDateConsultation(): string { return $this->dateConsultation; }
    public function getSpecialite(): string { return $this->specialite; }
    public function getMotif(): string { return $this->motif; }
    public function getDiagnostic(): string { return $this->diagnostic; }
    public function getTraitement(): string { return $this->traitement; }
    public function getObservations(): string { return $this->observations; }
    public function getDateCreation(): ?string { return $this->dateCreation; }
    public function getAuteur(): string { return $this->auteur !== '' ? $this->auteur : 'Professionnel (compte supprimé)'; }

    /** Seul l'auteur d'une consultation peut la corriger. */
    public function estEcritePar(int $utilisateurId): bool { return $this->professionnelId === $utilisateurId; }

    public function setFicheId(int $id): self { $this->ficheId = $id; return $this; }
    public function setProfessionnelId(int $id): self { $this->professionnelId = $id; return $this; }
    public function setDateConsultation(string $d): self { $this->dateConsultation = $d; return $this; }
    public function setSpecialite(string $v): self { $this->specialite = $v; return $this; }
    public function setMotif(string $v): self { $this->motif = $v; return $this; }
    public function setDiagnostic(string $v): self { $this->diagnostic = $v; return $this; }
    public function setTraitement(string $v): self { $this->traitement = $v; return $this; }
    public function setObservations(string $v): self { $this->observations = $v; return $this; }

    public static function fromArray(array $row): self
    {
        $c = new self();
        $c->id               = isset($row['id']) ? (int) $row['id'] : null;
        $c->ficheId          = isset($row['ficheId']) ? (int) $row['ficheId'] : null;
        $c->professionnelId  = isset($row['professionnelId']) ? (int) $row['professionnelId'] : null;
        $c->dateConsultation = (string) ($row['dateConsultation'] ?? '');
        $c->specialite       = (string) ($row['specialite'] ?? '');
        $c->motif            = (string) ($row['motif'] ?? '');
        $c->diagnostic       = (string) ($row['diagnostic'] ?? '');
        $c->traitement       = (string) ($row['traitement'] ?? '');
        $c->observations     = (string) ($row['observations'] ?? '');
        $c->dateCreation     = $row['dateCreation'] ?? null;
        $c->auteur           = trim(($row['auteurPrenom'] ?? '') . ' ' . ($row['auteurNom'] ?? ''));
        return $c;
    }
}
