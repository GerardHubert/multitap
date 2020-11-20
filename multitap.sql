-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  ven. 20 nov. 2020 à 23:43
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
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `reviewId` tinyint(11) NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text NOT NULL,
  `thumbsUp` int(11) NOT NULL,
  `thumbsDown` int(11) NOT NULL,
  `commentDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cascade` (`reviewId`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `reviewId`, `pseudo`, `content`, `thumbsUp`, `thumbsDown`, `commentDate`) VALUES
(4, 5, 'Jackie Chan', 'Donec feugiat blandit eros eu egestas. Quisque tempor feugiat venenatis. Donec dui sapien, luctus ac consequat eu, luctus eget augue. Nam magna ante, scelerisque vel est at, porta accumsan erat. Ut eros lorem, dictum in urna ac, pretium dignissim lorem.', 0, 16, '0000-00-00 00:00:00'),
(8, 6, 'NintendoManiac', 'La classe ! Banjoe et Kazooie en guests !', 0, 0, '2020-11-03 00:53:41'),
(11, 6, 'Gérard', 'Bonjour, ceci est un test !', 0, 0, '2020-11-04 23:57:00'),
(12, 6, 'Link', 'J\'adore Zelda', 0, 0, '2020-11-07 23:27:19'),
(13, 6, 'Diddy Kong', 'Je préfère les Donkey Kong !', 0, 0, '2020-11-07 23:28:14'),
(22, 5, 'gérard', 'Le must pour ne pas prendre trop de poids pendant le confinement !', 0, 0, '2020-11-08 09:58:26'),
(23, 5, 'gérard', '&#60;h1&#62;hello world&#60;/h1&#62;', 0, 0, '2020-11-09 17:30:32'),
(24, 5, 'gérard', 'test', 0, 0, '2020-11-09 17:31:53'),
(25, 5, 'gérard', 'test 1', 0, 0, '2020-11-09 17:35:47'),
(26, 3, 'Fande_link', 'Ce remake vaut vraiment le détour !', 0, 0, '2020-11-12 21:33:30'),
(28, 15, 'gérard', 'A s&#39;arracher les cheveux ce jeu.. Mais tellement jouissif !', 0, 0, '2020-11-19 18:31:19'),
(29, 15, 'Alfredo', 'De la difficulté à l&#39;ancienne !', 0, 0, '2020-11-19 18:33:35'),
(30, 15, 'Don Diego de la Vega', 'Forcément, c&#39;est Devolver: ils assurent les mecs !', 0, 0, '2020-11-19 18:46:58'),
(31, 15, 'Sergent Garcia', 'C&#39;est l&#39;apostrophe qui tue!', 0, 0, '2020-11-19 18:48:02'),
(32, 15, 'Tornado', 'Les chevaux ont-ils leur place dans l&#39;histoire ?', 0, 0, '2020-11-19 18:58:21'),
(33, 17, 'Al&#39;adin', 'Faudra prévoir un message d&#39;erreur en cas d&#39;absence de vidéo', 0, 0, '2020-11-19 21:27:30'),
(34, 21, 'aida', 'bravo', 0, 0, '2020-11-20 18:33:15');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `reviewTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gameTitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apiGameId` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `reviewer` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewStatus` int(11) NOT NULL,
  `reviewDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewTitle`, `gameTitle`, `apiGameId`, `content`, `reviewer`, `reviewStatus`, `reviewDate`) VALUES
(3, 'Le remake du 1er Zelda déchire', 'Zelda : Link\'s Awakening', 292842, 'Aliquam sit amet egestas sapien. Donec elementum finibus turpis. Maecenas eleifend mattis dolor, cursus pellentesque elit porta ac. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget vestibulum justo, et semper mi. Curabitur sed egestas ligula. Aenean rutrum volutpat diam. Donec mauris mi, convallis ut ipsum sit amet, dictum accumsan nunc. Morbi euismod sed turpis sed feugiat. Curabitur congue tempor cursus. Pellentesque ac felis in lorem pharetra rhoncus a quis ex. Pellentesque auctor est id fringilla facilisis. Donec lacinia tempus mattis. Morbi a placerat ex, nec blandit nunc.\r\n\r\nDonec suscipit porta felis, et tempor lorem bibendum nec. Proin in aliquam arcu, sit amet blandit ligula. Nullam ac imperdiet massa, a bibendum erat. Cras varius leo congue, dignissim dolor condimentum, placerat mauris. Nunc at elit lorem. Integer pellentesque scelerisque eleifend. Curabitur non fermentum nibh.\r\n\r\nQuisque eget augue ut lectus rutrum efficitur. Cras nec sagittis dolor. Vestibulum eget velit hendrerit, blandit nisl non, viverra sem. Cras vitae tortor tellus. Vivamus mollis pretium ultrices. In sagittis quis sapien et lobortis. Duis ornare id elit auctor suscipit. Nullam eleifend mi in lacus pretium, vitae auctor nibh pellentesque. Ut congue lectus egestas nisl pellentesque, vitae sodales diam vulputate. Morbi urna enim, cursus vitae maximus eget, eleifend ac arcu.\r\n\r\nDonec tincidunt, lacus volutpat congue pellentesque, leo massa cursus massa, eu porta justo neque quis justo. Vivamus nec aliquam odio. Donec lacinia posuere ultrices. Proin cursus accumsan dignissim. Morbi convallis ante nec massa imperdiet, sed maximus risus vehicula. Mauris egestas ligula sed lobortis varius. Etiam massa nunc, pulvinar in elementum et, aliquet id dui. Vivamus vehicula ac quam id egestas. Donec eros nisl, sodales mollis turpis vestibulum, tristique mattis turpis. Praesent non lorem at orci facilisis lacinia facilisis in ante.\r\n\r\nNulla vulputate sagittis risus, sollicitudin convallis velit tempor id. Proin in rutrum velit. Nam rutrum lacus ac metus porta porta. In sit amet tristique magna, sed finibus nisi. Maecenas eu fermentum tellus. Ut mollis venenatis diam ac cursus. Donec viverra metus ante, tempus viverra dui feugiat et. Cras varius, urna eu faucibus rutrum, nisl lacus facilisis massa, quis pellentesque sapien magna sit amet purus. Phasellus pellentesque massa massa, a molestie justo egestas in. Aenean commodo viverra libero non scelerisque. Praesent urna nibh, iaculis pretium lectus et, convallis porta ipsum. Pellentesque risus urna, scelerisque eget placerat vitae, pretium in enim. Nullam convallis ultrices sem, et vehicula magna convallis non. Fusce egestas nibh magna.', 'Tri-Force_master', 2, '2020-05-17 00:00:00'),
(5, 'Eliminez la raclette avec Ring-Fit Adventures', 'Ring-Fit Adventures', 373511, 'Nunc sagittis porttitor mi, at sodales velit elementum sit amet. Vivamus eu consectetur ex. Suspendisse id elit ligula. Nulla leo ante, facilisis vitae tincidunt at, vulputate eget leo. Aenean facilisis sagittis dolor, eget iaculis urna tincidunt nec. Curabitur eget massa at sapien pretium tempor. Phasellus maximus congue felis, eu sagittis leo aliquam a. Duis tristique porttitor venenatis. Nunc vitae sapien in odio ultricies tempus. Aliquam lorem dolor, mattis eu urna ut, iaculis sagittis enim. Nam tincidunt, est lacinia rhoncus efficitur, augue turpis consequat eros, vitae maximus quam dolor in diam. Aenean egestas arcu risus, ac ornare velit eleifend nec. Phasellus id eros non leo sodales fermentum. Curabitur scelerisque a dui vitae placerat.\r\n\r\nNunc sollicitudin quam in augue iaculis, ac hendrerit ex tempus. Proin sit amet mi quis tellus blandit pretium a et ipsum. Ut a lectus felis. Etiam neque ex, aliquam vel ultricies ac, viverra nec mauris. Phasellus pharetra, nibh in congue faucibus, nulla urna tempor nisl, ornare semper erat risus eu enim. Quisque sollicitudin nulla non nunc viverra, vitae ullamcorper magna viverra. Fusce congue justo et lacus bibendum auctor non id enim. Duis blandit elementum tellus. Nullam a libero suscipit, volutpat odio sed, fringilla felis. Integer mattis rhoncus elit at vestibulum. Vivamus sollicitudin sapien augue, quis vulputate nisi malesuada eu. Quisque in ex ligula. Donec eget varius tellus.\r\n\r\nMaecenas dapibus condimentum metus, et iaculis tortor aliquam at. Nam tellus nisi, ornare ac elit quis, venenatis malesuada ex. In ornare scelerisque mauris, ac luctus diam gravida in. Fusce ex tellus, volutpat a lacus et, tincidunt convallis est. Aenean diam felis, eleifend nec nisi eu, ultrices aliquam est. Proin sodales, nisl sit amet mattis ullamcorper, ligula eros fermentum risus, nec pretium arcu odio ac ante. In hac habitasse platea dictumst. Vivamus nec lacus feugiat purus luctus pretium. Morbi vitae leo ornare dolor viverra scelerisque non sit amet dolor. Nullam nulla massa, euismod id mauris at, aliquet aliquam arcu. Curabitur quis tempor augue.\r\n\r\nDonec sit amet lacinia sem, sed facilisis nisl. Nulla eu sem risus. Nullam nisi tortor, condimentum ut rhoncus accumsan, suscipit sit amet tellus. Donec in libero iaculis, sagittis turpis eget, malesuada orci. Duis molestie rutrum elit, non finibus diam tincidunt vel. Integer viverra enim ac nisi efficitur, ut sagittis ex finibus. Nullam at mauris mauris.\r\n\r\nPellentesque vitae feugiat mi, non tristique nisl. Nunc luctus, justo et commodo malesuada, arcu ipsum pellentesque lacus, sit amet dignissim enim nulla sit amet mauris. Etiam ut ligula tempus ipsum rutrum fringilla. Nam ultricies ullamcorper metus, sed ultricies purus volutpat nec. Integer pulvinar tortor lobortis, egestas mauris ac, vulputate odio. Nullam a dignissim turpis. Suspendisse malesuada metus ac sollicitudin venenatis. Integer bibendum velit ac risus laoreet lobortis. Nam pretium urna et eleifend eleifend. Donec aliquam cursus est, eget porta massa blandit non. Phasellus condimentum diam nec purus fringilla, id tristique eros tempus. Nullam rhoncus nibh lorem, at ornare libero maximus ac. Aenean id elit semper eros tempus blandit. Maecenas vehicula, velit et sollicitudin gravida, est lorem tristique nisi, a suscipit massa nisi fermentum est. Morbi maximus, lorem sit amet consequat posuere, tortor nunc dignissim ex, vitae elementum ipsum neque sed dolor.', 'Jogger_du_dimanche', 2, '2019-08-14 00:00:00'),
(11, 'Un excellent film intéractif !', 'The Order: 1886', 3459, '&#60;p&#62;Mon premier jeu PS4 &#38;agrave; la r&#38;eacute;alisation d&#39;enfer.... mais sans gameplay...&#60;/p&#62;', 'Gérard', 0, '2020-11-18 23:16:12'),
(12, 'Un vrai bon cadeau de noel pour les enfants', 'Spyro Reignited Trilogy', 58133, '<p>Et pour les faire d&eacute;couvrir les jeux de mon enfance !</p>', 'Gérard', 0, '2020-11-18 23:19:30'),
(13, 'Le rogue-like a le vent en poupe', 'Hades', 274755, 'La switch, c&#39;est vraiment le royaume des indies !!', 'Gérard', 0, '2020-11-18 23:23:18'),
(14, 'test', 'Hotline Miami', 3612, 'review test', 'Gérard', 0, '2020-11-18 23:26:49'),
(15, 'review test d&#39;apostrophe', 'Hotline Miami', 3612, 'l&#39;aspostrophe devrait s&#39;afficher correctement &eacute; l&eacute; accents aussi', 'Gérard', 0, '2020-11-18 23:27:58'),
(16, 'Hack and Slash !', 'Diablo III', 23600, '<p>l&#39;hack and slash dans toute sa splendeur ! &eacute; un accent l&agrave; !</p>', 'Gérard', 0, '2020-11-18 23:34:20'),
(18, 'Please intert disc 2', 'Final Fantasy VII Remake', 259801, 'Le jRPG dans toute sa splendeur !', 'Gérard', 0, '2020-11-19 18:23:47'),
(19, 'Le TPS effrené et gore dans un ultime épisode', 'Gears 5', 326252, 'Et c&#39;est bon de retrouver les fusils-tron&ccedil;onneuses !', 'Gérard', 0, '2020-11-19 22:55:21'),
(20, 'War of the Machines', 'Horizon Zero Dawn', 278, 'Le jugement dernier vu par Guerilla !', 'Gérard', 0, '2020-11-20 18:23:47'),
(21, 'mon avis', 'Resident Evil 6', 2623, 'test', 'Gérard', 0, '2020-11-20 18:32:21');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='rank values = string = admin, reviewer, chief_editor';

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `cascade` FOREIGN KEY (`reviewId`) REFERENCES `reviews` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
