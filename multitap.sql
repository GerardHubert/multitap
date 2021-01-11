-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  lun. 11 jan. 2021 à 23:17
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
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `reviewId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `thumbsUp` int(11) NOT NULL,
  `thumbsDown` int(11) NOT NULL,
  `commentDate` datetime NOT NULL,
  `commentStatus` int(5) NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `fk_comments_reviewId` (`reviewId`),
  KEY `fk_comments_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`commentId`, `reviewId`, `userId`, `content`, `thumbsUp`, `thumbsDown`, `commentDate`, `commentStatus`) VALUES
(129, 36, 6, 'je suis un super grand fan de spidey !', 3, 1, '2021-01-02 23:36:09', 2);

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `reviewId` int(11) NOT NULL AUTO_INCREMENT,
  `reviewTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gameTitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apiGameId` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `userId` int(11) NOT NULL,
  `reviewStatus` int(11) NOT NULL,
  `reviewDate` datetime NOT NULL,
  PRIMARY KEY (`reviewId`),
  KEY `fk_reviews_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`reviewId`, `reviewTitle`, `gameTitle`, `apiGameId`, `content`, `userId`, `reviewStatus`, `reviewDate`) VALUES
(36, 'La playstation 5 en a sous le capot', 'Marvel&#39;s Spider-Man: Miles Morales', 452634, '&#60;p&#62;&#60;strong&#62;C&#39;est parti pour le multiverse !&#60;/strong&#62; r&#38;eacute;vision 1&#60;/p&#62;', 6, 0, '2020-12-10 19:20:05'),
(37, 'Le soulèvement des machines', 'Horizon Zero Dawn', 278, '&#60;p&#62;&#60;strong&#62;Un univers que james cameron n&#39;aurait pas reni&#38;eacute;&#60;/strong&#62; ! O&#38;ugrave; les machines dominent le monde !!! Mais sans&#38;nbsp; Sarah Connor... ni le T800 !&#60;/p&#62;', 6, 0, '2020-12-10 23:46:05'),
(38, 'Le meilleur rogue-like de tous les temps', 'Dead Cells', 11726, '&#60;p&#62;Prison Break! Ou la l&#39;&#38;eacute;chapp&#38;eacute;e belle !&#60;/p&#62;', 14, 0, '2020-12-10 23:59:32'),
(39, 'Le prochain FPS de Bungie', 'Halo: Reach', 28613, '&#60;p&#62;N&#39;a pas fait forte impression lors des premi&#38;egrave;res vid&#38;eacute;os de gameplay... Heureusement qu&#39;il y a encore un peu de temps avant sa sortie.&#60;/p&#62;', 6, 0, '2020-12-10 23:49:08'),
(41, 'Voyage en mythologie greque', 'Assassin&#39;s Creed Odyssey', 58616, '&#60;p&#62;Et rencontre avec Herodote, P&#38;eacute;ricl&#38;egrave;s, et autres figures historiques de la Gr&#38;egrave;ce antique ! En compagnie de Kassadra ou d&#39;Alexios!&#60;/p&#62;', 14, 0, '2020-12-11 01:08:32'),
(43, 'Du remake, du vrai, du bon !', 'Crash Bandicoot N. Sane Trilogy', 34, '&#60;p&#62;Comme on aimerait en voir plus souvent !&#60;/p&#62;', 14, 0, '2020-12-11 17:01:11'),
(44, 'La physique des éléments', 'Dead or Alive Xtreme 3: Scarlet', 304898, '&#60;p&#62;Voici le meilleur moteur physique de tous les temps !! Ninja theory: les rois de la mod&#38;eacute;lisation ! Et du fameux effet &#34;boing-boing&#34; !&#60;/p&#62;', 14, 0, '2020-12-11 17:07:22'),
(46, 'Mort aux rats', 'A Plague Tale: Innocence', 59359, '&#60;p&#62;Invasion de nuisibles pendant l&#39;inquisition ! Mias au final, ,y a pas beaucoup moins de rats que dans un parc parisien aujourd&#39;hui... Cette r&#38;eacute;vision (modification apr&#38;egrave;s publication, et donc apr&#38;egrave;s validation par l&#39;admin) doit faire l&#39;objet d&#39;une nouvelle validation de l&#39;admin.&#38;nbsp;Cette ultime r&#38;eacute;vision, la troisi&#38;egrave;me, devrait mettre &#38;agrave; jour la version d&#38;eacute;j&#38;agrave; en attente de validation chez l&#39;admin et l&#39;editor.&#60;em&#62;&#38;nbsp;&#60;/em&#62;&#60;/p&#62;&#13;&#10;&#60;p&#62;Cette derni&#38;egrave;re r&#38;eacute;vision a &#38;eacute;t&#38;eacute; apport&#38;eacute;e par l&#39;editor.&#60;/p&#62;', 14, 0, '2021-01-02 22:35:43'),
(47, 'Le JRPG dans tout son classicisme', 'Octopath Traveler', 46667, '&#60;p&#62;et toute sa splendeur ! &#60;strong&#62;R&#38;eacute;vision 1!&#60;/strong&#62;&#38;nbsp;&#60;em&#62;R&#38;eacute;vision 2 en italique ! R&#38;eacute;vision 3 donne publication !!&#60;/em&#62; Et boom !&#60;/p&#62;', 14, 0, '2021-01-02 22:43:11'),
(48, 'La review de l&#39;admin', 'Assassin&#39;s Creed Valhalla', 437059, '&#60;p&#62;Parce que je le vaut bien !&#60;/p&#62;', 6, 0, '2020-12-14 13:28:29'),
(50, 'Un peu moins bondé que le métro parisien', 'Metro: Last Light Redux', 3604, '&#60;p&#62;Pas de multijoueurs. Pas de map, d&#39;add-ons, de patch pour r&#38;eacute;&#38;eacute;quilibrer quoi que ce soit. Juste une ambiance prenante, un solo solide, et une aventure m&#38;eacute;morable ! Mise en ligne avec modification de l&#39;admin.&#60;/p&#62;', 14, 0, '2020-12-23 02:18:15'),
(51, 'D&#39;la merde', 'Cyberpunk 2077', 41494, '&#60;p&#62;plein de bugs !&#60;/p&#62;&#13;&#10;&#60;p&#62;r&#38;eacute;vision 1.&#60;/p&#62;&#13;&#10;&#60;p&#62;r&#38;eacute;vision de l&#39;editor !&#60;/p&#62;&#13;&#10;&#60;p&#62;r&#38;eacute;vision apr&#38;egrave;s publication&#60;/p&#62;', 14, 0, '2020-12-21 17:48:36'),
(52, 'Finish Him !', 'Mortal Kombat 11 Ultimate', 520359, '&#60;p&#62;Le plus grand jeu de baston de tous les temps !&#60;/p&#62;', 6, 0, '2021-01-02 22:44:40'),
(53, 'Finish Him !', 'Mortal Kombat 11 Ultimate', 520359, '&#60;p&#62;Le plus grand jeu de baston de tous les temps !&#60;/p&#62;', 6, 0, '2021-01-02 22:44:40'),
(54, 'La baston en mode pornawak', 'Super Smash Bros. Ultimate', 58829, '&#60;p&#62;un bon et gros bordel&#60;/p&#62;', 6, 0, '2021-01-02 23:01:13'),
(55, 'un éléphant ça trompe énormément', 'TEMBO THE BADASS ELEPHANT', 1712, '&#60;p&#62;Un bon vieux platformer / action 2d &#38;agrave; l&#39;ancienne, &#38;ccedil;a fait du bien !&#60;/p&#62;', 6, 0, '2021-01-02 23:09:41');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userRank` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `previousRank` varchar(50) NOT NULL,
  `userDemand` varchar(50) NOT NULL DEFAULT 'none',
  `isActive` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `signInDate` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email` (`email`(255))
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COMMENT='rank values = string = admin, reviewer, chief_editor';

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`userId`, `username`, `pass`, `email`, `userRank`, `previousRank`, `userDemand`, `isActive`, `signInDate`, `token`) VALUES
(6, 'papa_joueur', '$2y$10$G7a94K26Bvfx3S0toorgJ.ifT3C64XeaFqvKQNOZkr.zOwV2XfaNi', 'mikado842@gmail.com', 'ROLE_ADMIN', '', 'none', 'active', 0, ''),
(14, 'Anonyme', '$2y$10$pJju4bUqOj2r4jvAmPEFRecRojGbTezecPu8VTtDofm0r28gi2Sz2', 'anonyme@anonyme.com', 'ROLE_ANONYME', '', 'none', 'active', 0, ''),
(81, 'gerard', '$2y$10$1IpZ8Px6sclbve6d2yKBR.nFBGvi9FnqVM66L9c03mDJN.Vm2OaaK', 'gerard.hubert@yahoo.fr', 'ROLE_MEMBER', '', 'none', 'inactive', 0, ''),
(82, 'kassandra', '$2y$10$LpnH.8iU2Vs3VExqicJ8fuBkq3AlNWJ2UXALF6xBw0j1vH9TdO8uW', 'gerard.hubert@mail.fr', 'ROLE_MEMBER', '', 'none', 'active', 0, '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_reviewId` FOREIGN KEY (`reviewId`) REFERENCES `reviews` (`reviewId`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_comments_userId` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_userId` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
