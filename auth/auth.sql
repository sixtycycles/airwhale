DROP DATABASE ORONOISSUE;
CREATE DATABASE if NOT EXISTS ORONOISSUE ;
USE ORONOISSUE;
-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 28, 2017 at 09:53 PM
-- Server version: 5.6.28
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ORONOISSUE`
--

-- --------------------------------------------------------

--
-- Table structure for table `Problems`
--

DROP TABLE IF EXISTS `Problems`;
CREATE TABLE `Problems` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `description` tinyblob NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_status` varchar(15) DEFAULT 'Reported',
  `file` varchar(100) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Problems`
--

INSERT INTO `Problems` (`id`, `name`, `lat`, `lon`, `description`, `timestamp`, `problem_status`, `file`, `type_id`) VALUES
  (35, 'admin', 44.884093476429, -68.70162963867188, 0x4772616666697469206f6e2074726565, '2017-04-28 15:25:45', 'Completed', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_problem_types`
--

DROP TABLE IF EXISTS `tbl_problem_types`;
CREATE TABLE `tbl_problem_types` (
  `type_id` int(11) NOT NULL,
  `type_name` text NOT NULL,
  `markerImage` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_problem_types`
--

INSERT INTO `tbl_problem_types` (`type_id`, `type_name`, `markerImage`) VALUES
  (1, 'Other', 'alert.png'),
  (2, 'Graffiti', 'graffiti.png'),
  (3, 'Streetlight Out', 'light.png'),
  (4, 'Noise Complaint', 'noise.png'),
  (5, 'Pothole', 'pothole.png'),
  (6, 'Trash', 'trash.png'),
  (7, 'Fire Hydrant Issue', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_uploads`
--

DROP TABLE IF EXISTS `tbl_uploads`;
CREATE TABLE `tbl_uploads` (
  `id` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `userID` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userStatus` enum('Y','N','A') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `userName`, `userEmail`, `userPass`, `userStatus`, `tokenCode`) VALUES
  (2, 'admin', 'test@test.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'A', ''),
  (4, 'Rod', 'sixtycycles@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', '02f0cc0a7dc2c0e8ed94b2a41248717d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Problems`
--
ALTER TABLE `Problems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Problems_file_uindex` (`file`),
  ADD KEY `Problems_tbl_problem_types_type_id_fk` (`type_id`);

--
-- Indexes for table `tbl_problem_types`
--
ALTER TABLE `tbl_problem_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `id` (`type_id`);

--
-- Indexes for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tbl_uploads_file_uindex` (`file`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Problems`
--
ALTER TABLE `Problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `tbl_problem_types`
--
ALTER TABLE `tbl_problem_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Problems`
--
ALTER TABLE `Problems`
  ADD CONSTRAINT `Problems_tbl_problem_types_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `tbl_problem_types` (`type_id`);

--
-- Constraints for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  ADD CONSTRAINT `tbl_uploads_Problems_file_fk` FOREIGN KEY (`file`) REFERENCES `Problems` (`file`) ON DELETE CASCADE ON UPDATE CASCADE;


