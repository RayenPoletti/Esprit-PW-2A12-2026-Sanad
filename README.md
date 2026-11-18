# Sanad – Plateforme de santé accessible

> **La santé accessible, vérifiée par ceux qui la vivent.**
> Projet développé à **Esprit School of Engineering – Tunisia** · Projet Technologies Web 2A · Année universitaire 2026–2027.

## Table des matières

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Contributors](#contributors)
- [Academic Context](#academic-context)
- [Sécurité, rôles et sessions](#sécurité-rôles-et-sessions)
- [Getting Started](#getting-started)
- [Utilisation](#utilisation)
- [Contribution](#contribution)
- [Acknowledgments](#acknowledgments)
- [Licence](#licence)

## Overview

This project was developed as part of the **Projet Technologies Web – 2nd Year Engineering Program** at **Esprit School of Engineering** (Academic Year 2026–2027).

**Sanad** (« soutien » en arabe) est une application web de santé et de bien-être inclusive.

**Le problème :** aujourd’hui, aucun site ne dit si un cabinet médical est vraiment accessible. Une personne en fauteuil, malvoyante ou sourde découvre souvent sur place que rien n’est prévu pour elle.

**Notre solution :**
- des **ambassadeurs**, des patients volontaires, vérifient sur place l’accessibilité des cabinets ;
- le **passeport d’accessibilité** du patient est transmis au cabinet avant chaque rendez-vous ;
- des **points solidaires** récompensent les audits et sont utilisables dans la boutique de matériel reconditionné.

Le projet contribue aux Objectifs de développement durable 3, 10, 11 et 12.

## Features

| Module | Responsable | Front Office | Back Office | Jointure |
|---|---|---|---|---|
| Commun : comptes et passeport | Toute l’équipe | Inscription, connexion, mon compte, mon passeport | CRUD utilisateurs, rôles, blocage, passeports | Utilisateur 1 — 0..1 Passeport |
| 1 · Audits d’accessibilité | Rayen Poletti | L’ambassadeur envoie un audit (6 critères) et suit ses audits | CRUD lieux et audits, validation / refus : **+50 points** et **score du lieu recalculé** | Lieu 1 — 0..* Audit |
| 2 · Professionnels et rendez-vous | Aziz Kouki | Recherche filtrée par **le passeport du membre** (critères des audits validés), prise et annulation de rendez-vous, **fiche médicale** | CRUD professionnels (validation), rendez-vous (confirmer, annuler, conflit de créneau) | Professionnel 1 — 0..* RendezVous · FicheMedicale 1 — 0..* Consultation |
| 3 · Programmes bien-être | Rayen Marzougi | Catalogue, détail des séances, inscription, progression séance par séance | CRUD programmes et séances (un programme sans séance est invisible : 1..*) | Programme 1 — 1..* Séance · Inscription |
| 4 · Communauté d’entraide | Houssem Hafsi | Publier, commenter, signaler, supprimer ses messages | Modération : publier / masquer, modifier, supprimer | Publication 1 — 0..* Commentaire |
| 5 · Boutique solidaire | Karim Bousetta | Panier, commande payée **en dinars ou en points** (transaction, contrôle du stock), annulation | CRUD produits, suivi des commandes (annuler rend le stock et les points) | Commande 0..* — 1..* Produit (LigneCommande) |

Chaque module a un **Front Office** (côté utilisateur) et un **Back Office** (tableau de bord administrateur).

## Tech Stack

### Frontend
- HTML5, CSS3, JavaScript
- **Front Office :** mise en page du template [Medilab](https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/), Bootstrap 5.3 et Font Awesome inclus dans `frontoffice/Views/assets/lib/` (rien à télécharger)
- **Back Office :** template [TailAdmin](https://github.com/TailAdmin/tailadmin-free-tailwind-dashboard-template) (Tailwind CSS v4, Alpine.js, ApexCharts)

### Backend
- PHP 8 en architecture **MVC** (Models, Views, Controllers)
- MySQL via **PDO** (classe `Database` en singleton, requêtes préparées) — schéma dans `database/sanad.sql`
- Sessions PHP sécurisées, jeton CSRF, `password_hash()` (voir [Sécurité](#sécurité-rôles-et-sessions))

## Architecture

Le Front Office et le Back Office suivent **la même structure MVC que le workshop** (projet eLearning) :

```
Esprit-PW-2A12-2026-Sanad/
├── frontoffice/                     # Partie client (template Medilab)
│   ├── Controllers/                 # le « cerveau » : reçoit la requête, appelle le modèle, choisit la vue
│   │   ├── Controller.php           # classe de base : view(), redirect()
│   │   ├── AuthController.php       # connexion, inscription, rôles, CSRF, messages flash
│   │   ├── Validator.php            # contrôle des saisies côté serveur
│   │   └── HomeController.php, CompteController.php, PasseportController.php, ...
│   ├── Models/                      # les classes (attributs privés, getters/setters, sans SQL)
│   │   └── Utilisateur.php, Passeport.php
│   ├── Views/                       # l’interface en PHP
│   │   ├── assets/
│   │   │   ├── css/                 # main.css (mise en page Medilab) + sanad.css (charte Sanad)
│   │   │   ├── img/                 # images
│   │   │   ├── js/                  # main.js (menu mobile, preloader) + validation.js
│   │   │   └── lib/                 # bibliothèques externes : Bootstrap 5.3, Font Awesome
│   │   ├── includes/                # blocs répétés : head, spinner, navbar, footer, scripts (+ helpers)
│   │   └── accueil.php, connexion.php, compte.php, passeport.php, 403.php, ...
│   ├── config.php                   # base de données (Database), session sécurisée, en-têtes HTTP
│   └── index.php                    # point d’entrée : routes + contrôle d’accès par rôle
├── backoffice/                      # Tableau de bord (template TailAdmin), réservé au rôle admin
│   ├── Controllers/                 # Controller.php (render, redirect), AuthController, Validator,
│   │                                # DashboardController, UtilisateurController, LieuController, ...
│   ├── Models/                      # Utilisateur, Passeport + une classe par entité des modules 1 à 5
│   ├── Views/
│   │   ├── assets/
│   │   │   ├── css/                 # style.css (compilé) + input.css (source Tailwind)
│   │   │   ├── img/                 # logos, favicon
│   │   │   ├── js/                  # app.js, validation.js
│   │   │   └── lib/                 # ApexCharts
│   │   ├── includes/                # head, spinner, sidebar, navbar, footer, scripts, layout (+ helpers)
│   │   └── dashboard.php, utilisateurs.php, utilisateur_form.php, lieux.php, 403.php, ...
│   ├── config.php
│   └── index.php                    # point d’entrée : routes + requireRole(['admin'])
├── database/sanad.sql               # schéma + comptes de démonstration
└── docs/                            # diagrammes, tâches, guides GitHub et Git
```

Les classes du module commun (`AuthController.php`, `Validator.php`, `Utilisateur.php`, `Passeport.php`) et `config.php` existent dans les deux espaces, pour que chacun respecte la structure du workshop. **Si vous modifiez l’un de ces fichiers, recopiez-le dans l’autre espace.**

![Diagramme de classes](docs/diagramme-classes.png)

## Contributors

- Rayen Poletti
- Aziz Kouki
- Rayen Marzougi
- Houssem Hafsi
- Karim Bousetta

## Academic Context

Developed at **Esprit School of Engineering – Tunisia**
Projet Technologies Web – 2A | 2026–2027

## Sécurité, rôles et sessions

Réalisé selon le workshop **« Session & Sécurité »**.

### Rôles et droits d’accès

| Page | Visiteur | Membre / Aidant / Professionnel | Ambassadeur | Admin |
|---|:-:|:-:|:-:|:-:|
| Accueil, professionnels, programmes, communauté (lecture), boutique | ✅ | ✅ | ✅ | ✅ |
| Inscription, connexion | ✅ | — | — | — |
| Mon compte, mon passeport, prendre rendez-vous, publier/commenter | ➜ connexion | ✅ | ✅ | ✅ |
| Prendre rendez-vous, panier et commandes, s’inscrire à un programme | ➜ connexion | ✅ | ✅ | ✅ |
| Ma fiche médicale (`?page=fiche`) | ➜ connexion | ✅ (la sienne) | ✅ (la sienne) | ✅ (la sienne) |
| Mes patients, dossier d’un patient (`?page=patients`) | ➜ connexion | ✅ rôle **professionnel** seulement, et si le patient a autorisé le partage | ⛔ 403 | ⛔ 403 |
| Auditer un lieu (`?page=audits`) | ➜ connexion | ⛔ 403 | ✅ | ✅ |
| Back Office (toutes les pages) | ➜ connexion | ⛔ 403 | ⛔ 403 | ✅ |

- Le contrôle est fait **côté serveur à chaque requête** (`requireAuth()` / `requireRole()` dans les deux `index.php`), pas seulement en cachant les liens.
- Le rôle et le statut sont **relus en base à chaque requête** : un compte bloqué ou supprimé par l’admin est déconnecté immédiatement.
- À l’inscription, le rôle est **forcé à « membre »** ; seul un admin peut changer un rôle (Back Office > Utilisateurs).
- Un admin ne peut ni se supprimer, ni se bloquer, ni se retirer le rôle admin.
- Après connexion, l’admin est envoyé au Back Office, les autres rôles au Front Office.

### Fiche médicale et antécédents

Chaque patient a une **fiche médicale** (groupe sanguin, allergies, maladies chroniques, traitements en cours) et la liste de ses **consultations**.

- **Le patient** (`Ma fiche médicale`) remplit sa fiche, lit ses consultations et choisit s’il **autorise les professionnels** à la consulter. Il peut retirer cette autorisation à tout moment.
- **Le médecin** (rôle « professionnel », `Mes patients`) retrouve un patient par son e-mail. Il ouvre la fiche **seulement si le patient l’a autorisé**, ce qui est vérifié à chaque requête. Il ajoute ce qu’il a fait (date, spécialité, motif, diagnostic, traitement, observations pour le prochain médecin).
- Un médecin ne peut **corriger que ses propres consultations**, et aucune consultation ne peut être supprimée : l’historique reste complet pour le médecin suivant.
- Les administrateurs n’ont **aucun accès** au contenu des fiches (secret médical).
- Si le compte d’un médecin est supprimé, ses consultations restent dans le dossier du patient (clé étrangère `ON DELETE SET NULL`).

Tables : `ficheMedicale` (Utilisateur 1 — 0..1 FicheMedicale) et `consultation` (FicheMedicale 1 — 0..* Consultation, rédigée par un Utilisateur de rôle professionnel).

### Gestion des utilisateurs (Back Office)

Liste avec **recherche** (nom, e-mail, ville), **filtres** (rôle, statut), **pagination** et compteurs par rôle · **ajout** · **modification** (mot de passe facultatif) · **blocage / déblocage** · **suppression** (le passeport est supprimé en cascade ; un compte ayant des audits ou des rendez-vous ne peut pas être supprimé, il faut le bloquer) · liste des **passeports** (jointure Utilisateur ↔ Passeport) · statistiques sur le tableau de bord.

Côté Front Office : inscription, connexion, déconnexion, **mon compte** (profil + changement de mot de passe avec vérification de l’ancien), **mon passeport** (création, mise à jour, suppression).

### Mesures de sécurité

| Risque | Protection | Où |
|---|---|---|
| Injection SQL | requêtes préparées PDO, `ATTR_EMULATE_PREPARES => false` | `config.php`, contrôleurs |
| Mots de passe volés | `password_hash()` / `password_verify()` (bcrypt) | `AuthController` |
| XSS | `e()` = `htmlspecialchars()` sur chaque valeur affichée + en-tête CSP | vues, `config.php` |
| CSRF | jeton aléatoire (`random_bytes`) vérifié avec `hash_equals()` sur chaque POST ; suppression/blocage uniquement en POST | `AuthController`, formulaires |
| Vol / fixation de session | `session_regenerate_id(true)` à la connexion, cookie `HttpOnly` + `SameSite=Lax`, `use_strict_mode` | `config.php`, `AuthController::login()` |
| Session oubliée ouverte | expiration après **30 min** d’inactivité | `config.php` |
| Clickjacking, MIME sniffing | `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` | `config.php` |
| Énumération des comptes | message unique « E-mail ou mot de passe incorrect » | `AuthController::login()` |

### Contrôle des saisies

Chaque formulaire est contrôlé **deux fois** :
1. **JavaScript** (`assets/js/validation.js`) : message sous le champ dès qu’on le quitte et à l’envoi. Les règles sont écrites dans le HTML (`data-required`, `data-rule="email"`, `data-match="motDePasse"`…). Le formulaire porte `novalidate` pour ne pas utiliser les bulles du navigateur.
2. **PHP** (`Controllers/Validator.php`) : les mêmes règles sont refaites sur le serveur, car le JavaScript peut être désactivé ou contourné. Les erreurs s’affichent sous chaque champ et les valeurs saisies sont conservées.

> **Tous** les formulaires des deux espaces (modules commun et 1 à 5) sont validés en JavaScript **et** en PHP. Règles disponibles dans `Validator` : obligatoire, nom, email, téléphone, mot de passe, identique, liste autorisée, entier, décimal, date passée, date future, URL (http/https seulement), longueur min/max.

### Comptes de démonstration

| E-mail | Mot de passe | Rôle |
|---|---|---|
| admin@sanad.tn | `Admin@1234` | Administrateur (Back Office) |
| amira@exemple.tn | `Membre@1234` | Ambassadeur (fiche médicale partagée, 2 consultations) |
| hedi@exemple.tn | `Membre@1234` | Membre |
| salma@exemple.tn | `Membre@1234` | Aidant |
| leila@exemple.tn | `Membre@1234` | Professionnel (médecin : « Mes patients », dossier d’Amira) |
| mourad@exemple.tn | `Membre@1234` | Membre **bloqué** (pour tester le refus de connexion) |

## Getting Started

### Prérequis
- [XAMPP](https://www.apachefriends.org/) ou WAMP (PHP 8 et MySQL)
- Git
- Une connexion Internet (les polices Google Fonts et Alpine.js du Back Office sont chargées en ligne)

### Installation

1. Clonez le dépôt dans le dossier `htdocs` de XAMPP (ou `www` de WAMP) :
   ```bash
   git clone https://github.com/RayenPoletti/Esprit-PW-2A12-2026-Sanad.git
   cd Esprit-PW-2A12-2026-Sanad
   ```
2. Créez la base de données :
   * ouvrez phpMyAdmin (`http://localhost/phpmyadmin`) ;
   * importez le fichier `database/sanad.sql`.
3. Adaptez si besoin les constantes de connexion (utilisateur, mot de passe MySQL) dans `frontoffice/config.php` **et** `backoffice/config.php`.
4. Lancez Apache et MySQL depuis XAMPP.

## Utilisation

- **Front Office :** `http://localhost/Esprit-PW-2A12-2026-Sanad/frontoffice/index.php?page=accueil`
- **Back Office :** `http://localhost/Esprit-PW-2A12-2026-Sanad/backoffice/index.php?page=dashboard`

Sans XAMPP, avec le serveur intégré de PHP (depuis la racine du dépôt) :
```bash
php -S localhost:8000
# Front Office : http://localhost:8000/frontoffice/index.php
# Back Office  : http://localhost:8000/backoffice/index.php (connexion admin)
```

Le Front Office et le Back Office partagent la même session : un admin connecté sur l’un l’est aussi sur l’autre.

### Ajouter une page (Back Office)
1. Créez la vue `backoffice/Views/ma_page.php` (HTML pur).
2. Ajoutez une méthode dans le contrôleur du module, qui définit `$this->page`, `$this->title` puis appelle `$this->render('ma_page')`.
3. Déclarez la route dans le tableau `$routes` de `backoffice/index.php`.
4. Ajoutez le lien dans `backoffice/Views/includes/sidebar.php` : il devient actif automatiquement.

### Recompiler le CSS du Back Office
Le fichier `backoffice/Views/assets/css/style.css` est déjà compilé. Si vous ajoutez des classes Tailwind qui ne sont pas encore utilisées, recompilez avec le [Tailwind CSS CLI](https://tailwindcss.com/docs/installation/tailwind-cli) :
```bash
cd backoffice/Views/assets/css
npx @tailwindcss/cli -i input.css -o style.css --minify
```

## Contribution

1. Créez une branche pour votre module :
   ```bash
   git checkout -b module-1-audits
   ```
2. Faites des commits clairs :
   ```bash
   git add .
   git commit -m "Module 1 : liste des audits dans le Back Office"
   ```
3. Poussez la branche et ouvrez une Pull Request vers `main` :
   ```bash
   git push origin module-1-audits
   ```
4. Mettez à jour l’état de vos tâches dans le tableau **Projects** du dépôt.

Le guide pas à pas (branches, commits, fusion, conflits, validation sur une seule machine) est dans [`docs/GIT-EQUIPE.md`](docs/GIT-EQUIPE.md).

## Acknowledgments

- **Esprit School of Engineering** et les tuteurs du module Projet Technologies Web
- [TailAdmin](https://tailadmin.com) — template du Back Office (licence MIT)
- [Medilab par BootstrapMade](https://bootstrapmade.com/) — template du Front Office
- [ApexCharts](https://apexcharts.com/), [Alpine.js](https://alpinejs.dev/), [Tailwind CSS](https://tailwindcss.com/)

## Licence

Le code de ce projet est distribué sous licence **MIT** (voir le fichier `LICENSE`).
Les templates gardent leur propre licence : TailAdmin (MIT) et Medilab (licence gratuite BootstrapMade, qui impose de garder le lien de crédit dans le footer).
