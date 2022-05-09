-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 09 mai 2022 à 15:10
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `taskmanager`
--

-- --------------------------------------------------------

--
-- Structure de la table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE IF NOT EXISTS `lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lists`
--

INSERT INTO `lists` (`id`, `name`) VALUES
(56, 'List 3'),
(55, 'List 2'),
(54, 'List 1');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `listID` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` int DEFAULT '0',
  `description` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `last_modification_date` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `listID`, `name`, `status`, `description`, `start_date`, `end_date`, `last_modification_date`, `created_date`) VALUES
(134, 54, 'task 2', 2, '', '0000-00-00', '0000-00-00', '2022-05-09 15:09:26', '2022-05-09 15:09:26'),
(133, 54, 'task 1', 1, '', '0000-00-00', '0000-00-00', '2022-05-09 15:09:21', '2022-05-09 15:09:21'),
(132, 55, 'task 1', 2, '', '0000-00-00', '0000-00-00', '2022-05-09 15:09:15', '2022-05-09 15:09:15'),
(129, 56, 'task completed', 2, 'Some description', '2022-05-17', '2022-05-25', '2022-05-09 15:08:42', '2022-05-09 15:08:42'),
(130, 56, 'task created', 0, '', '0000-00-00', '0000-00-00', '2022-05-09 15:08:57', '2022-05-09 15:08:57'),
(131, 56, 'task in progress', 1, '', '0000-00-00', '0000-00-00', '2022-05-09 15:09:08', '2022-05-09 15:09:08');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
