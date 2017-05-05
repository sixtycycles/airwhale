-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 01, 2017 at 08:22 PM
-- Server version: 5.6.28
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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
CREATE TABLE `likes` (
  `user` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user`, `problem_id`) VALUES
(1, 1),
(2, 1);

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
  `type_id` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Problems`
--

INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.89296405121482, -68.6609137058258, 0x546865726520697320736F6D65206772616666697469206F6E207468652073696465206F662074686973206275696C64696E672E, '2017-05-05 19:25:12', 'Started', '51958-graffiti.jpg', 2, 0);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.88793984050824, -68.66930365562439, 0x74686572652069732061206C69676874206F7574206F6E207468697320737472656574212069747320736361727921, '2017-05-05 19:26:45', 'Reported', '52907-streetlight.JPG', 3, 0);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.90342911396686, -68.70695114135742, 0x4E4F4953455920574F4F445321, '2017-05-05 19:27:18', 'Reported', '16454-Couch in the Woods.jpg', 4, 1);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.90402944832199, -68.67437839508057, 0x746865726520697320612062696720706F74686F6C652068657265212069747320676F6E6E61206D65737320757020736F6D65206361727321, '2017-05-05 19:28:10', 'Started', '69659-pothole2.jpg', 5, 0);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.87065198742505, -68.69040727615356, 0x746865206C696768742068617320676F6E65206F7574212077652061726520686176696E672074726F75626C6520736565696E672E, '2017-05-05 19:28:55', 'Completed', '12417-streetlight2.JPG', 3, 2);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.88821728567946, -68.75629037618637, 0x4120545245452046454C4C20444F574E, '2017-05-05 19:29:50', 'Reported', '98775-fallen_tree.jpg', 6, 3);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.88397184807364, -68.67982864379883, 0x736F6D656F6E65207061696E746564206F6E207468657365207468696E677321, '2017-05-05 19:30:55', 'Reported', '51182-grafitti.jpg', 2, 0);
INSERT INTO ORONOISSUE.Problems (name, lat, lon, description, timestamp, problem_status, file, type_id, likes) VALUES ('admin', 44.88664761249026, -68.71673583984375, 0x74686520666972652068796472616E74206973207370726179696E672077617465722065766572797768657265212066756E2C2062757420776173746566756C2E, '2017-05-05 19:31:44', 'Reported', '97030-firehydrant2.jpg', 7, 0);
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
(1, 'admin', 'test@test.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'A', ''),
(2, 'Rod', 'sixtycycles@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Y', '02f0cc0a7dc2c0e8ed94b2a41248717d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`user`,`problem_id`),
  ADD KEY `likes_tbl_users_userID_fk` (`problem_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
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
-- Constraints for table `tbl_uploads`
--
ALTER TABLE `tbl_uploads`
  ADD CONSTRAINT `tbl_uploads_Problems_file_fk` FOREIGN KEY (`file`) REFERENCES `Problems` (`file`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
