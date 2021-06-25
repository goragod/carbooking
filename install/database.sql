-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2021 at 01:57 PM
-- Server version: 10.1.44-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_car_reservation`
--

CREATE TABLE `{prefix}_car_reservation` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `chauffeur` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `travelers` int(11) NOT NULL,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `reason` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_car_reservation_data`
--

CREATE TABLE `{prefix}_car_reservation_data` (
  `reservation_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_category`
--

CREATE TABLE `{prefix}_category` (
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT 0,
  `topic` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `{prefix}_category`
--

INSERT INTO `{prefix}_category` (`type`, `category_id`, `topic`, `color`, `published`) VALUES
('department', 1, 'บริหาร', '', 1),
('department', 2, 'จัดซื้อจัดจ้าง', '', 1),
('department', 3, 'บุคคล', '', 1),
('car_accessories', 2, 'น้ำมันเต็มถัง', NULL, 1),
('car_accessories', 1, 'เครื่องกระจายเสียง', NULL, 1),
('car_brand', 12, 'GM', NULL, 1),
('car_type', 9, 'รถบัส', NULL, 1),
('car_type', 8, 'รถมินิบัส', NULL, 1),
('car_type', 7, 'รถตู้', NULL, 1),
('car_type', 6, 'รถบรรทุก 10 ล้อ', NULL, 1),
('car_type', 4, 'รถกระบะบรรทุก', NULL, 1),
('car_type', 5, 'รถบรรทุกเล็ก 6 ล้อ', NULL, 1),
('car_brand', 11, 'Hino', NULL, 1),
('car_brand', 10, 'Volvo', NULL, 1),
('car_brand', 9, 'Ford', NULL, 1),
('car_brand', 7, 'Bmw', NULL, 1),
('car_brand', 8, 'Benz', NULL, 1),
('car_brand', 6, 'Chevtolet', NULL, 1),
('car_brand', 5, 'Mazda', NULL, 1),
('car_type', 3, 'รถกระบะ CAB 4 ประตู', NULL, 1),
('car_brand', 3, 'Misubishi', NULL, 1),
('car_type', 2, 'รถกระบะ CAB 2 ประตู', NULL, 1),
('car_brand', 4, 'Nissan', NULL, 1),
('car_brand', 1, 'Toyota', NULL, 1),
('car_type', 1, 'รถเก๋ง', NULL, 1),
('car_brand', 2, 'Honda', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_language`
--

CREATE TABLE `{prefix}_language` (
  `id` int(11) NOT NULL,
  `key` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `owner` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `js` tinyint(1) NOT NULL,
  `th` text COLLATE utf8_unicode_ci,
  `en` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_user`
--

CREATE TABLE `{prefix}_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `permission` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_card` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provinceID` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT 'TH',
  `visited` int(11) DEFAULT 0,
  `lastvisited` int(11) DEFAULT 0,
  `session_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `social` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_vehicles`
--

CREATE TABLE `{prefix}_vehicles` (
  `id` int(11) NOT NULL,
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  `seats` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `{prefix}_vehicles`
--

INSERT INTO `{prefix}_vehicles` (`id`, `number`, `color`, `detail`, `published`, `seats`) VALUES
(1, 'นม 6', '#304FFE', 'พร้อมเครื่องเสียงชุดใหญ่', 1, 50),
(2, 'บจ 888', '#4A148C', '', 1, 13),
(3, 'กข 1234', '#B71C1C', '', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_vehicles_meta`
--

CREATE TABLE `{prefix}_vehicles_meta` (
  `vehicle_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `{prefix}_vehicles_meta`
--

INSERT INTO `{prefix}_vehicles_meta` (`vehicle_id`, `name`, `value`) VALUES
(3, 'car_brand', '12'),
(2, 'car_brand', '2'),
(2, 'car_type', '7'),
(1, 'car_brand', '8'),
(1, 'car_type', '9'),
(3, 'car_type', '3');

-- --------------------------------------------------------

--
-- Indexes for table `{prefix}_car_reservation`
--
ALTER TABLE `{prefix}_car_reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}_car_reservation_data`
--
ALTER TABLE `{prefix}_car_reservation_data`
  ADD KEY `reservation_id` (`reservation_id`) USING BTREE;

--
-- Indexes for table `{prefix}_category`
--
ALTER TABLE `{prefix}_category`
  ADD KEY `type` (`type`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `{prefix}_language`
--
ALTER TABLE `{prefix}_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}_user`
--
ALTER TABLE `{prefix}_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `token` (`token`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `{prefix}_vehicles`
--
ALTER TABLE `{prefix}_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}_vehicles_meta`
--
ALTER TABLE `{prefix}_vehicles_meta`
  ADD KEY `room_id` (`vehicle_id`);


--
-- AUTO_INCREMENT for table `{prefix}_car_reservation`
--
ALTER TABLE `{prefix}_car_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT for table `{prefix}_language`
--
ALTER TABLE `{prefix}_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}_user`
--
ALTER TABLE `{prefix}_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}_vehicles`
--
ALTER TABLE `{prefix}_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
