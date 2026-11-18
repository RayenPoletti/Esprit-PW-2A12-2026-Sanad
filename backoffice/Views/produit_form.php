<?php
/**
 * Ajouter / Modifier un produit · Module 5 · Karim Bousetta · jointure Produit ↔ Commande
 * Variables : $produit (?Produit), $estModification, $old (valeurs saisies), $erreurs (champ => message), $csrfToken
 * Double contrôle : validation.js (attributs data-*) puis Validator.php côté serveur.
 */
$titre  = $estModification ? 'Modifier un produit' : 'Ajouter un produit';
$action = $estModification ? 'index.php?page=produit-edit&id=' . (int) $produit->getId() : 'index.php?page=produit-form';
$label  = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
$val    = fn(string $c): string => e($old[$c] ?? '');
?>
<?= filAriane($titre, ['Produits' => 'index.php?page=produits']) ?>

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
  <div class="px-5 py-4 sm:px-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= $estModification ? e($produit->getNom()) : 'Nouveau produit' ?></h3>
    <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Module 5 · Karim Bousetta · Les champs marqués * sont obligatoires.</p>
  </div>

  <form action="<?= e($action) ?>" method="post" data-validate novalidate class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>" />
    <?php if (isset($erreurs['_form'])): ?><?= bandeauFlash(['type' => 'danger', 'message' => $erreurs['_form']]) ?><?php endif; ?>

    <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
      <div>
        <label for="nom" class="<?= $label ?>">Nom du produit *</label>
        <input id="nom" name="nom" type="text" value="<?= $val('nom') ?>" placeholder="Fauteuil roulant pliant" data-label="Le nom du produit" data-required data-minlength="2" data-maxlength="100" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'nom') ?> />
        <?= zoneErreur($erreurs, 'nom') ?>
      </div>
      <div>
        <label for="etat" class="<?= $label ?>">État *</label>
        <select id="etat" name="etat" data-label="L’état" data-required class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'etat') ?>>
          <option value="">Choisir…</option>
          <?php foreach (Produit::ETATS as $etat): ?>
            <option value="<?= e($etat) ?>" <?= ($old['etat'] ?? '') === $etat ? 'selected' : '' ?>><?= e($etat) ?></option>
          <?php endforeach; ?>
        </select>
        <?= zoneErreur($erreurs, 'etat') ?>
      </div>
      <div>
        <label for="prix" class="<?= $label ?>">Prix (DT) * <span class="font-normal text-gray-400">— 0 pour un don</span></label>
        <input id="prix" name="prix" type="text" inputmode="decimal" value="<?= $val('prix') ?>" placeholder="350" data-label="Le prix" data-required data-rule="decimal" data-min="0" data-max="10000" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'prix') ?> />
        <?= zoneErreur($erreurs, 'prix') ?>
      </div>
      <div>
        <label for="stock" class="<?= $label ?>">Stock *</label>
        <input id="stock" name="stock" type="text" inputmode="numeric" value="<?= $val('stock') ?>" placeholder="4" data-label="Le stock" data-required data-rule="entier" data-min="0" data-max="10000" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'stock') ?> />
        <?= zoneErreur($erreurs, 'stock') ?>
      </div>
      <div>
        <label for="poidsKg" class="<?= $label ?>">Poids (kg)</label>
        <input id="poidsKg" name="poidsKg" type="text" inputmode="decimal" value="<?= $val('poidsKg') ?>" placeholder="18" data-label="Le poids" data-rule="decimal" data-min="0" data-max="500" class="<?= CLASSE_CHAMP ?>"<?= ariaInvalide($erreurs, 'poidsKg') ?> />
        <?= zoneErreur($erreurs, 'poidsKg') ?>
      </div>
      <div class="sm:col-span-2">
        <label for="description" class="<?= $label ?>">Description *</label>
        <textarea id="description" name="description" rows="4" placeholder="État, dimensions, conditions de retrait…" data-label="La description" data-required data-minlength="10" data-maxlength="1000" class="<?= str_replace('h-11', 'min-h-28', CLASSE_CHAMP) ?>"<?= ariaInvalide($erreurs, 'description') ?>><?= $val('description') ?></textarea>
        <?= zoneErreur($erreurs, 'description') ?>
      </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600"><?= $estModification ? 'Enregistrer les modifications' : 'Ajouter le produit' ?></button>
      <a href="index.php?page=produits" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">Annuler</a>
    </div>
  </form>
</div>
