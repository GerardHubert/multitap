-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  jeu. 19 nov. 2020 à 00:06
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewId` int(11) NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text NOT NULL,
  `thumbsUp` int(11) NOT NULL,
  `thumbsDown` int(11) NOT NULL,
  `commentDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `reviewId`, `pseudo`, `content`, `thumbsUp`, `thumbsDown`, `commentDate`) VALUES
(1, 1, 'Don Diego de la Vega', 'Donec feugiat blandit eros eu egestas. Quisque tempor feugiat venenatis. Donec dui sapien, luctus ac consequat eu, luctus eget augue. Nam magna ante, scelerisque vel est at, porta accumsan erat. Ut eros lorem, dictum in urna ac, pretium dignissim lorem.', 2, 1, '0000-00-00 00:00:00'),
(2, 1, 'Jean de la Fontaine', 'Donec feugiat blandit eros eu egestas. Quisque tempor feugiat venenatis. Donec dui sapien, luctus ac consequat eu, luctus eget augue. Nam magna ante, scelerisque vel est at, porta accumsan erat. Ut eros lorem, dictum in urna ac, pretium dignissim lorem.', 12, 0, '0000-00-00 00:00:00'),
(3, 2, 'Long John Silver', 'Donec feugiat blandit eros eu egestas. Quisque tempor feugiat venenatis. Donec dui sapien, luctus ac consequat eu, luctus eget augue. Nam magna ante, scelerisque vel est at, porta accumsan erat. Ut eros lorem, dictum in urna ac, pretium dignissim lorem.', 41, 12, '0000-00-00 00:00:00'),
(4, 5, 'Jackie Chan', 'Donec feugiat blandit eros eu egestas. Quisque tempor feugiat venenatis. Donec dui sapien, luctus ac consequat eu, luctus eget augue. Nam magna ante, scelerisque vel est at, porta accumsan erat. Ut eros lorem, dictum in urna ac, pretium dignissim lorem.', 0, 16, '0000-00-00 00:00:00'),
(5, 2, 'gérard', 'on essaie de laisser un commentaire ?', 0, 0, '2020-11-02 00:00:00'),
(6, 4, 'gérard', 'test', 0, 0, '0000-00-00 00:00:00'),
(7, 4, 'gérard', 'on essaie de laisser un autre commentaire', 0, 0, '2020-11-03 00:29:16'),
(8, 6, 'NintendoManiac', 'La classe ! Banjoe et Kazooie en guests !', 0, 0, '2020-11-03 00:53:41'),
(9, 1, 'Afred De Musset', 'Ce jeu est vraiment cool, mais dur ! Si j\'avais des cheveux, je me les arracherais !', 0, 0, '2020-11-03 16:28:34'),
(10, 2, 'Pokémaniac', 'Rien ne vaudra jamais Pokemon Version Rouge et Bleue !', 0, 0, '2020-11-04 00:25:52'),
(11, 6, 'Gérard', 'Bonjour, ceci est un test !', 0, 0, '2020-11-04 23:57:00'),
(12, 6, 'Link', 'J\'adore Zelda', 0, 0, '2020-11-07 23:27:19'),
(13, 6, 'Diddy Kong', 'Je préfère les Donkey Kong !', 0, 0, '2020-11-07 23:28:14'),
(14, 4, 'gérard', 'test', 0, 0, '2020-11-07 23:46:38'),
(15, 4, 'gérard', 'test2', 0, 0, '2020-11-07 23:48:29'),
(16, 2, 'José', 'Test', 0, 0, '2020-11-07 23:59:10'),
(17, 2, 'José', 'test 2', 0, 0, '2020-11-07 23:59:33'),
(18, 2, 'alberic', 'Je suis dans saint seiya !', 0, 0, '2020-11-08 00:27:19'),
(19, 2, 'Seiya', 'par les météores de pégase !', 0, 0, '2020-11-08 00:28:53'),
(20, 2, 'Liliane', 'j\'en ai marre de scènes de ménage !', 0, 0, '2020-11-08 00:32:54'),
(21, 2, 'gérard', 'test Token', 0, 0, '2020-11-08 00:34:31'),
(22, 5, 'gérard', 'Le must pour ne pas prendre trop de poids pendant le confinement !', 0, 0, '2020-11-08 09:58:26'),
(23, 5, 'gérard', '&#60;h1&#62;hello world&#60;/h1&#62;', 0, 0, '2020-11-09 17:30:32'),
(24, 5, 'gérard', 'test', 0, 0, '2020-11-09 17:31:53'),
(25, 5, 'gérard', 'test 1', 0, 0, '2020-11-09 17:35:47'),
(26, 3, 'Fande_link', 'Ce remake vaut vraiment le détour !', 0, 0, '2020-11-12 21:33:30'),
(27, 1, 'Ezio', 'bonjour, je suis du clan des assassins, et je combats les templiers.', 0, 0, '2020-11-16 16:48:55');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gameTitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apiGameId` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `reviewer` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reviewStatus` int(11) NOT NULL,
  `reviewDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewTitle`, `gameTitle`, `apiGameId`, `content`, `reviewer`, `reviewStatus`, `reviewDate`) VALUES
(1, 'Découverte du rogue-like avec Dead Cells', 'Dead Cells', 11726, 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsam alias fugiat recusandae excepturi eos impedit reiciendis libero non unde dignissimos, modi aperiam eaque quo delectus dolorum omnis atque quaerat ad assumenda tempora eveniet totam expedita ratione quasi. Provident veritatis saepe illo, suscipit amet, optio repellat distinctio voluptatum architecto modi exercitationem aspernatur eius ipsam incidunt earum, dolorum reprehenderit? Sed, dicta assumenda consequuntur sequi est provident delectus totam quia, ea ad quae repellat quas at numquam inventore expedita asperiores optio unde, enim tenetur. Provident facere ipsum exercitationem blanditiis pariatur deserunt eum iusto distinctio non quam? Quidem dicta quis ab nam accusamus possimus.', 'Mauvais_Joueur', 1, '2020-08-14 00:00:00'),
(2, 'Enfin un vrai Pokemon sur Switch !', 'Pokemon Epée / Bouclier', 282825, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eu dolor euismod, hendrerit dui gravida, finibus arcu. Nullam est ipsum, tristique vel turpis mollis, sagittis scelerisque nisl. In bibendum convallis mauris id rhoncus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur porttitor dui nec nulla ultricies volutpat. Curabitur ullamcorper mattis arcu eu consequat. Curabitur eget tincidunt est. Vestibulum porttitor ipsum eget risus blandit, eu vestibulum dolor mollis. Donec facilisis ante eget sapien mattis, et iaculis velit efficitur. Maecenas sed sollicitudin felis, id luctus mi. Curabitur at enim gravida, interdum lectus eget, rutrum dui. Sed venenatis aliquet massa, sit amet placerat justo semper sed. Duis tincidunt lacinia nibh non elementum. Mauris sollicitudin, massa id bibendum pharetra, tellus nibh vehicula orci, vitae luctus justo nisi quis urna. Vestibulum non urna ut arcu congue tristique.\r\n\r\nUt sed convallis ipsum. Sed venenatis sapien sit amet finibus aliquet. Vivamus risus elit, maximus tempus imperdiet id, ultricies quis mi. Vestibulum mattis augue quis diam euismod, vel euismod ante gravida. Nulla facilisi. In gravida ligula eu leo faucibus rutrum. Maecenas volutpat tellus tortor, viverra gravida ante vehicula vel. Praesent vel viverra purus, ac vehicula diam. Mauris sodales ultrices tempus. Quisque sodales in velit eget tempor. Phasellus accumsan iaculis quam, sed vehicula magna sagittis ac. In velit tortor, porttitor eget elit eu, consequat vulputate massa. Aenean cursus, diam in fringilla lobortis, arcu velit ultricies quam, a lobortis mi urna a dui. Sed neque ipsum, cursus et eleifend ac, finibus id ipsum.', 'Gérard', 1, '2019-12-12 00:00:00'),
(3, 'Le remake du 1er Zelda déchire', 'Zelda : Link\'s Awakening', 292842, 'Aliquam sit amet egestas sapien. Donec elementum finibus turpis. Maecenas eleifend mattis dolor, cursus pellentesque elit porta ac. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget vestibulum justo, et semper mi. Curabitur sed egestas ligula. Aenean rutrum volutpat diam. Donec mauris mi, convallis ut ipsum sit amet, dictum accumsan nunc. Morbi euismod sed turpis sed feugiat. Curabitur congue tempor cursus. Pellentesque ac felis in lorem pharetra rhoncus a quis ex. Pellentesque auctor est id fringilla facilisis. Donec lacinia tempus mattis. Morbi a placerat ex, nec blandit nunc.\r\n\r\nDonec suscipit porta felis, et tempor lorem bibendum nec. Proin in aliquam arcu, sit amet blandit ligula. Nullam ac imperdiet massa, a bibendum erat. Cras varius leo congue, dignissim dolor condimentum, placerat mauris. Nunc at elit lorem. Integer pellentesque scelerisque eleifend. Curabitur non fermentum nibh.\r\n\r\nQuisque eget augue ut lectus rutrum efficitur. Cras nec sagittis dolor. Vestibulum eget velit hendrerit, blandit nisl non, viverra sem. Cras vitae tortor tellus. Vivamus mollis pretium ultrices. In sagittis quis sapien et lobortis. Duis ornare id elit auctor suscipit. Nullam eleifend mi in lacus pretium, vitae auctor nibh pellentesque. Ut congue lectus egestas nisl pellentesque, vitae sodales diam vulputate. Morbi urna enim, cursus vitae maximus eget, eleifend ac arcu.\r\n\r\nDonec tincidunt, lacus volutpat congue pellentesque, leo massa cursus massa, eu porta justo neque quis justo. Vivamus nec aliquam odio. Donec lacinia posuere ultrices. Proin cursus accumsan dignissim. Morbi convallis ante nec massa imperdiet, sed maximus risus vehicula. Mauris egestas ligula sed lobortis varius. Etiam massa nunc, pulvinar in elementum et, aliquet id dui. Vivamus vehicula ac quam id egestas. Donec eros nisl, sodales mollis turpis vestibulum, tristique mattis turpis. Praesent non lorem at orci facilisis lacinia facilisis in ante.\r\n\r\nNulla vulputate sagittis risus, sollicitudin convallis velit tempor id. Proin in rutrum velit. Nam rutrum lacus ac metus porta porta. In sit amet tristique magna, sed finibus nisi. Maecenas eu fermentum tellus. Ut mollis venenatis diam ac cursus. Donec viverra metus ante, tempus viverra dui feugiat et. Cras varius, urna eu faucibus rutrum, nisl lacus facilisis massa, quis pellentesque sapien magna sit amet purus. Phasellus pellentesque massa massa, a molestie justo egestas in. Aenean commodo viverra libero non scelerisque. Praesent urna nibh, iaculis pretium lectus et, convallis porta ipsum. Pellentesque risus urna, scelerisque eget placerat vitae, pretium in enim. Nullam convallis ultrices sem, et vehicula magna convallis non. Fusce egestas nibh magna.', 'Tri-Force_master', 2, '2020-05-17 00:00:00'),
(4, 'La vie de famille selon Kratos', 'God of War', 58175, 'Vivamus sagittis cursus augue eu pellentesque. Vestibulum sit amet odio at enim euismod ornare sit amet ut massa. Nulla vel consectetur erat, ut vestibulum dui. Duis condimentum non ante et consectetur. Aliquam erat volutpat. Donec interdum accumsan tellus. Nam sodales lorem ex, sed finibus libero egestas nec. Nulla facilisi. Donec ut sagittis purus, ut maximus odio. Curabitur eros magna, vulputate in mauris at, pulvinar aliquet massa. Cras placerat velit iaculis lectus facilisis, sed sodales purus volutpat. Curabitur interdum, nulla nec auctor viverra, enim orci placerat mi, varius fermentum nisl dolor vel nibh. Nam tincidunt enim vel est aliquet, vitae euismod dui mollis.\r\n\r\nNulla id metus quis sapien iaculis gravida sed at enim. Curabitur vestibulum velit semper mauris cursus pharetra. Suspendisse fermentum ornare nibh ut ornare. Proin ut neque velit. Morbi bibendum imperdiet sollicitudin. Quisque non pharetra quam. Fusce nec mi et turpis volutpat tincidunt. Nulla tortor sem, aliquam nec ligula in, tincidunt consequat ligula. Nulla id justo eget massa accumsan efficitur. Cras quis elementum turpis, a malesuada neque. Vivamus ultrices dui quis ante tempus laoreet. Cras lobortis malesuada tortor condimentum commodo. Mauris egestas eleifend fringilla.\r\n\r\nPraesent ultrices ac massa non vehicula. Curabitur suscipit risus in enim sodales efficitur. Praesent placerat, eros sit amet maximus tincidunt, mauris mi aliquam augue, a condimentum nunc odio a justo. Vestibulum eleifend aliquet quam, id vehicula nibh eleifend sit amet. Mauris vel arcu iaculis, sagittis nunc ut, vehicula nisi. Nullam mollis consectetur tempor. Aenean sollicitudin arcu a condimentum laoreet. Nunc at scelerisque sem.\r\n\r\nIn porttitor, erat ac tincidunt hendrerit, augue urna dictum metus, non ultricies quam elit eu ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis convallis tempus risus, eget faucibus lectus blandit at. Vivamus vitae magna mauris. Morbi odio arcu, varius non sodales in, tincidunt non eros. Maecenas tincidunt sapien mi, et rhoncus libero sollicitudin eu. Pellentesque eu libero nulla.\r\n\r\nSed lacinia dignissim magna, non tempus justo rhoncus at. Ut iaculis mattis ante vel sodales. In bibendum, neque et aliquam pretium, ligula justo fringilla lacus, vel fringilla massa sem sed neque. Pellentesque condimentum, dolor non malesuada aliquet, ipsum purus interdum lorem, eget aliquam ipsum erat sit amet libero. Quisque urna nisi, venenatis nec nunc vel, volutpat aliquet quam. Sed pharetra arcu leo, et aliquet massa luctus a. Fusce posuere tortor quis egestas maximus. Aenean vulputate nisl tellus, id elementum metus vestibulum id. Quisque ullamcorper magna sapien, eu pharetra quam ultricies at. Sed vitae augue metus. In commodo augue eget facilisis interdum. Aliquam imperdiet arcu posuere, rhoncus enim vel, mollis quam. Ut maximus gravida auctor. Ut facilisis ornare metus quis dignissim.', 'Platon', 0, '2019-03-14 00:00:00'),
(5, 'Eliminez la raclette avec Ring-Fit Adventures', 'Ring-Fit Adventures', 373511, 'Nunc sagittis porttitor mi, at sodales velit elementum sit amet. Vivamus eu consectetur ex. Suspendisse id elit ligula. Nulla leo ante, facilisis vitae tincidunt at, vulputate eget leo. Aenean facilisis sagittis dolor, eget iaculis urna tincidunt nec. Curabitur eget massa at sapien pretium tempor. Phasellus maximus congue felis, eu sagittis leo aliquam a. Duis tristique porttitor venenatis. Nunc vitae sapien in odio ultricies tempus. Aliquam lorem dolor, mattis eu urna ut, iaculis sagittis enim. Nam tincidunt, est lacinia rhoncus efficitur, augue turpis consequat eros, vitae maximus quam dolor in diam. Aenean egestas arcu risus, ac ornare velit eleifend nec. Phasellus id eros non leo sodales fermentum. Curabitur scelerisque a dui vitae placerat.\r\n\r\nNunc sollicitudin quam in augue iaculis, ac hendrerit ex tempus. Proin sit amet mi quis tellus blandit pretium a et ipsum. Ut a lectus felis. Etiam neque ex, aliquam vel ultricies ac, viverra nec mauris. Phasellus pharetra, nibh in congue faucibus, nulla urna tempor nisl, ornare semper erat risus eu enim. Quisque sollicitudin nulla non nunc viverra, vitae ullamcorper magna viverra. Fusce congue justo et lacus bibendum auctor non id enim. Duis blandit elementum tellus. Nullam a libero suscipit, volutpat odio sed, fringilla felis. Integer mattis rhoncus elit at vestibulum. Vivamus sollicitudin sapien augue, quis vulputate nisi malesuada eu. Quisque in ex ligula. Donec eget varius tellus.\r\n\r\nMaecenas dapibus condimentum metus, et iaculis tortor aliquam at. Nam tellus nisi, ornare ac elit quis, venenatis malesuada ex. In ornare scelerisque mauris, ac luctus diam gravida in. Fusce ex tellus, volutpat a lacus et, tincidunt convallis est. Aenean diam felis, eleifend nec nisi eu, ultrices aliquam est. Proin sodales, nisl sit amet mattis ullamcorper, ligula eros fermentum risus, nec pretium arcu odio ac ante. In hac habitasse platea dictumst. Vivamus nec lacus feugiat purus luctus pretium. Morbi vitae leo ornare dolor viverra scelerisque non sit amet dolor. Nullam nulla massa, euismod id mauris at, aliquet aliquam arcu. Curabitur quis tempor augue.\r\n\r\nDonec sit amet lacinia sem, sed facilisis nisl. Nulla eu sem risus. Nullam nisi tortor, condimentum ut rhoncus accumsan, suscipit sit amet tellus. Donec in libero iaculis, sagittis turpis eget, malesuada orci. Duis molestie rutrum elit, non finibus diam tincidunt vel. Integer viverra enim ac nisi efficitur, ut sagittis ex finibus. Nullam at mauris mauris.\r\n\r\nPellentesque vitae feugiat mi, non tristique nisl. Nunc luctus, justo et commodo malesuada, arcu ipsum pellentesque lacus, sit amet dignissim enim nulla sit amet mauris. Etiam ut ligula tempus ipsum rutrum fringilla. Nam ultricies ullamcorper metus, sed ultricies purus volutpat nec. Integer pulvinar tortor lobortis, egestas mauris ac, vulputate odio. Nullam a dignissim turpis. Suspendisse malesuada metus ac sollicitudin venenatis. Integer bibendum velit ac risus laoreet lobortis. Nam pretium urna et eleifend eleifend. Donec aliquam cursus est, eget porta massa blandit non. Phasellus condimentum diam nec purus fringilla, id tristique eros tempus. Nullam rhoncus nibh lorem, at ornare libero maximus ac. Aenean id elit semper eros tempus blandit. Maecenas vehicula, velit et sollicitudin gravida, est lorem tristique nisi, a suscipit massa nisi fermentum est. Morbi maximus, lorem sit amet consequat posuere, tortor nunc dignissim ex, vitae elementum ipsum neque sed dolor.', 'Jogger_du_dimanche', 2, '2019-08-14 00:00:00'),
(6, 'Noel avec le nouveau Smash Bros', 'Super Smash Bros Ultimate', 58829, 'Nunc sagittis porttitor mi, at sodales velit elementum sit amet. Vivamus eu consectetur ex. Suspendisse id elit ligula. Nulla leo ante, facilisis vitae tincidunt at, vulputate eget leo. Aenean facilisis sagittis dolor, eget iaculis urna tincidunt nec. Curabitur eget massa at sapien pretium tempor. Phasellus maximus congue felis, eu sagittis leo aliquam a. Duis tristique porttitor venenatis. Nunc vitae sapien in odio ultricies tempus. Aliquam lorem dolor, mattis eu urna ut, iaculis sagittis enim. Nam tincidunt, est lacinia rhoncus efficitur, augue turpis consequat eros, vitae maximus quam dolor in diam. Aenean egestas arcu risus, ac ornare velit eleifend nec. Phasellus id eros non leo sodales fermentum. Curabitur scelerisque a dui vitae placerat.\r\n\r\nNunc sollicitudin quam in augue iaculis, ac hendrerit ex tempus. Proin sit amet mi quis tellus blandit pretium a et ipsum. Ut a lectus felis. Etiam neque ex, aliquam vel ultricies ac, viverra nec mauris. Phasellus pharetra, nibh in congue faucibus, nulla urna tempor nisl, ornare semper erat risus eu enim. Quisque sollicitudin nulla non nunc viverra, vitae ullamcorper magna viverra. Fusce congue justo et lacus bibendum auctor non id enim. Duis blandit elementum tellus. Nullam a libero suscipit, volutpat odio sed, fringilla felis. Integer mattis rhoncus elit at vestibulum. Vivamus sollicitudin sapien augue, quis vulputate nisi malesuada eu. Quisque in ex ligula. Donec eget varius tellus.\r\n\r\nMaecenas dapibus condimentum metus, et iaculis tortor aliquam at. Nam tellus nisi, ornare ac elit quis, venenatis malesuada ex. In ornare scelerisque mauris, ac luctus diam gravida in. Fusce ex tellus, volutpat a lacus et, tincidunt convallis est. Aenean diam felis, eleifend nec nisi eu, ultrices aliquam est. Proin sodales, nisl sit amet mattis ullamcorper, ligula eros fermentum risus, nec pretium arcu odio ac ante. In hac habitasse platea dictumst. Vivamus nec lacus feugiat purus luctus pretium. Morbi vitae leo ornare dolor viverra scelerisque non sit amet dolor. Nullam nulla massa, euismod id mauris at, aliquet aliquam arcu. Curabitur quis tempor augue.\r\n\r\nDonec sit amet lacinia sem, sed facilisis nisl. Nulla eu sem risus. Nullam nisi tortor, condimentum ut rhoncus accumsan, suscipit sit amet tellus. Donec in libero iaculis, sagittis turpis eget, malesuada orci. Duis molestie rutrum elit, non finibus diam tincidunt vel. Integer viverra enim ac nisi efficitur, ut sagittis ex finibus. Nullam at mauris mauris.\r\n\r\nPellentesque vitae feugiat mi, non tristique nisl. Nunc luctus, justo et commodo malesuada, arcu ipsum pellentesque lacus, sit amet dignissim enim nulla sit amet mauris. Etiam ut ligula tempus ipsum rutrum fringilla. Nam ultricies ullamcorper metus, sed ultricies purus volutpat nec. Integer pulvinar tortor lobortis, egestas mauris ac, vulputate odio. Nullam a dignissim turpis. Suspendisse malesuada metus ac sollicitudin venenatis. Integer bibendum velit ac risus laoreet lobortis. Nam pretium urna et eleifend eleifend. Donec aliquam cursus est, eget porta massa blandit non. Phasellus condimentum diam nec purus fringilla, id tristique eros tempus. Nullam rhoncus nibh lorem, at ornare libero maximus ac. Aenean id elit semper eros tempus blandit. Maecenas vehicula, velit et sollicitudin gravida, est lorem tristique nisi, a suscipit massa nisi fermentum est. Morbi maximus, lorem sit amet consequat posuere, tortor nunc dignissim ex, vitae elementum ipsum neque sed dolor.', 'Jean_forteroche', 1, '2019-10-08 00:00:00'),
(11, 'Un excellent film intéractif !', 'The Order: 1886', 3459, '&#60;p&#62;Mon premier jeu PS4 &#38;agrave; la r&#38;eacute;alisation d&#39;enfer.... mais sans gameplay...&#60;/p&#62;', 'Gérard', 0, '2020-11-18 23:16:12'),
(12, 'Un vrai bon cadeau de noel pour les enfants', 'Spyro Reignited Trilogy', 58133, '<p>Et pour les faire d&eacute;couvrir les jeux de mon enfance !</p>', 'Gérard', 0, '2020-11-18 23:19:30'),
(13, 'Le rogue-like a le vent en poupe', 'Hades', 274755, 'La switch, c&#39;est vraiment le royaume des indies !!', 'Gérard', 0, '2020-11-18 23:23:18'),
(14, 'test', 'Hotline Miami', 3612, 'review test', 'Gérard', 0, '2020-11-18 23:26:49'),
(15, 'review test d&#39;apostrophe', 'Hotline Miami', 3612, 'l&#39;aspostrophe devrait s&#39;afficher correctement &eacute; l&eacute; accents aussi', 'Gérard', 0, '2020-11-18 23:27:58'),
(16, 'Hack and Slash !', 'Diablo III', 23600, '<p>l&#39;hack and slash dans toute sa splendeur ! &eacute; un accent l&agrave; !</p>', 'Gérard', 0, '2020-11-18 23:34:20');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
