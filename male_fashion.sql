-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 06:47 AM
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
(1, 'Bags', 1, 'BBRA_1.png', 1),
(2, 'Shoes', 1, 'BBRS_1.png', 1),
(3, 'Clothing', 1, 'LV_5.png', 1),
(4, 'Hats', 1, 'HMH_3.png', 1),
(5, 'Accessories', 1, 'CTA_1.png', 1),
(6, 'Fashion', 1, 'BBRA_2.png', 1),
(7, 'Product', 1, 'CNP_3.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `ID` int(11) NOT NULL,
  `IDUSER` int(11) DEFAULT NULL,
  `NAME` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `EMAIL` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MESSAGE` text NOT NULL,
  `ISACTIVE` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`ID`, `IDUSER`, `NAME`, `EMAIL`, `MESSAGE`, `ISACTIVE`) VALUES
(1, 1, 'Nguyễn Tuấn Hưng', 'xenonex04@gmail.com', 'gud', 0),
(2, 1, 'Nguyễn Tuấn Hưng', 'xenonex04@gmail.com', 'gud', 0),
(3, 4, 'huybeovcl', 'test@gmail.com', 'shop ban hang nhu cut', 0);

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `IDPAYMENT`, `IDTRANSPORT`, `ORDERS_DATE`, `IDUSER`, `TOTAL_MONEY`, `NOTES`, `RECIPIENT_NAME`, `ADDRESS`, `PHONE`) VALUES
(122, 1, 1, '2024-11-04 10:47:51', 1, 1725000, 'done', 'Nguyễn Tuấn Hưng', 'Thanh Xuân, Hà Nội', '0943213826'),
(126, 1, 1, '2024-11-08 14:19:22', 2, 84075, 'done', 'admin', 'admin', 'admin');

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

--
-- Dumping data for table `orders_details`
--

INSERT INTO `orders_details` (`ID`, `IDORDER`, `IDPRODUCT`, `QUANTITY`, `PRICE`) VALUES
(94, 122, 45, 10, 2300000),
(108, 126, NULL, 1, 0),
(111, 126, 46, 1, 88500);

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
  `ISACTIVE` tinyint(4) DEFAULT NULL,
  `SALE` int(11) DEFAULT 0,
  `NEWARRIVALS` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ID`, `NAME`, `IMAGE`, `IDCATEGORY`, `PRICE`, `DESCRIPTION`, `QUANTITY`, `RATING`, `ISACTIVE`, `SALE`, `NEWARRIVALS`) VALUES
(17, 'Textured Cotton Crewneck', 'LV_1.png', 3, 1580, 'This graphic cotton crewneck stands out with a distinctive jacquard signature motif. Crafted in indigo tones, it features oversized tonal allover Monogram Flowers and an LV signature on the chest, outlined with a bouclette stitch creating a textured 3D effect. A perfect partner for jeans or casual pants, it also pairs with matching shorts for a total look.', 99, 4.2, 1, 60, 0),
(18, 'Textured Cotton Knit Shorts', 'LV_2.png', 3, 1330, 'These cotton-knit drawstring shorts stand out with a distinctive jacquard signature motif. Crafted in indigo tones, they are adorned with oversized tonal Monogram Flowers with a textured 3D-effect bouclette stitch and an LV signature on the left leg. This relaxed yet refined piece makes a graphic set paired with a matching crewneck.', 99, 4.7, 1, 10, 0),
(19, 'Quilted Textured Wool Blouson', 'LV_3.png', 3, 2790, 'Casual yet elegant, this loungewear-inspired blouson is perfect for traveling, crafted from warm, softly textured wool fleece in timeless cream tones. The sporty tailoring features a tall, drawstring-adjusted collar and raglan sleeves with ribbed elbows for ease of movement. Topstitching creates a smart, quilted effect with an embroidered LV on the chest. Matching pants make a cosy set.', 99, 4.9, 1, 80, 0),
(20, 'Quilted Textured Wool Pants', 'LV_4.png', 3, 1820, 'Casual yet elegant, these loungewear-inspired pants are perfect for traveling, crafted from warm, softly textured wool fleece in timeless cream tones. Sporty topstitched panels create a quilted effect with an embroidered LV on the left leg. A ribbed waistband and cuffs ensure comfort, while a matching blouson makes a cozy set.', 99, 5, 1, 17, 0),
(21, 'Technical Shell Ski Jacket', 'LV_5.png', 3, 4500, 'This versatile shell jacket, designed for layering according to outdoor temperatures, is perfect for skiing. It is crafted from high performance fabric featuring the Damoflage Snow motif — a new wintery reinterpretation of the heritage Damier. This technical piece provides snow protection with a wealth of technical features, including a technical snow skirt and Monogram thumbhole cuffs. It can be paired with matching shell pants for a subtly graphic look', 99, 4.7, 1, 95, 0),
(22, 'Technical Down Ski Pants', 'LV_6.png', 3, 2910, 'These high performance ski pants are conceived for colder days on the slopes. The sporty two-tone paneled design, carfted from water-repellent fabrics, is lined with lightweight Ecodown© for warmth and provides optimum comfort with its adjustable waist, built-in snow gaiters as well as its zippered vents inside the legs. A padded Monogram Flower adds a bold orange accent to the back, matching the corresponding jacket.', 99, 5, 1, 72, 0),
(23, 'Louis Vuitton Escale, Automatic, 40.5mm, Platinum and diamonds', 'LVW_1.png', 5, 164000, 'The Escale Automatic 40.5 mm in platinum and diamonds features intricate yet refined details that echo those found adorning the Maison\'s iconic trunks. Enriched by an onyx dial, this time-only high jewellery timepiece is a celebration of technical precision, powered by the automatic LFT023 caliber.\r\nMale model’s wrist size: 18cm/7.09 inches\r\nFemale model’s wrist size: 15cm/5.91 inches', 5, 5, 1, 25, 0),
(24, 'Ombre Nomade', 'LVP_1.png', 7, 410, 'Swirls of oud wood for a journey into the heart of the desert\r\nAs the day passes, the path of the sun creates shimmering patterns of shadow and light on the dunes. Whilst everything else seems motionless, the desert comes alive and draws the traveler into a passionate odyssey. Designed for lovers of rare essences, Ombre Nomade concentrates that sensation of infinity into one of the most mythical ingredients in perfumery, oud wood. A rebellious material, intoxicatingly beautiful, that the Master Perfumer Jacques Cavallier Belletrud has faceted with a hint of benzoin and raspberry accents. Far away, a smoky wisp of incense floats towards the heavens. Never has oud been so mystical.\r\n\r\nThe bottle is refillable in stores equipped with a perfume fountain.\r\n\r\nOrder your Louis Vuitton fragrance and receive a complimentary sample so you can discover the fragrance before wearing or gifting it. That way, should you wish to, you can return your unopened bottle for reimbursement.', 20, 5, 1, 55, 0),
(25, 'Les Gastons Vuitton Puzzle Ring, Yellow Gold and Titanium', 'LVR_1.png', 5, 4900, 'The innovative design of the Puzzle Titanium Ring was inspired by the multifaceted personality of Louis Vuitton’s grandson Gaston-Louis Vuitton, a creator, a collector and a dandy. The signature Monogram Flowers and Initials are engraved on three spinning bands of Les Gastons Vuitton blue titanium, creating a puzzle steeped in House heritage. A playful, elegant ring for the modern-day Gaston in all of us.', 15, 5, 1, 45, 0),
(26, 'Leather Puffer Jacket', 'BBR_1.png', 3, 9546.88, 'A boxy puffer jacket made in Italy from smooth nappa leather, filled with goose down and feathers. The funnel-neck design is cut to a regular fit and with B buckle straps at the collar.', 99, 5, 1, 100, 0),
(27, 'Short Leather Car Coat', 'BBR_2.png', 3, 7750.45, 'A car coat made in Italy from smooth calf leather with a quilted Equestrian Knight Design lining. The style is cut to a relaxed fit and features a detachable collar in tactile shearling and B-cut zip pulls.', 99, 5, 1, 75, 0),
(28, 'Ivy Cotton Blend Shirt', 'BBR_3.png', 3, 4028.94, 'A short-sleeved shirt made in Italy from a cotton blend. The style is cut to an oversized fit and patterned with a seasonal ivy motif in broderie anglaise embroidery.', 99, 5, 1, 22, 0),
(29, 'Heritage EKD Backpack', 'BBRA_1.png', 1, 3543.53, 'A backpack in calf leather embossed with a pattern inspired by twill fabric. The spacious style is lined and edged with Burberry Check and finished with B-cut zip pulls.', 50, 4, 1, 18, 0),
(30, 'Leather Saddle High Boots', 'BBRS_1.png', 2, 2734.5, 'Equestrian-inspired boots made in Italy from smooth calf leather with B buckle straps. The leather sole is embossed with the Equestrian Knight Design.', 30, 5, 1, 80, 0),
(31, 'Check Cashmere Hooded Scarf', 'BBRA_2.png', 6, 1601.87, 'A hooded scarf in cashmere woven with the Burberry Check. Made at a 200-year-old Scottish mill, the fabric is washed in local spring water and brushed with teasels for an ultra-soft finish.\r\n', 30, 5, 1, 0, 0),
(35, 'Fred Decoupages de H reversible bucket hat', 'HMH_1.png', 4, 460, 'Reversible bucket hat in plain canvas with contrasting \"H Droit\" embroidery on the outside, \"Decoupages de H\" printed canvas on the inside.', 99, 4, 1, 16, 0),
(36, 'Fred Sangle H bucket hat', 'HMH_2.png', 4, 600, 'Bucket hat in linen and cotton H canvas, band with \"H\" cut-out detail in Swift calfskin. Lining in plain linen.', 99, 4, 1, 86, 0),
(37, 'Fred Etriers reversible bucket hat', 'HMH_3.png', 4, 580, 'Reversible bucket hat in \"Etriers\" printed Toilovent on the outside, design by Françoise de la Perrière, plain Toilovent on the inside.', 99, 4, 1, 28, 0),
(38, 'Glenan H Jean bracelet', 'HMA_1.png', 5, 370, 'Braided bracelet in denim with Glenan closure.\r\nThis season, the nautical-inspired Glenan H braided bracelet is adorned with denim, displaying the House\'s leather braiding know-how through a cotton textile version.', 50, 4, 1, 30, 0),
(39, 'Chypre sandal', 'HMS_1.png', 2, 910, 'Techno-sandal in calfskin with anatomical rubber sole and adjustable strap.\r\nA sleek design for a comfortable casual look.', 75, 5, 1, 38, 0),
(40, 'SYCOMORE', 'CNP_1.png', 7, 850, 'LES EXCLUSIFS DE CHANEL – 6.8 fl. oz. Eau de Parfum Huile Corps Set', 25, 5, 1, 0, 0),
(41, '1957', 'CNP_2.png', 7, 850, 'LES EXCLUSIFS DE CHANEL – 6.8 fl. oz. Eau de Parfum Huile Corps Set', 25, 5, 1, 17, 0),
(42, 'GARDÉNIA', 'CNP_3.png', 7, 17000, 'LES EXCLUSIFS DE CHANEL – Parfum Grand Extrait', 10, 5, 1, 69, 1),
(43, 'SUBLIMAGE L’EXTRAIT DE NUIT – LE COFFRET', 'CNP_4.png', 7, 1920, 'Ultimate Repair Night Concentrate – Limited Edition', 5, 5, 1, 63, 1),
(44, 'J12 Watch Caliber 12.1, 38 mm', 'CNA_1.png', 5, 7950, 'Black highly resistant ceramic and steel', 30, 5, 1, 82, 1),
(45, 'Santos de Cartier watch', 'CTA_1.png', 5, 230000, 'Santos de Cartier watch, skeleton, Manufacture mechanical movement with manual winding 9611 MC.\r\nRhodium-finish 18K white gold (750/1000) case set with 126 baguette-cut diamonds totaling 5.88 carats and faceted crown set with a 0.22-carat brilliant-cut diamond.', 5, 5, 1, 79, 1),
(46, 'Fine Jewelry watch', 'CTA_2.png', 5, 88500, 'Zelda Fine Jewelry watch, quartz movement, rhodiumized 18K white gold (750/1000) case set with 112 brilliant-cut diamonds totaling 0.31 carats, rhodiumized 18K white gold (750/1000) dial set with 62 brilliant-cut diamonds totaling 0.21 carat, rhodiumized 18K white gold (750/1000) bracelet and clasp set with 143 brilliant-cut diamonds totaling 2.96 carats, rhodiumized steel sword-shaped hands. Case dimensions: 16 mm x 16 mm, 6 mm thick. Water-resistant up to 3 bar (approx. 30 meters/100 feet).', 5, 5, 1, 14, 1),
(47, 'Santos de Cartier watch', 'CTA_3.png', 5, 70000, 'Santos de Cartier Skeleton watch, large model, skeleton mechanical movement with manual winding 9612 MC. Yellow gold 750/1000 case, 7-sided crown set with a faceted sapphire, gold-finish sword-shaped hands, sapphire crystal and case back. Yellow gold 750/1000 bracelet with SmartLink adjustment system. Semi-matte gray second strap in alligator leather, with interchangeable yellow gold 750/1000 folding buckle. Both bracelets are fitted with the “QuickSwitch” interchangeability system. Hours and minutes with skeletonized bridges forming Roman numerals.Santos de Cartier Skeleton watch, large model, skeleton mechanical movement with manual winding 9612 MC. Yellow gold 750/1000 case, 7-sided crown set with a faceted sapphire, gold-finish sword-shaped hands, sapphire crystal and case back. Yellow gold 750/1000 bracelet with SmartLink adjustment system. Semi-matte gray second strap in alligator leather, with interchangeable yellow gold 750/1000 folding buckle. Both bracelets are fitted with the “QuickSwitch” interchangeability system. Hours and minutes with skeletonized bridges forming Roman numerals.', 5, 5, 1, 0, 1),
(48, 'Tank Normale watch', 'CTA_4.png', 5, 80000, 'Tank Normale watch, skeleton, Manufacture mechanical movement with manual winding, caliber 9628 MC.\r\nPlatinum 950/1000 case, platinum 950/1000 crown set with a ruby, polished blue steel sword-shaped hands, sapphire case back and crystal.\r\nGray alligator-skin strap and burgundy alligator-skin strap, platinum 950/1000 ardillon buckle. Hours, minutes, with 24-hour day/night indicator\r\nMovement consisting of 150 parts including 21 rubies.', 5, 5, 1, 64, 1),
(49, 'Tank Normale watch', 'CTA_5.png', 5, 71000, 'Tank Normale watch, skeleton, Manufacture mechanical movement with manual winding, caliber 9628 MC.\r\n18K yellow gold (750/1000) case and beaded crown set with a sapphire, polished blue steel sword-shaped hands, sapphire case back and crystal.\r\nBrown alligator-skin strap, second strap in green alligator skin, 18K yellow gold (750/1000) ardillon buckle. Hours, minutes, with 24-hour day/night indicator.\r\nMovement consisting of 150 parts including 21 rubies.', 5, 5, 1, 73, 1);

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
(2, 'admin', 'admin', 'admin', 'admin', 'admin', 'admin@admin.admin', 'admin', NULL, 1),
(3, 'Nguyễn Dương Ninh', 'ninhmaytroem', '1', 'admin', 'Kim Ngưu, Hà Nội', 'maytroem@gmail.com', '0123456789', NULL, 1),
(4, 'Nguyễn Anh Huy', 'huydz', 'huydz', 'admin', 'La Thành, Hà Nội', 'huydz@gmail.com', '0987654321', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_feedback_user` (`IDUSER`);

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
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`IDUSER`) REFERENCES `user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

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
