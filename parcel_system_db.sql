-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 10:40 PM
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
-- Database: `parcel_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

CREATE TABLE `account_tbl` (
  `id` int(11) NOT NULL,
  `member_id` varchar(6) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`id`, `member_id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(1, '46DAF7', 'Max', 'Laga', '', '', '2024-11-30 04:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `declare_shipment_order_tbl`
--

CREATE TABLE `declare_shipment_order_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tracking_no` varchar(255) NOT NULL,
  `total_weight_lb` decimal(10,2) NOT NULL,
  `declared_shipment` tinyint(1) NOT NULL,
  `no_of_order` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(255) NOT NULL,
  `confirmed_payment` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `declare_shipment_order_tbl`
--

INSERT INTO `declare_shipment_order_tbl` (`id`, `user_id`, `tracking_no`, `total_weight_lb`, `declared_shipment`, `no_of_order`, `totalPrice`, `warehouse`, `timestamp`, `order_status`, `confirmed_payment`) VALUES
(21, 6, 'G543216781', 0.00, 0, 1, 12.00, 'Canada', '2024-12-07 15:11:31', 'Pending', 0),
(22, 6, 'G5432167821', 0.00, 0, 1, 599.00, 'Australia', '2024-12-07 15:12:15', 'Pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `deliveryaddress_tbl`
--

CREATE TABLE `deliveryaddress_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveryaddress_tbl`
--

INSERT INTO `deliveryaddress_tbl` (`id`, `user_id`, `first_name`, `last_name`, `mobile_number`, `address`, `zip_code`, `is_default`) VALUES
(24, 1, 'Max', 'Pain', '8438779017', '433 Foxtail Drive', '29568', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_tbl`
--

CREATE TABLE `order_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tracking_no` varchar(255) NOT NULL,
  `product_type` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_tbl`
--

INSERT INTO `order_tbl` (`id`, `user_id`, `tracking_no`, `product_type`, `product_name`, `quantity`, `unit_price`) VALUES
(20, 6, 'G543216781', 'Handmade', '4242', 1, 12.00),
(21, 6, 'G5432167821', 'Movies, Music $ Games', 'Pokemon Sword 3DS', 1, 599.00);

-- --------------------------------------------------------

--
-- Table structure for table `paid_orders_tbl`
--

CREATE TABLE `paid_orders_tbl` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tracking_no` varchar(255) NOT NULL,
  `total_weight_lb` decimal(10,2) NOT NULL,
  `no_of_order` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `total_shipping_fee` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `base_shipping_fee_per_lb` decimal(10,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `dateTime` datetime DEFAULT current_timestamp(),
  `warehouse` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paid_orders_tbl`
--

INSERT INTO `paid_orders_tbl` (`order_id`, `user_id`, `tracking_no`, `total_weight_lb`, `no_of_order`, `totalPrice`, `total_shipping_fee`, `payment_method`, `base_shipping_fee_per_lb`, `name`, `address`, `mobile_no`, `order_status`, `dateTime`, `warehouse`) VALUES
(7, 1, '3686354338FF', 5.90, 1, 134.00, 1920.00, 'Gcash', 5.00, 'Max Pain', '433 Foxtail Drive', '8438779017', 'Delivered', '2024-12-09 04:27:14', 'United States');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_addresses_tbl`
--

CREATE TABLE `warehouse_addresses_tbl` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse_addresses_tbl`
--

INSERT INTO `warehouse_addresses_tbl` (`id`, `address`, `city`, `state`, `zip_code`, `country`, `tel`, `img_path`) VALUES
(1, '123 Maple Street', 'Toronto', 'Ontario', 'M5A 2B7', 'Canada', '+1 416-555-0198', '../assets/country/ca.png'),
(2, '45 High Street', 'London', NULL, 'W1U 4QS', 'United Kingdom', '+44 20 7946 0958', '../assets/country/uk.png'),
(3, '101 Main Street', 'Los Angeles', 'California', '90001', 'United States', '+1 310-555-0199', '../assets/country/us.png'),
(4, '2-3-1 Shibuya', 'Tokyo', NULL, '150-0002', 'Japan', '+81 3-5555-6789', '../assets/country/jp.png'),
(5, '88 King Street', 'Sydney', 'New South Wales', '2000', 'Australia', '+61 2 5555 6789', '../assets/country/au.png'),
(6, '77 Gangnam-daero', 'Seoul', NULL, '06050', 'South Korea', '+82 2-555-1234', '../assets/country/kr.png'),
(7, '123 Beijing Road', 'Beijing', NULL, '100000', 'China', '+86 10-5555-6789', '../assets/country/cn.png'),
(8, '45 Xinyi Road', 'Taipei', NULL, '11051', 'Taiwan', '+886 2-5555-1234', '../assets/country/tw.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id` (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `declare_shipment_order_tbl`
--
ALTER TABLE `declare_shipment_order_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_no` (`tracking_no`);

--
-- Indexes for table `deliveryaddress_tbl`
--
ALTER TABLE `deliveryaddress_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracking_no` (`tracking_no`);

--
-- Indexes for table `paid_orders_tbl`
--
ALTER TABLE `paid_orders_tbl`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `tracking_no` (`tracking_no`);

--
-- Indexes for table `warehouse_addresses_tbl`
--
ALTER TABLE `warehouse_addresses_tbl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_tbl`
--
ALTER TABLE `account_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `declare_shipment_order_tbl`
--
ALTER TABLE `declare_shipment_order_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `deliveryaddress_tbl`
--
ALTER TABLE `deliveryaddress_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_tbl`
--
ALTER TABLE `order_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `paid_orders_tbl`
--
ALTER TABLE `paid_orders_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `warehouse_addresses_tbl`
--
ALTER TABLE `warehouse_addresses_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveryaddress_tbl`
--
ALTER TABLE `deliveryaddress_tbl`
  ADD CONSTRAINT `deliveryaddress_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account_tbl` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD CONSTRAINT `order_tbl_ibfk_1` FOREIGN KEY (`tracking_no`) REFERENCES `declare_shipment_order_tbl` (`tracking_no`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
