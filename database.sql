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
(1, 'Privilégier la marche ou le vélo', 'Laissez la voiture au garage pour vos trajets de moins de 5 km pendant une semaine', 1, 3, 20, 5.00, 1),
(2, 'Faire du vélo pendant 2 semaines', 'Ne plus utiliser la voiture pendant au moins 2 semaines', 2, 3, 50, 20.00, 1),
(3, 'Éviter l''avion et privilégier le train', 'Remplacez un trajet en avion par le train pour votre prochain déplacement', 3, 3, 100, 50.00, 1),
-- Logement (type_defi_id = 1)
(4, 'Débrancher vos appareils', 'Débranchez tous les appareils en veille', 1, 1, 25, 0.80, 1),
(5, 'Baisser le chauffage de 1°C', 'Réduisez la température de votre logement de 1°C pendant un mois', 2, 1, 40, 10.00, 1),
(6, 'Passer à l''énergie verte', 'Souscrivez à un fournisseur d''électricité 100% renouvelable', 3, 1, 100, 60.00, 1),
-- Alimentation (type_defi_id = 2)
(7, 'Un repas végétarien par semaine', 'Remplacez un repas avec viande par un repas végétarien chaque semaine', 1, 2, 25, 3.00, 1),
(8, 'Manger local et de saison', 'Privilégiez les produits locaux et de saison pendant 2 semaines', 2, 2, 40, 8.00, 1),
(9, 'Manger moins de viande', 'Ne mangez de la viande qu''une seule fois par semaine', 3, 2, 100, 15.00, 1),
-- Achats & numérique (type_defi_id = 4)
(10, 'Réduire la qualité du streaming', 'Passez votre streaming vidéo en qualité standard plutôt qu''en HD pendant une semaine', 1, 4, 20, 2.00, 1),
(11, 'Acheter un appareil reconditionné', 'Choisissez du reconditionné pour votre prochain achat électronique', 2, 4, 50, 30.00, 1),
(12, 'Zéro vêtement neuf pendant 1 mois', 'N''achetez aucun vêtement neuf pendant un mois (seconde main autorisée)', 3, 4, 80, 40.00, 1);

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
