#!/usr/bin/env bash
# Crée une issue GitHub par tâche de docs/TACHES.md.
# Usage : bash docs/creer-issues.sh <compte>/<depot>
set -e
REPO="$1"
if [ -z "$REPO" ]; then echo "Usage : bash docs/creer-issues.sh <compte>/<depot>"; exit 1; fi

# Étiquettes par module
for L in general module-commun module-1 module-2 module-3 module-4 module-5; do
  gh label create "$L" --repo "$REPO" --force >/dev/null
done
gh label create "done" --repo "$REPO" --color 2E7D6B --force >/dev/null
gh label create "in-progress" --repo "$REPO" --color F28C6B --force >/dev/null

gh issue create --repo "$REPO" --title "Diagramme de cas d’utilisation général et détaillés" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Diagramme de classes" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "README structuré selon le guide Esprit" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Intégration du template Back Office TailAdmin en MVC (Controllers, Models, Views/includes, layout)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Schéma de la base de données MySQL (database/sanad.sql)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Module commun · pages Front : inscription, connexion, passeport" --body "Responsable : Toute l’équipe" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · pages Back : utilisateurs, ajout, passeports" --body "Responsable : Toute l’équipe" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module 1 · pages Front : formulaire d’audit, mes audits" --body "Responsable : Rayen Poletti" --label module-1 --label done
gh issue create --repo "$REPO" --title "Module 1 · pages Back : lieux, ajout lieu, audits, ajout audit" --body "Responsable : Rayen Poletti" --label module-1 --label done
gh issue create --repo "$REPO" --title "Module 2 · pages Front : professionnels, prise de rendez-vous" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 2 · pages Back : professionnels, rendez-vous, formulaires" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 3 · pages Front : programmes" --body "Responsable : Rayen Marzougi" --label module-3 --label done
gh issue create --repo "$REPO" --title "Module 3 · pages Back : programmes, séances, formulaires" --body "Responsable : Rayen Marzougi" --label module-3 --label done
gh issue create --repo "$REPO" --title "Module 4 · pages Front : communauté (publier, commenter, signaler)" --body "Responsable : Houssem Hafsi" --label module-4 --label done
gh issue create --repo "$REPO" --title "Module 4 · pages Back : publications, commentaires, modération" --body "Responsable : Houssem Hafsi" --label module-4 --label done
gh issue create --repo "$REPO" --title "Module 5 · pages Front : boutique solidaire" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Module 5 · pages Back : produits, ajout produit, commandes" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Intégration du template Front Office Medilab en MVC (includes, navbar dynamique)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Création du dépôt GitHub (nom, description, topics, tuteurs invités)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Module commun · socle sécurité : Database singleton, bootstrap (session, expiration, en-têtes)" --body "Responsable : Rayen Poletti" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · authentification : inscription, connexion, déconnexion, CSRF" --body "Responsable : Aziz Kouki" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · rôles et contrôle d’accès (requireAuth, requireRole, pages 403)" --body "Responsable : Rayen Marzougi" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · CRUD utilisateurs Back Office (recherche, filtres, pagination, blocage)" --body "Responsable : Houssem Hafsi" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · Mon compte + Mon passeport (Front Office)" --body "Responsable : Karim Bousetta" --label module-commun --label done
gh issue create --repo "$REPO" --title "Contrôle de saisie : validation.js + Validator.php (module commun)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Structure MVC du workshop (Controllers, Models, Views/assets, Views/includes, config.php) dans les deux espaces" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Module 5 · CRUD Produit (Back Office) + boutique du Front Office lue en base" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Module 2 · Fiche médicale et antécédents (patient, médecin, consentement au partage)" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 1 · CRUD Lieu et Audit, validation des audits, points et score des lieux (FO + BO)" --body "Responsable : Rayen Poletti" --label module-1 --label done
gh issue create --repo "$REPO" --title "Module 2 · CRUD Professionnel et RendezVous, recherche selon le passeport, prise de rendez-vous (FO + BO)" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 3 · CRUD Programme et Séance, inscription et progression (FO + BO)" --body "Responsable : Rayen Marzougi" --label module-3 --label done
gh issue create --repo "$REPO" --title "Module 4 · Publications et commentaires, signalement et modération (FO + BO)" --body "Responsable : Houssem Hafsi" --label module-4 --label done
gh issue create --repo "$REPO" --title "Module 5 · Panier, commandes en dinars ou en points, suivi des commandes (FO + BO)" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Validation PHP + JavaScript de tous les formulaires" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Tableau de bord du Back Office branché sur la base" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Ajout des fichiers du template Medilab (assets/vendor)" --body "Responsable : Toute l’équipe" --label general --label in-progress
gh issue create --repo "$REPO" --title "Fonctionnalités IA : orientation et recommandations" --body "Responsable : Toute l’équipe" --label general
echo "Issues créées. Ajoutez-les au tableau Projects et fermez celles qui sont terminées."
