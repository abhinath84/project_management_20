-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2017 at 03:33 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `scrum_backlog`
--

CREATE TABLE `scrum_backlog` (
  `title` varchar(25) NOT NULL,
  `project` varchar(25) NOT NULL,
  `sprint` varchar(25) DEFAULT NULL,
  `owner` varbinary(100) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `estimated` double DEFAULT NULL,
  `type` enum('Story','Defect') DEFAULT NULL,
  `status` enum('Future','In-process','Done','Accepted') DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `risk` enum('Low','Medium','High') DEFAULT NULL,
  `etype` enum('New Feature','Enhancement') DEFAULT NULL,
  `source` enum('Product Mgt','Sales','Customer','Development') DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `build` varchar(25) DEFAULT NULL,
  `resolution` enum('Fixed','As Designed','Duplicated','Cant Reproduce','Rejected') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scrum_backlog`
--

INSERT INTO `scrum_backlog` (`title`, `project`, `sprint`, `owner`, `description`, `estimated`, `type`, `status`, `priority`, `risk`, `etype`, `source`, `reference`, `build`, `resolution`) VALUES
('5489672', 'Project 2017', NULL, 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Backlog 5489672', 36, 'Story', 'Future', 'High', 'High', 'New Feature', 'Product Mgt', NULL, NULL, NULL),
('78965412', 'Project 2017', '', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Backlog 78965412', 40, 'Story', 'Future', 'High', 'High', 'New Feature', 'Sales', '', '', 'Fixed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scrum_backlog`
--
ALTER TABLE `scrum_backlog`
  ADD PRIMARY KEY (`title`,`project`),
  ADD KEY `title` (`title`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
