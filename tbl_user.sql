-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2024 at 07:23 PM
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
-- Database: `multi_role_login_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `last_login_datetime` datetime DEFAULT NULL,
  `online_status` enum('Online','Offline') DEFAULT 'Offline',
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default_image.png'
) ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`tbl_user_id`, `name`, `username`, `email`, `password`, `role`, `last_login_datetime`, `online_status`, `profile_picture`) VALUES
(3, 'Vaibhav Mohan Manchekar', 'vaibhav', 'vmanchekar@ep.com', '$2y$10$ZvoffMOSIU4Jx2Ec1sjk6e7ZDQyhjz80hhyH1/m.gNfcVPUUsWaGG', 'user', '2024-07-03 10:16:16', 'Offline', 'default_image.png'),
(5, 'SuperAdmin', 'superadmin', 'superadmin@ep.com', '$2y$10$Je5eKUDNMz.33wyi1fEwE.7Isl.V/GRZJD9tIjDwQ6fWUeRsCowxy', 'admin', '2024-07-03 10:17:53', 'Offline', 'default_image.png'),
(6, 'Syed Sameer Ahmed', 'sahmed@ep.com', 'sahmed@ep.com', '$2y$10$TQ3Z4nWmqA5OF9/OHiqPK.60IdjiaMcFJKd6alphR0yEf70t/VIbu', 'admin', '2024-07-03 09:14:40', 'Offline', 'default_image.png'),
(11, 'test user', 'test@ep.com', 'test@ep.com', '$2y$10$WyrBfRIvrDJPJqJAX2luSeimyf3oEAXMbtxg0yskqPCsoX4YIZ0KG', 'user', '2024-07-03 09:15:17', 'Online', 'default_image.png'),
(12, 'test user', 'test@123.com', 'test@123.com', '$2y$10$hE8n7wcj/VFAQFpntoXr6e5lhaJMSq5crFrmHjYu880vVSPu17x3K', 'user', '2024-07-03 10:17:37', 'Offline', 'default_image.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
