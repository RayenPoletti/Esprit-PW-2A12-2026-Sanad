# Travailler en équipe avec Git : branches, commits, fusion

Les tuteurs vérifient sur GitHub **qui a fait quoi** (onglet *Insights > Contributors* et l’historique des commits). Chaque membre doit donc **commiter lui-même, depuis son propre compte GitHub**, la partie dont il est responsable. Si une seule personne pousse tout, les autres n’apparaissent pas.

## 0. Une seule fois, sur chaque PC

```bash
git config --global user.name "Prénom Nom"
git config --global user.email "adresse-du-compte-github@exemple.com"
git clone https://github.com/RayenPoletti/Esprit-PW-2A12-2026-Sanad.git
cd Esprit-PW-2A12-2026-Sanad
```

L’adresse e-mail doit être celle du compte GitHub, sinon les commits ne sont pas comptés à votre nom.
Le propriétaire du dépôt doit avoir invité chaque membre (*Settings > Collaborators > Add people*) et chacun doit avoir accepté l’invitation.

## 1. Qui fait quoi (module commun)

| Partie | Fichiers | Branche | Responsable |
|---|---|---|---|
| Socle sécurité | `frontoffice/config.php`, `backoffice/config.php` | `commun-securite` | Rayen Poletti |
| Authentification | `Controllers/AuthController.php` (les deux espaces), `frontoffice/Controllers/CompteController.php` (connexion/inscription), vues `connexion.php`, `inscription.php` | `commun-auth` | Aziz Kouki |
| Rôles et accès | `frontoffice/index.php`, `backoffice/index.php`, les deux `403.php`, `navbar.php` | `commun-roles` | Rayen Marzougi |
| CRUD utilisateurs (Back Office) | `backoffice/Controllers/UtilisateurController.php`, `PasseportController.php`, `DashboardController.php`, vues `utilisateurs.php`, `utilisateur_form.php`, `passeports.php` | `commun-crud-bo` | Houssem Hafsi |
| Mon compte + passeport (Front Office) | `frontoffice/Controllers/PasseportController.php`, vues `compte.php`, `passeport.php` | `commun-compte-fo` | Karim Bousetta |
| Contrôle de saisie | `Controllers/Validator.php`, `Views/assets/js/validation.js` (les deux espaces) | `commun-validation` | à se répartir |

Ce découpage est une proposition : changez-le si vous voulez, mais gardez **une partie par personne**.

### Fichiers de chaque module (à commiter par son responsable)

| Module | Branche | Fichiers |
|---|---|---|
| 1 · Rayen Poletti | `module-1-audits` | `backoffice/Controllers/LieuController.php`, `AuditController.php`, vues `lieux.php`, `lieu_form.php`, `audits.php`, `audit_form.php` · `frontoffice/Controllers/AuditController.php`, `Views/audits.php` · modèles `Lieu.php`, `Audit.php` (les deux espaces) |
| 2 · Aziz Kouki | `module-2-rdv` | `backoffice/Controllers/ProfessionnelController.php`, `RendezVousController.php` + leurs 4 vues · `frontoffice/Controllers/ProfessionnelController.php`, `FicheMedicaleController.php`, vues `professionnels.php`, `rendezvous.php`, `fiche.php`, `patients.php`, `dossier.php`, `consultation_form.php` · modèles `Professionnel.php`, `RendezVous.php`, `FicheMedicale.php`, `Consultation.php` |
| 3 · Rayen Marzougi | `module-3-programmes` | `backoffice/Controllers/ProgrammeController.php`, `SeanceController.php` + leurs 4 vues · `frontoffice/Controllers/ProgrammeController.php`, vues `programmes.php`, `programme.php` · modèles `Programme.php`, `Seance.php` |
| 4 · Houssem Hafsi | `module-4-communaute` | `backoffice/Controllers/PublicationController.php`, `CommentaireController.php`, vues `publications.php`, `publication_form.php`, `commentaires.php`, `commentaire_form.php` · `frontoffice/Controllers/CommunauteController.php`, `Views/communaute.php` · modèles `Publication.php`, `Commentaire.php` |
| 5 · Karim Bousetta | `module-5-boutique` | `backoffice/Controllers/ProduitController.php`, `CommandeController.php`, vues `produits.php`, `produit_form.php`, `commandes.php` · `frontoffice/Controllers/BoutiqueController.php`, vues `boutique.php`, `panier.php` · modèles `Produit.php`, `Commande.php` |
| Commun | voir le tableau ci-dessus | `index.php` (routes), `Views/includes/*`, `config.php`, `Validator.php`, `database/sanad.sql`, `README.md` : **un seul membre à la fois**, en prévenant le groupe |

## 2. Le cycle de travail (à répéter)

```bash
# a) Partir de la dernière version de main
git checkout main
git pull origin main

# b) Créer sa branche (une fois par tâche)
git checkout -b commun-auth

# c) Travailler, puis enregistrer par petites étapes
git status                       # voir les fichiers modifiés
git add frontoffice/Controllers/AuthController.php backoffice/Controllers/AuthController.php
git commit -m "Auth : connexion avec password_verify et session_regenerate_id"

# d) Envoyer la branche sur GitHub
git push -u origin commun-auth
```

Puis sur GitHub : bouton **Compare & pull request** → base `main` ← `commun-auth` → *Create pull request*. Un autre membre relit et clique **Merge pull request**.
Dans le tableau **Projects**, déplacez la carte de la tâche : *Todo → In Progress → Done*. Écrivez `Closes #12` (numéro de l’issue) dans la description de la pull request pour fermer l’issue automatiquement.

### Bons messages de commit

- ✅ `CRUD utilisateurs : recherche, filtres rôle/statut et pagination`
- ✅ `Validation : message sous chaque champ du formulaire d’inscription`
- ❌ `update`, `modif`, `final2`

## 3. Récupérer le travail des autres

```bash
git checkout main
git pull origin main          # récupère les branches fusionnées
git checkout ma-branche
git merge main                # met ma branche à jour
```

## 4. Résoudre un conflit

Si deux personnes ont modifié les mêmes lignes, Git affiche `CONFLICT`. Ouvrez le fichier (VS Code propose *Accept Current / Accept Incoming / Accept Both*) :

```
<<<<<<< HEAD
ma version
=======
la version de main
>>>>>>> main
```

Gardez le bon code, supprimez les marques `<<<<<<<`, `=======`, `>>>>>>>`, puis :

```bash
git add le-fichier.php
git commit -m "Fusion de main dans commun-auth"
git push
```

Conseil : pour éviter les conflits, ne modifiez pas le même fichier en même temps. Les fichiers partagés (`index.php`, `Views/includes/sidebar.php`, `sanad.sql`) : prévenez le groupe avant de les toucher.

## 5. Validation sur une seule machine

Le jour de la validation, tout doit tourner sur **un seul PC** :

1. Toutes les pull requests sont fusionnées dans `main` (aucune branche en attente).
2. Sur le PC de démonstration :
   ```bash
   cd C:/xampp/htdocs/Esprit-PW-2A12-2026-Sanad
   git checkout main
   git pull origin main
   ```
3. Dans phpMyAdmin, importez `database/sanad.sql` (la base est recréée avec les comptes de démonstration).
4. Testez dans cet ordre :
   - inscription d’un nouveau membre → connexion → mon compte → mon passeport ;
   - un membre qui ouvre `?page=audits` ou le Back Office reçoit **403** ;
   - connexion `admin@sanad.tn` / `Admin@1234` → Back Office → ajouter, modifier, bloquer, supprimer un utilisateur ;
   - le membre bloqué est déconnecté tout de suite ;
   - envoyer un formulaire vide : messages d’erreur JavaScript, puis (JavaScript désactivé) messages PHP.
5. Sur GitHub, ouvrez *Insights > Contributors* : chaque membre doit apparaître avec ses commits.
