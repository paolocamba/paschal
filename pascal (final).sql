-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2024 at 09:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pascal`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('Admin','User') NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_type`, `action`, `details`, `created_at`) VALUES
(35, 2, '', 'Registered', 'Registered', '2024-12-07 09:32:20'),
(36, 2, '', 'Registered', 'Registered', '2024-12-07 09:36:00'),
(37, 2, '', 'Registered', 'Registered', '2024-12-07 11:47:29'),
(38, 2, '', 'Registered', 'Registered', '2024-12-08 05:44:22'),
(39, 2, '', 'Registered', 'Registered', '2024-12-08 05:49:34'),
(40, 2, '', 'Registered', 'Registered', '2024-12-08 05:55:28'),
(41, 2, '', 'Logged in', 'Logged in', '2024-12-08 10:55:50'),
(42, 2, '', 'Logged in', 'Logged in', '2024-12-08 10:56:16'),
(43, 2, '', 'Logged in', 'Logged in', '2024-12-08 10:56:28'),
(44, 2, '', 'Logged in', 'Logged in', '2024-12-08 10:58:06'),
(45, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 11:01:18'),
(46, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 11:03:32'),
(47, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 11:05:02'),
(48, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 11:05:35'),
(49, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 11:06:25'),
(50, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 11:13:30'),
(51, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:42:15'),
(52, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:42:20'),
(53, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:42:42'),
(54, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:43:12'),
(55, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:58:17'),
(56, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:58:21'),
(57, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:58:25'),
(58, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:59:22'),
(59, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 13:59:26'),
(60, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:00:38'),
(61, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:00:48'),
(62, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:01'),
(63, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:05'),
(64, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:10'),
(65, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:29'),
(66, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:31'),
(67, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:44'),
(68, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:02:50'),
(69, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:02:56'),
(70, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:03:22'),
(71, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:03:26'),
(72, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:06:32'),
(73, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:06:40'),
(74, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:07:14'),
(75, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:07:16'),
(76, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:12:25'),
(77, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:12:30'),
(78, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:12:36'),
(79, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:12:50'),
(80, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:14:58'),
(81, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:17:41'),
(82, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:17:48'),
(83, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:17:52'),
(84, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:19:01'),
(85, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:19:47'),
(86, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:20:33'),
(87, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:20:43'),
(88, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:20:43'),
(89, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:22:56'),
(90, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:23:07'),
(91, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:23:15'),
(92, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:23:42'),
(93, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:25:00'),
(94, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:25:26'),
(95, 2, '', 'Logged in', 'Logged in', '2024-12-08 14:25:50'),
(96, 3, '', 'Registered', 'Registered', '2024-12-08 14:31:11'),
(97, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:32:19'),
(98, 3, '', 'Logged in', 'Logged in', '2024-12-08 14:32:26'),
(99, 3, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:32:41'),
(100, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:33:03'),
(101, 4, '', 'Registered', 'Registered', '2024-12-08 14:34:53'),
(102, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:36:23'),
(103, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:36:28'),
(104, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:40:56'),
(105, 4, '', 'Logged in', 'Logged in', '2024-12-08 14:41:02'),
(106, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:41:09'),
(107, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:41:15'),
(108, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:41:57'),
(109, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:42:03'),
(110, 4, '', 'Logged in', 'Logged in', '2024-12-08 14:42:08'),
(111, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:42:17'),
(112, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:42:21'),
(113, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:44:50'),
(114, 4, '', 'Logged in', 'Logged in', '2024-12-08 14:44:56'),
(115, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:45:05'),
(116, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:45:11'),
(117, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:47:00'),
(118, 4, '', 'Logged in', 'Logged in', '2024-12-08 14:47:03'),
(119, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:47:13'),
(120, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:47:18'),
(121, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:48:57'),
(122, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:49:00'),
(123, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:52:15'),
(124, 4, '', 'Logged in', 'Logged in', '2024-12-08 14:52:20'),
(125, 4, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 14:52:31'),
(126, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 14:52:36'),
(127, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-08 15:00:01'),
(128, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-08 15:00:05'),
(129, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 03:43:42'),
(130, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 03:43:52'),
(131, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 03:44:02'),
(132, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 03:44:31'),
(133, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 03:44:34'),
(134, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 03:45:49'),
(135, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 03:46:15'),
(136, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 03:46:18'),
(137, 2, '', 'Registered', 'Registered', '2024-12-09 05:38:29'),
(138, 3, '', 'Registered', 'Registered', '2024-12-09 05:54:06'),
(139, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 06:21:49'),
(140, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 06:21:57'),
(141, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 06:22:01'),
(142, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 06:22:05'),
(143, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 06:22:11'),
(144, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 06:22:39'),
(145, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 06:22:41'),
(146, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 07:43:03'),
(147, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 07:43:08'),
(148, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 07:43:39'),
(149, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 07:43:51'),
(150, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 12:40:11'),
(151, 2, '', 'Logged in', 'Logged in', '2024-12-09 12:40:15'),
(152, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 12:40:24'),
(153, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 12:40:44'),
(154, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-09 13:11:46'),
(155, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-09 13:11:51'),
(156, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 10:01:16'),
(157, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 10:01:19'),
(158, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-10 10:01:21'),
(159, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 11:37:23'),
(160, 2, '', 'Logged in', 'Logged in', '2024-12-10 11:37:30'),
(161, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 12:09:10'),
(162, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-10 12:09:13'),
(163, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 12:11:28'),
(164, 2, '', 'Logged in', 'Logged in', '2024-12-10 12:11:33'),
(165, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 12:17:02'),
(166, 2, '', 'Logged in', 'Logged in', '2024-12-10 12:17:22'),
(167, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-10 12:22:45'),
(168, 3, '', 'Logged in', 'Logged in', '2024-12-10 12:22:53'),
(169, 2, '', 'Logged in', 'Logged in', '2024-12-10 12:29:20'),
(170, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-10 12:51:13'),
(171, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-10 12:53:31'),
(172, 2, '', 'Logged in', 'Logged in', '2024-12-10 14:57:43'),
(173, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-10 14:59:23'),
(174, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 03:26:57'),
(175, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 03:27:46'),
(176, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 06:18:44'),
(177, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 06:51:17'),
(178, 2, '', 'Logged in', 'Logged in', '2024-12-11 07:42:28'),
(179, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 07:49:05'),
(180, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-11 08:56:11'),
(181, 2, '', 'Logged in', 'Logged in', '2024-12-11 10:11:56'),
(182, 2, '', 'Logged in', 'Logged in', '2024-12-11 16:11:51'),
(183, 2, '', 'Logged in', 'Logged in', '2024-12-12 09:26:28'),
(184, 2, '', 'Logged in', 'Logged in', '2024-12-12 09:27:05'),
(185, 2, '', 'Logged in', 'Logged in', '2024-12-12 10:18:23'),
(186, 2, '', 'Logged in', 'Logged in', '2024-12-12 14:09:26'),
(187, 2, '', 'Logged in', 'Logged in', '2024-12-12 15:19:06'),
(188, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-12 15:25:49'),
(189, 2, '', 'Logged in', 'Logged in', '2024-12-12 15:25:53'),
(190, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-12 15:29:57'),
(191, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-12 15:30:04'),
(192, 2, '', 'Logged in', 'Logged in', '2024-12-12 15:35:45'),
(193, 2, '', 'Logged in', 'Logged in', '2024-12-12 16:20:38'),
(194, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-12 16:22:05'),
(195, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-12 16:22:10'),
(196, 2, '', 'Logged in', 'Logged in', '2024-12-12 16:42:01'),
(197, 2, '', 'Logged in', 'Logged in', '2024-12-12 16:42:16'),
(198, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 06:32:45'),
(199, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-13 06:32:49'),
(200, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 06:33:17'),
(201, 2, '', 'Logged in', 'Logged in', '2024-12-13 06:33:21'),
(202, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 10:10:48'),
(203, 2, '', 'Logged in', 'Logged in', '2024-12-13 10:10:50'),
(204, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 12:49:31'),
(205, 2, '', 'Logged in', 'Logged in', '2024-12-13 12:49:36'),
(206, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 13:45:06'),
(207, 2, '', 'Logged in', 'Logged in', '2024-12-13 13:45:08'),
(208, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-13 14:23:13'),
(209, 2, '', 'Logged in', 'Logged in', '2024-12-13 14:23:15'),
(210, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 07:26:16'),
(211, 2, '', 'Logged in', 'Logged in', '2024-12-15 07:27:07'),
(212, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 07:43:40'),
(213, 2, '', 'Logged in', 'Logged in', '2024-12-15 07:43:43'),
(214, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 10:20:48'),
(215, 2, '', 'Logged in', 'Logged in', '2024-12-15 10:20:52'),
(216, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 11:52:29'),
(217, 2, '', 'Logged in', 'Logged in', '2024-12-15 11:52:32'),
(218, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:20:07'),
(219, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:21:27'),
(220, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:25:10'),
(221, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:29:17'),
(222, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:48:11'),
(223, 2, '', 'Logged in', 'Logged in', '2024-12-15 12:48:16'),
(224, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:54:01'),
(225, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:58:16'),
(226, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:58:56'),
(227, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 12:59:49'),
(228, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 13:30:42'),
(229, 2, '', 'Logged in', 'Logged in', '2024-12-15 13:30:46'),
(230, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 13:36:55'),
(231, 2, '', 'Logged in', 'Logged in', '2024-12-15 13:36:59'),
(232, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 13:49:44'),
(233, 2, '', 'Logged in', 'Logged in', '2024-12-15 13:49:47'),
(234, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 14:02:58'),
(235, 2, '', 'Logged in', 'Logged in', '2024-12-15 14:03:01'),
(236, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 15:05:12'),
(237, 2, '', 'Logged in', 'Logged in', '2024-12-15 15:05:14'),
(238, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-15 15:11:16'),
(239, 2, '', 'Logged in', 'Logged in', '2024-12-15 15:11:18'),
(240, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 12:32:59'),
(241, 2, '', 'Logged in', 'Logged in', '2024-12-20 12:33:03'),
(242, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 14:17:46'),
(243, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 14:22:05'),
(244, 4, '', 'Logged in', 'Logged in', '2024-12-20 14:22:15'),
(245, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 14:46:46'),
(246, 2, '', 'Logged in', 'Logged in', '2024-12-20 14:46:51'),
(247, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 14:59:03'),
(248, 2, '', 'Logged in', 'Logged in', '2024-12-20 14:59:06'),
(249, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 15:25:15'),
(250, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-20 15:25:20'),
(251, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 15:26:33'),
(252, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 15:26:47'),
(253, 2, '', 'Logged in', 'Logged in', '2024-12-20 15:26:53'),
(254, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 15:50:00'),
(255, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-20 15:50:05'),
(256, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 15:50:29'),
(257, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-20 15:50:37'),
(258, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-20 15:51:26'),
(259, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 16:21:36'),
(260, 2, '', 'Logged in', 'Logged in', '2024-12-20 16:21:42'),
(261, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-20 16:33:24'),
(262, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-20 16:33:28'),
(263, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 09:25:04'),
(264, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-21 09:25:08'),
(265, 2, '', 'Logged in', 'Logged in', '2024-12-21 09:37:59'),
(266, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 10:13:52'),
(267, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 10:31:12'),
(268, 2, '', 'Logged in', 'Logged in', '2024-12-21 10:31:18'),
(269, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 11:21:16'),
(270, 2, '', 'Logged in', 'Logged in', '2024-12-21 11:21:19'),
(271, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 12:59:32'),
(272, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 12:59:39'),
(273, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 12:59:39'),
(274, 2, '', 'Logged in', 'Logged in', '2024-12-21 12:59:43'),
(275, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 13:12:46'),
(276, 2, '', 'Logged in', 'Logged in', '2024-12-21 13:12:49'),
(277, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 13:24:39'),
(278, 2, '', 'Logged in', 'Logged in', '2024-12-21 13:24:59'),
(279, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 14:00:58'),
(280, 2, '', 'Logged in', 'Logged in', '2024-12-21 14:01:14'),
(281, 2, '', 'Logged in', 'Logged in', '2024-12-21 14:01:32'),
(282, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 14:09:49'),
(283, 2, '', 'Logged in', 'Logged in', '2024-12-21 14:10:06'),
(284, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 15:55:26'),
(285, 2, '', 'Logged in', 'Logged in', '2024-12-21 15:55:29'),
(286, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 16:05:42'),
(287, 2, '', 'Logged in', 'Logged in', '2024-12-21 16:05:44'),
(288, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-21 16:11:15'),
(289, 2, '', 'Logged in', 'Logged in', '2024-12-21 16:11:17'),
(290, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 08:41:31'),
(291, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 08:41:36'),
(292, 2, '', 'Logged in', 'Logged in', '2024-12-22 08:41:41'),
(293, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 10:17:15'),
(294, 2, '', 'Logged in', 'Logged in', '2024-12-22 10:17:18'),
(295, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 10:22:23'),
(296, 2, '', 'Logged in', 'Logged in', '2024-12-22 10:22:52'),
(297, 2, '', 'Logged in', 'Logged in', '2024-12-22 10:23:22'),
(298, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 10:30:42'),
(299, 2, '', 'Logged in', 'Logged in', '2024-12-22 10:30:51'),
(300, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 13:08:54'),
(301, 2, '', 'Logged in', 'Logged in', '2024-12-22 13:08:56'),
(302, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 14:06:30'),
(303, 2, '', 'Logged in', 'Logged in', '2024-12-22 14:13:47'),
(304, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 14:33:59'),
(305, 2, '', 'Logged in', 'Logged in', '2024-12-22 14:34:01'),
(306, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 15:03:52'),
(307, 2, '', 'Logged in', 'Logged in', '2024-12-22 15:04:00'),
(308, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-22 16:26:28'),
(309, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-22 18:06:22'),
(310, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 19:42:59'),
(311, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-22 19:43:02'),
(312, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 19:45:58'),
(313, 2, '', 'Logged in', 'Logged in', '2024-12-22 19:46:01'),
(314, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 19:52:19'),
(315, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-22 19:52:22'),
(316, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-22 20:06:48'),
(317, 2, '', 'Logged in', 'Logged in', '2024-12-22 20:06:51'),
(318, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 07:44:05'),
(319, 2, '', 'Logged in', 'Logged in', '2024-12-23 07:44:11'),
(320, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 08:04:06'),
(321, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 08:21:18'),
(322, 2, '', 'Logged in', 'Logged in', '2024-12-23 08:24:46'),
(323, 3, '', 'Logged in', 'Logged in', '2024-12-23 08:38:04'),
(324, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 10:00:35'),
(325, 3, '', 'Logged in', 'Logged in', '2024-12-23 10:12:35'),
(326, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 11:37:31'),
(327, 2, '', 'Logged in', 'Logged in', '2024-12-23 11:37:33'),
(328, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 13:59:56'),
(329, 4, '', 'Logged in', 'Logged in', '2024-12-23 14:00:40'),
(330, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 14:06:06'),
(331, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 14:06:10'),
(332, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 14:09:27'),
(333, 4, '', 'Logged in', 'Logged in', '2024-12-23 14:09:33'),
(334, 2, '', 'Logged in', 'Logged in', '2024-12-23 14:11:38'),
(335, 3, '', 'Logged in', 'Logged in', '2024-12-23 14:12:04'),
(336, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 14:24:04'),
(337, 4, '', 'Logged in', 'Logged in', '2024-12-23 14:24:10'),
(338, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 14:53:58'),
(339, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 14:54:48'),
(340, 2, '', 'Logged in', 'Logged in', '2024-12-23 14:54:52'),
(341, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 15:12:36'),
(342, 4, '', 'Logged in', 'Logged in', '2024-12-23 15:12:42'),
(343, 2, '', 'Logged in', 'Logged in', '2024-12-23 15:26:33'),
(344, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 15:30:26'),
(345, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 15:30:30'),
(346, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 15:38:59'),
(347, 4, '', 'Logged in', 'Logged in', '2024-12-23 15:39:03'),
(348, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 16:02:35'),
(349, 7, '', 'Logged in', 'Logged in', '2024-12-23 16:02:44'),
(350, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 16:03:54'),
(351, 7, '', 'Logged in', 'Logged in', '2024-12-23 16:03:56'),
(352, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 16:17:43'),
(353, 2, '', 'Logged in', 'Logged in', '2024-12-23 16:18:40'),
(354, 4, '', 'Logged in', 'Logged in', '2024-12-23 16:23:16'),
(355, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 16:38:49'),
(356, 6, '', 'Logged in', 'Logged in', '2024-12-23 16:39:11'),
(357, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 16:41:01'),
(358, 6, '', 'Logged in', 'Logged in', '2024-12-23 16:41:05'),
(359, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 17:12:48'),
(360, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 17:12:53'),
(361, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 17:17:24'),
(362, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-23 17:17:28'),
(363, 2, '', 'Logged in', 'Logged in', '2024-12-23 17:24:55'),
(364, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 17:56:48'),
(365, 4, '', 'Logged in', 'Logged in', '2024-12-23 17:56:55'),
(366, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 17:58:05'),
(367, 7, '', 'Logged in', 'Logged in', '2024-12-23 17:58:10'),
(368, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 18:07:44'),
(369, 5, '', 'Logged in', 'Logged in', '2024-12-23 18:08:02'),
(370, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-23 18:12:14'),
(371, 5, '', 'Logged in', 'Logged in', '2024-12-23 18:12:18'),
(372, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 07:49:50'),
(373, 2, '', 'Logged in', 'Logged in', '2024-12-24 07:53:09'),
(374, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 08:09:55'),
(375, 3, '', 'Logged in', 'Logged in', '2024-12-24 08:10:00'),
(376, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 09:20:41'),
(377, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 09:20:45'),
(378, 2, '', 'Logged in', 'Logged in', '2024-12-24 09:20:49'),
(379, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 09:55:44'),
(380, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-24 09:55:48'),
(381, 8, '', 'Registered', 'Registered', '2024-12-24 09:59:21'),
(382, 2, '', 'Logged in', 'Logged in', '2024-12-24 10:16:36'),
(383, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-24 11:22:18'),
(384, 6, '', 'Logged in', 'Logged in', '2024-12-24 11:22:23'),
(385, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-26 11:05:40'),
(386, 6, '', 'Logged in', 'Logged in', '2024-12-26 11:05:44'),
(387, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-26 17:44:54'),
(388, 5, '', 'Logged in', 'Logged in', '2024-12-26 17:45:04'),
(389, 1, 'Admin', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-26 17:53:34'),
(390, 2, '', 'Logged in', 'Logged in', '2024-12-26 17:53:39'),
(391, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-26 17:59:45'),
(392, 5, '', 'Logged in', 'Logged in', '2024-12-26 17:59:49'),
(393, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 09:29:36'),
(394, 2, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 09:29:37'),
(395, 6, '', 'Logged in', 'Logged in', '2024-12-27 09:29:50'),
(396, 6, '', 'Logged in', 'Logged in', '2024-12-27 09:30:08'),
(397, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 09:39:51'),
(398, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 09:39:56'),
(399, 5, '', 'Logged in', 'Logged in', '2024-12-27 09:39:58'),
(400, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 09:59:39'),
(401, 6, '', 'Logged in', 'Logged in', '2024-12-27 09:59:45'),
(402, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 10:06:44'),
(403, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-27 10:06:47'),
(404, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 11:13:56'),
(405, 5, '', 'Logged in', 'Logged in', '2024-12-27 11:14:13'),
(406, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-27 17:12:13'),
(407, 6, '', 'Logged in via Remember Me', 'Logged in via Remember Me', '2024-12-27 18:17:51'),
(408, 1, 'Admin', 'Logged in', 'Logged in', '2024-12-27 18:17:55'),
(409, 5, '', 'Logged in', 'Logged in', '2024-12-27 18:32:26'),
(410, 4, '', 'Logged in', 'Logged in', '2024-12-27 18:50:35'),
(411, 6, '', 'Logged in', 'Logged in', '2024-12-27 18:52:14'),
(412, 2, '', 'Logged in', 'Logged in', '2024-12-27 18:53:08'),
(413, 7, '', 'Logged in', 'Logged in', '2024-12-27 18:54:22'),
(414, 2, '', 'Logged in', 'Logged in', '2024-12-27 18:55:26'),
(415, 2, '', 'Logged in', 'Logged in', '2024-12-27 20:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `day` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `name`, `image`, `date`, `day`, `created_at`) VALUES
(19, 'Health Seminar', '675868298e516.jpeg', '2024-12-12', 'Thursday', '2024-12-10 15:56:47'),
(20, 'Subsidy', '67586413841d0_sub.jpeg', '2024-12-23', 'Monday', '2024-12-10 15:53:55'),
(21, 'Christmas Party', '6758642270aaa_christmas.jpeg', '2024-12-24', 'Tuesday', '2024-12-10 15:54:10'),
(22, 'Annual General Meeting', '67586442c0fb1_annual.jpeg', '2024-12-18', 'Wednesday', '2024-12-10 15:54:42'),
(23, 'Annual General Assembly', '6758644c2c2ca_ass.jpeg', '2024-12-20', 'Friday', '2024-12-10 15:54:52'),
(24, 'New Year Celebration', '6758645a1f440_new.jpeg', '2024-12-31', 'Tuesday', '2024-12-10 15:55:06'),
(25, 'Valentine\'s Day Event', '6758646fc641b_val.jpeg', '2025-02-14', 'Friday', '2024-12-10 15:55:27'),
(26, 'Community Cleanup', '6758647d03663_com.jpeg', '2024-12-22', 'Sunday', '2024-12-10 15:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `appointmentdate` date DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `serviceID` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `first_name`, `last_name`, `email`, `description`, `appointmentdate`, `user_id`, `serviceID`, `status`) VALUES
(1, 'John', 'Doe', 'chrisdoe@gmail.com', 'Membership Application Payment', '2024-12-10', '2', 11, 'Approved'),
(2, 'Max', 'Collins', 'mitchcollins@gmail.com', 'Membership Application Payment', '2024-12-18', '3', 11, 'Approved'),
(8, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-17', '2', 3, 'Approved'),
(9, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-27', '2', 6, 'Approved'),
(21, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', NULL, 'Approved'),
(22, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', NULL, 'Approved'),
(23, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', 4, 'Approved'),
(24, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-25', '2', 7, 'Approved'),
(25, 'Max', 'Collins', 'mitchcollins@gmail.com', NULL, '2024-12-27', '3', 8, 'Approved'),
(26, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', NULL, 'Approved'),
(28, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', NULL, 'Approved'),
(29, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2024-12-23', '2', NULL, 'Approved'),
(30, 'John', 'Doe', 'chrisdoe@gmail.com', NULL, '2025-01-09', '2', 9, 'Approved'),
(31, 'Jigs', 'Medina', 'jigsmedina1999@gmail.com', 'Membership Application Payment', '2024-12-26', '8', 11, 'Approved'),
(32, 'Jigs', 'Medina', 'jigsmedina1999@gmail.com', NULL, '2024-12-27', '8', NULL, 'Approved'),
(33, 'John', 'Doe', 'chrisdoe@gmail.com', 'Savings Deposit', '2024-12-28', '2', NULL, 'Approved'),
(34, 'John', 'Doe', 'chrisdoe@gmail.com', 'Savings Deposit', '2024-12-28', '2', NULL, 'Approved'),
(35, 'John', 'Doe', 'chrisdoe@gmail.com', 'Space for Rent', '2024-12-31', '2', 4, 'Approved'),
(36, 'John', 'Doe', 'chrisdoe@gmail.com', 'Medical Consultation', '2024-12-30', '2', 5, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `collateral_info`
--

CREATE TABLE `collateral_info` (
  `CollateralID` int(11) NOT NULL,
  `LoanID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `land_title_path` varchar(255) DEFAULT NULL,
  `square_meters` int(11) DEFAULT NULL,
  `type_of_land` enum('Industrial','Residential','Agricultural','Commercial') DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `right_of_way` enum('Yes','No') DEFAULT NULL,
  `has_hospital` enum('Yes','No') DEFAULT 'No',
  `has_school` enum('Yes','No') DEFAULT 'No',
  `has_clinic` enum('Yes','No') DEFAULT 'No',
  `has_church` enum('Yes','No') DEFAULT 'No',
  `has_market` enum('Yes','No') DEFAULT 'No',
  `has_terminal` enum('Yes','No') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collateral_info`
--

INSERT INTO `collateral_info` (`CollateralID`, `LoanID`, `userID`, `land_title_path`, `square_meters`, `type_of_land`, `location_name`, `right_of_way`, `has_hospital`, `has_school`, `has_clinic`, `has_church`, `has_market`, `has_terminal`, `created_at`) VALUES
(2, 60, 3, '../dist/assets/images/proofs/land_title_1735028347.jpg', 400, 'Industrial', 'Masagana', 'Yes', 'Yes', 'No', 'No', 'No', 'No', 'No', '2024-12-24 08:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `credit_history`
--

CREATE TABLE `credit_history` (
  `MemberID` int(11) DEFAULT NULL,
  `LoanID` int(11) NOT NULL,
  `AmountRequested` decimal(10,2) DEFAULT NULL,
  `LoanTerm` int(11) DEFAULT NULL,
  `LoanType` varchar(50) DEFAULT NULL,
  `InterestRate` decimal(5,2) DEFAULT NULL,
  `loanable_amount` decimal(10,0) NOT NULL,
  `ApprovalStatus` varchar(20) DEFAULT NULL CHECK (`ApprovalStatus` in ('In Progress','Approved','Disapproved','Completed')),
  `LoanEligibility` varchar(20) GENERATED ALWAYS AS (case when `ApprovalStatus` = 'Approved' then 'Eligible' else 'Not Eligible' end) STORED,
  `ModeOfPayment` varchar(50) DEFAULT NULL,
  `MemberIncome` decimal(10,2) DEFAULT NULL,
  `Comaker` varchar(100) DEFAULT NULL,
  `Remarks` text DEFAULT NULL,
  `ExistingLoans` int(11) DEFAULT NULL,
  `CollateralValue` decimal(10,2) DEFAULT NULL,
  `PayableAmount` decimal(10,2) DEFAULT NULL,
  `PayableDate` date DEFAULT NULL,
  `NextPayableAmount` decimal(10,2) DEFAULT NULL,
  `NextPayableDate` date DEFAULT NULL,
  `MaturityDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credit_history`
--

INSERT INTO `credit_history` (`MemberID`, `LoanID`, `AmountRequested`, `LoanTerm`, `LoanType`, `InterestRate`, `loanable_amount`, `ApprovalStatus`, `ModeOfPayment`, `MemberIncome`, `Comaker`, `Remarks`, `ExistingLoans`, `CollateralValue`, `PayableAmount`, `PayableDate`, `NextPayableAmount`, `NextPayableDate`, `MaturityDate`) VALUES
(3, 60, 50000.00, 6, 'Collateral', 0.14, 760000, 'Approved', 'Monthly', 320000.00, NULL, NULL, 0, 1520000.00, 144400.00, '2024-12-25', 144400.00, '2025-01-25', '2025-06-25'),
(2, 61, 5400.00, 6, 'Regular', 0.12, 34200, 'Approved', 'Monthly', 250000.00, 'Maria Carry', NULL, 0, NULL, 6384.00, '2024-12-25', 6384.00, '2025-01-25', '2025-06-25');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `image`, `description`, `event_date`, `created_at`) VALUES
(2, 'Paschal Coop Year end get Together - Officers, Staff and Committees', '6758656c5891f.jpeg', 'celebrated the holiday season with a touch of Boho elegance.', '2024-12-21', '2024-12-08 12:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `land_appraisal`
--

CREATE TABLE `land_appraisal` (
  `LoanID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `square_meters` decimal(10,2) DEFAULT NULL,
  `type_of_land` varchar(100) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `final_zonal_value` decimal(12,2) DEFAULT NULL,
  `right_of_way` varchar(100) DEFAULT NULL,
  `hospital` varchar(100) DEFAULT NULL,
  `clinic` varchar(100) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `market` varchar(100) DEFAULT NULL,
  `church` varchar(100) DEFAULT NULL,
  `public_terminal` varchar(100) DEFAULT NULL,
  `EMV_per_sqm` decimal(10,2) DEFAULT NULL,
  `total_value` decimal(12,2) DEFAULT NULL,
  `loanable_amount` decimal(12,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_appraisal`
--

INSERT INTO `land_appraisal` (`LoanID`, `userID`, `square_meters`, `type_of_land`, `location`, `final_zonal_value`, `right_of_way`, `hospital`, `clinic`, `school`, `market`, `church`, `public_terminal`, `EMV_per_sqm`, `total_value`, `loanable_amount`, `image_path`) VALUES
(60, 3, 400.00, 'INDUSTRIAL', 'Masagana', 3800.00, 'Yes', 'Yes', 'No', 'No', 'No', 'No', 'No', 3800.00, 1520000.00, 760000.00, '../dist/assets/images/collateral/60_1735229886_property.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `loanapplication`
--

CREATE TABLE `loanapplication` (
  `LoanID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `DateOfLoan` date DEFAULT NULL,
  `AmountRequested` decimal(10,2) DEFAULT NULL,
  `LoanTerm` int(11) DEFAULT NULL,
  `Purpose` varchar(255) DEFAULT NULL,
  `LoanType` varchar(50) DEFAULT NULL,
  `ModeOfPayment` varchar(50) DEFAULT NULL,
  `years_stay_present_address` int(11) DEFAULT NULL,
  `own_house` enum('Yes','No') DEFAULT NULL,
  `renting` enum('Yes','No') DEFAULT NULL,
  `living_with_relative` enum('Yes','No') DEFAULT NULL,
  `marital_status` enum('Single','Married','Separated','Divorced','Widowed') DEFAULT NULL,
  `spouse_name` varchar(100) DEFAULT NULL,
  `number_of_dependents` int(11) DEFAULT NULL,
  `dependents_in_school` int(11) DEFAULT NULL,
  `dependent1_name` varchar(100) DEFAULT NULL,
  `dependent1_age` int(11) DEFAULT NULL,
  `dependent1_grade_level` varchar(50) DEFAULT NULL,
  `dependent2_name` varchar(100) DEFAULT NULL,
  `dependent2_age` int(11) DEFAULT NULL,
  `dependent2_grade_level` varchar(50) DEFAULT NULL,
  `dependent3_name` varchar(100) DEFAULT NULL,
  `dependent3_age` int(11) DEFAULT NULL,
  `dependent3_grade_level` varchar(50) DEFAULT NULL,
  `dependent4_name` varchar(100) DEFAULT NULL,
  `dependent4_age` int(11) DEFAULT NULL,
  `dependent4_grade_level` varchar(50) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `present_position` varchar(100) DEFAULT NULL,
  `date_of_employment` date DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_telephone_no` varchar(20) DEFAULT NULL,
  `self_employed_business_type` varchar(100) DEFAULT NULL,
  `business_start_date` date DEFAULT NULL,
  `family_member_count` int(11) DEFAULT NULL,
  `self_income` varchar(100) DEFAULT NULL,
  `self_income_amount` decimal(10,2) DEFAULT NULL,
  `other_income` varchar(100) DEFAULT NULL,
  `self_other_income_amount` decimal(10,2) DEFAULT NULL,
  `spouse_income` varchar(100) DEFAULT NULL,
  `spouse_income_amount` decimal(10,2) DEFAULT NULL,
  `spouse_other_income` varchar(100) DEFAULT NULL,
  `spouse_other_income_amount` decimal(10,2) DEFAULT NULL,
  `food_groceries_expense` decimal(10,2) DEFAULT NULL,
  `gas_oil_transportation_expense` decimal(10,2) DEFAULT NULL,
  `schooling_expense` decimal(10,2) DEFAULT NULL,
  `utilities_expense` decimal(10,2) DEFAULT NULL,
  `miscellaneous_expense` decimal(10,2) DEFAULT NULL,
  `total_expenses` decimal(10,2) DEFAULT NULL,
  `net_family_income` decimal(10,2) DEFAULT NULL,
  `savings_account` tinyint(1) DEFAULT 0,
  `savings_bank` varchar(255) DEFAULT NULL,
  `savings_branch` varchar(255) DEFAULT NULL,
  `current_account` tinyint(1) DEFAULT 0,
  `current_bank` varchar(255) DEFAULT NULL,
  `current_branch` varchar(255) DEFAULT NULL,
  `assets1` varchar(255) DEFAULT NULL,
  `assets2` varchar(255) DEFAULT NULL,
  `assets3` varchar(255) DEFAULT NULL,
  `assets4` varchar(255) DEFAULT NULL,
  `creditor1_name` varchar(255) DEFAULT NULL,
  `creditor1_address` varchar(255) DEFAULT NULL,
  `creditor1_original_amount` decimal(10,2) DEFAULT NULL,
  `creditor1_present_balance` decimal(10,2) DEFAULT NULL,
  `creditor2_name` varchar(255) DEFAULT NULL,
  `creditor2_address` varchar(255) DEFAULT NULL,
  `creditor2_original_amount` decimal(10,2) DEFAULT NULL,
  `creditor2_present_balance` decimal(10,2) DEFAULT NULL,
  `creditor3_name` varchar(255) DEFAULT NULL,
  `creditor3_address` varchar(255) DEFAULT NULL,
  `creditor3_original_amount` decimal(10,2) DEFAULT NULL,
  `creditor3_present_balance` decimal(10,2) DEFAULT NULL,
  `creditor4_name` varchar(255) DEFAULT NULL,
  `creditor4_address` varchar(255) DEFAULT NULL,
  `creditor4_original_amount` decimal(10,2) DEFAULT NULL,
  `creditor4_present_balance` decimal(10,2) DEFAULT NULL,
  `property_foreclosed_repossessed` enum('yes','no') DEFAULT NULL,
  `co_maker_cosigner_guarantor` enum('yes','no') DEFAULT NULL,
  `reference1_name` varchar(255) DEFAULT NULL,
  `reference1_address` varchar(255) DEFAULT NULL,
  `reference1_contact_no` varchar(20) DEFAULT NULL,
  `reference2_name` varchar(255) DEFAULT NULL,
  `reference2_address` varchar(255) DEFAULT NULL,
  `reference2_contact_no` varchar(20) DEFAULT NULL,
  `reference3_name` varchar(255) DEFAULT NULL,
  `reference3_address` varchar(255) DEFAULT NULL,
  `reference3_contact_no` varchar(20) DEFAULT NULL,
  `comaker_name` varchar(255) DEFAULT NULL,
  `comaker_address` varchar(255) DEFAULT NULL,
  `comaker_contact_no` varchar(20) DEFAULT NULL,
  `deed_of_sale1` varchar(255) DEFAULT NULL,
  `deed_of_sale2` varchar(255) DEFAULT NULL,
  `deed_of_sale3` varchar(255) DEFAULT NULL,
  `deed_of_sale4` varchar(255) DEFAULT NULL,
  `orcr_vehicle1` varchar(255) DEFAULT NULL,
  `orcr_vehicle2` varchar(255) DEFAULT NULL,
  `orcr_vehicle3` varchar(255) DEFAULT NULL,
  `orcr_vehicle4` varchar(255) DEFAULT NULL,
  `valid_id_path` varchar(255) DEFAULT NULL,
  `deed_of_sale_path` varchar(255) DEFAULT NULL,
  `vehicle_orcr_path` varchar(255) DEFAULT NULL,
  `proof_of_income_path` varchar(255) DEFAULT NULL,
  `tax_declaration_path` varchar(255) DEFAULT NULL,
  `tax_clearance_path` varchar(255) DEFAULT NULL,
  `original_transfer_certificate_of_title_path` varchar(255) DEFAULT NULL,
  `certified_true_copy_path` varchar(255) DEFAULT NULL,
  `vicinity_map_path` varchar(255) DEFAULT NULL,
  `barangay_clearance_path` varchar(255) DEFAULT NULL,
  `cedula_path` varchar(255) DEFAULT NULL,
  `post_dated_check_path` varchar(255) DEFAULT NULL,
  `promisory_note_path` varchar(255) DEFAULT NULL,
  `Status` enum('In Progress','Approved','Disapproved') NOT NULL DEFAULT 'In Progress',
  `loanable_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loanapplication`
--

INSERT INTO `loanapplication` (`LoanID`, `userID`, `DateOfLoan`, `AmountRequested`, `LoanTerm`, `Purpose`, `LoanType`, `ModeOfPayment`, `years_stay_present_address`, `own_house`, `renting`, `living_with_relative`, `marital_status`, `spouse_name`, `number_of_dependents`, `dependents_in_school`, `dependent1_name`, `dependent1_age`, `dependent1_grade_level`, `dependent2_name`, `dependent2_age`, `dependent2_grade_level`, `dependent3_name`, `dependent3_age`, `dependent3_grade_level`, `dependent4_name`, `dependent4_age`, `dependent4_grade_level`, `employer_name`, `employer_address`, `present_position`, `date_of_employment`, `monthly_income`, `contact_person`, `contact_telephone_no`, `self_employed_business_type`, `business_start_date`, `family_member_count`, `self_income`, `self_income_amount`, `other_income`, `self_other_income_amount`, `spouse_income`, `spouse_income_amount`, `spouse_other_income`, `spouse_other_income_amount`, `food_groceries_expense`, `gas_oil_transportation_expense`, `schooling_expense`, `utilities_expense`, `miscellaneous_expense`, `total_expenses`, `net_family_income`, `savings_account`, `savings_bank`, `savings_branch`, `current_account`, `current_bank`, `current_branch`, `assets1`, `assets2`, `assets3`, `assets4`, `creditor1_name`, `creditor1_address`, `creditor1_original_amount`, `creditor1_present_balance`, `creditor2_name`, `creditor2_address`, `creditor2_original_amount`, `creditor2_present_balance`, `creditor3_name`, `creditor3_address`, `creditor3_original_amount`, `creditor3_present_balance`, `creditor4_name`, `creditor4_address`, `creditor4_original_amount`, `creditor4_present_balance`, `property_foreclosed_repossessed`, `co_maker_cosigner_guarantor`, `reference1_name`, `reference1_address`, `reference1_contact_no`, `reference2_name`, `reference2_address`, `reference2_contact_no`, `reference3_name`, `reference3_address`, `reference3_contact_no`, `comaker_name`, `comaker_address`, `comaker_contact_no`, `deed_of_sale1`, `deed_of_sale2`, `deed_of_sale3`, `deed_of_sale4`, `orcr_vehicle1`, `orcr_vehicle2`, `orcr_vehicle3`, `orcr_vehicle4`, `valid_id_path`, `deed_of_sale_path`, `vehicle_orcr_path`, `proof_of_income_path`, `tax_declaration_path`, `tax_clearance_path`, `original_transfer_certificate_of_title_path`, `certified_true_copy_path`, `vicinity_map_path`, `barangay_clearance_path`, `cedula_path`, `post_dated_check_path`, `promisory_note_path`, `Status`, `loanable_amount`) VALUES
(60, 3, '2024-12-25', 50000.00, 6, 'business', 'Collateral', 'Monthly', 5, 'Yes', 'No', 'No', 'Married', 'Tan Collins', 2, 1, 'Chris Collins', 7, '1', 'Kevin Collins', 11, '5', NULL, NULL, NULL, NULL, NULL, NULL, 'Timothy Tui', 'Binangonan', 'Developer', '2000-03-05', 1000000.00, 'Megana Tui', '09712050033', 'Computer Programming PH', '2005-07-10', 4, 'Salary', 200000.00, 'Savings', 50000.00, 'Seller', 50000.00, 'Savings', 20000.00, 500.00, 500.00, 500.00, 550.00, 1000.00, 3050.00, 320000.00, 1, 'Land Bank', 'Masagana', 1, 'BDO', 'Masagana', 'House', 'Motorcycle', NULL, NULL, 'Lyndon Tan', 'Sta. Rosa Laguna', 1000.00, 100.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yes', 'no', 'Juan Dela Cruz', '123 Mabini St., Manila', '09171234561', 'Maria Santos', '456 Rizal Ave., Cebu City', '09281234562', 'Pedro Reyes', '789 Bonifacio Rd., Davao City', '09391234564', NULL, NULL, NULL, 'deed_676a6deabe372.png', NULL, NULL, NULL, NULL, 'orcr_676a6deabed66.jpg', NULL, NULL, 'valid_id_60_676a6f498644c.jpg', NULL, NULL, 'proof_of_income_60_676a6f4986859.jpg', 'tax_declaration_60_676a6f4986c50.jpeg', 'tax_clearance_60_676a6f498712d.jpg', 'original_transfer_certificate_of_title_60_676a6f4987619.jpg', 'certified_true_copy_60_676a6f498799a.jpg', 'vicinity_map_60_676a6f4987de8.png', 'barangay_clearance_60_676a6f49880bc.png', 'cedula_60_676a6f49886e4.jpg', 'post_dated_check_60_676a6f4988a09.jpg', 'promisory_note_60_676a6f4988d38.pdf', 'Approved', 760000.00),
(61, 2, '2024-12-25', 5400.00, 6, 'emergency', 'Regular', 'Monthly', 6, 'Yes', 'No', 'Yes', 'Married', 'Mandy Moore', 2, 2, 'Jake Doe', 11, '5', 'Tim Doe', 12, '6', NULL, NULL, NULL, NULL, NULL, NULL, 'Merry Gidjet', 'Plaridel Bulacan', 'Fullstack Developer', '2001-12-24', 1000000.00, 'Megan Young', '09810265489', 'Food Shop', '2006-05-10', 4, 'Salary', 150000.00, 'Savings', 50000.00, 'Seller', 30000.00, 'Savings', 20000.00, 500.00, 1000.00, 1000.00, 500.00, 1000.00, 4000.00, 250000.00, 1, 'BDO', 'BAGBAGUIN', 1, 'BPI', 'BAGBAGUIN', 'Apartment', 'Van', NULL, NULL, 'Teresa Dela Cruz', 'Sta. Rosa Laguna', 2000.00, 100.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'no', 'no', 'Juan Santiago', '123 Mabini St., Manila', '09171234566', 'Maria Santiago', '456 Rizal Ave., Cebu City', '09281234566', 'Pedro Santiago', '789 Bonifacio Rd., Davao City', '0985250365', 'Maria Carry', 'Bulacan', '0966885052', 'deed_676a7db7891d7.jpeg', NULL, NULL, NULL, NULL, 'orcr_676a7db789629.jpeg', NULL, NULL, 'valid_id_61_676a82a176aa6.jpg', NULL, NULL, 'proof_of_income_61_676a82a177838.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approved', 34200.00);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `created_at`) VALUES
(1, 'Bagbaguin', '2024-12-09 08:21:04'),
(2, 'Bagong Barrio', '2024-12-09 08:21:04'),
(3, 'Baka-bakahan', '2024-12-09 08:21:04'),
(4, 'Santo Niño', '2024-12-09 08:21:04'),
(5, 'Bunsuran I', '2024-12-09 08:21:04'),
(6, 'Bunsuran II', '2024-12-09 08:21:04'),
(7, 'Bunsuran III', '2024-12-09 08:21:04'),
(8, 'Cacarong Bata', '2024-12-09 08:21:04'),
(9, 'Cacarong Matanda', '2024-12-09 08:21:04'),
(10, 'Cupang', '2024-12-09 08:21:04'),
(11, 'Malibong Bata', '2024-12-09 08:21:04'),
(12, 'Malibong Matanda', '2024-12-09 08:21:04'),
(13, 'Manatal', '2024-12-09 08:21:04'),
(14, 'Mapulang Lupa', '2024-12-09 08:21:04'),
(15, 'Masagana', '2024-12-09 08:21:04'),
(16, 'Masuso', '2024-12-09 08:21:04'),
(17, 'Pinagkuartelan', '2024-12-09 08:21:04'),
(18, 'Poblacion', '2024-12-09 08:21:04'),
(19, 'Real de Cacarong', '2024-12-09 08:21:04'),
(20, 'San Roque', '2024-12-09 08:21:04'),
(21, 'Siling Bata', '2024-12-09 08:21:04'),
(22, 'Siling Matanda', '2024-12-09 08:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `member_applications`
--

CREATE TABLE `member_applications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `fillupform` tinyint(1) DEFAULT 0,
  `watchvideoseminar` tinyint(1) DEFAULT 0,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('In Progress','Approved','Failed','Completed') DEFAULT 'In Progress',
  `membershipfee` decimal(10,2) DEFAULT 0.00,
  `service_name` varchar(255) DEFAULT NULL,
  `regular_fee` decimal(10,2) DEFAULT 0.00,
  `collateral_fee` decimal(10,2) DEFAULT 0.00,
  `lifeinsurance_fee` decimal(10,2) DEFAULT 0.00,
  `medical_fee` decimal(10,2) DEFAULT 0.00,
  `rice_fee` decimal(10,2) DEFAULT 0.00,
  `space_fee` decimal(10,2) DEFAULT 0.00,
  `health_fee` decimal(10,2) DEFAULT 0.00,
  `lab_fee` decimal(10,2) DEFAULT 0.00,
  `xray_fee` decimal(10,2) DEFAULT 0.00,
  `hilot_fee` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('In Progress','Completed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_applications`
--

INSERT INTO `member_applications` (`id`, `user_id`, `fillupform`, `watchvideoseminar`, `appointment_date`, `status`, `membershipfee`, `service_name`, `regular_fee`, `collateral_fee`, `lifeinsurance_fee`, `medical_fee`, `rice_fee`, `space_fee`, `health_fee`, `lab_fee`, `xray_fee`, `hilot_fee`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, '2', 1, 1, '2024-12-10 00:00:00', 'Completed', 6750.00, 'Regular Loan', 1111.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'In Progress', '2024-12-09 05:38:29', '2024-12-11 10:00:28'),
(2, '3', 1, 1, '2024-12-18 00:00:00', 'In Progress', 4444.00, 'Life Insurance', 0.00, 0.00, 4335.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'In Progress', '2024-12-09 05:54:06', '2024-12-09 17:08:35'),
(3, '8', 1, 1, '2024-12-26 00:00:00', NULL, 0.00, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2024-12-24 09:59:21', '2024-12-24 10:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `admin_reply_date` datetime DEFAULT NULL,
  `membership_reply` text DEFAULT NULL,
  `membership_reply_date` datetime DEFAULT NULL,
  `medical_reply` text DEFAULT NULL,
  `medical_reply_date` datetime DEFAULT NULL,
  `loan_reply` text NOT NULL,
  `loan_reply_date` datetime DEFAULT NULL,
  `admin_message` text DEFAULT NULL,
  `admin_date` datetime DEFAULT NULL,
  `membership_message` text DEFAULT NULL,
  `membership_date` datetime DEFAULT NULL,
  `medical_message` text DEFAULT NULL,
  `medical_date` datetime DEFAULT NULL,
  `loan_message` text DEFAULT NULL,
  `loan_date` datetime DEFAULT NULL,
  `datesent` datetime DEFAULT current_timestamp(),
  `is_replied` tinyint(1) DEFAULT 0,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `category`, `message`, `admin_reply`, `admin_reply_date`, `membership_reply`, `membership_reply_date`, `medical_reply`, `medical_reply_date`, `loan_reply`, `loan_reply_date`, `admin_message`, `admin_date`, `membership_message`, `membership_date`, `medical_message`, `medical_date`, `loan_message`, `loan_date`, `datesent`, `is_replied`, `is_read`, `created_at`) VALUES
(1, 2, 1, 'Loan', 'I just want to ask if i can still applied to collateral loan even i still have an outstanding balance of the regular loan.', 'Yes', '2024-12-10 22:53:28', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 20:00:42', 1, 0, '2024-12-10 14:53:28'),
(2, 2, 1, 'Services', 'i want to know all the services', 'you can check it to your services tab', '2024-12-23 23:31:45', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 18:19:02', 1, 0, '2024-12-23 15:31:45'),
(9, 2, 4, 'Medical', 'i need to know more medical services', NULL, NULL, NULL, NULL, 'you can check it to the services tab in your account', '2024-12-23 23:39:29', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-23 23:34:48', 1, 0, '2024-12-23 15:39:29'),
(10, 2, 7, 'Membership', 'Am I already approved?', NULL, NULL, 'Yes you\'re already a member', '2024-12-24 00:16:09', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-24 00:15:49', 1, 0, '2024-12-23 16:16:09'),
(12, 1, 2, 'General Query', '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 'If you have general query just send as message', '2024-12-24 01:55:21', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-24 01:55:21', 0, 0, '2024-12-23 17:55:21'),
(13, 4, 2, 'Medical', '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 'If you have medical services question just send as message', '2024-12-24 01:57:18', NULL, NULL, '2024-12-24 01:57:18', 0, 0, '2024-12-23 17:57:18'),
(16, 7, 2, 'Membership', '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'If you have membership question just send as message', '2024-12-24 02:04:05', NULL, NULL, NULL, NULL, '2024-12-24 02:04:05', 0, 0, '2024-12-23 18:04:05'),
(17, 5, 2, 'Loan', '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'If you have loan question just send as message', '2024-12-24 02:09:20', '2024-12-24 02:09:20', 0, 0, '2024-12-23 18:09:20'),
(18, 2, 5, 'Loan', 'How long my loan to be approved?', NULL, NULL, NULL, NULL, NULL, NULL, 'Just wait it to be approved', '2024-12-24 02:16:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-24 02:09:50', 1, 0, '2024-12-23 18:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `SavingsID` int(11) NOT NULL,
  `MemberID` varchar(255) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `Notes` text DEFAULT NULL,
  `Status` enum('In Progress','Approved','Disapproved') DEFAULT 'In Progress',
  `TransactionDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`SavingsID`, `MemberID`, `Amount`, `PaymentMethod`, `Notes`, `Status`, `TransactionDate`) VALUES
(12, '2', 20000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-23 10:11:13'),
(13, '2', 3000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-23 14:54:17'),
(15, '2', 5000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-23 15:03:11'),
(16, '8', 5000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-27 10:28:07'),
(17, '2', 4000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-27 20:22:52'),
(18, '2', 1000.00, 'walkin', 'deposit savings', 'Approved', '2024-12-27 20:27:59');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `type`, `created_at`) VALUES
(2, 'Life Insurance', 'Product', '2024-12-09 07:56:39'),
(3, 'Rice', 'Product', '2024-12-09 07:56:49'),
(4, 'Space for Rent', 'Product', '2024-12-09 07:57:04'),
(5, 'Medical Consultation', 'Medical', '2024-12-09 07:57:18'),
(6, 'Laboratory', 'Medical', '2024-12-09 07:57:30'),
(7, 'X-RAY', 'Medical', '2024-12-09 07:57:39'),
(8, 'Hilot Healom', 'Medical', '2024-12-09 07:57:50'),
(9, 'Health Card', 'Medical', '2024-12-09 07:58:00'),
(10, 'Regular Loan', 'Loan', '2024-12-09 07:58:11'),
(11, 'Collateral Loan', 'Loan', '2024-12-09 07:58:23'),
(13, 'Membership Payment', 'Membership Payment', '2024-12-10 10:38:54'),
(14, 'Savings Deposit', 'Members Savings', '2024-12-22 19:57:35'),
(15, 'Share Capital Deposit', 'Members Share Capital', '2024-12-22 19:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `share_capital`
--

CREATE TABLE `share_capital` (
  `ShareCapitalID` int(11) NOT NULL,
  `MemberID` varchar(255) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `Notes` text DEFAULT NULL,
  `Status` enum('In Progress','Approved','Disapproved') DEFAULT 'In Progress',
  `TransactionDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `share_capital`
--

INSERT INTO `share_capital` (`ShareCapitalID`, `MemberID`, `Amount`, `PaymentMethod`, `Notes`, `Status`, `TransactionDate`) VALUES
(11, '2', 5000.00, 'walkin', 'deposit share capital', 'Approved', '2024-12-23 10:06:00'),
(12, '2', 5000.00, 'walkin', 'deposit share capital', 'Approved', '2024-12-23 15:04:28'),
(13, '8', 1000.00, 'walkin', 'deposit share capital', 'Approved', '2024-12-27 10:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `control_number` varchar(20) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `payment_status` enum('In Progress','Completed') NOT NULL DEFAULT 'In Progress',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `service_name`, `amount`, `control_number`, `signature`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Rice', 1000.00, '00101', '', 'Completed', '2024-12-11 09:20:26', '2024-12-21 09:28:27'),
(5, 3, 'Membership Payment', 6750.00, '00102', '', 'Completed', '2024-12-11 10:09:47', '2024-12-21 09:28:36'),
(12, 2, 'Membership Payment', 6850.00, '00103', '', 'Completed', '2024-12-15 10:33:34', '2024-12-21 09:28:43'),
(13, 2, 'Laboratory', 2000.00, '00104', '', 'Completed', '2024-12-21 09:38:10', '2024-12-21 09:39:08'),
(26, 2, 'Share Capital Deposit', 5000.00, '00105', 'Admin', 'Completed', '2024-12-23 10:06:00', '2024-12-23 10:06:00'),
(27, 2, 'Savings Deposit', 20000.00, '00106', 'Admin', 'Completed', '2024-12-23 10:11:27', '2024-12-23 15:05:25'),
(28, 2, 'Space for Rent', 0.00, '00107', '', 'In Progress', '2024-12-23 11:54:49', '2024-12-23 15:05:36'),
(29, 2, 'X-RAY', 5000.00, '00108', '', 'Completed', '2024-12-23 14:11:50', '2024-12-23 15:14:45'),
(30, 3, 'Hilot Healom', 5000.00, '00109', '', 'Completed', '2024-12-23 14:12:10', '2024-12-23 14:52:53'),
(31, 2, 'Savings Deposit', 3000.00, '00110', 'Admin', 'Completed', '2024-12-23 14:54:17', '2024-12-23 14:54:17'),
(33, 2, 'Savings Deposit', 5000.00, '00111', 'Admin', 'Completed', '2024-12-23 15:03:59', '2024-12-23 15:03:59'),
(34, 2, 'Share Capital Deposit', 5000.00, '00112', 'Admin', 'Completed', '2024-12-23 15:05:03', '2024-12-23 15:05:03'),
(35, 2, 'Health Card', 1000.00, '00113', '', 'Completed', '2024-12-23 15:06:50', '2024-12-23 15:12:00'),
(36, 8, 'Membership Payment', 6750.00, '00114', '', 'Completed', '2024-12-27 10:28:07', '2024-12-27 10:36:46'),
(37, 8, 'Savings Deposit', 5000.00, '00115', '', 'Completed', '2024-12-27 10:28:07', '2024-12-27 10:28:07'),
(38, 8, 'Share Capital Deposit', 1000.00, '00116', 'Admin', 'Completed', '2024-12-27 10:40:55', '2024-12-27 10:40:55'),
(39, 2, 'Savings Deposit', 4000.00, '00117', 'Admin', 'Completed', '2024-12-27 20:24:27', '2024-12-27 20:24:27'),
(40, 2, 'Savings Deposit', 1000.00, '00118', 'Admin', 'Completed', '2024-12-27 20:27:59', '2024-12-27 20:27:59'),
(41, 2, 'Space for Rent', 0.00, '', '', 'In Progress', '2024-12-27 20:32:21', '2024-12-27 20:32:21'),
(42, 2, 'Medical Consultation', 0.00, '', '', 'In Progress', '2024-12-27 20:33:02', '2024-12-27 20:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `tin_number` varchar(50) DEFAULT NULL,
  `tin_id_image` varchar(255) DEFAULT NULL,
  `certificate_no` varchar(50) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Admin','Member','Loan Officer','Membership Officer','Liaison Officer','Medical Officer') NOT NULL,
  `membership_type` varchar(50) DEFAULT NULL,
  `savings` decimal(10,2) DEFAULT 0.00,
  `share_capital` decimal(10,2) DEFAULT 0.00,
  `membership_status` enum('Pending','Active') DEFAULT 'Pending',
  `uploadID` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_logged_in` tinyint(1) DEFAULT 0,
  `is_logged_in_time` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `terms_accepted` enum('agree','disagree') NOT NULL DEFAULT 'disagree',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `middle_name`, `gender`, `birthday`, `age`, `street`, `barangay`, `municipality`, `province`, `tin_number`, `tin_id_image`, `certificate_no`, `username`, `email`, `mobile`, `password`, `user_type`, `membership_type`, `savings`, `share_capital`, `membership_status`, `uploadID`, `description`, `status`, `created_at`, `is_logged_in`, `is_logged_in_time`, `remember_token`, `reset_token`, `reset_token_expiry`, `terms_accepted`, `updated_at`, `last_activity`) VALUES
(1, '1', 'Mr.', 'Admin', 'M', 'male', '1990-01-01', 34, '123 Main St', 'Downtown', 'Sample City', 'Albay', '123456789', NULL, NULL, 'johndoe', 'administrator@gmail.com', '09123456789', '$2y$10$.L.nFuKHxsLSFQQ.uCwbMODdzD8aGXsGPKkc.F/79XjU.IGwNX2aK', 'Admin', NULL, 0.00, 0.00, 'Pending', '6755b3739c948_6ae228e6-1317-4cde-a891-850a67ff6315.jfif', NULL, 1, '2024-12-06 16:48:06', 1, '2024-12-28 02:17:55', '744fe9414055c6fa445b859954edc3aebf202019a267c480c07e48e6c21937f1', NULL, NULL, 'agree', '2024-12-27 18:17:55', '2024-12-06 16:48:06'),
(2, '2', 'John', 'Doe', 'S.', 'male', '1995-08-09', 29, 'Balete', 'Binitayan', 'Daraga', 'Albay', '54544545', '1733722709_istockphoto-1177502660-612x612.jpg', '4563', '', 'chrisdoe@gmail.com', '09121038324', '$2y$10$bRQy2dNe.ZvmXiQwfASL/.BaStbTfgjIIaUHfGmOmoHHadPxl4RG2', 'Member', 'Regular', 1000.00, 5000.00, 'Active', '6759b9b622be5_face4.jpg', NULL, 0, '2024-12-09 05:38:29', 1, '2024-12-28 04:08:21', '9c9b7ad4cf70afdda1cf2a1e74e166332d421e7c136772fb23fc8aede81fb9d4', NULL, NULL, 'disagree', '2024-12-27 20:08:21', '2024-12-09 05:38:29'),
(3, '3', 'Max', 'Collins', 'Anderson', 'female', '1995-08-10', 29, 'Balete', 'Binitayan', 'Cabanatuan', 'Cabanatuan', '14533234', '1733723646_mitch.png', '34456', '', 'mitchcollins@gmail.com', '09121038301', '$2y$10$.gdBDXwOFvIzRPYoBzWuxOha1MhBIAIzrn4fI0efB2e6S59Q/ad1.', 'Member', 'Associate', 1000.00, 3000.00, 'Active', NULL, NULL, 0, '2024-12-09 05:54:06', 0, '2024-12-24 16:10:00', NULL, NULL, NULL, 'disagree', '2024-12-24 09:20:39', '2024-12-09 05:54:06'),
(4, '4', 'Mr.', 'Medical', 'M', 'male', '1990-01-01', 34, '123 Main St', 'Downtown', 'Taguig City', 'Albay', '123456789', NULL, NULL, '', 'medical@gmail.com', '09123456789', '$2y$10$dwrITXzFbN3.fl8NZ7uE/.US/0DAYoNrmEZKNLwwCibem8LYQKPNG', 'Medical Officer', NULL, 0.00, 0.00, 'Pending', '67697267cfed7_face11.jpg', NULL, 1, '2024-12-06 08:48:06', 0, '2024-12-28 02:50:35', '', NULL, NULL, 'agree', '2024-12-27 18:52:07', '2024-12-06 08:48:06'),
(5, '5', 'Mr.', 'Loan Officer', NULL, 'male', '1985-07-15', 39, '456 Elm St', 'Central', 'Quezon City', 'Metro Manila', '987654321', NULL, NULL, 'loansmith', 'loanofficer@gmail.com', '09171234567', '$2y$10$2o/2rg2hU0AT13BOQhSgMOEylUT/BWm/orFQMu/Uy8Tyj5TGxhzuW', 'Loan Officer', NULL, 0.00, 0.00, 'Pending', '6769a729e9c1d_face22.jpg', NULL, 1, '2024-12-23 00:48:06', 0, '2024-12-28 02:32:26', NULL, NULL, NULL, 'agree', '2024-12-27 18:50:31', '2024-12-23 00:48:06'),
(6, '6', 'Mr.', 'Liaison Officer', NULL, 'female', '1992-03-22', 32, '789 Pine St', 'North', 'Pasig City', 'Metro Manila', '123321123', NULL, NULL, 'liaisonjane', 'liaisonofficer@gmail.com', '09181234567', '$2y$10$xHicVsMYTol82f2ZqOzqReUgiOK22jC1k879gJpAiwv4u81DSFLkG', 'Liaison Officer', NULL, 0.00, 0.00, 'Pending', '6769929732b2b_face28.jpg', NULL, 1, '2024-12-23 00:48:06', 0, '2024-12-28 02:52:14', '7b49fe0eef9ab1dbbf1aea52ea20f288a7120b8f8138dc64cc4b9c752a662c7e', NULL, NULL, 'agree', '2024-12-27 18:53:02', '2024-12-23 00:48:06'),
(7, '7', 'Mr.', 'Membership', 'M', 'male', '1991-10-01', 34, '123 Main St', 'Downtown', 'Manila City', 'Albay', '123456789', NULL, NULL, '', 'membership@gmail.com', '09123456781', '$2y$10$32zbWTaxOdKOiOWhjYQLVu3xjn/nYs5JVv3pBXbu2WKoy1FQqQuIu', 'Membership Officer', NULL, 0.00, 0.00, 'Pending', '676989e3538fd_face25.jpg', NULL, 1, '2024-12-06 00:48:06', 0, '2024-12-28 02:54:22', '', NULL, NULL, 'agree', '2024-12-27 18:55:22', '2024-12-06 00:48:06'),
(8, '8', 'Jigs', 'Medina', 'S.', 'male', '1999-12-27', 26, 'Balete', 'Binitayan', 'Leg', 'Albay', '54544543', '1735034361_tin.jpg', '4561', '', 'jigsmedina1999@gmail.com', '09810265490', '$2y$10$D7U74dXyQBZSHnsRNFbcouHnk/Q3C3gRhOVN/BrP2WknbM.f3dNZq', 'Member', 'Regular', 5000.00, 1000.00, 'Active', NULL, NULL, 0, '2024-12-24 09:59:21', 0, NULL, NULL, NULL, NULL, 'disagree', '2024-12-27 10:28:07', '2024-12-24 09:59:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collateral_info`
--
ALTER TABLE `collateral_info`
  ADD PRIMARY KEY (`CollateralID`),
  ADD KEY `LoanID` (`LoanID`);

--
-- Indexes for table `credit_history`
--
ALTER TABLE `credit_history`
  ADD PRIMARY KEY (`LoanID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `land_appraisal`
--
ALTER TABLE `land_appraisal`
  ADD PRIMARY KEY (`LoanID`);

--
-- Indexes for table `loanapplication`
--
ALTER TABLE `loanapplication`
  ADD PRIMARY KEY (`LoanID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_applications`
--
ALTER TABLE `member_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`SavingsID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share_capital`
--
ALTER TABLE `share_capital`
  ADD PRIMARY KEY (`ShareCapitalID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `collateral_info`
--
ALTER TABLE `collateral_info`
  MODIFY `CollateralID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loanapplication`
--
ALTER TABLE `loanapplication`
  MODIFY `LoanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `member_applications`
--
ALTER TABLE `member_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `SavingsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `share_capital`
--
ALTER TABLE `share_capital`
  MODIFY `ShareCapitalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `member_applications`
--
ALTER TABLE `member_applications`
  ADD CONSTRAINT `member_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `share_capital`
--
ALTER TABLE `share_capital`
  ADD CONSTRAINT `share_capital_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
