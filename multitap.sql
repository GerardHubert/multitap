-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  lun. 14 déc. 2020 à 00:50
-- Version du serveur :  8.0.18
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `multitap`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `commentId` tinyint(11) NOT NULL AUTO_INCREMENT,
  `reviewId` tinyint(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `thumbsUp` int(11) NOT NULL,
  `thumbsDown` int(11) NOT NULL,
  `commentDate` datetime NOT NULL,
  `commentStatus` int(5) NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `cascade` (`reviewId`),
  KEY `fk_user` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`commentId`, `reviewId`, `userId`, `content`, `thumbsUp`, `thumbsDown`, `commentDate`, `commentStatus`) VALUES
(115, 37, 12, 'on essaie de laisser un commentaire ici', 0, 0, '2020-12-12 00:45:02', 0),
(116, 43, 7, 'un commentaire du user 01 ici', 0, 0, '2020-12-13 02:03:28', 0);

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `reviewId` tinyint(11) NOT NULL AUTO_INCREMENT,
  `reviewTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gameTitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apiGameId` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `userId` int(11) NOT NULL,
  `reviewStatus` int(11) NOT NULL,
  `reviewDate` datetime NOT NULL,
  PRIMARY KEY (`reviewId`),
  KEY `fk_user_id` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`reviewId`, `reviewTitle`, `gameTitle`, `apiGameId`, `content`, `userId`, `reviewStatus`, `reviewDate`) VALUES
(36, 'La playstation 5 en a sous le capot', 'Marvel&#39;s Spider-Man: Miles Morales', 452634, '&#60;p&#62;&#60;strong&#62;C&#39;est parti pour le multiverse !&#60;/strong&#62; r&#38;eacute;vision 1&#60;/p&#62;', 6, 0, '2020-12-10 19:20:05'),
(37, 'Le soulèvement des machines', 'Horizon Zero Dawn', 278, '&#60;p&#62;&#60;strong&#62;Un univers que james cameron n&#39;aurait pas reni&#38;eacute;&#60;/strong&#62; ! O&#38;ugrave; les machines dominent le monde !!! Mais sans&#38;nbsp; Sarah Connor... ni le T800 !&#60;/p&#62;', 6, 0, '2020-12-10 23:46:05'),
(38, 'Le meilleur rogue-like de tous les temps', 'Dead Cells', 11726, '&#60;p&#62;Prison Break! Ou la l&#39;&#38;eacute;chapp&#38;eacute;e belle !&#60;/p&#62;', 12, 0, '2020-12-10 23:59:32'),
(39, 'Le prochain FPS de Bungie', 'Halo: Reach', 28613, '&#60;p&#62;N&#39;a pas fait forte impression lors des premi&#38;egrave;res vid&#38;eacute;os de gameplay... Heureusement qu&#39;il y a encore un peu de temps avant sa sortie.&#60;/p&#62;', 6, 0, '2020-12-10 23:49:08'),
(41, 'Voyage en mythologie greque', 'Assassin&#39;s Creed Odyssey', 58616, '&#60;p&#62;Et rencontre avec Herodote, P&#38;eacute;ricl&#38;egrave;s, et autres figures historiques de la Gr&#38;egrave;ce antique ! En compagnie de Kassadra ou d&#39;Alexios!&#60;/p&#62;', 12, 0, '2020-12-11 01:08:32'),
(43, 'Du remake, du vrai, du bon !', 'Crash Bandicoot N. Sane Trilogy', 34, '&#60;p&#62;Comme on aimerait en voir plus souvent !&#60;/p&#62;', 14, 0, '2020-12-11 17:01:11'),
(44, 'La physique des éléments', 'Dead or Alive Xtreme 3: Scarlet', 304898, '&#60;p&#62;Voici le meilleur moteur physique de tous les temps !! Ninja theory: les rois de la mod&#38;eacute;lisation ! Et du fameux effet &#34;boing-boing&#34; !&#60;/p&#62;', 7, 0, '2020-12-11 17:07:22');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userRank` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userDemand` varchar(50) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='rank values = string = admin, reviewer, chief_editor';

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`userId`, `username`, `pass`, `email`, `userRank`, `userDemand`) VALUES
(6, 'papa_joueur', '$2y$10$G7a94K26Bvfx3S0toorgJ.ifT3C64XeaFqvKQNOZkr.zOwV2XfaNi', 'mikado842@gmail.com', 'ROLE_ADMIN', 'none'),
(7, 'user01', '$2y$10$ijuMVMUH8qfI6HKTRV.GzujzRmw.YjGQAbncBx8XknpF/nTHg3Pxq', 'test1@test.com', 'ROLE_EDITOR', 'none'),
(12, 'user03', '$2y$10$VpCTr.Flf5aD7ogaR9a2F.BSQpdgICUfuRZ1R8TvHXbfxUnlNeqF6', 'test3@test.com', 'ROLE_REVIEWER', 'none'),
(14, 'Anonyme', '$2y$10$pJju4bUqOj2r4jvAmPEFRecRojGbTezecPu8VTtDofm0r28gi2Sz2', 'mikado842@gmail.com', 'ROLE_ANONYME', 'none'),
(15, 'albert02', '$2y$10$.08qg6DYpWFQo6OlXntiWebGVN8elJ3XxLuPXvHieR8bxFRG2kcmK', 'user02@test.com', 'ROLE_MEMBER', 'REVIEWER_DEMAND');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `cascade` FOREIGN KEY (`reviewId`) REFERENCES `reviews` (`reviewId`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
