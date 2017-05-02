-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 02, 2017 at 02:52 AM
-- Server version: 5.6.28
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ORONOISSUE`
--
CREATE DATABASE IF NOT EXISTS `ORONOISSUE` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ORONOISSUE`;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `user` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  PRIMARY KEY (`user`,`problem_id`),
  KEY `likes_tbl_users_userID_fk` (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Problems`
--

DROP TABLE IF EXISTS `Problems`;
CREATE TABLE IF NOT EXISTS `Problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT 'ANON',
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `description` tinyblob NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_status` varchar(15) DEFAULT 'Reported',
  `file` varchar(100) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Problems_id_uindex` (`id`),
  UNIQUE KEY `Problems_file_uindex` (`file`),
  KEY `Problems_tbl_problem_types_type_id_fk` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `problem_timelines`
--

DROP TABLE IF EXISTS `problem_timelines`;
CREATE TABLE IF NOT EXISTS `problem_timelines` (
  `id` int(11) NOT NULL,
  `create_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_timestamp` datetime DEFAULT NULL,
  `complete_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `problem_timelines_id_uindex` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_problem_types`
--

DROP TABLE IF EXISTS `tbl_problem_types`;
CREATE TABLE IF NOT EXISTS `tbl_problem_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` text NOT NULL,
  `markerImage` text,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `id` (`type_id`)
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
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userStatus` enum('Y','N','A') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userEmail` (`userEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `userName`, `userEmail`, `userPass`, `userStatus`, `tokenCode`) VALUES
  (1, 'admin', 'test@test.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'A', ''),
  (2, 'Rod', 'sixtycycles@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', '02f0cc0a7dc2c0e8ed94b2a41248717d');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_Problems_id_fk` FOREIGN KEY (`user`) REFERENCES `tbl_users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_tbl_users_userID_fk` FOREIGN KEY (`problem_id`) REFERENCES `Problems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Problems`
--
ALTER TABLE `Problems`
  ADD CONSTRAINT `Problems_tbl_problem_types_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `tbl_problem_types` (`type_id`);

--
-- Constraints for table `problem_timelines`
--
ALTER TABLE `problem_timelines`
  ADD CONSTRAINT `problem_timelines_Problems_id_fk` FOREIGN KEY (`id`) REFERENCES `Problems` (`id`) ON DELETE CASCADE;
