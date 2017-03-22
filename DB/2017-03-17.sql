-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2017 at 08:31 AM
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
  `title` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `sprint` varchar(50) NOT NULL,
  `owner` varbinary(100) NOT NULL,
  `type` enum('story','defect') NOT NULL,
  `team` int(11) NOT NULL,
  `feature_group` varchar(50) NOT NULL,
  `epic` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `estimate` double NOT NULL,
  `project_owners` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `priority` varchar(10) NOT NULL,
  `complexity` varchar(10) NOT NULL,
  `source` varchar(20) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `requested_by` varchar(50) NOT NULL,
  `build` float NOT NULL,
  `retrospective` varchar(50) NOT NULL,
  `planned_estimate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scrum_member`
--

CREATE TABLE `scrum_member` (
  `member_id` varbinary(100) NOT NULL,
  `privilage` enum('System Admin','Member Admin','Project Admin','Project Lead','Team Member','Developer','Tester','Customer','Visitor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scrum_member`
--

INSERT INTO `scrum_member` (`member_id`, `privilage`) VALUES
(0x30312b4f3134455a674d585333746f5838425a61374a38584d4e4f7975344334302f6871784644594e6c633d, 'Team Member'),
(0x574d4c6353624f6c5444394d2b4a416751753266753131322b4947335837446e5a7a7371632f62396447303d, 'Developer'),
(0x724632444249554177777578737350502b46336a78536b4a416258736f4652495a4f4d55654751486f39413d, 'Team Member'),
(0x736c4454537334766c596d48615a34343149466d69384d775565783079395138656d736b6c7274667133673d, 'Team Member'),
(0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'System Admin');

-- --------------------------------------------------------

--
-- Table structure for table `scrum_project`
--

CREATE TABLE `scrum_project` (
  `title` varchar(50) NOT NULL,
  `parent` varchar(50) NOT NULL,
  `sprint_schedule` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '''''',
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `owner` varbinary(100) NOT NULL,
  `status` enum('Open','In-progress','Closed') NOT NULL DEFAULT 'Open',
  `target_estimate` double NOT NULL DEFAULT '0',
  `test_suit` double NOT NULL DEFAULT '0',
  `target_swag` double NOT NULL DEFAULT '0',
  `reference` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scrum_project`
--

INSERT INTO `scrum_project` (`title`, `parent`, `sprint_schedule`, `description`, `begin_date`, `end_date`, `owner`, `status`, `target_estimate`, `test_suit`, `target_swag`, `reference`) VALUES
('New Project', 'System(All Projects)', 'Base Sprint Schedule', 'New Project.\n', '2017-01-03', '2017-03-29', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Open', 0, 0, 0, NULL),
('Project 2017', 'System(All Projects)', 'Base Sprint Schedule', 'Project 2017', '2017-01-02', '2017-12-29', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Open', 0, 0, 0, NULL),
('Project 2018', 'System(All Projects)', 'Base Sprint Schedule', 'Project 2018.', '2018-01-01', '2018-12-31', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Open', 0, 0, 0, NULL),
('Release 1.0', 'Project 2017', 'Default Schedule', 'Release 1.0', '2017-01-02', '2017-06-30', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Open', 0, 0, 0, NULL),
('System(All Projects)', 'System(All Projects)', 'Default Schedule', 'System(All Projects)', '0000-00-00', '0000-00-00', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'Open', 0, 0, 0, NULL),
('Test', 'System(All Projects)', 'Default Schedule', 'Test.', '2017-02-16', '2017-04-13', 0x574d4c6353624f6c5444394d2b4a416751753266753131322b4947335837446e5a7a7371632f62396447303d, 'Open', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scrum_project_member`
--

CREATE TABLE `scrum_project_member` (
  `project_title` varchar(100) NOT NULL,
  `member_id` varbinary(100) NOT NULL,
  `privilage` enum('System Admin','Member Admin','Project Admin','Project Lead','Team Member','Developer','Tester','Customer','Visitor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scrum_project_member`
--

INSERT INTO `scrum_project_member` (`project_title`, `member_id`, `privilage`) VALUES
('New Project', 0x30312b4f3134455a674d585333746f5838425a61374a38584d4e4f7975344334302f6871784644594e6c633d, 'Team Member'),
('New Project', 0x736c4454537334766c596d48615a34343149466d69384d775565783079395138656d736b6c7274667133673d, 'Team Member'),
('New Project', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'System Admin'),
('Project 2017', 0x30312b4f3134455a674d585333746f5838425a61374a38584d4e4f7975344334302f6871784644594e6c633d, 'Team Member'),
('Project 2017', 0x724632444249554177777578737350502b46336a78536b4a416258736f4652495a4f4d55654751486f39413d, 'Project Admin'),
('Project 2017', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'System Admin'),
('Project 2018', 0x30312b4f3134455a674d585333746f5838425a61374a38584d4e4f7975344334302f6871784644594e6c633d, 'Team Member'),
('Project 2018', 0x78444b31413139564958484653584b326c536c3330482f5235414d33312b77776b4969307061314b7a2f633d, 'System Admin');

-- --------------------------------------------------------

--
-- Table structure for table `scrum_sprint`
--

CREATE TABLE `scrum_sprint` (
  `title` varchar(50) NOT NULL,
  `project` varchar(50) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` varchar(50) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `sprint_schedule` double NOT NULL,
  `state` varchar(10) NOT NULL,
  `target_estimate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scrum_sprint_schedule`
--

CREATE TABLE `scrum_sprint_schedule` (
  `title` varchar(50) NOT NULL,
  `length` int(10) NOT NULL,
  `length_unit` enum('days','weeks','months') NOT NULL,
  `gap` int(10) NOT NULL,
  `gap_unit` enum('days','weeks','months') NOT NULL,
  `description` varchar(250) DEFAULT ''''''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scrum_sprint_schedule`
--

INSERT INTO `scrum_sprint_schedule` (`title`, `length`, `length_unit`, `gap`, `gap_unit`, `description`) VALUES
('Base Sprint Schedule', 1, 'weeks', 2, 'days', 'Base Sprint Schedule.'),
('Default Schedule', 60, 'days', 2, 'days', 'Default Schedule.'),
('Sprint Schedule - Project 2016', 1, 'months', 2, 'days', 'Sprint Schedule - Project 2016.\n'),
('Sprint Schedule - Project 2017', 1, 'months', 1, 'days', 'Sprint Schedule - Project 2017.\n'),
('Sprint Schedule - Project 2018', 1, 'months', 3, 'days', 'Sprint Schedule - Project 2018.\n');

-- --------------------------------------------------------

--
-- Table structure for table `scrum_task`
--

CREATE TABLE `scrum_task` (
  `title` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `type` varchar(20) NOT NULL,
  `source` varchar(20) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `build` int(11) NOT NULL,
  `detail_estimate` double NOT NULL,
  `to_do` varchar(50) NOT NULL,
  `change_comment` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sprint_member`
--

CREATE TABLE `sprint_member` (
  `id` int(10) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `sprint_name` varchar(50) NOT NULL,
  `working_day` int(10) DEFAULT NULL,
  `buffer_time` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scrum_backlog`
--
ALTER TABLE `scrum_backlog`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `scrum_member`
--
ALTER TABLE `scrum_member`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `user_name` (`member_id`);

--
-- Indexes for table `scrum_project`
--
ALTER TABLE `scrum_project`
  ADD PRIMARY KEY (`title`,`parent`);

--
-- Indexes for table `scrum_project_member`
--
ALTER TABLE `scrum_project_member`
  ADD PRIMARY KEY (`project_title`,`member_id`);

--
-- Indexes for table `scrum_sprint`
--
ALTER TABLE `scrum_sprint`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `scrum_sprint_schedule`
--
ALTER TABLE `scrum_sprint_schedule`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `scrum_task`
--
ALTER TABLE `scrum_task`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `sprint_member`
--
ALTER TABLE `sprint_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_name` (`user_name`),
  ADD KEY `sprint_name` (`sprint_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sprint_member`
--
ALTER TABLE `sprint_member`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
