-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2024 at 07:42 AM
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
-- Database: `membership`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `membership_type` int(11) NOT NULL,
  `membership_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `fullname`, `dob`, `gender`, `contact_number`, `email`, `address`, `country`, `postcode`, `occupation`, `membership_type`, `membership_number`, `created_at`, `photo`, `expiry_date`) VALUES
(1, 'Demo NameUPD', '1995-11-10', 'Male', '4444444444', 'demo@mail.com', '123 Demo', 'DCountry', '4545', 'Tests', 2, 'CA-923020', '2024-02-02 04:10:30', '', '2024-05-04'),
(4, 'Qwerty', '1990-12-12', 'Male', '1010101012', 'qwerty@mail.com', '77 asd', 'aaaaa', '8888', 'addddd', 3, 'CA-610243', '2024-02-04 03:40:16', '', '2024-05-04'),
(5, 'Demo Test', '1990-12-12', 'Male', '7412121455', 'demo@test.com', '77 address', 'Ghana', '1010', 'aaaaaa', 3, 'CA-373031', '2024-02-04 12:23:22', '../uploads/images/f-3.jpg', '2024-11-20'),
(7, 'Member B', '1985-11-02', 'Male', '7000000001', 'memberb@mail.com', '8 demoo', 'Demoo', '7777', 'demo', 7, 'CA-992342', '2024-02-05 01:14:52', '', NULL),
(9, 'Random Updated', '1989-04-12', 'Female', '1010101010', 'random1989@mail.com', '12 demo', 'qweee', '1111', 'wwwwww', 3, 'CA-871386', '2024-02-05 02:55:03', '', '2025-02-05'),
(10, 'Testing Member', '1985-12-12', 'Female', '1212121212', 'testing@mail.com', '77 demo', 'demooo', '1111', 'demodemo', 1, 'CA-519259', '2024-02-05 05:21:32', '../uploads/images/avatar-7.jpg', '2024-05-05'),
(11, 'Member C', '1991-02-22', 'Male', '1111111100', 'c@mem.com', '8 test', 'testing', '1111', 'test', 10, 'CA-905203', '2024-02-05 05:30:04', '../uploads/images/f-4.jpg', '2025-08-22'),
(12, 'Demo Member', '1990-12-12', 'Male', '7777777770', 'member@demo.com', '77 demo', 'Ghana', '7777', 'Test', 10, 'CA-053289', '2024-02-05 06:07:10', '../uploads/images/f-2.jpg', '2025-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `membership_types`
--

CREATE TABLE `membership_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `membership_types`
--

INSERT INTO `membership_types` (`id`, `type`, `amount`) VALUES
(1, 'Basic', 8),
(2, 'Standard', 13),
(3, 'Gold', 19),
(4, 'Silver', 15),
(6, 'Bronze', 12),
(7, 'BB Upd', 6),
(10, 'Premium', 28);

-- --------------------------------------------------------

--
-- Table structure for table `renew`
--

CREATE TABLE `renew` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `renew_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `renew`
--

INSERT INTO `renew` (`id`, `member_id`, `total_amount`, `renew_date`) VALUES
(1, 1, 39.00, '2024-02-04'),
(16, 4, 57.00, '2024-02-04'),
(18, 5, 114.00, '2024-02-04'),
(19, 9, 228.00, '2024-02-05'),
(20, 10, 8.00, '2024-02-05'),
(21, 11, 13.00, '2024-02-05'),
(23, 12, 336.00, '2024-02-05'),
(25, 7, 6.00, '2024-08-20'),
(26, 5, 57.00, '2024-08-20'),
(27, 12, 336.00, '2024-08-22'),
(28, 11, 336.00, '2024-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `system_name`, `logo`, `currency`) VALUES
(1, 'Membership System', '../uploads/logo/logo.png', 'GH₵');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `locale` varchar(10) DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `middle_name`, `last_name`, `image_path`, `role`, `registration_date`, `updated_date`, `locale`) VALUES
(1, 'admin@gmail.com', '44ffe44097bbce02fbaa42734e92ae04', 'Code', '', 'Lytical', '', 'admin', '2024-02-02 01:34:26', '2024-08-22 05:36:37', 'en');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membership_type` (`membership_type`);

--
-- Indexes for table `membership_types`
--
ALTER TABLE `membership_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `renew`
--
ALTER TABLE `renew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `membership_types`
--
ALTER TABLE `membership_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `renew`
--
ALTER TABLE `renew`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`membership_type`) REFERENCES `membership_types` (`id`);

--
-- Constraints for table `renew`
--
ALTER TABLE `renew`
  ADD CONSTRAINT `renew_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
