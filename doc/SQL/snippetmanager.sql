CREATE DATABASE snippetmanager;

USE snippetmanager;

-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 12. Jul 2018 um 18:19
-- Server-Version: 10.1.33-MariaDB
-- PHP-Version: 7.1.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `snippetmanager`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `CATEGORY_ID` int(11) NOT NULL,
  `CATEGORY_NAME` varchar(255) NOT NULL,
  `CATEGORY_DESCRIPTION` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`CATEGORY_ID`, `CATEGORY_NAME`, `CATEGORY_DESCRIPTION`) VALUES
(1, 'PHP', 'The best scripting language on this planet.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `snippet`
--

CREATE TABLE `snippet` (
  `SNIPPET_ID` int(11) NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `SNIPPET_NAME` varchar(255) NOT NULL,
  `SNIPPET_TEXT` longtext,
  `SNIPPET_TAGS` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `snippet`
--

INSERT INTO `snippet` (`SNIPPET_ID`, `CATEGORY_ID`, `SNIPPET_NAME`, `SNIPPET_TEXT`, `SNIPPET_TAGS`) VALUES
(1, 1, 'Exit', 'exit;', 'anwendung schließen'),
(2, 1, 'Exit', 'exit;', 'anwendung schließen'),
(3, 1, 'Exit', 'exit;', 'anwendung schließen'),
(4, 1, 'Exit', 'exit;', 'anwendung schließen'),
(5, 1, 'Exit', 'exit;', 'anwendung schließen'),
(6, 1, 'Exit', 'exit;', 'anwendung schließen'),
(7, 1, 'Exit', 'exit;', 'anwendung schließen'),
(8, 1, 'Exit', 'exit;', 'anwendung schließen'),
(9, 1, 'Exit', 'exit;', 'anwendung schließen'),
(10, 1, 'Exit', 'exit;', 'anwendung schließen'),
(11, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(12, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(13, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(14, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(15, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(16, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(17, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(18, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(19, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(20, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string'),
(21, 1, 'Crawl Webpage', 'function getPage($url) {\r\n    $url = parse_url($url);\r\n    echo \"hi\";\r\n\r\n    return file_get_contents($url);\r\n}', 'file_get_contents webseite crawlen laden echo string');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CATEGORY_ID`);

--
-- Indizes für die Tabelle `snippet`
--
ALTER TABLE `snippet`
  ADD PRIMARY KEY (`SNIPPET_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `snippet`
--
ALTER TABLE `snippet`
  MODIFY `SNIPPET_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
