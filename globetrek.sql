-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2026 at 08:00 PM
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
-- Database: `globetrek`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `travel_date` date NOT NULL,
  `travelers` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `travel_date`, `travelers`, `total_price`, `status`, `created_at`) VALUES
(2, 3, 4, '2026-09-22', 5, 125000.00, 'confirmed', '2026-09-20 02:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `tester_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `tester_name`, `rating`, `comments`, `submitted_at`) VALUES
(1, 'Nimal Perera', 5, 'Booking was quick and simple, loved the live price update', '2026-09-26 13:49:04'),
(2, 'Sanduni Silva', 4, 'Liked the role-based dashboards, easy to navigate', '2026-09-26 13:49:21'),
(3, 'Kasun Fernando', 4, 'Great overall, maybe add pagination for more packages later', '2026-09-26 13:49:37');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `destination`, `description`, `price`, `duration`, `image`, `created_by`, `created_at`) VALUES
(1, 'Ella Adventure', 'Ella, Sri Lanka', 'Explore the beautiful hills of Ella, visit Nine Arches Bridge, enjoy scenic views and experience the natural beauty of Sri Lanka.', 45000.00, '3 Days / 2 Nights', 'ella.jpg', NULL, '2026-09-19 12:48:59'),
(2, 'Kandy Cultural Escape', 'Kandy, Sri Lanka', 'Discover the cultural heart of Sri Lanka with visits to the Temple of the Sacred Tooth Relic, Kandy Lake and traditional cultural attractions.', 38000.00, '2 Days / 1 Night', 'kandy.jpg', NULL, '2026-09-19 12:48:59'),
(3, 'Mirissa Beach Getaway', 'Mirissa, Sri Lanka', 'Relax on the beautiful southern coast of Sri Lanka and enjoy beaches, sunsets and a peaceful tropical getaway.', 42000.00, '3 Days / 2 Nights', 'mirissa.jpg', NULL, '2026-09-19 12:48:59'),
(4, 'Sigiriya Rock Explorer', 'Sigiriya, Sri Lanka', 'Discover the ancient rock fortress and cave temples of Sigiriya on this unforgettable journey through Sri Lanka\'s cultural heart.', 25000.00, '2 Days', 'sigiriya.jpg', NULL, '2026-09-19 16:33:14'),
(5, 'Galle Fort Heritage Walk', 'Galle, Sri Lanka', 'Walk through the cobblestone streets of the UNESCO-listed Galle Fort, exploring colonial architecture, boutique shops, and ocean views.', 18000.00, '1 Day', 'galle.jpg', NULL, '2026-09-19 16:33:14'),
(6, 'Yala Safari Adventure', 'Yala, Sri Lanka', 'Experience an exciting jeep safari through Yala National Park, home to leopards, elephants, and a wide variety of wildlife.', 35000.00, '2 Days', 'yala.jpg', NULL, '2026-09-19 16:33:14'),
(7, 'Anuradhapura Heritage Tour', 'Anuradhapura, Sri Lanka', 'Explore the ancient city of Anuradhapura and discover its historic temples, sacred sites and cultural heritage.', 30000.00, '2 Days / 1 Night', 'anuradhapura-heritage-tour-1789873893.jpg', 3, '2026-09-20 03:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','responded') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queries`
--

INSERT INTO `queries` (`id`, `user_id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 2, 'Test Customer 2', 'testcustomer2@gmail.com', 'I would like to know more about the accommodation options for the Ella Adventure package.', 'responded', '2026-09-19 13:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','staff','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`) VALUES
(1, 'Test Customer', 'testcustomer@gmail.com', '2026-09-19 18:2', '$2y$10$l1ngbkdSclMrEwXlEyJa2.wBkcVJ2uXG3zQyTOPTxR7/u/RnKRfGu', 'customer'),
(2, 'Test Customer 2', 'testcustomer2@gmail.com', '2026-09-19 18:3', '$2y$10$h5ywHyqdMb10qIX.R4UiNO.6JeLPnzJTrb7hIK7VTZKceTmAiSwQq', 'customer'),
(3, 'GlobeTrek Staff', 'staff@globetrek.com', '2026-09-19 18:5', '$2y$10$3LOL5IaxTlbI1b/QjoKLnOq1vSLFw/S4mtSZrRu0PNmI695NaV6.u', 'staff'),
(4, 'GlobeTrek Admin', 'admin@globetrek.com', '2026-09-20 08:4', '$2y$10$f2awW0k/iS7bLsv.ibaJxee37hykE6ayQWCJp9bXjXAwO4Lxkmyhq', 'admin'),
(5, 'Hima', 'testcustomer4@gmail.com', '0771234567', '$2y$10$MKkw7AdwIBWJM0kcJKk0u.gAs6ujTbLDt6ZK/Cg38LBWlOX.PPAQO', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `queries`
--
ALTER TABLE `queries`
  ADD CONSTRAINT `queries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
