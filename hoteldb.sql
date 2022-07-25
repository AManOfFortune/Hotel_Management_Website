-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Jan 2022 um 21:49
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `hoteldb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `text_content` text NOT NULL,
  `thumbnail_path` varchar(300) DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`news_id`, `title`, `text_content`, `thumbnail_path`, `datetime`, `active`) VALUES
(15, 'Covid regulations', '</p><h2>General</h2><p>\r\nWe respectfully ask all guests to wear FFP2-Masks everywhere in our facilities except their rooms. Anyone who does not comply will be forceably removed.\r\n\r\nLink to </p><a href=\'http://orf.at\'>ORF.at</a><p> for current rules.', NULL, '2022-01-17 22:35:25', 1),
(17, 'Check out these beautiful landscape pictures!', '</p><h1>Credit</h1><p>\r\n\r\nAll credits go to the following photographers: \r\n\r\n</p><ul><li>Max Mustermann</li><li>Sam Sample</li><li>Tom Test</li></ul><p>\r\n\r\n</p><h3>Make sure to check them out on social media!</h3><p>', '/Semesterprojekt/pictures/thumbnails/bastei-bridge-3014467_1920_t.jpg', '2022-01-19 16:13:25', 1),
(19, 'We have culinary news!', '</p><h2>New Buffet!</h2><p>\r\n\r\nFrom now on the buffet will have a vegan menu too! We also change up the menu every day, so check it out!\r\n\r\n</p><h2>New Opening Hours in our bar!</h2><p>\r\n\r\nOur bar opens from </p><ul>\r\n<li>Weekdays: 10:00 a.m. - 21:00 p.m.</li>\r\n<li>Weekends: 10:00 a.m. - 03:00 a.m</li></ul><p>', '/Semesterprojekt/pictures/thumbnails/bar_t.jpg', '2022-01-19 21:36:49', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `anrede` char(1) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `plz` varchar(20) NOT NULL,
  `ort` varchar(150) NOT NULL,
  `straße` varchar(200) NOT NULL,
  `hausnummer` varchar(10) NOT NULL,
  `kommentar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `person`
--

INSERT INTO `person` (`person_id`, `anrede`, `vorname`, `nachname`, `plz`, `ort`, `straße`, `hausnummer`, `kommentar`) VALUES
(44, 'm', 'Max', 'Mustermann', '1234', 'Musterstadt', 'Musterweg', '1', 'Musterkommentar'),
(50, 'm', 'Sam', 'Sample', '134', 'asdfa', 'asdfas', '45', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pictures`
--

CREATE TABLE `pictures` (
  `picture_id` int(11) NOT NULL,
  `picture_path` varchar(300) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `pictures`
--

INSERT INTO `pictures` (`picture_id`, `picture_path`, `news_id`, `ticket_id`) VALUES
(16, '../../uploads/news/bastei-bridge-3014467_1920.jpg', 17, NULL),
(17, '../../uploads/news/rocks-316748_1280.jpg', 17, NULL),
(18, '../../uploads/news/scotland-540119_1920.jpg', 17, NULL),
(19, '../../uploads/news/stonehenge-2326750_1920.jpg', 17, NULL),
(20, '../../uploads/news/summer-4074534_1920.jpg', 17, NULL),
(21, '../../uploads/news/sweden-1947001_1920.jpg', 17, NULL),
(30, '../../uploads/hoteladmin/Toilette-nicht-nutzbar.jpg', NULL, 90),
(31, '../../uploads/hoteladmin/tür.jpg', NULL, 92),
(35, '../../uploads/news/bar.jpg', 19, NULL),
(36, '../../uploads/news/food-of-the-hotel.jpg', 19, NULL),
(37, '../../uploads/news/more-food.jpg', 19, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `comment` varchar(4096) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'offen',
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tickets`
--

INSERT INTO `tickets` (`id`, `title`, `file_path`, `comment`, `user_id`, `status`, `datetime`) VALUES
(90, 'Klo kaputt', '/Semesterprojekt/pictures/thumbnails/Toilette-nicht-nutzbar_t.jpg', 'Zimmer 407 im Bad', 18, 'offen', '2022-01-19 21:19:39'),
(92, 'Tür quietscht', '/Semesterprojekt/pictures/thumbnails/tür_t.jpg', 'Sehr laut und störend', 18, 'offen', '2022-01-19 21:23:09');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `rolle` varchar(16) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `email`, `rolle`, `person_id`, `active`) VALUES
(18, 'hoteladmin', '8eecd075d9acc294a0810cbe31fccff82bf448f58a78586f2c44c66306666fb3', 'hoteladmin@mail.com', 'admin', NULL, 1),
(32, 'hoteltechnician', '60de50577ee9ea5829aa2561f3762cafbcc90f03be529a3e303f0b2b09c538e6', 'technik@hotelverwaltung.at', 'technician', NULL, 1),
(35, 'hoteluser', '25d41342040a12d8fa575a292a3550887c2255029f57781e43838acf7f08875f', 'maxmustermann@mustermail.com', 'user', 44, 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indizes für die Tabelle `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`);

--
-- Indizes für die Tabelle `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`picture_id`),
  ADD KEY `fk_pictures_news` (`news_id`),
  ADD KEY `fk_pictures_tickets` (`ticket_id`);

--
-- Indizes für die Tabelle `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `myConst` (`user_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `fk_user_person` (`person_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT für Tabelle `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT für Tabelle `pictures`
--
ALTER TABLE `pictures`
  MODIFY `picture_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `fk_pictures_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`news_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pictures_tickets` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `myConst` FOREIGN KEY (`user_id`) REFERENCES `user` (`userid`);

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
