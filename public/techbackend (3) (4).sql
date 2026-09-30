-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2026 at 01:19 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techbackend`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendences`
--

CREATE TABLE `attendences` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `machine_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:29:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"view factories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:16:\"create factories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:14:\"edit factories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:16:\"delete factories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:13:\"view machines\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:15:\"create machines\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"edit machines\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"delete machines\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"view productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:18:\"create productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:16:\"edit productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"delete productions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:14:\"view employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:16:\"create employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"edit employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:16:\"delete employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:15:\"view attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:17:\"create attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"edit attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"delete attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"approve production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:17:\"reject production\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:15:\"mark attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:7:\"scan qr\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:12:\"view profile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"owner\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:8:\"employee\";s:1:\"c\";s:3:\"web\";}}}', 1789183940);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `factory_id` bigint UNSIGNED DEFAULT NULL,
  `shift_starttime` time DEFAULT NULL,
  `shift_endtime` time DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `factory_id`, `shift_starttime`, `shift_endtime`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '05:00:00', '17:00:00', 2, '2026-09-03 22:34:11', '2026-09-03 22:34:11'),
(2, 1, '17:00:00', '05:00:00', 5, '2026-09-03 22:34:58', '2026-09-03 22:34:58'),
(3, 1, '05:00:00', '17:00:00', 4, '2026-09-03 22:35:47', '2026-09-03 22:35:47'),
(4, 2, '05:00:00', '17:00:00', 7, '2026-09-10 23:44:16', '2026-09-10 23:44:16'),
(5, 2, '17:00:00', '05:00:00', 8, '2026-09-10 23:45:20', '2026-09-10 23:45:20'),
(6, 2, '05:00:00', '17:00:00', 9, '2026-09-10 23:45:56', '2026-09-10 23:45:56'),
(7, 2, '17:00:00', '05:00:00', 10, '2026-09-10 23:46:35', '2026-09-10 23:46:35'),
(8, 3, '05:00:00', '17:00:00', 12, '2026-09-11 02:28:37', '2026-09-11 02:28:37'),
(9, 3, '05:00:00', '17:00:00', 13, '2026-09-11 02:29:27', '2026-09-11 02:29:27'),
(10, 3, '05:00:00', '17:00:00', 14, '2026-09-11 02:30:01', '2026-09-11 02:30:01'),
(11, 4, '05:00:00', '17:00:00', 16, '2026-09-11 07:22:42', '2026-09-11 07:22:42'),
(12, 4, '17:00:00', '05:00:00', 17, '2026-09-11 07:42:57', '2026-09-11 07:42:57'),
(13, 4, '05:00:00', '17:00:00', 18, '2026-09-11 07:45:14', '2026-09-11 07:45:14'),
(14, 4, '17:00:00', '05:00:00', 19, '2026-09-11 07:45:54', '2026-09-11 07:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `factories`
--

CREATE TABLE `factories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `week_start_day` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factories`
--

INSERT INTO `factories` (`id`, `name`, `address`, `city`, `week_start_day`, `created_at`, `updated_at`) VALUES
(1, 'Ansari Texstile', 'G.T Road', 'Kamoki', 1, '2026-09-03 22:18:20', '2026-09-03 22:18:20'),
(2, 'Mala Textile', 'Railway Road', 'Kamoki', 1, '2026-09-10 22:38:12', '2026-09-10 22:38:12'),
(3, 'Neelam Textile', 'Bhola Peer', 'Kamoki', 1, '2026-09-11 01:02:30', '2026-09-11 01:02:30'),
(4, 'Al-Rahman', 'G.T Road', 'kamoki', 1, '2026-09-11 02:42:46', '2026-09-11 02:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint UNSIGNED NOT NULL,
  `factory_id` bigint UNSIGNED NOT NULL,
  `machine_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `factory_id`, `machine_name`, `machine_type`, `time`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ansari 01', 'Power Loom', '2026-09-04 08:19:01', '2026-09-03 22:19:01', '2026-09-03 22:19:01'),
(2, 1, 'Ansari 02', 'Power Loom', '2026-09-04 08:19:29', '2026-09-03 22:19:29', '2026-09-03 22:19:29'),
(3, 1, 'Ansari 03', 'Power Loom', '2026-09-04 08:19:56', '2026-09-03 22:19:56', '2026-09-03 22:19:56'),
(4, 1, 'Ansari 04', 'Power Loom', '2026-09-04 08:20:39', '2026-09-03 22:20:39', '2026-09-03 22:20:39'),
(5, 2, 'Mala 01', 'Power Loom', '2026-09-11 08:39:48', '2026-09-10 22:39:48', '2026-09-10 22:39:48'),
(6, 2, 'Mala 02', 'Power Loom', '2026-09-11 08:40:07', '2026-09-10 22:40:07', '2026-09-10 22:40:07'),
(7, 2, 'Mala 03', 'Power Loom', '2026-09-11 08:40:23', '2026-09-10 22:40:23', '2026-09-10 22:40:23'),
(8, 2, 'Mala 04', 'Power Loom', '2026-09-11 08:40:44', '2026-09-10 22:40:44', '2026-09-10 22:40:44'),
(9, 2, 'Mala 05', 'Power Loom', '2026-09-11 08:41:02', '2026-09-10 22:41:02', '2026-09-10 22:41:02'),
(10, 2, 'Mala 06', 'Power Loom', '2026-09-11 08:41:23', '2026-09-10 22:41:23', '2026-09-10 22:41:23'),
(11, 2, 'Mala 07', 'Power Loom', '2026-09-11 08:41:39', '2026-09-10 22:41:39', '2026-09-10 22:41:39'),
(12, 2, 'Mala 08', 'Power Loom', '2026-09-11 08:41:56', '2026-09-10 22:41:57', '2026-09-10 22:41:57'),
(13, 2, 'Mala 09', 'Power Loom', '2026-09-11 08:42:18', '2026-09-10 22:42:18', '2026-09-10 22:42:18'),
(14, 2, 'Mala 10', 'Power Loom', '2026-09-11 08:42:42', '2026-09-10 22:42:42', '2026-09-10 22:42:42'),
(15, 2, 'Mala 11', 'Power Loom', '2026-09-11 08:43:05', '2026-09-10 22:43:05', '2026-09-10 22:43:05'),
(16, 2, 'Mala 12', 'Power Loom', '2026-09-11 08:43:25', '2026-09-10 22:43:25', '2026-09-10 22:43:25'),
(17, 2, 'Mala 13', 'Power Loom', '2026-09-11 08:43:43', '2026-09-10 22:43:43', '2026-09-10 22:43:43'),
(18, 2, 'Mala 14', 'Power Loom', '2026-09-11 08:44:03', '2026-09-10 22:44:03', '2026-09-10 22:44:03'),
(19, 2, 'Mala 15', 'Power Loom', '2026-09-11 08:44:21', '2026-09-10 22:44:20', '2026-09-10 22:44:20'),
(20, 2, 'Mala 16', 'Power Loom', '2026-09-11 08:44:36', '2026-09-10 22:44:36', '2026-09-10 22:44:36'),
(21, 3, 'MC 01', 'Power Loom', '2026-09-11 00:00:00', '2026-09-11 01:03:24', '2026-09-11 01:03:49'),
(22, 3, 'MC 02', 'Power Loom', '2026-09-11 11:04:14', '2026-09-11 01:04:14', '2026-09-11 01:04:14'),
(23, 3, 'MC 03', 'Power Loom', '2026-09-11 11:04:34', '2026-09-11 01:04:34', '2026-09-11 01:04:34'),
(24, 3, 'MC 04', 'Power Loom', '2026-09-11 11:04:58', '2026-09-11 01:04:58', '2026-09-11 01:04:58'),
(25, 3, 'MC 05', 'Power Loom', '2026-09-11 11:05:33', '2026-09-11 01:05:33', '2026-09-11 01:05:33'),
(26, 3, 'MC 06', 'Power Loom', '2026-09-11 11:05:49', '2026-09-11 01:05:49', '2026-09-11 01:05:49'),
(27, 4, 'ALR 01', 'Power Loom', '2026-09-11 16:41:32', '2026-09-11 06:41:32', '2026-09-11 06:41:32'),
(28, 4, 'ALR 02', 'Power Loom', '2026-09-11 17:01:42', '2026-09-11 07:01:42', '2026-09-11 07:01:42'),
(29, 4, 'ALR 03', 'Power Loom', '2026-09-11 17:02:01', '2026-09-11 07:02:01', '2026-09-11 07:02:01'),
(30, 4, 'ALR 04', 'Power Loom', '2026-09-11 17:02:51', '2026-09-11 07:02:51', '2026-09-11 07:02:51'),
(31, 4, 'ALR 05', 'Power Loom', '2026-09-11 17:03:48', '2026-09-11 07:03:49', '2026-09-11 07:03:49'),
(32, 4, 'ALR 06', 'Power Loom', '2026-09-11 17:04:05', '2026-09-11 07:04:05', '2026-09-11 07:04:05'),
(33, 4, 'ALR 07', 'Power Loom', '2026-09-11 17:04:21', '2026-09-11 07:04:21', '2026-09-11 07:04:21'),
(34, 4, 'ALR 08', 'Power Loom', '2026-09-11 17:11:38', '2026-09-11 07:11:39', '2026-09-11 07:11:39'),
(35, 4, 'ALR 09', 'Power Loom', '2026-09-11 17:11:53', '2026-09-11 07:11:53', '2026-09-11 07:11:53'),
(36, 4, 'ALR 10', 'Power Loom', '2026-09-11 17:12:09', '2026-09-11 07:12:09', '2026-09-11 07:12:09'),
(37, 4, 'ALR 11', 'Power Loom', '2026-09-11 17:12:25', '2026-09-11 07:12:26', '2026-09-11 07:12:26'),
(38, 4, 'ALR 12', 'Power Loom', '2026-09-11 17:12:43', '2026-09-11 07:12:43', '2026-09-11 07:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_02_063829_create_personal_access_tokens_table', 1),
(5, '2026_04_14_000002_create_productions_table', 1),
(6, '2026_04_26_094306_create_factories_table', 1),
(7, '2026_05_12_155707_create_permission_tables', 1),
(8, '2026_06_09_135014_add_status_to_productions_table', 1),
(9, '2026_06_10_133603_add_status_to_attendences_table', 1),
(10, '2026_06_12_151209_add_batch_id_to_productions_table', 1),
(11, '2026_06_14_234127_create_machines_table', 1),
(12, '2026_06_14_234555_create_employees_table', 1),
(13, '2026_06_14_234840_create_attendences_table', 1),
(15, '2026_08_20_121408_create_payments_table', 2),
(16, '2026_08_15_073416_create_cities_table', 3),
(17, '2026_09_02_043350_add_week_start_day_to_factories_table', 3),
(18, '2026_09_02_043514_add_alert_threshold_to_productions_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 18),
(3, 'App\\Models\\User', 19),
(3, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 22);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED DEFAULT NULL,
  `sender_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('production_created','approved','rejected','pending','production_assigned') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `production_id`, `sender_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 7, 1, 'New Production assigned', 'Ali assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:48:53', '2026-09-03 22:48:53'),
(2, 5, 8, 1, 'New Production assigned', 'Husnain assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:48:53', '2026-09-03 22:48:53'),
(3, 2, 9, 1, 'New Production assigned', 'Ali assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:50:25', '2026-09-03 22:50:25'),
(4, 5, 10, 1, 'New Production assigned', 'Husnain assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:50:25', '2026-09-03 22:50:25'),
(5, 4, 11, 1, 'New Production assigned', 'Zaid assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:52:16', '2026-09-03 22:52:16'),
(6, 4, 12, 1, 'New Production assigned', 'Zaid assigned production by Abdullah', 'production_assigned', 0, '2026-09-03 22:52:58', '2026-09-03 22:52:58'),
(7, 2, 7, 1, 'Production Approved', 'Your production has been approved by Abdullah', 'approved', 0, '2026-09-06 21:11:13', '2026-09-11 03:36:31'),
(8, 3, 7, 1, 'Owner Approved Production', 'Abdullah approved Ali\'s production on \"Ansari 01\"', 'approved', 0, '2026-09-06 21:11:13', '2026-09-11 03:36:31'),
(9, 5, 10, 1, 'Production Approved', 'Your production has been approved by Abdullah', 'approved', 0, '2026-09-06 21:12:00', '2026-09-11 03:36:31'),
(10, 3, 10, 1, 'Owner Approved Production', 'Abdullah approved Husnain\'s production on \"Ansari 02\"', 'approved', 0, '2026-09-06 21:12:00', '2026-09-11 03:36:31'),
(11, 4, 12, 3, 'Production Approved', 'Your production has been approved by Marsad', 'approved', 0, '2026-09-07 01:24:10', '2026-09-11 03:36:31'),
(12, 1, 12, 3, 'Manager Approved Production', 'Marsad approved Zaid\'s production on \"Ansari 04\"', 'approved', 0, '2026-09-07 01:24:10', '2026-09-11 03:36:31'),
(13, 4, 6, 3, 'Production Approved', 'Your production has been approved by Marsad', 'approved', 0, '2026-09-07 01:24:11', '2026-09-11 03:36:31'),
(14, 1, 6, 3, 'Manager Approved Production', 'Marsad approved Zaid\'s production on \"Ansari 04\"', 'approved', 0, '2026-09-07 01:24:11', '2026-09-11 03:36:31'),
(15, 4, 11, 1, 'Production Approved', 'Your production has been approved by Abdullah', 'approved', 0, '2026-09-07 01:36:02', '2026-09-11 03:36:31'),
(16, 3, 11, 1, 'Owner Approved Production', 'Abdullah approved Zaid\'s production on \"Ansari 03\"', 'approved', 0, '2026-09-07 01:36:02', '2026-09-11 03:36:31'),
(17, 7, 45, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:47:44', '2026-09-11 00:47:44'),
(18, 8, 46, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:47:44', '2026-09-11 00:47:44'),
(19, 7, 47, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:49:55', '2026-09-11 00:49:55'),
(20, 8, 48, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:49:55', '2026-09-11 00:49:55'),
(21, 7, 49, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:50:49', '2026-09-11 00:50:49'),
(22, 8, 50, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:50:49', '2026-09-11 00:50:49'),
(23, 7, 51, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:51:21', '2026-09-11 00:51:21'),
(24, 8, 52, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:51:21', '2026-09-11 00:51:21'),
(25, 7, 53, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:51:53', '2026-09-11 00:51:53'),
(26, 8, 54, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:51:53', '2026-09-11 00:51:53'),
(27, 7, 55, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:52:21', '2026-09-11 00:52:21'),
(28, 8, 56, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:52:21', '2026-09-11 00:52:21'),
(29, 7, 57, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:52:49', '2026-09-11 00:52:49'),
(30, 8, 58, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:52:49', '2026-09-11 00:52:49'),
(31, 7, 59, 1, 'New Production assigned', 'Arqam assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:53:15', '2026-09-11 00:53:15'),
(32, 8, 60, 1, 'New Production assigned', 'Talha assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:53:15', '2026-09-11 00:53:15'),
(33, 9, 61, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:54:22', '2026-09-11 00:54:22'),
(34, 10, 62, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:54:22', '2026-09-11 00:54:22'),
(35, 9, 63, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:54:48', '2026-09-11 00:54:48'),
(36, 10, 64, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:54:48', '2026-09-11 00:54:48'),
(37, 9, 65, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:55:34', '2026-09-11 00:55:34'),
(38, 10, 66, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:55:34', '2026-09-11 00:55:34'),
(39, 9, 67, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:56:04', '2026-09-11 00:56:04'),
(40, 10, 68, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:56:04', '2026-09-11 00:56:04'),
(41, 9, 69, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:57:41', '2026-09-11 00:57:41'),
(42, 10, 70, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:57:41', '2026-09-11 00:57:41'),
(43, 9, 71, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:58:19', '2026-09-11 00:58:19'),
(44, 10, 72, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:58:19', '2026-09-11 00:58:19'),
(45, 9, 73, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:58:54', '2026-09-11 00:58:54'),
(46, 10, 74, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:58:54', '2026-09-11 00:58:54'),
(47, 9, 75, 1, 'New Production assigned', 'Musa assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:59:17', '2026-09-11 00:59:17'),
(48, 10, 76, 1, 'New Production assigned', 'Khizar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 00:59:17', '2026-09-11 00:59:17'),
(49, 12, 83, 1, 'New Production assigned', 'Umar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:34:33', '2026-09-11 02:34:33'),
(50, 12, 84, 1, 'New Production assigned', 'Umar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:36:25', '2026-09-11 02:36:25'),
(51, 13, 85, 1, 'New Production assigned', 'Sudais assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:37:55', '2026-09-11 02:37:55'),
(52, 13, 86, 1, 'New Production assigned', 'Sudais assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:38:39', '2026-09-11 02:38:39'),
(53, 14, 87, 1, 'New Production assigned', 'Ammar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:40:58', '2026-09-11 02:40:58'),
(54, 14, 88, 1, 'New Production assigned', 'Ammar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 02:41:28', '2026-09-11 02:41:28'),
(55, 16, 113, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:03', '2026-09-11 08:01:03'),
(56, 17, 114, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:03', '2026-09-11 08:01:03'),
(57, 16, 115, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:30', '2026-09-11 08:01:30'),
(58, 17, 116, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:30', '2026-09-11 08:01:30'),
(59, 16, 117, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:56', '2026-09-11 08:01:56'),
(60, 17, 118, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:01:56', '2026-09-11 08:01:56'),
(61, 16, 119, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:02:24', '2026-09-11 08:02:24'),
(62, 17, 120, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:02:24', '2026-09-11 08:02:24'),
(63, 16, 121, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:02:49', '2026-09-11 08:02:49'),
(64, 17, 122, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:02:49', '2026-09-11 08:02:49'),
(65, 16, 123, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:03:19', '2026-09-11 08:03:19'),
(66, 17, 124, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:03:19', '2026-09-11 08:03:19'),
(67, 16, 125, 1, 'New Production assigned', 'Kuzaima assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:03:49', '2026-09-11 08:03:49'),
(68, 17, 126, 1, 'New Production assigned', 'Akram assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:03:49', '2026-09-11 08:03:49'),
(69, 18, 127, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:04:39', '2026-09-11 08:04:39'),
(70, 19, 128, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:04:39', '2026-09-11 08:04:39'),
(71, 18, 129, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:05:06', '2026-09-11 08:05:06'),
(72, 19, 130, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:05:06', '2026-09-11 08:05:06'),
(73, 18, 131, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:05:35', '2026-09-11 08:05:35'),
(74, 19, 132, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:05:35', '2026-09-11 08:05:35'),
(75, 18, 133, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:05', '2026-09-11 08:06:05'),
(76, 19, 134, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:05', '2026-09-11 08:06:05'),
(77, 18, 135, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:30', '2026-09-11 08:06:30'),
(78, 19, 136, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:30', '2026-09-11 08:06:30'),
(79, 18, 137, 1, 'New Production assigned', 'Ameen assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:59', '2026-09-11 08:06:59'),
(80, 19, 138, 1, 'New Production assigned', 'Munawar assigned production by Abdullah', 'production_assigned', 0, '2026-09-11 08:06:59', '2026-09-11 08:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view factories', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(2, 'create factories', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(3, 'edit factories', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(4, 'delete factories', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(5, 'view machines', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(6, 'create machines', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(7, 'edit machines', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(8, 'delete machines', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(9, 'view productions', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(10, 'create productions', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(11, 'edit productions', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(12, 'delete productions', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(13, 'view employees', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(14, 'create employees', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(15, 'edit employees', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(16, 'delete employees', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(17, 'view attendance', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(18, 'create attendance', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(19, 'edit attendance', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(20, 'delete attendance', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(21, 'view users', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(22, 'create users', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(23, 'edit users', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(24, 'delete users', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(25, 'approve production', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(26, 'reject production', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(27, 'mark attendance', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(28, 'scan qr', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(29, 'view profile', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '96208c9d354543c26c8bb74a39794bac0f052525efdf0f5bf4d782d72f6d2694', '[\"*\"]', '2026-06-20 02:23:12', NULL, '2026-06-20 02:23:09', '2026-06-20 02:23:12'),
(2, 'App\\Models\\User', 1, 'auth_token', '0f6726b30c08f5303daaa5b61cf0f9c6057d9a02fa5bf00f699d82c260c70f67', '[\"*\"]', '2026-06-20 03:56:19', NULL, '2026-06-20 02:30:52', '2026-06-20 03:56:19'),
(3, 'App\\Models\\User', 1, 'auth_token', 'de8e4a711254a104e84bf5be4a33887919159c3b1613685528772bd6c59b620f', '[\"*\"]', '2026-06-20 04:28:41', NULL, '2026-06-20 03:59:04', '2026-06-20 04:28:41'),
(4, 'App\\Models\\User', 1, 'auth_token', 'e8014a682e32f41c20956a0c6af1373e044430f65b6ecdc1c4b863ab116b5e94', '[\"*\"]', '2026-06-20 08:40:10', NULL, '2026-06-20 08:35:35', '2026-06-20 08:40:10'),
(5, 'App\\Models\\User', 1, 'auth_token', '1d2cc3ef814369eb9f44c70c529e58cc484579e5b836d35f1c311fa9733499bb', '[\"*\"]', '2026-06-20 08:42:42', NULL, '2026-06-20 08:41:17', '2026-06-20 08:42:42'),
(6, 'App\\Models\\User', 1, 'auth_token', '18f3c8c3383eb0109ced76f06c01e68ab87bd4e907ff812028628246d2f19437', '[\"*\"]', '2026-06-20 09:48:43', NULL, '2026-06-20 09:41:29', '2026-06-20 09:48:43'),
(7, 'App\\Models\\User', 1, 'auth_token', '7e85bb31f22e7c79526b1612552535ad8280337b00f7dcbe837cedae1501cd67', '[\"*\"]', '2026-06-21 06:14:17', NULL, '2026-06-20 10:02:59', '2026-06-21 06:14:17'),
(8, 'App\\Models\\User', 1, 'auth_token', 'de8c9faa50491c0a4583dd21c9b4301f8e0ba063c7bd54802d4859a179b70843', '[\"*\"]', '2026-06-21 09:28:17', NULL, '2026-06-21 09:27:57', '2026-06-21 09:28:17'),
(9, 'App\\Models\\User', 1, 'auth_token', '0fa51a961ec5cd241108b15914465d1037a780802f7398d677451758bade0433', '[\"*\"]', '2026-06-21 10:42:22', NULL, '2026-06-21 10:34:44', '2026-06-21 10:42:22'),
(10, 'App\\Models\\User', 1, 'auth_token', 'fe5de2fd880b3c01df24d72530bdb4e0e62567959231d15b3586972913be6319', '[\"*\"]', '2026-06-21 11:08:54', NULL, '2026-06-21 10:53:52', '2026-06-21 11:08:54'),
(11, 'App\\Models\\User', 1, 'auth_token', 'fa9e85083c0136ba978ca83d7762e1aec957ff990794149d3b74ad9cc48a7780', '[\"*\"]', '2026-06-21 11:39:32', NULL, '2026-06-21 11:37:17', '2026-06-21 11:39:32'),
(12, 'App\\Models\\User', 1, 'auth_token', '290d0c76746ca6712969dc7afd4eeffd5050693085964fba5f7c3908a6495c75', '[\"*\"]', '2026-06-21 12:18:39', NULL, '2026-06-21 12:10:50', '2026-06-21 12:18:39'),
(13, 'App\\Models\\User', 1, 'auth_token', 'c0312eed71615b6aa39b9f9c8f4f51ecc83a60ac3bb4bc88eb3a58a70dd74d96', '[\"*\"]', '2026-06-21 17:45:40', NULL, '2026-06-21 17:32:18', '2026-06-21 17:45:40'),
(14, 'App\\Models\\User', 1, 'auth_token', '94252959c49bece48896583a937e9584575cca18531a3ed872aa6b065e9828b8', '[\"*\"]', '2026-06-21 18:02:10', NULL, '2026-06-21 18:01:32', '2026-06-21 18:02:10'),
(15, 'App\\Models\\User', 1, 'auth_token', '1c57d845e5dbfc24b7a3785abe13ea3227596574f6a5c0072f8f44345759143d', '[\"*\"]', '2026-06-21 19:38:46', NULL, '2026-06-21 18:13:19', '2026-06-21 19:38:46'),
(16, 'App\\Models\\User', 1, 'auth_token', '240419a74a58ef52ccbe4432f7f60d9974f4036d2f3f6cc2a879ebecf0aa6973', '[\"*\"]', '2026-06-21 19:36:55', NULL, '2026-06-21 19:33:13', '2026-06-21 19:36:55'),
(17, 'App\\Models\\User', 1, 'auth_token', 'ea4e48c3528883b3674f9dd22016fe75bd6d0ab138693e660425ac9a48e7a54a', '[\"*\"]', '2026-06-22 01:54:52', NULL, '2026-06-21 19:44:02', '2026-06-22 01:54:52'),
(18, 'App\\Models\\User', 1, 'auth_token', '07a7ae9f29e74010f0ddb7481553c0db944b691df93aaae4cae8c593dbf7504b', '[\"*\"]', '2026-06-22 01:54:32', NULL, '2026-06-21 20:01:46', '2026-06-22 01:54:32'),
(19, 'App\\Models\\User', 1, 'auth_token', '1a6440e91882bae97f8ec48e66f24fa70ae1b1bf9fc1e57dc6e759e01d6312f9', '[\"*\"]', '2026-06-21 20:08:24', NULL, '2026-06-21 20:08:15', '2026-06-21 20:08:24'),
(20, 'App\\Models\\User', 1, 'auth_token', '8d2a8f7f445a4909043b3533329d4d5edf8bc51048ace467ab9d6bce766aed0b', '[\"*\"]', '2026-06-21 20:13:37', NULL, '2026-06-21 20:12:25', '2026-06-21 20:13:37'),
(21, 'App\\Models\\User', 1, 'auth_token', '137b8485fb3637609ef374e3f32ada16bb9303b4c34a52eb5f856a6bd2e6d8bf', '[\"*\"]', '2026-06-21 20:31:30', NULL, '2026-06-21 20:26:41', '2026-06-21 20:31:30'),
(22, 'App\\Models\\User', 1, 'auth_token', '4f933d45b931ba295327fcfbfe7c4ade1e9a88afb4373487d378308d8cbc1c08', '[\"*\"]', '2026-06-21 20:41:45', NULL, '2026-06-21 20:41:40', '2026-06-21 20:41:45'),
(23, 'App\\Models\\User', 1, 'auth_token', '5102a0c546e79a4ab5729d92d8791650437ee6f3ff610b555d91fb51d9cd27e5', '[\"*\"]', '2026-06-21 20:48:10', NULL, '2026-06-21 20:44:52', '2026-06-21 20:48:10'),
(24, 'App\\Models\\User', 1, 'auth_token', '3f912111241e1e4ded5c555e600685534beb1ffaeacf807408850ce0fd55f8c7', '[\"*\"]', '2026-06-21 20:52:45', NULL, '2026-06-21 20:52:26', '2026-06-21 20:52:45'),
(25, 'App\\Models\\User', 1, 'auth_token', 'ed2155c1f0ca3d7454c99270e1a20b307cca64aefb7a0745af8d2f5b06a225e5', '[\"*\"]', '2026-06-21 20:55:08', NULL, '2026-06-21 20:54:47', '2026-06-21 20:55:08'),
(26, 'App\\Models\\User', 1, 'auth_token', 'd72d97734422de8032473ba52d77d7b529056b4deb27d22b367153587d868a5a', '[\"*\"]', '2026-06-22 00:36:43', NULL, '2026-06-21 20:57:21', '2026-06-22 00:36:43'),
(27, 'App\\Models\\User', 1, 'auth_token', '91266fae6627a6868dfd1b16a5061cc91fef6d488b611aaa6a32c3414b369472', '[\"*\"]', '2026-06-22 00:40:36', NULL, '2026-06-22 00:38:20', '2026-06-22 00:40:36'),
(28, 'App\\Models\\User', 9, 'auth_token', 'e1b64f999c56a7048cf29874cc5055febd0c21f8114f8498e399ff3e7d4ec92e', '[\"*\"]', '2026-06-22 00:45:36', NULL, '2026-06-22 00:41:04', '2026-06-22 00:45:36'),
(29, 'App\\Models\\User', 9, 'auth_token', 'c800c9136282a796e929b094baf3e96f16378a9d6273cff14f68637d869b7e68', '[\"*\"]', '2026-06-22 00:51:54', NULL, '2026-06-22 00:51:04', '2026-06-22 00:51:54'),
(30, 'App\\Models\\User', 1, 'auth_token', '7818bdce00b939062ced972517d9529af804856403853cb5fc2bdb86f474ea3e', '[\"*\"]', '2026-06-22 01:53:16', NULL, '2026-06-22 00:54:29', '2026-06-22 01:53:16'),
(31, 'App\\Models\\User', 9, 'auth_token', '7d27de30624344b9319786c3581b6549c42631079620c53599da677682fc42c0', '[\"*\"]', '2026-06-22 01:57:02', NULL, '2026-06-22 01:53:59', '2026-06-22 01:57:02'),
(32, 'App\\Models\\User', 7, 'auth_token', '7367136ce042bd77437d984981daad104053b5509fe2840f04a4fbba1eb5c6c5', '[\"*\"]', '2026-06-22 01:59:02', NULL, '2026-06-22 01:58:50', '2026-06-22 01:59:02'),
(33, 'App\\Models\\User', 1, 'auth_token', '24e4f15a430c083742708acc462dc30ffa31c82cf7a11145d2719cfc952b80a6', '[\"*\"]', '2026-06-22 02:13:54', NULL, '2026-06-22 02:03:16', '2026-06-22 02:13:54'),
(34, 'App\\Models\\User', 9, 'auth_token', '43bd259fc471a1bb43045d24e573fc5db76a178a23d1d7663335b170da3793d1', '[\"*\"]', '2026-06-22 02:16:05', NULL, '2026-06-22 02:15:08', '2026-06-22 02:16:05'),
(35, 'App\\Models\\User', 1, 'auth_token', 'b8df9b5a1fa0d07e7cd04655a9a7ba2475dab3cf70b208023c094fdb59f5b5f8', '[\"*\"]', '2026-06-22 02:28:54', NULL, '2026-06-22 02:28:41', '2026-06-22 02:28:54'),
(36, 'App\\Models\\User', 1, 'auth_token', '3cf52f3346e8590a387559bf4fe9d51b6d42d219fdae188998aca97ec82e7af0', '[\"*\"]', '2026-06-22 02:42:13', NULL, '2026-06-22 02:40:16', '2026-06-22 02:42:13'),
(37, 'App\\Models\\User', 1, 'auth_token', '3f58658284e856f45b9fa9b07ba9b57475a0dd5b96b79e733ae4a26ab0deafd7', '[\"*\"]', '2026-06-22 02:52:17', NULL, '2026-06-22 02:46:49', '2026-06-22 02:52:17'),
(38, 'App\\Models\\User', 1, 'auth_token', '72fbf13ede8f92a22081cc17e34bd1951d95254cca93a38915f34519f7cc740b', '[\"*\"]', '2026-06-22 03:01:25', NULL, '2026-06-22 03:01:02', '2026-06-22 03:01:25'),
(39, 'App\\Models\\User', 1, 'auth_token', 'bf84365a70e76eea474ce02bffdfd6b080ee82df6525181b944e31738e404007', '[\"*\"]', '2026-06-22 03:15:40', NULL, '2026-06-22 03:15:20', '2026-06-22 03:15:40'),
(40, 'App\\Models\\User', 1, 'auth_token', '5a967f1328d6a884c0f424ae977b3773e3453621528c2bfaf02cc5b9f58c81dc', '[\"*\"]', '2026-06-22 03:32:02', NULL, '2026-06-22 03:27:38', '2026-06-22 03:32:02'),
(41, 'App\\Models\\User', 1, 'auth_token', '66876b81eda60021f3c574d2f88a1be13ed86479d9b81d254fa35f29cb3779e1', '[\"*\"]', '2026-06-22 03:34:43', NULL, '2026-06-22 03:34:32', '2026-06-22 03:34:43'),
(42, 'App\\Models\\User', 3, 'auth_token', '86696e5e819b37de259ea1d3a9d8bbcd1954fc95a8de4335feba43969fb7d39c', '[\"*\"]', '2026-06-22 03:37:41', NULL, '2026-06-22 03:37:15', '2026-06-22 03:37:41'),
(43, 'App\\Models\\User', 1, 'auth_token', 'efb4d54297727aca6f784cf0f98c0d7a4af6e6e08ed8e0d12910d40ca282352d', '[\"*\"]', '2026-06-22 03:51:26', NULL, '2026-06-22 03:45:23', '2026-06-22 03:51:26'),
(44, 'App\\Models\\User', 1, 'auth_token', '1c0ba69420e8d2609f3633886947f5806e8e412aa656d5c5c371757829daa510', '[\"*\"]', '2026-06-22 18:41:46', NULL, '2026-06-22 18:40:46', '2026-06-22 18:41:46'),
(45, 'App\\Models\\User', 1, 'auth_token', 'be4690caf62dce128144bfd8273479db694d8839fec0ac4e016935b3d04c0a11', '[\"*\"]', '2026-06-22 18:54:14', NULL, '2026-06-22 18:54:05', '2026-06-22 18:54:14'),
(46, 'App\\Models\\User', 1, 'auth_token', '4d221a98654178bc0d6487b300550338cb6db7d1d258b234df56d1f385bccecc', '[\"*\"]', '2026-06-22 19:00:40', NULL, '2026-06-22 19:00:27', '2026-06-22 19:00:40'),
(47, 'App\\Models\\User', 1, 'auth_token', '48a584833326e38e7d6d1c0279a6bed6a3c451f1ae311fb7de4cff3123468a85', '[\"*\"]', '2026-06-22 19:04:22', NULL, '2026-06-22 19:04:13', '2026-06-22 19:04:22'),
(48, 'App\\Models\\User', 1, 'auth_token', 'a65c8f80114ee8430eb8c1c2bebf02b50c8a878011e0850ce1f60613de113957', '[\"*\"]', '2026-06-22 19:22:56', NULL, '2026-06-22 19:22:46', '2026-06-22 19:22:56'),
(49, 'App\\Models\\User', 1, 'auth_token', 'bd881ee94e66a75fb16a842afcacd870a111c5f8a4852d612397473986eba279', '[\"*\"]', '2026-06-22 19:26:32', NULL, '2026-06-22 19:25:49', '2026-06-22 19:26:32'),
(50, 'App\\Models\\User', 3, 'auth_token', '29f470dac085f731367375177ab585a5c82e5c7498050c3c363bc0571571c418', '[\"*\"]', '2026-06-22 19:28:23', NULL, '2026-06-22 19:27:59', '2026-06-22 19:28:23'),
(51, 'App\\Models\\User', 5, 'auth_token', '04d256d844bea3bcac51a205c3c514daff347c4dfd02eb66174e8db0ebc9111a', '[\"*\"]', '2026-06-22 19:28:56', NULL, '2026-06-22 19:28:44', '2026-06-22 19:28:56'),
(52, 'App\\Models\\User', 2, 'auth_token', '8876372f93a1a701bc843abc9f2bbba8305b78376aeb7f83456753518fcedc55', '[\"*\"]', NULL, NULL, '2026-06-22 19:29:21', '2026-06-22 19:29:21'),
(53, 'App\\Models\\User', 1, 'auth_token', 'a3a6c6b103b54ca33a8ca3f5711a492a192ab86a5a1b13c4346250c78d7d6066', '[\"*\"]', '2026-06-23 07:12:17', NULL, '2026-06-23 06:22:11', '2026-06-23 07:12:17'),
(54, 'App\\Models\\User', 1, 'auth_token', 'adda2956627b0b53193ee02323b034c1e12f1612113f393823c8cbcd07c55f83', '[\"*\"]', '2026-06-23 07:18:00', NULL, '2026-06-23 07:16:36', '2026-06-23 07:18:00'),
(55, 'App\\Models\\User', 1, 'auth_token', '2991a841d1e635e8fe0efd2ae1cf8d24a8eb3871322658d08164cf7253d49878', '[\"*\"]', '2026-06-23 07:56:42', NULL, '2026-06-23 07:48:30', '2026-06-23 07:56:42'),
(56, 'App\\Models\\User', 2, 'auth_token', 'f1e287ec0cc795072a4e4ad1958907b4b2ac25a1f4be8f2bfc273eef0945ae92', '[\"*\"]', NULL, NULL, '2026-06-23 12:34:49', '2026-06-23 12:34:49'),
(57, 'App\\Models\\User', 2, 'auth_token', 'c553992b93e1dbcff7d6be02265d5e930544c6bff734c88d585d0d16b7c28c7b', '[\"*\"]', NULL, NULL, '2026-06-23 13:33:44', '2026-06-23 13:33:44'),
(58, 'App\\Models\\User', 2, 'auth_token', '9afa4e5e2ff27872a0594da59bf054d11c363922f4c5c7359baf5f143ce7c900', '[\"*\"]', NULL, NULL, '2026-06-23 13:45:38', '2026-06-23 13:45:38'),
(59, 'App\\Models\\User', 2, 'auth_token', '9efbb20f49875fa17cb3f973ed6c93449da34da601fe9d2e3228cc376fe9fa16', '[\"*\"]', NULL, NULL, '2026-06-23 19:54:05', '2026-06-23 19:54:05'),
(60, 'App\\Models\\User', 3, 'auth_token', '8eb8a05add7b01c369abca19c63efbc921b086ee16b8e7f67ccd3d179a0d1447', '[\"*\"]', '2026-06-23 20:04:49', NULL, '2026-06-23 20:04:48', '2026-06-23 20:04:49'),
(61, 'App\\Models\\User', 5, 'auth_token', '5a0a65f2f5b5303ef53d7b900277f9854879f7d2fff7720bbd76a264d13c31cd', '[\"*\"]', '2026-06-23 20:08:46', NULL, '2026-06-23 20:05:27', '2026-06-23 20:08:46'),
(62, 'App\\Models\\User', 5, 'auth_token', 'c9778abefd04c2e0ce51ccbaf5432aaac0ef64cf6e4ee9e9883e0e951cf9dae3', '[\"*\"]', '2026-06-23 20:16:03', NULL, '2026-06-23 20:09:27', '2026-06-23 20:16:03'),
(63, 'App\\Models\\User', 1, 'auth_token', '5d727565962ac98917b0beca174801d2c3e99d8a2ea226bfa91523a514612016', '[\"*\"]', '2026-06-23 21:06:28', NULL, '2026-06-23 20:43:32', '2026-06-23 21:06:28'),
(64, 'App\\Models\\User', 2, 'auth_token', '0be85e398d36ffacf2f67109860894f126e7c63963065f4b6d8a853907346f1d', '[\"*\"]', '2026-06-23 20:44:34', NULL, '2026-06-23 20:44:15', '2026-06-23 20:44:34'),
(65, 'App\\Models\\User', 5, 'auth_token', 'a054e1f0f699d47547c1762db48bbe8fd7db601cc5277c7f132e11fec65d3aa1', '[\"*\"]', '2026-06-23 21:28:17', NULL, '2026-06-23 20:45:11', '2026-06-23 21:28:17'),
(66, 'App\\Models\\User', 5, 'auth_token', '666795b44471d6202eb81231735587dbf0f795f7cebf54db0de14e01bd85a60d', '[\"*\"]', '2026-06-23 21:31:10', NULL, '2026-06-23 21:29:25', '2026-06-23 21:31:10'),
(67, 'App\\Models\\User', 5, 'auth_token', '930d4b0d8b65c5504ffbe655e12b827dbcccba65187a47e2592d4df229691f56', '[\"*\"]', '2026-06-23 22:44:22', NULL, '2026-06-23 21:39:05', '2026-06-23 22:44:22'),
(68, 'App\\Models\\User', 5, 'auth_token', '194dd96b8f47c8e8c24cfeff08b3fe78a26edee133049ffdc1adef9137796acb', '[\"*\"]', '2026-06-23 23:07:15', NULL, '2026-06-23 22:48:35', '2026-06-23 23:07:15'),
(69, 'App\\Models\\User', 5, 'auth_token', 'df33e7ac4049cfd96cc8446dd902f005563938f68f5e6fc512fb94ff626a78a3', '[\"*\"]', '2026-06-24 00:26:53', NULL, '2026-06-24 00:26:51', '2026-06-24 00:26:53'),
(70, 'App\\Models\\User', 5, 'auth_token', '3bdf9d8d3ed824f135379f09acba91f53aed35e60a4bfc2bb4967d2304111e78', '[\"*\"]', '2026-06-24 00:32:03', NULL, '2026-06-24 00:27:26', '2026-06-24 00:32:03'),
(71, 'App\\Models\\User', 2, 'auth_token', '25356819bc0f2ddf808c4245b552ba7eb50c7dac519b0b1863b60f8251b00b1f', '[\"*\"]', '2026-06-24 00:28:28', NULL, '2026-06-24 00:28:14', '2026-06-24 00:28:28'),
(72, 'App\\Models\\User', 1, 'auth_token', '47981f82c50ad4a541630ed6e18058ae63d04acd34fe840ef1591fe990fcc925', '[\"*\"]', '2026-06-24 00:41:07', NULL, '2026-06-24 00:37:01', '2026-06-24 00:41:07'),
(73, 'App\\Models\\User', 5, 'auth_token', 'bbec77ae461b0859b92374f6148ffffd1d45bb1fc460d77a069fbe522f17fdad', '[\"*\"]', '2026-06-24 00:42:57', NULL, '2026-06-24 00:42:56', '2026-06-24 00:42:57'),
(74, 'App\\Models\\User', 5, 'auth_token', '581e1ffe124d5a844d200b6c4ca664580f8c337fb1aa27f52df06a4900b1e9c7', '[\"*\"]', '2026-06-24 01:08:16', NULL, '2026-06-24 01:08:14', '2026-06-24 01:08:16'),
(75, 'App\\Models\\User', 5, 'auth_token', 'f76342f553b3508be5ea41a7f5fac1395f5ad11958732495cfb0cb39868b588f', '[\"*\"]', '2026-06-24 03:34:46', NULL, '2026-06-24 01:23:29', '2026-06-24 03:34:46'),
(76, 'App\\Models\\User', 2, 'auth_token', 'ae3db07268db642439967035d3fb76da051c38a5e0a13a2879ca2072c8da2f19', '[\"*\"]', NULL, NULL, '2026-06-24 01:49:27', '2026-06-24 01:49:27'),
(77, 'App\\Models\\User', 2, 'auth_token', '61fabde80bfded947cac3ac16143a0aff5cf2c75f3ca2c3e330228b1b602c186', '[\"*\"]', NULL, NULL, '2026-06-24 02:24:53', '2026-06-24 02:24:53'),
(78, 'App\\Models\\User', 2, 'auth_token', 'c880518d3270fe1837a5c698630980db4fda2ac65ef9d8888c069d774f925474', '[\"*\"]', '2026-06-24 03:28:38', NULL, '2026-06-24 03:27:36', '2026-06-24 03:28:38'),
(79, 'App\\Models\\User', 2, 'auth_token', '3de4430e8e318c136a46be990eabcaa5c7e254cbff61cb254cdd06756394fd11', '[\"*\"]', '2026-06-24 03:37:16', NULL, '2026-06-24 03:37:07', '2026-06-24 03:37:16'),
(80, 'App\\Models\\User', 2, 'auth_token', '3d51220d32636d0318fc46e0919fb5eba9f31bad2ffd645e125dcb3977ba46d8', '[\"*\"]', NULL, NULL, '2026-06-24 03:41:49', '2026-06-24 03:41:49'),
(81, 'App\\Models\\User', 2, 'auth_token', 'a4742539949f7c1e7e2c461e286908b4b75a2b96dc7da27bb73ee950fe87f7c7', '[\"*\"]', NULL, NULL, '2026-06-24 03:42:55', '2026-06-24 03:42:55'),
(82, 'App\\Models\\User', 1, 'auth_token', '43716454ad12ae72d055bb8e07526730ef621cdec4b222e932e196917765fdf4', '[\"*\"]', '2026-06-25 01:45:08', NULL, '2026-06-25 01:45:02', '2026-06-25 01:45:08'),
(83, 'App\\Models\\User', 2, 'auth_token', '4b42d4f3a63f75cac28bafc9b3dca1a213af4edff8a90fb810bfef3dc030b3cd', '[\"*\"]', '2026-06-25 01:47:37', NULL, '2026-06-25 01:45:28', '2026-06-25 01:47:37'),
(84, 'App\\Models\\User', 5, 'auth_token', '7bec060a1e52bcb793250ebb0b07987cffa269a3aa778d945e2474a8cece66ca', '[\"*\"]', '2026-06-25 01:46:29', NULL, '2026-06-25 01:45:53', '2026-06-25 01:46:29'),
(85, 'App\\Models\\User', 2, 'auth_token', '50b230abf7fd1ad0e28170f48b81f9c38e0393274a9a3cb8e81147cb3b7e98e3', '[\"*\"]', NULL, NULL, '2026-06-25 02:45:04', '2026-06-25 02:45:04'),
(86, 'App\\Models\\User', 2, 'auth_token', 'de56b24cdad96bf949677d6e417b6f3148a28d7424a0ae7082591a01dff53353', '[\"*\"]', NULL, NULL, '2026-06-25 02:45:53', '2026-06-25 02:45:53'),
(87, 'App\\Models\\User', 2, 'auth_token', '39f0192c27b984fa30b5cea9cc42f33eed8d5e86b3c08160478acf4746122e3f', '[\"*\"]', '2026-06-25 03:33:55', NULL, '2026-06-25 03:22:48', '2026-06-25 03:33:55'),
(88, 'App\\Models\\User', 2, 'auth_token', 'd8b71febc98b2b92bf7d8bfa2fc397afc3f21379f3c9ab464283587c9722225e', '[\"*\"]', '2026-06-25 06:34:38', NULL, '2026-06-25 06:14:46', '2026-06-25 06:34:38'),
(89, 'App\\Models\\User', 1, 'auth_token', 'fc2648d2ce59152df0e357133a8735b0af635e7c1b47e49d4e9e0162a353d55d', '[\"*\"]', '2026-06-25 06:37:42', NULL, '2026-06-25 06:15:07', '2026-06-25 06:37:42'),
(90, 'App\\Models\\User', 1, 'auth_token', '5ea01718b2604d56e6a655af22eb556202b25d7aa148521efe665e11bc0ae79d', '[\"*\"]', '2026-06-25 07:36:59', NULL, '2026-06-25 07:36:49', '2026-06-25 07:36:59'),
(91, 'App\\Models\\User', 1, 'auth_token', '31bcacd4657580531ee0721e16b34ea7e8008b6fd11241b0318390d572c038b9', '[\"*\"]', '2026-06-25 08:05:31', NULL, '2026-06-25 08:05:28', '2026-06-25 08:05:31'),
(92, 'App\\Models\\User', 1, 'auth_token', '1c73b1ecfccdfa69755943cc5e7ba92ba2bae443ea7a4741a305bfd8096c5658', '[\"*\"]', '2026-06-25 08:45:01', NULL, '2026-06-25 08:44:56', '2026-06-25 08:45:01'),
(93, 'App\\Models\\User', 1, 'auth_token', 'dfd2df2dcedfbbf6f569ddf0e7993118b54de13e8b582b4cef9738505a264e1a', '[\"*\"]', '2026-06-25 09:00:29', NULL, '2026-06-25 08:50:25', '2026-06-25 09:00:29'),
(94, 'App\\Models\\User', 1, 'auth_token', '1186e9a20a74a3ba6762433361585fa4e62442dee55c911e449080985d014b0e', '[\"*\"]', '2026-06-25 09:54:04', NULL, '2026-06-25 09:53:50', '2026-06-25 09:54:04'),
(95, 'App\\Models\\User', 1, 'auth_token', 'c62d366d13fd23c9805ed25b589c963932e9561ba6539e6b1418e88b367338f0', '[\"*\"]', '2026-06-25 09:58:49', NULL, '2026-06-25 09:58:43', '2026-06-25 09:58:49'),
(96, 'App\\Models\\User', 2, 'auth_token', '0a44051171751a1beae4dd34ddec38d6db223c6cae632078ba6a977770a4cfc1', '[\"*\"]', NULL, NULL, '2026-06-25 10:09:59', '2026-06-25 10:09:59'),
(97, 'App\\Models\\User', 2, 'auth_token', '1988bac2f7e2882fb2085380b3f2ffc32367649a4cb4dfd51c62ba7d2d371ece', '[\"*\"]', '2026-06-25 11:06:55', NULL, '2026-06-25 10:22:18', '2026-06-25 11:06:55'),
(98, 'App\\Models\\User', 1, 'auth_token', 'b01abf178fad4bf3d514fa59f7b1fc228a7c3bad3f0741eae06afa6a53668297', '[\"*\"]', '2026-06-25 10:23:34', NULL, '2026-06-25 10:23:27', '2026-06-25 10:23:34'),
(99, 'App\\Models\\User', 5, 'auth_token', '29f78c90fa8bacb6ca3271658c9b56adad76192bea5327210dc6676281b394a2', '[\"*\"]', '2026-06-26 00:18:30', NULL, '2026-06-26 00:18:25', '2026-06-26 00:18:30'),
(100, 'App\\Models\\User', 2, 'auth_token', '918e248c11c7554b81dc4572d55f71d300bc4ecd616d19e6cba0979212c7a302', '[\"*\"]', NULL, NULL, '2026-06-26 00:19:15', '2026-06-26 00:19:15'),
(101, 'App\\Models\\User', 1, 'auth_token', '8bcf3422899f04f45311186a4387023dff1cfe087e206726370acb0eba240166', '[\"*\"]', '2026-06-26 00:26:13', NULL, '2026-06-26 00:20:12', '2026-06-26 00:26:13'),
(102, 'App\\Models\\User', 1, 'auth_token', 'b55ea8083c7b4960f7a2dadfe2b98f9508b80325669eb5007119b34a757b8cf5', '[\"*\"]', '2026-06-26 02:15:05', NULL, '2026-06-26 00:26:59', '2026-06-26 02:15:05'),
(103, 'App\\Models\\User', 1, 'auth_token', 'f1e273502f8bcacdb877a0f66871d20f233706ff63dd47cef52d46d0344bb376', '[\"*\"]', '2026-06-26 06:05:11', NULL, '2026-06-26 05:49:44', '2026-06-26 06:05:11'),
(104, 'App\\Models\\User', 1, 'auth_token', '6a20d1745df2db1e49b52d07a328d403f384a19e59002c9cb7dd1acf5286a6e9', '[\"*\"]', '2026-06-26 06:34:14', NULL, '2026-06-26 06:05:58', '2026-06-26 06:34:14'),
(105, 'App\\Models\\User', 5, 'auth_token', 'cb578f0c257f78756272b80618af4e2ee9ce8d66ee69984c52fa67361bc7a844', '[\"*\"]', '2026-06-26 06:35:19', NULL, '2026-06-26 06:35:18', '2026-06-26 06:35:19'),
(106, 'App\\Models\\User', 1, 'auth_token', '8e0aae1a996122fbbdb8bdcaa6e2ed16b991428489d9317ae7045a252f4f3dcb', '[\"*\"]', '2026-06-26 06:37:30', NULL, '2026-06-26 06:36:21', '2026-06-26 06:37:30'),
(107, 'App\\Models\\User', 1, 'auth_token', '9c40605eeb0c96f88f4c601b0eea9eccdbe4a0c019b6dc5903e5b9c854f8f051', '[\"*\"]', '2026-06-26 07:07:50', NULL, '2026-06-26 06:45:51', '2026-06-26 07:07:50'),
(108, 'App\\Models\\User', 1, 'auth_token', '49b332a53108b9e9fdf24098f78e71b0fbbb76b354b2acc36676bad7cace17cf', '[\"*\"]', '2026-06-26 07:33:47', NULL, '2026-06-26 07:08:20', '2026-06-26 07:33:47'),
(109, 'App\\Models\\User', 1, 'auth_token', '672168b67d4ace6577a67603ac279c6c98eddc7b75196bf10acb7d499af7042c', '[\"*\"]', '2026-06-26 08:25:38', NULL, '2026-06-26 07:34:22', '2026-06-26 08:25:38'),
(110, 'App\\Models\\User', 1, 'auth_token', 'fc9e87ebe13a5746696ef05b2f3841576d5bd271d55da4c0a38efeca3f0455e3', '[\"*\"]', '2026-06-26 08:27:35', NULL, '2026-06-26 08:26:17', '2026-06-26 08:27:35'),
(111, 'App\\Models\\User', 2, 'auth_token', '4dd7cc7fc0c3fc1966cb0065a77a3cd32cb9f1f75a4e407f11b0268838f24f1f', '[\"*\"]', '2026-06-27 04:18:07', NULL, '2026-06-27 02:01:00', '2026-06-27 04:18:07'),
(112, 'App\\Models\\User', 3, 'auth_token', 'f498f25f1cdb1324138f215a4a6895ed17fdde86906fa08709b7de95455f7a1f', '[\"*\"]', '2026-06-27 02:01:16', NULL, '2026-06-27 02:01:14', '2026-06-27 02:01:16'),
(113, 'App\\Models\\User', 1, 'auth_token', 'e0978c1c3682e56975990b58ca60d53d6a9f0b49391f919c3659adc27e4d484c', '[\"*\"]', '2026-06-27 02:14:50', NULL, '2026-06-27 02:01:31', '2026-06-27 02:14:50'),
(114, 'App\\Models\\User', 5, 'auth_token', '67245c575b29157e3a43435b2a28f34bfc74a44b28fb3471a163539a7c928b40', '[\"*\"]', '2026-06-27 04:25:26', NULL, '2026-06-27 02:02:02', '2026-06-27 04:25:26'),
(115, 'App\\Models\\User', 1, 'auth_token', '4b6ea8a8c50bfc87c07ab0b91f32581a75826fc54899ba85a9a94631e117bdd8', '[\"*\"]', '2026-06-27 02:34:29', NULL, '2026-06-27 02:17:34', '2026-06-27 02:34:29'),
(116, 'App\\Models\\User', 1, 'auth_token', '996b3da1bb3cc3f62e7b79e74f3653e781c5be3c04ece593433ed199530b42e4', '[\"*\"]', '2026-06-27 02:43:31', NULL, '2026-06-27 02:36:50', '2026-06-27 02:43:31'),
(117, 'App\\Models\\User', 1, 'auth_token', 'b811f961e14a75d16ecb2d945cd7f48ec957679cbccf5242a713332574c349b3', '[\"*\"]', '2026-06-27 03:21:40', NULL, '2026-06-27 02:44:10', '2026-06-27 03:21:40'),
(118, 'App\\Models\\User', 1, 'auth_token', '06085ab7858b9024322b5103b8a8bcc458c07bc6dc2981572c7fa5ac8cc39291', '[\"*\"]', '2026-06-27 04:05:54', NULL, '2026-06-27 03:43:48', '2026-06-27 04:05:54'),
(119, 'App\\Models\\User', 1, 'auth_token', '1c7703946b6e087fc79267be2c70c1182b54459d7919f2aeb3c8c7009a5308ef', '[\"*\"]', '2026-06-27 04:44:00', NULL, '2026-06-27 04:10:26', '2026-06-27 04:44:00'),
(120, 'App\\Models\\User', 1, 'auth_token', 'd39d8afc05f58c3c92fb47f02c3e0b6790ce747468876c90d70e9fb2b62327ba', '[\"*\"]', '2026-06-28 10:54:29', NULL, '2026-06-28 10:14:22', '2026-06-28 10:54:29'),
(121, 'App\\Models\\User', 3, 'auth_token', '77482e95bd99ffee5a5ce31520e3725448f3bdffd6fc3a784d31a561a446000c', '[\"*\"]', '2026-06-28 11:09:11', NULL, '2026-06-28 11:09:10', '2026-06-28 11:09:11'),
(122, 'App\\Models\\User', 5, 'auth_token', '89176f67c23992c79a00b07d621ef365eb67871a98db6131d00c101779efc79e', '[\"*\"]', '2026-06-28 11:10:40', NULL, '2026-06-28 11:09:32', '2026-06-28 11:10:40'),
(123, 'App\\Models\\User', 1, 'auth_token', 'f71109944448f34d5962be8f2bb77fa44547412ac93a15314cfd75be4bb832b1', '[\"*\"]', '2026-06-28 20:49:23', NULL, '2026-06-28 11:14:29', '2026-06-28 20:49:23'),
(124, 'App\\Models\\User', 1, 'auth_token', '36565a9f216b0375c2a0cc69dcdeba2b1026401f6e17b6b239b8e05df535d84b', '[\"*\"]', '2026-06-29 04:06:26', NULL, '2026-06-28 19:49:27', '2026-06-29 04:06:26'),
(125, 'App\\Models\\User', 1, 'auth_token', 'cff768ffeb97f484a83c0bfa21bdeda0ab5ca18c8727b1744cdc09b93a6312d8', '[\"*\"]', '2026-06-29 04:46:49', NULL, '2026-06-29 04:07:19', '2026-06-29 04:46:49'),
(126, 'App\\Models\\User', 1, 'auth_token', 'e4645bc06453d744fc28b6f5cefd3c0e8c8639b2ae3cfd6f8aaa89b11e841136', '[\"*\"]', '2026-06-29 04:48:03', NULL, '2026-06-29 04:48:01', '2026-06-29 04:48:03'),
(127, 'App\\Models\\User', 1, 'auth_token', '40efb59aa0bd8d8bd81b81dee9d7ffcb8f5327562f8721ebab154d02c2350c56', '[\"*\"]', '2026-06-29 04:54:34', NULL, '2026-06-29 04:48:44', '2026-06-29 04:54:34'),
(128, 'App\\Models\\User', 2, 'auth_token', '7af473d3c75e100bd9a92b6fdf0fba7ca8b63ca53052b892fa62f55e960dbc53', '[\"*\"]', NULL, NULL, '2026-06-29 04:54:48', '2026-06-29 04:54:48'),
(129, 'App\\Models\\User', 2, 'auth_token', 'db008f460b8db87bd2b2053b791a0882260f3d51c449cf2cf4d7be8cc064463b', '[\"*\"]', NULL, NULL, '2026-06-29 05:01:30', '2026-06-29 05:01:30'),
(130, 'App\\Models\\User', 5, 'auth_token', '9e125873e621600cf3c8644fba3aee79c4cfac599c9c51269aa8e9c90ba2e49e', '[\"*\"]', '2026-06-29 05:03:43', NULL, '2026-06-29 05:03:42', '2026-06-29 05:03:43'),
(131, 'App\\Models\\User', 1, 'auth_token', 'ecde60c447869d58b0d260c796bde40b6b1bd6a95a042a07a7b7cf7b4b42b19a', '[\"*\"]', '2026-06-29 11:28:58', NULL, '2026-06-29 11:26:47', '2026-06-29 11:28:58'),
(132, 'App\\Models\\User', 1, 'auth_token', '1961cbf04354752b568b2502a0a2889fe8d2e917b9b263d2917691c2c5241588', '[\"*\"]', '2026-06-29 11:43:42', NULL, '2026-06-29 11:42:42', '2026-06-29 11:43:42'),
(133, 'App\\Models\\User', 1, 'auth_token', '76f24163af5693a16ca5b14860b5e1c38bcc3ead0b194b13a7718b1d4e46ef62', '[\"*\"]', '2026-06-29 12:57:17', NULL, '2026-06-29 12:04:34', '2026-06-29 12:57:17'),
(134, 'App\\Models\\User', 1, 'auth_token', '1dd5eb94502471a234779544da91a0c6e489a54c8e6b23fa7a6102a8e60211df', '[\"*\"]', '2026-06-29 13:25:09', NULL, '2026-06-29 12:57:52', '2026-06-29 13:25:09'),
(135, 'App\\Models\\User', 1, 'auth_token', '73e58fd0fc4e7340c264a384b8eb6c17ac71f183fce487aa248fa9fb291d453f', '[\"*\"]', '2026-06-29 13:44:36', NULL, '2026-06-29 13:27:38', '2026-06-29 13:44:36'),
(136, 'App\\Models\\User', 1, 'auth_token', '07404647fb88282b40f32fd5a133bd1f8e18188ce46f7a82d0864cf4826f6a8f', '[\"*\"]', '2026-06-29 14:07:58', NULL, '2026-06-29 14:07:39', '2026-06-29 14:07:58'),
(137, 'App\\Models\\User', 1, 'auth_token', '75c901c3521e457a8c2e126e39999b69032db5aaf12820d47f8d104fd169d18e', '[\"*\"]', '2026-06-29 14:12:32', NULL, '2026-06-29 14:12:28', '2026-06-29 14:12:32'),
(138, 'App\\Models\\User', 1, 'auth_token', '1d118d62567a6f3293ce267c6800130096b51e6ecab4805622de2bbde35ae824', '[\"*\"]', '2026-06-29 14:31:38', NULL, '2026-06-29 14:15:35', '2026-06-29 14:31:38'),
(139, 'App\\Models\\User', 1, 'auth_token', '8d984d3e64378467422a8d000f12f7a3393538774b2302863d458570653dd3fc', '[\"*\"]', '2026-06-29 14:37:56', NULL, '2026-06-29 14:37:29', '2026-06-29 14:37:56'),
(140, 'App\\Models\\User', 1, 'auth_token', '7939eca101efbebb84302eac8b0eb81b09803793e3db528686f41a7c80a52ceb', '[\"*\"]', '2026-06-29 15:33:10', NULL, '2026-06-29 15:08:33', '2026-06-29 15:33:10'),
(141, 'App\\Models\\User', 1, 'auth_token', 'c019ecdb2b6f5c431825c0a4b393906690451ba7958203d9cfffe9c4d7c3d340', '[\"*\"]', '2026-06-29 16:28:58', NULL, '2026-06-29 16:28:55', '2026-06-29 16:28:58'),
(142, 'App\\Models\\User', 1, 'auth_token', '9080b6abc55979560b6aa770d09c0f70421b7c315dec020a5d4fdfea9bae4441', '[\"*\"]', '2026-06-29 19:20:36', NULL, '2026-06-29 19:19:36', '2026-06-29 19:20:36'),
(143, 'App\\Models\\User', 1, 'auth_token', '0cca81210930fdf042c68f91c43ec8efe5012324575edbae3b9dad292e0bb7b9', '[\"*\"]', '2026-06-29 19:37:01', NULL, '2026-06-29 19:36:58', '2026-06-29 19:37:01'),
(144, 'App\\Models\\User', 19, 'auth_token', 'd30a1368b0b235e5ccc41eba295a3dcebcd1ee8ea3628f5a09424996163fb419', '[\"*\"]', '2026-06-30 02:56:07', NULL, '2026-06-30 02:56:06', '2026-06-30 02:56:07'),
(145, 'App\\Models\\User', 1, 'auth_token', '6769418b1212dbb21181254cd02d85001fd6dd61973052f38e303e737f74eda1', '[\"*\"]', '2026-06-30 03:25:24', NULL, '2026-06-30 03:00:09', '2026-06-30 03:25:24'),
(146, 'App\\Models\\User', 1, 'auth_token', '5700da78f2b0e876b2e3c858ed8e1eead52da57a8eefe31f9056c2a92412cfc7', '[\"*\"]', '2026-06-30 03:39:42', NULL, '2026-06-30 03:34:17', '2026-06-30 03:39:42'),
(147, 'App\\Models\\User', 19, 'auth_token', '0dfb70dd3e9b773e29a0602b4c2104927802da168d88d58d11ed033b3ae07811', '[\"*\"]', '2026-06-30 04:10:20', NULL, '2026-06-30 04:09:56', '2026-06-30 04:10:20'),
(148, 'App\\Models\\User', 1, 'auth_token', '0f3a3023ea9546a4bcac4faaa7e1d9380c39123f695bb79a0f83238731ce192c', '[\"*\"]', '2026-06-30 04:11:14', NULL, '2026-06-30 04:11:11', '2026-06-30 04:11:14'),
(149, 'App\\Models\\User', 2, 'auth_token', 'd7bff8c624230f5e69f71b8324f1b30db9ef3770be1be414c4dad6e63e3f13a5', '[\"*\"]', NULL, NULL, '2026-06-30 04:11:54', '2026-06-30 04:11:54'),
(150, 'App\\Models\\User', 1, 'auth_token', '02416bfd02c923cdf6ae0b2d3681cf6a898baeb6dc3ed1333237171f97469f55', '[\"*\"]', '2026-06-30 04:20:15', NULL, '2026-06-30 04:20:04', '2026-06-30 04:20:15'),
(151, 'App\\Models\\User', 2, 'auth_token', 'b639fbd32d4658f9d143990978ab90e9a0e7e822805a2f5950d6bf1b752374d8', '[\"*\"]', NULL, NULL, '2026-06-30 04:40:02', '2026-06-30 04:40:02'),
(152, 'App\\Models\\User', 19, 'auth_token', 'b83380d5bccd1cc4cedb3c0f933cdf56de12de77dff1d061445f75f1e6940ae7', '[\"*\"]', '2026-06-30 04:40:27', NULL, '2026-06-30 04:40:26', '2026-06-30 04:40:27'),
(153, 'App\\Models\\User', 15, 'auth_token', '29a3d0a0b9e8ae27532a34e87a48dd3e009fb5dd3cf26dc36c76cd15d09fd57a', '[\"*\"]', '2026-06-30 04:40:56', NULL, '2026-06-30 04:40:55', '2026-06-30 04:40:56'),
(154, 'App\\Models\\User', 7, 'auth_token', '536dead42892851829c698657391e703655c93fa519b03afcb9390ef29a9d152', '[\"*\"]', NULL, NULL, '2026-06-30 04:42:18', '2026-06-30 04:42:18'),
(155, 'App\\Models\\User', 15, 'auth_token', '375cfc7c84f27f6a1869d9b00877f429a08af813e7660666ad9cdf9cc6aa7b87', '[\"*\"]', '2026-06-30 08:07:03', NULL, '2026-06-30 07:59:25', '2026-06-30 08:07:03'),
(156, 'App\\Models\\User', 2, 'auth_token', '2f220ee584337390dbaf17fd320c9811554b7a5bfd98c1158520bc5600de0768', '[\"*\"]', NULL, NULL, '2026-06-30 08:19:39', '2026-06-30 08:19:39'),
(157, 'App\\Models\\User', 2, 'auth_token', '011cf947291753bba1e190742eba836b61c9dc1007ca2165bfc46d5ba879bb44', '[\"*\"]', NULL, NULL, '2026-06-30 09:14:54', '2026-06-30 09:14:54'),
(158, 'App\\Models\\User', 15, 'auth_token', 'b2b686925b984083371889ffca98321ceb173808e655ed47edb6f065ac13760a', '[\"*\"]', '2026-06-30 09:20:05', NULL, '2026-06-30 09:20:03', '2026-06-30 09:20:05'),
(159, 'App\\Models\\User', 2, 'auth_token', 'ae8534bd4ebce3954e8e51e2037def464a1cbe63031876c71b2c20d863aa08a0', '[\"*\"]', NULL, NULL, '2026-06-30 09:20:34', '2026-06-30 09:20:34'),
(160, 'App\\Models\\User', 2, 'auth_token', '2d3c45f04ca708e71fcb40fd08e81acd441d02dc831900a115bebe755200ddd3', '[\"*\"]', NULL, NULL, '2026-06-30 09:48:12', '2026-06-30 09:48:12'),
(161, 'App\\Models\\User', 2, 'auth_token', 'bc1289ac8d8e315c8aa9539bfb649901850bc95f25126e8630a7895f31ea9c05', '[\"*\"]', NULL, NULL, '2026-06-30 11:56:15', '2026-06-30 11:56:15'),
(162, 'App\\Models\\User', 2, 'auth_token', '2f21363bb55907de0b7026b30f1ae1d1125f10259a8ea608d80c610f63e04f40', '[\"*\"]', NULL, NULL, '2026-06-30 12:03:23', '2026-06-30 12:03:23'),
(163, 'App\\Models\\User', 2, 'auth_token', 'b8f1f3d8a2b3310c0759292797e8e8bf154b6f3380f41d469f376a47f11f1359', '[\"*\"]', NULL, NULL, '2026-06-30 20:24:11', '2026-06-30 20:24:11'),
(164, 'App\\Models\\User', 2, 'auth_token', 'd74aec0bb2c8d9d652764d4a3560d697fd7ad98372d88017f9ec40fee6b6c0d8', '[\"*\"]', NULL, NULL, '2026-06-30 20:35:38', '2026-06-30 20:35:38'),
(165, 'App\\Models\\User', 2, 'auth_token', '17f65c84db817b5392cfb5c7955916b6d838d56dbc6c94ce31915650c634ef66', '[\"*\"]', NULL, NULL, '2026-06-30 20:38:46', '2026-06-30 20:38:46'),
(166, 'App\\Models\\User', 2, 'auth_token', '938c5d48aa18831f81cd48acefea362202bc076e4eb9b2d35628735607c536a5', '[\"*\"]', NULL, NULL, '2026-06-30 20:52:06', '2026-06-30 20:52:06'),
(167, 'App\\Models\\User', 2, 'auth_token', '15148a23a76f510217de185c7d7ca65762bf84404d171874d32b8e90b94995fa', '[\"*\"]', NULL, NULL, '2026-06-30 21:05:56', '2026-06-30 21:05:56'),
(168, 'App\\Models\\User', 2, 'auth_token', '529a2de87940c2641daa25e0d843ed65f0cd972373cf131861b7a68a62ce78a6', '[\"*\"]', NULL, NULL, '2026-06-30 21:09:24', '2026-06-30 21:09:24'),
(169, 'App\\Models\\User', 2, 'auth_token', '8aca1f85b6a8c28ac43fd09bf3bc533f5a57059370efdc76a4bee9d6c5869482', '[\"*\"]', NULL, NULL, '2026-06-30 21:21:29', '2026-06-30 21:21:29'),
(170, 'App\\Models\\User', 2, 'auth_token', '7a5b40e0046784f0f8ca51c1a70f1064ea107a858e9c0ce87c997ca740dfc765', '[\"*\"]', NULL, NULL, '2026-06-30 21:35:52', '2026-06-30 21:35:52'),
(171, 'App\\Models\\User', 15, 'auth_token', '9ff44cb5bc99882fe1ca53f16013a80bf7c4881498626ed29d9822f0dc889deb', '[\"*\"]', '2026-06-30 23:08:43', NULL, '2026-06-30 21:58:40', '2026-06-30 23:08:43'),
(172, 'App\\Models\\User', 7, 'auth_token', '663e8e584eb558f4ede6e99b6f22a21a5f4825b50b33ebcf524edf2af3db7cce', '[\"*\"]', '2026-06-30 23:30:05', NULL, '2026-06-30 22:55:20', '2026-06-30 23:30:05'),
(173, 'App\\Models\\User', 1, 'auth_token', '5f2db7fdb4ce94c8bfa1364532e0087eec21ce6409dad5907242527b550653ed', '[\"*\"]', '2026-06-30 23:00:05', NULL, '2026-06-30 22:58:28', '2026-06-30 23:00:05'),
(174, 'App\\Models\\User', 16, 'auth_token', '2c7a5514724fe0908e764873b1fe8a6d3774453a02ac46d95186cf6aa564f859', '[\"*\"]', '2026-06-30 23:31:25', NULL, '2026-06-30 23:23:06', '2026-06-30 23:31:25'),
(175, 'App\\Models\\User', 16, 'auth_token', 'fb63144e9e79d8ac78e9dd1c76fe24a085428fa47ac014fc070041e2d07d725e', '[\"*\"]', '2026-06-30 23:49:46', NULL, '2026-06-30 23:48:57', '2026-06-30 23:49:46'),
(176, 'App\\Models\\User', 16, 'auth_token', '7c13cc60bf675e7587fa43111454fbef3dae8ac9e7959b0f95e8e48c62a0a4c6', '[\"*\"]', '2026-06-30 23:57:35', NULL, '2026-06-30 23:57:17', '2026-06-30 23:57:35'),
(177, 'App\\Models\\User', 16, 'auth_token', '7bd72dc9b4cbbb72ecd7e4035df5fb6dde459e17a5a3f441099d7d82c253e245', '[\"*\"]', '2026-07-01 00:08:43', NULL, '2026-07-01 00:08:28', '2026-07-01 00:08:43'),
(178, 'App\\Models\\User', 16, 'auth_token', '1a4bef18562018fd06d519f3aef7d9fa6c54f0ed3d0a8bc7616e802b863f8b39', '[\"*\"]', '2026-07-01 00:25:34', NULL, '2026-07-01 00:24:29', '2026-07-01 00:25:34'),
(179, 'App\\Models\\User', 16, 'auth_token', '7dc272c96dfc9edc5e7329e55ee51542e56fd01760678246ad2a2afa896dc198', '[\"*\"]', '2026-07-01 02:39:52', NULL, '2026-07-01 01:45:29', '2026-07-01 02:39:52'),
(180, 'App\\Models\\User', 7, 'auth_token', 'd5db82a52199fb89df22c0cb99590550af679e496b572d236d62dce4bf3f32c9', '[\"*\"]', NULL, NULL, '2026-07-01 01:46:56', '2026-07-01 01:46:56'),
(181, 'App\\Models\\User', 16, 'auth_token', '8534596728e5ae86b746ed69c66347cdd6c081cdf8a83aadb329846c04459b62', '[\"*\"]', '2026-07-01 02:49:41', NULL, '2026-07-01 02:43:10', '2026-07-01 02:49:41'),
(182, 'App\\Models\\User', 16, 'auth_token', '035616df5960ebf385fa3058e90fb869ee1e08ea01acbe3091934b34695e9e5f', '[\"*\"]', '2026-07-01 03:10:03', NULL, '2026-07-01 02:51:20', '2026-07-01 03:10:03'),
(183, 'App\\Models\\User', 16, 'auth_token', 'f4380366cf141b50f77772f646b0cf73ea957eb268a0ea2dafc762b0de719ada', '[\"*\"]', '2026-07-01 03:42:38', NULL, '2026-07-01 03:11:37', '2026-07-01 03:42:38'),
(184, 'App\\Models\\User', 7, 'auth_token', '414ab97324fa2a0be34efc84f37dc3efa0e492d22ec39d1d5a91e5cfb3b73c3f', '[\"*\"]', NULL, NULL, '2026-07-01 03:28:25', '2026-07-01 03:28:25'),
(185, 'App\\Models\\User', 2, 'auth_token', 'ff344785a3f7df4e45ada6fda88e58606b384dcfc6ba2f1359a735b968ad40a2', '[\"*\"]', NULL, NULL, '2026-07-01 03:32:59', '2026-07-01 03:32:59'),
(186, 'App\\Models\\User', 7, 'auth_token', 'a5ff3a39ab6ad26a63b09dd6ddc23766d69f5350d4c0893f279c7279443221ab', '[\"*\"]', '2026-07-01 03:36:18', NULL, '2026-07-01 03:35:43', '2026-07-01 03:36:18'),
(187, 'App\\Models\\User', 16, 'auth_token', 'dfd8edf5f07174375ec7d116fd8f68e2f23002d578cf8a3d6d5ef6946ab8362d', '[\"*\"]', '2026-07-01 04:18:45', NULL, '2026-07-01 03:43:51', '2026-07-01 04:18:45'),
(188, 'App\\Models\\User', 7, 'auth_token', 'c85bef4cba0f7409dac8bda6511b7eef094118a784b772c0056fe40c3e9a4f9b', '[\"*\"]', NULL, NULL, '2026-07-01 04:13:59', '2026-07-01 04:13:59'),
(189, 'App\\Models\\User', 7, 'auth_token', 'b8b90dd7c6ef960a20c606041b2d85b42ff4cafa411048683ce83c2d32a4a4d4', '[\"*\"]', NULL, NULL, '2026-07-01 04:15:49', '2026-07-01 04:15:49'),
(190, 'App\\Models\\User', 16, 'auth_token', '509cd09e23635b3a50ef78df21360f9037da36a74a56e82781d9debf6fc7508a', '[\"*\"]', '2026-07-01 22:56:16', NULL, '2026-07-01 20:42:17', '2026-07-01 22:56:16'),
(191, 'App\\Models\\User', 7, 'auth_token', '7cdb8c2ae21a25dad473696dd9e0248f7548f962a84968791ba2d806fa58e590', '[\"*\"]', '2026-07-01 23:20:24', NULL, '2026-07-01 20:43:00', '2026-07-01 23:20:24'),
(192, 'App\\Models\\User', 16, 'auth_token', '5b6c2801cb3863484fcaae63ed91b7db5a0f9a8e71db685214fbb83406ec3c74', '[\"*\"]', '2026-07-01 23:55:22', NULL, '2026-07-01 23:08:04', '2026-07-01 23:55:22'),
(193, 'App\\Models\\User', 16, 'auth_token', '0e48f1e5c9c50fa2d9be09180896ebcc00ece7c543491255cf1b7e332e87d50c', '[\"*\"]', '2026-07-02 02:45:21', NULL, '2026-07-02 01:57:23', '2026-07-02 02:45:21'),
(194, 'App\\Models\\User', 7, 'auth_token', 'd275cd808127ad34e75b2c2ba61c0d4baabf7a075b69b60b3454bfae8148f7bf', '[\"*\"]', NULL, NULL, '2026-07-02 02:49:41', '2026-07-02 02:49:41'),
(195, 'App\\Models\\User', 16, 'auth_token', 'bcbc0367d44550c6c776352fbd10497b85cc96bbd7d39aabbc4fee76405a5b5c', '[\"*\"]', '2026-07-02 03:14:04', NULL, '2026-07-02 03:14:02', '2026-07-02 03:14:04'),
(196, 'App\\Models\\User', 7, 'auth_token', '7fa45e3668f1c32c12233699d32e0a300312b5a1c41a808d05105b1e725880d3', '[\"*\"]', NULL, NULL, '2026-07-02 03:16:16', '2026-07-02 03:16:16'),
(197, 'App\\Models\\User', 16, 'auth_token', '6004deb829bf50044ef2730a2d8089514268c087ec3b4f374196e84fde0cc9b0', '[\"*\"]', '2026-07-02 03:21:53', NULL, '2026-07-02 03:21:51', '2026-07-02 03:21:53'),
(198, 'App\\Models\\User', 7, 'auth_token', 'f5677a2b8846a0301a55a3746ad966d508c6815c985923f542c8b58a01451184', '[\"*\"]', NULL, NULL, '2026-07-02 03:25:39', '2026-07-02 03:25:39'),
(199, 'App\\Models\\User', 16, 'auth_token', 'e967499866938f56e43ac06eb20557402bb9105dddfbc0d707e874ff131c72fb', '[\"*\"]', '2026-07-02 03:29:22', NULL, '2026-07-02 03:29:20', '2026-07-02 03:29:22'),
(200, 'App\\Models\\User', 7, 'auth_token', '7dd24aa90ad980630fc45cdc98b91c8c4e31a31fb8103800ed37976fb709cd72', '[\"*\"]', NULL, NULL, '2026-07-02 03:33:29', '2026-07-02 03:33:29'),
(201, 'App\\Models\\User', 7, 'auth_token', '03e8e7b9a0765222a2587a8f922befa2b8255460e051e9222fee67a76bab0c42', '[\"*\"]', '2026-07-02 19:10:47', NULL, '2026-07-02 19:09:38', '2026-07-02 19:10:47'),
(202, 'App\\Models\\User', 1, 'auth_token', '48858e037d1648e7e1ec08a17f194d58ed02e7f818e2d7af7fd079e8c06bfd52', '[\"*\"]', '2026-07-03 13:22:46', NULL, '2026-07-03 12:38:44', '2026-07-03 13:22:46'),
(203, 'App\\Models\\User', 1, 'auth_token', '24edcb1d4df16637cec5281cca0e3c0f85a7c0408127c418783f63b04d417334', '[\"*\"]', '2026-07-03 13:32:54', NULL, '2026-07-03 13:28:54', '2026-07-03 13:32:54'),
(204, 'App\\Models\\User', 7, 'auth_token', 'adcef508123639680c3884476c4416b37272976ea3dbcc82a52585a2c3dbbe2b', '[\"*\"]', '2026-07-03 13:52:36', NULL, '2026-07-03 13:34:40', '2026-07-03 13:52:36'),
(205, 'App\\Models\\User', 7, 'auth_token', '7583ed2bba02dcfcb9c716f7eacba80ba0ebb55e311f24642a46885bc49db403', '[\"*\"]', NULL, NULL, '2026-07-03 14:05:53', '2026-07-03 14:05:53'),
(206, 'App\\Models\\User', 7, 'auth_token', 'e1da4a1b6524f64599b17e97098fa82f0d4175fe6f9ec0c369e9376b30475d25', '[\"*\"]', '2026-07-03 14:17:21', NULL, '2026-07-03 14:17:05', '2026-07-03 14:17:21'),
(207, 'App\\Models\\User', 7, 'auth_token', '7be1eb351f38c0ec6484d3800326c70cbf9e0359a233db5d867acedbfc2c039c', '[\"*\"]', NULL, NULL, '2026-07-03 14:32:08', '2026-07-03 14:32:08'),
(208, 'App\\Models\\User', 16, 'auth_token', '2470e9da62873870c65599ef0bfa50259a3923b736e50a9eb4bb35acc209f674', '[\"*\"]', '2026-07-03 14:55:33', NULL, '2026-07-03 14:41:24', '2026-07-03 14:55:33'),
(209, 'App\\Models\\User', 1, 'auth_token', '26c67476c19ecfa7a75d1e6677e6fc629c6889b7ad1d810458440bf48d340497', '[\"*\"]', '2026-07-03 14:57:27', NULL, '2026-07-03 14:56:10', '2026-07-03 14:57:27'),
(210, 'App\\Models\\User', 1, 'auth_token', 'f83283549952707a253f918d8021b6abb6ca6b9c4eb285a19a88fdb10d8f3db6', '[\"*\"]', '2026-07-03 17:34:50', NULL, '2026-07-03 14:58:23', '2026-07-03 17:34:50'),
(211, 'App\\Models\\User', 7, 'auth_token', '600199d5ef6e1b13d9afd85c2ccd02a9a6005237c7dc735a0abb06e4b01de396', '[\"*\"]', NULL, NULL, '2026-07-03 15:05:33', '2026-07-03 15:05:33'),
(212, 'App\\Models\\User', 1, 'auth_token', '3d28fd17b626d60331c2e73e31b2be53e0e7d05ace81a989b9e9a8acfbee932b', '[\"*\"]', '2026-07-03 16:25:30', NULL, '2026-07-03 15:05:58', '2026-07-03 16:25:30'),
(213, 'App\\Models\\User', 1, 'auth_token', '5fac1a0e2ba3f416b96040f186a75e6dfe04bf86cd2ece2d96062edf60110f5b', '[\"*\"]', '2026-07-03 17:20:20', NULL, '2026-07-03 17:20:11', '2026-07-03 17:20:20'),
(214, 'App\\Models\\User', 1, 'auth_token', 'cafb14302e9f8e9e17aab31f5b4dd60fce973fd2df12b4153c2f8c1d769bea6a', '[\"*\"]', '2026-07-04 13:28:10', NULL, '2026-07-04 13:23:55', '2026-07-04 13:28:10'),
(215, 'App\\Models\\User', 1, 'auth_token', 'b98f3e036d54da20e23ec683753c2ee15a4903464c626e99abee28cd39e868d7', '[\"*\"]', '2026-07-04 14:20:11', NULL, '2026-07-04 14:15:04', '2026-07-04 14:20:11'),
(216, 'App\\Models\\User', 1, 'auth_token', 'cdde5cfc9ef254b82bdf3390336e5d7441b78ef8da077ea951b0f4e0ce94aa38', '[\"*\"]', '2026-07-04 15:33:06', NULL, '2026-07-04 15:33:01', '2026-07-04 15:33:06'),
(217, 'App\\Models\\User', 1, 'auth_token', 'b5a09eb13e2ab0f350a05a5786bc956c884236b94b3831a92a7b7a3be83b39ce', '[\"*\"]', '2026-07-06 18:48:55', NULL, '2026-07-06 18:31:34', '2026-07-06 18:48:55'),
(218, 'App\\Models\\User', 7, 'auth_token', '8263d1b3acd107062363a90a97bfb2521b80b26d4bbdc9e3d81a142b46e769f0', '[\"*\"]', '2026-07-06 19:06:54', NULL, '2026-07-06 18:57:53', '2026-07-06 19:06:54'),
(219, 'App\\Models\\User', 1, 'auth_token', '54ad28abf61167187c11db61c4d07b0cc9a7f5262fe263d5a059816ddf766d4e', '[\"*\"]', '2026-07-06 19:09:28', NULL, '2026-07-06 19:09:23', '2026-07-06 19:09:28'),
(220, 'App\\Models\\User', 1, 'auth_token', '68883a0e4bd5fc7927976b90dde9e8542e60da604d7ce210dbec4632074d855e', '[\"*\"]', '2026-07-06 19:13:46', NULL, '2026-07-06 19:13:41', '2026-07-06 19:13:46'),
(221, 'App\\Models\\User', 16, 'auth_token', 'a73f08ae19194bf3d732a6f9c9d36220720a8e1d2f1bfcbc30ef17a3a881ed96', '[\"*\"]', '2026-07-07 11:41:15', NULL, '2026-07-07 11:41:10', '2026-07-07 11:41:15'),
(222, 'App\\Models\\User', 16, 'auth_token', 'e0e9d87b4a7829558fcea447d88202de0133a515a6bb72b87ec5d24799344b0c', '[\"*\"]', '2026-07-07 11:42:32', NULL, '2026-07-07 11:42:30', '2026-07-07 11:42:32'),
(223, 'App\\Models\\User', 2, 'auth_token', '7761130532e73718be994ebc3dda47c9604b33f400a37a9c72457e58a2cc44de', '[\"*\"]', NULL, NULL, '2026-07-07 11:44:10', '2026-07-07 11:44:10'),
(224, 'App\\Models\\User', 8, 'auth_token', 'a9c359b1f1cf4c715bf4edd15ada4684a4b41598cad0e33940afc14de213135d', '[\"*\"]', NULL, NULL, '2026-07-07 11:45:59', '2026-07-07 11:45:59'),
(225, 'App\\Models\\User', 1, 'auth_token', 'ec7382fe33e48dc8f2a9bbd46305ba1e05d823250df66814d6e1f988c96dcc00', '[\"*\"]', '2026-07-07 13:29:14', NULL, '2026-07-07 13:22:34', '2026-07-07 13:29:14'),
(226, 'App\\Models\\User', 1, 'auth_token', '70fcb0e2f14fef24215852f6de2069ff026b22aa982ac3ffc2a33a5747d3456b', '[\"*\"]', '2026-07-07 13:36:52', NULL, '2026-07-07 13:30:54', '2026-07-07 13:36:52'),
(227, 'App\\Models\\User', 1, 'auth_token', '74775a85cfcdd7f587827a3f07e7253810616e4fee2043d36ac2d88cc6425d98', '[\"*\"]', '2026-07-07 13:38:46', NULL, '2026-07-07 13:37:28', '2026-07-07 13:38:46'),
(228, 'App\\Models\\User', 1, 'auth_token', '38fdd8a247e15c3c45062f26853778d7511bd292d8b109aca0b5118fab84cfd9', '[\"*\"]', '2026-07-07 13:48:11', NULL, '2026-07-07 13:47:22', '2026-07-07 13:48:11'),
(229, 'App\\Models\\User', 1, 'auth_token', '39fef65f4acbc0194e10f71a15f2d2d0924a9bee98ce72e6541c13c937366b63', '[\"*\"]', '2026-07-07 14:02:07', NULL, '2026-07-07 14:01:55', '2026-07-07 14:02:07'),
(230, 'App\\Models\\User', 1, 'auth_token', '75f4d3fb8e2cbd63aa1c2c88dbb2de4ea228da83116afef6134ead18aa371d82', '[\"*\"]', '2026-07-07 18:30:28', NULL, '2026-07-07 18:02:26', '2026-07-07 18:30:28'),
(231, 'App\\Models\\User', 1, 'auth_token', 'a7b280594f9c4cd54d0a9a19de8f314c8e1ebfe6f0813188ed68fdc135930e16', '[\"*\"]', '2026-07-30 15:00:13', NULL, '2026-07-30 15:00:04', '2026-07-30 15:00:13'),
(232, 'App\\Models\\User', 2, 'auth_token', '3dec3b2bdf8c6a64dc79d387ded68cb870dad327419bcdb5f14f273299bfc231', '[\"*\"]', NULL, NULL, '2026-07-30 15:38:17', '2026-07-30 15:38:17'),
(233, 'App\\Models\\User', 7, 'auth_token', '104f486b44e4d0c7c2b6de72b09db383a6171f5eda88558fb3d40c0e8b9820d5', '[\"*\"]', NULL, NULL, '2026-07-31 17:26:33', '2026-07-31 17:26:33'),
(234, 'App\\Models\\User', 7, 'auth_token', 'b6ddf806d81798ab4c6515634e6acf66f5950ede2bc0e02d7aa8265bf7e6b1d7', '[\"*\"]', NULL, NULL, '2026-07-31 17:36:16', '2026-07-31 17:36:16'),
(235, 'App\\Models\\User', 7, 'auth_token', '7123266280fcdf3bb152833bf1969a1f93813450c485629f4c77749a869c097b', '[\"*\"]', NULL, NULL, '2026-07-31 17:50:30', '2026-07-31 17:50:30'),
(236, 'App\\Models\\User', 7, 'auth_token', 'a7e68ddb47c01952f7cfd83b434311832e0744f0079480e054cfe5b8a9c0177f', '[\"*\"]', NULL, NULL, '2026-07-31 17:57:27', '2026-07-31 17:57:27'),
(237, 'App\\Models\\User', 7, 'auth_token', '34393898249d470f17d7c18649cc1b681e7d09f09ee65e260e0943c0256fab6d', '[\"*\"]', NULL, NULL, '2026-07-31 18:00:09', '2026-07-31 18:00:09'),
(238, 'App\\Models\\User', 7, 'auth_token', '938f9699193dc66783fbb75b2d930caa9c8c8ab2592fd0eb7cf0dd69e1b752a6', '[\"*\"]', NULL, NULL, '2026-07-31 18:01:41', '2026-07-31 18:01:41'),
(239, 'App\\Models\\User', 7, 'auth_token', 'b01d4581199ea40a8c41b614c8ed1f7ea7654453bedb949dadd7aaef039ecc31', '[\"*\"]', NULL, NULL, '2026-07-31 18:04:38', '2026-07-31 18:04:38'),
(240, 'App\\Models\\User', 7, 'auth_token', '645b716f8c96639b0a8360f7f0223365a2c42e2373d1a2a87d686f47d947666b', '[\"*\"]', NULL, NULL, '2026-07-31 18:09:11', '2026-07-31 18:09:11'),
(241, 'App\\Models\\User', 1, 'auth_token', 'd410d0c34eb6f0a577b6f5f1f8e63ce445cb866b87601723ee3dece40366221d', '[\"*\"]', '2026-07-31 20:09:20', NULL, '2026-07-31 18:13:38', '2026-07-31 20:09:20'),
(242, 'App\\Models\\User', 7, 'auth_token', '53a150f7530cd26a9a0c87ee1424c89b87b4b4ccdfe643b2996f79d501f726f1', '[\"*\"]', '2026-07-31 20:15:13', NULL, '2026-07-31 20:11:18', '2026-07-31 20:15:13'),
(243, 'App\\Models\\User', 16, 'auth_token', '5d6ef5013cddb1b407d3fd700239e50f10dc2278ca9fa14496da11ae61ee78a1', '[\"*\"]', '2026-07-31 20:21:56', NULL, '2026-07-31 20:18:55', '2026-07-31 20:21:56'),
(244, 'App\\Models\\User', 7, 'auth_token', '3c93522b1a3e46ada2f7b0e46eae810443e73261a9c2316ce6df9e44fa37ac4f', '[\"*\"]', NULL, NULL, '2026-07-31 20:22:26', '2026-07-31 20:22:26'),
(245, 'App\\Models\\User', 1, 'auth_token', '01c2edc3a62681de29ff6a5ac430c76b48bb0b02cdc61c5a38903fd30047afbf', '[\"*\"]', '2026-08-04 15:06:52', NULL, '2026-08-04 15:06:34', '2026-08-04 15:06:52'),
(246, 'App\\Models\\User', 16, 'auth_token', 'a0174d7be115e13ede130595c89b83c06454dd93ab484ff6bc4770587eee8cbf', '[\"*\"]', '2026-08-04 15:15:54', NULL, '2026-08-04 15:14:32', '2026-08-04 15:15:54'),
(247, 'App\\Models\\User', 16, 'auth_token', '851712a1f743169bb56ec0d99cf58e00732260b1e21738215ba880564fd3cd00', '[\"*\"]', '2026-08-04 15:27:21', NULL, '2026-08-04 15:26:44', '2026-08-04 15:27:21'),
(248, 'App\\Models\\User', 16, 'auth_token', 'ba043142672ceee23d922fb504fc4579f23cc36a71fdd727e29d7704865c3e60', '[\"*\"]', '2026-08-04 15:32:04', NULL, '2026-08-04 15:31:48', '2026-08-04 15:32:04'),
(249, 'App\\Models\\User', 16, 'auth_token', '705ac9c64243cdd075cf5a7f6cfde57f9965ffd17db9aed2c322c9b03699fc37', '[\"*\"]', '2026-08-04 16:09:01', NULL, '2026-08-04 16:08:46', '2026-08-04 16:09:01'),
(250, 'App\\Models\\User', 16, 'auth_token', '5ff3ec8ec72933e24dd4c90b85c629e367304b27c85a87253306cc6ab8b4e173', '[\"*\"]', '2026-08-05 15:13:24', NULL, '2026-08-05 15:12:53', '2026-08-05 15:13:24'),
(251, 'App\\Models\\User', 1, 'auth_token', 'd1d02b42b21849811423c8897ad31fda285aa9f1badf40454596fe1eb7bfe583', '[\"*\"]', '2026-08-05 16:25:49', NULL, '2026-08-05 15:49:34', '2026-08-05 16:25:49'),
(252, 'App\\Models\\User', 16, 'auth_token', 'ce0f402fb0fdb6bb7458fd7189249afcc1b536a900f95f24bbc4c0c8bab5b2e2', '[\"*\"]', '2026-08-05 16:49:22', NULL, '2026-08-05 16:46:02', '2026-08-05 16:49:22'),
(253, 'App\\Models\\User', 1, 'auth_token', 'd2ad086e03e9d2f86c4ff6bfbccf3fca7050a8b2621f4cdaef3e017e92b5f10d', '[\"*\"]', '2026-08-06 17:33:24', NULL, '2026-08-06 17:33:07', '2026-08-06 17:33:24'),
(254, 'App\\Models\\User', 7, 'auth_token', 'fd26236f982ce1d9a004045236c4626bc530f23d0b808a353f26694be4b88fef', '[\"*\"]', '2026-08-06 17:54:25', NULL, '2026-08-06 17:52:38', '2026-08-06 17:54:25'),
(255, 'App\\Models\\User', 16, 'auth_token', 'ecc0258bd28d14af44b4b6dfac0f1163032a7cf5a956fc6b402263037585c4d5', '[\"*\"]', '2026-08-06 17:58:21', NULL, '2026-08-06 17:56:28', '2026-08-06 17:58:21'),
(256, 'App\\Models\\User', 1, 'auth_token', '086df43fd450c05aa30b595f3859982a076a6ecd9f72a3c79dc63dd9b4c0ebd1', '[\"*\"]', '2026-08-07 14:19:17', NULL, '2026-08-07 14:18:41', '2026-08-07 14:19:17'),
(257, 'App\\Models\\User', 16, 'auth_token', '4dd8eb19db247516d8b04e80ca1bd281bfbc6caca4d9e840f7a2b791522ed6df', '[\"*\"]', '2026-08-08 12:40:48', NULL, '2026-08-08 12:40:32', '2026-08-08 12:40:48'),
(258, 'App\\Models\\User', 1, 'auth_token', 'fd55e1718914ba5dab274a6e5ede182b61ce806ac98c152313143d09d2325598', '[\"*\"]', '2026-08-08 13:27:13', NULL, '2026-08-08 12:47:11', '2026-08-08 13:27:13');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(259, 'App\\Models\\User', 1, 'auth_token', '515a227e8fd13ea929b1add459245aafd740011ef664fbf1977e3ac93915c0b6', '[\"*\"]', '2026-08-08 15:23:50', NULL, '2026-08-08 14:45:30', '2026-08-08 15:23:50'),
(260, 'App\\Models\\User', 16, 'auth_token', '6d04f7643f2f0b103d0189b83a10922deb934938f62c2ac7d1a48169cd60f4c5', '[\"*\"]', '2026-08-08 15:38:16', NULL, '2026-08-08 15:24:20', '2026-08-08 15:38:16'),
(261, 'App\\Models\\User', 1, 'auth_token', '3a7e2560cf2ac6ee03e84b29471f464a49e9267ed409f6d88a7f2c37a81a1ac3', '[\"*\"]', '2026-08-08 16:11:08', NULL, '2026-08-08 15:46:02', '2026-08-08 16:11:08'),
(262, 'App\\Models\\User', 1, 'auth_token', '3bfa3fccc18687d9b236bb2fa8067183e225e3961c11aa1edd85e93fd826acba', '[\"*\"]', '2026-08-08 16:34:11', NULL, '2026-08-08 16:17:02', '2026-08-08 16:34:11'),
(263, 'App\\Models\\User', 1, 'auth_token', '645cbb3048d94431f76ab48ed7e299b03c4ef0b9ddba830a168296eee5338c9a', '[\"*\"]', '2026-08-08 16:49:34', NULL, '2026-08-08 16:39:46', '2026-08-08 16:49:34'),
(264, 'App\\Models\\User', 1, 'auth_token', '75d80f62178f4d7053f29a6f85c8069a6be8eac5f68a26ef76ee973510da3edc', '[\"*\"]', '2026-08-08 16:57:46', NULL, '2026-08-08 16:55:16', '2026-08-08 16:57:46'),
(265, 'App\\Models\\User', 1, 'auth_token', '213bb1d54f10f7bee861ea3370fba04b8806653fd9771b639dd447618d2ac40d', '[\"*\"]', '2026-08-08 17:30:14', NULL, '2026-08-08 17:01:37', '2026-08-08 17:30:14'),
(266, 'App\\Models\\User', 1, 'auth_token', 'bd4d6ec3311b499d7bb0fc56b62f00792bb51bb130ab0279ce245572076d0a35', '[\"*\"]', '2026-08-08 18:35:26', NULL, '2026-08-08 17:52:57', '2026-08-08 18:35:26'),
(267, 'App\\Models\\User', 1, 'auth_token', 'aa1a87745c8a527ef1e9254ba683d83b5b923a209dc69bf0fb8cfd30c73e54be', '[\"*\"]', '2026-08-08 18:48:43', NULL, '2026-08-08 18:40:53', '2026-08-08 18:48:43'),
(268, 'App\\Models\\User', 1, 'auth_token', '0172b09f6df48815fb433a9cb9565b30315b9424f8e6d31971dc3074094813ea', '[\"*\"]', '2026-08-08 18:59:01', NULL, '2026-08-08 18:50:43', '2026-08-08 18:59:01'),
(269, 'App\\Models\\User', 1, 'auth_token', 'e3e08aae7116dd4b189648df3ad5ed35fa2676d6b4591321a6543b456f5672f5', '[\"*\"]', '2026-08-08 19:02:58', NULL, '2026-08-08 19:01:45', '2026-08-08 19:02:58'),
(270, 'App\\Models\\User', 1, 'auth_token', 'fa8e74f4185998ba66c811fa80d57c2c2653863b93dce90a475cb1524bd09c0a', '[\"*\"]', '2026-08-08 19:12:05', NULL, '2026-08-08 19:05:09', '2026-08-08 19:12:05'),
(271, 'App\\Models\\User', 1, 'auth_token', '4ec71a4bcec989023cc751b8c202d6dadae21bffd2b861272e6275833d3c86cf', '[\"*\"]', '2026-08-12 13:03:53', NULL, '2026-08-12 12:38:56', '2026-08-12 13:03:53'),
(272, 'App\\Models\\User', 1, 'auth_token', 'e69b5c1befa9ab223918e19b1ecafd319538bd066ba40f639c070afcb38c9cdd', '[\"*\"]', '2026-08-15 12:46:46', NULL, '2026-08-15 12:46:06', '2026-08-15 12:46:46'),
(273, 'App\\Models\\User', 1, 'auth_token', '610b7e8e00c355aa020e784fb88bf6643cb4b005be9c352f483cd6a48de36860', '[\"*\"]', '2026-08-17 12:55:28', NULL, '2026-08-17 12:51:54', '2026-08-17 12:55:28'),
(274, 'App\\Models\\User', 7, 'auth_token', 'dfa2148d097286461c4e1d26d1a658159acc68ff6323f22921d9ab327fc696e6', '[\"*\"]', NULL, NULL, '2026-08-17 12:55:49', '2026-08-17 12:55:49'),
(275, 'App\\Models\\User', 1, 'auth_token', '3d06a247a65848a31a49983e0ee2e6fca709c0f5fbd5bcfc60a174e2653ba000', '[\"*\"]', '2026-08-17 14:50:48', NULL, '2026-08-17 12:56:43', '2026-08-17 14:50:48'),
(276, 'App\\Models\\User', 16, 'auth_token', '88b82bc3b4ef3f9264e64828b0324c814c04691a66c4d772829f8ab4b8d18fd2', '[\"*\"]', '2026-08-17 13:18:40', NULL, '2026-08-17 13:18:22', '2026-08-17 13:18:40'),
(277, 'App\\Models\\User', 16, 'auth_token', '7f9bef71b23fe6f0fba41fb4a952778ad79a773d536a069ec41143eba37f71c6', '[\"*\"]', '2026-08-17 13:34:26', NULL, '2026-08-17 13:19:56', '2026-08-17 13:34:26'),
(278, 'App\\Models\\User', 1, 'auth_token', 'ded88b9098e0ef2835fbfc8b953face052714c3cf7499315157e5a35a8da782b', '[\"*\"]', '2026-08-17 15:33:07', NULL, '2026-08-17 15:32:42', '2026-08-17 15:33:07'),
(279, 'App\\Models\\User', 1, 'auth_token', '5229c0b028e173e1799d83831e75abbc33f514c508288a87e36a756669802ac9', '[\"*\"]', '2026-08-17 15:35:31', NULL, '2026-08-17 15:35:06', '2026-08-17 15:35:31'),
(280, 'App\\Models\\User', 1, 'auth_token', 'c6985ba220f4bfa4f0be86c199ff13d7a827e487d730725b99d139ea76a59907', '[\"*\"]', '2026-08-17 17:31:21', NULL, '2026-08-17 15:36:51', '2026-08-17 17:31:21'),
(281, 'App\\Models\\User', 1, 'auth_token', 'a0003fd4811f29e271a42584107e9e661850602369bd1b6947ca0d7d15da0e50', '[\"*\"]', '2026-08-17 17:35:09', NULL, '2026-08-17 17:33:50', '2026-08-17 17:35:09'),
(282, 'App\\Models\\User', 1, 'auth_token', '66606582c259fc63706984365c72d85d089fd0bea92a4896c4bac7b7698ebb7f', '[\"*\"]', '2026-08-17 17:43:40', NULL, '2026-08-17 17:42:49', '2026-08-17 17:43:40'),
(283, 'App\\Models\\User', 1, 'auth_token', 'ed678d9427f0a6f966f875e0c204962fd66f8d478b46812e779195c39e6e8159', '[\"*\"]', '2026-08-17 17:50:23', NULL, '2026-08-17 17:49:15', '2026-08-17 17:50:23'),
(284, 'App\\Models\\User', 1, 'auth_token', '163c0ecab227b407c47868e2548a7e3b1670f1b7135a3d1391ed0c51a8c7baa7', '[\"*\"]', '2026-08-17 17:55:16', NULL, '2026-08-17 17:54:15', '2026-08-17 17:55:16'),
(285, 'App\\Models\\User', 1, 'auth_token', '3d1686104495ca5ab8318807df6c8f07b4324c3a2748a314c767b44db2f0f2e0', '[\"*\"]', '2026-08-17 18:16:37', NULL, '2026-08-17 18:15:50', '2026-08-17 18:16:37'),
(286, 'App\\Models\\User', 1, 'auth_token', '348b81da36e4a01b87ab96c244f851337e70643a91af1428ed1f1080e72118e5', '[\"*\"]', '2026-08-17 18:22:04', NULL, '2026-08-17 18:21:32', '2026-08-17 18:22:04'),
(287, 'App\\Models\\User', 1, 'auth_token', 'de1ac390ad6818acb39b390d2b338981aad0a23f12a1fb99092b30d95c8230bc', '[\"*\"]', '2026-08-17 19:20:51', NULL, '2026-08-17 19:18:58', '2026-08-17 19:20:51'),
(288, 'App\\Models\\User', 1, 'auth_token', '51a3a02d85c3bcc50dd797ffc907fee019010f29bcf3968b786c010bc2f40b3e', '[\"*\"]', '2026-08-18 14:07:23', NULL, '2026-08-18 14:03:34', '2026-08-18 14:07:23'),
(289, 'App\\Models\\User', 1, 'auth_token', '0d21ba3171bb0a5e4975422bcef4907ad019ae1d259a9aeb7124d0cabf8cdc2b', '[\"*\"]', '2026-08-18 14:40:26', NULL, '2026-08-18 14:38:50', '2026-08-18 14:40:26'),
(290, 'App\\Models\\User', 1, 'auth_token', '2afc6e22ff104fba04f72d017e1195ad6ddf1611057690c68e86f9673b30e3a1', '[\"*\"]', '2026-08-18 15:37:08', NULL, '2026-08-18 14:52:05', '2026-08-18 15:37:08'),
(291, 'App\\Models\\User', 1, 'auth_token', '9dddbd6b9b107f624306f6553160a4d197f2cf7fc289cfeba2b9282ce0a8eadf', '[\"*\"]', '2026-08-18 16:39:17', NULL, '2026-08-18 16:20:07', '2026-08-18 16:39:17'),
(292, 'App\\Models\\User', 1, 'auth_token', 'a6d3172a17c0e11507c7fbdaf83af90fddb72826ec3c516e709b02402a546f5f', '[\"*\"]', '2026-08-18 16:59:09', NULL, '2026-08-18 16:44:05', '2026-08-18 16:59:09'),
(293, 'App\\Models\\User', 1, 'auth_token', '7802eb0d9ece5ff57008d3397c0332bbc81fe6aaa0b839ee352b0e509003f5fb', '[\"*\"]', '2026-08-18 18:36:36', NULL, '2026-08-18 18:20:53', '2026-08-18 18:36:36'),
(294, 'App\\Models\\User', 1, 'auth_token', '23840787e98947727b96685335d5a4f0e777e7e4db7879677c1d0e9732d6a9bc', '[\"*\"]', '2026-08-18 19:08:04', NULL, '2026-08-18 18:50:02', '2026-08-18 19:08:04'),
(295, 'App\\Models\\User', 1, 'auth_token', 'f60d7927e7e68d76f51333eda874e4d88df3a9537cb973775ce67afa07ad5129', '[\"*\"]', '2026-08-18 19:40:56', NULL, '2026-08-18 19:39:54', '2026-08-18 19:40:56'),
(296, 'App\\Models\\User', 1, 'auth_token', 'fd477bc225df90e0fb67ed0a5ddd759bda620b47f00f233d958c507f5fc3f466', '[\"*\"]', '2026-08-18 20:07:54', NULL, '2026-08-18 20:07:27', '2026-08-18 20:07:54'),
(297, 'App\\Models\\User', 1, 'auth_token', 'b01285fbea80dfd8f3548575b39b8a6b87e8ded1c99c409546aaf569f530b215', '[\"*\"]', '2026-08-18 20:14:57', NULL, '2026-08-18 20:12:36', '2026-08-18 20:14:57'),
(298, 'App\\Models\\User', 1, 'auth_token', '2a5904a8c9924c5f77cf06541c21493a808c39a9cd528b2b481bccf87fa92c5c', '[\"*\"]', '2026-08-18 20:17:28', NULL, '2026-08-18 20:16:36', '2026-08-18 20:17:28'),
(299, 'App\\Models\\User', 1, 'auth_token', '213a4966d04afc296c8a5d65ab94e0ba7ad16a445d66631d664e9056730ac2ca', '[\"*\"]', '2026-08-18 20:23:51', NULL, '2026-08-18 20:19:42', '2026-08-18 20:23:51'),
(300, 'App\\Models\\User', 1, 'auth_token', '5728ced64413f6a0e656ed4d1c916009b58917f70988fe0fb7bfb7ba7996416d', '[\"*\"]', '2026-08-19 02:23:48', NULL, '2026-08-19 02:23:28', '2026-08-19 02:23:48'),
(301, 'App\\Models\\User', 1, 'auth_token', 'c2a0285363a1ceb54c730637b8f127f97688fa83257823216f9dd392be3cb9dd', '[\"*\"]', '2026-08-19 02:37:39', NULL, '2026-08-19 02:28:55', '2026-08-19 02:37:39'),
(302, 'App\\Models\\User', 1, 'auth_token', 'e520bf32533e77dfe533ef8a1f0fafa845e12a12c5a34563c80aab4fdb4e25a2', '[\"*\"]', '2026-08-19 03:16:02', NULL, '2026-08-19 03:09:57', '2026-08-19 03:16:02'),
(303, 'App\\Models\\User', 1, 'auth_token', 'cd45b188b71e2e20523a57a424f0dafb0d2b34465b776cd405a1484cf62aebd9', '[\"*\"]', '2026-08-19 03:55:55', NULL, '2026-08-19 03:44:23', '2026-08-19 03:55:55'),
(304, 'App\\Models\\User', 1, 'auth_token', '8b7d960d1e4c906ee952a31254e1d98b5f9b9e2424b0e6279a3e045deab4ab95', '[\"*\"]', '2026-08-19 15:36:22', NULL, '2026-08-19 12:38:21', '2026-08-19 15:36:22'),
(305, 'App\\Models\\User', 16, 'auth_token', 'b5a4e74a320bbae1cd0f3b3f42c924c1f3194abe74d7eee93d71f4f54b3d1677', '[\"*\"]', '2026-08-19 12:47:20', NULL, '2026-08-19 12:46:13', '2026-08-19 12:47:20'),
(306, 'App\\Models\\User', 16, 'auth_token', '3bf890497148477fdb9ecfe6e41e0fa4d6833b56ff9165326e9b2d7fff4fe69b', '[\"*\"]', '2026-08-19 14:02:43', NULL, '2026-08-19 13:02:39', '2026-08-19 14:02:43'),
(307, 'App\\Models\\User', 1, 'auth_token', 'fd65a4f43d8290741cfe9840d05d7282cc75d102203c02a9962d52cabc2b5659', '[\"*\"]', '2026-08-19 14:56:03', NULL, '2026-08-19 14:06:14', '2026-08-19 14:56:03'),
(308, 'App\\Models\\User', 1, 'auth_token', '29f567b98a36ff465a1fc2c29ea75add20e6140a585084c9d6515f8bcbf6ddea', '[\"*\"]', '2026-08-19 18:07:07', NULL, '2026-08-19 17:58:20', '2026-08-19 18:07:07'),
(309, 'App\\Models\\User', 1, 'auth_token', '40a9f27188d2a49c8f0cfa20264b3fb3d6e0deda0af74035ea9b6b4c3b07a3d8', '[\"*\"]', '2026-08-19 18:15:17', NULL, '2026-08-19 18:14:45', '2026-08-19 18:15:17'),
(310, 'App\\Models\\User', 22, 'auth_token', 'fa465e8fba55724350383a25f502cf4cd27a938f87f524200cf6a343009b6bcf', '[\"*\"]', '2026-08-19 18:19:41', NULL, '2026-08-19 18:15:38', '2026-08-19 18:19:41'),
(311, 'App\\Models\\User', 1, 'auth_token', 'ff22dc7c36cc450304baafdcfd097e67f90ad53b39c3f5ba9056ef55603d85ab', '[\"*\"]', '2026-08-19 19:48:15', NULL, '2026-08-19 19:47:24', '2026-08-19 19:48:15'),
(312, 'App\\Models\\User', 1, 'auth_token', 'cc46edd2f0338dd4df4cea17a69ca6970318b949252a28fc8efd7402c0252d7c', '[\"*\"]', '2026-08-19 19:54:51', NULL, '2026-08-19 19:53:47', '2026-08-19 19:54:51'),
(313, 'App\\Models\\User', 1, 'auth_token', '0f549702777b872bd3a7e40005cb5245db07e44d2ea6a8bc39cfe5f9323f3fc7', '[\"*\"]', '2026-08-19 20:37:32', NULL, '2026-08-19 20:37:08', '2026-08-19 20:37:32'),
(314, 'App\\Models\\User', 1, 'auth_token', '093333fc86013d9bf1d01dc1e1fe6d0b4b91915e5404b36c272469d0ef6c07c4', '[\"*\"]', '2026-08-20 12:49:59', NULL, '2026-08-20 12:48:41', '2026-08-20 12:49:59'),
(315, 'App\\Models\\User', 1, 'auth_token', '1b74478056ab24c6893993a60f60de759a15358c0077be0fc3159e2ec4769ee9', '[\"*\"]', '2026-08-20 13:28:14', NULL, '2026-08-20 13:27:21', '2026-08-20 13:28:14'),
(316, 'App\\Models\\User', 1, 'auth_token', '1721a21470ee0eaf6946d168a7b6b9a1cbef8cf07e2e703fb0312ea07af56e1b', '[\"*\"]', '2026-08-20 13:32:40', NULL, '2026-08-20 13:32:03', '2026-08-20 13:32:40'),
(317, 'App\\Models\\User', 1, 'auth_token', 'd6e6e67686d1d976a185632d93c5450bbd031a5617e1eacdf3d50fd11093d211', '[\"*\"]', '2026-08-20 15:10:01', NULL, '2026-08-20 13:45:23', '2026-08-20 15:10:01'),
(318, 'App\\Models\\User', 1, 'auth_token', '5c64349613447897ee7b33cf0ed994f422acbacd1fedfb5dfa9190ad6ea58918', '[\"*\"]', '2026-08-20 15:50:55', NULL, '2026-08-20 15:39:45', '2026-08-20 15:50:55'),
(319, 'App\\Models\\User', 1, 'auth_token', '784df786445f05fa151ad096e6e93b6b3049dcd48c862ecaff74b1b6b3bb650d', '[\"*\"]', '2026-08-20 16:27:55', NULL, '2026-08-20 16:17:41', '2026-08-20 16:27:55'),
(320, 'App\\Models\\User', 1, 'auth_token', '749656f0f7ddbc45108b6f3e7323c2e1b762e79f843a032f3a8d93fb7bad00f2', '[\"*\"]', '2026-08-20 16:34:58', NULL, '2026-08-20 16:32:18', '2026-08-20 16:34:58'),
(321, 'App\\Models\\User', 1, 'auth_token', '45e59c316a803dc48fd117a05c888b967a66e4f827418610b54e132aac329e28', '[\"*\"]', '2026-08-20 16:55:14', NULL, '2026-08-20 16:54:21', '2026-08-20 16:55:14'),
(322, 'App\\Models\\User', 1, 'auth_token', '5a3152f37fd7375786c79af290384551f54cbcf5507f7593db14246742bde1f1', '[\"*\"]', '2026-08-20 18:27:36', NULL, '2026-08-20 18:14:26', '2026-08-20 18:27:36'),
(323, 'App\\Models\\User', 7, 'auth_token', 'f4b278c2f14d018070f902a7ada51ea655de3b56aacb8adac5e4ec42b5f70fbb', '[\"*\"]', NULL, NULL, '2026-08-20 18:29:47', '2026-08-20 18:29:47'),
(324, 'App\\Models\\User', 1, 'auth_token', 'c885f4bee866299fbf205ae38eb11370b94c29d562ab7b501f3e748ed5e35b0b', '[\"*\"]', '2026-08-20 18:38:22', NULL, '2026-08-20 18:37:42', '2026-08-20 18:38:22'),
(325, 'App\\Models\\User', 7, 'auth_token', '3bf9e0a4b5c5b050e28b7e099aed8a53bc45967adca81a9ace0ac0302f52ae9a', '[\"*\"]', NULL, NULL, '2026-08-20 18:43:25', '2026-08-20 18:43:25'),
(326, 'App\\Models\\User', 1, 'auth_token', '26f178bdace460eb87716b4daebc870904b8deb1aebf29d05cba6452b481039e', '[\"*\"]', '2026-08-20 19:35:13', NULL, '2026-08-20 19:34:45', '2026-08-20 19:35:13'),
(327, 'App\\Models\\User', 1, 'auth_token', '6c95a29ae7c7afe2aacc8854e37ad8d2f9fb20c43ff63446f35b5a5e9ed1fe7d', '[\"*\"]', '2026-08-20 19:37:44', NULL, '2026-08-20 19:37:22', '2026-08-20 19:37:44'),
(328, 'App\\Models\\User', 1, 'auth_token', '1decb183c1ba8f34578c1a40fe7f484b92ab2c64348618547ad7dc18699775a4', '[\"*\"]', '2026-08-20 19:50:15', NULL, '2026-08-20 19:49:52', '2026-08-20 19:50:15'),
(329, 'App\\Models\\User', 1, 'auth_token', '908db5a2279f249ef9b523e5e1eb48868ff2b80b2e29386f2f687a60cd31c46f', '[\"*\"]', '2026-08-20 19:54:27', NULL, '2026-08-20 19:53:59', '2026-08-20 19:54:27'),
(330, 'App\\Models\\User', 1, 'auth_token', '272614192495009db1509450cd8033b158695dc81602555b3cd2c8005103d6cb', '[\"*\"]', '2026-08-20 19:56:20', NULL, '2026-08-20 19:55:56', '2026-08-20 19:56:20'),
(331, 'App\\Models\\User', 1, 'auth_token', '20cbb13af287fe8bef930d5f5b8238d1db9c5f859bbb67c1ad427eff490299c1', '[\"*\"]', '2026-08-20 20:10:31', NULL, '2026-08-20 20:10:09', '2026-08-20 20:10:31'),
(332, 'App\\Models\\User', 1, 'auth_token', 'e1b847529cf772c9c1c79d1c9438d926c908d00886fa7c0b2df8de583daff1d4', '[\"*\"]', '2026-08-20 20:12:53', NULL, '2026-08-20 20:12:36', '2026-08-20 20:12:53'),
(333, 'App\\Models\\User', 1, 'auth_token', '871cb56ad6f104cf2b66ba47b3569b3f432f7df28fe64556d3c62340ef1dc59d', '[\"*\"]', '2026-08-20 20:26:02', NULL, '2026-08-20 20:25:42', '2026-08-20 20:26:02'),
(334, 'App\\Models\\User', 1, 'auth_token', 'fe79993830616c7452fa4bcc85695a3e244093d75b286be1152509f9f9dd71bf', '[\"*\"]', '2026-08-20 20:27:56', NULL, '2026-08-20 20:27:38', '2026-08-20 20:27:56'),
(335, 'App\\Models\\User', 1, 'auth_token', '0edca79fc37e350d31f40967f1b6ee8445b67fda7dc38d34f8f77a50ddf9cff7', '[\"*\"]', '2026-08-20 20:29:11', NULL, '2026-08-20 20:28:56', '2026-08-20 20:29:11'),
(336, 'App\\Models\\User', 1, 'auth_token', 'a3396dc0e75dc1dc4e73cd12904a89f060b6875974415e0153f76614c8c7f049', '[\"*\"]', '2026-08-20 20:34:06', NULL, '2026-08-20 20:33:50', '2026-08-20 20:34:06'),
(337, 'App\\Models\\User', 1, 'auth_token', 'dd0812923ff427589a5ea6263d9d92df6a130561fa8185623ccde908f9f1c1b5', '[\"*\"]', '2026-08-20 20:36:26', NULL, '2026-08-20 20:36:05', '2026-08-20 20:36:26'),
(338, 'App\\Models\\User', 1, 'auth_token', 'a91543bab45c2be52bc22cd4b38473856b5bc259dee37962da18c8297c9ca4cd', '[\"*\"]', '2026-08-20 20:38:56', NULL, '2026-08-20 20:38:38', '2026-08-20 20:38:56'),
(339, 'App\\Models\\User', 1, 'auth_token', 'e8646216d497cb515ff2250d4a462154c39d251c7958b4555c1a1164b46d0dca', '[\"*\"]', '2026-08-20 21:19:14', NULL, '2026-08-20 20:59:49', '2026-08-20 21:19:14'),
(340, 'App\\Models\\User', 1, 'auth_token', '963fd80b1c250417c6bd96163419ff647a6f65edcf7a51dd74ded22c47c2579f', '[\"*\"]', '2026-08-21 08:14:41', NULL, '2026-08-21 08:14:16', '2026-08-21 08:14:41'),
(341, 'App\\Models\\User', 1, 'auth_token', '05c32a44ac516746af7e13cd64da3ab318c180ab85e15439be34e616b75c3807', '[\"*\"]', '2026-08-21 08:53:27', NULL, '2026-08-21 08:51:14', '2026-08-21 08:53:27'),
(342, 'App\\Models\\User', 1, 'auth_token', '1425787234ad2e585a2307a2c8417f3b6d2d29d67319cb036a82268cfcfe7fd8', '[\"*\"]', '2026-08-21 09:05:19', NULL, '2026-08-21 09:04:40', '2026-08-21 09:05:19'),
(343, 'App\\Models\\User', 1, 'auth_token', '4e0f468b068918bdddb79a1cbb796c87494987106b75169011eab1736a9c4d06', '[\"*\"]', '2026-08-21 09:09:26', NULL, '2026-08-21 09:08:50', '2026-08-21 09:09:26'),
(344, 'App\\Models\\User', 1, 'auth_token', 'f34b5795f0470e971d78a943a2c7c953eb79e72a7eed5de52372878ce2567eb5', '[\"*\"]', '2026-08-21 13:37:05', NULL, '2026-08-21 13:34:39', '2026-08-21 13:37:05'),
(345, 'App\\Models\\User', 1, 'auth_token', '9c75ec997698884f8f27db3e757af54bb5d54636b45cdf953bc31a6ff0d136cf', '[\"*\"]', '2026-08-21 14:37:34', NULL, '2026-08-21 14:36:50', '2026-08-21 14:37:34'),
(346, 'App\\Models\\User', 1, 'auth_token', '11e8c9f8746fdab42311ff738989082082deeb634dddae7a6a823b22002cd357', '[\"*\"]', '2026-08-21 15:01:55', NULL, '2026-08-21 15:01:22', '2026-08-21 15:01:55'),
(347, 'App\\Models\\User', 1, 'auth_token', '60c92e7751b6058319770752bc6eba5461c326fc6e10eaf9badf19f3acf3bd10', '[\"*\"]', '2026-08-21 16:15:50', NULL, '2026-08-21 16:15:29', '2026-08-21 16:15:50'),
(348, 'App\\Models\\User', 1, 'auth_token', '2c14f5c5b2f79a75a309e527ea0ff2240a4efe38e344acd1e95e8e7996e0210b', '[\"*\"]', '2026-08-21 16:19:16', NULL, '2026-08-21 16:19:03', '2026-08-21 16:19:16'),
(349, 'App\\Models\\User', 1, 'auth_token', '6fb5f327888da757208b5967c9e8d80aa9e87f69620e77a9348965b0ded300c1', '[\"*\"]', '2026-08-21 16:21:19', NULL, '2026-08-21 16:20:59', '2026-08-21 16:21:19'),
(350, 'App\\Models\\User', 1, 'auth_token', '08a83165dc740c76d41dec56c90abe5aca6ac2e5147a6ade2ed391ddde76b8a7', '[\"*\"]', '2026-08-21 17:27:26', NULL, '2026-08-21 17:27:10', '2026-08-21 17:27:26'),
(351, 'App\\Models\\User', 1, 'auth_token', '8f654707b451803fafa6b648d6873a6a0b844a1615b7523ab0a04fc23dd460de', '[\"*\"]', '2026-08-21 17:33:35', NULL, '2026-08-21 17:32:55', '2026-08-21 17:33:35'),
(352, 'App\\Models\\User', 1, 'auth_token', 'a9988a08cf0dfa7aa41a4f5e8baf017399c89bdace9dca7e5ba920ecf7e1e17e', '[\"*\"]', '2026-08-21 17:38:28', NULL, '2026-08-21 17:37:11', '2026-08-21 17:38:28'),
(353, 'App\\Models\\User', 1, 'auth_token', '324823dcb33143a187e19828da30aacb3dd93bf0dd03189fc5cf810ddf5253ab', '[\"*\"]', '2026-08-21 17:42:40', NULL, '2026-08-21 17:42:02', '2026-08-21 17:42:40'),
(354, 'App\\Models\\User', 1, 'auth_token', '5783bde2986edbe1f257e7ec4e0934bd457f551f174b662dd4d682af62d28f0c', '[\"*\"]', '2026-08-21 18:06:01', NULL, '2026-08-21 18:05:31', '2026-08-21 18:06:01'),
(355, 'App\\Models\\User', 1, 'auth_token', '1e64553fa73a75f778e2edfe12d034eb61575e02dfd39af08016b79bdd265377', '[\"*\"]', '2026-08-21 18:41:06', NULL, '2026-08-21 18:36:43', '2026-08-21 18:41:06'),
(356, 'App\\Models\\User', 1, 'auth_token', '388669279a485ac20776a89775b1090c0002091df1f92c47335ca4dfe750d363', '[\"*\"]', '2026-08-21 18:55:38', NULL, '2026-08-21 18:49:42', '2026-08-21 18:55:38'),
(357, 'App\\Models\\User', 16, 'auth_token', '33aa5011f76a555bf9c287b205b52af3afd36f5dc16f3defbd8ad32052bde5ef', '[\"*\"]', '2026-08-21 18:57:13', NULL, '2026-08-21 18:57:04', '2026-08-21 18:57:13'),
(358, 'App\\Models\\User', 1, 'auth_token', '84105cbc00186c462ed077a699a139ec472325b92a22fd70aedaee312c41537e', '[\"*\"]', '2026-08-21 19:23:36', NULL, '2026-08-21 19:23:12', '2026-08-21 19:23:36'),
(359, 'App\\Models\\User', 1, 'auth_token', 'b2cf150d789d16dc6ec75278a058af6421bff1b4c1dd4ad41d302437d6acf452', '[\"*\"]', '2026-08-21 19:31:02', NULL, '2026-08-21 19:30:54', '2026-08-21 19:31:02'),
(360, 'App\\Models\\User', 16, 'auth_token', '3419680063ccfb8bc0ff45f0150750c29fe64e5ad121f52f7bdad6413863ea6a', '[\"*\"]', '2026-08-21 19:32:18', NULL, '2026-08-21 19:31:44', '2026-08-21 19:32:18'),
(361, 'App\\Models\\User', 16, 'auth_token', '7a2c1247a074d9988bae0951b9c2f2b34d6f17a5abe24f7e9bc57e53ae8b7036', '[\"*\"]', '2026-08-21 19:54:54', NULL, '2026-08-21 19:54:21', '2026-08-21 19:54:54'),
(362, 'App\\Models\\User', 16, 'auth_token', '7c196cf0e653a46ad17d66c9726a7e8e6f9c2acf19e51b2e249272cb564e4d91', '[\"*\"]', '2026-08-21 20:04:22', NULL, '2026-08-21 20:04:18', '2026-08-21 20:04:22'),
(363, 'App\\Models\\User', 16, 'auth_token', 'b053f36fd76e1efce05bffd5b1b14b6fa3bac21a6b3fc8b2545816510346b5c3', '[\"*\"]', '2026-08-21 20:38:15', NULL, '2026-08-21 20:37:21', '2026-08-21 20:38:15'),
(364, 'App\\Models\\User', 7, 'auth_token', '8f81f6e6942b4561c114d37389eb6f6af50f87f12fdff42f3527af89ad6e7bc8', '[\"*\"]', '2026-08-21 20:50:15', NULL, '2026-08-21 20:42:30', '2026-08-21 20:50:15'),
(365, 'App\\Models\\User', 1, 'auth_token', '2dd53f61a30c05df57a2a57c36b61a6c9bd0b7943ad49ef3ae6a752c78204dec', '[\"*\"]', '2026-08-22 11:24:11', NULL, '2026-08-22 11:23:48', '2026-08-22 11:24:11'),
(366, 'App\\Models\\User', 7, 'auth_token', '30c22b5b433ec370bdcd5117c553b84b33518ce66c8fa8234ad9001935bf29e0', '[\"*\"]', '2026-08-22 13:09:17', NULL, '2026-08-22 13:07:31', '2026-08-22 13:09:17'),
(367, 'App\\Models\\User', 7, 'auth_token', '00b9963380af80564c7c5adb1eedbb2f0983451ed8acffb63ffb624e03c67988', '[\"*\"]', NULL, NULL, '2026-08-22 13:15:04', '2026-08-22 13:15:04'),
(368, 'App\\Models\\User', 7, 'auth_token', '23fa0e2bb0af500ac5fbbbd1b2f7e56f9be8da8c522f50804403fa2359e2e6c9', '[\"*\"]', NULL, NULL, '2026-08-22 14:22:37', '2026-08-22 14:22:37'),
(369, 'App\\Models\\User', 7, 'auth_token', '94d74af24437591d8ccee11a91e58d6f0a44844fe18fdd351537be4d9f71a5e1', '[\"*\"]', NULL, NULL, '2026-08-22 15:08:32', '2026-08-22 15:08:32'),
(370, 'App\\Models\\User', 1, 'auth_token', '727f1ac9ad76fec5c89a9c71db4da9efc19c17f41e94479d2a30fad6e4840f2c', '[\"*\"]', '2026-08-22 15:46:00', NULL, '2026-08-22 15:44:53', '2026-08-22 15:46:00'),
(371, 'App\\Models\\User', 1, 'auth_token', '4c292d26ea9a66135323bdd389954f33efc98278e245e847e2a8e2d3f7407c0d', '[\"*\"]', '2026-08-22 16:55:02', NULL, '2026-08-22 16:54:26', '2026-08-22 16:55:02'),
(372, 'App\\Models\\User', 1, 'auth_token', 'bd4b16cccfeb50f4deb6374b10147367e09c03b8abeb9af6dad738d61c9fb98f', '[\"*\"]', '2026-08-22 17:17:32', NULL, '2026-08-22 16:57:28', '2026-08-22 17:17:32'),
(373, 'App\\Models\\User', 1, 'auth_token', '6a56d468ee4e322d603fda10d7707b5515308954eaf892fbbb3497847ea999ff', '[\"*\"]', '2026-08-22 17:34:51', NULL, '2026-08-22 17:30:12', '2026-08-22 17:34:51'),
(374, 'App\\Models\\User', 1, 'auth_token', 'f20c372d725bbf569ac3dc1cc226f1760fa51b70437178124a4ec6b970d98a30', '[\"*\"]', '2026-08-22 17:56:50', NULL, '2026-08-22 17:52:47', '2026-08-22 17:56:50'),
(375, 'App\\Models\\User', 7, 'auth_token', '524350e2f6a7729c86d6def1909e54b86729b82d30051a9fafeadce2e150532e', '[\"*\"]', NULL, NULL, '2026-08-23 16:18:53', '2026-08-23 16:18:53'),
(376, 'App\\Models\\User', 7, 'auth_token', 'b3a6f53acfa76da5b97ee5cf83da8e5cd662c0a305c7358846d775ac5713a3d1', '[\"*\"]', NULL, NULL, '2026-08-23 16:19:32', '2026-08-23 16:19:32'),
(377, 'App\\Models\\User', 7, 'auth_token', '0d560719e90520eed900deb9ae9a13d166b7f7e87702a6ffccc732508a542d02', '[\"*\"]', NULL, NULL, '2026-08-23 16:19:50', '2026-08-23 16:19:50'),
(378, 'App\\Models\\User', 7, 'auth_token', '85f5dfc7c9f1eda73089d926f91b5d5db710265c8c7fae8e2d35736acdd5aeb1', '[\"*\"]', NULL, NULL, '2026-08-23 20:53:45', '2026-08-23 20:53:45'),
(379, 'App\\Models\\User', 7, 'auth_token', 'b0fc524a2b7643c1c3374ab2e6f9c6e2b2cbe314fc8f972fe7ba3e65bd2069d1', '[\"*\"]', NULL, NULL, '2026-08-23 20:54:01', '2026-08-23 20:54:01'),
(380, 'App\\Models\\User', 7, 'auth_token', '72b97e9d3674337e65cb9ae19a41407973059fe3824c86e0cc4d126e88332870', '[\"*\"]', NULL, NULL, '2026-08-23 21:22:16', '2026-08-23 21:22:16'),
(381, 'App\\Models\\User', 1, 'auth_token', '09d7d8dd727658f8474950b238dfc30f60529da6e4752f67df5069bb950dd9d0', '[\"*\"]', '2026-08-23 22:09:13', NULL, '2026-08-23 22:08:40', '2026-08-23 22:09:13'),
(382, 'App\\Models\\User', 7, 'auth_token', '63fbcbbf88a7d114397b6feef8d5f7f718619d10c75aaa5cb9436662d1e8f658', '[\"*\"]', NULL, NULL, '2026-08-25 13:44:03', '2026-08-25 13:44:03'),
(383, 'App\\Models\\User', 1, 'auth_token', '1874433310c15d872b4eac3a79a13064478acc61126b0a656749bc4e035470af', '[\"*\"]', '2026-08-25 13:45:34', NULL, '2026-08-25 13:44:37', '2026-08-25 13:45:34'),
(384, 'App\\Models\\User', 1, 'auth_token', '2a22bba1a023fcdb9aff542bc54f22e820edd4487af7a33f1c771d67ff001f20', '[\"*\"]', '2026-08-25 17:58:52', NULL, '2026-08-25 17:58:21', '2026-08-25 17:58:52'),
(385, 'App\\Models\\User', 1, 'auth_token', 'af50ab4dab5fe50ccde3e55b8de6484c6c64f106ad4e5c31061dabf207147170', '[\"*\"]', '2026-08-25 18:35:41', NULL, '2026-08-25 18:04:27', '2026-08-25 18:35:41'),
(386, 'App\\Models\\User', 16, 'auth_token', '7ad334f5ac9dd551b08fc1ea2fee8abae44c8cfa9143d67f82a164e8d9c21b01', '[\"*\"]', '2026-08-25 18:14:19', NULL, '2026-08-25 18:06:44', '2026-08-25 18:14:19'),
(387, 'App\\Models\\User', 1, 'auth_token', '33f5f0939807c2b34ec356306de26595ed78c4d4be83fcbe4693d57991840297', '[\"*\"]', '2026-08-25 19:24:11', NULL, '2026-08-25 19:13:13', '2026-08-25 19:24:11'),
(388, 'App\\Models\\User', 1, 'auth_token', 'fe03a3a37cb8a7685089dd3e1e8073b373ccd0c3ddfb7b063c1aa1ccbf132eb1', '[\"*\"]', '2026-08-25 20:14:49', NULL, '2026-08-25 19:56:31', '2026-08-25 20:14:49'),
(389, 'App\\Models\\User', 1, 'auth_token', '73979d2b1b64cd42919ccf3ac3825c62be1fc6735243878bcec2e04ad751d88a', '[\"*\"]', '2026-08-25 20:43:37', NULL, '2026-08-25 20:43:07', '2026-08-25 20:43:37'),
(390, 'App\\Models\\User', 1, 'auth_token', 'a6151464c23e6b25553156ad6e2fcde5f9b64b7d8afcb7b746f5897459fa9a7e', '[\"*\"]', '2026-08-25 20:44:39', NULL, '2026-08-25 20:44:16', '2026-08-25 20:44:39'),
(391, 'App\\Models\\User', 1, 'auth_token', '335700afdfc2c1a9c1f9df68fbc43326938e04b7479d13dfb0b9ccab46e337dc', '[\"*\"]', '2026-08-25 20:47:31', NULL, '2026-08-25 20:45:46', '2026-08-25 20:47:31'),
(392, 'App\\Models\\User', 1, 'auth_token', '3344a46850c7484faad7abf6967067ec810985c59c557248067faac57aa8dbd9', '[\"*\"]', '2026-08-25 20:52:28', NULL, '2026-08-25 20:52:11', '2026-08-25 20:52:28'),
(393, 'App\\Models\\User', 1, 'auth_token', '8531eecde8f0e030005d867e6e0a03b094ef7dc17c86b5eb9806333869a0b21a', '[\"*\"]', '2026-08-27 14:12:50', NULL, '2026-08-27 14:04:15', '2026-08-27 14:12:50'),
(394, 'App\\Models\\User', 1, 'auth_token', '70ca554c3794bbc8c19c08c7be3d348763af06c2338b9a5e47878a88caad8b8a', '[\"*\"]', '2026-08-31 19:15:26', NULL, '2026-08-31 15:08:57', '2026-08-31 19:15:26'),
(395, 'App\\Models\\User', 1, 'auth_token', 'd7bca920121451a86e99c902aeb33db802102b526639cf1590aa9a1f59fe1e8f', '[\"*\"]', '2026-09-01 07:15:56', NULL, '2026-09-01 01:18:42', '2026-09-01 07:15:56'),
(396, 'App\\Models\\User', 7, 'auth_token', '3072b8713fbecdb949341b83a55fe8552815e7e1b9d223f5917cf39b640c7a08', '[\"*\"]', NULL, NULL, '2026-09-01 07:16:15', '2026-09-01 07:16:15'),
(397, 'App\\Models\\User', 7, 'auth_token', '08d36bdf523c74b7d3770071cf01c2bc95f80da21edfb357411e2f35f2da92ea', '[\"*\"]', NULL, NULL, '2026-09-01 07:20:47', '2026-09-01 07:20:47'),
(398, 'App\\Models\\User', 1, 'auth_token', '694d70f1a720ad30089170a39f2f74f4b026352ad33b8acd495300f8d0362109', '[\"*\"]', '2026-09-01 08:49:45', NULL, '2026-09-01 07:21:08', '2026-09-01 08:49:45'),
(399, 'App\\Models\\User', 1, 'auth_token', '6f46eae19e876fdb6ae1c16fcc5bda902013deca6344180cbb76c6c76544d4b7', '[\"*\"]', '2026-09-01 08:33:09', NULL, '2026-09-01 08:04:00', '2026-09-01 08:33:09'),
(400, 'App\\Models\\User', 1, 'auth_token', 'b4c85f96c7d3089af43568a5b208110b176712f13f8888dc6d05773cf9141199', '[\"*\"]', '2026-09-01 08:37:45', NULL, '2026-09-01 08:33:35', '2026-09-01 08:37:45'),
(401, 'App\\Models\\User', 1, 'auth_token', 'f4ab4a96f767e7d4f48718ab7a1dcf399da8ccccf92913ea1d4b2164b877eb96', '[\"*\"]', '2026-09-01 10:01:32', NULL, '2026-09-01 08:38:13', '2026-09-01 10:01:32'),
(402, 'App\\Models\\User', 1, 'auth_token', '59dceb2087ecb09db142177c3169db86f3cc0f43384fd00e1fab8c9b5ed3ec8b', '[\"*\"]', '2026-09-01 10:25:03', NULL, '2026-09-01 10:24:47', '2026-09-01 10:25:03'),
(403, 'App\\Models\\User', 1, 'auth_token', '869697a02f5b27b27c558cdbd70d4dea298be74c639eaa22052b7369c373c76f', '[\"*\"]', '2026-09-01 10:35:13', NULL, '2026-09-01 10:35:07', '2026-09-01 10:35:13'),
(404, 'App\\Models\\User', 7, 'auth_token', '9bfcfb965041e834bd55664ee32c40430c2fc9278ade572e8d55f6d96ab9b741', '[\"*\"]', NULL, NULL, '2026-09-01 17:16:57', '2026-09-01 17:16:57'),
(405, 'App\\Models\\User', 9, 'auth_token', '79ae195652742723da7b42c263b7ac7831dac8839792ffbfd3d593f624d92f84', '[\"*\"]', '2026-09-01 17:31:26', NULL, '2026-09-01 17:18:04', '2026-09-01 17:31:26'),
(406, 'App\\Models\\User', 7, 'auth_token', '1f94e752a6c4068116d0a5dccd310d012d8c1d3cf4cbde7563196fcaf1d0e3da', '[\"*\"]', NULL, NULL, '2026-09-01 17:52:01', '2026-09-01 17:52:01'),
(407, 'App\\Models\\User', 9, 'auth_token', 'da24cbf831e9ca1de9c647398e91c7fcefaa3ae5ee8b7b4701cda76fc1ff43b5', '[\"*\"]', '2026-09-01 17:55:18', NULL, '2026-09-01 17:53:52', '2026-09-01 17:55:18'),
(408, 'App\\Models\\User', 1, 'auth_token', '6927232a11edd4f5c403d77436a84f5df36cf8fccc152bd2e36cbfe7244f95db', '[\"*\"]', '2026-09-02 00:03:49', NULL, '2026-09-01 23:46:50', '2026-09-02 00:03:49'),
(409, 'App\\Models\\User', 1, 'auth_token', '8875c306e21cf289de51133af90c8b8b5ff6b0c7a921a48bcec92fed0f3f1fc4', '[\"*\"]', '2026-09-02 00:17:22', NULL, '2026-09-02 00:17:15', '2026-09-02 00:17:22'),
(410, 'App\\Models\\User', 7, 'auth_token', '9f345d31c13f54aa22b3a84f664496c705d36b83c06ecab90839c316a70bd874', '[\"*\"]', '2026-09-02 00:19:54', NULL, '2026-09-02 00:19:41', '2026-09-02 00:19:54'),
(411, 'App\\Models\\User', 1, 'auth_token', 'e24f7e47703f341b0eca91330ae1e16ab9a75b414417049d37a340ea738986bc', '[\"*\"]', '2026-09-02 00:22:41', NULL, '2026-09-02 00:21:32', '2026-09-02 00:22:41'),
(412, 'App\\Models\\User', 1, 'auth_token', 'dd00a7007cf6a5906b8040858a5fc8e5405b83a16c6d823638c3eea7245094c0', '[\"*\"]', '2026-09-02 00:25:04', NULL, '2026-09-02 00:23:05', '2026-09-02 00:25:04'),
(413, 'App\\Models\\User', 1, 'auth_token', '8e5b4e809e4be108a7088d7441badf951a1ca06160564ecaa6708eff9013d301', '[\"*\"]', '2026-09-02 00:33:46', NULL, '2026-09-02 00:32:43', '2026-09-02 00:33:46'),
(414, 'App\\Models\\User', 1, 'auth_token', '9924f2a909ee62e6dd6ab3556692b740b5519afaeafb29d8f12eebbbe1509a83', '[\"*\"]', '2026-09-02 00:58:41', NULL, '2026-09-02 00:36:13', '2026-09-02 00:58:41'),
(415, 'App\\Models\\User', 1, 'auth_token', '1e6af32d3e5751d42efd16d97747a332e407552f2a0ff2b0759211fbe65ba2ce', '[\"*\"]', '2026-09-02 02:01:19', NULL, '2026-09-02 01:55:06', '2026-09-02 02:01:19'),
(416, 'App\\Models\\User', 1, 'auth_token', '8fe97126659d9e163c665d7c1a5622ed956bc3eb3d166e8c45d9f33525f5e15c', '[\"*\"]', '2026-09-02 02:28:04', NULL, '2026-09-02 02:02:00', '2026-09-02 02:28:04'),
(417, 'App\\Models\\User', 1, 'auth_token', '425ac62c1c552966b2995a37f94d0c8546bda414b51d62799f7bdc93088d3a41', '[\"*\"]', '2026-09-02 06:11:11', NULL, '2026-09-02 02:31:22', '2026-09-02 06:11:11'),
(418, 'App\\Models\\User', 1, 'auth_token', '8de7c594821d66f1bc8cbbf06fd63a0bea2e3d570621ae35cdc3a9aa649a73b7', '[\"*\"]', '2026-09-02 06:23:41', NULL, '2026-09-02 06:13:53', '2026-09-02 06:23:41'),
(419, 'App\\Models\\User', 1, 'auth_token', 'c92557b0f6927ce0186763a7d40194233ac677581b37cb0330b5e5495a28bec3', '[\"*\"]', '2026-09-02 06:32:25', NULL, '2026-09-02 06:32:12', '2026-09-02 06:32:25'),
(420, 'App\\Models\\User', 1, 'auth_token', 'f87dfdafd1f881a8ce5dbd63da2096dd607cba1e45152ca2c6c6ba21dd48a5f8', '[\"*\"]', '2026-09-02 06:37:44', NULL, '2026-09-02 06:36:25', '2026-09-02 06:37:44'),
(421, 'App\\Models\\User', 1, 'auth_token', 'f2bd4ac350a1afb64a79fcbecd24f5073a9426a6b2f6fd8cb6450f07c84e5da9', '[\"*\"]', '2026-09-02 07:15:45', NULL, '2026-09-02 07:14:37', '2026-09-02 07:15:45'),
(422, 'App\\Models\\User', 7, 'auth_token', '7e603ec94c8d04c581c22bb0a136b86d1a859c45638a30da9ada2a18ba07f9be', '[\"*\"]', NULL, NULL, '2026-09-02 07:16:58', '2026-09-02 07:16:58'),
(423, 'App\\Models\\User', 1, 'auth_token', '98f1f71ac2d0038de26a6ffd121db3f99b406a8486278968d824c3c6ac335572', '[\"*\"]', '2026-09-02 07:20:08', NULL, '2026-09-02 07:17:20', '2026-09-02 07:20:08'),
(424, 'App\\Models\\User', 1, 'auth_token', '23b9793a223aef8eadfd6a3eb78dfa5933ac492efceb52d0c1be66a161bfc37d', '[\"*\"]', '2026-09-02 07:39:51', NULL, '2026-09-02 07:39:33', '2026-09-02 07:39:51'),
(425, 'App\\Models\\User', 7, 'auth_token', 'f4b1d7be9d67f7f67697118ebd4cccde34f31f859351ed1582f93b10d71fc0a9', '[\"*\"]', '2026-09-02 07:41:01', NULL, '2026-09-02 07:40:16', '2026-09-02 07:41:01'),
(426, 'App\\Models\\User', 7, 'auth_token', '11427f9ca3336472038ebd95c8f46741fca719d14b1bfb528be5beb9feb22615', '[\"*\"]', '2026-09-02 07:41:51', NULL, '2026-09-02 07:41:45', '2026-09-02 07:41:51'),
(427, 'App\\Models\\User', 9, 'auth_token', '3ec050f52a9c31e4f3df25f3a61fa8d0a0378b800a7c18637c45f526b8df4626', '[\"*\"]', '2026-09-02 07:42:52', NULL, '2026-09-02 07:42:51', '2026-09-02 07:42:52'),
(428, 'App\\Models\\User', 9, 'auth_token', '674f5e65378483a229d4bea86261649ee535dfc2f0e94f418a829205934f66b1', '[\"*\"]', '2026-09-02 07:52:25', NULL, '2026-09-02 07:51:08', '2026-09-02 07:52:25'),
(429, 'App\\Models\\User', 1, 'auth_token', 'b318363bba48428470babc5381f80852df69d7f808b226eb135105d94aa45d7d', '[\"*\"]', '2026-09-02 07:52:50', NULL, '2026-09-02 07:52:41', '2026-09-02 07:52:50'),
(430, 'App\\Models\\User', 7, 'auth_token', 'ed01f78de554b294159553d1736a02a894812b00f585d693793316f07e3934f8', '[\"*\"]', '2026-09-03 05:55:20', NULL, '2026-09-03 04:53:38', '2026-09-03 05:55:20'),
(431, 'App\\Models\\User', 1, 'auth_token', '2cb9f2a77f71c201e985198751bf03df9db87ffa3961c1bce6f72273ab9fc3ef', '[\"*\"]', '2026-09-03 06:03:27', NULL, '2026-09-03 06:02:44', '2026-09-03 06:03:27'),
(432, 'App\\Models\\User', 1, 'auth_token', 'ca801f6a894d08719f00c15d7aac1bfc2ada3874c347eb48fdc24150f03cb75c', '[\"*\"]', '2026-09-03 22:59:57', NULL, '2026-09-03 21:18:37', '2026-09-03 22:59:57'),
(433, 'App\\Models\\User', 3, 'auth_token', '14751160399cf06b325e74d155a2304e7c909c0ee56e314c8e6aa21c1d43c63c', '[\"*\"]', NULL, NULL, '2026-09-03 23:35:44', '2026-09-03 23:35:44'),
(434, 'App\\Models\\User', 1, 'auth_token', 'e84711d7cb5bda7890dc051ed03785e5ae6b8eda833769e2d1bbf55c1956f408', '[\"*\"]', '2026-09-06 08:40:57', NULL, '2026-09-06 08:12:02', '2026-09-06 08:40:57'),
(435, 'App\\Models\\User', 1, 'auth_token', '8c16199651608df10289e1e8c06b68b12cef886ef551531767768ebb5c790c5e', '[\"*\"]', '2026-09-06 20:39:42', NULL, '2026-09-06 20:28:46', '2026-09-06 20:39:42'),
(436, 'App\\Models\\User', 1, 'auth_token', 'e6ec119e6ec703f0841ba4e7c55e983b0eb8e180dcc12236f49417716d7e4357', '[\"*\"]', '2026-09-06 20:59:49', NULL, '2026-09-06 20:56:37', '2026-09-06 20:59:49'),
(437, 'App\\Models\\User', 1, 'auth_token', 'f97fe5eed2e193e390b453e1f726f762fefcde2150484471f766a6152b49d987', '[\"*\"]', '2026-09-06 21:14:42', NULL, '2026-09-06 21:01:22', '2026-09-06 21:14:42'),
(438, 'App\\Models\\User', 1, 'auth_token', '27c5741390d087172e0ad78fe1df1d91c45f1331f7524906762b9b235f80357b', '[\"*\"]', '2026-09-06 23:09:23', NULL, '2026-09-06 23:02:09', '2026-09-06 23:09:23'),
(439, 'App\\Models\\User', 1, 'auth_token', '3c2346f103d93d2feeff73acb1e792f78a2c34b584c11b759ffc7beff8548356', '[\"*\"]', '2026-09-07 00:07:26', NULL, '2026-09-06 23:42:57', '2026-09-07 00:07:26'),
(440, 'App\\Models\\User', 3, 'auth_token', '9ee88790c6cf3bcf85c9df493de8b959f562d037cc10eb91d5d888e41e08c62a', '[\"*\"]', '2026-09-07 00:08:11', NULL, '2026-09-07 00:07:53', '2026-09-07 00:08:11'),
(441, 'App\\Models\\User', 1, 'auth_token', 'f008fea76260467e02c1b717b4a8d027ca424446a7c5f6338050be42a4072331', '[\"*\"]', '2026-09-07 01:03:48', NULL, '2026-09-07 00:13:22', '2026-09-07 01:03:48'),
(442, 'App\\Models\\User', 1, 'auth_token', 'b78b4f33c913fede8e2e55f8f546fa0d0c4b75b33969b6db17889c747dadb0b0', '[\"*\"]', '2026-09-07 01:36:02', NULL, '2026-09-07 01:20:52', '2026-09-07 01:36:02'),
(443, 'App\\Models\\User', 3, 'auth_token', '8d05b21b6ee6ac80163a10cfe6fa10cfbb0b68973058e0002e8fe498712f42f4', '[\"*\"]', '2026-09-07 01:38:40', NULL, '2026-09-07 01:21:23', '2026-09-07 01:38:40'),
(444, 'App\\Models\\User', 1, 'auth_token', '55573ad6dbd1180746116b41086b322147ec142633ca20b346dceb5fb23043e7', '[\"*\"]', '2026-09-08 22:12:42', NULL, '2026-09-08 22:11:49', '2026-09-08 22:12:42'),
(445, 'App\\Models\\User', 1, 'auth_token', '13d1357f4e2e10c75e1b78ad4af48dcfe07b906cd59684f920a0bc899d6ce6da', '[\"*\"]', '2026-09-08 23:06:56', NULL, '2026-09-08 23:05:06', '2026-09-08 23:06:56'),
(446, 'App\\Models\\User', 3, 'auth_token', '2aa9b857bed2013f19ce2c5f9faf21399caeddedf8401765a5a8726f582c6a49', '[\"*\"]', NULL, NULL, '2026-09-08 23:07:26', '2026-09-08 23:07:26'),
(447, 'App\\Models\\User', 3, 'auth_token', '01695a2c33756cdbe91ed29b4a2fd864b7142204c58f14c8b73093fd2577eefc', '[\"*\"]', '2026-09-08 23:29:18', NULL, '2026-09-08 23:16:17', '2026-09-08 23:29:18'),
(448, 'App\\Models\\User', 1, 'auth_token', 'b59a5df83bce9f75d792478cf0f40b5e71f46c5cb3688103e51de46e772187e7', '[\"*\"]', '2026-09-09 23:17:10', NULL, '2026-09-09 21:43:43', '2026-09-09 23:17:10'),
(449, 'App\\Models\\User', 3, 'auth_token', '81572f2ca83547aadca18e25f5cc6471042b20cc80b668d023acd8e05489b39d', '[\"*\"]', NULL, NULL, '2026-09-09 23:17:57', '2026-09-09 23:17:57'),
(450, 'App\\Models\\User', 4, 'auth_token', 'a4aee8af03b4a69e71ff16e7a9a90c2a842c0a60d91d8c423a2e295a3881d8d9', '[\"*\"]', '2026-09-10 00:39:25', NULL, '2026-09-10 00:24:21', '2026-09-10 00:39:25'),
(451, 'App\\Models\\User', 1, 'auth_token', '61b5f1833bd5c9490d602dd6f5a93793f1c57d055f8fd09b3b30ef3184f6882f', '[\"*\"]', '2026-09-11 01:07:18', NULL, '2026-09-10 21:29:15', '2026-09-11 01:07:18'),
(452, 'App\\Models\\User', 1, 'auth_token', '88bb1cdd09c556de52677518b90032faff59e468472382924a259088222d169c', '[\"*\"]', '2026-09-11 02:43:35', NULL, '2026-09-11 02:05:58', '2026-09-11 02:43:35'),
(453, 'App\\Models\\User', 1, 'auth_token', '1ad85b58b3fcbdf69f00ce4fc7d1e4b0e9fe2887f3febf4d1c1d7de07da7e51d', '[\"*\"]', '2026-09-11 07:22:43', NULL, '2026-09-11 06:32:45', '2026-09-11 07:22:43'),
(454, 'App\\Models\\User', 1, 'auth_token', 'e50495e08cd5387fad521378fc074f122acc0e0ad3278edbd2005a6f9dca9044', '[\"*\"]', '2026-09-11 08:07:00', NULL, '2026-09-11 07:40:54', '2026-09-11 08:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variety_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_length` decimal(10,2) DEFAULT NULL,
  `ready_production` int DEFAULT NULL,
  `machine_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `factory_id` bigint UNSIGNED DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `waste_production` double DEFAULT NULL,
  `remaining` double DEFAULT NULL,
  `alert_threshold` decimal(10,2) DEFAULT NULL,
  `alert_sent` tinyint(1) NOT NULL DEFAULT '0',
  `earned_amount` double DEFAULT NULL,
  `amount_per_meter` float DEFAULT NULL,
  `select_days` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productions`
--

INSERT INTO `productions` (`id`, `batch_id`, `variety_type`, `total_length`, `ready_production`, `machine_id`, `employee_id`, `factory_id`, `manager_id`, `shift_start`, `shift_end`, `status`, `created_at`, `updated_at`, `waste_production`, `remaining`, `alert_threshold`, `alert_sent`, `earned_amount`, `amount_per_meter`, `select_days`) VALUES
(1, NULL, NULL, NULL, 0, 1, 1, 1, 3, '05:00:00', '17:00:00', 1, '2026-09-03 22:36:35', '2026-09-03 22:36:35', 0, 0, NULL, 0, NULL, NULL, NULL),
(2, NULL, NULL, NULL, 0, 2, 1, 1, 3, '05:00:00', '17:00:00', 1, '2026-09-03 22:36:55', '2026-09-03 22:36:55', 0, 0, NULL, 0, NULL, NULL, NULL),
(3, NULL, NULL, NULL, 0, 1, 2, 1, 3, '17:00:00', '05:00:00', 1, '2026-09-03 22:37:19', '2026-09-03 22:37:19', 0, 0, NULL, 0, NULL, NULL, NULL),
(4, NULL, NULL, NULL, 0, 2, 2, 1, 3, '17:00:00', '05:00:00', 1, '2026-09-03 22:37:51', '2026-09-03 22:37:51', 0, 0, NULL, 0, NULL, NULL, NULL),
(5, NULL, NULL, NULL, 0, 3, 3, 1, 3, '05:00:00', '17:00:00', 1, '2026-09-03 22:38:45', '2026-09-03 22:38:45', 0, 0, NULL, 0, NULL, NULL, NULL),
(6, NULL, NULL, NULL, 0, 4, 3, 1, 3, '05:00:00', '17:00:00', 2, '2026-09-03 22:39:05', '2026-09-07 01:24:11', 0, 0, NULL, 0, NULL, NULL, NULL),
(7, 'BATCH-1-1788493733', 'Velvet Shawl Small', 450.00, 0, 1, 1, 1, 3, '05:00:00', '17:00:00', 4, '2026-09-03 22:48:53', '2026-09-06 21:11:13', 0, 450, 50.00, 0, 0, 30, NULL),
(8, 'BATCH-1-1788493733', 'Velvet Shawl Small', 450.00, 0, 1, 2, 1, 3, '17:00:00', '05:00:00', 1, '2026-09-03 22:48:53', '2026-09-03 22:48:53', 0, 450, 50.00, 0, NULL, 30, NULL),
(9, 'BATCH-2-1788493825', 'Velvet Shawl Small', 450.00, 0, 2, 1, 1, 3, '05:00:00', '17:00:00', 1, '2026-09-03 22:50:25', '2026-09-03 22:50:25', 0, 450, 50.00, 0, NULL, 30, NULL),
(10, 'BATCH-2-1788493825', 'Velvet Shawl Small', 450.00, 0, 2, 2, 1, 3, '17:00:00', '05:00:00', 4, '2026-09-03 22:50:25', '2026-09-06 21:12:00', 0, 450, 50.00, 0, 0, 30, NULL),
(11, 'BATCH-3-1788493936', 'Velvet Shawl Large', 600.00, 0, 3, 3, 1, 3, '05:00:00', '17:00:00', 4, '2026-09-03 22:52:16', '2026-09-07 01:36:02', 0, 600, 100.00, 0, 0, 50, NULL),
(12, 'BATCH-4-1788493978', 'Velvet Shawl Large', 600.00, 0, 4, 3, 1, 3, '05:00:00', '17:00:00', 2, '2026-09-03 22:52:58', '2026-09-07 01:24:10', 0, 600, 100.00, 0, NULL, 50, NULL),
(13, NULL, NULL, NULL, 0, 5, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-10 23:55:05', '2026-09-10 23:55:05', 0, 0, NULL, 0, NULL, NULL, NULL),
(14, NULL, NULL, NULL, 0, 6, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-10 23:56:45', '2026-09-10 23:56:45', 0, 0, NULL, 0, NULL, NULL, NULL),
(15, NULL, NULL, NULL, 0, 7, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-10 23:57:11', '2026-09-10 23:57:11', 0, 0, NULL, 0, NULL, NULL, NULL),
(16, NULL, NULL, NULL, 0, 8, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-10 23:57:29', '2026-09-10 23:57:29', 0, 0, NULL, 0, NULL, NULL, NULL),
(17, NULL, NULL, NULL, 0, 5, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-10 23:57:52', '2026-09-10 23:57:52', 0, 0, NULL, 0, NULL, NULL, NULL),
(18, NULL, NULL, NULL, 0, 6, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-10 23:59:48', '2026-09-10 23:59:48', 0, 0, NULL, 0, NULL, NULL, NULL),
(19, NULL, NULL, NULL, 0, 7, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:00:09', '2026-09-11 00:00:09', 0, 0, NULL, 0, NULL, NULL, NULL),
(20, NULL, NULL, NULL, 0, 8, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:00:32', '2026-09-11 00:00:32', 0, 0, NULL, 0, NULL, NULL, NULL),
(21, NULL, NULL, NULL, 0, 9, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:01:14', '2026-09-11 00:01:14', 0, 0, NULL, 0, NULL, NULL, NULL),
(22, NULL, NULL, NULL, 0, 10, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:01:34', '2026-09-11 00:01:34', 0, 0, NULL, 0, NULL, NULL, NULL),
(23, NULL, NULL, NULL, 0, 11, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:18:33', '2026-09-11 00:18:33', 0, 0, NULL, 0, NULL, NULL, NULL),
(24, NULL, NULL, NULL, 0, 12, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:18:55', '2026-09-11 00:18:55', 0, 0, NULL, 0, NULL, NULL, NULL),
(25, NULL, NULL, NULL, 0, 9, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:22:47', '2026-09-11 00:22:47', 0, 0, NULL, 0, NULL, NULL, NULL),
(26, NULL, NULL, NULL, 0, 10, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:23:08', '2026-09-11 00:23:08', 0, 0, NULL, 0, NULL, NULL, NULL),
(27, NULL, NULL, NULL, 0, 11, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:23:27', '2026-09-11 00:23:27', 0, 0, NULL, 0, NULL, NULL, NULL),
(28, NULL, NULL, NULL, 0, 12, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:23:47', '2026-09-11 00:23:47', 0, 0, NULL, 0, NULL, NULL, NULL),
(29, NULL, NULL, NULL, 0, 13, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:25:13', '2026-09-11 00:25:13', 0, 0, NULL, 0, NULL, NULL, NULL),
(30, NULL, NULL, NULL, 0, 14, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:25:34', '2026-09-11 00:25:34', 0, 0, NULL, 0, NULL, NULL, NULL),
(31, NULL, NULL, NULL, 0, 15, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:26:31', '2026-09-11 00:26:31', 0, 0, NULL, 0, NULL, NULL, NULL),
(32, NULL, NULL, NULL, 0, 16, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:26:52', '2026-09-11 00:26:52', 0, 0, NULL, 0, NULL, NULL, NULL),
(33, NULL, NULL, NULL, 0, 17, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:27:15', '2026-09-11 00:27:15', 0, 0, NULL, 0, NULL, NULL, NULL),
(34, NULL, NULL, NULL, 0, 18, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:27:37', '2026-09-11 00:27:37', 0, 0, NULL, 0, NULL, NULL, NULL),
(35, NULL, NULL, NULL, 0, 19, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:28:55', '2026-09-11 00:28:55', 0, 0, NULL, 0, NULL, NULL, NULL),
(36, NULL, NULL, NULL, 0, 20, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:29:15', '2026-09-11 00:29:15', 0, 0, NULL, 0, NULL, NULL, NULL),
(37, NULL, NULL, NULL, 0, 13, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:29:41', '2026-09-11 00:29:41', 0, 0, NULL, 0, NULL, NULL, NULL),
(38, NULL, NULL, NULL, 0, 14, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:30:04', '2026-09-11 00:30:04', 0, 0, NULL, 0, NULL, NULL, NULL),
(39, NULL, NULL, NULL, 0, 15, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:30:31', '2026-09-11 00:30:31', 0, 0, NULL, 0, NULL, NULL, NULL),
(40, NULL, NULL, NULL, 0, 16, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:31:26', '2026-09-11 00:31:26', 0, 0, NULL, 0, NULL, NULL, NULL),
(41, NULL, NULL, NULL, 0, 17, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:31:52', '2026-09-11 00:31:52', 0, 0, NULL, 0, NULL, NULL, NULL),
(42, NULL, NULL, NULL, 0, 18, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:32:21', '2026-09-11 00:32:21', 0, 0, NULL, 0, NULL, NULL, NULL),
(43, NULL, NULL, NULL, 0, 19, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:32:42', '2026-09-11 00:32:42', 0, 0, NULL, 0, NULL, NULL, NULL),
(44, NULL, NULL, NULL, 0, 20, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:33:03', '2026-09-11 00:33:03', 0, 0, NULL, 0, NULL, NULL, NULL),
(45, 'BATCH-5-1789105664', 'Check', 2100.00, 0, 5, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:47:44', '2026-09-11 00:47:44', 0, 1900, 100.00, 0, NULL, 15, NULL),
(46, 'BATCH-5-1789105664', 'Check', 2100.00, 0, 5, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:47:44', '2026-09-11 00:47:44', 0, 1900, 100.00, 0, NULL, 15, NULL),
(47, 'BATCH-6-1789105795', 'Check', 2100.00, 0, 6, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:49:55', '2026-09-11 00:49:55', 0, 2100, 100.00, 0, NULL, 15, NULL),
(48, 'BATCH-6-1789105795', 'Check', 2100.00, 0, 6, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:49:55', '2026-09-11 00:49:55', 0, 2100, 100.00, 0, NULL, 15, NULL),
(49, 'BATCH-7-1789105849', 'Check', 2100.00, 0, 7, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:50:49', '2026-09-11 00:50:49', 0, 2100, 100.00, 0, NULL, 15, NULL),
(50, 'BATCH-7-1789105849', 'Check', 2100.00, 0, 7, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:50:49', '2026-09-11 00:50:49', 0, 2100, 100.00, 0, NULL, 15, NULL),
(51, 'BATCH-8-1789105881', 'Check', 2100.00, 0, 8, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:51:21', '2026-09-11 00:51:21', 0, 2100, 100.00, 0, NULL, 15, NULL),
(52, 'BATCH-8-1789105881', 'Check', 2100.00, 0, 8, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:51:21', '2026-09-11 00:51:21', 0, 2100, 100.00, 0, NULL, 15, NULL),
(53, 'BATCH-9-1789105913', 'Check', 2100.00, 0, 9, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:51:53', '2026-09-11 00:51:53', 0, 2100, 100.00, 0, NULL, 15, NULL),
(54, 'BATCH-9-1789105913', 'Check', 2100.00, 0, 9, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:51:53', '2026-09-11 00:51:53', 0, 2100, 100.00, 0, NULL, 15, NULL),
(55, 'BATCH-10-1789105941', 'Check', 2100.00, 0, 10, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:52:21', '2026-09-11 00:52:21', 0, 2100, 100.00, 0, NULL, 15, NULL),
(56, 'BATCH-10-1789105941', 'Check', 2100.00, 0, 10, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:52:21', '2026-09-11 00:52:21', 0, 2100, 100.00, 0, NULL, 15, NULL),
(57, 'BATCH-11-1789105969', 'Check', 2100.00, 0, 11, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:52:49', '2026-09-11 00:52:49', 0, 2100, 100.00, 0, NULL, 15, NULL),
(58, 'BATCH-11-1789105969', 'Check', 2100.00, 0, 11, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:52:49', '2026-09-11 00:52:49', 0, 2100, 100.00, 0, NULL, 15, NULL),
(59, 'BATCH-12-1789105995', 'Check', 2100.00, 0, 12, 4, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:53:15', '2026-09-11 00:53:15', 0, 2100, 100.00, 0, NULL, 15, NULL),
(60, 'BATCH-12-1789105995', 'Check', 2100.00, 0, 12, 5, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:53:15', '2026-09-11 00:53:15', 0, 2100, 100.00, 0, NULL, 15, NULL),
(61, 'BATCH-13-1789106062', 'Lining', 1900.00, 0, 13, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:54:22', '2026-09-11 00:54:22', 0, 1900, 100.00, 0, NULL, 12, NULL),
(62, 'BATCH-13-1789106062', 'Lining', 1900.00, 0, 13, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:54:22', '2026-09-11 00:54:22', 0, 1900, 100.00, 0, NULL, 12, NULL),
(63, 'BATCH-14-1789106088', 'Lining', 1900.00, 0, 14, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:54:48', '2026-09-11 00:54:48', 0, 1900, 100.00, 0, NULL, 12, NULL),
(64, 'BATCH-14-1789106088', 'Lining', 1900.00, 0, 14, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:54:48', '2026-09-11 00:54:48', 0, 1900, 100.00, 0, NULL, 12, NULL),
(65, 'BATCH-15-1789106134', 'Lining', 1900.00, 0, 15, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:55:34', '2026-09-11 00:55:34', 0, 1900, 100.00, 0, NULL, 12, NULL),
(66, 'BATCH-15-1789106134', 'Lining', 1900.00, 0, 15, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:55:34', '2026-09-11 00:55:34', 0, 1900, 100.00, 0, NULL, 12, NULL),
(67, 'BATCH-16-1789106164', 'Lining', 1900.00, 0, 16, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:56:04', '2026-09-11 00:56:04', 0, 1900, 100.00, 0, NULL, 12, NULL),
(68, 'BATCH-16-1789106164', 'Lining', 1900.00, 0, 16, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:56:04', '2026-09-11 00:56:04', 0, 1900, 100.00, 0, NULL, 12, NULL),
(69, 'BATCH-17-1789106261', 'Lining', 1900.00, 0, 17, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:57:41', '2026-09-11 00:57:41', 0, 1900, 100.00, 0, NULL, 12, NULL),
(70, 'BATCH-17-1789106261', 'Lining', 1900.00, 0, 17, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:57:41', '2026-09-11 00:57:41', 0, 1900, 100.00, 0, NULL, 12, NULL),
(71, 'BATCH-18-1789106299', 'Lining', 1900.00, 0, 18, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:58:19', '2026-09-11 00:58:19', 0, 1900, 100.00, 0, NULL, 12, NULL),
(72, 'BATCH-18-1789106299', 'Lining', 1900.00, 0, 18, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:58:19', '2026-09-11 00:58:19', 0, 1900, 100.00, 0, NULL, 12, NULL),
(73, 'BATCH-19-1789106334', 'Lining', 1900.00, 0, 19, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:58:54', '2026-09-11 00:58:54', 0, 1900, 100.00, 0, NULL, 12, NULL),
(74, 'BATCH-19-1789106334', 'Lining', 1900.00, 0, 19, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:58:54', '2026-09-11 00:58:54', 0, 1900, 100.00, 0, NULL, 12, NULL),
(75, 'BATCH-20-1789106357', 'Lining', 1900.00, 0, 20, 6, 2, 6, '05:00:00', '17:00:00', 0, '2026-09-11 00:59:17', '2026-09-11 00:59:17', 0, 1900, 100.00, 0, NULL, 12, NULL),
(76, 'BATCH-20-1789106357', 'Lining', 1900.00, 0, 20, 7, 2, 6, '17:00:00', '05:00:00', 0, '2026-09-11 00:59:17', '2026-09-11 00:59:17', 0, 1900, 100.00, 0, NULL, 12, NULL),
(77, NULL, NULL, NULL, 0, 21, 8, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:31:18', '2026-09-11 02:31:18', 0, 0, NULL, 0, NULL, NULL, NULL),
(78, NULL, NULL, NULL, 0, 22, 8, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:31:39', '2026-09-11 02:31:39', 0, 0, NULL, 0, NULL, NULL, NULL),
(79, NULL, NULL, NULL, 0, 23, 9, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:31:59', '2026-09-11 02:31:59', 0, 0, NULL, 0, NULL, NULL, NULL),
(80, NULL, NULL, NULL, 0, 24, 9, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:32:19', '2026-09-11 02:32:19', 0, 0, NULL, 0, NULL, NULL, NULL),
(81, NULL, NULL, NULL, 0, 25, 10, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:32:38', '2026-09-11 02:32:38', 0, 0, NULL, 0, NULL, NULL, NULL),
(82, NULL, NULL, NULL, 0, 26, 10, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:32:59', '2026-09-11 02:32:59', 0, 0, NULL, 0, NULL, NULL, NULL),
(83, 'BATCH-21-1789112073', 'small velvet shawl', 450.00, 0, 21, 8, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:34:33', '2026-09-11 02:34:33', 0, 450, 50.00, 0, NULL, 30, NULL),
(84, 'BATCH-22-1789112185', 'small velvet shawl', 450.00, 0, 22, 8, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:36:25', '2026-09-11 02:36:25', 0, 450, 50.00, 0, NULL, 30, NULL),
(85, 'BATCH-23-1789112275', 'small velvet shawl', 450.00, 0, 23, 9, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:37:55', '2026-09-11 02:37:55', 0, 450, 50.00, 0, NULL, 30, NULL),
(86, 'BATCH-24-1789112319', 'small velvet shawl', 450.00, 0, 24, 9, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:38:39', '2026-09-11 02:38:39', 0, 450, 50.00, 0, NULL, 30, NULL),
(87, 'BATCH-25-1789112458', 'Large velvet shawl', 650.00, 0, 25, 10, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:40:58', '2026-09-11 02:40:58', 0, 650, 50.00, 0, NULL, 50, NULL),
(88, 'BATCH-26-1789112488', 'Large velvet shawl', 650.00, 0, 26, 10, 3, 11, '05:00:00', '17:00:00', 0, '2026-09-11 02:41:28', '2026-09-11 02:41:28', 0, 650, 50.00, 0, NULL, 50, NULL),
(89, NULL, NULL, NULL, 0, 27, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:47:18', '2026-09-11 07:47:18', 0, 0, NULL, 0, NULL, NULL, NULL),
(90, NULL, NULL, NULL, 0, 28, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:47:46', '2026-09-11 07:47:46', 0, 0, NULL, 0, NULL, NULL, NULL),
(91, NULL, NULL, NULL, 0, 29, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:48:05', '2026-09-11 07:48:05', 0, 0, NULL, 0, NULL, NULL, NULL),
(92, NULL, NULL, NULL, 0, 30, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:48:24', '2026-09-11 07:48:24', 0, 0, NULL, 0, NULL, NULL, NULL),
(93, NULL, NULL, NULL, 0, 31, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:50:10', '2026-09-11 07:50:10', 0, 0, NULL, 0, NULL, NULL, NULL),
(94, NULL, NULL, NULL, 0, 32, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:50:28', '2026-09-11 07:50:28', 0, 0, NULL, 0, NULL, NULL, NULL),
(95, NULL, NULL, NULL, 0, 27, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:50:49', '2026-09-11 07:50:49', 0, 0, NULL, 0, NULL, NULL, NULL),
(96, NULL, NULL, NULL, 0, 28, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:51:13', '2026-09-11 07:51:13', 0, 0, NULL, 0, NULL, NULL, NULL),
(97, NULL, NULL, NULL, 0, 29, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:51:32', '2026-09-11 07:51:32', 0, 0, NULL, 0, NULL, NULL, NULL),
(98, NULL, NULL, NULL, 0, 30, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:51:51', '2026-09-11 07:51:51', 0, 0, NULL, 0, NULL, NULL, NULL),
(99, NULL, NULL, NULL, 0, 31, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:52:12', '2026-09-11 07:52:12', 0, 0, NULL, 0, NULL, NULL, NULL),
(100, NULL, NULL, NULL, 0, 32, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:52:30', '2026-09-11 07:52:30', 0, 0, NULL, 0, NULL, NULL, NULL),
(101, NULL, NULL, NULL, 0, 33, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:52:57', '2026-09-11 07:52:57', 0, 0, NULL, 0, NULL, NULL, NULL),
(102, NULL, NULL, NULL, 0, 34, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:53:13', '2026-09-11 07:53:13', 0, 0, NULL, 0, NULL, NULL, NULL),
(103, NULL, NULL, NULL, 0, 35, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:53:32', '2026-09-11 07:53:32', 0, 0, NULL, 0, NULL, NULL, NULL),
(104, NULL, NULL, NULL, 0, 36, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:53:57', '2026-09-11 07:53:57', 0, 0, NULL, 0, NULL, NULL, NULL),
(105, NULL, NULL, NULL, 0, 37, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:54:15', '2026-09-11 07:54:15', 0, 0, NULL, 0, NULL, NULL, NULL),
(106, NULL, NULL, NULL, 0, 38, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 07:54:34', '2026-09-11 07:54:34', 0, 0, NULL, 0, NULL, NULL, NULL),
(107, NULL, NULL, NULL, 0, 33, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:55:04', '2026-09-11 07:55:04', 0, 0, NULL, 0, NULL, NULL, NULL),
(108, NULL, NULL, NULL, 0, 34, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:55:21', '2026-09-11 07:55:21', 0, 0, NULL, 0, NULL, NULL, NULL),
(109, NULL, NULL, NULL, 0, 35, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:55:40', '2026-09-11 07:55:40', 0, 0, NULL, 0, NULL, NULL, NULL),
(110, NULL, NULL, NULL, 0, 36, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:55:58', '2026-09-11 07:55:58', 0, 0, NULL, 0, NULL, NULL, NULL),
(111, NULL, NULL, NULL, 0, 37, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:56:17', '2026-09-11 07:56:17', 0, 0, NULL, 0, NULL, NULL, NULL),
(112, NULL, NULL, NULL, 0, 38, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 07:56:36', '2026-09-11 07:56:36', 0, 0, NULL, 0, NULL, NULL, NULL),
(113, 'BATCH-27-1789131663', 'Cotton Motorway', 1700.00, 0, 27, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:01:03', '2026-09-11 08:01:03', 0, 1700, 100.00, 0, NULL, 12, NULL),
(114, 'BATCH-27-1789131663', 'Cotton Motorway', 1700.00, 0, 27, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:01:03', '2026-09-11 08:01:03', 0, 1700, 100.00, 0, NULL, 12, NULL),
(115, 'BATCH-28-1789131690', 'Cotton Motorway', 1700.00, 0, 28, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:01:30', '2026-09-11 08:01:30', 0, 1700, 100.00, 0, NULL, 12, NULL),
(116, 'BATCH-28-1789131690', 'Cotton Motorway', 1700.00, 0, 28, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:01:30', '2026-09-11 08:01:30', 0, 1700, 100.00, 0, NULL, 12, NULL),
(117, 'BATCH-29-1789131716', 'Cotton Motorway', 1700.00, 0, 29, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:01:56', '2026-09-11 08:01:56', 0, 1700, 100.00, 0, NULL, 12, NULL),
(118, 'BATCH-29-1789131716', 'Cotton Motorway', 1700.00, 0, 29, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:01:56', '2026-09-11 08:01:56', 0, 1700, 100.00, 0, NULL, 12, NULL),
(119, 'BATCH-30-1789131744', 'Cotton Motorway', 1700.00, 0, 30, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:02:24', '2026-09-11 08:02:24', 0, 1700, 100.00, 0, NULL, 12, NULL),
(120, 'BATCH-30-1789131744', 'Cotton Motorway', 1700.00, 0, 30, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:02:24', '2026-09-11 08:02:24', 0, 1700, 100.00, 0, NULL, 12, NULL),
(121, 'BATCH-30-1789131769', 'Cotton Motorway', 1700.00, 0, 30, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:02:49', '2026-09-11 08:02:49', 0, 1700, 100.00, 0, NULL, 12, NULL),
(122, 'BATCH-30-1789131769', 'Cotton Motorway', 1700.00, 0, 30, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:02:49', '2026-09-11 08:02:49', 0, 1700, 100.00, 0, NULL, 12, NULL),
(123, 'BATCH-31-1789131799', 'Cotton Motorway', 1700.00, 0, 31, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:03:19', '2026-09-11 08:03:19', 0, 1700, 100.00, 0, NULL, 12, NULL),
(124, 'BATCH-31-1789131799', 'Cotton Motorway', 1700.00, 0, 31, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:03:19', '2026-09-11 08:03:19', 0, 1700, 100.00, 0, NULL, 12, NULL),
(125, 'BATCH-32-1789131829', 'Cotton Motorway', 1700.00, 0, 32, 11, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:03:49', '2026-09-11 08:03:49', 0, 1700, 100.00, 0, NULL, 12, NULL),
(126, 'BATCH-32-1789131829', 'Cotton Motorway', 1700.00, 0, 32, 12, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:03:49', '2026-09-11 08:03:49', 0, 1700, 100.00, 0, NULL, 12, NULL),
(127, 'BATCH-33-1789131879', 'Cotton Turkish', 1500.00, 0, 33, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:04:39', '2026-09-11 08:04:39', 0, 1500, 100.00, 0, NULL, 13, NULL),
(128, 'BATCH-33-1789131879', 'Cotton Turkish', 1500.00, 0, 33, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:04:39', '2026-09-11 08:04:39', 0, 1500, 100.00, 0, NULL, 13, NULL),
(129, 'BATCH-34-1789131906', 'Cotton Turkish', 1500.00, 0, 34, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:05:06', '2026-09-11 08:05:06', 0, 1500, 100.00, 0, NULL, 13, NULL),
(130, 'BATCH-34-1789131906', 'Cotton Turkish', 1500.00, 0, 34, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:05:06', '2026-09-11 08:05:06', 0, 1500, 100.00, 0, NULL, 13, NULL),
(131, 'BATCH-35-1789131935', 'Cotton Turkish', 1500.00, 0, 35, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:05:35', '2026-09-11 08:05:35', 0, 1500, 100.00, 0, NULL, 13, NULL),
(132, 'BATCH-35-1789131935', 'Cotton Turkish', 1500.00, 0, 35, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:05:35', '2026-09-11 08:05:35', 0, 1500, 100.00, 0, NULL, 13, NULL),
(133, 'BATCH-36-1789131965', 'Cotton Turkish', 1500.00, 0, NULL, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:06:05', '2026-09-11 08:06:05', 0, 1500, 100.00, 0, NULL, 13, NULL),
(134, 'BATCH-36-1789131965', 'Cotton Turkish', 1500.00, 0, NULL, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:06:05', '2026-09-11 08:06:05', 0, 1500, 100.00, 0, NULL, 13, NULL),
(135, 'BATCH-37-1789131990', 'Cotton Turkish', 1500.00, 0, 37, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:06:30', '2026-09-11 08:06:30', 0, 1500, 100.00, 0, NULL, 13, NULL),
(136, 'BATCH-37-1789131990', 'Cotton Turkish', 1500.00, 0, 37, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:06:30', '2026-09-11 08:06:30', 0, 1500, 100.00, 0, NULL, 13, NULL),
(137, 'BATCH-38-1789132019', 'Cotton Turkish', 1500.00, 0, 38, 13, 4, 15, '05:00:00', '17:00:00', 0, '2026-09-11 08:06:59', '2026-09-11 08:06:59', 0, 1500, 100.00, 0, NULL, 13, NULL),
(138, 'BATCH-38-1789132019', 'Cotton Turkish', 1500.00, 0, 38, 14, 4, 15, '17:00:00', '05:00:00', 0, '2026-09-11 08:06:59', '2026-09-11 08:06:59', 0, 1500, 100.00, 0, NULL, 13, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(2, 'manager', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39'),
(3, 'employee', 'web', '2026-06-20 02:19:39', '2026-06-20 02:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(1, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(11, 2),
(13, 2),
(17, 2),
(18, 2),
(19, 2),
(5, 3),
(9, 3),
(10, 3),
(17, 3),
(18, 3),
(27, 3),
(28, 3),
(29, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('xuzdGIfvLgC3xoUY871pYM47GXAPIcTiy2UmjOh5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmFBZXFqVUlTNjBLd0lvcGJDbE11ME9veDd5SmFVNmg0ek4yZW5OaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1785235326);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone_no`, `cnic`, `address`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Abdullah', '03224307942', '1234567890124', 'kamoki', 'own@gmail.com', '$2y$12$0KbalbFIHxm8vb0QdFzDBONSTl.XBZxKQqxDRdaLsy2rPEeuVIxwW', NULL, NULL, '2026-09-03 22:14:20'),
(2, 'Ali', '03224307942', '1234567890129', 'kamoki', 'ali@gmail.com', '$2y$12$Kg8Ds65AYYT3vxQmOTg3Le6i0iW0NoIopTy5/NEPI40RYx56kFVpW', NULL, '2026-09-03 22:25:07', '2026-09-03 22:25:07'),
(3, 'Marsad', '03224307942', '1234567890123', 'kamoki', 'marsad@gmail.com', '$2y$12$0qeJ83IiWp8mAFLYhnk60OLEZGy03F3UA75FSRYoIJ8XpLmIwo3IW', NULL, '2026-09-03 22:26:26', '2026-09-03 22:26:26'),
(4, 'Zaid', '03224307942', '1234567890127', 'kamoki', 'zaid@gmail.com', '$2y$12$rbtZbqVI1propXavEnARPOJkXNrxFq0mKZ.N9zPoDu.c70eGoialq', NULL, '2026-09-03 22:29:52', '2026-09-03 22:29:52'),
(5, 'Husnain', '03224307942', '1234567890123', 'kamoki', 'husnain@gmail.com', '$2y$12$1mZRf8Z3adpAZjaoirMWSe08AjKB9CqbLgL9Xd20R4A3CVMORpBQS', NULL, '2026-09-03 22:32:52', '2026-09-03 22:32:52'),
(6, 'Hassan', '03224307942', '1234567890126', 'er', 'man@gmail.com', '$2y$12$Q84cffpWZPwEVSdWXI5No.FZiNc7CTTwBGxlhpOuuyc6nq5uo9jgS', NULL, '2026-09-10 22:46:11', '2026-09-10 22:46:11'),
(7, 'Arqam', '03224307942', '1234567890723', '/', 'emp@gmail.com', '$2y$12$eIAq0pL5Y.tHQveZmYTmKu7xJzZT/i961hEZyXJKnTdQu89cBc0uO', NULL, '2026-09-10 22:50:23', '2026-09-10 22:50:23'),
(8, 'Talha', '03224307942', '1234567890173', 'k', 'em@gmail.com', '$2y$12$suxVQfCNXF0MumRIUUXcmu0dYFpu6tWx1LwmSf6GRYFXABmsJcDzW', NULL, '2026-09-10 22:52:42', '2026-09-10 22:52:42'),
(9, 'Musa', '03224307942', '1234567890122', '/', 'musa@gmail.com', '$2y$12$nfvRFKagIMBXG3U8mlzQZ.pMOXWL9MN5DJLJS1nnIK9O9pC6bdiV.', NULL, '2026-09-10 22:55:11', '2026-09-10 22:55:11'),
(10, 'Khizar', '03224307942', '1234567880123', 't', 'Khizar@gmail.com', '$2y$12$gCiY9OIhQzdoNJa/iPzdQewBEFY/LAiw0T8YfwqCPKXQSS2.mveTe', NULL, '2026-09-10 22:56:09', '2026-09-10 22:56:09'),
(11, 'Ahmad', '03224307942', '0322430794223', 'g', 'AHM@gmail.com', '$2y$12$rP0GiRUeul9Wwf78dHhyGOil6b5KVWhUvduqmW.9K4JWYjhp/sJpW', NULL, '2026-09-11 01:07:18', '2026-09-11 01:07:18'),
(12, 'Umar', '34565432786', '5653456789765', 'hj', 'umar@gmail.com', '$2y$12$j5wg91/bVzT2VdjqRizA2OhFzUNx77hEBN/w.QaA5zsx.mCCbFJYS', NULL, '2026-09-11 02:10:57', '2026-09-11 02:10:57'),
(13, 'Sudais', '12345678909', '1234567890987', 'hg', 'sud@gmail.com', '$2y$12$8J4TBNzEJiZr0JJBpl0kwuJ1dyl7MHIzWFTLcnb32ge9TcuvhbZtW', NULL, '2026-09-11 02:12:47', '2026-09-11 02:12:47'),
(14, 'Ammar', '12345678909', '0987654321234', 'hg', 'amr@gmail.com', '$2y$12$CXl/Ih4O3sMorunNHWPLsOj4ddGllzmMxYu2RN6zhuTeoMmEI9SSK', NULL, '2026-09-11 02:14:22', '2026-09-11 02:14:22'),
(15, 'Suraka', '12345678901', '2350987654323', 'gh', 'sur@gmail.com', '$2y$12$JbrUHEpgxU/ywAYTITqSGefFzTMiP1KAw3yUgihr4hDuFi/RcwaVe', NULL, '2026-09-11 07:14:36', '2026-09-11 07:14:36'),
(16, 'Kuzaima', '99876543212', '7987654321234', 'hj', 'kuz@gmail.com', '$2y$12$eIxMkV8mYkpaVfp1Fku8Ge9ooMEps/tun0d2AJgTMYRaIUrcy6jay', NULL, '2026-09-11 07:16:09', '2026-09-11 07:16:09'),
(17, 'Akram', '89076545678', '9875674325678', 'gh', 'akr@gmail.com', '$2y$12$O/2ktTL.N9szQXCbIwo5qO2Lf3L.CRuN46lQY6lbjjXflwltJDUcG', NULL, '2026-09-11 07:19:50', '2026-09-11 07:19:50'),
(18, 'Ameen', '23457865432', '2345678765432', 'fg', 'ameen@gmail.com', '$2y$12$vUSJjaCExSVnLh7sx4HR5e4MMxxvUwYHwTA.3TgwYME./aPDW6Xv.', NULL, '2026-09-11 07:21:02', '2026-09-11 07:21:02'),
(19, 'Munawar', '54321456787', '2343256765432', 'fg', 'munawar@gmail.com', '$2y$12$Q3OEv/QnLizSKVdHLhSQROVRmpHc/GM42l0s/uF/Iti6s2.dhdY6i', NULL, '2026-09-11 07:21:52', '2026-09-11 07:21:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendences`
--
ALTER TABLE `attendences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendences_machine` (`machine_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factories`
--
ALTER TABLE `factories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `production_id` (`production_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_employee_id_foreign` (`employee_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_production_id_foreign` (`production_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_batch_id_index` (`batch_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendences`
--
ALTER TABLE `attendences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `factories`
--
ALTER TABLE `factories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendences`
--
ALTER TABLE `attendences`
  ADD CONSTRAINT `fk_attendences_machine` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
