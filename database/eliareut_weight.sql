-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Nov 2017 um 17:48
-- Server-Version: 10.1.25-MariaDB
-- PHP-Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `eliareut_weight`
--
CREATE DATABASE IF NOT EXISTS `eliareut_weight` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `eliareut_weight`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `ID` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `goal_weight` double DEFAULT NULL,
  `goal_date` date DEFAULT NULL,
  `current_weight` double DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `height` double DEFAULT NULL,
  `bmi` double DEFAULT NULL,
  `age` double DEFAULT NULL,
  `user_lang` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_user`
--

INSERT INTO `tbl_user` (`ID`, `name`, `goal_weight`, `goal_date`, `current_weight`, `username`, `password`, `height`, `bmi`, `age`, `user_lang`) VALUES
(16, 'Admin', 50, '0000-00-00', 59, 'admin', '$2a$10$JqffdpZ8hogbICYyWMVM7OyXkB5RZZ2inkaGO9YCc.JANEGi/z7Wq', 170, NULL, 20, 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_weight_data`
--

DROP TABLE IF EXISTS `tbl_weight_data`;
CREATE TABLE `tbl_weight_data` (
  `ID` int(11) NOT NULL,
  `weight` double NOT NULL,
  `time` datetime NOT NULL,
  `tbl_user_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tbl_weight_data`
--

INSERT INTO `tbl_weight_data` (`ID`, `weight`, `time`, `tbl_user_ID`) VALUES
(142, 60, '2017-11-23 17:47:55', 16),
(143, 59, '2017-11-23 17:48:15', 16);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `tbl_weight_data`
--
ALTER TABLE `tbl_weight_data`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `tbl_user_ID` (`tbl_user_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT für Tabelle `tbl_weight_data`
--
ALTER TABLE `tbl_weight_data`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tbl_weight_data`
--
ALTER TABLE `tbl_weight_data`
  ADD CONSTRAINT `tbl_weight_data_ibfk_1` FOREIGN KEY (`tbl_user_ID`) REFERENCES `tbl_user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
