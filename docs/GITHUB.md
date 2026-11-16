# Mettre le projet sur GitHub (pas à pas)

Ce guide applique la directive « Standardisation des dépôts GitHub – Projets PI » d’Esprit.

## 1. Nom du dépôt (0,5 point)

Format obligatoire : `Esprit-[PI]-[Classe]-[AU]-[NomDuProjet]`

```
Esprit-PW-2AXX-2026-Sanad
```

➡️ Remplacez `2AXX` par votre classe (par exemple `2A12`), ici et dans le `README.md`.

## 2. Créer le dépôt

1. Sur GitHub : **New repository**.
2. Nom : `Esprit-PW-2AXX-2026-Sanad` · visibilité **Public**.
3. Ne cochez pas « Add a README » (il est déjà dans le dossier).
4. Dans un terminal, depuis le dossier du projet :

```bash
git init
git add .
git commit -m "Initialisation : structure MVC, templates Front et Back Office, diagramme de classes"
git branch -M main
git remote add origin https://github.com/<votre-compte>/Esprit-PW-2AXX-2026-Sanad.git
git push -u origin main
```

## 3. Description du dépôt (0,5 point)

Dans **About** (roue dentée à droite de la page du dépôt), collez :

```
Sanad – accessible health platform. Developed at Esprit School of Engineering – Tunisia (Academic Year 2026–2027) as part of the Projet Technologies Web 2A. PHP MVC, Tailwind CSS, Bootstrap, MySQL.
```

## 4. Topics (0,5 point)

Toujours dans **About → Topics**, ajoutez :

```
esprit-school-of-engineering
academic-project
esprit-pw
2026-2027
php
mvc
tailwindcss
bootstrap
mysql
accessibility
```

## 5. Inviter les membres et les tuteurs

**Settings → Collaborators → Add people**, puis entrez le nom d’utilisateur GitHub de :
- chaque membre de l’équipe (Rayen Poletti, Aziz Kouki, Rayen Marzougi, Houssem Hafsi, Karim Bousetta) ;
- chaque tuteur concerné.

Chaque personne doit accepter l’invitation reçue par e-mail.

## 6. Tableau de bord des tâches (GitHub Projects)

1. Onglet **Projects → Link a project → New project**.
2. Choisissez le modèle **Board** et nommez-le `Sanad – suivi des tâches`.
3. Gardez les colonnes **Todo**, **In Progress**, **Done**.
4. Créez les tâches de `docs/TACHES.md`, soit à la main avec **Add item**, soit automatiquement avec le script `docs/creer-issues.sh` (voir ci-dessous).
5. Assignez chaque tâche à son responsable et placez-la dans la bonne colonne.
6. Mettez le tableau à jour à chaque séance : c’est lui que les tuteurs consultent.

### Créer les tâches automatiquement (facultatif)

Avec le [GitHub CLI](https://cli.github.com/) connecté à votre compte (`gh auth login`) :

```bash
bash docs/creer-issues.sh <votre-compte>/Esprit-PW-2AXX-2026-Sanad
```

Le script crée une *issue* par tâche, avec une étiquette par module. Ajoutez-les ensuite au tableau (**Add item → #numéro**).

## 7. Hébergement (facultatif, 0,25 point)

Un projet PHP ne peut pas être hébergé sur GitHub Pages. Des hébergeurs gratuits PHP/MySQL existent (par exemple InfinityFree ou Render avec Docker). Ajoutez le lien obtenu dans **About → Website**.
