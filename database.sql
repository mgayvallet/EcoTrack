-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 juin 2026 à 09:06
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecotrack`
--

DROP DATABASE IF EXISTS `ecotrack`;
CREATE DATABASE `ecotrack` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecotrack`;

-- --------------------------------------------------------

--
-- Structure de la table `defis`
--

DROP TABLE IF EXISTS `defis`;
CREATE TABLE IF NOT EXISTS `defis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulte_id` int NOT NULL,
  `type_defi_id` int NOT NULL,
  `points` int NOT NULL DEFAULT '10',
  `co2_economise` decimal(8,2) NOT NULL DEFAULT '0.00',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `difficulte_id` (`difficulte_id`),
  KEY `type_defi_id` (`type_defi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `defis`
-- Un défi par catégorie du QCM (transport, logement, alimentation, numerique)
-- et par niveau de difficulté, afin de proposer le défi adapté au poste
-- le plus émetteur de l'empreinte calculée.
--

INSERT INTO `defis` (`id`, `titre`, `description`, `difficulte_id`, `type_defi_id`, `points`, `co2_economise`, `actif`) VALUES

-- Transport (type_defi_id = 3)

(1, 'Trajet à pied ou à vélo', 'Effectuez au moins un trajet de moins de 5 km à pied ou à vélo aujourd\'hui', 1, 3, 20, 1.20, 1),
(2, 'Journée sans voiture', 'N\'utilisez pas votre voiture de toute la journée', 2, 3, 50, 3.50, 1),
(3, 'Zéro avion aujourd\'hui', 'Choisissez le train ou visioconférence plutôt que l\'avion pour tout déplacement prévu ce jour', 3, 3, 100, 50.00, 1),
(4, 'Transport en commun', 'Prends au moins un transport en commun aujourd\'hui', 2, 3, 50, 2.10, 1),
(5, 'Consommation organisée', 'Planifie tes trajets à l\'avance pour les optimiser', 1, 3, 20, 0.60, 1),
(6, 'Télétravail', 'Télétravaille aujourd\'hui', 3, 3, 100, 3.50, 1),

-- Logement (type_defi_id = 1)

(7, 'Débrancher les appareils en veille', 'Débranchez tous vos appareils en veille dès ce matin', 1, 1, 25, 0.10, 1),
(8, 'Baisser le chauffage d\'1°C aujourd\'hui', 'Réduisez la température de votre logement d\'1°C pour toute la journée', 2, 1, 40, 0.40, 1),
(9, 'Renseignez-vous sur l\'énergie verte', 'Comparez et choisissez un fournisseur d\'électricité 100% renouvelable aujourd\'hui', 3, 1, 100, 0.05, 1),
(10, 'Rapide efficace', 'Prends une douche de moins de 5 minutes', 2, 1, 50, 0.30, 1),
(11, '2 en 1', 'Utilise l\'eau non potable (comme l\'eau de rinçage des légumes) pour arroser les plantes', 3, 1, 100, 0.05, 1),
(12, 'Éteins les lumières inutiles', 'Ne laisse pas de lumière allumée si il fait jour ou si tu sors d\'une pièce', 1, 1, 20, 0.08, 1),

-- Alimentation (type_defi_id = 2)

(13, 'Un repas végétarien aujourd\'hui', 'Préparez et mangez un repas entièrement végétarien ce jour', 1, 2, 25, 1.50, 1),
(14, 'Manger local aujourd\'hui', 'Achetez uniquement des produits locaux et de saison pour vos repas du jour', 2, 2, 40, 0.80, 1),
(15, 'Journée sans viande', 'Ne consommez aucun produit carné de toute la journée', 3, 2, 100, 4.50, 1),
(16, 'Cuisine les restes', 'Utilise les restes pour tous tes repas aujourd\'hui', 2, 2, 50, 1.20, 1),
(17, 'Prévois ta liste de courses', 'Fais une liste de courses pour éviter d\'acheter ce dont tu n\'as pas besoin', 1, 2, 20, 0.50, 1),
(18, 'Fait maison', 'Fais TOUS tes plats de la journée maison', 3, 2, 100, 2.00, 1),

-- Achats & numérique (type_defi_id = 4)

(19, 'Streaming en qualité standard', 'Passez votre streaming en qualité SD plutôt qu\'en HD pour toute la journée', 1, 4, 20, 0.04, 1),
(20, 'Rechercher un appareil reconditionné', 'Identifiez et comparez des offres reconditionnées pour votre prochain achat électronique', 2, 4, 50, 0.05, 1),
(21, 'Zéro achat vestimentaire aujourd\'hui', 'Ne commandez et n\'achetez aucun vêtement neuf de toute la journée', 3, 4, 80, 0.05, 1),
(22, 'Supprime les mails inutiles', '1000 mails supprimés correspondent à 10g de CO2 !', 1, 4, 10, 0.01, 1),
(23, 'Bob le bricoleur', 'Répare un objet cassé plutôt que de le jeter', 3, 4, 100, 2.50, 1),
(24, 'Tout le monde dort, tes appareils aussi', 'Active le mode sombre sur tous tes appareils et éteins-les complètement avant de dormir', 2, 4, 50, 0.15, 1);

-- --------------------------------------------------------

--
-- Structure de la table `difficultes`
--

DROP TABLE IF EXISTS `difficultes`;
CREATE TABLE IF NOT EXISTS `difficultes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_difficultes_libelle` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `difficultes`
--

INSERT INTO `difficultes` (`id`, `libelle`) VALUES
(3, 'difficile'),
(1, 'facile'),
(2, 'intermediaire');

-- --------------------------------------------------------

--
-- Structure de la table `empreintes`
--

DROP TABLE IF EXISTS `empreintes`;
CREATE TABLE IF NOT EXISTS `empreintes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `empreinte_carbone` int NOT NULL,
  `empreinte_transport` int NOT NULL,
  `empreinte_logement` int NOT NULL,
  `empreinte_alimentation` int NOT NULL,
  `empreinte_achat_numerique` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `defis_completes`
-- Suit les défis validés par chaque utilisateur (points, CO2, série).
--

DROP TABLE IF EXISTS `defis_completes`;
CREATE TABLE IF NOT EXISTS `defis_completes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `defi_id` int NOT NULL,
  `completed_on` date NOT NULL,
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_defi_jour` (`user_id`,`defi_id`,`completed_on`),
  KEY `defi_id` (`defi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie` enum('transport','logement','alimentation','achats_numerique') COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aide` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('slider','choix') COLLATE utf8mb4_unicode_ci NOT NULL,
  `valeur_min` float DEFAULT NULL,
  `valeur_max` float DEFAULT NULL,
  `valeur_defaut` float DEFAULT NULL,
  `pas` float DEFAULT '1',
  `unite` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordre` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_questions_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `categorie`, `code`, `libelle`, `aide`, `type`, `valeur_min`, `valeur_max`, `valeur_defaut`, `pas`, `unite`, `ordre`) VALUES
(1, 'transport', 'km_voiture_par_an', 'Combien de km faites-vous en voiture par an ?', NULL, 'slider', 0, 40000, 12000, 100, 'km', 1),
(2, 'transport', 'type_vehicule', 'Quel type de vehicule conduisez-vous ?', NULL, 'choix', NULL, NULL, NULL, NULL, NULL, 2),
(3, 'transport', 'vols_courts_par_an', 'Combien de vols courts faites-vous par an ?', 'Moins de 2h, ex. Paris-Lyon', 'slider', 0, 20, 0, 1, 'vols', 3),
(4, 'transport', 'vols_longs_par_an', 'Combien de vols longs faites-vous par an ?', 'Plus de 6h, intercontinental', 'slider', 0, 10, 0, 1, 'vols', 4),
(5, 'transport', 'km_train_par_an', 'Combien de km faites-vous en train par an ?', NULL, 'slider', 0, 30000, 0, 100, 'km', 5),
(6, 'logement', 'surface_m2', 'Quelle est la surface de votre logement ?', NULL, 'slider', 10, 250, 70, 1, 'm2', 6),
(7, 'logement', 'mode_chauffage', 'Quel est votre mode de chauffage principal ?', NULL, 'choix', NULL, NULL, NULL, NULL, NULL, 7),
(8, 'logement', 'conso_electrique_kwh', 'Quelle est votre consommation electrique annuelle ?', 'Moyenne francaise : 4 500 kWh', 'slider', 500, 20000, 4500, 100, 'kWh', 8),
(9, 'alimentation', 'regime_alimentaire', 'Quelle est votre alimentation principale ?', NULL, 'choix', NULL, NULL, NULL, NULL, NULL, 9),
(10, 'alimentation', 'part_locale_saison', 'Quelle part de votre alimentation est locale et de saison ?', NULL, 'choix', NULL, NULL, NULL, NULL, NULL, 10),
(11, 'achats_numerique', 'vetements_neufs_par_an', 'Combien d\'articles vestimentaires neufs achetez-vous par an ?', 'Vetements, chaussures', 'slider', 0, 80, 0, 1, 'articles', 11),
(12, 'achats_numerique', 'appareils_electroniques_par_an', 'Combien d\'appareils electroniques neufs achetez-vous par an ?', 'Telephone, ordi, TV...', 'slider', 0, 10, 0, 1, 'appareils', 12),
(13, 'achats_numerique', 'heures_streaming_par_jour', 'Combien d\'heures par jour passez-vous sur du streaming ou internet ?', NULL, 'slider', 0, 12, 0, 0.5, 'h/jour', 13);

-- --------------------------------------------------------

--
-- Structure de la table `question_options`
--

DROP TABLE IF EXISTS `question_options`;
CREATE TABLE IF NOT EXISTS `question_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `valeur` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_option` (`question_id`,`valeur`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `valeur`, `libelle`, `ordre`) VALUES
(1, 2, 'electrique', 'Electrique', 1),
(2, 2, 'hybride', 'Hybride', 2),
(3, 2, 'essence_diesel_moyen', 'Essence-diesel moyen', 3),
(4, 2, 'gros_suv', 'Gros SUV', 4),
(5, 7, 'pompe_a_chaleur', 'Pompe a chaleur', 1),
(6, 7, 'fioul_ou_gaz', 'Fioul ou gaz', 2),
(7, 7, 'electrique_resistif', 'Electrique resistif', 3),
(8, 7, 'bois', 'Bois', 4),
(9, 9, 'vegan', 'Vegan', 1),
(10, 9, 'vegetarien', 'Vegetarien', 2),
(11, 9, 'peu_de_viande', 'Peu de viande', 3),
(12, 9, 'omnivore_moyen', 'Omnivore moyen', 4),
(13, 9, 'gros_consommateur_viande', 'Gros consommateur de viande', 5),
(14, 10, '100', 'Quasiment tout, 100%', 1),
(15, 10, '75', 'En majorité, 75%', 2),
(16, 10, '50', 'Environ la moitié, 50%', 3),
(17, 10, '25', 'Pas tellement, 25%', 4),
(18, 10, '0', 'Quasiment rien 0%', 5);

-- --------------------------------------------------------

--
-- Structure de la table `types_defi`
--

DROP TABLE IF EXISTS `types_defi`;
CREATE TABLE IF NOT EXISTS `types_defi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_types_defi_libelle` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_defi`
--

INSERT INTO `types_defi` (`id`, `libelle`) VALUES
(1, 'logement'),
(2, 'nourriture'),
(3, 'transport'),
(4, 'numerique');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `defis`
--
ALTER TABLE `defis`
  ADD CONSTRAINT `defis_ibfk_1` FOREIGN KEY (`difficulte_id`) REFERENCES `difficultes` (`id`),
  ADD CONSTRAINT `defis_ibfk_2` FOREIGN KEY (`type_defi_id`) REFERENCES `types_defi` (`id`);

--
-- Contraintes pour la table `defis_completes`
--
ALTER TABLE `defis_completes`
  ADD CONSTRAINT `defis_completes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `defis_completes_ibfk_2` FOREIGN KEY (`defi_id`) REFERENCES `defis` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `empreintes`
--
ALTER TABLE `empreintes`
  ADD CONSTRAINT `empreintes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
