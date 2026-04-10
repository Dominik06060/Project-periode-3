
CREATE TABLE `account` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int DEFAULT NULL
);

INSERT INTO `account` (`id`, `email`, `password`, `role`) VALUES
(9, 'kelvin@kelvin.nl', '$2y$12$w2fuXiPg1m2jC.C9BCCB5ebeEPNUcwxVp2StqdFJa9y62xwwmfKWK', NULL),
(10, 'cassandra@cassandra.nl', '$2y$12$pVGqaOKe9t0QZZozeub4ueghtgx09JEKWb/ohSPhh6VCucC8Zpplm', NULL);

ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
  
ALTER TABLE `account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

CREATE TABLE `auto` (
  `idauto` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typecar` varchar(255) DEFAULT NULL,
  `steering` varchar(255) DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  `gasoline` int DEFAULT NULL,
  `prijs` int DEFAULT NULL,
  `foto` longblob NOT NULL
);

ALTER TABLE `auto`
  ADD PRIMARY KEY (`idauto`);

ALTER TABLE `auto`
  MODIFY `idauto` int NOT NULL AUTO_INCREMENT;

CREATE TABLE `verhuur` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `auto_id` int NOT NULL,
  `beginverhuur` date NOT NULL,
  `eindverhuur` date NOT NULL,
  `prijs` int NOT NULL,
  `aangemaakt_op` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `verhuur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_verhuur_account` (`account_id`),
  ADD KEY `idx_verhuur_auto` (`auto_id`);

ALTER TABLE `verhuur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `verhuur`
  ADD CONSTRAINT `fk_verhuur_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_verhuur_auto` FOREIGN KEY (`auto_id`) REFERENCES `auto` (`idauto`) ON DELETE CASCADE;
