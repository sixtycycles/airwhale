-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 21, 2017 at 02:29 AM
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
-- Table structure for table `Problems`
--

CREATE TABLE IF NOT EXISTS `Problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `description` tinyblob NOT NULL,
  `type` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_status` varchar(15) DEFAULT 'Reported',
  `file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Problems`
--

INSERT INTO `Problems` (`id`, `name`, `lat`, `lon`, `description`, `type`, `timestamp`, `problem_status`, `file`) VALUES
  (3, 'bobob', 44.888715163384624, -68.67450714111328, 0x54686973206c616d7020646f65736e7420776f726b, 'streetLight', '2017-04-20 16:15:01', 'Reported', NULL),
  (4, 'bobob', 44.908049247210855, -68.6810302734375, 0x4974732067657474696e6720776574206f766572206865726521, 'fireHydrant', '2017-04-20 16:15:01', 'Reported', NULL),
  (5, 'bobob', 44.89795742331478, -68.76720428466797, 0x4920666f756e64206120646f6e7574, 'other', '2017-04-20 16:15:01', 'Reported', NULL),
  (6, 'RodOconnor1985', 44.884397546192474, -68.67429256439209, 0x736f6d656f6e65207061696e7465642061206469636b, 'grafitti', '2017-04-20 16:15:01', 'Reported', NULL),
  (7, 'admin', 44.889688102796235, -68.71055603027344, 0x676f6f6462796520746972657321, 'pothole', '2017-04-20 16:15:01', 'Reported', NULL),
  (8, 'GEORGIE', 44.897106224633916, -68.72308731079102, 0x6d6964646c65206f662074686520726f61642c203266742064656570, 'pothole', '2017-04-20 16:15:01', 'Reported', NULL),
  (9, 'admin', 44.85635555684337, -68.70025634765625, 0x736f6d65206869707069657320617265207061696e74696e6720666c6f77657273, 'grafitti', '2017-04-20 16:15:01', 'FUNKY', NULL),
  (10, 'UERS', 44.893458087568014, -68.75432968139648, 0x74686973206c616d702066656c6c206f76657221, 'streetLight', '2017-04-20 16:15:01', 'Reported', NULL),
  (11, 'RodOconnor1985', 44.887841024096666, -68.70635032653809, 0x736f6d656f6e652068697420746869732077697468207468656972206361722c206e6f77206974732073697820666c616773, 'fireHydrant', '2017-04-20 16:15:01', 'Reported', NULL),
  (13, 'admin', 44.937585003910904, -68.73733520507812, 0x594f4f4f4f4f4f, 'pothole', '2017-04-20 18:02:24', 'Reported', ''),
  (14, 'admin', 44.9715991458543, -68.83243560791016, '', 'pothole', '2017-04-20 18:02:57', 'Reported', 'CaptureLAB8.PNG'),
  (15, 'admin', 44.86341296203542, -68.74042510986328, 0x464f5245535420494d472054455354, 'other', '2017-04-20 19:26:56', 'Reported', '9103-CaptureLAB8.PNG');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_uploads`
--

CREATE TABLE IF NOT EXISTS `tbl_uploads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_uploads`
--

INSERT INTO `tbl_uploads` (`id`, `file`, `type`, `size`) VALUES
  (1, '9103-CaptureLAB8.PNG', 'image/png', 16334);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPass` varchar(100) NOT NULL,
  `userStatus` enum('Y','N','A') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userEmail` (`userEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `userName`, `userEmail`, `userPass`, `userStatus`, `tokenCode`) VALUES
  (1, 'RodOconnor1985', 'sixtycycles@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', '4237c430ba8b0fd4f62d00b65f2ae9a0'),
  (2, 'admin', 'test@test.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'A', ''),
  (3, 'testUserNew', 'roderic.oconnor@maine.edu', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', 'fd60a134c72d7980227f73be69f90be8');
