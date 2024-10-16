-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 05:23 AM
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
-- Database: `male_fashion`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `IMAGE` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ISACTIVE` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `NAME`, `QUANTITY`, `IMAGE`, `ISACTIVE`) VALUES
(1, 'Bags', 1, 'product-11.jpg', 1),
(2, 'Shoes', 1, 'product-1.jpg', 1),
(3, 'Clothing', 1, 'product-2.jpg', 1),
(4, 'Hats', 1, 'product-15.jpg', 1),
(5, 'Accessories', 1, 'product-14.jpg', 1),
(6, 'Fashion', 1, 'product-6.jpg', 1),
(7, 'Product', 1, 'product-10.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `IDPAYMENT` int(11) DEFAULT NULL,
  `IDTRANSPORT` int(11) DEFAULT NULL,
  `ORDERS_DATE` timestamp NULL DEFAULT NULL,
  `IDUSER` int(11) DEFAULT NULL,
  `TOTAL_MONEY` double DEFAULT NULL,
  `NOTES` text DEFAULT NULL,
  `RECIPIENT_NAME` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ADDRESS` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PHONE` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

CREATE TABLE `orders_details` (
  `ID` int(11) NOT NULL,
  `IDORDER` int(11) DEFAULT NULL,
  `IDPRODUCT` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `URL` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `CREATED_DATE` timestamp NULL DEFAULT NULL,
  `UPDATED_DATE` timestamp NULL DEFAULT NULL,
  `ISACTIVE` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`ID`, `NAME`, `URL`, `CREATED_DATE`, `UPDATED_DATE`, `ISACTIVE`) VALUES
(1, 'Credit Card', 'http://example.com', NULL, NULL, 1),
(2, 'PayPal', 'http://example.com', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `IMAGE` varchar(550) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `IDCATEGORY` int(11) DEFAULT NULL,
  `PRICE` double DEFAULT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `RATING` float DEFAULT NULL,
  `ISACTIVE` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ID`, `NAME`, `IMAGE`, `IDCATEGORY`, `PRICE`, `DESCRIPTION`, `QUANTITY`, `RATING`, `ISACTIVE`) VALUES
(1, 'Diagonal Textured Cap', 'product-11.jpg', 1, 60.9, '', 99, 3, 1),
(2, 'Basic Flowing Scarf', 'product-14.jpg', 5, 26.28, '', 99, 4, 1),
(3, 'Piqué Biker Jacket', 'product-2.jpg', 3, 67.24, '', 99, 2, 1),
(4, 'Ankle Boots', 'product-12.jpg', 3, 98.49, '', 99, 2, 1),
(5, 'Multi-pocket Chest Bag', 'product-3.jpg', 2, 43.48, '', 99, 0, 1),
(6, 'Diagonal Textured Cap', 'product-4.jpg', 3, 60.9, '', 99, 5, 1),
(7, 'Ankle Boots', 'product-6.jpg', 6, 98.49, '', 99, 3, 1),
(8, 'T-shirt Contrast Pocket', 'product-7.jpg', 1, 49.66, '', 99, 5, 1),
(9, 'Basic Flowing Scarf', 'product-8.jpg', 3, 26.28, '', 99, 5, 1),
(10, 'Piqué Biker Jacket', 'product-9.jpg', 3, 67.24, '', 99, 5, 1),
(11, 'Multi-pocket Chest Bag', 'product-10.jpg', 7, 43.48, '', 99, 1, 1),
(12, 'T-shirt Contrast Pocket', 'product-13.jpg', 1, 49.66, '', 99, 5, 1),
(13, 'Slate Blue Handfold Fedora by Hampui', 'product-15.jpg', 4, 390, '', 99, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transport_method`
--

CREATE TABLE `transport_method` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NOTES` text DEFAULT NULL,
  `CREATED_DATE` timestamp NULL DEFAULT NULL,
  `UPDATED_DATE` timestamp NULL DEFAULT NULL,
  `ISACTIVE` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_method`
--

INSERT INTO `transport_method` (`ID`, `NAME`, `NOTES`, `CREATED_DATE`, `UPDATED_DATE`, `ISACTIVE`) VALUES
(1, 'Standard Delivery', '', NULL, NULL, 1),
(2, 'Express Delivery', '', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `USERNAME` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PASSWORD` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ROLE` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ADDRESS` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `EMAIL` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `PHONE` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `CREATED_DATE` timestamp NULL DEFAULT NULL,
  `ISACTIVE` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `NAME`, `USERNAME`, `PASSWORD`, `ROLE`, `ADDRESS`, `EMAIL`, `PHONE`, `CREATED_DATE`, `ISACTIVE`) VALUES
(1, 'Nguyễn Tuấn Hưng', 'puddpuss', 'Josee1567@', 'admin', 'Thanh Xuân, Hà Nội', 'xenonex04@gmail.com', '0943213826', NULL, 1),
(2, 'admin', 'admin', 'admin', 'admin', 'admin', 'admin', 'admin', NULL, 1),
(3, NULL, 'ninhmaytroem', '1', NULL, 'Kim Ngưu', 'maytroem@gmail.com', '0123456789', NULL, NULL),
(4, NULL, 'huydz', 'huydz', NULL, 'La Thành, Hà Nội', 'huydz@gmail.com', '0987654321', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_orders_payment` (`IDPAYMENT`),
  ADD KEY `fk_orders_transport` (`IDTRANSPORT`),
  ADD KEY `fk_orders_user` (`IDUSER`);

--
-- Indexes for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `orders_details_orders_ID_fk` (`IDORDER`),
  ADD KEY `orders_details_product_ID_fk` (`IDPRODUCT`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `product_category_ID_fk` (`IDCATEGORY`);

--
-- Indexes for table `transport_method`
--
ALTER TABLE `transport_method`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_payment` FOREIGN KEY (`IDPAYMENT`) REFERENCES `payment_method` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_transport` FOREIGN KEY (`IDTRANSPORT`) REFERENCES `transport_method` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`IDUSER`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD CONSTRAINT `orders_details_orders_ID_fk` FOREIGN KEY (`IDORDER`) REFERENCES `orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_details_product_ID_fk` FOREIGN KEY (`IDPRODUCT`) REFERENCES `product` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category_ID_fk` FOREIGN KEY (`IDCATEGORY`) REFERENCES `category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
