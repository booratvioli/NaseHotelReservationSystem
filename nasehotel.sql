-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2024 at 08:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nasehotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`) VALUES
(2, 'jazz', '1', '2023-11-24 03:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reservation_date` date DEFAULT NULL,
  `reservation_time` time DEFAULT NULL,
  `room_category` varchar(255) DEFAULT NULL,
  `hours_reserved` int(255) DEFAULT NULL,
  `room_number` int(255) DEFAULT NULL,
  `reservation_cost` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `advance_payment` int(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(255) DEFAULT NULL,
  `remaining_payment` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `reservation_date`, `reservation_time`, `room_category`, `hours_reserved`, `room_number`, `reservation_cost`, `payment_method`, `advance_payment`, `reference_number`, `status`, `emailaddress`, `remaining_payment`) VALUES
(342, 6, '2023-12-25', '05:36:00', 'Economy', 1, 1, 150.00, 'Gcash', 45, '12312', 'Accepted', 'jazzmichaelnase@gmail.com', 105.00),
(343, 6, '2023-12-25', '09:00:00', 'Economy', 1, 2, 150.00, 'Gcash', 45, '12321', 'Accepted', 'jazzmichaelnase@gmail.com', 105.00),
(344, 6, '2023-12-25', '07:15:00', 'Economy', 1, 3, 150.00, 'Gcash', 45, '123123', 'Accepted', 'jazzmichaelnase@gmail.com', 105.00),
(365, 6, '2023-12-30', '08:00:00', 'Economy', 1, 2, 150.00, 'Gcash', 45, '3123', 'Accepted', 'jazzmichaelnase@gmail.com', 105.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `verification_code` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `firstname`, `middlename`, `lastname`, `emailaddress`, `verification_code`) VALUES
(6, 'jazz', '1', 'Jazz ', 'Gili', 'Esan', 'jazzmichaelnase@gmail.com', ''),
(73, 'bb', '1', 'bb', 'bb', 'bb', 'jazzmichaelnase@gmail.com', ''),
(82, 'jojo', '12345678aA@', 'jojo', 'jojo', 'jojo', 'bc.jojo.maninang@cvsu.edu.ph', ''),
(83, 'Maryanna', '@Password0', 'Marianne', 'Cute', 'Muico', 'maaaryaaannaaa@gmail.com', ''),
(125, 'aa', '1', 'aa', 'aa', 'aa', 'jazzmichaelnase@gmail.com', ''),
(126, 'abc', '1', 'abc', 'abc', 'abc', 'jazzmichaelnase@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `expiration_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `username`, `code`, `expiration_time`) VALUES
(189, 'qwerty', 'A0419C', NULL),
(191, 'qwerty123', '336BEE', NULL),
(193, 'jkl', '4B74DB', NULL),
(197, 'popo', '822F6D', NULL),
(201, 'change', '9A7042', NULL),
(204, 'ty', 'E9AC73', NULL),
(206, 'zxc', '9DB60F', NULL),
(208, 'abc', '807904', NULL),
(211, 'popo', 'FAB331', NULL),
(213, 'wow', '7B34E1', NULL),
(215, 'localhost', 'F8AF9D', NULL),
(217, 'qwerty', '4D0E1B', NULL),
(219, 'zxc', '6E4A3B', NULL),
(221, 'abc', 'F378F6', NULL),
(223, 'asd', 'FCCCD6', NULL),
(225, 'kjl', '7A1C61', NULL),
(227, 'tyuiop', '7A84B7', NULL),
(229, 'zxcvbnm', '8499FD', NULL),
(231, 'zxc', 'D73C30', NULL),
(233, 'asdasd', '944398', NULL),
(235, 'asdfghjkl', 'B26A1C', NULL),
(237, 'zxcvbnm', '3C49D1', NULL),
(241, 'localhost', 'DC6D63', NULL),
(243, 'poiuyt', '3B80D6', NULL),
(245, 'asdasdHGJGHJ', 'D7F5D1', NULL),
(247, 'visual', 'E24505', NULL),
(249, 'putangina', 'E72237', NULL),
(251, 'aa', 'E29913', NULL),
(254, 'abc', '1855F3', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
