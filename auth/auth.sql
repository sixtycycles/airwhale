-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 22, 2017 at 10:13 PM
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

CREATE TABLE `Problems` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  `description` tinyblob NOT NULL,
  `type` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problem_status` varchar(15) DEFAULT 'Reported',
  `file` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Problems`
--

INSERT INTO `Problems` (`id`, `name`, `lat`, `lon`, `description`, `type`, `timestamp`, `problem_status`, `file`) VALUES
  (11, 'admin', 44.87630874326679, -68.73046875, 0x6c616d707921, 'streetlight', '2017-04-22 15:45:45', 'Reported', '14269-Diagram.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_uploads`
--

CREATE TABLE `tbl_uploads` (
  `id` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_uploads`
--

INSERT INTO `tbl_uploads` (`id`, `file`, `type`, `size`) VALUES
  (4, '14269-Diagram.jpg', 'image/jpeg', 7926);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

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
  (1, 'RodOconnor1985', 'sixtycycles@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', '4237c430ba8b0fd4f62d00b65f2ae9a0'),
  (2, 'admin', 'test@test.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'A', ''),
  (3, 'testUserNew', 'roderic.oconnor@maine.edu', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', 'fd60a134c72d7980227f73be69f90be8');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Problems`
--
ALTER TABLE `Problems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Problems_file_uindex` (`file`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  ADD CONSTRAINT `tbl_uploads_Problems_file_fk` FOREIGN KEY (`file`) REFERENCES `Problems` (`file`) ON DELETE CASCADE;
