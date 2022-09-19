-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 08, 2021 at 03:25 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MisterCocktail`
--
CREATE DATABASE IF NOT EXISTS `MisterCocktail` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `MisterCocktail`;

-- --------------------------------------------------------

--
-- Table structure for table `Cocktail`
--

CREATE TABLE `Cocktail` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `urlPhoto` varchar(255) NOT NULL,
  `dateConception` date NOT NULL,
  `prixMoyen` float NOT NULL,
  `idFamille` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Cocktail`
--

INSERT INTO `Cocktail` (`id`, `nom`, `description`, `urlPhoto`, `dateConception`, `prixMoyen`, `idFamille`) VALUES
(1, 'Aperol Spritz', 'Le Spritz est un cocktail datant du siècle dernier. Il aurait été inventé par des soldats autrichiens qui trouvaient le vin italien trop chargé en alcool.<br><br>L’auriez-vous deviné ?', 'aperol-spritz.jpg', '1938-01-01', 9.75, 6),
(2, 'Mojito', 'La création du Mojito remonte au XVIe siècle lorsque Francis Draque, le célèbre corsaire anglais, avait pour habitude de célébrer ses pillages en sirotant à La Havane, du tafia (l’ancêtre du rhum), aromatisé de quelques feuilles de menthe et de citron.', 'mojito.jpg', '1583-01-01', 10, 2),
(3, 'Piña Colada', 'Le cocktail Piña Colada puise ses origines à Puerto Rico où il a été inventé par un barman de l’hôtel Caribe Hilton en 1954. Décrétée 30 ans plus tard boisson nationale, cet élixir doux et fruité concentre dans le verre toutes les saveurs ensoleillées des Caraïbes.', 'pina-colada.jpg', '1954-01-01', 8.85, 2),
(6, 'Punch', 'Historiquement, le punch date du XVIe siècle. Il aurait été créé par des marins britanniques en mélangeant du Tafia (un genre de rhum brut) qui était embarqué sur les navires, avec d’autres ingrédients.', 'punch.jpg', '1532-01-01', 9, 2),
(7, 'Punch Exotique', 'Historiquement, le punch date du XVIe siècle. Il aurait été créé par des marins britanniques en mélangeant du Tafia (un genre de rhum brut) qui était embarqué sur les navires, avec d’autres ingrédients.', 'punch-exotique.jpg', '1532-01-01', 10.55, 2),
(8, 'Soupe de Champagne', 'À l’origine, c’était Pierre «Dom» Pérignon (1635-1713) qui dirigeait un monastère à Reims et qui a marqué l’histoire du champagne tout en contribuant beaucoup à sa renommée.', 'soupe-champagne.jpg', '1621-01-01', 12.35, 4),
(9, 'Caipirinha', 'La caïpirinha est un cocktail brésilien préparé à base de cachaça, de sucre de canne et de citron vert. Créé par les paysans dont il tirerait l\'origine de son nom, ce cocktail est très populaire et largement consommé dans les restaurants, bars et boîtes de nuit.', 'caipirinha.webp', '1918-01-01', 10, 7),
(10, 'Blue Lagoon ', 'Le Blue Lagoon est un cocktail à base de vodka, de curaçao bleu et de jus de citron. Il est aussi appelé le « lagon bleu » par sa traduction. Il fut créé par Andy MacElhone au Harry\'s New York Bar à Paris, en 1960.', 'blue_lagoon.webp', '1960-01-01', 11, 1),
(11, 'Vodka martini', 'Ce cocktail est réalisé en combinant vodka, vermouth sec, et glace, dans un shaker, ou dans un verre mélangeur, avec deux dosages répandus : quatre doses, ou six doses de vodka, pour une de vermouth.', 'vodka_martini.webp', '1933-01-01', 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Famille`
--

CREATE TABLE `Famille` (
  `id` int(11) NOT NULL,
  `nomFamille` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Famille`
--

INSERT INTO `Famille` (`id`, `nomFamille`) VALUES
(1, 'Vodka'),
(2, 'Rhum'),
(3, 'Whisky'),
(4, 'Champagne'),
(5, 'Sans alcool'),
(6, 'Aperol'),
(7, 'Cachaça');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cocktail`
--
ALTER TABLE `Cocktail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_Famille` (`idFamille`);

--
-- Indexes for table `Famille`
--
ALTER TABLE `Famille`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Cocktail`
--
ALTER TABLE `Cocktail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Famille`
--
ALTER TABLE `Famille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cocktail`
--
ALTER TABLE `Cocktail`
  ADD CONSTRAINT `cocktail_ibfk_1` FOREIGN KEY (`idFamille`) REFERENCES `Famille` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
