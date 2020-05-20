-- phpMyAdmin SQL Dump
-- version 2.6.1-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 23, 2005 at 08:04 PM
-- Server version: 4.0.24
-- PHP Version: 4.3.11
-- 
-- Database: `ausesolu_k9catalog`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `category`
-- 

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `parent_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `category`
-- 

REPLACE INTO `category` VALUES (1, 'Kennels', 'Dog Kennels', 0);
REPLACE INTO `category` VALUES (2, 'Bedding', '', 0);
REPLACE INTO `category` VALUES (3, 'Pet Toys', '', 0);
REPLACE INTO `category` VALUES (4, 'Treats and Munchies', '', 0);
REPLACE INTO `category` VALUES (5, 'Bowls', '', 0);
REPLACE INTO `category` VALUES (6, 'Leads & Collars', '', 0);
REPLACE INTO `category` VALUES (7, 'Pet Clothing', '', 0);
REPLACE INTO `category` VALUES (8, 'Dog Coats', '', 7);
REPLACE INTO `category` VALUES (9, 'Dog Skivvies', '', 7);
REPLACE INTO `category` VALUES (10, 'Dog Jumpers', '', 7);
REPLACE INTO `category` VALUES (11, 'Grooming Products', '', 0);
REPLACE INTO `category` VALUES (12, 'Carry Cages', '', 0);
REPLACE INTO `category` VALUES (14, 'Cat Litter Handling', '', 0);
REPLACE INTO `category` VALUES (15, 'Bottles and Vials', '', 0);
REPLACE INTO `category` VALUES (16, 'Car Seat Protectors', '', 0);
REPLACE INTO `category` VALUES (17, 'Books', '', 0);
REPLACE INTO `category` VALUES (18, 'Igloos', 'Pet Igloos', 2);
REPLACE INTO `category` VALUES (19, 'Baskets', 'Bedding baskets', 2);
REPLACE INTO `category` VALUES (20, '', '', 0);
REPLACE INTO `category` VALUES (21, 'Mats', 'Bedding mats', 2);
REPLACE INTO `category` VALUES (22, 'Futons', 'Bedding Futtons', 2);
REPLACE INTO `category` VALUES (23, 'Tramopline Beds', 'Trampoline beds', 2);
REPLACE INTO `category` VALUES (24, 'Blankets', '', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `category_image_index`
-- 

DROP TABLE IF EXISTS `category_image_index`;
CREATE TABLE IF NOT EXISTS `category_image_index` (
  `cat_id` int(11) NOT NULL default '0',
  `image_id` mediumint(9) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `category_image_index`
-- 

REPLACE INTO `category_image_index` VALUES (13, 150);

-- --------------------------------------------------------

-- 
-- Table structure for table `client_prices`
-- 

DROP TABLE IF EXISTS `client_prices`;
CREATE TABLE IF NOT EXISTS `client_prices` (
  `client_id` int(11) NOT NULL default '0',
  `product_code` varchar(20) NOT NULL default '',
  `client_price` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `client_prices`
-- 

REPLACE INTO `client_prices` VALUES (1, 'RNDBGNT-xxx', 999);

-- --------------------------------------------------------

-- 
-- Table structure for table `clients`
-- 

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`client_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `clients`
-- 

REPLACE INTO `clients` VALUES (1, 'Pets Paradise');
REPLACE INTO `clients` VALUES (2, 'Zoe''s Vet Clinic');
REPLACE INTO `clients` VALUES (3, 'Wal''s Pet Center');

-- --------------------------------------------------------

-- 
-- Table structure for table `images`
-- 

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `taint` tinyint(4) NOT NULL default '0',
  `free` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `images`
-- 

REPLACE INTO `images` VALUES (150, '', 'source/bowls/DSCN1050.jpg', 0, 0);
REPLACE INTO `images` VALUES (149, '', 'source/pet_toys/business 127.JPG', 0, 0);
REPLACE INTO `images` VALUES (148, '', 'source/pet_toys/business 060.JPG', 0, 0);
REPLACE INTO `images` VALUES (147, '', 'source/pet_toys/business 020.JPG', 0, 0);
REPLACE INTO `images` VALUES (146, '', 'source/pet_toys/business 011.JPG', 0, 0);
REPLACE INTO `images` VALUES (145, '', 'source/pet_toys/business 008.JPG', 0, 0);
REPLACE INTO `images` VALUES (144, '', 'source/other/business 196.JPG', 0, 0);
REPLACE INTO `images` VALUES (143, '', 'source/other/business 128.JPG', 0, 0);
REPLACE INTO `images` VALUES (142, '', 'source/bowls/business 044.JPG', 0, 0);
REPLACE INTO `images` VALUES (141, '', 'source/bowls/business 012.JPG', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `order_items`
-- 

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_id` int(11) NOT NULL default '0',
  `product_code` varchar(20) NOT NULL default '',
  `qty` int(11) NOT NULL default '0',
  `price` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `order_items`
-- 

REPLACE INTO `order_items` VALUES (39, 'C010', 4, 0);
REPLACE INTO `order_items` VALUES (38, 'BK01', 6, 0);
REPLACE INTO `order_items` VALUES (14, 'B199', 7, 0);
REPLACE INTO `order_items` VALUES (27, 'RNDBGNT-xxx', 6, 0);
REPLACE INTO `order_items` VALUES (38, 'BK02', 6, 0);
REPLACE INTO `order_items` VALUES (38, 'RNDBGNT-xxx', 5, 0);
REPLACE INTO `order_items` VALUES (38, 'BELBLGE-xxx', 5, 0);
REPLACE INTO `order_items` VALUES (27, 'RNDDGNT-xxx', 5, 0);
REPLACE INTO `order_items` VALUES (27, 'RNDDLGE-xxx', 4, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `orders`
-- 

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `status` varchar(12) NOT NULL default '',
  `client_id` int(11) NOT NULL default '0',
  `instructions` varchar(255) NOT NULL default '',
  `modified` timestamp(14) NOT NULL,
  PRIMARY KEY  (`order_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `orders`
-- 

REPLACE INTO `orders` VALUES (38, 'open', 1, 'my instructions are good', '00000000000000');
REPLACE INTO `orders` VALUES (14, 'closed', 2, 'Zoes instructions', '00000000000000');
REPLACE INTO `orders` VALUES (27, 'closed', 2, 'second order', '00000000000000');
REPLACE INTO `orders` VALUES (39, 'open', 2, '', '00000000000000');

-- --------------------------------------------------------

-- 
-- Table structure for table `products`
-- 

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(124) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `option` varchar(80) NOT NULL default '',
  `size` varchar(80) NOT NULL default '',
  `price` int(20) NOT NULL default '0',
  `product_code` varchar(20) NOT NULL default '',
  `typeid` int(12) NOT NULL default '0',
  `aus_made` tinyint(4) NOT NULL default '0',
  `qty_break` int(11) NOT NULL default '0',
  `qty_discount` int(11) NOT NULL default '0',
  `qty_instock` int(11) NOT NULL default '0',
  `special` smallint(6) NOT NULL default '0',
  `clearance` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `products`
-- 

REPLACE INTO `products` VALUES (1519, '', '6" aluminium dog bowl', '', '', 375, 'B199', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1520, '', '7" aluminium dog bowl', '', '', 495, 'B200', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2076, '', 'Baisted pringles 1kg', '', '', 2325, 'DC045', 21, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1744, '', 'Pork rolls 20pc', '', '', 2000, 'DC020', 24, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2069, '', '"Smoked pork rolls 6"" 38pc"', '', '', 3800, 'DC036', 24, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (6, 'Dog Chokies - Carob Drops', '', '', '1Kg bulk pack', 700, 'DCDC01', 23, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (7, 'Dog Chokies - Carob Drops', '', '', '5Kg bulk pack', 3000, 'DCDC05', 23, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (8, 'Dog Chokies - Carob Drops', '', '', '250gm Container pack', 258, 'DCDC25', 23, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2063, '', 'Rawhide plain puppy chips 1kg', '', '', 2000, 'DC024', 22, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2064, '', 'Rawhide baisted puppy chip 1kg', '', '', 2200, 'DC025', 158, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2073, '', 'Lamb neck', '', '', 200, 'DC040', 162, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2072, '', 'Bull sticks 25pc', '', '', 3375, 'DC039', 27, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1747, '', '"8"" porkhide retrievers 10pc"', '', '', 3360, 'DC016', 125, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1745, '', '"3"" porkhide drumsticks 45pc"', '', '', 2485, 'DC014', 29, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1746, '', '"4"" porkhide knot bone 25pc"', '', '', 2240, 'DC015', 30, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1748, '', 'Porkhide sausage rolls 85pc', '', '', 2295, 'DC017', 31, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1761, '', 'R/h twist stick 10mm jar 20pc', '', '', 495, 'DC001', 130, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2074, '', 'Turkey jerky 1kg', '', '', 1975, 'DC042', 33, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (19, 'Turkey Jerky', '', '', '120gm Container Pack', 420, 'DC046', 33, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (20, 'Dog Chokies - Yoghurt Drops', '', '', '1Kg Bulk Pack', 1000, 'DCYB01', 34, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (21, 'Dog Chokies - Yoghurt Drops', '', '', '5Kg Bulk Pack', 4200, 'DCYB05', 34, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (22, 'Dog Chokies - Yoghurt Drops', '', '', '250gm Container pack', 295, 'DCYB25', 34, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2079, '', 'Dried liver 1kg', '', '', 2500, 'PDL01', 35, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (24, 'Dried Liver', '', '', '150gm', 455, 'PDL15', 35, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2062, '', 'Smoked cows ears 50pc', '', '', 5250, 'DC023', 36, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1790, '', 'Smoked pigs ear 50pc', '', '', 5500, 'DC018', 37, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1933, '', '"5"" white retriever stick 40pc"', '', '', 2495, 'DC012', 150, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1934, '', '"10"" white retriever stick 20pc"', '', '', 4160, 'DC013', 150, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2068, '', 'Roo sausages 25pc', '', '', 2395, 'DC035', 40, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2071, '', 'Premium grade beef jerky 1kg', '', '', 3000, 'DC038', 161, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2061, '', 'Smoked pigs trotter split 20pc', '', '', 1890, 'DC022', 157, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2078, '', 'Beef rattles 1kg', '', '', 2664, 'DC049', 43, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1719, '', 'Smoked pigs snouts 50pc', '', '', 2500, 'DC021', 123, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (34, 'Smoked Pig Snouts - large', 'Made in Australia x', '', '20 pieces - large', 1500, 'DC041', 44, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2070, '', 'Beef crackers 12pc', '', '', 324, 'DC037', 45, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1595, '', 'Clod/femur bones small 40pc', '', '', 4680, 'DC019', 82, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2075, '', 'Clod/femur bones large 8pc', '', '', 2880, 'DC044', 82, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2067, '', 'Roo tails', '', '', 189, 'DC034', 47, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1801, '', 'S/S pet bowl embossed small', '', '', 595, 'B260', 137, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1802, '', 'S/S pet bowl embossed medium', '', '', 775, 'B261', 137, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1804, '', 'Suede cat collar black', '', '', 215, 'LC754-blk', 138, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1803, '', 'S/S pet bowl embossed large', '', '', 990, 'B262', 137, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1990, '', 'Purple knitted dog jumper smal', '', '', 2500, 'K9JSML-pur', 18, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (44, 'Dog Coats - Flannelette', '', '', 'Suit 20cm length', 534, 'K9C20C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (45, 'Dog Coat - Flannelette', '', '', 'Suit 25cm length', 640, 'K9C25C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (46, 'Dog Coat - Flannelette', '', '', 'Suit 30cm length', 939, 'K9C30C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (47, 'Dog Coat - Flannelette', '', '', 'Suit 35cm length', 1265, 'K9C35C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (48, 'Dog Coat - Flannelette', '', '', 'Suit 40cm length', 1384, 'K9C40C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (50, 'Dog Coat - Flannelette', '', '', 'Suit 45cm length', 1465, 'K9C45C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (51, 'Dog Coat - Flannelette', '', '', 'Suit 50cm length', 1795, 'K9C50C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (52, 'Dog Coat - Flannelette', '', '', 'Suit 55cm length', 2173, 'K9C55C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (53, 'Dog Coat - Flannelette', '', '', 'Suit 60cm length', 2467, 'K9C60C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (54, 'Dog Coat - Flannelette', '', '', 'Suit 65cm length', 2715, 'K9C65C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (55, 'Dog Coat - Flannelette', '', '', 'Suit 70cm length', 2953, 'K9C70C-blu', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (56, 'Dog Coat - Flannelette', '', '', 'Suit 20cm length', 534, 'K9C20C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (57, 'Dog Coat - Flannelette', '', '', 'Suit 25cm length', 640, 'K9C25C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (58, 'Dog Coat - Flannelette', '', '', 'Suit 30cm length', 939, 'K9C30C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (59, 'Dog Coat - Flannelette', '', '', 'Suit 35cm length', 1265, 'K9C35C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (60, 'Dog Coat - Flannelette', '', '', 'Suit 40cm length', 1384, 'K9C40C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (61, 'Dog Coat - Flannelette', '', '', 'Suit 45cm length', 1465, 'K9C45C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (62, 'Dog Coat - Flannelette', '', '', 'Suit 40cm length', 1384, 'K9C40C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (63, 'Dog Coat - Flannelette', '', '', 'Suit 50cm length', 1795, 'K9C50C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (64, 'Dog Coat - Flannelette', '', '', 'Suit 55cm length', 2173, 'K9C55C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (65, 'Dog Coat - Flannelette', '', '', 'Suit 60cm length', 2467, 'K9C60C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (66, 'Dog Coat - Flannelette', '', '', 'Suit 65cm length', 2715, 'K9C65C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (67, 'Dog Coat - Flannelette', '', '', 'Suit 70cm length', 2953, 'K9C70C-red', 14, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1985, '', 'Polar fleece coat blue sml', '', '', 900, 'K9CSML-blu', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1983, '', 'Polar fleece coat blue med', '', '', 1175, 'K9CMED-blu', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1981, '', 'Polar fleece coat blue large', '', '', 1350, 'K9CLGE-blu', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1987, '', 'Polar fleece coat blue xlarge', '', '', 1970, 'K9CXLG-blu', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1986, '', 'Polar fleece coat purple small', '', '', 900, 'K9CSML-pur', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1984, '', 'Polar fleece coat purple med', '', '', 1175, 'K9CMED-pur', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1982, '', 'Polar fleece coat purple large', '', '', 1350, 'K9CLGE-pur', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1988, '', 'Polar fleece coat purple xlg', '', '', 1970, 'K9CXLG-pur', 17, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1993, '', '20cm skivvy red', '', '', 900, 'K9S20-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2027, '', 'Cat scratch pole w/swing toy', '', '', 1000, 'CT015', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2026, '', 'Colourful rats on card / 12pc', '', '', 2450, 'CT013', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1999, '', '30cm skivvy red', '', '', 900, 'K9S30-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2025, '', 'Barrel of coloured fur mice 60', '', '', 3100, 'CT012', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2002, '', '35cm skivvy red', '', '', 990, 'K9S35-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2024, '', 'Catnip furry mice/card 24pc', '', '', 1800, 'CT011', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2005, '', '40cm skivvy red', '', '', 1080, 'K9S40-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2023, '', 'Assorted pack cat toys 12pc', '', '', 2100, 'CT009', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2008, '', '45cm skivvy red', '', '', 1170, 'K9S45-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2022, '', 'Twist rope bird perch', '', '', 250, 'BRD03', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2011, '', '50cm skivvy red', '', '', 1890, 'K9S50-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2021, '', 'Craft style hanging bird toy', '', '', 215, 'BRD02', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2017, '', 'Medium skivvy red', '', '', 2250, 'K9SMED-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2018, '', 'Neem pet wash soap 12cakes', '', '', 2545, 'GR52', 154, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2014, '', 'Large skivvy red', '', '', 2790, 'K9SLGE-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2020, '', 'Craft perch w-10cm extension', '', '', 205, 'BRD01', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1937, '', '20cm oil cloth coat buckle', '', '', 772, 'K9C20OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1941, '', '25cm oil cloth coat buckle', '', '', 863, 'K9C25OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1945, '', '30cm oil cloth coat buckle', '', '', 1158, 'K9C30OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1949, '', '35cm oil cloth coat buckle', '', '', 1488, 'K9C35OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1953, '', '40cm oil cloth coat buckle', '', '', 1683, 'K9C40OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1957, '', '45cm oil cloth coat buckle', '', '', 1920, 'K9C45OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1961, '', '50cm oil cloth coat buckle', '', '', 2285, 'K9C50OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1969, '', '60cm oil cloth coat buckle', '', '', 2976, 'K9C60OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1974, '', '65cm oil cloth coat velcro', '', '', 3154, 'K9C65OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1977, '', '70cm oil cloth coat buckle', '', '', 3482, 'K9C70OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1979, '', '75cm oil cloth coat buckle', '', '', 3633, 'K9C75OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1980, '', '80cm oil cloth coat buckle', '', '', 4109, 'K9C80OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1938, '', '20cm oil cloth coat velcro', '', '', 734, 'K9C20OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1942, '', '25cm oil cloth coat velcro', '', '', 825, 'K9C25OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1946, '', '30cm oil cloth coat velcro', '', '', 1090, 'K9C30OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1950, '', '35cm oil cloth coat velcro', '', '', 1420, 'K9C35OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1954, '', '40cm oil cloth coat velcro', '', '', 1613, 'K9C40OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1958, '', '45cm oil cloth coat velcro', '', '', 1851, 'K9C45OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1962, '', '50cm oil cloth coat velcro', '', '', 2200, 'K9C50OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1966, '', '55cm oil cloth coat velcro', '', '', 2597, 'K9C55OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1970, '', '60cm oil cloth coat velcro', '', '', 2907, 'K9C60OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1973, '', '65cm oil cloth coat buckle', '', '', 3224, 'K9C65OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1978, '', '70cm oil cloth coat velcro', '', '', 3402, 'K9C70OV', 15, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1668, '', 'Dog kennel deluxe 60lt', '', '', 7910, '060D', 106, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1671, '', 'Dog kennel standard 200lt', '', '', 8181, '200S', 106, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1637, '', 'Water/food fold up dog bowl', '', '', 350, 'B237', 98, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1550, '', 'Water bowl suit sml carry cage', '', '', 350, 'B271', 70, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1552, '', 'Water bowl suit lge carry cage', '', '', 450, 'B275', 70, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1551, '', 'Cage bowls', '', '', 248, 'B272', 70, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (125, 'Litter Scoop', '', '', '-', 120, 'B242', 0, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1692, '', 'Litter tray liner bags', '', '', 250, 'CT008', 112, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1693, '', 'Deluxe dacron mat large', '', '', 2069, 'MATDDLGE-xxx', 113, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1711, '', 'Melamine bowl w/r rim multi', '', '', 250, 'B273-mti', 153, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1669, '', 'Dog kennel standard 60lt', '', '', 7400, '060S', 106, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1670, '', 'Dog kennel deluxe 200lt', '', '', 9636, '200D', 106, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1509, '', '15mm smart lead black', '', '', 600, 'LC675-blk', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1531, '', 'Ant free small bowl green', '', '', 214, 'B245-grn', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1532, '', 'Ant free small bowl red', '', '', 214, 'B245-red', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1533, '', 'Bella #5 pooch perfume', '', '', 525, 'GR61', 63, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1537, '', '4 Dram vial with s/cap 200pc', '', '', 3175, 'C010', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1538, '', '6 Dram vial with s/cap 200pc', '', '', 3965, 'C011', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1539, '', '8 Dram vial with s/cap 200pc', '', '', 4256, 'C012', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1540, '', '12 Dram vial with s/cap 100pc', '', '', 2641, 'C013', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1541, '', '16 Dram vial with s/cap 100pc', '', '', 2980, 'C014', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1542, '', '20 Dram vial with s/cap 100pc', '', '', 4437, 'C015', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1543, '', '40 Dram vial with s/cap 50pc', '', '', 2916, 'C016', 65, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1544, '', '15x120cm bowl/bone embr lead', '', '', 432, 'LC150', 66, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1545, '', '20x120cm bowl/bone embr lead', '', '', 432, 'LC151', 66, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1546, '', '25x120cm bowl/bone embr lead', '', '', 432, 'LC152', 66, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1547, '', '14x40cm brushed collar pink', '', '', 660, 'LC450-pnk', 67, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1548, '', 'Budget basket giant', '', '', 2680, 'RNDBGNT-xxx', 68, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1549, '', 'Budget bell igloo large', '', '', 2600, 'BELBLGE-xxx', 69, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1553, '', 'Car harness small', '', '', 621, 'LC532', 71, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1554, '', 'Car harness medium', '', '', 711, 'LC534', 71, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1555, '', 'Car harness large', '', '', 867, 'LC536', 71, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1556, '', 'Car harness extra large', '', '', 990, 'LC538', 71, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1557, '', 'Car back seat cover 140x100cm', '', '', 1360, 'CVR02', 72, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1510, '', '15mm smart lead dark blue', '', '', 600, 'LC675-dkb', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1511, '', '15mm smart lead light blue', '', '', 600, 'LC675-ltb', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1512, '', '15mm smart lead purple', '', '', 600, 'LC675-pur', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1513, '', '15mm smart lead red', '', '', 600, 'LC675-red', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1514, '', '25mm smart lead black', '', '', 655, 'LC679-blk', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1515, '', '25mm smart lead dark blue', '', '', 655, 'LC679-dkb', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1516, '', '25mm smart lead light blue', '', '', 655, 'LC679-ltb', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1517, '', '25mm smart lead purple', '', '', 655, 'LC679-pur', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1518, '', '25mm smart lead red', '', '', 655, 'LC679-red', 61, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1521, '', '8" aluminium dog bowl', '', '', 600, 'B201', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1522, '', '9" aluminium dog bowl', '', '', 713, 'B202', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1523, '', '10" aluminium dog bowl', '', '', 825, 'B203', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1524, '', '11" aluminium dog bowl', '', '', 975, 'B204', 1, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1525, '', 'Ant free double diner blue', '', '', 216, 'B244-blu', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1526, '', 'Ant free double diner gold', '', '', 216, 'B244-gld', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1527, '', 'Ant free double diner green', '', '', 216, 'B244-grn', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1528, '', 'Ant free double diner red', '', '', 216, 'B244-red', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1529, '', 'Ant free small bowl blue', '', '', 214, 'B245-blu', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1530, '', 'Ant free small bowl gold', '', '', 214, 'B245-gld', 53, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1534, '', 'Holidaying with dogs book', '', '', 1060, 'BK01', 64, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1535, '', 'Walkies in Victoria book', '', '', 1060, 'BK02', 64, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1536, '', 'Holidaying with cats book', '', '', 1006, 'BK03', 64, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1558, '', 'Car back seat h/mock 140x150cm', '', '', 1960, 'CVR03', 72, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1559, '', 'Small carry cage', '', '', 3600, 'CC01', 73, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1560, '', 'Large carry cage', '', '', 4760, 'CC02', 73, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1561, '', 'Small wire carry cage', '', '', 3200, 'CC05', 73, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1568, '', 'H/duty litter tray lge blue', '', '', 330, 'B232-blu', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1569, '', 'H/duty litter tray lge green', '', '', 330, 'B232-grn', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1570, '', 'H/duty litter tray lge yellow', '', '', 330, 'B232-red', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1571, '', 'H/duty litter tray lge yellow', '', '', 330, 'B232-yel', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1572, '', 'H/duty litter tray lge blue', '', '', 290, 'B240-blu', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1573, '', 'H/duty litter tray lge green', '', '', 290, 'B240-grn', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1574, '', 'H/duty litter tray lge red', '', '', 290, 'B240-red', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1575, '', 'H/duty litter tray lge yellow', '', '', 290, 'B240-yel', 57, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1633, '', 'Fleece blanket 150x120cm blue', '', '', 600, 'BLAN01-blu', 58, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1634, '', 'Fleece blanket 150x120cm purpl', '', '', 600, 'BLAN01-pur', 58, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1635, '', 'Fleece blanket 150x120cm red', '', '', 600, 'BLAN01-red', 58, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1636, '', 'Fleece blanket 150x120cm yello', '', '', 600, 'BLAN01-yel', 58, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1732, '', 'Small plastic dog bowl blue', '', '', 75, 'B210-blu', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1733, '', 'Small plastic dog bowl green', '', '', 75, 'B210-grn', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1734, '', 'Small plastic dog bowl red', '', '', 75, 'B210-red', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1735, '', 'Small plastic dog bowl yellow', '', '', 75, 'B210-yel', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1736, '', 'Large plastic dog bowl blue', '', '', 195, 'B213-blu', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1737, '', 'Large plastic dog bowl green', '', '', 195, 'B213-grn', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1738, '', 'Large plastic dog bowl red', '', '', 195, 'B213-red', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1739, '', 'Large plastic dog bowl yellow', '', '', 195, 'B213-yel', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1740, '', 'Medium plastic dog bowl blue', '', '', 143, 'B220-blu', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1741, '', 'Medium plastic dog bowl green', '', '', 143, 'B220-grn', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1742, '', 'Medium plastic dog bowl red', '', '', 143, 'B220-red', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1743, '', 'Medium plastic dog bowl yellow', '', '', 143, 'B220-yel', 2, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1816, '', 'Deluxe raised dog bed large', '', '', 4945, 'DBDLGE', 59, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1817, '', 'Standard dog bed large', '', '', 3675, 'DBSLGE', 59, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1818, '', 'Standard dog bed small', '', '', 3293, 'DBSSML', 59, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1819, '', 'Deluxe dog bed sling large', '', '', 2117, 'DBDRSLGE', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1820, '', 'Deluxe dog bed sling small', '', '', 1650, 'DBDRSSML', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1821, '', 'Standard dog bed sling jumbo', '', '', 1824, 'DBSRSJUM', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1822, '', 'Standard dog bed sling large', '', '', 1680, 'DBSRSLGE', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1823, '', 'Standard dog bed sling medium', '', '', 1500, 'DBSRSMED', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1824, '', 'Standard dog bed sling mini', '', '', 1184, 'DBSRSMIN', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1825, '', 'Standard dog bed sling small', '', '', 1432, 'DBSRSSML', 60, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1562, '', 'Cat balls with bells 36pcs', '', '', 2000, 'LC763', 75, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1563, '', 'Cat bell & D ring nickel', '', '', 40, 'LC765', 76, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1564, '', 'Cat harness with lead black', '', '', 616, 'LC892-blk', 77, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1565, '', 'Cat harness with lead blue', '', '', 616, 'LC892-blu', 77, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1566, '', 'Cat harness with lead purple', '', '', 616, 'LC892-pur', 77, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1567, '', 'Cat harness with lead red', '', '', 616, 'LC892-red', 77, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1576, '', 'Cat repair 9 tubs', '', '', 2600, 'GR51', 78, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1577, '', 'Cat shaped plastic bowl blue', '', '', 296, 'B221-blu', 79, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1578, '', 'Cat shaped plastic bowl red', '', '', 296, 'B221-red', 79, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1579, '', 'Cat shaped plastic bowl yellow', '', '', 296, 'B221-yel', 79, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1580, '', 'Chain lead 2x120cm', '', '', 533, 'CL212', 80, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1581, '', 'Chain lead 3x120cm', '', '', 645, 'CL312', 80, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1582, '', 'Chain lead 2x120cm', '', '', 752, 'CL412', 80, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1583, '', 'Choker chain 2.0mmx30cm', '', '', 182, 'LC958', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1584, '', 'Choker chain 2.0mmx35cm', '', '', 188, 'LC959', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1585, '', 'Choker chain 2.0mmx40cm', '', '', 203, 'LC960', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1586, '', 'Choker chain 3.0mmx45cm', '', '', 244, 'LC961', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1587, '', 'Choker chain 3.0mmx50cm', '', '', 257, 'LC962', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1588, '', 'Choker chain 3.0mmx55cm', '', '', 272, 'LC963', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1589, '', 'Choker chain 3.0mmx60cm', '', '', 295, 'LC964', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1590, '', 'Choker chain 4.0mmx60cm', '', '', 392, 'LC965', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1591, '', 'Choker chain 4.0mmx65cm', '', '', 407, 'LC966', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1592, '', 'Choker chain 4.0mmx70cm', '', '', 423, 'LC967', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1593, '', 'Choker chain 4.8mmx75cm', '', '', 558, 'LC968', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1594, '', 'Choker chain 4.8mmx80cm', '', '', 599, 'LC969', 81, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1596, '', 'Ctn wb clr 25x50-70 black', '', '', 600, 'LC694-blk', 83, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1597, '', 'Ctn wb clr 25x50-70 blue', '', '', 600, 'LC694-blu', 83, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1598, '', 'Ctn wb clr 25x50-70 purple', '', '', 600, 'LC694-pur', 83, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1599, '', 'Ctn wb clr 25x50-70 red', '', '', 600, 'LC694-red', 83, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1600, '', 'Ctn wb lead 25x180 black', '', '', 800, 'LC700-blk', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1601, '', 'Ctn wb lead 25x180 blue', '', '', 800, 'LC700-blu', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1602, '', 'Ctn wb lead 25x180 purple', '', '', 800, 'LC700-pur', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1603, '', 'Ctn wb lead 25x180 red', '', '', 800, 'LC700-red', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1160, '', 'Ctn wb lead 25x120 black', '', '', 0, 'LC704-blk', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1605, '', 'Ctn wb lead 25x120 blue', '', '', 700, 'LC704-blu', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1606, '', 'Ctn wb lead 25x120 purple', '', '', 700, 'LC704-pur', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1607, '', 'Ctn wb lead 25x120 red', '', '', 700, 'LC704-red', 84, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1608, '', 'Deluxe basket giant', '', '', 5040, 'RNDDGNT-xxx', 85, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1609, '', 'Deluxe basket large', '', '', 3595, 'RNDDLGE-xxx', 85, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1610, '', 'Deluxe basket medium', '', '', 2940, 'RNDDMED-xxx', 85, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1611, '', 'Deluxe basket small', '', '', 2325, 'RNDDSML-xxx', 85, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1612, '', 'Deluxe basket xlarge', '', '', 4465, 'RNDDXLG-xxx', 85, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1613, '', 'Deluxe bell igloo large', '', '', 4200, 'BELDLGE-xxx', 86, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1614, '', 'Diamante 32cm collar green', '', '', 250, 'LC801-grn', 87, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1615, '', 'Diamante 32cm collar orange', '', '', 250, 'LC801-org', 87, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1616, '', 'Diamante 32cm collar yellow', '', '', 250, 'LC801-yel', 87, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1617, '', 'Diamante web cat collar black', '', '', 480, 'LC101-blk', 88, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1618, '', 'Diamante web cat collar blue', '', '', 480, 'LC101-blu', 88, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1619, '', 'Diamante web cat collar red', '', '', 480, 'LC101-red', 88, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1620, '', 'Dog & cat shampoo 500ml', '', '', 200, 'GR21', 89, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1621, '', 'Nylon dog muzzle small', '', '', 295, 'MUZ01', 90, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1622, '', 'Dog polish 9 tubs', '', '', 2600, 'GR53', 91, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1623, '', 'Double plastic diner blue', '', '', 83, 'B209-blu', 92, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1624, '', 'Double plastic diner green', '', '', 83, 'B209-grn', 92, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1625, '', 'Double plastic diner red', '', '', 83, 'B209-red', 92, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1626, '', 'Double plastic diner yellow', '', '', 83, 'B209-yel', 92, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1627, '', 'Dr Dog pet conditioner', '', '', 585, 'GR65', 93, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1628, '', 'Dr Dog pet shampoo', '', '', 925, 'GR64', 94, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1629, '', 'Dr Dog polish & brush set', '', '', 762, 'GR63', 95, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1630, '', 'Elastic cat collar with bell', '', '', 215, 'LC766', 96, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1631, '', 'Elastic cat collar with 2 bell', '', '', 252, 'LC767', 96, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1632, '', 'Full elastic paw cat collar', '', '', 215, 'LC799', 97, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1638, '', 'Dlxe futon cvr lge', '', '', 2621, 'FUTDCLGE-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1639, '', 'Dlxe futon cvr med', '', '', 2004, 'FUTDCMED-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1640, '', 'Dlxe futon cvr xlg', '', '', 3290, 'FUTDCXLG-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1641, '', 'Std futon cvr lge', '', '', 2121, 'FUTSCLGE-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1642, '', 'Std futon cvr med', '', '', 1504, 'FUTSCMED-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1643, '', 'Std futon cvr xlg', '', '', 2790, 'FUTSCXLG-xxx', 99, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1644, '', 'Premium futon lge', '', '', 4695, 'FUTPLGE-xxx', 100, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1645, '', 'Premium futon med', '', '', 3295, 'FUTPMED-xxx', 100, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1646, '', 'Premium futon xlg', '', '', 5695, 'FUTPXLG-xxx', 100, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1647, '', 'Graduate web cat collar blue', '', '', 315, 'LC102-blu', 101, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1648, '', 'Graduate web cat collar green', '', '', 315, 'LC102-grn', 101, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1649, '', 'Graduate web cat collar grey', '', '', 315, 'LC102-gry', 101, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1650, '', 'Graduate web cat collar purple', '', '', 315, 'LC102-pur', 101, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1651, '', 'Graduate web cat collar red', '', '', 315, 'LC102-red', 101, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1652, '', 'Heavy duty small square bowl', '', '', 255, 'B227', 102, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1653, '', 'Heavy duty small cat bowl', '', '', 165, 'B230', 102, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1654, '', 'Heavy duty double diner', '', '', 248, 'B231', 102, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1655, '', 'Heavy duty large square bowl', '', '', 495, 'B233', 102, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1656, '', 'Budget igloo large', '', '', 2024, 'IGLBLGE-xxx', 103, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1657, '', 'Budget igloo medium', '', '', 1663, 'IGLBMED-xxx', 103, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1658, '', 'Deluxe igloo large', '', '', 3400, 'IGLDLGE-xxx', 103, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1659, '', 'Standard igloo large', '', '', 2530, 'IGLSLGE-xxx', 103, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1660, '', 'Standard igloo medium', '', '', 2079, 'IGLS-MED-xxx', 103, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1661, '', 'Jungle cat collar brown', '', '', 445, 'LC103-brn', 104, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1662, '', 'Jungle cat collar tan', '', '', 445, 'LC103-tan', 104, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1663, '', 'Jungle cat collar white', '', '', 445, 'LC103-wte', 104, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1664, '', 'K-9 Fun Flyer blue', '', '', 450, 'FF01-blu', 105, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1665, '', 'K-9 Fun Flyer green', '', '', 450, 'FF01-grn', 105, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1666, '', 'K-9 Fun Flyer red', '', '', 450, 'FF01-red', 105, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1667, '', 'K-9 Fun Flyer yellow', '', '', 450, 'FF01-yel', 105, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1672, '', 'Dog kennel deluxe 300lt', '', '', 18000, '300D', 107, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1673, '', 'Dog kennel deluxe 600lt', '', '', 25000, '600D', 107, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1674, '', 'Dog kennel deluxe 900lt', '', '', 28000, '900D', 107, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1675, '', 'Kitten harness w/lead black', '', '', 616, 'LC890-blk', 108, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1676, '', 'Kitten harness w/lead blue', '', '', 616, 'LC890-blu', 108, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1677, '', 'Kitten harness w/lead purple', '', '', 616, 'LC890-pur', 108, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1678, '', 'Kitten harness w/lead red', '', '', 616, 'LC890-red', 108, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1679, '', '"4"" knot bone 20pc"', '', '', 1155, 'DC007', 109, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1680, '', '"6"" knot bone 20pc"', '', '', 1840, 'DC008', 109, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1681, '', '"8"" knot bone 10pc"', '', '', 1715, 'DC009', 109, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1682, '', '"14"" knot bone 10pc"', '', '', 3150, 'DC011', 109, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1683, '', '"16"" knot bone 6pc"', '', '', 1500, 'DC047', 109, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1684, '', 'Leather cat collar', '', '', 225, 'LC756', 110, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1685, '', '16mmx40cm leather collar rosco', '', '', 800, 'LC920', 111, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1686, '', '28mmx50cm leather collor rosco', '', '', 1200, 'LC921', 111, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1687, '', '32mmx60cm leather collar rosco', '', '', 1400, 'LC922', 111, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1688, '', '16mmx105cm leather lead rosco', '', '', 1500, 'LC923', 111, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1689, '', '16mmx105cm lth swivel ld rosco', '', '', 1800, 'LC924', 111, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1690, '', 'Cat litter scoop blue', '', '', 120, 'B242-blu', 112, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1691, '', 'H/D pooper scooper pan & scoop', '', '', 1386, 'B243', 112, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1694, '', 'Deluxe dacron mat med', '', '', 1230, 'MATDDMED-xxx', 113, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1695, '', 'Deluxe dacron mat small', '', '', 1009, 'MATDDSML-xxx', 113, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1696, '', 'Standard foam mat jumbo', '', '', 1290, 'MATSFJUM-xxx', 114, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1697, '', 'Standard foam mat large', '', '', 1000, 'MATSFLGE-xxx', 114, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1698, '', 'Standard foam mat mini', '', '', 800, 'MATSFMIN-xxx', 114, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1699, '', 'Dacron mat suit 60lt kennel', '', '', 496, 'MATSD060-xxx', 115, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1700, '', 'Dacron mat suit 200lt kennel', '', '', 943, 'MATSD200-xxx', 115, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1701, '', 'Dacron mat suit 300lt kennel', '', '', 988, 'MATSD300-xxx', 115, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1702, '', 'Dacron mat suit 600lt kennel', '', '', 1210, 'MATSD600-xxx', 115, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1703, '', 'Dacron mat suit 900lt kennel', '', '', 1644, 'MATSD900-xxx', 115, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1704, '', 'Sherpa dacron mat', '', '', 2295, 'MATWDLGE-xxx', 116, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1705, '', 'Sherpa dacron mat', '', '', 1995, 'MATWDMED-xxx', 116, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1706, '', 'Sherpa dacron mat', '', '', 1295, 'MATWDSML-xxx', 116, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1707, '', 'Standard dacron mat large', '', '', 1644, 'MATSDLGE-xxx', 152, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1708, '', 'Standard dacron mat medium', '', '', 988, 'MATSDMED-xxx', 152, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1709, '', 'Standard dacron mat small', '', '', 859, 'MATSDSML-xxx', 152, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1713, '', 'Metallic cat collar', '', '', 196, 'LC621', 119, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1714, '', 'Pastel cat collar blue', '', '', 500, 'LC893-blu', 120, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1715, '', 'Pastel cat collar green', '', '', 500, 'LC893-grn', 120, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1716, '', 'Pastel cat collar purple', '', '', 500, 'LC893-pur', 120, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1717, '', 'Patent leather cat collar red', '', '', 295, 'LC888-red', 121, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1718, '', 'Patent puppy collar red', '', '', 224, 'LC627-red', 122, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1720, '', 'Plain lth clr 12x35 black', '', '', 220, 'LC540-blk', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1721, '', 'Plain lth clr 12x35 red', '', '', 220, 'LC540-red', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1722, '', 'Plain lth clr 12x35 tan', '', '', 220, 'LC540-tan', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1723, '', 'Plain lth clr 18x45 black', '', '', 320, 'LC542-blk', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1724, '', 'Plain lth clr 18x45 red', '', '', 320, 'LC542-red', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1725, '', 'Plain lth clr 18x45 tan', '', '', 320, 'LC542-tan', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1726, '', 'Plain lth clr 25x55 black', '', '', 440, 'LC544-blk', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1727, '', 'Plain lth clr 25x55 red', '', '', 440, 'LC544-red', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1728, '', 'Plain lth clr 25x55 tan', '', '', 440, 'LC544-tan', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1729, '', 'Plain lth clr 30x65 black', '', '', 680, 'LC546-blk', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1730, '', 'Plain lth clr 30x65 red', '', '', 680, 'LC546-red', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1731, '', 'Plain lth clr 30x65 tan', '', '', 680, 'LC546-tan', 124, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1749, '', 'Premium basket large', '', '', 3795, 'RNDPLGE-xxx', 126, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1750, '', 'Premium basket medium', '', '', 3095, 'RNDPMED-xxx', 126, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1751, '', 'Premium basket small', '', '', 2495, 'RNDPSML-xxx', 126, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1752, '', 'Premium basket extra large', '', '', 4797, 'RNDPXLGE-xxx', 126, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1753, '', '"4"" pressed bone 30pc"', '', '', 1445, 'DC002', 127, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1754, '', '"6"" pressed bone 20pc"', '', '', 2085, 'DC003', 127, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1755, '', '"12"" pressed bone 10pc"', '', '', 3815, 'DC006', 127, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1756, '', 'Printed webbed lead 120cm', '', '', 435, 'LC661', 128, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1757, '', 'PVC lead 120cm yellow', '', '', 1080, 'LC970-yel', 129, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1758, '', 'PVC collar 20x45 red', '', '', 720, 'LC971-red', 129, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1759, '', 'PVC collar 20x65 yellow', '', '', 810, 'LC972-yel', 129, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1760, '', 'PVC collar 20x35 yellow', '', '', 625, 'LC973-yel', 129, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1762, '', 'S rfl adj cat cat collar black', '', '', 392, 'LC752-blk', 131, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1763, '', 'S rfl adj cat cat collar dk bl', '', '', 392, 'LC752-dkb', 131, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1764, '', 'S rfl adj cat cat collar purpl', '', '', 392, 'LC752-pur', 131, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1765, '', 'S rfl adj cat cat collar red', '', '', 392, 'LC752-red', 131, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1766, '', 'S rfl clr 10x20-30 black', '', '', 385, 'LC502-blk', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1767, '', 'S rfl clr 10x20-30 dark blue', '', '', 385, 'LC502-dkb', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1768, '', 'S rfl clr 10x20-30 purple', '', '', 385, 'LC502-pur', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1769, '', 'S rfl clr 10x20-30 red', '', '', 385, 'LC502-red', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1770, '', 'S rfl clr 20x25-45 black', '', '', 462, 'LC504-blk', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1771, '', 'S rfl clr 20x25-45 dark blue', '', '', 462, 'LC504-dkb', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1772, '', 'S rfl clr 20x25-45 purple', '', '', 462, 'LC504-pur', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1773, '', 'S rfl clr 20x25-45 red', '', '', 462, 'LC504-red', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1774, '', 'S rfl clr 25x50-70 black', '', '', 644, 'LC508-blk', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1775, '', 'S rfl clr 25x50-70 dark blue', '', '', 644, 'LC508-dkb', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1776, '', 'S rfl clr 25x50-70 purple', '', '', 644, 'LC508-pur', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1777, '', 'S rfl clr 25x50-70 red', '', '', 644, 'LC508-red', 132, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1778, '', 'S rfl ld 10x120 black', '', '', 553, 'LC510-blk', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1779, '', 'S rfl ld 10x120 dark blue', '', '', 553, 'LC510-dkb', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1780, '', 'S rfl ld 10x120 purple', '', '', 553, 'LC510-pur', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1781, '', 'S rfl ld 10x120 red', '', '', 553, 'LC510-red', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1782, '', 'S rfl ld 20x120 black', '', '', 756, 'LC512-blk', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1783, '', 'S rfl ld 20x120 dark blue', '', '', 756, 'LC512-dkb', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1784, '', 'S rfl ld 20x120 purple', '', '', 756, 'LC512-pur', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1785, '', 'S rfl ld 20x120 red', '', '', 756, 'LC512-red', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1786, '', 'S rfl ld 25x120 black', '', '', 756, 'LC514-blk', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1787, '', 'S rfl ld 25x120 dark blue', '', '', 756, 'LC514-dkb', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1788, '', 'S rfl ld 25x120 purple', '', '', 756, 'LC514-pur', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1789, '', 'S rfl ld 25x120 red', '', '', 756, 'LC514-red', 133, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1791, '', 'Spill proof water bowl blue', '', '', 350, 'B241-blu', 134, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1792, '', 'Spill proof water bowl gold', '', '', 350, 'B241-gld', 134, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1793, '', 'Spill proof water bowl green', '', '', 350, 'B241-grn', 134, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1794, '', 'Spill proof water bowl red', '', '', 350, 'B241-red', 134, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1795, '', 'Standard basket giant', '', '', 3600, 'RNDSGNT-xxx', 135, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1796, '', 'Standard basket large', '', '', 2300, 'RNDSLGE-xxx', 135, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1797, '', 'Standard basket medium', '', '', 1900, 'RNDSMED-xxx', 135, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1798, '', 'Standard basket small', '', '', 1500, 'RNDSSML-xxx', 135, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1799, '', 'Standard basket xlarge', '', '', 3100, 'RNDSXLG-xxx', 135, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1800, '', 'Standard bell igloo large', '', '', 3254, 'BELSLGE-xxx', 136, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1805, '', 'Suede cat collar blue', '', '', 215, 'LC754-blu', 138, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1806, '', 'Suede cat collar purple', '', '', 215, 'LC754-pur', 138, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1807, '', 'Suede cat collar red', '', '', 215, 'LC754-red', 138, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1808, '', 'Suede puppy clr 10x30 black', '', '', 210, 'LC620-blk', 139, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1809, '', 'Suede puppy clr 10x30 blue', '', '', 210, 'LC620-blu', 139, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1810, '', 'Suede puppy clr 10x30 purple', '', '', 210, 'LC620-pur', 139, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1811, '', 'Suede puppy clr 10x30 red', '', '', 210, 'LC620-red', 139, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1812, '', 'Suede puppy ld 10x105cm black', '', '', 230, 'LC624-blk', 140, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1813, '', 'Suede puppy ld 10x105cm blue', '', '', 230, 'LC624-blu', 140, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1814, '', 'Suede puppy ld 10x105cm purple', '', '', 230, 'LC624-pur', 140, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1815, '', 'Suede puppy ld 10x105cm red', '', '', 230, 'LC624-red', 140, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1826, '', 'Dlxe untangling dog comb black', '', '', 250, 'GR62-blk', 141, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1827, '', 'Dlxe untangling dog comb blue', '', '', 250, 'GR62-blu', 141, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1828, '', 'Velvet cat collar black', '', '', 480, 'LC100-blk', 142, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1829, '', 'Velvet cat collar blue', '', '', 480, 'LC100-blu', 142, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1830, '', 'Velvet cat collar red', '', '', 480, 'LC100-red', 142, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1831, '', 'Dog walk leash bag', '', '', 495, 'HP009', 143, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1832, '', 'Small walking harness black', '', '', 0, 'LC792-blk', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1833, '', 'Small walking harness dk blue', '', '', 0, 'LC792-dkb', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1834, '', 'Small walking harness purple', '', '', 0, 'LC792-pur', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1835, '', 'Small walking harness red', '', '', 0, 'LC792-red', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1836, '', 'Medium walking harness black', '', '', 0, 'LC794-blk', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1837, '', 'Medium walking harness dk blue', '', '', 0, 'LC794-dkb', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1838, '', 'Medium walking harness purple', '', '', 0, 'LC794-pur', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1839, '', 'Medium walking harness red', '', '', 0, 'LC794-red', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1840, '', 'Large walking harness black', '', '', 0, 'LC796-blk', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1841, '', 'Large walking harness dk blue', '', '', 0, 'LC796-dkb', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1842, '', 'Large walking harness purple', '', '', 0, 'LC796-pur', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1843, '', 'Large walking harness red', '', '', 0, 'LC796-red', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1844, '', 'Xlarge walking harness black', '', '', 0, 'LC798-blk', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1845, '', 'Xlarge walking harness dk blue', '', '', 0, 'LC798-dkb', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1846, '', 'Xlarge walking harness purple', '', '', 0, 'LC798-pur', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1847, '', 'Xlarge walking harness red', '', '', 0, 'LC798-red', 144, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1848, '', 'WB adj cat collar black', '', '', 293, 'LC764-blk', 145, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1849, '', 'WB adj cat collar dark blue', '', '', 293, 'LC764-dkb', 145, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1850, '', 'WB adj cat collar light blue', '', '', 293, 'LC764-ltb', 145, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1851, '', 'WB adj cat collar purple', '', '', 293, 'LC764-pur', 145, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1852, '', 'WB adj cat collar red', '', '', 293, 'LC764-red', 145, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1853, '', 'WB col adj 20x25-45 black', '', '', 449, 'LC630-blk', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1854, '', 'WB col adj 20x25-45 dark blue', '', '', 449, 'LC630-dkb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1855, '', 'WB col adj 20x25-45 light blue', '', '', 449, 'LC630-ltb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1856, '', 'WB col adj 20x25-45 purple', '', '', 449, 'LC630-pur', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1857, '', 'WB col adj 20x25-45 red', '', '', 449, 'LC630-red', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1858, '', 'WB col adj 25x50-70 black', '', '', 486, 'LC632-blk', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1859, '', 'WB col adj 25x50-70 dark blue', '', '', 486, 'LC632-dkb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1860, '', 'WB col adj 25x50-70 light blue', '', '', 486, 'LC632-ltb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1861, '', 'WB col adj 25x50-70 purple', '', '', 486, 'LC632-pur', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1862, '', 'WB col adj 25x50-70 red', '', '', 486, 'LC632-red', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1863, '', 'WB col adj 10x20-30 black', '', '', 318, 'LC634-blk', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1864, '', 'WB col adj 10x20-30 dark blue', '', '', 318, 'LC634-dkb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1865, '', 'WB col adj 10x20-30 light blue', '', '', 318, 'LC634-ltb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1866, '', 'WB col adj 10x20-30 purple', '', '', 318, 'LC634-pur', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1867, '', 'WB col adj 10x20-30 red', '', '', 318, 'LC634-red', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1868, '', 'WB col adj 15x25-45 black', '', '', 421, 'LC636-blk', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1869, '', 'WB col adj 15x25-45 dark blue', '', '', 421, 'LC636-dkb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1870, '', 'WB col adj 15x25-45 light blue', '', '', 421, 'LC636-ltb', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1871, '', 'WB col adj 15x25-45 purple', '', '', 421, 'LC636-pur', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1872, '', 'WB col adj 15x25-45 red', '', '', 421, 'LC636-red', 146, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1873, '', 'WB dbler lead 25x75 black', '', '', 691, 'LC690-blk', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1874, '', 'WB dbler lead 25x75 dark blue', '', '', 691, 'LC690-dkb', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1875, '', 'WB dbler lead 25x75 light blue', '', '', 691, 'LC690-ltb', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1876, '', 'WB dbler lead 25x75 purple', '', '', 691, 'LC690-pur', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1877, '', 'WB dbler lead 25x75red', '', '', 691, 'LC690-red', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1878, '', 'WB dbler lead 15x75 black', '', '', 650, 'LC691-blk', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1879, '', 'WB dbler lead 15x75 dark blue', '', '', 650, 'LC691-dkb', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1880, '', 'WB dbler lead 15x75 light blue', '', '', 650, 'LC691-ltb', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1881, '', 'WB dbler lead 15x75 purple', '', '', 650, 'LC691-pur', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1882, '', 'WB dbler lead 15x75 red', '', '', 650, 'LC691-red', 147, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1883, '', 'WB lead 10x120 black', '', '', 299, 'LC663-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1884, '', 'WB lead 10x120 dark blue', '', '', 299, 'LC663-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1885, '', 'WB lead 10x120 light blue', '', '', 299, 'LC663-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1886, '', 'WB lead 10x120 purple', '', '', 299, 'LC663-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1887, '', 'WB lead 10x120 red', '', '', 299, 'LC663-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1888, '', 'WB lead 10x180 black', '', '', 329, 'LC664-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1889, '', 'WB lead 10x180 dark blue', '', '', 329, 'LC664-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1890, '', 'WB lead 10x180 light blue', '', '', 329, 'LC664-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1891, '', 'WB lead 10x180 purple', '', '', 329, 'LC664-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1892, '', 'WB lead 10x180 red', '', '', 329, 'LC664-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1893, '', 'WB lead 15x120 black', '', '', 365, 'LC667-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1894, '', 'WB lead 15x120 dark blue', '', '', 365, 'LC667-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1895, '', 'WB lead 15x120 light blue', '', '', 365, 'LC667-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1896, '', 'WB lead 15x120 purple', '', '', 365, 'LC667-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1897, '', 'WB lead 15x120 red', '', '', 365, 'LC667-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1898, '', 'WB lead 15x180 black', '', '', 411, 'LC668-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1899, '', 'WB lead 15x180 dark blue', '', '', 411, 'LC668-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1900, '', 'WB lead 15x180 light blue', '', '', 411, 'LC668-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1901, '', 'WB lead 15x180 purple', '', '', 411, 'LC668-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1902, '', 'WB lead 15x180 red', '', '', 411, 'LC668-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1903, '', 'WB lead 20x120 black', '', '', 393, 'LC672-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1904, '', 'WB lead 20x120 dark blue', '', '', 393, 'LC672-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1905, '', 'WB lead 20x120 light blue', '', '', 393, 'LC672-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1906, '', 'WB lead 20x120 purple', '', '', 393, 'LC672-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1907, '', 'WB lead 20x120 red', '', '', 393, 'LC672-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1908, '', 'WB lead 25x45 black', '', '', 355, 'LC674-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1909, '', 'WB lead 25x45 dark blue', '', '', 355, 'LC674-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1910, '', 'WB lead 25x45 light blue', '', '', 355, 'LC674-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1911, '', 'WB lead 25x45 purple', '', '', 355, 'LC674-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1912, '', 'WB lead 25x45 red', '', '', 355, 'LC674-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1913, '', 'WB lead 25x90 black', '', '', 402, 'LC676-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1914, '', 'WB lead 25x90 dark blue', '', '', 402, 'LC676-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1915, '', 'WB lead 25x90 light blue', '', '', 402, 'LC676-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1916, '', 'WB lead 25x90 purple', '', '', 402, 'LC676-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1917, '', 'WB lead 25x90 red', '', '', 405, 'LC676-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1918, '', 'WB lead 25x120 black', '', '', 449, 'LC677-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1919, '', 'WB lead 25x120 dark blue', '', '', 449, 'LC677-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1920, '', 'WB lead 25x120 light blue', '', '', 449, 'LC677-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1921, '', 'WB lead 25x120 purple', '', '', 449, 'LC677-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1922, '', 'WB lead 25x120 red', '', '', 449, 'LC677-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1923, '', 'WB lead 25x180 black', '', '', 514, 'LC678-blk', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1924, '', 'WB lead 25x180 dark blue', '', '', 514, 'LC678-dkb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1925, '', 'WB lead 25x180 light blue', '', '', 514, 'LC678-ltb', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1926, '', 'WB lead 25x180 purple', '', '', 514, 'LC678-pur', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1927, '', 'WB lead 25x180 red', '', '', 514, 'LC678-red', 148, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1928, '', 'WB metal bkle cat collar black', '', '', 293, 'LC762-blk', 149, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1929, '', 'WB metal bkle cat collar dk bl', '', '', 293, 'LC762-dkb', 149, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1930, '', 'WB metal bkle cat collar lt bl', '', '', 293, 'LC762-ltb', 149, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1931, '', 'WB metal bkle cat collar purpl', '', '', 293, 'LC762-pur', 149, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1932, '', 'WB metal bkle cat collar red', '', '', 293, 'LC762-red', 149, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1996, '', '25cm skivvy red', '', '', 900, 'K9S25-red', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1992, '', '20cm skivvy purple', '', '', 900, 'K9S20-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1995, '', '25cm skivvy purple', '', '', 900, 'K9S25-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1998, '', '30cm skivvy purple', '', '', 900, 'K9S30-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2001, '', '35cm skivvy purple', '', '', 990, 'K9S35-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2004, '', '40cm skivvy purple', '', '', 1080, 'K9S40-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2007, '', '45cm skivvy purple', '', '', 1170, 'K9S45-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2010, '', '50cm skivvy purple', '', '', 1890, 'K9S50-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2016, '', 'Medium skivvy purple', '', '', 2790, 'K9SMED-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2013, '', 'Large skivvy purple', '', '', 2790, 'K9SLGE-pur', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1991, '', '20cm skivvy blue', '', '', 900, 'K9S20-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1994, '', '25cm skivvy blue', '', '', 900, 'K9S25-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1997, '', '30cm skivvy blue', '', '', 900, 'K9S30-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2000, '', '35cm skivvy blue', '', '', 990, 'K9S35-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2003, '', '40cm skivvy blue', '', '', 1080, 'K9S40-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2006, '', '45cm skivvy blue', '', '', 1170, 'K9S45-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2009, '', '50cm skivvy blue', '', '', 1890, 'K9S50-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2015, '', 'Medium skivvy blue', '', '', 2790, 'K9SMED-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2019, '', 'Cat garden', '', '', 642, 'GR60', 166, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2012, '', 'Large skivvy blue', '', '', 2790, 'K9SLGE-blu', 19, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1712, '', 'Melamine bowl w/r rim red', '', '', 250, 'B273-red', 153, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1989, '', 'Blue knitted dog jumper small', '', '', 2500, 'K9JSML-blu', 18, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1710, '', 'Melamine bowl w/r rim blue', '', '', 250, 'B273-blu', 153, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (1965, '', '55cm oil cloth coat buckle', '', '', 2666, 'K9C55OB', 16, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2028, '', 'Cat scratch pad mouse design', '', '', 450, 'CT016', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2029, '', 'Plush springing cat toy 4pc', '', '', 668, 'CT018', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2030, '', 'Moveable mouse cat toy', '', '', 195, 'CT019', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2031, '', 'Small fur gry rats on crd 12pc', '', '', 2190, 'CT025', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2032, '', 'Shake & squeak cat toy pk 18pc', '', '', 4050, 'CT026', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2033, '', 'Fluffy tail catnip toy 4pc', '', '', 516, 'CT027', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2034, '', 'Cat tnl lge w/2 pop outs black', '', '', 1695, 'CT028-blk', 156, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2035, '', 'Cat tnl lge w/2 pop outs blue', '', '', 1695, 'CT028-blu', 156, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2036, '', 'Cat tnl lge w/2 pop outs red', '', '', 1695, 'CT028-red', 156, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2037, '', 'Cat teasers on stick 3pc', '', '', 675, 'CT029', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2038, '', 'Pendulum cat toy 3pc', '', '', 402, 'CT030', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2039, '', 'Sponge star ball small', '', '', 250, 'DT010', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2040, '', 'Bone shaped tennis ball', '', '', 330, 'DT013', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2041, '', 'Jumbo tennis  ball 10cm', '', '', 350, 'DT018', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2042, '', 'Fleecy squeaky bone (Sherpa)', '', '', 350, 'DT019', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2043, '', 'Rope with 2 tennis balls', '', '', 350, 'DT021', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2044, '', 'Plush coloured star with 6 sq', '', '', 450, 'DT022', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2045, '', 'Dog chew rope w/3 sausages', '', '', 350, 'DT023', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2046, '', 'Back saver grab & throw stick', '', '', 495, 'DT027', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2047, '', 'Pet toy dispenser large', '', '', 495, 'DT029', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2048, '', 'Heavy duty life ring pull toy', '', '', 445, 'DT030', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2049, '', 'Odd shape tennis ball', '', '', 330, 'DT031', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2050, '', 'Spike dog squeak ball 24pc', '', '', 3600, 'DT036', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2051, '', 'Solid rubber ball with rope', '', '', 225, 'DT037', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2052, '', 'Dog beer can 15pc Squeaky', '', '', 2500, 'DT041', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2053, '', 'Dog cat sheep squeaky toy 3pc', '', '', 660, 'DT042', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2054, '', 'Jumbo rainbow sqky animal 4pc', '', '', 1160, 'DT044', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2055, '', 'Play n chase ball', '', '', 575, 'DT049', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2056, '', 'Puppy companion pet toy 6pc', '', '', 1206, 'DT052', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2057, '', '"Blood hound', '', '', 490, 'DT053', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2058, '', 'Tyre tuff buddies dog toy 3pc', '', '', 585, 'DT054', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2059, '', 'Astd h/ware buddies w sqk 6pc', '', '', 1170, 'DT055', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2060, '', 'Mint fresh dog breath ball', '', '', 680, 'DT056', 155, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2065, '', '6x20mm munchy sticks 50pc', '', '', 1314, 'DC030', 159, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2066, '', '"1"" munchy bones 100pc"', '', '', 892, 'DC032', 160, 0, 0, 0, 0, 0, 0);
REPLACE INTO `products` VALUES (2077, '', 'Munchy twist stixs 30pc', '', '', 500, 'DC048', 159, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `type`
-- 

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `typeid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `display_format` char(1) NOT NULL default '',
  `aus_made` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`typeid`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `type`
-- 

REPLACE INTO `type` VALUES (1, 'Aluminium Dog Bowl', '', 'v', 0);
REPLACE INTO `type` VALUES (2, 'Plastic bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (15, 'Dog Coats - Oilskin (velcro)', '', 'v', 1);
REPLACE INTO `type` VALUES (16, 'Dog Coats - Oilskin (snap buckles)', '', 'v', 1);
REPLACE INTO `type` VALUES (14, 'Dog Coats - Flannelette', '', 'h', 1);
REPLACE INTO `type` VALUES (17, 'Dog Coats - Polar Fleece', '', 'h', 1);
REPLACE INTO `type` VALUES (18, 'Small Dog Jumpers - Hand Knitted', '', 'h', 1);
REPLACE INTO `type` VALUES (19, 'Dog Skivvies', '', 'h', 1);
REPLACE INTO `type` VALUES (20, 'Embossed Stainless Steel Dog Bowls with Rubber Rim', '', 'v', 0);
REPLACE INTO `type` VALUES (21, 'Rawhide Basted Pringles', '', 'v', 0);
REPLACE INTO `type` VALUES (22, 'Rawhide Puppy Chips', '', 'v', 0);
REPLACE INTO `type` VALUES (23, 'Carob Dog Chokies', '', 'v', 0);
REPLACE INTO `type` VALUES (24, 'Pork Rolls', '', 'v', 0);
REPLACE INTO `type` VALUES (25, 'Rawhide Puppy Chips - Beef Basted', '', 'v', 0);
REPLACE INTO `type` VALUES (26, 'Lamb Neck', '', 'v', 0);
REPLACE INTO `type` VALUES (27, 'Bull Sticks', '', 'v', 0);
REPLACE INTO `type` VALUES (28, 'Rawhide Retriever Sticks - 8" Basted Loose Rolled', '', 'v', 0);
REPLACE INTO `type` VALUES (29, 'Porkhide Drumsticks', '', 'v', 0);
REPLACE INTO `type` VALUES (30, 'Porkhide Knotted Bones', '', 'v', 0);
REPLACE INTO `type` VALUES (31, 'Porkhide Sausage Rolls', '', 'v', 0);
REPLACE INTO `type` VALUES (32, 'Rawhide Twist Stick - 10mm', '', 'v', 0);
REPLACE INTO `type` VALUES (33, 'Turkey Jerky', '', 'v', 0);
REPLACE INTO `type` VALUES (34, 'Dog Chokies - Yoghurt Drops', '', 'v', 0);
REPLACE INTO `type` VALUES (35, 'Dried Liver', '', 'v', 0);
REPLACE INTO `type` VALUES (36, 'Smoked Cows Ears', '', 'v', 0);
REPLACE INTO `type` VALUES (37, 'Smoked Pigs Ears', '', 'v', 0);
REPLACE INTO `type` VALUES (38, 'Rawhide Retriever Sticks - 5"', '', 'v', 0);
REPLACE INTO `type` VALUES (39, 'Rawhide Retriever Sticks - 10"', '', 'v', 0);
REPLACE INTO `type` VALUES (40, 'Roo Sausages', '', 'v', 0);
REPLACE INTO `type` VALUES (41, 'Premium grade Beef Jerky', '', 'v', 0);
REPLACE INTO `type` VALUES (42, 'Smoked Pig Trotters', '', 'v', 0);
REPLACE INTO `type` VALUES (43, 'Beef Rattles', '', 'v', 0);
REPLACE INTO `type` VALUES (44, 'Smoked Pig Snouts', '', 'v', 0);
REPLACE INTO `type` VALUES (45, 'Beef Crackers', '', 'v', 0);
REPLACE INTO `type` VALUES (46, 'Femur / Clod Bones', '', 'v', 0);
REPLACE INTO `type` VALUES (47, 'Roo Tails', '', 'v', 0);
REPLACE INTO `type` VALUES (166, 'Cat Garden', '', 'v', 0);
REPLACE INTO `type` VALUES (49, 'Kennel - Drum Style', '', 'v', 1);
REPLACE INTO `type` VALUES (50, 'Fold-up Water Bowl', '', 'v', 0);
REPLACE INTO `type` VALUES (52, 'Plastic Bowl - Double Diner', '', 'h', 0);
REPLACE INTO `type` VALUES (53, 'Ant Free Bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (54, 'Carry Cage Bowl', '', 'v', 0);
REPLACE INTO `type` VALUES (55, 'Drink Bowl with wire clips', '', 'v', 0);
REPLACE INTO `type` VALUES (56, 'Melamine Dog Bowl with Rubber Ring', '', 'h', 0);
REPLACE INTO `type` VALUES (57, 'Cat Litter Tray - Heavy Duty', '', 'h', 0);
REPLACE INTO `type` VALUES (58, 'Fleece Blanket', '', 'h', 0);
REPLACE INTO `type` VALUES (59, 'Trampoline Bed', '', 'v', 1);
REPLACE INTO `type` VALUES (60, 'Trampoline Slings', '', 'v', 1);
REPLACE INTO `type` VALUES (61, 'Smart Leads', '', 'h', 0);
REPLACE INTO `type` VALUES (63, 'Bella Pooch Perfume', '', 'h', 0);
REPLACE INTO `type` VALUES (64, 'Books', '', 'v', 0);
REPLACE INTO `type` VALUES (65, 'Bottles & Vials', '', 'v', 0);
REPLACE INTO `type` VALUES (66, 'bowl/bone embr', '', 'v', 0);
REPLACE INTO `type` VALUES (67, 'Brushed Collar', '', 'h', 0);
REPLACE INTO `type` VALUES (68, 'Budget Baskets', '', 'h', 1);
REPLACE INTO `type` VALUES (69, 'Budget Bell Igloos', '', 'h', 1);
REPLACE INTO `type` VALUES (70, 'Cage Bowls', '', 'v', 0);
REPLACE INTO `type` VALUES (71, 'Car Harness', '', 'v', 0);
REPLACE INTO `type` VALUES (72, 'Car Seat Protectors', '', 'v', 0);
REPLACE INTO `type` VALUES (73, 'Carry Cages', '', 'v', 0);
REPLACE INTO `type` VALUES (74, 'Books', '', 'v', 0);
REPLACE INTO `type` VALUES (75, 'Cat Balls with Bells', '', '', 0);
REPLACE INTO `type` VALUES (76, 'Cat Bell & D Ring', '', '', 0);
REPLACE INTO `type` VALUES (77, 'Cat Harnesses', '', 'h', 0);
REPLACE INTO `type` VALUES (78, 'Cat Repair', '', '', 0);
REPLACE INTO `type` VALUES (79, 'Cat Shaped Plastic Bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (80, 'Chain Leads', '', '', 0);
REPLACE INTO `type` VALUES (81, 'Choker Chains', '', '', 0);
REPLACE INTO `type` VALUES (82, 'Clod/femur bones', '', '', 0);
REPLACE INTO `type` VALUES (83, 'Ctn wb Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (84, 'Ctn wb lead', '', 'h', 0);
REPLACE INTO `type` VALUES (85, 'Deluxe Baskets', '', 'h', 0);
REPLACE INTO `type` VALUES (86, 'Deluxe Bell Igloo', '', 'h', 0);
REPLACE INTO `type` VALUES (87, 'Diamante Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (88, 'Diamante web cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (89, 'Dog & Cat Shampoo', '', '', 0);
REPLACE INTO `type` VALUES (90, 'Dog Muzzle - Nylon', '', '', 0);
REPLACE INTO `type` VALUES (91, 'Dog Polish', '', '', 0);
REPLACE INTO `type` VALUES (92, 'Double Plastic Bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (93, 'Dr Dog Pet Conditioner', '', '', 0);
REPLACE INTO `type` VALUES (94, 'Dr Dog Pet Shampoo', '', '', 0);
REPLACE INTO `type` VALUES (95, 'Dr Dog Polish and Brush Set', '', '', 0);
REPLACE INTO `type` VALUES (96, 'Elastic Cat Collars with Bell', '', '', 0);
REPLACE INTO `type` VALUES (97, 'Elastic Paw Cat Collars', '', '', 0);
REPLACE INTO `type` VALUES (98, 'Fold-Up Bowls', '', '', 0);
REPLACE INTO `type` VALUES (99, 'Futon Covers', '', 'h', 0);
REPLACE INTO `type` VALUES (100, 'Futons', '', 'h', 0);
REPLACE INTO `type` VALUES (101, 'Graduate web cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (102, 'Heavey Duty Plastic Bowls', '', '', 0);
REPLACE INTO `type` VALUES (103, 'Igloos', '', 'h', 0);
REPLACE INTO `type` VALUES (104, 'Jungle cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (105, 'K-9 Fun Flyer', '', 'h', 0);
REPLACE INTO `type` VALUES (106, 'Kennel-Drum Style', '', '', 0);
REPLACE INTO `type` VALUES (107, 'Kennel-Square Style', '', '', 0);
REPLACE INTO `type` VALUES (108, 'Kitten Harnesses', '', 'h', 0);
REPLACE INTO `type` VALUES (109, 'Knot Bones', '', '', 0);
REPLACE INTO `type` VALUES (110, 'Leather Cat Collars', '', '', 0);
REPLACE INTO `type` VALUES (111, 'Rosco Leads and Collars', '', '', 0);
REPLACE INTO `type` VALUES (112, 'Litter Accessories', '', 'v', 0);
REPLACE INTO `type` VALUES (113, 'Mats - Deluxe Dacron', '', 'h', 0);
REPLACE INTO `type` VALUES (114, 'Mats - Foam', '', 'h', 0);
REPLACE INTO `type` VALUES (115, 'Mats - Kennel (Dacron)', '', 'h', 0);
REPLACE INTO `type` VALUES (116, 'Mats - Sherpa/Dacron', '', 'h', 0);
REPLACE INTO `type` VALUES (117, 'Mats - Standard Dacron', '', 'h', 0);
REPLACE INTO `type` VALUES (119, 'Metalic Cat Collar', '', '', 0);
REPLACE INTO `type` VALUES (120, 'Pastel Cat Collar', '', 'h', 0);
REPLACE INTO `type` VALUES (121, 'Patent Leather Cat Collars', '', '', 0);
REPLACE INTO `type` VALUES (122, 'Patent Puppy Collar', '', '', 0);
REPLACE INTO `type` VALUES (123, 'Pig Snouts', '', '', 0);
REPLACE INTO `type` VALUES (124, 'Plain Leather Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (125, 'Porkhide Retrievers', '', '', 0);
REPLACE INTO `type` VALUES (126, 'Premium Baskets', '', 'h', 0);
REPLACE INTO `type` VALUES (127, 'Pressed Bones', '', '', 0);
REPLACE INTO `type` VALUES (128, 'Printed Webbed Lead', '', '', 0);
REPLACE INTO `type` VALUES (129, 'PVC Leads and Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (130, 'Rawhide Twist Stick', '', '', 0);
REPLACE INTO `type` VALUES (131, 'S rfl adj cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (132, 'Safety Reflective Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (133, 'S rfl Lead', '', 'h', 0);
REPLACE INTO `type` VALUES (134, 'Spill Proof Bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (135, 'Standard Baskets', '', 'h', 0);
REPLACE INTO `type` VALUES (136, 'Standard Bell Igloo', '', 'h', 0);
REPLACE INTO `type` VALUES (137, 'Staniless Steel Bowls', '', '', 0);
REPLACE INTO `type` VALUES (138, 'Suede Cat Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (139, 'Suede Puppy Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (140, 'Suede Puppy Lead', '', 'h', 0);
REPLACE INTO `type` VALUES (141, 'Untangling Dog Comb', '', 'h', 0);
REPLACE INTO `type` VALUES (142, 'Velvet Cat Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (143, 'Walk Leash Bag', '', '', 0);
REPLACE INTO `type` VALUES (144, 'Walking Harnesses', '', 'h', 0);
REPLACE INTO `type` VALUES (145, 'WB adj cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (146, 'Nylon Webbed Collars', '', 'h', 0);
REPLACE INTO `type` VALUES (147, 'WB DBLER Leads', '', 'h', 0);
REPLACE INTO `type` VALUES (148, 'Nylon Webbed Leads', '', 'h', 0);
REPLACE INTO `type` VALUES (149, 'WB metal bkle cat collar', '', 'h', 0);
REPLACE INTO `type` VALUES (150, 'White Retriever Sticks', '', '', 0);
REPLACE INTO `type` VALUES (165, '', '', '', 0);
REPLACE INTO `type` VALUES (162, 'Lamb Necks', '', '', 0);
REPLACE INTO `type` VALUES (161, 'Beef Jerky', '', '', 0);
REPLACE INTO `type` VALUES (160, 'Munchy Bones', '', '', 0);
REPLACE INTO `type` VALUES (159, 'Munchy Sticks', '', '', 0);
REPLACE INTO `type` VALUES (158, 'Rawhide Basted Puppy Chips', '', '', 0);
REPLACE INTO `type` VALUES (157, 'Smoked Pigs Trotters', '', '', 0);
REPLACE INTO `type` VALUES (156, 'Cat Tunnel', '', 'h', 0);
REPLACE INTO `type` VALUES (155, 'Pet Toys', '', '', 0);
REPLACE INTO `type` VALUES (154, 'Neem Pet Wash Soap', '', '', 0);
REPLACE INTO `type` VALUES (153, 'Melamine Bowls', '', 'h', 0);
REPLACE INTO `type` VALUES (152, 'Mats - Standard Dacron', '', 'h', 0);
REPLACE INTO `type` VALUES (151, 'TYPE', '', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `type_category`
-- 

DROP TABLE IF EXISTS `type_category`;
CREATE TABLE IF NOT EXISTS `type_category` (
  `catid` int(11) NOT NULL default '0',
  `typeid` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `type_category`
-- 

REPLACE INTO `type_category` VALUES (5, 1);
REPLACE INTO `type_category` VALUES (5, 2);
REPLACE INTO `type_category` VALUES (8, 14);
REPLACE INTO `type_category` VALUES (8, 15);
REPLACE INTO `type_category` VALUES (8, 16);
REPLACE INTO `type_category` VALUES (8, 17);
REPLACE INTO `type_category` VALUES (10, 18);
REPLACE INTO `type_category` VALUES (9, 19);
REPLACE INTO `type_category` VALUES (5, 20);
REPLACE INTO `type_category` VALUES (4, 21);
REPLACE INTO `type_category` VALUES (4, 22);
REPLACE INTO `type_category` VALUES (4, 23);
REPLACE INTO `type_category` VALUES (4, 24);
REPLACE INTO `type_category` VALUES (4, 26);
REPLACE INTO `type_category` VALUES (4, 27);
REPLACE INTO `type_category` VALUES (4, 28);
REPLACE INTO `type_category` VALUES (4, 29);
REPLACE INTO `type_category` VALUES (4, 30);
REPLACE INTO `type_category` VALUES (4, 31);
REPLACE INTO `type_category` VALUES (4, 32);
REPLACE INTO `type_category` VALUES (4, 33);
REPLACE INTO `type_category` VALUES (4, 34);
REPLACE INTO `type_category` VALUES (4, 35);
REPLACE INTO `type_category` VALUES (4, 36);
REPLACE INTO `type_category` VALUES (4, 37);
REPLACE INTO `type_category` VALUES (4, 38);
REPLACE INTO `type_category` VALUES (4, 39);
REPLACE INTO `type_category` VALUES (4, 40);
REPLACE INTO `type_category` VALUES (4, 41);
REPLACE INTO `type_category` VALUES (4, 42);
REPLACE INTO `type_category` VALUES (4, 43);
REPLACE INTO `type_category` VALUES (4, 44);
REPLACE INTO `type_category` VALUES (4, 45);
REPLACE INTO `type_category` VALUES (4, 46);
REPLACE INTO `type_category` VALUES (4, 47);
REPLACE INTO `type_category` VALUES (3, 48);
REPLACE INTO `type_category` VALUES (1, 49);
REPLACE INTO `type_category` VALUES (5, 50);
REPLACE INTO `type_category` VALUES (5, 51);
REPLACE INTO `type_category` VALUES (5, 52);
REPLACE INTO `type_category` VALUES (5, 53);
REPLACE INTO `type_category` VALUES (5, 54);
REPLACE INTO `type_category` VALUES (12, 54);
REPLACE INTO `type_category` VALUES (5, 55);
REPLACE INTO `type_category` VALUES (5, 56);
REPLACE INTO `type_category` VALUES (14, 57);
REPLACE INTO `type_category` VALUES (6, 61);
REPLACE INTO `type_category` VALUES (11, 63);
REPLACE INTO `type_category` VALUES (15, 65);
REPLACE INTO `type_category` VALUES (6, 66);
REPLACE INTO `type_category` VALUES (6, 67);
REPLACE INTO `type_category` VALUES (19, 68);
REPLACE INTO `type_category` VALUES (18, 69);
REPLACE INTO `type_category` VALUES (5, 70);
REPLACE INTO `type_category` VALUES (6, 71);
REPLACE INTO `type_category` VALUES (16, 72);
REPLACE INTO `type_category` VALUES (17, 74);
REPLACE INTO `type_category` VALUES (17, 64);
REPLACE INTO `type_category` VALUES (12, 73);
REPLACE INTO `type_category` VALUES (24, 58);
REPLACE INTO `type_category` VALUES (23, 59);
REPLACE INTO `type_category` VALUES (23, 60);
REPLACE INTO `type_category` VALUES (6, 75);
REPLACE INTO `type_category` VALUES (6, 76);
REPLACE INTO `type_category` VALUES (6, 77);
REPLACE INTO `type_category` VALUES (11, 78);
REPLACE INTO `type_category` VALUES (5, 79);
REPLACE INTO `type_category` VALUES (6, 80);
REPLACE INTO `type_category` VALUES (6, 81);
REPLACE INTO `type_category` VALUES (4, 82);
REPLACE INTO `type_category` VALUES (6, 83);
REPLACE INTO `type_category` VALUES (6, 84);
REPLACE INTO `type_category` VALUES (19, 85);
REPLACE INTO `type_category` VALUES (18, 86);
REPLACE INTO `type_category` VALUES (6, 87);
REPLACE INTO `type_category` VALUES (6, 88);
REPLACE INTO `type_category` VALUES (11, 89);
REPLACE INTO `type_category` VALUES (6, 90);
REPLACE INTO `type_category` VALUES (11, 91);
REPLACE INTO `type_category` VALUES (5, 92);
REPLACE INTO `type_category` VALUES (11, 93);
REPLACE INTO `type_category` VALUES (11, 94);
REPLACE INTO `type_category` VALUES (11, 95);
REPLACE INTO `type_category` VALUES (6, 96);
REPLACE INTO `type_category` VALUES (6, 97);
REPLACE INTO `type_category` VALUES (5, 98);
REPLACE INTO `type_category` VALUES (22, 99);
REPLACE INTO `type_category` VALUES (22, 100);
REPLACE INTO `type_category` VALUES (6, 101);
REPLACE INTO `type_category` VALUES (5, 102);
REPLACE INTO `type_category` VALUES (18, 103);
REPLACE INTO `type_category` VALUES (6, 104);
REPLACE INTO `type_category` VALUES (3, 105);
REPLACE INTO `type_category` VALUES (1, 106);
REPLACE INTO `type_category` VALUES (1, 107);
REPLACE INTO `type_category` VALUES (6, 108);
REPLACE INTO `type_category` VALUES (4, 109);
REPLACE INTO `type_category` VALUES (6, 110);
REPLACE INTO `type_category` VALUES (6, 111);
REPLACE INTO `type_category` VALUES (14, 112);
REPLACE INTO `type_category` VALUES (21, 113);
REPLACE INTO `type_category` VALUES (21, 114);
REPLACE INTO `type_category` VALUES (21, 115);
REPLACE INTO `type_category` VALUES (21, 116);
REPLACE INTO `type_category` VALUES (2, 117);
REPLACE INTO `type_category` VALUES (5, 118);
REPLACE INTO `type_category` VALUES (6, 119);
REPLACE INTO `type_category` VALUES (6, 120);
REPLACE INTO `type_category` VALUES (6, 121);
REPLACE INTO `type_category` VALUES (6, 122);
REPLACE INTO `type_category` VALUES (4, 123);
REPLACE INTO `type_category` VALUES (6, 124);
REPLACE INTO `type_category` VALUES (4, 125);
REPLACE INTO `type_category` VALUES (19, 126);
REPLACE INTO `type_category` VALUES (4, 127);
REPLACE INTO `type_category` VALUES (6, 128);
REPLACE INTO `type_category` VALUES (6, 129);
REPLACE INTO `type_category` VALUES (4, 130);
REPLACE INTO `type_category` VALUES (6, 131);
REPLACE INTO `type_category` VALUES (6, 132);
REPLACE INTO `type_category` VALUES (6, 133);
REPLACE INTO `type_category` VALUES (5, 134);
REPLACE INTO `type_category` VALUES (19, 135);
REPLACE INTO `type_category` VALUES (18, 136);
REPLACE INTO `type_category` VALUES (5, 137);
REPLACE INTO `type_category` VALUES (6, 138);
REPLACE INTO `type_category` VALUES (6, 139);
REPLACE INTO `type_category` VALUES (6, 140);
REPLACE INTO `type_category` VALUES (11, 141);
REPLACE INTO `type_category` VALUES (6, 142);
REPLACE INTO `type_category` VALUES (6, 143);
REPLACE INTO `type_category` VALUES (6, 144);
REPLACE INTO `type_category` VALUES (6, 145);
REPLACE INTO `type_category` VALUES (6, 146);
REPLACE INTO `type_category` VALUES (6, 147);
REPLACE INTO `type_category` VALUES (6, 148);
REPLACE INTO `type_category` VALUES (6, 149);
REPLACE INTO `type_category` VALUES (4, 150);
REPLACE INTO `type_category` VALUES (21, 152);
REPLACE INTO `type_category` VALUES (5, 153);
REPLACE INTO `type_category` VALUES (11, 154);
REPLACE INTO `type_category` VALUES (3, 155);
REPLACE INTO `type_category` VALUES (3, 156);
REPLACE INTO `type_category` VALUES (4, 157);
REPLACE INTO `type_category` VALUES (4, 158);
REPLACE INTO `type_category` VALUES (4, 159);
REPLACE INTO `type_category` VALUES (4, 160);
REPLACE INTO `type_category` VALUES (4, 161);
REPLACE INTO `type_category` VALUES (4, 162);
REPLACE INTO `type_category` VALUES (11, 166);

-- --------------------------------------------------------

-- 
-- Table structure for table `type_options`
-- 

DROP TABLE IF EXISTS `type_options`;
CREATE TABLE IF NOT EXISTS `type_options` (
  `typeid` int(11) NOT NULL default '0',
  `opt_code` varchar(20) NOT NULL default '',
  `opt_desc` varchar(255) NOT NULL default '',
  `opt_class` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `type_options`
-- 

REPLACE INTO `type_options` VALUES (14, 'blu', 'Blue tarten', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (14, 'red', 'Red Tarten', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (17, 'blu', 'Blue', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (17, 'pur', 'Purple', 'opt_ff00ff');
REPLACE INTO `type_options` VALUES (18, 'blu', 'Blue', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (18, 'pur', 'Purple', 'opt_ff00ff');
REPLACE INTO `type_options` VALUES (19, 'red', 'Red', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (19, 'blu', 'Blue', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (19, 'pur', 'Purple', 'opt_ff00ff');
REPLACE INTO `type_options` VALUES (52, 'blu', 'Blue', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (153, 'blu', '', '');
REPLACE INTO `type_options` VALUES (152, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (61, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (61, 'blk', '', '');
REPLACE INTO `type_options` VALUES (61, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (61, 'pur', '', '');
REPLACE INTO `type_options` VALUES (61, 'red', '', '');
REPLACE INTO `type_options` VALUES (53, 'blu', '', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (53, 'gld', '', 'opt_ffff40');
REPLACE INTO `type_options` VALUES (53, 'grn', '', 'opt_00ff00');
REPLACE INTO `type_options` VALUES (53, 'red', '', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (67, 'pnk', '', '');
REPLACE INTO `type_options` VALUES (68, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (69, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (57, 'blu', '', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (57, 'grn', '', 'opt_00ff00');
REPLACE INTO `type_options` VALUES (57, 'red', '', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (57, 'yel', '', 'opt_ffff00');
REPLACE INTO `type_options` VALUES (58, 'blu', '', '');
REPLACE INTO `type_options` VALUES (58, 'pur', '', '');
REPLACE INTO `type_options` VALUES (58, 'red', '', '');
REPLACE INTO `type_options` VALUES (58, 'yel', '', '');
REPLACE INTO `type_options` VALUES (2, 'blu', '', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (2, 'grn', '', 'opt_00ff00');
REPLACE INTO `type_options` VALUES (2, 'red', '', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (2, 'yel', 'opt_ffff00', '');
REPLACE INTO `type_options` VALUES (77, 'blk', '', '');
REPLACE INTO `type_options` VALUES (77, 'blu', '', '');
REPLACE INTO `type_options` VALUES (77, 'pur', '', '');
REPLACE INTO `type_options` VALUES (77, 'red', '', '');
REPLACE INTO `type_options` VALUES (79, 'blu', '', '');
REPLACE INTO `type_options` VALUES (79, 'red', '', '');
REPLACE INTO `type_options` VALUES (79, 'yel', '', '');
REPLACE INTO `type_options` VALUES (83, 'blk', '', '');
REPLACE INTO `type_options` VALUES (83, 'blu', '', '');
REPLACE INTO `type_options` VALUES (83, 'pur', '', '');
REPLACE INTO `type_options` VALUES (83, 'red', '', '');
REPLACE INTO `type_options` VALUES (84, 'blu', '', '');
REPLACE INTO `type_options` VALUES (84, 'pur', '', '');
REPLACE INTO `type_options` VALUES (84, 'red', '', '');
REPLACE INTO `type_options` VALUES (84, 'blk', '', '');
REPLACE INTO `type_options` VALUES (85, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (86, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (87, 'grn', '', '');
REPLACE INTO `type_options` VALUES (87, 'org', '', '');
REPLACE INTO `type_options` VALUES (87, 'yel', '', '');
REPLACE INTO `type_options` VALUES (88, 'blk', '', '');
REPLACE INTO `type_options` VALUES (88, 'blu', '', '');
REPLACE INTO `type_options` VALUES (88, 'red', '', '');
REPLACE INTO `type_options` VALUES (92, 'blu', '', '');
REPLACE INTO `type_options` VALUES (92, 'grn', '', '');
REPLACE INTO `type_options` VALUES (92, 'red', '', '');
REPLACE INTO `type_options` VALUES (92, 'yel', '', '');
REPLACE INTO `type_options` VALUES (99, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (100, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (101, 'blu', '', '');
REPLACE INTO `type_options` VALUES (101, 'grn', '', '');
REPLACE INTO `type_options` VALUES (101, 'gry', '', '');
REPLACE INTO `type_options` VALUES (101, 'pur', '', '');
REPLACE INTO `type_options` VALUES (101, 'red', '', '');
REPLACE INTO `type_options` VALUES (103, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (104, 'brn', '', '');
REPLACE INTO `type_options` VALUES (104, 'tan', '', '');
REPLACE INTO `type_options` VALUES (104, 'wte', '', '');
REPLACE INTO `type_options` VALUES (105, 'blu', '', 'opt_0000ff');
REPLACE INTO `type_options` VALUES (105, 'grn', '', 'opt_00ff00');
REPLACE INTO `type_options` VALUES (105, 'red', '', 'opt_ff0000');
REPLACE INTO `type_options` VALUES (105, 'yel', '', 'opt_ffff00');
REPLACE INTO `type_options` VALUES (108, 'blk', '', '');
REPLACE INTO `type_options` VALUES (108, 'blu', '', '');
REPLACE INTO `type_options` VALUES (108, 'pur', '', '');
REPLACE INTO `type_options` VALUES (108, 'red', '', '');
REPLACE INTO `type_options` VALUES (112, 'blu', '', '');
REPLACE INTO `type_options` VALUES (113, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (114, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (115, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (116, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (117, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (120, 'blu', '', '');
REPLACE INTO `type_options` VALUES (120, 'grn', '', '');
REPLACE INTO `type_options` VALUES (120, 'pur', '', '');
REPLACE INTO `type_options` VALUES (121, 'red', '', '');
REPLACE INTO `type_options` VALUES (122, 'red', '', '');
REPLACE INTO `type_options` VALUES (124, 'blk', '', '');
REPLACE INTO `type_options` VALUES (124, 'red', '', '');
REPLACE INTO `type_options` VALUES (124, 'tan', '', '');
REPLACE INTO `type_options` VALUES (126, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (129, 'yel', '', '');
REPLACE INTO `type_options` VALUES (129, 'red', '', '');
REPLACE INTO `type_options` VALUES (131, 'blk', '', '');
REPLACE INTO `type_options` VALUES (131, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (131, 'pur', '', '');
REPLACE INTO `type_options` VALUES (131, 'red', '', '');
REPLACE INTO `type_options` VALUES (132, 'blk', '', '');
REPLACE INTO `type_options` VALUES (132, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (132, 'pur', '', '');
REPLACE INTO `type_options` VALUES (132, 'red', '', '');
REPLACE INTO `type_options` VALUES (133, 'blk', '', '');
REPLACE INTO `type_options` VALUES (133, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (133, 'pur', '', '');
REPLACE INTO `type_options` VALUES (133, 'red', '', '');
REPLACE INTO `type_options` VALUES (134, 'blu', '', '');
REPLACE INTO `type_options` VALUES (134, 'gld', '', '');
REPLACE INTO `type_options` VALUES (134, 'grn', '', '');
REPLACE INTO `type_options` VALUES (134, 'red', '', '');
REPLACE INTO `type_options` VALUES (135, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (136, 'xxx', '', '');
REPLACE INTO `type_options` VALUES (138, 'blk', '', '');
REPLACE INTO `type_options` VALUES (138, 'blu', '', '');
REPLACE INTO `type_options` VALUES (138, 'pur', '', '');
REPLACE INTO `type_options` VALUES (138, 'red', '', '');
REPLACE INTO `type_options` VALUES (139, 'blk', '', '');
REPLACE INTO `type_options` VALUES (139, 'blu', '', '');
REPLACE INTO `type_options` VALUES (139, 'pur', '', '');
REPLACE INTO `type_options` VALUES (139, 'red', '', '');
REPLACE INTO `type_options` VALUES (140, 'blk', '', '');
REPLACE INTO `type_options` VALUES (140, 'blu', '', '');
REPLACE INTO `type_options` VALUES (140, 'pur', '', '');
REPLACE INTO `type_options` VALUES (140, 'red', '', '');
REPLACE INTO `type_options` VALUES (141, 'blk', '', '');
REPLACE INTO `type_options` VALUES (141, 'blu', '', '');
REPLACE INTO `type_options` VALUES (142, 'blk', '', '');
REPLACE INTO `type_options` VALUES (142, 'blu', '', '');
REPLACE INTO `type_options` VALUES (142, 'red', '', '');
REPLACE INTO `type_options` VALUES (144, 'blk', '', '');
REPLACE INTO `type_options` VALUES (144, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (144, 'pur', '', '');
REPLACE INTO `type_options` VALUES (144, 'red', '', '');
REPLACE INTO `type_options` VALUES (145, 'blk', '', '');
REPLACE INTO `type_options` VALUES (145, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (145, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (145, 'pur', '', '');
REPLACE INTO `type_options` VALUES (145, 'red', '', '');
REPLACE INTO `type_options` VALUES (146, 'blk', '', '');
REPLACE INTO `type_options` VALUES (146, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (146, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (146, 'pur', '', '');
REPLACE INTO `type_options` VALUES (146, 'red', '', '');
REPLACE INTO `type_options` VALUES (147, 'blk', '', '');
REPLACE INTO `type_options` VALUES (147, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (147, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (147, 'pur', '', '');
REPLACE INTO `type_options` VALUES (147, 'red', '', '');
REPLACE INTO `type_options` VALUES (148, 'blk', '', '');
REPLACE INTO `type_options` VALUES (148, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (148, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (148, 'pur', '', '');
REPLACE INTO `type_options` VALUES (148, 'red', '', '');
REPLACE INTO `type_options` VALUES (149, 'blk', '', '');
REPLACE INTO `type_options` VALUES (149, 'dkb', '', '');
REPLACE INTO `type_options` VALUES (149, 'ltb', '', '');
REPLACE INTO `type_options` VALUES (149, 'pur', '', '');
REPLACE INTO `type_options` VALUES (149, 'red', '', '');
REPLACE INTO `type_options` VALUES (153, 'mti', '', '');
REPLACE INTO `type_options` VALUES (153, 'red', '', '');
REPLACE INTO `type_options` VALUES (156, 'blk', '', '');
REPLACE INTO `type_options` VALUES (156, 'blu', '', '');
REPLACE INTO `type_options` VALUES (156, 'red', '', '');
