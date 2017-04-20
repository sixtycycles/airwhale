-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 20, 2017 at 06:27 PM
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
  `address` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Problems`
--

INSERT INTO `Problems` (`id`, `name`, `lat`, `lon`, `address`, `type`) VALUES
  (3, 'bobob', 44.888715163384624, -68.67450714111328, 'some prog', 'noise'),
  (4, 'bobob', 44.908049247210855, -68.6810302734375, 'another', 'poop'),
  (5, 'bobob', 44.89795742331478, -68.76720428466797, 'oh noe!', 'noise'),
  (6, 'RodOconnor1985', 44.884397546192474, -68.67429256439209, 'somewhere over there', 'pothole'),
  (7, 'admin', 44.889688102796235, -68.71055603027344, 'WHAT ABOUT THIS ONE', 'garbage'),
  (8, 'GEORGIE', 44.897106224633916, -68.72308731079102, 'MY HOISE', 'garbage'),
  (9, 'admin', 44.85635555684337, -68.70025634765625, 'some other people house', 'poop'),
  (10, 'UERS', 44.893458087568014, -68.75432968139648, 'WHO MADE THIS', 'noise'),
  (11, 'RodOconnor1985', 44.887841024096666, -68.70635032653809, '', 'pothole');

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
