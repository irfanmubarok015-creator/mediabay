-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 04:38 PM
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
-- Database: `mediabay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `event_location` text DEFAULT NULL,
  `event_name` varchar(200) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `dp_amount` decimal(12,2) NOT NULL,
  `status` enum('REQUESTED','WAITING_VERIFICATION','APPROVED','REJECTED','EXPIRED') DEFAULT 'REQUESTED',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carousels`
--

CREATE TABLE `carousels` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `media_type` enum('image','video') DEFAULT 'image',
  `media_file` varchar(255) NOT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `carousels`
--

INSERT INTO `carousels` (`id`, `title`, `subtitle`, `media_type`, `media_file`, `cta_text`, `cta_link`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Abadikan Momen Terbaik Anda', 'Layanan fotografer & videografer profesional untuk wedding, event, dan corporate', 'image', 'placeholder.jpg', 'Booking Sekarang', '/booking', 1, 1, '2026-04-05 01:19:03'),
(2, 'Sinematografi Berkualitas', 'Film pernikahan cinematic yang akan Anda kenang seumur hidup', 'image', 'placeholder.jpg', 'Lihat Layanan', '/layanan', 2, 1, '2026-04-05 01:19:03'),
(3, 'Live Streaming Profesional', 'Siarkan momen spesial ke seluruh dunia secara langsung', 'image', 'placeholder.jpg', 'Konsultasi Gratis', '/contact', 3, 1, '2026-04-05 01:19:03'),
(4, 'HIMAFOR kini hadir menjadi parter kami', 'Saat ini Himpunan Mahasiswa Informatika Universitas Islam Negeri Siber Syekh Nurjati Cirebon hadir menjadi partership kami', 'video', '69d1d84705f3d_1775360071.mp4', '', '', 4, 1, '2026-04-05 03:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `image`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Photography', 'photography', 'camera', 'Abadikan setiap momen berharga dengan foto profesional berkualitas tinggi', NULL, 1, 1, '2026-04-05 01:19:03'),
(2, 'Videography', 'videography', 'film', 'Ceritakan kisah Anda melalui sinematografi profesional yang memukau', NULL, 2, 1, '2026-04-05 01:19:03'),
(3, 'Live Streaming', 'live-streaming', 'broadcast-tower', 'Siarkan momen spesial Anda secara langsung ke seluruh dunia', NULL, 3, 1, '2026-04-05 01:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` text DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` enum('news','portfolio','blog') DEFAULT 'portfolio',
  `is_published` tinyint(1) DEFAULT 1,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `content`, `excerpt`, `image`, `category`, `is_published`, `published_at`, `created_at`) VALUES
(1, 'Tips Memilih Fotografer Wedding Terbaik', 'tips-fotografer-wedding-terbaik', 'Memilih fotografer wedding yang tepat adalah salah satu keputusan terpenting dalam persiapan pernikahan Anda. Berikut beberapa tips yang dapat membantu Anda.', 'Panduan lengkap cara memilih fotografer wedding yang tepat untuk hari spesial Anda', NULL, 'blog', 1, '2026-04-05 01:19:03', '2026-04-05 01:19:03'),
(2, 'Wedding Ayu &amp; Budi - Cinematic Story', 'wedding-ayu-budi-cinematic', 'Momen indah pernikahan Ayu & Budi yang penuh keharuan. Tim Mediabay hadir mengabadikan setiap detail dari pernikahan sakral ini.', 'Momen indah pernikahan Ayu &amp; Budi yang penuh keharuan dan kebahagiaan', '69d1d7c71f5dc_1775359943.jpg', 'portfolio', 1, '2026-04-05 01:19:03', '2026-04-05 01:19:03'),
(3, 'Event Corporate PT Maju Jaya 2024', 'event-corporate-maju-jaya-2024', 'Liputan profesional annual meeting PT Maju Jaya dengan 500 peserta. Kami menyediakan layanan foto, video, dan live streaming untuk event berskala besar ini.', 'Liputan profesional annual meeting PT Maju Jaya dengan 500 peserta', NULL, 'portfolio', 1, '2026-04-05 01:19:03', '2026-04-05 01:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `dp_percentage` int(11) DEFAULT 50,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `duration_hours` int(11) DEFAULT 8,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `service_id`, `name`, `price`, `dp_percentage`, `description`, `features`, `duration_hours`, `is_active`, `created_at`) VALUES
(1, 1, 'Silver Package', 2500000.00, 50, 'Paket foto pernikahan dasar', '[\"1 fotografer\",\"8 jam liputan\",\"200 foto edit\",\"Album digital\",\"Hard copy 4R 50 lembar\"]', 8, 1, '2026-04-05 01:19:03'),
(2, 1, 'Gold Package', 4500000.00, 50, 'Paket foto pernikahan lengkap', '[\"2 fotografer\",\"10 jam liputan\",\"400 foto edit\",\"Album premium\",\"Hard copy 4R 100 lembar\",\"Foto kanvas 60x90cm\"]', 10, 1, '2026-04-05 01:19:03'),
(3, 1, 'Platinum Package', 7500000.00, 30, 'Paket foto pernikahan eksklusif', '[\"3 fotografer\",\"12 jam liputan\",\"600 foto edit\",\"Album mewah custom\",\"Hard copy 4R 200 lembar\",\"2 foto kanvas\",\"Drone shot\"]', 12, 1, '2026-04-05 01:19:03'),
(4, 4, 'Silver Video', 3500000.00, 50, 'Paket video pernikahan standar', '[\"1 videografer\",\"8 jam\",\"Edit 10-15 menit\",\"Format HD 1080p\",\"1 revisi\"]', 8, 1, '2026-04-05 01:19:03'),
(5, 4, 'Gold Video', 6000000.00, 50, 'Paket video pernikahan premium', '[\"2 videografer\",\"10 jam\",\"Edit 20-30 menit\",\"Format 4K\",\"3 revisi\",\"Drone shot\",\"Color grading\"]', 10, 1, '2026-04-05 01:19:03'),
(6, 6, 'Basic Stream', 2000000.00, 50, 'Live streaming pernikahan dasar', '[\"1 kamera\",\"Streaming YouTube/IG\",\"8 jam\",\"Bandwidth dedicated\",\"Operator profesional\"]', 8, 1, '2026-04-05 01:19:03'),
(7, 6, 'Premium Stream', 4000000.00, 50, 'Live streaming premium', '[\"3 kamera multi-angle\",\"Streaming YouTube/IG/Zoom\",\"12 jam\",\"Bandwidth dedicated\",\"2 operator\",\"Grafis overlay\"]', 12, 1, '2026-04-05 01:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_type` enum('dp','full','remaining') DEFAULT 'dp',
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('PENDING','VERIFIED','REJECTED') DEFAULT 'PENDING',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 1, 'Wedding Photography', 'wedding-photography', 'Dokumentasi pernikahan lengkap dengan sentuhan artistik', NULL, 1, '2026-04-05 01:19:03'),
(2, 1, 'Event Photography', 'event-photography', 'Liputan foto profesional untuk semua jenis event', NULL, 1, '2026-04-05 01:19:03'),
(3, 1, 'Corporate Photography', 'corporate-photography', 'Foto korporat dan produk berkualitas tinggi', NULL, 1, '2026-04-05 01:19:03'),
(4, 2, 'Wedding Videography', 'wedding-videography', 'Film pernikahan cinematic dengan editing profesional', NULL, 1, '2026-04-05 01:19:03'),
(5, 2, 'Event Videography', 'event-videography', 'Dokumentasi video event yang memukau', NULL, 1, '2026-04-05 01:19:03'),
(6, 3, 'Wedding Live Stream', 'wedding-live-stream', 'Streaming pernikahan langsung ke seluruh dunia', NULL, 1, '2026-04-05 01:19:03'),
(7, 3, 'Event Live Stream', 'event-live-stream', 'Siaran langsung event profesional multi-kamera', NULL, 1, '2026-04-05 01:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Admin Mediabay', 'admin@mediabay.id', '6281234567890', '$2y$10$FjvgEwiX58CWNFydrjxIveeZEWe671EuWut1IghzfbEIz1lt7rJ56', 'admin', NULL, '2026-04-05 01:19:03', '2026-04-05 03:28:24'),
(2, 'Admin Mediabay', 'abay@mediabay.id', '6283804339441', '$2y$10$/SsDKnLI4TgdGUy6/Yy7j.CRE1xGWLZutHh4VTm903XqLc7WAku8C', 'admin', NULL, '2026-04-05 03:28:54', '2026-04-05 03:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `wa_logs`
--

CREATE TABLE `wa_logs` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` enum('sent','failed') DEFAULT 'sent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wa_logs`
--
ALTER TABLE `wa_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wa_logs`
--
ALTER TABLE `wa_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
