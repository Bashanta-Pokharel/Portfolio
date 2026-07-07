-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3336
-- Generation Time: Oct 29, 2025 at 10:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jfc`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_application`
--

CREATE TABLE `accepted_application` (
  `id` int(111) NOT NULL,
  `application_id` int(111) NOT NULL,
  `job_id` int(111) NOT NULL,
  `fullname` varchar(111) NOT NULL,
  `email` varchar(111) NOT NULL,
  `phone` int(10) NOT NULL,
  `address` text NOT NULL,
  `skills` text NOT NULL,
  `experiences` text NOT NULL,
  `photo` varchar(100) NOT NULL,
  `cv` varchar(20) NOT NULL,
  `accepted_at` datetime NOT NULL,
  `username` varchar(50) NOT NULL,
  `cid` int(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_application`
--

INSERT INTO `accepted_application` (`id`, `application_id`, `job_id`, `fullname`, `email`, `phone`, `address`, `skills`, `experiences`, `photo`, `cv`, `accepted_at`, `username`, `cid`) VALUES
(13, 24, 22, 'bashanta pokharel', 'pokharelbashantabb@gmail.com', 2147483647, 'jorpati', 'php html css and finance management', '5 years in ....company', 'WhatsApp Image 2025-', 'download.pdf', '2025-10-15 17:22:31', 'bashanta', 7),
(14, 25, 21, 'sweekriti karki', 'pokharelbashantabb@gmail.com', 2147483647, 'jorpati', 'php html css and finance management', '25 yearsin ...........................company', 'myphoto.jpg', 'lumbin book_250916_0', '2025-10-15 17:47:53', 'bashanta', 7),
(15, 26, 22, 'sweekriti karki', 'sweekriti@gmail.com', 2147483647, 'jorpati kathmandu', 'php html css and finance management', '4 years in bppvt.ltd', 'pan.jpg', 'SL_LabWorks -Part2_D', '2025-10-15 18:47:34', 'sweekriti', 7),
(16, 27, 21, 'sweekriti karki', 'sweekriti@gmail.com', 2147483647, 'chabahel', 'php html css and driver management', '5 yers................company', 'goma didi.jpg', 'results.pdf', '2025-10-15 19:18:38', 'sweekriti', 7),
(17, 29, 23, 'sweekriti karki', 'pokharelbashantabb@gmail.com', 2147483647, 'lalitpur', 'php html css and finance management', '10+ in .......................', 'mamipic.jpg', 'mamimshramsweekriti.', '2025-10-17 01:08:05', 'sweekriti', 8);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('', ''),
('Bashanta', '$2y$10$.tVw8w0cuITCMuelpzBFaO7SrHIsIvAE4.Hg31zUc1myCzhZHMvFy'),
('kiran', '$2y$10$VPlzfeIg1AdZoM8GD5d4/eFzZkW8x3cCDn8SR8RZrkgUC5/RV9Cv2'),
('admin', '$2y$10$Nv8L8S7L7cZvfYlY0xGZLOP9UJO1n5fs./oxbvutVmaQVPvEThO8q');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `cid` int(3) NOT NULL,
  `company_name` varchar(40) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(30) NOT NULL,
  `address` varchar(50) NOT NULL,
  `company_pan` varchar(20) NOT NULL,
  `company_license` varchar(20) NOT NULL,
  `company_type` varchar(50) NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`cid`, `company_name`, `username`, `password`, `email`, `address`, `company_pan`, `company_license`, `company_type`, `datecreated`) VALUES
(7, 'bpholidays.pvt.ltd', 'bpholidays', '$2y$10$tUlXRn/wytiiyleGoqwpuudW9XzAAOqNJD0Yn8i.Hwk0.peutwkby', 'basaanta340@gmail.com', 'jorpati,Kathmandu', '122-23-20-006', '09-876-543-21', 'Service', '2025-10-14 23:33:48'),
(8, 'swekritisweets.pvt.ltd', 'sweeti', '$2y$10$wmP7tcL46snUSx2jxezm.ekSCiQkCWGnsr6fCUTZmtSBTFQLaNMfy', 'sweeti@gmail.com', 'Chabahel', '122-23-22-009', '23-45-67-8-999', 'IT', '2025-10-16 22:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `declined_application`
--

CREATE TABLE `declined_application` (
  `id` int(111) NOT NULL,
  `application_id` int(111) NOT NULL,
  `job_id` int(111) NOT NULL,
  `fullname` varchar(111) NOT NULL,
  `email` varchar(111) NOT NULL,
  `phone` int(10) NOT NULL,
  `address` text NOT NULL,
  `skills` text NOT NULL,
  `experiences` text NOT NULL,
  `photo` varchar(111) NOT NULL,
  `cv` varchar(111) NOT NULL,
  `rejected_at` datetime NOT NULL,
  `username` varchar(111) NOT NULL,
  `cid` int(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `declined_application`
--

INSERT INTO `declined_application` (`id`, `application_id`, `job_id`, `fullname`, `email`, `phone`, `address`, `skills`, `experiences`, `photo`, `cv`, `rejected_at`, `username`, `cid`) VALUES
(1, 28, 22, 'sweekriti karki', 'sweekriti@gmail.com', 2147483647, 'narayanghat', 'php html css and finance management', '7 years cooking', 'WhatsApp Image 2025-09-16 at 17.26.14_d074f6e8.jpg', 'mamashram.pdf', '2025-10-18 23:02:25', 'sweekriti', 7);

-- --------------------------------------------------------

--
-- Table structure for table `jobapplication`
--

CREATE TABLE `jobapplication` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `fullname` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` int(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `skills` varchar(200) NOT NULL,
  `experiences` text NOT NULL,
  `cv` varchar(200) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `applied_date` datetime NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobapplication`
--

INSERT INTO `jobapplication` (`id`, `job_id`, `fullname`, `email`, `phone`, `address`, `skills`, `experiences`, `cv`, `photo`, `applied_date`, `username`) VALUES
(30, 24, 'Sweekriti karki', 'Sweeti@gmail.con', 2147483647, 'Narayantar', 'marketing specilist,professional sells man', '2 years in bp.pvt.ltd', 'IDCROTAIA.pdf', 'WhatsApp Image 2025-09-16 at 17.26.14_d074f6e8.jpg', '2025-10-18 23:39:36', 'sweekriti');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(50) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `salary` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `username` varchar(20) NOT NULL,
  `openeddate` datetime NOT NULL,
  `expirydate` date NOT NULL,
  `category` varchar(50) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `location`, `qualification`, `salary`, `image`, `username`, `openeddate`, `expirydate`, `category`, `company_id`) VALUES
(21, 'Driver', ' driver needed for heavy vehicles', 'jorpati,ktm', 'minimum read write english', '150k-200k rs per/month', 'driver.jpeg', 'bpholidays', '2025-10-14 23:37:04', '2025-10-29', 'Transport & Logistics', 7),
(22, 'Accountant', 'finance manager', 'kalanki', 'MBA', '20000-80000 rs', 'account.jpg', 'bpholidays', '2025-10-15 10:00:39', '2025-10-24', 'Marketing & Sales', 7),
(23, 'programmer', 'we need a backend developer', 'bouddha', 'any it bachlor degreee', '150k to 190k', 'marketing.jpg', 'sweeti', '2025-10-16 22:23:40', '2025-10-31', 'IT & Software', 8),
(24, 'Marketing manager', 'we need a respsonsibe well marketing expert with expertise in this field.', 'Bagbazar,Kathmandu', 'Any marketing releted degree.', '100k to 120K /month', 'marketing.jpg', 'bpholidays', '2025-10-18 23:09:42', '2025-11-01', 'Marketing & Sales', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `qualification` varchar(50) NOT NULL,
  `skills` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `fname`, `lname`, `username`, `password`, `email`, `qualification`, `skills`, `gender`, `datecreated`) VALUES
(6, 'Bashanta', 'pokharel', 'bashanta', '$2y$10$/XNAUgi6uG1r.XAhJm8usuB.Yj1jGKyEbJHIOUSW729ytXn1u9yvu', 'pbasanta340@gmail.com', 'High School', 'HTML, CSS, PHP, Java', 'Male', '0000-00-00 00:00:00'),
(7, 'sweekriti', 'karki', 'sweekriti', '$2y$10$0FkJ.O0GWs2aeh5aoqCArO6gZpm2s23nlIy7APnoSlS/AIYLzGmTK', 'sweekriti@gmail.com', 'High School', 'HTML, CSS, PHP, MySQL', 'Female', '2025-10-15 18:39:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_application`
--
ALTER TABLE `accepted_application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `declined_application`
--
ALTER TABLE `declined_application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobapplication`
--
ALTER TABLE `jobapplication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accepted_application`
--
ALTER TABLE `accepted_application`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `cid` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `declined_application`
--
ALTER TABLE `declined_application`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobapplication`
--
ALTER TABLE `jobapplication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
