-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 19, 2024 at 06:52 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin1');

-- --------------------------------------------------------

--
-- Table structure for table `studentsdetails`
--

DROP TABLE IF EXISTS `studentsdetails`;
CREATE TABLE IF NOT EXISTS `studentsdetails` (
  `admissionNo` int NOT NULL AUTO_INCREMENT,
  `nameWithInitials` varchar(100) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `contactNumber` varchar(20) DEFAULT NULL,
  `address` text,
  `medium` varchar(50) DEFAULT NULL,
  `grade` int DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int DEFAULT NULL,
  `extraCurricular` text,
  `achievements` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`admissionNo`)
) ENGINE=MyISAM AUTO_INCREMENT=10251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `studentsdetails`
--

INSERT INTO `studentsdetails` (`admissionNo`, `nameWithInitials`, `fullName`, `contactNumber`, `address`, `medium`, `grade`, `class`, `dob`, `age`, `extraCurricular`, `achievements`, `created_at`, `updated_at`) VALUES
(20, 'indu', 'padu', '0724113376', 'maharagama', 'English', 4, 'A1', '2005-10-10', 19, 'hsadadaab,\r\ndajddadjab', 'dadhavd\r\ndaujd', '2024-10-17 17:41:48', '2024-10-17 18:19:17'),
(10, 'indu', 'padu', '0703213090', 'Colombo', 'Sinhala', 10, 'A', '2000-01-01', 24, 'AAA,\r\naaaaaaaa', 'AAA,\r\naaaaaaaa', '2024-10-17 17:55:10', '2024-10-17 17:56:07'),
(10250, 'indu', 'padu', '0703213090', 'maharagama', 'Sinhala', 12, 'B', '2002-10-10', 22, 'hsadadaab,\r\ndajddadjab', 'dadhavd\r\ndaujd', '2024-10-17 18:21:32', '2024-10-17 18:21:32'),
(150, 'javi', 'Javindu', '0703213090', 'maharagama', 'Sinhala', 11, 'B', '2005-02-10', 19, 'hsadadaab,\r\ndajddadjab', 'dadhavd\r\ndaujd', '2024-10-17 18:35:15', '2024-10-17 18:35:15'),
(50, 'Teacher', 'Teacher', '0777777777', 'maharagama', 'Sinhala', 5, 'B', '2005-10-05', 19, 'aaaaaaaaaaaaaaaaa,aaaaaaaaa,aaaaaaaaaaaa', 'aaaaaaaaaaaa,aaaaaaaaaaaaaa,aaaaaaaaaaaaaaaaaa', '2024-10-19 15:34:44', '2024-10-19 15:34:44'),
(100, 'Teacher', 'Teacher', '0777777777', 'Colombo', 'Sinhala', 5, 'B', '2000-10-10', 24, 'Sports, \r\nExtra, \r\nAbc', 'Sports, \r\nExtra, \r\nGame', '2024-10-18 05:13:52', '2024-10-18 05:15:29'),
(1000, 'Student a.b.c', 'Student student', '0777777777', 'Colombo', 'English', 5, 'B', '2010-10-10', 14, 'National,\r\nGames', 'Sports, \r\nGames,', '2024-10-19 15:58:55', '2024-10-19 16:00:17');

-- --------------------------------------------------------

--
-- Table structure for table `student_activities`
--

DROP TABLE IF EXISTS `student_activities`;
CREATE TABLE IF NOT EXISTS `student_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `activity` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `age` int NOT NULL,
  `first_appointment` date NOT NULL,
  `current_school_appointment` date NOT NULL,
  `time_in_school` varchar(50) NOT NULL,
  `appointment_type` enum('Graduate','Training') NOT NULL,
  `subject` text NOT NULL,
  `professional_qualifications` text NOT NULL,
  `educational_qualifications` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `full_name`, `username`, `password`, `address`, `phone`, `dob`, `age`, `first_appointment`, `current_school_appointment`, `time_in_school`, `appointment_type`, `subject`, `professional_qualifications`, `educational_qualifications`) VALUES
(1, 'Javindu', 'teacher', '$2y$10$TJUEzep9lDB.ZNuKOOnlW.H79hATclBcrloNZ4l49mf8xz6k6Oyd2', 'maharagama', '0703213090', '2000-10-10', 24, '2005-10-10', '2010-10-10', '19 years, 0 months', 'Graduate', 'vdsv', 'tf', 'hfh'),
(3, 'Test test', 'Test', '$2y$10$UjE7t3K0bx.Kx/nonr40LuTOc.6OU9P18XO7ubNh3rRbJt2ZR9Cu6', 'Colombo', '0777777777', '1999-05-10', 25, '2002-10-10', '2010-10-10', '22 years, 0 months', 'Graduate', 'English,\r\nSinhala,\r\nMaths', 'Graduate,\r\nHnd\r\nNvq', 'Graduate,\r\nHnd\r\nNvq'),
(4, 'Paduma', 'paduma', '$2y$10$2wVyohTX0FTWLWutrQhFkuXOZ19bvipuYBI9SGRJxDSlUZblutuuG', 'maharagama', '0703213090', '1995-10-10', 29, '2005-10-10', '2010-10-10', '19 years, 0 months', 'Graduate', 'English,\r\nEnglish', 'Graduate,\r\nGraduate,\r\nGraduate,', 'Graduate,Graduate,Graduate,');

DELIMITER $$
--
-- Events
--
DROP EVENT IF EXISTS `update_student_ages`$$
CREATE DEFINER=`root`@`localhost` EVENT `update_student_ages` ON SCHEDULE EVERY 1 YEAR STARTS '2024-01-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE studentsdetails
SET age = YEAR(CURDATE()) - YEAR(dob) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(dob, '%m%d'))$$

DROP EVENT IF EXISTS `update_teacher_age_and_time`$$
CREATE DEFINER=`root`@`localhost` EVENT `update_teacher_age_and_time` ON SCHEDULE EVERY 1 DAY STARTS '2024-10-19 23:57:15' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE teacher 
  SET 
    age = TIMESTAMPDIFF(YEAR, dob, CURDATE()), -- Calculate the age based on dob
    time_in_school = CONCAT(TIMESTAMPDIFF(YEAR, first_appointment, CURDATE()), ' years, ', TIMESTAMPDIFF(MONTH, first_appointment, CURDATE()) % 12, ' months')$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
