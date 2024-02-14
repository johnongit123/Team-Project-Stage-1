-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2024 at 04:02 PM
-- Server version: 8.0.36-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Team20`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigns`
--

CREATE TABLE `assigns` (
  `emp_id` int NOT NULL,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `job_role` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_reg` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `first_name`, `last_name`, `email`, `password`, `job_role`, `is_reg`) VALUES
(1000, 'John', 'Mikel', 'JohnM@makeitall.com', 'password', 'employee', 'Y'),
(1002, 'Romelu', 'Lukaku', 'RomeluL@makeitall.com', 'password2', 'admin', 'Y'),
(1111, 'Dean', 'Henderson', 'Deanh@makeitall.com', 'password1', 'team_lead', 'Y'),
(2423, 'Franco', 'Baresi', 'FrancoB@makeitall.com', '', 'employee', 'N'),
(2696, 'Eden', 'Hazard', 'EdenH@makeitall.com', '', 'Admin', 'N'),
(4242, 'Daphne', 'Wheeler', 'D.Wheeler@makeitall.com', '', 'employee', 'N'),
(7853, 'Adem', 'Lawson', 'A_law@makeitall.com', '', 'employee', 'N'),
(8729, 'Clarence', 'Seedorf', 'ClarenceS@makitall.com', '', 'team_lead', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `Personal Tasks`
--

CREATE TABLE `Personal Tasks` (
  `Task ID` int NOT NULL,
  `Task Name` varchar(50) NOT NULL,
  `Start Date` date NOT NULL,
  `End Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Personal Tasks`
--

INSERT INTO `Personal Tasks` (`Task ID`, `Task Name`, `Start Date`, `End Date`) VALUES
(4444, 'get file', '2024-02-10', '2024-02-14'),
(4444, 'get file', '2024-02-10', '2024-02-14');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int NOT NULL,
  `project_name` varchar(40) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Threads`
--

CREATE TABLE `Threads` (
  `ThreadID` int NOT NULL,
  `Title` varchar(250) NOT NULL,
  `Author` varchar(250) NOT NULL,
  `Date` date NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Threads`
--

INSERT INTO `Threads` (`ThreadID`, `Title`, `Author`, `Date`, `Content`) VALUES
(1, 'How to get rich quick.', 'John Obi Mikel', '2024-12-25', 'Get Rich'),
(2, 'How to say composed', 'Kobbie Mainoo', '2024-12-25', 'Practise');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigns`
--
ALTER TABLE `assigns`
  ADD PRIMARY KEY (`emp_id`,`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `Threads`
--
ALTER TABLE `Threads`
  ADD PRIMARY KEY (`ThreadID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigns`
--
ALTER TABLE `assigns`
  ADD CONSTRAINT `assigns_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigns_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
