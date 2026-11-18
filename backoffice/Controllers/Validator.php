<?php
/**
 * Controllers/Validator.php — Module commun (toute l'équipe). Même fichier dans frontoffice/ et backoffice/.
 * Contrôle des saisies côté serveur (indispensable : le JavaScript peut être désactivé ou contourné).
 * Chaque règle enregistre au plus une erreur par champ.
 *
 * Exemple :
 *   $v = new Validator($_POST);
 *   $v->obligatoire('email', 'L’e-mail')->email('email');
 *   if ($v->echoue()) { $erreurs = $v->erreurs(); }
 */
class Validator
{
    public const REGEX_NOM       = "/^[\p{L}][\p{L}\s'’-]{1,49}$/u";
    public const REGEX_TELEPHONE = '/^\+?[0-9 ]{8,15}$/';

    private array $donnees;
    private array $erreurs = [];

    public function __construct(array $donnees)
    {
        $this->donnees = $donnees;
    }

    /** Valeur nettoyée (espaces retirés) d'un champ. */
    public function valeur(string $champ): string
    {
        $v = $this->donnees[$champ] ?? '';
        return is_string($v) ? trim($v) : '';
    }

    private function ajouter(string $champ, string $message): self
    {
        if (!isset($this->erreurs[$champ])) {
            $this->erreurs[$champ] = $message;
        }
        return $this;
    }

    private function vide(string $champ): bool
    {
        return $this->valeur($champ) === '';
    }

    public function obligatoire(string $champ, string $libelle): self
    {
        return $this->vide($champ) ? $this->ajouter($champ, "$libelle est obligatoire.") : $this;
    }

    public function nom(string $champ, string $libelle): self
    {
        if (!$this->vide($champ) && !preg_match(self::REGEX_NOM, $this->valeur($champ))) {
            $this->ajouter($champ, "$libelle doit contenir de 2 à 50 lettres (espaces, tirets et apostrophes acceptés).");
        }
        return $this;
    }

    public function email(string $champ): self
    {
        $v = $this->valeur($champ);
        if ($v !== '' && (!filter_var($v, FILTER_VALIDATE_EMAIL) || mb_strlen($v) > 150)) {
            $this->ajouter($champ, 'Adresse e-mail invalide (exemple : nom@exemple.tn).');
        }
        return $this;
    }

    public function telephone(string $champ): self
    {
        if (!$this->vide($champ) && !preg_match(self::REGEX_TELEPHONE, $this->valeur($champ))) {
            $this->ajouter($champ, 'Téléphone invalide : 8 à 15 chiffres, « + » accepté au début.');
        }
        return $this;
    }

    /** Mot de passe : 8 caractères minimum, au moins une lettre et un chiffre. */
    public function motDePasse(string $champ): self
    {
        $v = $this->donnees[$champ] ?? '';
        if ($v !== '' && (mb_strlen($v) < 8 || !preg_match('/[A-Za-z]/', $v) || !preg_match('/\d/', $v))) {
            $this->ajouter($champ, 'Le mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.');
        }
        return $this;
    }

    public function identique(string $champ, string $autre, string $message): self
    {
        if (($this->donnees[$champ] ?? '') !== ($this->donnees[$autre] ?? '')) {
            $this->ajouter($champ, $message);
        }
        return $this;
    }

    public function dansListe(string $champ, array $valeurs, string $libelle): self
    {
        if (!in_array($this->valeur($champ), $valeurs, true)) {
            $this->ajouter($champ, "$libelle n’est pas une valeur autorisée.");
        }
        return $this;
    }

    public function entier(string $champ, int $min, int $max, string $libelle): self
    {
        $v = $this->valeur($champ);
        if ($v !== '' && (filter_var($v, FILTER_VALIDATE_INT) === false || (int) $v < $min || (int) $v > $max)) {
            $this->ajouter($champ, "$libelle doit être un nombre entier entre $min et $max.");
        }
        return $this;
    }

    /** Nombre décimal (virgule ou point accepté) compris entre $min et $max. */
    public function decimal(string $champ, float $min, float $max, string $libelle): self
    {
        if ($this->vide($champ)) {
            return $this;
        }
        $v = str_replace(',', '.', $this->valeur($champ));
        if (!preg_match('/^-?\d+(\.\d{1,2})?$/', $v)) {
            return $this->ajouter($champ, "$libelle doit être un nombre (exemple : 12.5), avec 2 décimales au maximum.");
        }
        if ((float) $v < $min || (float) $v > $max) {
            return $this->ajouter($champ, "$libelle doit être compris entre $min et $max.");
        }
        return $this;
    }

    /** Valeur décimale prête pour la base (« 12,5 » → 12.5), ou null si le champ est vide. */
    public function nombre(string $champ): ?float
    {
        return $this->vide($champ) ? null : (float) str_replace(',', '.', $this->valeur($champ));
    }

    /** Date au format AAAA-MM-JJ, réelle, entre 1900 et aujourd'hui. */
    public function datePassee(string $champ, string $libelle): self
    {
        if ($this->vide($champ)) {
            return $this;
        }
        $d = DateTime::createFromFormat('!Y-m-d', $this->valeur($champ));
        if (!$d || $d->format('Y-m-d') !== $this->valeur($champ)) {
            return $this->ajouter($champ, "$libelle n’est pas une date valide.");
        }
        if ($d > new DateTime('today') || $d < new DateTime('1900-01-01')) {
            return $this->ajouter($champ, "$libelle ne peut pas être dans le futur.");
        }
        return $this;
    }

    /** Date au format AAAA-MM-JJ, réelle, à partir de demain et au plus dans un an. */
    public function dateFuture(string $champ, string $libelle): self
    {
        if ($this->vide($champ)) {
            return $this;
        }
        $d = DateTime::createFromFormat('!Y-m-d', $this->valeur($champ));
        if (!$d || $d->format('Y-m-d') !== $this->valeur($champ)) {
            return $this->ajouter($champ, "$libelle n’est pas une date valide.");
        }
        if ($d <= new DateTime('today') || $d > new DateTime('+1 year')) {
            return $this->ajouter($champ, "$libelle doit être comprise entre demain et dans un an.");
        }
        return $this;
    }

    /** Adresse web commençant par http:// ou https:// (refuse javascript:, data:…). */
    public function url(string $champ, string $libelle): self
    {
        $v = $this->valeur($champ);
        if ($v !== '' && (!filter_var($v, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $v) || mb_strlen($v) > 255)) {
            $this->ajouter($champ, "$libelle doit être une adresse web commençant par http:// ou https://.");
        }
        return $this;
    }

    public function longueurMin(string $champ, int $min, string $libelle): self
    {
        if (!$this->vide($champ) && mb_strlen($this->valeur($champ)) < $min) {
            $this->ajouter($champ, "$libelle doit contenir au moins $min caractères.");
        }
        return $this;
    }

    public function longueurMax(string $champ, int $max, string $libelle): self
    {
        if (mb_strlen($this->valeur($champ)) > $max) {
            $this->ajouter($champ, "$libelle ne doit pas dépasser $max caractères.");
        }
        return $this;
    }

    /** Ajoute une erreur calculée ailleurs (ex. : e-mail déjà utilisé). */
    public function erreur(string $champ, string $message): self
    {
        return $this->ajouter($champ, $message);
    }

    public function echoue(): bool
    {
        return !empty($this->erreurs);
    }

    /** @return array<string,string> champ => message */
    public function erreurs(): array
    {
        return $this->erreurs;
    }
}
