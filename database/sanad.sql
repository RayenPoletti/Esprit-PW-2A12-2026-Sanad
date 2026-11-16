-- Sanad · schéma MySQL (une table par classe du diagramme de classes)
CREATE DATABASE IF NOT EXISTS sanad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sanad;

-- Utilisateur · Module commun · toute l’équipe
CREATE TABLE utilisateur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150),
  prenom VARCHAR(150),
  email VARCHAR(150) NOT NULL UNIQUE,
  motDePasse VARCHAR(150),
  telephone VARCHAR(150),
  ville VARCHAR(150),
  role VARCHAR(150),
  points INT,
  dateInscription DATE
) ENGINE=InnoDB;

-- Passeport · Module commun · toute l’équipe
CREATE TABLE passeport (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fauteuilRoulant TINYINT(1),
  mobiliteReduite TINYINT(1),
  malvoyance TINYINT(1),
  surdite TINYINT(1),
  accompagneAidant TINYINT(1),
  precisions TEXT,
  partageAutorise TINYINT(1),
  dateMiseAJour DATE,
  utilisateurId INT NOT NULL UNIQUE,
  CONSTRAINT fk_passeport_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lieu · Module 1 · Rayen Poletti
CREATE TABLE lieu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150),
  adresse VARCHAR(150),
  ville VARCHAR(150),
  latitude DECIMAL(9,6),
  longitude DECIMAL(9,6),
  scoreAccessibilite DECIMAL(10,2)
) ENGINE=InnoDB;

-- Audit · Module 1 · Rayen Poletti
CREATE TABLE audit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateAudit DATE,
  rampe TINYINT(1),
  porteLarge TINYINT(1),
  ascenseur TINYINT(1),
  toilettesAdaptees TINYINT(1),
  parkingPMR TINYINT(1),
  accueilLSF TINYINT(1),
  commentaire TEXT,
  nbPhotos INT,
  statut VARCHAR(150),
  lieuId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_audit_lieu FOREIGN KEY (lieuId) REFERENCES lieu(id) ON DELETE CASCADE,
  CONSTRAINT fk_audit_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Professionnel · Module 2 · Aziz Kouki
CREATE TABLE professionnel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150),
  prenom VARCHAR(150),
  specialite VARCHAR(150),
  telephone VARCHAR(150),
  valide TINYINT(1),
  lieuId INT NOT NULL,
  CONSTRAINT fk_professionnel_lieu FOREIGN KEY (lieuId) REFERENCES lieu(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- RendezVous · Module 2 · Aziz Kouki
CREATE TABLE rendez_vous (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateHeure DATETIME,
  motif VARCHAR(150),
  statut VARCHAR(150),
  passeportTransmis TINYINT(1),
  professionnelId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_rendez_vous_professionnel FOREIGN KEY (professionnelId) REFERENCES professionnel(id) ON DELETE RESTRICT,
  CONSTRAINT fk_rendez_vous_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Programme · Module 3 · Rayen Marzougi
CREATE TABLE programme (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150),
  description TEXT,
  niveau VARCHAR(150),
  formats VARCHAR(150)
) ENGINE=InnoDB;

-- Seance · Module 3 · Rayen Marzougi
CREATE TABLE seance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(150),
  ordre INT,
  dureeMin INT,
  urlVideo VARCHAR(150),
  urlAudio VARCHAR(150),
  programmeId INT NOT NULL,
  CONSTRAINT fk_seance_programme FOREIGN KEY (programmeId) REFERENCES programme(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Publication · Module 4 · Houssem Hafsi
CREATE TABLE publication (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT,
  datePublication DATETIME,
  statut VARCHAR(150),
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_publication_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Commentaire · Module 4 · Houssem Hafsi
CREATE TABLE commentaire (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT,
  dateCommentaire DATETIME,
  statut VARCHAR(150),
  publicationId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_commentaire_publication FOREIGN KEY (publicationId) REFERENCES publication(id) ON DELETE CASCADE,
  CONSTRAINT fk_commentaire_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Produit · Module 5 · Karim Bousetta
CREATE TABLE produit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150),
  description TEXT,
  etat VARCHAR(150),
  prix DECIMAL(10,2),
  stock INT,
  poidsKg DECIMAL(10,2)
) ENGINE=InnoDB;

-- Commande · Module 5 · Karim Bousetta
CREATE TABLE commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateCommande DATETIME,
  montantTotal DECIMAL(10,2),
  pointsUtilises INT,
  statut VARCHAR(150),
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_commande_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Classe d'association Inscription (Utilisateur 0..* — 0..* Programme) · Module 3
CREATE TABLE inscription (
  utilisateurId INT NOT NULL,
  programmeId INT NOT NULL,
  dateInscription DATE,
  progression INT DEFAULT 0,
  PRIMARY KEY (utilisateurId, programmeId),
  CONSTRAINT fk_inscription_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE CASCADE,
  CONSTRAINT fk_inscription_programme FOREIGN KEY (programmeId) REFERENCES programme(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Classe d'association LigneCommande (Commande 0..* — 1..* Produit) · Module 5
CREATE TABLE ligneCommande (
  commandeId INT NOT NULL,
  produitId INT NOT NULL,
  quantite INT NOT NULL DEFAULT 1,
  prixUnitaire DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (commandeId, produitId),
  CONSTRAINT fk_ligne_commande FOREIGN KEY (commandeId) REFERENCES commande(id) ON DELETE CASCADE,
  CONSTRAINT fk_ligne_produit FOREIGN KEY (produitId) REFERENCES produit(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
