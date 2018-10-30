-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 30, 2018 at 01:50 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_garbage_collection`
--

-- --------------------------------------------------------

--
-- Table structure for table `giver_table`
--

DROP TABLE IF EXISTS `giver_table`;
CREATE TABLE IF NOT EXISTS `giver_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `lat` text NOT NULL,
  `lng` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `giver_table`
--

INSERT INTO `giver_table` (`id`, `user_id`, `address`, `lat`, `lng`, `status`) VALUES
(1, 2, 'C-82,Noida,Uttar Pradesh,201307', '28.47749', '77.50372', 'N'),
(2, 2, 'B-14, Ground Floor,Noida,Uttar Pradesh,201301', '28.58467', '77.35201', 'Y'),
(3, 2, 'HUDA Complex,Rohtak,Haryana,124001', '28.89556', '76.58438', 'Y'),
(8, 2, 'Shivaji Complex,HUDA Complex,Rohtak,Haryana,124001', '28.89574', '76.58391', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `taker_table`
--

DROP TABLE IF EXISTS `taker_table`;
CREATE TABLE IF NOT EXISTS `taker_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `lat` text NOT NULL,
  `lng` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taker_table`
--

INSERT INTO `taker_table` (`id`, `user_id`, `address`, `lat`, `lng`) VALUES
(1, 1, 'C-82,Block C,Sector 32, Noida,Uttar Pradesh,201307', '28.58429', '77.32384'),
(2, 1, 'B-14, Ground Floor,Sector 67,C-Block, Jal Vayu Vihar,Noida,Uttar Pradesh,201301', '28.58578', '77.33463'),
(3, 1, 'B â€“ 17 , B â€“ BLOCK,  Shanti Kunj Main Rd,Sector D, Vasant Kunj,New Delhi,Delhi,110070', '28.51848', '77.15972');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` text NOT NULL,
  `user` text NOT NULL,
  `keypass` text NOT NULL,
  `user_type` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `user`, `keypass`, `user_type`) VALUES
(1, 'Abhishek Singh', 'proabe', 'pass1', '1'),
(2, 'Abhinav Singh', 'proabe1', 'pass2', '0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
