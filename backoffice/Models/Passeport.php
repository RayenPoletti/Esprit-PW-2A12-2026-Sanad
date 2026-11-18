<?php
/**
 * Models/Passeport.php — Module commun (toute l'équipe). Même fichier dans frontoffice/ et backoffice/.
 * Jointure : Utilisateur 1 — 0..1 Passeport (utilisateurId UNIQUE, suppression en cascade).
 */
class Passeport
{
    /** Besoins déclarables : colonne => libellé affiché */
    public const BESOINS = [
        'fauteuilRoulant'  => 'Je me déplace en fauteuil roulant',
        'mobiliteReduite'  => 'Je marche difficilement',
        'malvoyance'       => 'Je suis malvoyant(e)',
        'surdite'          => 'Je suis sourd(e) ou malentendant(e)',
        'accompagneAidant' => 'Je viens avec un aidant',
    ];

    private int $id = 0;
    private int $utilisateurId = 0;
    private bool $fauteuilRoulant = false;
    private bool $mobiliteReduite = false;
    private bool $malvoyance = false;
    private bool $surdite = false;
    private bool $accompagneAidant = false;
    private string $precisions = '';
    private bool $partageAutorise = false;
    private string $dateMiseAJour = '';

    public function getId(): int { return $this->id; }
    public function getUtilisateurId(): int { return $this->utilisateurId; }
    public function getBesoin(string $besoin): bool { return array_key_exists($besoin, self::BESOINS) && $this->$besoin; }
    public function getPrecisions(): string { return $this->precisions; }
    public function isPartageAutorise(): bool { return $this->partageAutorise; }
    public function getDateMiseAJour(): string { return $this->dateMiseAJour; }

    /** Libellés des besoins cochés. */
    public function getBesoinsDeclares(): array
    {
        $liste = [];
        foreach (self::BESOINS as $cle => $libelle) {
            if ($this->$cle) {
                $liste[] = $libelle;
            }
        }
        return $liste;
    }

    public function setId(int $id): void { $this->id = $id; }
    public function setUtilisateurId(int $id): void { $this->utilisateurId = $id; }
    public function setBesoin(string $besoin, bool $valeur): void
    {
        if (array_key_exists($besoin, self::BESOINS)) {
            $this->$besoin = $valeur;
        }
    }
    public function setPrecisions(string $precisions): void { $this->precisions = $precisions; }
    public function setPartageAutorise(bool $ok): void { $this->partageAutorise = $ok; }
    public function setDateMiseAJour(string $date): void { $this->dateMiseAJour = $date; }

    public static function fromArray(array $row): self
    {
        $p = new self();
        $p->setId((int) ($row['id'] ?? 0));
        $p->setUtilisateurId((int) ($row['utilisateurId'] ?? 0));
        foreach (array_keys(self::BESOINS) as $besoin) {
            $p->setBesoin($besoin, (bool) ($row[$besoin] ?? false));
        }
        $p->setPrecisions((string) ($row['precisions'] ?? ''));
        $p->setPartageAutorise((bool) ($row['partageAutorise'] ?? false));
        $p->setDateMiseAJour((string) ($row['dateMiseAJour'] ?? ''));
        return $p;
    }
}
