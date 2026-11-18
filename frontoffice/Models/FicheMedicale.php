<?php
/**
 * Models/FicheMedicale.php — Fiche médicale du patient (Utilisateur 1 — 0..1 FicheMedicale).
 * Modèle « pur » (workshop sécurité) : attributs privés, getters/setters, aucune requête SQL.
 * Table SQL : ficheMedicale (voir database/sanad.sql)
 */
class FicheMedicale
{
    public const GROUPES_SANGUINS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    private ?int $id = null;
    private ?int $utilisateurId = null;
    private string $groupeSanguin = '';
    private string $allergies = '';
    private string $maladiesChroniques = '';
    private string $traitementsEnCours = '';
    private bool $partageAutorise = false;
    private ?string $dateMiseAJour = null;

    public function getId(): ?int { return $this->id; }
    public function getUtilisateurId(): ?int { return $this->utilisateurId; }
    public function getGroupeSanguin(): string { return $this->groupeSanguin; }
    public function getAllergies(): string { return $this->allergies; }
    public function getMaladiesChroniques(): string { return $this->maladiesChroniques; }
    public function getTraitementsEnCours(): string { return $this->traitementsEnCours; }
    public function isPartageAutorise(): bool { return $this->partageAutorise; }
    public function getDateMiseAJour(): ?string { return $this->dateMiseAJour; }

    public function setUtilisateurId(int $id): self { $this->utilisateurId = $id; return $this; }
    public function setGroupeSanguin(string $g): self { $this->groupeSanguin = $g; return $this; }
    public function setAllergies(string $v): self { $this->allergies = $v; return $this; }
    public function setMaladiesChroniques(string $v): self { $this->maladiesChroniques = $v; return $this; }
    public function setTraitementsEnCours(string $v): self { $this->traitementsEnCours = $v; return $this; }
    public function setPartageAutorise(bool $v): self { $this->partageAutorise = $v; return $this; }

    public static function fromArray(array $row): self
    {
        $f = new self();
        $f->id                 = isset($row['id']) ? (int) $row['id'] : null;
        $f->utilisateurId      = isset($row['utilisateurId']) ? (int) $row['utilisateurId'] : null;
        $f->groupeSanguin      = (string) ($row['groupeSanguin'] ?? '');
        $f->allergies          = (string) ($row['allergies'] ?? '');
        $f->maladiesChroniques = (string) ($row['maladiesChroniques'] ?? '');
        $f->traitementsEnCours = (string) ($row['traitementsEnCours'] ?? '');
        $f->partageAutorise    = !empty($row['partageAutorise']);
        $f->dateMiseAJour      = $row['dateMiseAJour'] ?? null;
        return $f;
    }
}
