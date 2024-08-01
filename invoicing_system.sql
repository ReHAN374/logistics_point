-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3377
-- Generation Time: Jul 28, 2024 at 10:51 AM
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
-- Database: `invoicing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_user` int(11) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `vat_no` varchar(255) DEFAULT NULL,
  `vat_amount` double NOT NULL DEFAULT 0,
  `grand_total` double NOT NULL DEFAULT 0,
  `printed_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `sales_user`, `invoice_no`, `invoice_date`, `customer_name`, `customer_address`, `vat_no`, `vat_amount`, `grand_total`, `printed_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV-1316', '2024-07-23 11:59:57', 'Dana Lee', '321 Birch Rd', 'VAT003210', 62.08, 406.99, '2024-07-23 11:59:57', 1, '2024-07-23 11:59:57', '2024-07-23 11:59:57'),
(2, 2, 'INV-9288', '2024-07-23 18:52:11', 'Michael Brown', '789 Oak St', 'VAT987654', 46.79, 306.74, '2024-07-25 18:44:42', 1, '2024-07-25 18:44:42', '2024-07-25 18:53:13'),
(3, 5, 'INV-1873', '2024-07-26 03:47:35', 'Bob Williams', '456 Oak Blvd', 'VAT005678', 50.38, 330.28, '2024-07-25 18:47:06', 1, '2024-07-25 18:47:06', '2024-07-26 03:47:35'),
(4, 6, 'INV-3073', '2024-07-24 18:52:20', 'Dana Lee', '321 Birch Rd', 'VAT003210', 60.29, 395.22, '2024-07-25 18:48:05', 1, '2024-07-25 18:48:05', '2024-07-25 18:53:30'),
(5, 10, 'INV-2748', '2024-07-25 18:52:24', 'Michael Brown', '789 Oak St', 'VAT987654', 86.39, 566.31, '2024-07-25 18:48:42', 1, '2024-07-25 18:48:42', '2024-07-25 18:52:24'),
(7, 5, 'INV-8290', '2024-07-25 18:52:30', 'Michael Brown', '789 Oak St', 'VAT987654', 28.79, 188.75, '2024-07-25 18:51:08', 1, '2024-07-25 18:51:08', '2024-07-25 18:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `invoices_items`
--

CREATE TABLE `invoices_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `qty` double NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `value` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices_items`
--

INSERT INTO `invoices_items` (`id`, `warehouse_id`, `invoice_id`, `product_name`, `product_code`, `qty`, `unit_price`, `value`, `sub_total`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Kids\' Sweatpants', 'PC008', 4, 29.99, 0, 119.96, 1, '2024-07-23 11:59:57', '2024-07-23 11:59:57'),
(2, 1, 1, 'Women\'s Cardigan', 'PC007', 3, 34.99, 0, 104.97, 1, '2024-07-23 11:59:57', '2024-07-23 11:59:57'),
(3, 2, 1, 'Men\'s Hoodie', 'PC006', 2, 59.99, 0, 119.98, 1, '2024-07-23 11:59:57', '2024-07-23 11:59:57'),
(4, 1, 2, 'Men\'s Hoodie', 'PC006', 3, 59.99, 0, 179.97, 1, '2024-07-25 18:44:42', '2024-07-25 18:44:42'),
(5, 1, 2, 'Men\'s Jeans', 'PC004', 2, 39.99, 0, 79.98, 1, '2024-07-25 18:44:42', '2024-07-25 18:44:42'),
(6, 2, 3, 'Men\'s T-Shirt', 'PC001', 5, 19.99, 0, 99.95, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(7, 3, 3, 'Women\'s Blouse', 'PC002', 2, 29.99, 0, 59.98, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(8, 1, 3, 'Men\'s Jeans', 'PC004', 3, 39.99, 0, 119.97, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(9, 1, 4, 'Women\'s Cardigan', 'PC007', 1, 34.99, 0, 34.99, 1, '2024-07-25 18:48:05', '2024-07-25 18:48:05'),
(10, 2, 4, 'Kids\' Jacket', 'PC003', 6, 49.99, 0, 299.94, 1, '2024-07-25 18:48:05', '2024-07-25 18:48:05'),
(11, 2, 5, 'Men\'s Hoodie', 'PC006', 8, 59.99, 0, 479.92, 1, '2024-07-25 18:48:42', '2024-07-25 18:48:42'),
(12, 2, 6, 'Men\'s T-Shirt', 'PC001', 2, 19.99, 0, 39.98, 1, '2024-07-25 18:50:03', '2024-07-25 18:50:03'),
(13, 2, 6, 'Kids\' Jacket', 'PC003', 3, 49.99, 0, 149.97, 1, '2024-07-25 18:50:03', '2024-07-25 18:50:03'),
(14, 3, 7, 'Men\'s Jeans', 'PC004', 4, 39.99, 0, 159.96, 1, '2024-07-25 18:51:08', '2024-07-25 18:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `issue_notes`
--

CREATE TABLE `issue_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `issue_note_no` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `created_by` int(20) NOT NULL,
  `issued_by` varchar(100) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0 COMMENT '0-pending, 1-issued, 2-rejected, 3-partially issued, 4-rejected',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_notes`
--

INSERT INTO `issue_notes` (`id`, `invoice_id`, `issue_note_no`, `customer_name`, `created_by`, `issued_by`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'IN-9660', 'Dana Lee', 1, '[1,7]', 1, '2024-07-23 11:59:57', '2024-07-23 12:07:16'),
(2, 1, 'BO-7596', 'Dana Lee', 1, '[1]', 1, '2024-07-23 12:07:12', '2024-07-25 08:34:31'),
(3, 1, 'BO-948', 'Dana Lee', 7, NULL, 0, '2024-07-23 12:07:16', '2024-07-23 12:07:16'),
(7, 1, 'BO-1661', 'Dana Lee', 1, NULL, 0, '2024-07-25 08:34:31', '2024-07-25 08:34:31'),
(8, 2, 'IN-4826', 'Michael Brown', 1, '[1]', 1, '2024-07-25 18:44:42', '2024-07-26 03:43:41'),
(9, 3, 'IN-4184', 'Bob Williams', 1, NULL, 0, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(10, 4, 'IN-9101', 'Dana Lee', 1, NULL, 0, '2024-07-25 18:48:05', '2024-07-25 18:48:05'),
(11, 5, 'IN-3623', 'Michael Brown', 1, NULL, 0, '2024-07-25 18:48:42', '2024-07-25 18:48:42'),
(12, 6, 'IN-7299', 'Bob Williams', 1, NULL, 0, '2024-07-25 18:50:03', '2024-07-25 18:50:03'),
(13, 7, 'IN-9709', 'Michael Brown', 1, NULL, 0, '2024-07-25 18:51:08', '2024-07-25 18:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `issue_note_items`
--

CREATE TABLE `issue_note_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issue_note_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `stock_no` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `order_qty` int(11) NOT NULL,
  `issued_qty` int(11) NOT NULL,
  `balance_qty` int(11) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_note_items`
--

INSERT INTO `issue_note_items` (`id`, `issue_note_id`, `warehouse_id`, `stock_no`, `description`, `unit_of_measure`, `order_qty`, `issued_qty`, `balance_qty`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'PC008', 'Kids\' Sweatpants', 'Pair', 4, 3, 1, 1, '2024-07-23 11:59:57', '2024-07-23 12:07:16'),
(2, 1, 1, 'PC007', 'Women\'s Cardigan', 'Piece', 3, 1, 2, 1, '2024-07-23 11:59:57', '2024-07-23 12:07:12'),
(3, 1, 2, 'PC006', 'Men\'s Hoodie', 'Piece', 2, 2, 0, 1, '2024-07-23 11:59:57', '2024-07-23 12:07:16'),
(4, 2, 1, 'PC007', 'Women\'s Cardigan', 'Piece', 2, 1, 1, 1, '2024-07-23 12:07:12', '2024-07-25 08:34:31'),
(5, 3, 2, 'PC008', 'Kids\' Sweatpants', 'Pair', 1, 0, 0, 1, '2024-07-23 12:07:16', '2024-07-23 12:07:16'),
(8, 7, 3, 'PC007', 'Women\'s Cardigan', 'Piece', 1, 0, 0, 1, '2024-07-25 08:34:31', '2024-07-25 08:34:31'),
(9, 8, 1, 'PC006', 'Men\'s Hoodie', 'Piece', 3, 3, 0, 1, '2024-07-25 18:44:42', '2024-07-26 03:43:41'),
(10, 8, 1, 'PC004', 'Men\'s Jeans', 'Pair', 2, 2, 0, 1, '2024-07-25 18:44:42', '2024-07-26 03:43:41'),
(11, 9, 2, 'PC001', 'Men\'s T-Shirt', 'Piece', 5, 0, 0, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(12, 9, 3, 'PC002', 'Women\'s Blouse', 'Piece', 2, 0, 0, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(13, 9, 1, 'PC004', 'Men\'s Jeans', 'Pair', 3, 0, 0, 1, '2024-07-25 18:47:06', '2024-07-25 18:47:06'),
(14, 10, 1, 'PC007', 'Women\'s Cardigan', 'Piece', 1, 0, 0, 1, '2024-07-25 18:48:05', '2024-07-25 18:48:05'),
(15, 10, 2, 'PC003', 'Kids\' Jacket', 'Piece', 6, 0, 0, 1, '2024-07-25 18:48:05', '2024-07-25 18:48:05'),
(16, 11, 2, 'PC006', 'Men\'s Hoodie', 'Piece', 8, 0, 0, 1, '2024-07-25 18:48:42', '2024-07-25 18:48:42'),
(17, 12, 2, 'PC001', 'Men\'s T-Shirt', 'Piece', 2, 0, 0, 1, '2024-07-25 18:50:03', '2024-07-25 18:50:03'),
(18, 12, 2, 'PC003', 'Kids\' Jacket', 'Piece', 3, 0, 0, 1, '2024-07-25 18:50:03', '2024-07-25 18:50:03'),
(19, 13, 3, 'PC004', 'Men\'s Jeans', 'Pair', 4, 0, 0, 1, '2024-07-25 18:51:08', '2024-07-25 18:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_06_28_083602_create_products_table', 1),
(6, '2024_06_29_122844_create_warehouses_table', 1),
(7, '2024_06_30_091409_create_invoices_table', 1),
(8, '2024_06_30_091429_create_invoices_items_table', 1),
(9, '2024_07_23_111725_create_issue_notes_table', 1),
(10, '2024_07_23_111733_create_issue_notes_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_unit` varchar(255) DEFAULT NULL,
  `product_unit_price` double NOT NULL DEFAULT 0,
  `stock_available` varchar(255) NOT NULL DEFAULT '0',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `warehouse_id`, `product_code`, `product_name`, `product_unit`, `product_unit_price`, `stock_available`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC001', 'Men\'s T-Shirt', 'Piece', 19.99, '150', 1, '2024-07-01 02:30:00', '2024-07-01 02:30:00'),
(2, 2, 'PC001', 'Men\'s T-Shirt', 'Piece', 19.99, '120', 1, '2024-07-02 03:45:00', '2024-07-02 03:45:00'),
(3, 1, 'PC002', 'Women\'s Blouse', 'Piece', 29.99, '80', 1, '2024-07-03 05:00:00', '2024-07-03 05:00:00'),
(4, 3, 'PC002', 'Women\'s Blouse', 'Piece', 29.99, '60', 1, '2024-07-04 06:15:00', '2024-07-04 06:15:00'),
(5, 1, 'PC003', 'Kids\' Jacket', 'Piece', 49.99, '100', 1, '2024-07-05 06:30:00', '2024-07-05 06:30:00'),
(6, 2, 'PC003', 'Kids\' Jacket', 'Piece', 49.99, '90', 1, '2024-07-06 07:45:00', '2024-07-06 07:45:00'),
(7, 3, 'PC004', 'Men\'s Jeans', 'Pair', 39.99, '75', 1, '2024-07-07 09:00:00', '2024-07-07 09:00:00'),
(8, 1, 'PC004', 'Men\'s Jeans', 'Pair', 39.99, '83', 1, '2024-07-08 10:15:00', '2024-07-26 03:43:41'),
(9, 2, 'PC005', 'Women\'s Skirt', 'Piece', 24.99, '110', 1, '2024-07-09 10:30:00', '2024-07-09 10:30:00'),
(10, 3, 'PC005', 'Women\'s Skirt', 'Piece', 24.99, '95', 1, '2024-07-10 11:45:00', '2024-07-10 11:45:00'),
(11, 1, 'PC006', 'Men\'s Hoodie', 'Piece', 59.99, '62', 1, '2024-07-11 13:00:00', '2024-07-26 03:43:41'),
(12, 2, 'PC006', 'Men\'s Hoodie', 'Piece', 59.99, '68', 1, '2024-07-12 14:15:00', '2024-07-23 12:07:16'),
(13, 3, 'PC007', 'Women\'s Cardigan', 'Piece', 34.99, '85', 1, '2024-07-13 14:30:00', '2024-07-13 14:30:00'),
(14, 1, 'PC007', 'Women\'s Cardigan', 'Piece', 34.99, '81', 1, '2024-07-14 15:45:00', '2024-07-25 08:34:31'),
(15, 2, 'PC008', 'Kids\' Sweatpants', 'Pair', 29.99, '117', 1, '2024-07-15 17:00:00', '2024-07-23 12:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `purchase_order_no` varchar(50) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_address` text NOT NULL,
  `grand_total` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `user_id`, `purchase_order_no`, `purchase_date`, `supplier_name`, `supplier_address`, `grand_total`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'PO-8293', '2024-07-28 14:20:36', 'Alice Johnson', '123 Maple Ave', 106.16, 1, '2024-07-28 08:50:36', '2024-07-28 08:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `qty` double NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `warehouse_id`, `product_name`, `product_code`, `qty`, `unit_price`, `sub_total`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Kids\' Sweatpants', 'PC008', 3, 29.99, 89.97, 1, '2024-07-28 08:50:36', '2024-07-28 08:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT 0 COMMENT '1-admin, 2-sales user, 3-customer, 4-supplier',
  `warehouse_id` int(11) NOT NULL,
  `customer_code` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `customer_phone_no` varchar(255) NOT NULL,
  `customer_vat_no` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `warehouse_id`, `customer_code`, `customer_name`, `customer_address`, `customer_phone_no`, `customer_vat_no`, `customer_email`, `password`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'CUS00001', 'Admin', '123 Main St', '555-0101', 'VAT123456', 'admin@gmail.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-01 02:30:00', '2024-07-23 19:01:49'),
(2, 2, 2, 'CUS00002', 'Jane Smith', '456 Elm St', '555-0102', 'VAT654321', 'janesmith@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-02 04:00:00', '2024-07-23 11:54:33'),
(3, 3, 3, 'CUS00003', 'Michael Brown', '789 Oak St', '555-0103', 'VAT987654', 'michaelbrown@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-03 04:45:00', '2024-07-23 11:54:33'),
(4, 4, 4, 'CUS00004', 'Emily Johnson', '321 Pine St', '555-0104', 'VAT112233', 'emilyjohnson@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-04 05:30:00', '2024-07-27 05:28:24'),
(5, 2, 5, 'CUS00005', 'Daniel Lee', '654 Cedar St', '555-0105', 'VAT445566', 'daniellee@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-05 07:15:00', '2024-07-23 11:54:33'),
(6, 4, 1, 'CUS00006', 'Alice Johnson', '123 Maple Ave', '555-1234', 'VAT001234', 'alicejohnson@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-01 02:30:00', '2024-07-27 05:28:08'),
(7, 3, 2, 'CUS00007', 'Bob Williams', '456 Oak Blvd', '555-5678', 'VAT005678', 'bob@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-02 03:45:00', '2024-07-23 12:02:53'),
(8, 3, 3, 'CUS00008', 'Charlie Brown', '789 Pine St', '555-8765', 'VAT009876', 'charliebrown@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 0, '2024-07-03 05:00:00', '2024-07-23 11:55:32'),
(9, 3, 4, 'CUS00009', 'Dana Lee', '321 Birch Rd', '555-4321', 'VAT003210', 'danalee@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-04 06:15:00', '2024-07-23 11:54:33'),
(10, 2, 5, 'CUS000010', 'Eva Green', '654 Cedar Ln', '555-3456', 'VAT004567', 'evagreen@example.com', '$2y$10$L4twNjlEd/MW8Lxgmv4wIepZXOYcBJRjRTMqhL9VFu.aRqZ9ymeLG', 1, '2024-07-05 06:30:00', '2024-07-23 11:54:33');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WH001', 'Main Warehouse', 1, '2024-07-01 02:30:00', '2024-07-01 02:30:00'),
(2, 'WH002', 'East Side Warehouse', 1, '2024-07-02 04:00:00', '2024-07-02 04:00:00'),
(3, 'WH003', 'West Side Warehouse', 1, '2024-07-03 04:45:00', '2024-07-25 07:09:54'),
(4, 'WH004', 'Central Warehouse', 1, '2024-07-04 05:30:00', '2024-07-04 05:30:00'),
(5, 'WH005', 'North Warehouse', 1, '2024-07-05 07:15:00', '2024-07-05 07:15:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`);

--
-- Indexes for table `invoices_items`
--
ALTER TABLE `invoices_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_notes`
--
ALTER TABLE `issue_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_note_items`
--
ALTER TABLE `issue_note_items`
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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoices_items`
--
ALTER TABLE `invoices_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `issue_notes`
--
ALTER TABLE `issue_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `issue_note_items`
--
ALTER TABLE `issue_note_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
