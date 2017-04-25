-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 25, 2017 at 06:38 PM
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
  (25, 'Driver184', 44.88760538427629, -68.70652198791504, 0x74686973206f6e65206973207265616c6c792062696721, 'pothole', '2017-04-24 20:14:38', 'Completed', '45413-pothole1.jpg'),
  (26, 'college student', 44.88952088000612, -68.66685748100281, 0x7468657265206973206120706f74686f6c652120736f6d656f6e6520636f756c642064616d61676520746865697220636172206f722062696b652e, 'pothole', '2017-04-24 20:36:11', 'Started', '37067-pothole2.jpg'),
  (27, 'coffeeperson88', 44.894643757500134, -68.65927219390869, 0x7468657265207761732061206c6967687420686572652c20627574206974206e6f206c6f6e676572207475726e73206f6e2e2074686520747261696c20697320766572792073706f6f6b792e, 'streetlight', '2017-04-24 20:40:10', 'Completed', '23097-streetlight.JPG'),
  (28, 'dog walker', 44.87460569248299, -68.67931365966797, 0x4120666972652068796472616e7420776173206f70656e65642062656361757365206f662074686520686561742e202073686f756c6420736f6d656f6e6520636f6d652073687574206974206f66663f, 'fireHydrant', '2017-04-24 20:58:43', 'Started', '73734-firehydrant.jpg'),
  (29, 'town person', 44.904568983975736, -68.6898922920227, 0x61206669726568796472616e74206973206c65616b657921, 'fireHydrant', '2017-04-24 20:59:56', 'Reported', '39992-firehydrant2.jpg'),
  (30, 'admin', 44.90464497450605, -68.66549491882324, 0x7468657265207761732061206c6967687420686572652c20627574206974206e6f206c6f6e676572207475726e73206f6e2e2074686520747261696c20697320766572792073706f6f6b792e, 'streetlight', '2017-04-24 21:01:00', 'Reported', '10918-streetlight2.JPG'),
  (31, 'couchguy66', 44.897106224633916, -68.76119613647461, 0x636f75636820696e2074686520776f6f64732120697473206e6173747921, 'other', '2017-04-24 21:04:15', 'Reported', '33974-Couch in the Woods.jpg'),
  (32, 'angry guy!', 44.86706300459254, -68.7165641784668, 0x736f6d656f6e6520686173207370726179207061696e746564206d792077616c6c21, 'grafitti', '2017-04-24 21:05:17', 'Reported', '34031-grafitti.jpg'),
  (33, 'Administrator', 44.89053941128094, -68.6810302734375, 0x6772616669747469206f6e207468697320766163616e74206275696c64696e672e2e2e206c6f6f6b732062616420666f7220746865206e65696768626f72686f6f6421, 'grafitti', '2017-04-24 21:06:22', 'Reported', '72196-graffiti.jpg'),
  (34, 'TreeGuyTM', 44.901848456821654, -68.72291564941406, 0x6120747265652066656c6c20646f776e212077652063616e74206472697665206865726521, 'other', '2017-04-24 21:20:45', 'Reported', '5390-fallen_tree.jpg');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
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
-- Constraints for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  ADD CONSTRAINT `tbl_uploads_Problems_file_fk` FOREIGN KEY (`file`) REFERENCES `Problems` (`file`) ON DELETE CASCADE;
