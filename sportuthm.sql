-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 05:23 AM
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
-- Database: `sportuthm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminMatrix` varchar(30) NOT NULL,
  `adminPass` varchar(200) NOT NULL,
  `adminSalt` varchar(50) NOT NULL,
  `adminName` varchar(50) NOT NULL,
  `adminNoTel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminMatrix`, `adminPass`, `adminSalt`, `adminName`, `adminNoTel`) VALUES
('admin12', '$2y$10$MiucTVGzlc/.kGJVuGbkQObRWR2BD/dKCuLArBUovw1/RGHPVUjAe', '2IGZKz30QfqGJtrw', 'Muzaffar', 12332313);

-- --------------------------------------------------------

--
-- Table structure for table `booking_court`
--

CREATE TABLE `booking_court` (
  `booking_id` int(255) NOT NULL,
  `timeSlot` varchar(100) NOT NULL,
  `userMatrix` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booking_court`
--

INSERT INTO `booking_court` (`booking_id`, `timeSlot`, `userMatrix`, `date`) VALUES
(12, '09:00AM-12:00PM', 'di220178', '2024-06-12'),
(13, '09:00AM-12:00PM', 'di220179', '2024-06-11');

-- --------------------------------------------------------

--
-- Table structure for table `booking_field`
--

CREATE TABLE `booking_field` (
  `booking_id` int(11) NOT NULL,
  `timeSlot` varchar(100) NOT NULL,
  `userMatrix` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booking_field`
--

INSERT INTO `booking_field` (`booking_id`, `timeSlot`, `userMatrix`, `date`) VALUES
(17, '09:00AM-12:00PM', 'di220178', '2024-05-30'),
(18, '12:00PM-15:00PM', 'di220135', '2024-05-30');

-- --------------------------------------------------------

--
-- Table structure for table `booking_gym`
--

CREATE TABLE `booking_gym` (
  `booking_id` int(11) NOT NULL,
  `timeSlot` varchar(100) NOT NULL,
  `userMatrix` varchar(30) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booking_gym`
--

INSERT INTO `booking_gym` (`booking_id`, `timeSlot`, `userMatrix`, `date`) VALUES
(10, '12:00PM-15:00PM', 'di220179', '2024-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `email_verification`
--

CREATE TABLE `email_verification` (
  `email_id` int(255) NOT NULL,
  `token` varchar(200) DEFAULT NULL,
  `userMatrix` varchar(30) NOT NULL,
  `verified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `email_verification`
--

INSERT INTO `email_verification` (`email_id`, `token`, `userMatrix`, `verified_date`) VALUES
(5, NULL, 'di220135', '2024-05-20 19:37:57'),
(8, NULL, 'di220178', '2024-05-30 16:43:00'),
(9, NULL, 'di220179', '2024-06-10 14:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userMatrix` varchar(30) NOT NULL,
  `userPass` varchar(200) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userNoTel` int(15) NOT NULL,
  `adminMatrix` varchar(30) DEFAULT NULL,
  `userSalt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userMatrix`, `userPass`, `userName`, `userEmail`, `userNoTel`, `adminMatrix`, `userSalt`) VALUES
('di220135', '$2y$10$KM1k34L.O2jQLd7brdTuVeYRz0T8/80s4430kYBq5IUh71BoWx0qW', 'Yasin Bin Ibrahim', 'di220135@student.uthm.edu.my', 122345566, 'admin12', 'H9x0OJ6KIGWWf7iX'),
('di220178', '$2y$10$CNkcL7O4EYjNLX1BHF2yzuNZVt6HAJ0m1am20xLx840Yp1BVR..tG', 'Tengku Muzaffar', 'di220178@student.uthm.edu.my', 121244432, 'admin12', 'g3t95zkDAs2rd1zk'),
('di220179', '$2y$10$P.Lv.n1VvmcbhTmIyr/EjO915WjFwfeXDzJjU/kXVcknmEfqZ6WAC', 'Tengku Muzaffar', 'di220179@student.uthm.edu.my', 121244432, 'admin12', 'sBFgEyFBggBKa04S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminMatrix`);

--
-- Indexes for table `booking_court`
--
ALTER TABLE `booking_court`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `matrix_User` (`userMatrix`);

--
-- Indexes for table `booking_field`
--
ALTER TABLE `booking_field`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `userMatrix` (`userMatrix`) USING BTREE;

--
-- Indexes for table `booking_gym`
--
ALTER TABLE `booking_gym`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `matrix_User` (`userMatrix`);

--
-- Indexes for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD PRIMARY KEY (`email_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `matrix_User` (`userMatrix`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userMatrix`),
  ADD KEY `adminMatrix` (`adminMatrix`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_court`
--
ALTER TABLE `booking_court`
  MODIFY `booking_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `booking_field`
--
ALTER TABLE `booking_field`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `booking_gym`
--
ALTER TABLE `booking_gym`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `email_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_court`
--
ALTER TABLE `booking_court`
  ADD CONSTRAINT `booking_court_ibfk_1` FOREIGN KEY (`userMatrix`) REFERENCES `user` (`userMatrix`) ON DELETE CASCADE;

--
-- Constraints for table `booking_field`
--
ALTER TABLE `booking_field`
  ADD CONSTRAINT `booking_field_ibfk_1` FOREIGN KEY (`userMatrix`) REFERENCES `user` (`userMatrix`) ON DELETE CASCADE;

--
-- Constraints for table `booking_gym`
--
ALTER TABLE `booking_gym`
  ADD CONSTRAINT `booking_gym_ibfk_1` FOREIGN KEY (`userMatrix`) REFERENCES `user` (`userMatrix`) ON DELETE CASCADE;

--
-- Constraints for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD CONSTRAINT `email_verification_ibfk_1` FOREIGN KEY (`userMatrix`) REFERENCES `user` (`userMatrix`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`adminMatrix`) REFERENCES `admin` (`adminMatrix`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
