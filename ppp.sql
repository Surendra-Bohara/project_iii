-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 02:43 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppp`
--

-- --------------------------------------------------------

--
-- Table structure for table `abandoned_carts`
--

CREATE TABLE `abandoned_carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `cart_data` json DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `abandoned_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `recovered` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Suren', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `categories` varchar(255) NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `categories`, `status`) VALUES
(2, 'Mobile', 1),
(5, 'Latob', 1),
(8, 'Watch', 1),
(9, 'cat4', 0),
(10, 'CAT5', 0),
(11, 'CAT10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `comment`, `added_on`) VALUES
(2, 'ss', 'sbohara241@gmail.com', '9848969678', 'try', '2025-11-19 08:44:20'),
(6, 'ss', 'sbohara241@gmail.com', '9848969678', 'try', '2025-11-19 08:56:18');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` int NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `total_price` float NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `order_status` int NOT NULL,
  `txnid` varchar(20) NOT NULL DEFAULT '',
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `address`, `city`, `pincode`, `payment_type`, `total_price`, `payment_status`, `order_status`, `txnid`, `added_on`) VALUES
(9, 4, 'ktm', 'ktm', 223, 'COD', 720000, 'pending', 3, '', '2025-11-26 03:54:16'),
(10, 4, 'ktm', 'ktm', 225, 'payu', 720000, 'pending', 1, '2ed284a79eb568c2a37b', '2025-11-26 10:45:29'),
(11, 4, 'city', 'state', 1212, 'COD', 540000, 'pending', 1, '', '2025-11-27 03:55:09'),
(12, 4, 'ktm', 'ktm', 12, 'COD', 9000, 'pending', 1, '', '2025-11-28 03:13:11'),
(13, 4, 'ktm', 'ktm', 3, 'COD', 150000, 'pending', 1, '', '2025-11-28 07:08:32'),
(14, 4, 'ktm', 'ktm', 12, 'COD', 297000, 'pending', 5, '', '2025-11-30 06:09:21'),
(15, 4, 'aaa', 'bbb', 111, 'COD', 396000, 'pending', 1, '', '2025-11-30 08:29:51'),
(16, 4, 'ababa', 'baba', 123, 'COD', 90000, 'pending', 5, '', '2025-11-30 08:38:20'),
(17, 5, 'add', 'add', 12, 'COD', 270000, 'pending', 5, '', '2025-11-30 10:29:13'),
(18, 4, 'ww', 'ww', 12, 'COD', 0, 'pending', 1, '', '2025-11-30 10:33:14'),
(19, 4, 'ww', 'ww', 12, 'COD', 320000, 'pending', 5, '', '2025-11-30 10:34:03'),
(20, 4, 'bbd', 'bbd', 1212, 'COD', 211998, 'pending', 5, '', '2025-11-30 13:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(9, 9, 6, 4, 180000),
(10, 10, 6, 4, 180000),
(11, 11, 6, 3, 180000),
(12, 12, 11, 3, 3000),
(13, 13, 9, 3, 50000),
(14, 14, 8, 3, 99000),
(15, 15, 8, 4, 99000),
(16, 16, 10, 3, 30000),
(17, 17, 7, 3, 80000),
(18, 17, 11, 1, 30000),
(19, 19, 7, 4, 80000),
(20, 20, 10, 5, 30000),
(21, 20, 12, 2, 30999);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Processing'),
(1, 'Pending'),
(2, 'Processing'),
(3, 'Shipped'),
(4, 'Canceled'),
(5, 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `categories_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `mrp` float NOT NULL,
  `price` float NOT NULL,
  `qty` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `short_desc` varchar(2000) NOT NULL,
  `description` text NOT NULL,
  `best_seller` int NOT NULL,
  `meta_title` text NOT NULL,
  `meta_desc` varchar(2000) NOT NULL,
  `meta_keyword` varchar(2000) NOT NULL,
  `status` tinyint NOT NULL,
  `rating` float DEFAULT '0',
  `inventory_level` int DEFAULT '0',
  `reorder_point` int DEFAULT '10',
  `view_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `categories_id`, `name`, `mrp`, `price`, `qty`, `image`, `short_desc`, `description`, `best_seller`, `meta_title`, `meta_desc`, `meta_keyword`, `status`, `rating`, `inventory_level`, `reorder_point`, `view_count`) VALUES
(6, 2, 'iphone 17', 20000, 180000, 56, '2107665897_iphone.jpg', 'kjhsd', 'akjhga', 0, 'gkjah', 'agd', 'fhhfg', 1, 4.5, 0, 10, 5),
(7, 5, 'name', 100000, 80000, 20, '7469182274_Dell laptop.jpg', 'hfakh', 'note', 0, 'ajk', 'aga', 'lkaga', 1, 0, 0, 10, 3),
(8, 2, 'Samsung S24 Ultra', 100000, 99000, 56, '2571913895_samsung.jpg', 'Samsung Galaxy S24: Compact flagship with 6.2″ AMOLED display, Snapdragon 8 Gen 3, triple camera, 4000 mAh battery, and Android 14.', 'Samsung Galaxy S24 is a compact flagship with a 6.2″ Dynamic AMOLED 2X screen, 120 Hz refresh rate, and a powerful Snapdragon 8 Gen 3 / Exynos 2400 chipset. It packs a 50 MP + 12 MP + 10 MP triple rear camera, 4000 mAh battery with fast charging, and runs Android 14 with One UI 6.1.', 1, 'samsung', 'samsung', 'sam', 1, 2, 0, 10, 7),
(9, 2, 'Samsung Galaxy a71', 45000, 40000, 60, '3682417143_samsung-galaxy-a71-pink.jpg', 'Samsung Galaxy A71 features a 6.7-inch AMOLED display, quad-camera setup, Snapdragon 730, 4500mAh battery, sleek design, and fast charging.', 'The Samsung Galaxy A71 offers a 6.7-inch Super AMOLED display with vibrant colors, Snapdragon 730 processor, 6–8GB RAM, and 128GB storage. It has a versatile quad-camera system, 4500mAh battery with fast charging, and a slim, modern design, making it ideal for multimedia, gaming, and everyday smartphone use.', 0, 'Samsung', 'Galaxy', 'phone', 1, 4.5, 0, 10, 44),
(10, 2, 'Redmi NOTE 14 5G', 31000, 30000, 20, '9906017630_Redmi.jpg', 'Redmi Note 14 5G — 6.67″ AMOLED, 108 MP rear camera, MediaTek Dimensity 7025, 5110 mAh battery, 120 Hz smooth display, 45 W fast charging.', 'The Redmi Note 14 5G packs a bright 6.67″ AMOLED display (120 Hz, 2100 nits peak), a powerful MediaTek Dimensity 7025 Ultra processor, and up to 12 GB RAM with up to 512 GB storage. Its triple rear camera includes a 108 MP main sensor, paired with a large 5110 mAh battery and 45 W fast charging — a great mid‑range phone for everyday use.', 0, 'MEta', 'descr', 'redmi', 1, 3, 0, 10, 44),
(11, 8, 'Smart Watch 7', 35000, 30000, 20, '2934836769_watch.png', 'Watch 7 Black — sleek AMOLED smartwatch, 32 GB storage, 2 GB RAM, heart-rate & ECG sensors, GPS, 5 ATM water-resistant.', 'Watch 7 Black offers a sharp Super AMOLED display (432×432 px or 480×480 px depending on size), a powerful Exynos W1000 chipset, 2 GB RAM and 32 GB internal storage. It tracks heart rate, ECG, sleep and fitness, provides GPS, Bluetooth, NFC and 5 ATM water resistance — combining smart-watching, fitness tracking and everyday convenience.', 1, 'Black watch', 'Black watch', 'watch', 1, 3.5, 0, 10, 32),
(12, 8, 'Apple Watch', 39999, 30999, 42, '9635307900_apple watch.jpg', 'apple watch', 'The Apple Watch SE is an ideal buy for iPhone owners on a tight budget. Despite being an affordable alternative, it comes with the fairly premium build quality.', 1, 'watch', 'watch', 'watch', 1, 4.5, 0, 10, 13);

-- --------------------------------------------------------

--
-- Table structure for table `product_bundles`
--

CREATE TABLE `product_bundles` (
  `id` int NOT NULL,
  `bundle_name` varchar(255) NOT NULL,
  `product_1` int NOT NULL,
  `product_2` int NOT NULL,
  `bundle_discount` decimal(5,2) DEFAULT '0.00',
  `status` int DEFAULT '1',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` text,
  `added_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_ratings`
--

INSERT INTO `product_ratings` (`id`, `product_id`, `user_id`, `rating`, `review`, `added_on`) VALUES
(1, 10, 5, 3, NULL, '2025-11-30 12:54:54'),
(3, 10, 4, 4, NULL, '2025-11-30 12:57:11'),
(4, 12, 4, 4, NULL, '2025-11-30 13:45:59'),
(5, 11, 4, 4, NULL, '2025-11-30 13:46:10'),
(6, 11, 5, 3, NULL, '2025-11-30 13:54:52'),
(7, 12, 5, 5, NULL, '2025-11-30 13:55:17'),
(9, 9, 5, 5, NULL, '2025-11-30 14:34:24'),
(12, 9, 4, 4, NULL, '2025-11-30 15:35:50'),
(17, 8, 4, 2, NULL, '2025-11-30 16:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `recommendation_log`
--

CREATE TABLE `recommendation_log` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int NOT NULL,
  `recommendation_type` varchar(50) DEFAULT NULL,
  `clicked` int DEFAULT '0',
  `purchased` int DEFAULT '0',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `added_on` datetime(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `mobile`, `added_on`) VALUES
(4, 'Surendra Bohara', '$2y$10$WQw6doM4rqCs0VdBx3wxTuyi3AqXLLukxygNQced7IwfYyHzyo7Hq', 'sbohara241@gmail.com', '9848969678', '2025-11-20 08:55:47.00000'),
(5, 'Suren', '$2y$10$RRh4prr90r7rQqjXDIw4U.N5V/jHNF/mN2J1M4MxSJrFPTntT6EwG', 'Bohara', '9854789632', '2025-11-27 09:31:42.00000');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_on`) VALUES
(10, 5, 8, '2025-11-27 09:59:39'),
(12, 5, 10, '2025-11-27 10:08:04'),
(13, 5, 9, '2025-11-27 10:08:23'),
(16, 4, 7, '2025-11-28 03:52:50'),
(17, 4, 12, '2025-11-30 09:08:24'),
(18, 4, 11, '2025-11-30 09:08:27'),
(19, 5, 11, '2025-11-30 09:09:38'),
(20, 5, 12, '2025-11-30 09:09:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abandoned_carts`
--
ALTER TABLE `abandoned_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_bundles`
--
ALTER TABLE `product_bundles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_1` (`product_1`),
  ADD KEY `product_2` (`product_2`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`product_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recommendation_log`
--
ALTER TABLE `recommendation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abandoned_carts`
--
ALTER TABLE `abandoned_carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_bundles`
--
ALTER TABLE `product_bundles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `recommendation_log`
--
ALTER TABLE `recommendation_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `abandoned_carts`
--
ALTER TABLE `abandoned_carts`
  ADD CONSTRAINT `abandoned_carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_bundles`
--
ALTER TABLE `product_bundles`
  ADD CONSTRAINT `product_bundles_ibfk_1` FOREIGN KEY (`product_1`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `product_bundles_ibfk_2` FOREIGN KEY (`product_2`) REFERENCES `product` (`id`);

--
-- Constraints for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `recommendation_log`
--
ALTER TABLE `recommendation_log`
  ADD CONSTRAINT `recommendation_log_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
