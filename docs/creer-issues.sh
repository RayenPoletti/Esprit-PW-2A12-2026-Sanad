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
gh issue create --repo "$REPO" --title "Création du dépôt GitHub (nom, description, topics, tuteurs invités)" --body "Responsable : Toute l’équipe" --label general
gh issue create --repo "$REPO" --title "README structuré selon le guide Esprit" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Intégration du template Back Office TailAdmin en MVC (partials, layout, Controller, routes)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Intégration du template Front Office Medilab en MVC (includes, navbar dynamique)" --body "Responsable : Toute l’équipe" --label general --label in-progress
gh issue create --repo "$REPO" --title "Schéma de la base de données MySQL (database/sanad.sql)" --body "Responsable : Toute l’équipe" --label general --label done
gh issue create --repo "$REPO" --title "Module commun · pages Front : inscription, connexion, passeport" --body "Responsable : Toute l’équipe" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · pages Back : utilisateurs, ajout, passeports" --body "Responsable : Toute l’équipe" --label module-commun --label done
gh issue create --repo "$REPO" --title "Module commun · CRUD Utilisateur et Passeport (PDO)" --body "Responsable : Toute l’équipe" --label module-commun
gh issue create --repo "$REPO" --title "Module commun · authentification et sessions" --body "Responsable : Toute l’équipe" --label module-commun
gh issue create --repo "$REPO" --title "Module 1 · pages Front : formulaire d’audit, mes audits" --body "Responsable : Rayen Poletti" --label module-1 --label done
gh issue create --repo "$REPO" --title "Module 1 · pages Back : lieux, ajout lieu, audits, ajout audit" --body "Responsable : Rayen Poletti" --label module-1 --label done
gh issue create --repo "$REPO" --title "Module 1 · CRUD Lieu et Audit + calcul du score (jointure)" --body "Responsable : Rayen Poletti" --label module-1
gh issue create --repo "$REPO" --title "Module 2 · pages Front : professionnels, prise de rendez-vous" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 2 · pages Back : professionnels, rendez-vous, formulaires" --body "Responsable : Aziz Kouki" --label module-2 --label done
gh issue create --repo "$REPO" --title "Module 2 · CRUD Professionnel et RendezVous (jointure)" --body "Responsable : Aziz Kouki" --label module-2
gh issue create --repo "$REPO" --title "Module 3 · pages Front : programmes" --body "Responsable : Rayen Marzougi" --label module-3 --label done
gh issue create --repo "$REPO" --title "Module 3 · pages Back : programmes, séances, formulaires" --body "Responsable : Rayen Marzougi" --label module-3 --label done
gh issue create --repo "$REPO" --title "Module 3 · CRUD Programme et Séance (jointure)" --body "Responsable : Rayen Marzougi" --label module-3
gh issue create --repo "$REPO" --title "Module 4 · pages Front : communauté (publier, commenter, signaler)" --body "Responsable : Houssem Hafsi" --label module-4 --label done
gh issue create --repo "$REPO" --title "Module 4 · pages Back : publications, commentaires, modération" --body "Responsable : Houssem Hafsi" --label module-4 --label done
gh issue create --repo "$REPO" --title "Module 4 · CRUD Publication et Commentaire (jointure)" --body "Responsable : Houssem Hafsi" --label module-4
gh issue create --repo "$REPO" --title "Module 5 · pages Front : boutique solidaire" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Module 5 · pages Back : produits, ajout produit, commandes" --body "Responsable : Karim Bousetta" --label module-5 --label done
gh issue create --repo "$REPO" --title "Module 5 · CRUD Produit et Commande + LigneCommande (jointure)" --body "Responsable : Karim Bousetta" --label module-5
gh issue create --repo "$REPO" --title "Contrôle de saisie des formulaires (PHP et JavaScript)" --body "Responsable : Toute l’équipe" --label general
gh issue create --repo "$REPO" --title "Fonctionnalités IA : orientation et recommandations" --body "Responsable : Toute l’équipe" --label general
echo "Issues créées. Ajoutez-les au tableau Projects et fermez celles qui sont terminées."
