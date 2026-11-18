<?php
/**
 * Formulaire Ajouter / Modifier un utilisateur · Module commun
 * Variables : $utilisateur (?Utilisateur), $estModification, $old (valeurs saisies), $erreurs (champ => message), $csrfToken
 * Double contrôle : validation.js (data-*) dans le navigateur, puis Validator.php sur le serveur.
 */
$titre  = $estModification ? 'Modifier un utilisateur' : 'Ajouter un utilisateur';
$action = $estModification ? 'index.php?page=utilisateur-edit&id=' . (int) $utilisateur->getId() : 'index.php?page=utilisateur-form';
$label  = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
$val    = fn(string $c): string => e($old[$c] ?? '');
?>
<?= filAriane($titre, ['Utilisateurs' => 'index.php?page=utilisateurs']) ?>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $estModification ? 'Compte de ' . e($utilisateur->getNomComplet()) : 'Nouveau compte' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Les champs marqués * sont obligatoires.</p>
  </div>

  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>

    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <div>
        <label for="prenom" class="<?= $label ?>">Prénom *</label>
        <input id="prenom" name="prenom" type="text" value="<?= $val('prenom') ?>" placeholder="Amira" data-label="Le prénom" data-required data-rule="nom" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'prenom') ?> />
        <?= zoneErreur($erreurs, 'prenom') ?>
      </div>
      <div>
        <label for="nom" class="<?= $label ?>">Nom *</label>
        <input id="nom" name="nom" type="text" value="<?= $val('nom') ?>" placeholder="Ben Ali" data-label="Le nom" data-required data-rule="nom" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'nom') ?> />
        <?= zoneErreur($erreurs, 'nom') ?>
      </div>
      <div>
        <label for="email" class="<?= $label ?>">Adresse e-mail *</label>
        <input id="email" name="email" type="email" value="<?= $val('email') ?>" placeholder="nom@exemple.tn" autocomplete="off" data-label="L’e-mail" data-required data-rule="email" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'email') ?> />
        <?= zoneErreur($erreurs, 'email') ?>
      </div>
      <div>
        <label for="telephone" class="<?= $label ?>">Téléphone</label>
        <input id="telephone" name="telephone" type="tel" value="<?= $val('telephone') ?>" placeholder="+216 20 123 456" data-label="Le téléphone" data-rule="telephone" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'telephone') ?> />
        <?= zoneErreur($erreurs, 'telephone') ?>
      </div>
      <div>
        <label for="ville" class="<?= $label ?>">Ville</label>
        <input id="ville" name="ville" type="text" value="<?= $val('ville') ?>" placeholder="Tunis" data-label="La ville" data-rule="nom" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'ville') ?> />
        <?= zoneErreur($erreurs, 'ville') ?>
      </div>
      <div>
        <label for="points" class="<?= $label ?>">Points solidaires *</label>
        <input id="points" name="points" type="text" inputmode="numeric" value="<?= $val('points') ?>" data-label="Le nombre de points" data-required data-rule="entier" data-min="0" data-max="100000" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'points') ?> />
        <?= zoneErreur($erreurs, 'points') ?>
      </div>
      <div>
        <label for="role" class="<?= $label ?>">Rôle *</label>
        <select id="role" name="role" data-label="Le rôle" data-required class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'role') ?>>
          <?php foreach (Utilisateur::LIBELLES_ROLES as $cle => $libelle): ?>
            <option value="<?= e($cle) ?>" <?= ($old['role'] ?? '') === $cle ? 'selected' : '' ?>><?= e($libelle) ?></option>
          <?php endforeach; ?>
        </select>
        <?= zoneErreur($erreurs, 'role') ?>
      </div>
      <div>
        <label for="statut" class="<?= $label ?>">Statut *</label>
        <select id="statut" name="statut" data-label="Le statut" data-required class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'statut') ?>>
          <option value="actif" <?= ($old['statut'] ?? '') === 'actif' ? 'selected' : '' ?>>Actif</option>
          <option value="bloque" <?= ($old['statut'] ?? '') === 'bloque' ? 'selected' : '' ?>>Bloqué (connexion impossible)</option>
        </select>
        <?= zoneErreur($erreurs, 'statut') ?>
      </div>
      <div>
        <label for="motDePasse" class="<?= $label ?>">Mot de passe <?= $estModification ? '(laisser vide pour le garder)' : '*' ?></label>
        <input id="motDePasse" name="motDePasse" type="password" autocomplete="new-password" placeholder="8 caractères, lettres et chiffres" data-label="Le mot de passe" <?= $estModification ? '' : 'data-required' ?> data-rule="motdepasse" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'motDePasse') ?> />
        <?= zoneErreur($erreurs, 'motDePasse') ?>
      </div>
      <div>
        <label for="confirmation" class="<?= $label ?>">Confirmer le mot de passe</label>
        <input id="confirmation" name="confirmation" type="password" autocomplete="new-password" data-label="La confirmation" data-match="motDePasse" data-match-msg="Les deux mots de passe ne sont pas identiques." class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'confirmation') ?> />
        <?= zoneErreur($erreurs, 'confirmation') ?>
      </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"><?= $estModification ? 'Enregistrer les modifications' : 'Créer le compte' ?></button>
      <a href="index.php?page=utilisateurs" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">Annuler</a>
    </div>
  </form>
</div>
