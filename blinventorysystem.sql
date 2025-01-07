-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2025 at 05:43 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blinventorysystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(39, '0001_01_01_000000_create_users_table', 1),
(40, '0001_01_01_000001_create_cache_table', 1),
(41, '0001_01_01_000002_create_jobs_table', 1),
(42, '2024_12_20_034248_create_stocks_level_table', 1),
(43, '2024_12_21_034249_create_stocks_tbl', 1),
(44, '2024_12_22_024457_create_stocks_materials_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0pjM1expdepGLC9gQtjb7lRClGIC2gjPmznYoU2M', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUpvbmN1NWNSanA1NEdOUXhmc3JhUDJ5aHAzSFBISFRVZkxjcGJNSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9ibGludmVudG9yeXN5c3RlbS50ZXN0L3N0b2NrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1735343976),
('2XLDJBSp9yXLh7FJZtpZzX7cgyvJ2Hmms85RgryF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNm9DTjdBZHhST2dEV2JwMzRvTzZpQWloUHBPMTRVOEdrcG9tbnBVRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9ibGludmVudG9yeXN5c3RlbS50ZXN0L3N0b2NrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1735263382),
('NwKZ20fEmkbTeEQXLhvXHCHyQgt9E78AwEaCHhqv', NULL, NULL, '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVMwTFVvYnBFMEVtMWF5UnJkdktNbkdnb0hLWHdMeFFCSkMzRXk4VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1735795517);

-- --------------------------------------------------------

--
-- Table structure for table `stocks_level`
--

CREATE TABLE `stocks_level` (
  `id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `stocks_level_status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks_level`
--

INSERT INTO `stocks_level` (`id`, `description`, `stocks_level_status`, `created_at`, `updated_at`) VALUES
(1, 'MODEM', 0, '2024-12-20 23:53:12', '2024-12-20 23:53:12'),
(2, 'MODEM', 4, '2024-12-20 23:53:12', '2024-12-20 23:53:12'),
(3, 'MODEM', 4, '2024-12-20 23:53:12', '2024-12-20 23:53:12'),
(4, 'CONNECTOR', 2, '2024-12-20 23:53:31', '2024-12-20 23:53:31'),
(5, 'CONNECTOR', 2, '2024-12-20 23:53:31', '2024-12-20 23:53:31'),
(6, 'CONNECTOR', 4, '2024-12-20 23:53:31', '2024-12-20 23:53:31'),
(7, 'CONNECTOR', 4, '2024-12-20 23:53:31', '2024-12-20 23:53:31'),
(8, 'CONNECTOR', 0, '2024-12-20 23:53:31', '2024-12-20 23:53:31'),
(9, 'CONNECTOR', 0, '2024-12-20 23:53:31', '2024-12-20 23:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `stocks_materials`
--

CREATE TABLE `stocks_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `description_id` bigint UNSIGNED DEFAULT NULL,
  `stocks_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks_materials`
--

INSERT INTO `stocks_materials` (`id`, `description_id`, `stocks_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(46, 1, 5, NULL, NULL, NULL),
(47, 8, 5, NULL, NULL, NULL),
(48, 9, 5, NULL, NULL, NULL),
(51, 4, 6, NULL, NULL, NULL),
(52, 5, 6, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stocks_tbl`
--

CREATE TABLE `stocks_tbl` (
  `id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_id` bigint UNSIGNED DEFAULT NULL,
  `team_tech` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subsname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subsaccount_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `j_o_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sar_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_new_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_active` date DEFAULT NULL,
  `date_released` date DEFAULT NULL,
  `date_used` date DEFAULT NULL,
  `date_repaired` date DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks_tbl`
--

INSERT INTO `stocks_tbl` (`id`, `product_name`, `description_id`, `team_tech`, `subsname`, `subsaccount_no`, `account_no`, `j_o_no`, `sar_no`, `serial_no`, `serial_new_no`, `ticket_no`, `date_active`, `date_released`, `date_used`, `date_repaired`, `status`, `created_at`, `updated_at`) VALUES
(5, 'ZTE', NULL, 'Roms', 'Farhan', 'FARHAN2024', NULL, '1', 'S1', '456456', NULL, NULL, '2024-12-26', '2024-12-27', '2024-12-30', NULL, 0, '2024-12-25 23:06:57', '2024-12-25 23:18:51'),
(6, 'ZTE', NULL, 'Vale', 'Gareth', 'GARETH 2024', '345435', '2', 'S2', '123123', NULL, NULL, '2024-12-27', '2024-12-28', '2024-12-31', NULL, 2, '2024-12-25 23:17:48', '2024-12-25 23:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stocks_level`
--
ALTER TABLE `stocks_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks_materials`
--
ALTER TABLE `stocks_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_materials_description_id_foreign` (`description_id`),
  ADD KEY `stocks_materials_stocks_id_foreign` (`stocks_id`);

--
-- Indexes for table `stocks_tbl`
--
ALTER TABLE `stocks_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_tbl_description_id_foreign` (`description_id`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `stocks_level`
--
ALTER TABLE `stocks_level`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stocks_materials`
--
ALTER TABLE `stocks_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `stocks_tbl`
--
ALTER TABLE `stocks_tbl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stocks_materials`
--
ALTER TABLE `stocks_materials`
  ADD CONSTRAINT `stocks_materials_description_id_foreign` FOREIGN KEY (`description_id`) REFERENCES `stocks_level` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_materials_stocks_id_foreign` FOREIGN KEY (`stocks_id`) REFERENCES `stocks_tbl` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks_tbl`
--
ALTER TABLE `stocks_tbl`
  ADD CONSTRAINT `stocks_tbl_description_id_foreign` FOREIGN KEY (`description_id`) REFERENCES `stocks_level` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
