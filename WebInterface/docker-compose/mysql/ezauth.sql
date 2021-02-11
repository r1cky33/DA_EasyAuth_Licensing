-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Jan 2021 um 21:28
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ezauth`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `licenses`
--

CREATE TABLE `licenses` (
  `id` int(11) NOT NULL,
  `license` varchar(255) DEFAULT NULL,
  `activation_date` datetime DEFAULT NULL,
  `duration` datetime DEFAULT NULL,
  `hwid` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `licenses`
--

/*INSERT INTO `licenses` (`id`, `license`, `activation_date`, `duration`, `hwid`, `product_id`, `state`) VALUES
(1, 'testlicense1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', 0, 0);*/

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description_title` varchar(1024) DEFAULT NULL,
  `description_text` varchar(1024) DEFAULT NULL,
  `architecture` varchar(128) DEFAULT NULL,
  `state` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`product_id`, `user_id`, `name`, `description_title`, `description_text`, `architecture`, `state`) VALUES
(8, 14, '543be6f438a186957b07ae1bf304649c', 'test title', 'test description', '1', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `hwid` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `hwid`, `token`, `is_active`) VALUES
(14, 'PlsWorkNow', 'j.schwarzinger@htlkrems.at', '$2y$10$ECZbDH6fkV2.Z9UWuEFeZuzUZXdtpOCSxKsrJmvxQ1MQPwYmLXlqm', NULL, '5fc9167a70b299eb014d2030411ca3f5', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `licenses`
--
ALTER TABLE `licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `licenses`
--
ALTER TABLE `licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
