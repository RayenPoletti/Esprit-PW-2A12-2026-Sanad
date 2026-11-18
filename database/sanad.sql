-- Sanad · schéma MySQL (une table par classe du diagramme de classes)
-- Import : phpMyAdmin > Importer > ce fichier. ATTENTION : la base « sanad » existante est supprimée puis recréée.
DROP DATABASE IF EXISTS sanad;
CREATE DATABASE IF NOT EXISTS sanad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sanad;

-- Utilisateur · Module commun · toute l’équipe
-- motDePasse : empreinte password_hash() (jamais le mot de passe en clair) → VARCHAR(255)
CREATE TABLE utilisateur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  motDePasse VARCHAR(255) NOT NULL,
  telephone VARCHAR(20) NOT NULL DEFAULT '',
  ville VARCHAR(50) NOT NULL DEFAULT '',
  role ENUM('membre','aidant','ambassadeur','professionnel','admin') NOT NULL DEFAULT 'membre',
  statut ENUM('actif','bloque') NOT NULL DEFAULT 'actif',
  points INT NOT NULL DEFAULT 0,
  dateInscription TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Passeport · Module commun · toute l’équipe (Utilisateur 1 — 0..1 Passeport : utilisateurId UNIQUE)
CREATE TABLE passeport (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fauteuilRoulant TINYINT(1) NOT NULL DEFAULT 0,
  mobiliteReduite TINYINT(1) NOT NULL DEFAULT 0,
  malvoyance TINYINT(1) NOT NULL DEFAULT 0,
  surdite TINYINT(1) NOT NULL DEFAULT 0,
  accompagneAidant TINYINT(1) NOT NULL DEFAULT 0,
  precisions TEXT,
  partageAutorise TINYINT(1) NOT NULL DEFAULT 0,
  dateMiseAJour TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  utilisateurId INT NOT NULL UNIQUE,
  CONSTRAINT fk_passeport_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Fiche médicale · une par patient (Utilisateur 1 — 0..1 FicheMedicale)
-- partageAutorise : le patient autorise les professionnels de santé à consulter ses antécédents
CREATE TABLE ficheMedicale (
  id INT AUTO_INCREMENT PRIMARY KEY,
  groupeSanguin VARCHAR(3) NOT NULL DEFAULT '',
  allergies TEXT,
  maladiesChroniques TEXT,
  traitementsEnCours TEXT,
  partageAutorise TINYINT(1) NOT NULL DEFAULT 0,
  dateMiseAJour TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  utilisateurId INT NOT NULL UNIQUE,
  CONSTRAINT fk_fiche_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Consultation · antécédent écrit par un professionnel (FicheMedicale 1 — 0..* Consultation)
-- professionnelId : compte « professionnel » auteur ; SET NULL garde l'historique si ce compte est supprimé
CREATE TABLE consultation (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateConsultation DATE NOT NULL,
  specialite VARCHAR(60) NOT NULL,
  motif VARCHAR(150) NOT NULL,
  diagnostic TEXT NOT NULL,
  traitement TEXT,
  observations TEXT,
  dateCreation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ficheId INT NOT NULL,
  professionnelId INT NULL,
  CONSTRAINT fk_consultation_fiche FOREIGN KEY (ficheId) REFERENCES ficheMedicale(id) ON DELETE CASCADE,
  CONSTRAINT fk_consultation_professionnel FOREIGN KEY (professionnelId) REFERENCES utilisateur(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Lieu · Module 1 · Rayen Poletti
-- scoreAccessibilite (sur 5) est recalculé à partir des audits validés
CREATE TABLE lieu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  adresse VARCHAR(200) NOT NULL,
  ville VARCHAR(50) NOT NULL,
  latitude DECIMAL(9,6) NULL,
  longitude DECIMAL(9,6) NULL,
  scoreAccessibilite DECIMAL(3,1) NULL
) ENGINE=InnoDB;

-- Audit · Module 1 · Rayen Poletti (Lieu 1 — 0..* Audit, réalisé par un ambassadeur)
CREATE TABLE audit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateAudit DATE NOT NULL,
  rampe TINYINT(1) NOT NULL DEFAULT 0,
  porteLarge TINYINT(1) NOT NULL DEFAULT 0,
  ascenseur TINYINT(1) NOT NULL DEFAULT 0,
  toilettesAdaptees TINYINT(1) NOT NULL DEFAULT 0,
  parkingPMR TINYINT(1) NOT NULL DEFAULT 0,
  accueilLSF TINYINT(1) NOT NULL DEFAULT 0,
  commentaire TEXT NOT NULL,
  nbPhotos INT NOT NULL DEFAULT 0,
  statut ENUM('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  lieuId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_audit_lieu FOREIGN KEY (lieuId) REFERENCES lieu(id) ON DELETE CASCADE,
  CONSTRAINT fk_audit_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Professionnel · Module 2 · Aziz Kouki (exerce dans un Lieu)
CREATE TABLE professionnel (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  specialite VARCHAR(50) NOT NULL,
  telephone VARCHAR(20) NOT NULL,
  valide TINYINT(1) NOT NULL DEFAULT 0,
  lieuId INT NOT NULL,
  CONSTRAINT fk_professionnel_lieu FOREIGN KEY (lieuId) REFERENCES lieu(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- RendezVous · Module 2 · Aziz Kouki (Professionnel 1 — 0..* RendezVous, pris par un Utilisateur)
CREATE TABLE rendez_vous (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateHeure DATETIME NOT NULL,
  motif VARCHAR(150) NOT NULL,
  statut ENUM('en_attente','confirme','annule') NOT NULL DEFAULT 'en_attente',
  passeportTransmis TINYINT(1) NOT NULL DEFAULT 0,
  professionnelId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_rendez_vous_professionnel FOREIGN KEY (professionnelId) REFERENCES professionnel(id) ON DELETE RESTRICT,
  CONSTRAINT fk_rendez_vous_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Programme · Module 3 · Rayen Marzougi
-- formats : liste séparée par des virgules (video,audio,facile)
CREATE TABLE programme (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  niveau ENUM('Débutant','Intermédiaire','Tous niveaux') NOT NULL DEFAULT 'Tous niveaux',
  formats VARCHAR(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB;

-- Seance · Module 3 · Rayen Marzougi (Programme 1 — 1..* Seance, composition)
CREATE TABLE seance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  ordre INT NOT NULL DEFAULT 1,
  dureeMin INT NOT NULL,
  urlVideo VARCHAR(255) NOT NULL DEFAULT '',
  urlAudio VARCHAR(255) NOT NULL DEFAULT '',
  programmeId INT NOT NULL,
  CONSTRAINT fk_seance_programme FOREIGN KEY (programmeId) REFERENCES programme(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Publication · Module 4 · Houssem Hafsi
CREATE TABLE publication (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT NOT NULL,
  datePublication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  statut ENUM('publie','signale','masque') NOT NULL DEFAULT 'publie',
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_publication_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Commentaire · Module 4 · Houssem Hafsi (Publication 1 — 0..* Commentaire, composition)
CREATE TABLE commentaire (
  id INT AUTO_INCREMENT PRIMARY KEY,
  contenu TEXT NOT NULL,
  dateCommentaire DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  statut ENUM('publie','signale','masque') NOT NULL DEFAULT 'publie',
  publicationId INT NOT NULL,
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_commentaire_publication FOREIGN KEY (publicationId) REFERENCES publication(id) ON DELETE CASCADE,
  CONSTRAINT fk_commentaire_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Produit · Module 5 · Karim Bousetta
CREATE TABLE produit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  etat ENUM('Reconditionné','Don') NOT NULL DEFAULT 'Reconditionné',
  prix DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  poidsKg DECIMAL(10,2) NULL
) ENGINE=InnoDB;

-- Commande · Module 5 · Karim Bousetta
CREATE TABLE commande (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateCommande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  montantTotal DECIMAL(10,2) NOT NULL DEFAULT 0,
  pointsUtilises INT NOT NULL DEFAULT 0,
  statut ENUM('en_attente','preparation','livree','annulee') NOT NULL DEFAULT 'en_attente',
  utilisateurId INT NOT NULL,
  CONSTRAINT fk_commande_utilisateur FOREIGN KEY (utilisateurId) REFERENCES utilisateur(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Classe d'association Inscription (Utilisateur 0..* — 0..* Programme) · Module 3
CREATE TABLE inscription (
  utilisateurId INT NOT NULL,
  programmeId INT NOT NULL,
  dateInscription DATE NOT NULL,
  progression INT NOT NULL DEFAULT 0,
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

-- =====================================================================
-- Comptes de démonstration (mots de passe hachés avec password_hash)
--   admin@sanad.tn        / Admin@1234   → rôle admin  (Back Office)
--   les autres comptes    / Membre@1234  → Front Office
-- =====================================================================
INSERT INTO utilisateur (nom, prenom, email, motDePasse, telephone, ville, role, statut, points) VALUES
('Sanad', 'Admin', 'admin@sanad.tn', '$2y$10$5EKE9np7FqiQQLAjJzGc6eqLIbrYlOL12FZfCHmmScJALPtG5EqMu', '+216 71 000 000', 'Tunis', 'admin', 'actif', 0),
('Ben Ali', 'Amira', 'amira@exemple.tn', '$2y$10$DbLbBcoStQ4WZuFu7FA3wee9OKs14veYxueyEO6Ec/H8m5YMdLgN2', '+216 20 123 456', 'Tunis', 'ambassadeur', 'actif', 120),
('Mejri', 'Hédi', 'hedi@exemple.tn', '$2y$10$DbLbBcoStQ4WZuFu7FA3wee9OKs14veYxueyEO6Ec/H8m5YMdLgN2', '+216 22 456 789', 'Béja', 'membre', 'actif', 60),
('Kefi', 'Salma', 'salma@exemple.tn', '$2y$10$DbLbBcoStQ4WZuFu7FA3wee9OKs14veYxueyEO6Ec/H8m5YMdLgN2', '', 'Tunis', 'aidant', 'actif', 20),
('Ben Salah', 'Leila', 'leila@exemple.tn', '$2y$10$DbLbBcoStQ4WZuFu7FA3wee9OKs14veYxueyEO6Ec/H8m5YMdLgN2', '+216 98 765 432', 'Tunis', 'professionnel', 'actif', 0),
('Toumi', 'Mourad', 'mourad@exemple.tn', '$2y$10$DbLbBcoStQ4WZuFu7FA3wee9OKs14veYxueyEO6Ec/H8m5YMdLgN2', '', 'Sfax', 'membre', 'bloque', 0);

INSERT INTO passeport (utilisateurId, fauteuilRoulant, mobiliteReduite, malvoyance, surdite, accompagneAidant, precisions, partageAutorise) VALUES
(2, 1, 0, 0, 0, 1, 'Fauteuil manuel de 68 cm de large. Ma sœur m’accompagne.', 1),
(3, 0, 1, 0, 0, 0, 'Je ne peux pas monter plus de 3 marches.', 1);

-- Produits de la boutique solidaire (module 5)
INSERT INTO produit (nom, description, etat, prix, stock, poidsKg) VALUES
('Fauteuil roulant pliant', 'Fauteuil manuel révisé, largeur d’assise 45 cm, freins neufs. Retrait à Tunis.', 'Reconditionné', 350, 4, 18),
('Déambulateur 4 roues', 'Déambulateur avec siège et panier, hauteur réglable.', 'Reconditionné', 120, 6, 8),
('Siège de douche pliant', 'Siège mural rabattable, offert par un membre. Réservé aux membres.', 'Don', 0, 3, 4),
('Loupe électronique', 'Écran 5 pouces, zoom x2 à x22, contrastes réglables. Idéale pour la malvoyance.', 'Reconditionné', 90, 5, 1),
('Lit médicalisé', 'Lit électrique 3 fonctions avec barrières. Livraison à organiser avec le donateur.', 'Don', 0, 1, 65),
('Coussin anti-escarres', 'Coussin à mémoire de forme, housse lavable neuve.', 'Reconditionné', 45, 10, 2);

-- Fiches médicales de démonstration (Leila Ben Salah, id 5, est le compte « professionnel »)
INSERT INTO ficheMedicale (utilisateurId, groupeSanguin, allergies, maladiesChroniques, traitementsEnCours, partageAutorise) VALUES
(2, 'O+', 'Pénicilline', 'Paraplégie (lésion médullaire D10)', 'Baclofène 10 mg matin et soir', 1),
(3, 'A-', '', 'Hypertension artérielle', 'Amlodipine 5 mg', 0);

INSERT INTO consultation (ficheId, professionnelId, dateConsultation, specialite, motif, diagnostic, traitement, observations) VALUES
(1, 5, '2026-10-02', 'Cardiologie', 'Palpitations à l’effort', 'ECG normal, tachycardie sinusale isolée.', 'Pas de traitement. Hydratation.', 'Revoir si les symptômes reviennent. Accès au cabinet par la rampe latérale.'),
(1, 5, '2026-11-05', 'Cardiologie', 'Contrôle', 'Bilan rassurant, tension 12/8.', '', 'Contrôle dans un an.');

-- =====================================================================
-- Données de démonstration des modules 1 à 5
-- (utilisateurs : 1 admin, 2 Amira ambassadrice, 3 Hédi, 4 Salma, 5 Leila professionnelle, 6 Mourad bloqué)
-- =====================================================================
INSERT INTO lieu (nom, adresse, ville, latitude, longitude, scoreAccessibilite) VALUES
('Cabinet Ben Salah', '12 rue de Marseille', 'Tunis', 36.800800, 10.180000, 4.2),
('Clinique El Menzah', 'Avenue Taieb Mhiri, El Menzah 6', 'Tunis', 36.842000, 10.170000, 5.0),
('Cabinet Gharbi', '5 avenue Habib Bourguiba', 'Béja', 36.725600, 9.181700, 2.5),
('Centre Jaziri', '20 rue Ibn Khaldoun', 'Sousse', 35.825600, 10.636000, NULL),
('Cabinet Chaouch', '8 rue de Rome', 'Bizerte', 37.274400, 9.873900, NULL);

INSERT INTO audit (lieuId, utilisateurId, dateAudit, rampe, porteLarge, ascenseur, toilettesAdaptees, parkingPMR, accueilLSF, commentaire, statut) VALUES
(1, 2, '2026-10-12', 1, 1, 1, 1, 1, 0, 'Rampe à l’entrée, ascenseur large, toilettes adaptées au 1er étage. Pas d’accueil en langue des signes.', 'valide'),
(2, 2, '2026-10-20', 1, 1, 1, 1, 1, 1, 'Clinique entièrement accessible, personnel formé à la langue des signes.', 'valide'),
(3, 2, '2026-11-02', 1, 1, 0, 1, 0, 0, 'Cabinet au rez-de-chaussée mais pas de place de parking réservée.', 'valide'),
(4, 2, '2026-11-15', 1, 0, 0, 0, 1, 0, 'Porte trop étroite pour un fauteuil (70 cm).', 'en_attente');

INSERT INTO professionnel (nom, prenom, specialite, telephone, valide, lieuId) VALUES
('Ben Salah', 'Leila', 'Cardiologie', '+216 71 100 200', 1, 1),
('Trabelsi', 'Mehdi', 'Dermatologie', '+216 71 300 400', 1, 2),
('Gharbi', 'Sonia', 'Médecine générale', '+216 78 450 120', 1, 3),
('Jaziri', 'Karim', 'Kinésithérapie', '+216 73 220 330', 1, 4),
('Chaouch', 'Amel', 'Ophtalmologie', '+216 72 440 550', 0, 5);

INSERT INTO rendez_vous (dateHeure, motif, statut, passeportTransmis, professionnelId, utilisateurId) VALUES
('2026-12-03 10:00:00', 'Contrôle annuel', 'confirme', 1, 1, 2),
('2026-12-10 15:30:00', 'Douleurs au genou', 'en_attente', 1, 4, 3);

INSERT INTO programme (titre, description, niveau, formats) VALUES
('Yoga sur chaise', 'Des mouvements doux, assis, pour gagner en souplesse sans risque de chute.', 'Débutant', 'video,audio,facile'),
('Gestion du stress', 'Respiration, relaxation et petites habitudes pour mieux vivre la maladie au quotidien.', 'Tous niveaux', 'audio,facile'),
('Marche douce seniors', 'Un programme progressif pour reprendre la marche en sécurité.', 'Débutant', 'video,facile');

INSERT INTO seance (programmeId, titre, ordre, dureeMin, urlVideo, urlAudio) VALUES
(1, 'Réveil des épaules', 1, 10, 'https://www.youtube.com/watch?v=exemple1', ''),
(1, 'Dos et nuque', 2, 12, 'https://www.youtube.com/watch?v=exemple2', ''),
(1, 'Respiration et détente', 3, 8, '', 'https://exemple.tn/audio/respiration.mp3'),
(2, 'Respirer en carré', 1, 6, '', 'https://exemple.tn/audio/carre.mp3'),
(2, 'Scanner corporel', 2, 15, '', 'https://exemple.tn/audio/scanner.mp3'),
(3, 'Premiers pas', 1, 15, 'https://www.youtube.com/watch?v=exemple3', '');

INSERT INTO inscription (utilisateurId, programmeId, dateInscription, progression) VALUES
(2, 1, '2026-11-01', 2), (3, 2, '2026-11-05', 1);

INSERT INTO publication (contenu, datePublication, statut, utilisateurId) VALUES
('Quel kinésithérapeute accessible recommandez-vous à Sousse ? Je suis en fauteuil.', '2026-11-10 09:15:00', 'publie', 3),
('Merci à l’équipe de la Clinique El Menzah : accueil en langue des signes parfait pour mon fils.', '2026-11-12 18:40:00', 'publie', 4),
('Vends des médicaments pas chers, contactez-moi en privé.', '2026-11-14 22:05:00', 'signale', 6);

INSERT INTO commentaire (contenu, dateCommentaire, statut, publicationId, utilisateurId) VALUES
('Le Centre Jaziri a une rampe, mais la porte est étroite. Un audit est en cours.', '2026-11-10 11:00:00', 'publie', 1, 2),
('Très contente de lire ça !', '2026-11-13 08:30:00', 'publie', 2, 2);

INSERT INTO commande (dateCommande, montantTotal, pointsUtilises, statut, utilisateurId) VALUES
('2026-11-08 14:20:00', 45.00, 0, 'livree', 3),
('2026-11-16 10:05:00', 0.00, 900, 'en_attente', 2);

INSERT INTO ligneCommande (commandeId, produitId, quantite, prixUnitaire) VALUES
(1, 6, 1, 45.00),
(2, 4, 1, 90.00);
