<?php
/**
 * Models/Utilisateur.php — Module commun (toute l'équipe). Même fichier dans frontoffice/ et backoffice/.
 * Le modèle ne contient QUE des attributs privés, des getters/setters et fromArray() :
 * aucune requête SQL (elles sont dans les contrôleurs, comme dans le workshop).
 */
class Utilisateur
{
    public const ROLES = ['membre', 'aidant', 'ambassadeur', 'professionnel', 'admin'];
    public const LIBELLES_ROLES = [
        'membre' => 'Membre', 'aidant' => 'Aidant', 'ambassadeur' => 'Ambassadeur',
        'professionnel' => 'Professionnel', 'admin' => 'Administrateur',
    ];
    public const STATUTS = ['actif', 'bloque'];

    private int $id = 0;
    private string $nom = '';
    private string $prenom = '';
    private string $email = '';
    private string $motDePasse = '';
    private string $telephone = '';
    private string $ville = '';
    private string $role = 'membre';
    private string $statut = 'actif';
    private int $points = 0;
    private string $dateInscription = '';

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getNomComplet(): string { return trim($this->prenom . ' ' . $this->nom); }
    public function getEmail(): string { return $this->email; }
    public function getMotDePasse(): string { return $this->motDePasse; }
    public function getTelephone(): string { return $this->telephone; }
    public function getVille(): string { return $this->ville; }
    public function getRole(): string { return $this->role; }
    public function getLibelleRole(): string { return self::LIBELLES_ROLES[$this->role] ?? $this->role; }
    public function getStatut(): string { return $this->statut; }
    public function estBloque(): bool { return $this->statut === 'bloque'; }
    public function getPoints(): int { return $this->points; }
    public function getDateInscription(): string { return $this->dateInscription; }

    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setMotDePasse(string $motDePasse): void { $this->motDePasse = $motDePasse; }
    public function setTelephone(string $telephone): void { $this->telephone = $telephone; }
    public function setVille(string $ville): void { $this->ville = $ville; }
    public function setRole(string $role): void { $this->role = $role; }
    public function setStatut(string $statut): void { $this->statut = $statut; }
    public function setPoints(int $points): void { $this->points = $points; }
    public function setDateInscription(string $date): void { $this->dateInscription = $date; }

    /** Transforme une ligne SQL en objet. */
    public static function fromArray(array $row): self
    {
        $u = new self();
        $u->setId((int) ($row['id'] ?? 0));
        $u->setNom((string) ($row['nom'] ?? ''));
        $u->setPrenom((string) ($row['prenom'] ?? ''));
        $u->setEmail((string) ($row['email'] ?? ''));
        $u->setMotDePasse((string) ($row['motDePasse'] ?? ''));
        $u->setTelephone((string) ($row['telephone'] ?? ''));
        $u->setVille((string) ($row['ville'] ?? ''));
        $u->setRole((string) ($row['role'] ?? 'membre'));
        $u->setStatut((string) ($row['statut'] ?? 'actif'));
        $u->setPoints((int) ($row['points'] ?? 0));
        $u->setDateInscription((string) ($row['dateInscription'] ?? ''));
        return $u;
    }
}
