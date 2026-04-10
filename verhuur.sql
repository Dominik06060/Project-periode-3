-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 10 apr 2026 om 09:42
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verhuur`
--

CREATE TABLE `verhuur` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `auto_id` int(11) NOT NULL,
  `beginverhuur` date NOT NULL,
  `eindverhuur` date NOT NULL,
  `prijs` int(11) NOT NULL,
  `aangemaakt_op` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `verhuur`
--

INSERT INTO `verhuur` (`id`, `account_id`, `auto_id`, `beginverhuur`, `eindverhuur`, `prijs`, `aangemaakt_op`) VALUES
(5, 19, 2, '2026-04-15', '2026-04-24', 900, '2026-04-09 12:28:34'),
(6, 19, 1, '2026-04-16', '2026-04-30', 3735, '2026-04-10 06:50:53'),
(7, 19, 4, '2026-04-10', '2026-04-17', 1440, '2026-04-10 07:29:06');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `verhuur`
--
ALTER TABLE `verhuur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_verhuur_account` (`account_id`),
  ADD KEY `idx_verhuur_auto` (`auto_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `verhuur`
--
ALTER TABLE `verhuur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `verhuur`
--
ALTER TABLE `verhuur`
  ADD CONSTRAINT `fk_verhuur_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_verhuur_auto` FOREIGN KEY (`auto_id`) REFERENCES `auto` (`idauto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
