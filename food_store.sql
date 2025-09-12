-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 08, 2025 at 08:39 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Món chính', 'mn-chnh', 'Các món ăn chính trong bữa ăn', 'categories/gvjViKXhCct0y8VmbQ4igJCg2sW7HWesfiYslkOC.png', 1, '2025-08-03 09:30:39', '2025-08-04 08:24:52'),
(2, 'Món khai vị', 'mn-khai-v', 'Các món ăn nhẹ trước bữa ăn chính', 'categories/CBGDdl9ckpYH1YTpiQ2lgFU277F8OHVnbaBqLIAq.png', 1, '2025-08-03 09:33:07', '2025-08-04 08:24:52'),
(3, 'Món tráng miệng', 'mn-trng-ming', 'Các món ngọt sau bữa ăn', 'categories/UUZGaZrCaS5baX67djyRVixDXkVN2KPF6ZFOJ5ye.png', 1, '2025-08-03 09:34:13', '2025-08-04 08:24:52'),
(4, 'Đồ uống', 'ung', 'Các loại nước uống', 'categories/fntCKycUqGJPar3n2OY2SAU98nL1BmzIatjotsLi.png', 1, '2025-08-03 09:35:40', '2025-08-04 08:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_histories`
--

CREATE TABLE `order_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `product_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `image`, `category_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cơm rang dương châu', 'cm-rang-dng-chu', 'Cơm rang dương châu với nhiều loại thịt và rau củ', 50000.00, 'products/7fe0XbswSwtw1u1MNkQYwoCU9dr0OUdBK4VIGTAj.png', 1, 1, '2025-08-03 09:58:59', '2025-08-03 09:58:59'),
(2, 'Cơm gà xối mỡ', 'cm-g-xi-m', 'Cơm chiên vàng giòn, ăn kèm đùi gà chiên giòn rụm, rưới nước mỡ gà thơm béo.', 45000.00, 'products/OViCsvVNSzVyMO2tKL43HEbEV1IwlyZQBuIdQrZu.png', 1, 1, '2025-08-03 10:01:25', '2025-08-03 10:01:25'),
(3, 'Bún chả Hà Nội', 'bn-ch-h-ni', 'Bún tươi ăn cùng chả nướng thơm lừng, nước mắm pha chua ngọt kèm rau sống.', 35000.00, 'products/jJZaLQjBLXmJZQiNNzIKEGjXAKFWzQOmA71pGNAM.png', 1, 1, '2025-08-03 10:02:27', '2025-08-03 10:02:27'),
(4, 'Mì xào hải sản', 'm-xo-hi-sn', 'Mì trứng xào dai giòn với tôm, mực, rau củ, sốt đậm đà hấp dẫn.', 55000.00, 'products/AdLq5fKwVcVqPhi7u80ACeyMr7OCEakYOqbnUVqp.png', 1, 1, '2025-08-03 10:03:04', '2025-08-03 10:03:04'),
(5, 'Gỏi cuốn tôm thịt', 'gi-cun-tm-tht', 'Bánh tráng cuốn tôm, thịt, bún, rau sống, chấm mắm nêm đậm đà.', 35000.00, 'products/ZDhrXITRLBjhYjjM6BTfKN0xOTfnnuBiqqY4aAQI.png', 2, 1, '2025-08-03 10:03:40', '2025-08-04 08:27:01'),
(6, 'Chả giò rế', 'ch-gi-r', 'Chả giò vỏ rế giòn tan, nhân thịt băm, miến, nấm mèo, ăn kèm rau và nước mắm chua ngọt.', 40000.00, 'products/exp5xxH9zVms58hXAECe0jAFmAzAtZcrMZqBoYXf.png', 2, 1, '2025-08-03 10:04:22', '2025-08-04 08:27:01'),
(7, 'Súp cua trứng bắc thảo', 'sp-cua-trng-bc-tho', 'Súp sánh mịn, kết hợp cua xé, trứng bắc thảo và ngò thơm.', 50000.00, 'products/GYrXfAQbrYSsKwrOBs9FHrrTLpdJJpsjC9JNvkHf.png', 2, 1, '2025-08-03 10:04:57', '2025-08-04 08:27:01'),
(8, 'Khoai tây chiên phô mai', 'khoai-ty-chin-ph-mai', 'Khoai tây giòn tan phủ lớp phô mai béo ngậy, thích hợp làm món ăn nhẹ.', 25000.00, 'products/HwiThuPSmSTOYILaI5osMevg3imIjQr746YqkQFQ.png', 2, 1, '2025-08-03 10:05:57', '2025-08-04 08:27:01'),
(9, 'Chè khúc bạch', 'ch-khc-bch', 'Chè lạnh với khúc bạch mềm mịn, hạnh nhân rang và trái cây tươi.', 25000.00, 'products/t4uzRCArhwV8ZBfrwgfRaxnoPoReGvWMrBYHmjHC.png', 3, 1, '2025-08-03 10:06:36', '2025-08-03 10:06:36'),
(10, 'Bánh flan caramel', 'bnh-flan-caramel', 'Bánh flan mềm mịn, phủ lớp caramel đắng nhẹ, tan trong miệng.', 25000.00, 'products/vvAQvagoIh0rpuSQ5cTQpWpeYSAqzzgiNmsDWUNC.png', 3, 1, '2025-08-03 10:07:17', '2025-08-03 10:07:17'),
(11, 'Sữa chua nếp cẩm', 'sa-chua-np-cm', 'Sữa chua dẻo kết hợp nếp cẩm dẻo thơm, vị chua ngọt hài hòa.', 15000.00, 'products/IzGHoNHyESCJqXbm81jBnHYjQxQYJb4naevyHTv4.png', 3, 1, '2025-08-03 10:09:24', '2025-08-03 10:09:24'),
(12, 'Kem dừa Thái', 'kem-da-thi', 'Kem dừa mát lạnh, ăn kèm topping thạch dừa, đậu phộng và dừa sợi.', 30000.00, 'products/6f5yH1V8GYa8iP5UBTcg41IF3QuHXZOthLRVrbGw.png', 3, 1, '2025-08-03 10:10:07', '2025-08-03 10:10:07'),
(13, 'Trà đào cam sả', 'tr-o-cam-s', 'Trà đen thơm mùi đào, kết hợp cam tươi và sả, vị chua ngọt thanh mát.', 30000.00, 'products/KPa9QzD6QU9JpPdbcH6WCPIene94g4UOlZnVTUS6.png', 4, 1, '2025-08-03 10:10:57', '2025-08-03 10:10:57'),
(14, 'Sữa tươi trân châu đường đen', 'sa-ti-trn-chu-ng-en', 'Sữa tươi béo nhẹ kết hợp trân châu dai và sốt đường đen thơm lừng.', 30000.00, 'products/P0eHAPqyvVJm5vv0GhqDiL9kh5WFW2jAD8i4hhz1.png', 4, 1, '2025-08-03 10:11:43', '2025-08-03 10:11:43'),
(15, 'Nước ép dưa hấu bạc hà', 'nc-p-da-hu-bc-h', 'Dưa hấu tươi ép lạnh, thêm chút bạc hà tạo cảm giác mát lạnh sảng khoái.', 25000.00, 'products/jBKylyh0LrpIJnNrnqOgjFa85JZilNRMUbVOa2uN.png', 4, 1, '2025-08-03 10:12:18', '2025-08-03 10:12:18'),
(16, 'Sinh tố bơ sữa', 'sinh-t-b-sa', 'Bơ sáp xay cùng sữa đặc và đá, tạo thành ly sinh tố béo ngậy, bổ dưỡng.', 25000.00, 'products/g89c3IbTCBLGTJNIKa8HZ4Rve6d6jHA6fPwZ4a8l.png', 4, 1, '2025-08-03 10:13:01', '2025-08-03 10:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '0123456789', 'Đống Đa, Hà Nội', 'admin', '$2y$12$N4h8/LOS6ZwoObSzAbgUUuqzngloQAB4oOHptE3gDgcqQw5hXELyK', '2025-08-03 09:19:23', '2025-08-03 10:21:39'),
(2, 'Nguyễn Văn A', 'user1@gmail.com', '0123456789', 'Nam Từ Liêm, Hà Nội', 'user', '$2y$12$gOe0bx7ODwPE7PONDJ/tg.Nzz2J1KRBWP6ZNjNsAVt6bDK0/v1QiO', '2025-08-03 09:20:30', '2025-08-04 08:19:19'),
(3, 'Nguyễn Văn B', 'user2@gmail.com', '0123456789', 'Thanh Xuân, Hà Nội', 'user', '$2y$12$0Jc8mv7L69cfTmctvQrhK.HMuA99dMYaUjX6iwbZrvAuxGiuWiudK', '2025-08-03 09:20:53', '2025-08-03 10:21:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_histories_order_id_foreign` (`order_id`),
  ADD KEY `order_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_order_item_id_foreign` (`order_item_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
