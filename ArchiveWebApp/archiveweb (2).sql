-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 07 sep. 2025 à 01:27
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `archiveweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `target_user_id` int(11) DEFAULT NULL,
  `action_date` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `document_id`, `target_user_id`, `action_date`, `ip_address`, `details`) VALUES
(1, 3, 'CONNEXION', NULL, NULL, '2025-09-06 17:32:00', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(2, 3, 'CONNEXION', NULL, NULL, '2025-09-06 19:06:51', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(3, 3, 'CONNEXION', NULL, NULL, '2025-09-06 19:08:15', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(6, 4, 'CONNEXION', NULL, NULL, '2025-09-06 19:09:56', '::1', 'Connexion de l\'utilisateur: anelka@rpi.com'),
(7, 4, 'CONNEXION', NULL, NULL, '2025-09-06 19:22:35', '::1', 'Connexion de l\'utilisateur: anelka@rpi.com'),
(8, 4, 'CONNEXION', NULL, NULL, '2025-09-06 19:38:49', '::1', 'Connexion de l\'utilisateur: anelka@rpi.com'),
(9, 3, 'CONNEXION', NULL, NULL, '2025-09-06 19:39:25', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(10, 3, 'CONNEXION', NULL, NULL, '2025-09-06 20:08:29', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(11, 3, 'CONNEXION', NULL, NULL, '2025-09-06 20:24:44', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(12, 3, 'CONNEXION', NULL, NULL, '2025-09-06 20:25:01', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(13, 2, 'CONNEXION', NULL, NULL, '2025-09-06 20:38:45', '::1', 'Connexion de l\'utilisateur: franck@rpi.com'),
(14, 2, 'CONNEXION', NULL, NULL, '2025-09-06 20:46:44', '::1', 'Connexion de l\'utilisateur: franck@rpi.com'),
(15, 3, 'CONNEXION', NULL, NULL, '2025-09-06 20:47:45', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(16, 4, 'CONNEXION', NULL, NULL, '2025-09-06 21:20:20', '::1', 'Connexion de l\'utilisateur: anelka@rpi.com'),
(17, 3, 'CONNEXION', NULL, NULL, '2025-09-06 21:34:55', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(18, 3, 'CONNEXION', NULL, NULL, '2025-09-06 21:46:38', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(19, 3, 'CONNEXION', NULL, NULL, '2025-09-06 22:50:29', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(20, 2, 'CONNEXION', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Connexion de l\'utilisateur: admin@rpi.com'),
(21, 2, 'AJOUT_DOCUMENT', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Ajout du document \'Loi sur la protection des données\' (Type: lois)'),
(22, 2, 'MODIFICATION_DOCUMENT', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Modification du document \'Décret présidentiel 2024\' (Type: décrets)'),
(23, 2, 'CONSULTATION_DOCUMENT', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Consultation du document \'Arrêté ministériel\' (Type: arrêtés)'),
(24, 2, 'AJOUT_UTILISATEUR', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Ajout de l\'utilisateur: user@example.com'),
(25, 2, 'MODIFICATION_UTILISATEUR', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Modification de l\'utilisateur: user@example.com'),
(26, 2, 'CHANGEMENT_STATUT_UTILISATEUR', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Changement de statut de l\'utilisateur: user@example.com (Nouveau statut: bloque)'),
(27, 2, 'DÉCONNEXION', NULL, NULL, '2025-09-06 22:58:02', 'unknown', 'Déconnexion de l\'utilisateur: admin@rpi.com'),
(28, 3, 'CONNEXION', NULL, NULL, '2025-09-06 23:00:42', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(29, 3, 'CONNEXION', NULL, NULL, '2025-09-06 23:06:30', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(31, 5, 'CONNEXION', NULL, NULL, '2025-09-06 23:07:54', '::1', 'Connexion de l\'utilisateur: darrick@rpi.com'),
(32, 2, 'CONNEXION', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Connexion de l\'utilisateur: admin@rpi.com'),
(33, 2, 'AJOUT_DOCUMENT', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Ajout du document \'Loi sur la protection des données\' (Type: lois)'),
(34, 2, 'MODIFICATION_DOCUMENT', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Modification du document \'Décret présidentiel 2024\' (Type: décrets)'),
(35, 2, 'CONSULTATION_DOCUMENT', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Consultation du document \'Arrêté ministériel\' (Type: arrêtés)'),
(36, 2, 'AJOUT_UTILISATEUR', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Ajout de l\'utilisateur: user@example.com'),
(37, 2, 'MODIFICATION_UTILISATEUR', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Modification de l\'utilisateur: user@example.com'),
(38, 2, 'CHANGEMENT_STATUT_UTILISATEUR', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Changement de statut de l\'utilisateur: user@example.com (Nouveau statut: bloque)'),
(39, 2, 'DÉCONNEXION', NULL, NULL, '2025-09-06 23:08:42', 'unknown', 'Déconnexion de l\'utilisateur: admin@rpi.com'),
(40, 5, 'CONNEXION', NULL, NULL, '2025-09-06 23:11:02', '::1', 'Connexion de l\'utilisateur: darrick@rpi.com'),
(42, 3, 'CONNEXION', NULL, NULL, '2025-09-06 23:11:55', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(43, 3, 'CONSULTATION_DOCUMENT', 13, NULL, '2025-09-06 23:16:51', '::1', 'Consultation du document \'RPI-PAD\' (Type: résolution)'),
(45, 3, 'AJOUT_DOCUMENT', 53, NULL, '2025-09-06 23:26:36', '::1', 'Ajout du document \'COMMISSAIRE\' (Type: lois)'),
(46, 3, 'CONSULTATION_DOCUMENT', 20, NULL, '2025-09-06 23:27:01', '::1', 'Consultation du document \'BALAMBO\' (Type: décrets)'),
(47, 3, 'AJOUT_DOCUMENT', 54, NULL, '2025-09-06 23:27:34', '::1', 'Ajout du document \'COMMISSAIRE\' (Type: convention)'),
(48, 4, 'CONNEXION', NULL, NULL, '2025-09-06 23:37:31', '::1', 'Connexion de l\'utilisateur: anelka@rpi.com'),
(49, 4, 'CONSULTATION_DOCUMENT', 54, NULL, '2025-09-06 23:39:19', '::1', 'Consultation du document \'COMMISSAIRE\' (Type: convention)'),
(50, 5, 'CONNEXION', NULL, NULL, '2025-09-06 23:39:55', '::1', 'Connexion de l\'utilisateur: darrick@rpi.com'),
(51, 5, 'CONNEXION', NULL, NULL, '2025-09-06 23:44:02', '::1', 'Connexion de l\'utilisateur: darrick@rpi.com'),
(52, 3, 'CONNEXION', NULL, NULL, '2025-09-07 00:04:36', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(53, 3, 'CONNEXION', NULL, NULL, '2025-09-07 00:05:03', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(54, 3, 'MODIFICATION_DOCUMENT', 34, NULL, '2025-09-07 00:05:55', '::1', 'Modification du document \'BALAMBO\' (Type: ordonnance)'),
(55, 3, 'CONNEXION', NULL, NULL, '2025-09-07 00:17:55', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com'),
(56, 3, 'CONNEXION', NULL, NULL, '2025-09-07 00:18:22', '::1', 'Connexion de l\'utilisateur: k2m@rpi.com');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `url_fichier` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `add_date` datetime NOT NULL,
  `add_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `delete_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `titre`, `type`, `categorie`, `url_fichier`, `description`, `add_date`, `add_by`, `update_by`, `delete_by`, `is_deleted`, `deleted_at`) VALUES
(13, 'RPI-PAD', 'résolution', 'direction générale', '../uploads/68b86b5218295_RPI.jpg', '', '2025-09-03 17:22:42', NULL, NULL, NULL, 0, NULL),
(20, 'BALAMBO', 'décrets', 'présidentiel', '../uploads/68b970c771638_BALAMBO.docx', '', '2025-09-04 11:58:15', NULL, NULL, NULL, 0, NULL),
(23, 'logo iai', 'note de service', 'présidentiel', '../uploads/68b9723d05150_logo iai.png', '', '2025-09-04 12:04:29', NULL, NULL, NULL, 0, NULL),
(24, 'BALAMBO', 'note de service', 'ministériel', '../uploads/68b974c2766a5_BALAMBO.pdf', '', '2025-09-04 12:15:14', NULL, NULL, NULL, 0, NULL),
(25, 'COMMISSAIRE', 'note de service', 'présidentiel', '../uploads/68b9772fc515f_COMMISSAIRE.pdf', '', '2025-09-04 12:25:35', NULL, NULL, NULL, 0, NULL),
(32, 'FICHE DE NOTATION FINAL', 'décision', 'présidentiel', '../uploads/68b98151c1349_FICHE DE NOTATION FINAL.pdf', 'Modif attribué', '2025-09-04 13:08:49', NULL, NULL, NULL, 0, NULL),
(34, 'BALAMBO', 'ordonnance', 'présidentiel', '../uploads/68b983ce4cbbd_BALAMBO.pdf', '', '2025-09-04 13:19:26', NULL, NULL, NULL, 0, NULL),
(36, 'COMMISSAIRE', 'arrêtés', 'ministériel', '../uploads/68b987284465b_COMMISSAIRE.pdf', '', '2025-09-04 13:33:44', NULL, NULL, NULL, 0, NULL),
(42, 'DELEGUE FINAL', 'décision', 'ministériel', '../uploads/68b98cd45c4fa_DELEGUE FINAL.pdf', '', '2025-09-04 13:57:56', NULL, NULL, NULL, 0, NULL),
(43, 'BALAMBO', 'décision', 'gouvernement', '../uploads/68b98de1a728f_BALAMBO.pdf', '', '2025-09-04 14:02:25', NULL, NULL, NULL, 0, NULL),
(44, 'BALAMBO', 'note de service', 'présidentiel', '../uploads/68b98e291efc9_BALAMBO.pdf', '', '2025-09-04 14:03:37', NULL, NULL, NULL, 0, NULL),
(45, 'BALAMBO', 'décision', 'présidentiel', '../uploads/68b991a32e543_BALAMBO.pdf', '', '2025-09-04 14:18:27', NULL, NULL, NULL, 0, NULL),
(46, 'D_de stage academique UCB', 'note de service', 'direction générale', '../uploads/68b991c786568_D_de stage academique UCB.pdf', '', '2025-09-04 14:19:03', NULL, NULL, NULL, 0, NULL),
(47, 'BALAMBO', 'note de service', 'présidentiel', '../uploads/68b9925deb8c7_BALAMBO.pdf', '', '2025-09-04 14:21:33', NULL, NULL, NULL, 0, NULL),
(49, 'BALAMBO', 'résolution', 'présidentiel', '../uploads/68b9998a05e69_BALAMBO.pdf', '', '2025-09-04 14:52:10', NULL, NULL, NULL, 0, NULL),
(50, 'archivage', 'décrets', 'présidentiel', '../uploads/68baafafcbaf0_archivage.zip', 'Description Test', '2025-09-05 10:38:55', NULL, NULL, NULL, 0, NULL),
(51, 'RDV KANA', 'décrets', 'ministériel', '../uploads/68bcacceb10cf_RDV KANA.pdf', '', '2025-09-06 22:51:10', NULL, NULL, NULL, 0, NULL),
(53, 'COMMISSAIRE', 'lois', '--', '../uploads/68bcb51cbaefa_COMMISSAIRE.pdf', '', '2025-09-06 23:26:36', NULL, NULL, NULL, 0, NULL),
(54, 'COMMISSAIRE', 'convention', '--', '../uploads/68bcb556aa461_COMMISSAIRE.pdf', '', '2025-09-06 23:27:34', NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'actif',
  `add_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `delete_at` datetime DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `delete_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `statut`, `add_date`, `update_date`, `delete_at`, `add_by`, `delete_by`, `update_by`) VALUES
(2, 'DJOKO', 'Franck', 'franck@rpi.com', '123456', 'admin', 'actif', '2025-09-06 16:32:47', NULL, NULL, NULL, NULL, NULL),
(3, 'KOUAN', 'Murielle', 'k2m@rpi.com', 'K2M10241234', 'admin', 'actif', '2025-09-06 16:34:06', NULL, NULL, NULL, NULL, NULL),
(4, 'DONGMO', 'Anelka', 'anelka@rpi.com', '10241234', 'user', 'actif', '2025-09-06 19:09:20', NULL, NULL, NULL, NULL, NULL),
(5, 'FOUODO', 'Darrick', 'darrick@rpi.com', '987654', 'admin', 'actif', '2025-09-06 23:07:36', NULL, NULL, NULL, NULL, NULL),
(6, 'TCHOMO', 'Ingrid', 'tkpi@rpi.com', '16092009', 'user', 'actif', '2025-09-07 00:21:47', NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `target_user_id` (`target_user_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_by` (`add_by`),
  ADD KEY `update_by` (`update_by`),
  ADD KEY `delete_by` (`delete_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_by` (`add_by`),
  ADD KEY `delete_by` (`delete_by`),
  ADD KEY `update_by` (`update_by`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `audit_logs_ibfk_3` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`add_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`update_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`delete_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
