-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 23, 2022 at 06:00 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `equipment_demand`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`) VALUES
(1, 'Bed Sheets'),
(2, 'Portable Toilets'),
(3, 'Sleeping Bags'),
(4, 'Camping Tables'),
(5, 'Camping Chairs');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_transaction`
--

DROP TABLE IF EXISTS `equipment_transaction`;
CREATE TABLE IF NOT EXISTS `equipment_transaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idOrder` int DEFAULT NULL,
  `idStartStation` int DEFAULT NULL,
  `idEndStation` int DEFAULT NULL,
  `idEquipment` int DEFAULT NULL,
  `count` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idOrder` (`idOrder`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `equipment_transaction`
--

INSERT INTO `equipment_transaction` (`id`, `idOrder`, `idStartStation`, `idEndStation`, `idEquipment`, `count`) VALUES
(1, 1, 0, 1, 1, 35),
(2, 1, 0, 1, 2, 20),
(3, 1, 0, 1, 3, 10),
(4, 1, 0, 1, 4, 13),
(5, 1, 0, 1, 5, 22),
(6, 101, 0, 2, 1, 20),
(7, 101, 0, 2, 2, 13),
(8, 101, 0, 2, 3, 9),
(9, 101, 0, 2, 4, 15),
(10, 101, 0, 2, 5, 21),
(11, 102, 0, 3, 1, 8),
(12, 102, 0, 3, 2, 14),
(13, 102, 0, 3, 3, 23),
(14, 102, 0, 3, 4, 17),
(15, 102, 0, 3, 5, 6),
(16, 103, 0, 4, 1, 11),
(17, 103, 0, 4, 2, 5),
(18, 103, 0, 4, 3, 8),
(19, 103, 0, 4, 4, 11),
(20, 103, 0, 4, 5, 26),
(22, 2, 2, 4, 1, 2),
(23, 2, 2, 4, 2, 3),
(24, 2, 2, 4, 3, 4),
(25, 3, 1, 2, 2, 4),
(26, 3, 1, 2, 3, 3),
(27, 4, 4, 2, 2, 5),
(28, 4, 4, 2, 1, 3),
(29, 5, 3, 4, 1, 2),
(30, 5, 3, 4, 3, 3),
(31, 49, 1, 3, 1, 2),
(32, 49, 1, 3, 2, 3),
(33, 49, 1, 3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idStartStation` int DEFAULT NULL,
  `idEndStation` int DEFAULT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idStartStation` (`idStartStation`),
  KEY `idEndStation` (`idEndStation`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `idStartStation`, `idEndStation`, `startDate`, `endDate`) VALUES
(1, NULL, 1, '2022-01-01 00:00:00', '2022-01-01 00:00:00'),
(2, 2, 4, '2022-01-23 00:00:00', '2022-01-25 00:00:00'),
(3, 1, 2, '2022-01-23 00:00:00', '2022-01-24 00:00:00'),
(4, 4, 2, '2022-01-23 00:00:00', '2022-01-24 00:00:00'),
(5, 3, 4, '2022-01-24 00:00:00', '2022-01-26 00:00:00'),
(49, 1, 3, '2022-01-25 00:00:00', '2022-01-27 00:00:00'),
(101, NULL, 2, '2022-01-01 18:35:18', '2022-01-01 18:35:18'),
(102, NULL, 3, '2022-01-01 18:35:18', '2022-01-01 18:35:18'),
(103, NULL, 4, '2022-01-01 18:36:30', '2022-01-01 18:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE IF NOT EXISTS `station` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`id`, `name`) VALUES
(1, 'Paris'),
(2, 'Madrid'),
(3, 'Porto'),
(4, 'Munich');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `equipment_transaction`
--
ALTER TABLE `equipment_transaction`
  ADD CONSTRAINT `equipment_transaction_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `idEndStation` FOREIGN KEY (`idEndStation`) REFERENCES `station` (`id`),
  ADD CONSTRAINT `idStartStation` FOREIGN KEY (`idStartStation`) REFERENCES `station` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
