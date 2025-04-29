-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 11:56 AM
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
-- Database: `student_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fullname`, `email`, `password`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$eW8isKOFdwXjmDSYXFyl1.x5Ep.QSCE3I12wOtVNVF6N87vK3o8kS');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `max_students` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_code`, `max_students`, `created_at`) VALUES
(13, 'การเขียนโปรแกรมเบื้องต้น', '125120', 0, '2025-04-02 07:02:33'),
(14, 'คณิตศาสตร์เพื่อการเรียนรู้', '112503', 0, '2025-04-02 07:04:05'),
(15, 'ComputerEN', '115021', 0, '2025-04-02 07:16:42'),
(16, 'CPE', '111230', 0, '2025-04-02 07:18:37'),
(17, 'การทำงานเป็นทีม', '101125', 0, '2025-04-02 08:38:39'),
(18, 'การทำงานด้วยภาษาอังกฤษ', '114685', 0, '2025-04-02 09:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `score` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `student_id`, `course_id`, `registered_at`) VALUES
(1, 1, 13, '2025-04-02 07:03:18'),
(2, 1, 14, '2025-04-02 07:05:43'),
(3, 4, 16, '2025-04-02 07:53:43'),
(4, 4, 15, '2025-04-02 07:53:46'),
(5, 4, 14, '2025-04-02 07:53:47'),
(6, 4, 13, '2025-04-02 07:53:47'),
(7, 1, 15, '2025-04-02 07:55:24'),
(8, 1, 16, '2025-04-02 07:55:25'),
(9, 5, 17, '2025-04-02 08:40:19'),
(10, 1, 17, '2025-04-02 09:12:32'),
(11, 5, 13, '2025-04-02 09:25:20'),
(12, 5, 14, '2025-04-02 09:28:27'),
(13, 6, 13, '2025-04-02 09:30:17'),
(14, 6, 14, '2025-04-02 09:31:48'),
(15, 6, 15, '2025-04-02 09:31:48'),
(16, 6, 16, '2025-04-02 09:33:58'),
(17, 6, 17, '2025-04-02 09:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `fullname`, `email`, `password`, `department`, `created_at`) VALUES
(1, 'Piyaphon Aumpiam', 'Linsama9113@gmail.com', '$2y$10$7DnBhGoaJ4qqZmAK836KNeUwsq9m./0dWL/KNGQVf4PHVNHiBg.TK', 'วิศวกรรม คอมพิวเตอร์', '2025-04-02 06:41:43'),
(4, 'Anupong Somdee', 'anupong@gmail.com', '$2y$10$SVda9PN053DffuqYyJMz/OcbhjqjueyKrz0CK1p0LH59aZ2C72C8y', 'วิศวกรรม คอมพิวเตอร์', '2025-04-02 07:53:11'),
(5, 'Supawadee Jansukswat', 'Su@gmail.com', '$2y$10$VxQ9ewj8SEayBEKxrIOTTea/g39pdQkjBYW8.RLR8wIDYlH/6ls6W', 'คณะมนุศาสตร์ สาขานิติ', '2025-04-02 08:39:56'),
(6, 'หียฟแ้ฟร ฟหกสาฟก', 'Linsa9113@gmail.com', '$2y$10$.DTofABDmt65kE9BKqvz3eHft1vizY0UCWl7fSbYiqA3viyGJ/txK', 'วิศวกรรม คอมพิวเตอร์', '2025-04-02 09:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_id`, `course_id`, `score`) VALUES
(1, 13, 80.00),
(1, 14, 40.00),
(1, 15, 80.00),
(1, 16, 80.00),
(1, 17, 55.00),
(4, 13, 96.00),
(4, 14, 60.00),
(4, 15, 75.00),
(4, 16, 46.00),
(5, 13, 65.00),
(5, 14, 77.00),
(5, 17, 10.00),
(6, 13, 72.00),
(6, 14, 70.00),
(6, 15, 14.00),
(6, 16, 39.00),
(6, 17, 66.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
