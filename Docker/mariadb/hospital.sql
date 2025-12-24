-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Dec 12, 2025 at 11:38 PM
-- Server version: 10.8.8-MariaDB-1:10.8.8+maria~ubu2204
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user_id`, `username`, `action_type`, `description`, `timestamp`) VALUES
(13, 'admin01', 'jelina', 'LOGIN', 'User Logged In', '2025-12-12 19:43:06'),
(15, 'doc01', 'mceards', 'LOGIN', 'User Logged In', '2025-12-12 19:46:03'),
(16, 'doc01', 'mceards', 'PRESCRIBE', 'Prescribed Test ID 9 for Patient W0456', '2025-12-12 19:46:38'),
(17, 'doc01', 'mceards', 'PARKING REQUEST', 'Requested Monthly permit for car LJ 2UK', '2025-12-12 19:48:27'),
(18, 'doc01', 'mceards', 'UPDATE PROFILE', 'User updated password or address', '2025-12-12 19:48:41'),
(19, 'admin01', 'jelina', 'LOGIN', 'User Logged In', '2025-12-12 19:49:08'),
(20, 'admin01', 'jelina', 'PARKING UPDATE', 'Updated request 8 to Approved', '2025-12-12 19:49:42'),
(21, 'admin01', 'jelina', 'ADD DOCTOR', 'Created account for Dr. Bean (DOC025)', '2025-12-12 19:52:38'),
(22, 'doc02', 'moorland', 'LOGIN', 'User Logged In', '2025-12-12 19:52:54'),
(23, 'doc02', 'moorland', 'ADD PATIENT', 'Registered new patient Jessica Molly (W0456)', '2025-12-12 19:54:01'),
(24, 'doc02', 'moorland', 'ADMIT PATIENT', 'Admitted patient W0456 to Ward 4', '2025-12-12 19:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `staffno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `specialisation` varchar(20) NOT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `pay` int(11) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `consultantstatus` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`staffno`, `firstname`, `lastname`, `specialisation`, `qualification`, `pay`, `gender`, `consultantstatus`, `address`, `username`, `password`) VALUES
('admin01', 'Jelina', 'Admin', 'Specialist', NULL, 60000, 'F', 1, 'Admin Office', 'jelina', 'iron99'),
('CH007', 'Steve', 'Fan', 'General', NULL, 67000, 'M', 1, '45 The Barnum Nottingham NG2 6TY', 'sfan', 'password01'),
('doc01', 'Mike', 'Ceards', 'Specialist', NULL, 50000, 'M', 1, 'Nottingham', 'mceards', 'lord456'),
('doc02', 'Sarah', 'Moorland', 'General', NULL, 48000, 'F', 0, 'QMC Nottingham', 'moorland', 'buzz48'),
('DOC025', 'Jacob', 'Bean', '0', NULL, 45000, NULL, 0, '7, Wollaton, Nottingham', 'jacobean', 'xyz456'),
('DOC111', 'Jas', 'Lyn', 'Specialist', 'MS', 70000, 'F', 1, 'Nottingham, UK', 'jaslyn', 'mjdrmuk10'),
('DOC8081', 'Melancholy', 'Doc', 'Specialist', NULL, 97000, 'M', 1, '40 Kingston Rd, Nottingham', 'docmel', 'melon81'),
('GT067', 'Julie', 'Ford', 'General', 'CCT', 66000, 'F', 1, NULL, 'jford', 'password02'),
('QM003', 'Joel', 'Graham', 'General', NULL, 44000, 'M', 0, '1 Chatsworth Avenue, Carlton, Nottingham, NG4', 'jgraham', 'password03'),
('QM004', 'Jason', 'Atkin', 'General', 'CCT', 60000, 'M', 1, '102 Leeming Lane South, Mansfield Woodhouse, Mansfield', 'jatkin', 'password04'),
('QM009', 'Grazziela', 'Luis', 'General', 'CCT', 62000, 'F', 1, '16 Lenton Boulevard, Lenton, Nottingham, NG7 2ES', 'gluis', 'password05'),
('QM122', 'David', 'Ulrik', 'General', NULL, 46000, 'M', 0, '3 Rolleston Drive, Nottingham', 'dulrik', 'password06'),
('QM267', 'Andrew', 'Xin', 'General', 'CCT', 58000, 'M', 1, '44 Dunlop Avenue, Lenton, Nottingham NG1 5AW', 'axin', 'password07'),
('QM300', 'Joy', 'Liz', 'General', 'CCT', 52000, 'F', 0, '55 Wishford Avenue, Lenton, Nottingham', 'jliz', 'password08'),
('QT001', 'Martin', 'Peter', 'General', NULL, 48000, 'M', 0, '47 Derby Road, Nottingham, NG1 5AW', 'mpeter', 'password09');

-- --------------------------------------------------------

--
-- Table structure for table `parking_permit`
--

CREATE TABLE `parking_permit` (
  `request_id` int(11) NOT NULL,
  `doctor_id` varchar(100) DEFAULT NULL,
  `car_reg` varchar(20) DEFAULT NULL,
  `permit_type` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `request_date` datetime DEFAULT current_timestamp(),
  `permit_number` varchar(50) DEFAULT NULL,
  `admin_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_permit`
--

INSERT INTO `parking_permit` (`request_id`, `doctor_id`, `car_reg`, `permit_type`, `status`, `request_date`, `permit_number`, `admin_comment`) VALUES
(8, 'doc01', 'LJ 2UK', 'Monthly', 'Approved', '2025-12-12 19:48:27', '1245', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `NHSno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `emergencyphone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`NHSno`, `firstname`, `lastname`, `phone`, `address`, `age`, `gender`, `emergencyphone`) VALUES
('NHS1234', 'Alex', 'Alex', '078594545454', '44 Lake Street, Nottingham, NG7 4BT', 10, 'F', NULL),
('W0111', 'Jess', 'Bean', '07456877798', '40A, Richmond Upon Thames', 25, 'F', NULL),
('W0456', 'Jessica', 'Molly', '07941653810', '40A, Richmond Upon Thames', 24, 'F', NULL),
('W20616', 'Zoya', 'Kalim', '07656999653', '668 Watnall Road, Hucknall, Nottingham, NG15', 18, 'F', NULL),
('W20620', 'Nazia', 'Rafiq', '07798522777', '1 Pelham Crescent, Beeston NG9', 37, 'F', NULL),
('W21028', 'Max', 'Wilson', '07740312868', '4 Lake Street, Nottingham, NG7 4BT', 33, 'M', NULL),
('W21758', 'Alex', 'Kai', '06654742456', '52 Chatsworth Avenue, Carlton, Nottingham, NG4', 46, 'M', NULL),
('W21814', 'Chao', 'Chen', '077 25 765428', 'Lake Street, Nottingham, NG7 4BT\r\n\r\n', 36, 'M', NULL),
('W21895', 'Liz', 'Felton', '074 56 733 487', '100 Hawton Crescent, Wollaton, NG8 1BZ', 23, 'F', NULL),
('W21961', 'Jeremie ', 'Clos', '07754312868', '22 Hawton Crescent, Wollaton, NG8 1BZ', 45, 'M', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patientexamination`
--

CREATE TABLE `patientexamination` (
  `patientid` varchar(100) NOT NULL,
  `doctorid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientexamination`
--

INSERT INTO `patientexamination` (`patientid`, `doctorid`, `date`, `time`) VALUES
('W20616', 'CH007', '2023-12-21', '11:23:11'),
('W20616', 'QM004', '2022-10-18', '10:23:19'),
('W20616', 'QM267', '2022-02-02', '08:23:19'),
('W20620', 'GT067', '2023-06-18', '07:06:05'),
('W20620', 'QM300', '2023-11-08', '09:09:19'),
('W21028', 'QM003', '2021-11-08', '09:23:19'),
('W21758', 'GT067', '2020-11-11', '11:23:05'),
('W21814', 'QM122', '2023-12-12', '02:02:10'),
('W21814', 'QT001', '2016-03-03', '08:18:18'),
('W21895', 'QM003', '2019-11-19', '08:09:10'),
('W21895', 'QM009', '2021-11-19', '08:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `patient_test`
--

CREATE TABLE `patient_test` (
  `pid` varchar(100) NOT NULL,
  `testid` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `report` varchar(100) DEFAULT NULL,
  `doctorid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_test`
--

INSERT INTO `patient_test` (`pid`, `testid`, `date`, `report`, `doctorid`) VALUES
('W0456', 9, '2025-12-12', 'Pending', 'doc01'),
('W20616', 6, '2023-10-01', NULL, 'QM003'),
('W20616', 16, '2025-12-12', 'Pending', 'doc01'),
('W20620', 1, '2025-12-10', 'Pending', 'doc01'),
('W20620', 3, '2025-12-10', 'Pending', 'doc01'),
('W20620', 8, '2025-12-10', 'Pending', 'doc01'),
('W20620', 9, '2025-12-10', 'Pending', 'doc01'),
('W20620', 10, '2025-12-10', 'Pending', 'doc01'),
('W20620', 13, '2025-12-11', 'Pending', 'doc01'),
('W20620', 14, '2025-12-10', 'Pending', 'doc01'),
('W21028', 3, '2021-11-07', NULL, 'QM004'),
('W21028', 4, '2025-12-10', 'Pending', 'doc01'),
('W21028', 8, '2021-11-11', NULL, 'QM004'),
('W21028', 10, '2025-12-10', 'Pending', 'doc01'),
('W21028', 11, '2025-12-11', 'Pending', 'doc01'),
('W21028', 14, '2025-12-10', 'Pending', 'doc01'),
('W21758', 6, '', NULL, 'CH007'),
('W21758', 12, '', NULL, 'QM122'),
('W21814', 3, '2023-02-17', NULL, 'QM267'),
('W21814', 3, '2023-02-18', NULL, 'QM300'),
('W21814', 5, '', NULL, 'QM009'),
('W21814', 17, '2025-12-12', 'Pending', 'doc01'),
('W21895', 5, '2023-06-07', NULL, 'QM300'),
('W21895', 5, '2023-06-08', NULL, 'QM267'),
('W21895', 7, '2023-06-09', NULL, 'CH007'),
('W21961', 4, '2019-10-18', NULL, 'QM004');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `testid` int(11) NOT NULL,
  `testname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testid`, `testname`) VALUES
(1, 'Blood count'),
(2, 'Urinalysis'),
(3, 'CT scan'),
(4, 'Ultrasonography'),
(5, 'Colonoscopy'),
(6, 'Genetic testing'),
(7, 'Hematocrit'),
(8, 'Pap smear'),
(9, 'X-ray'),
(10, 'Biopsy'),
(11, 'Mammography'),
(12, 'Lumbar puncture'),
(13, 'thyroid function test'),
(14, 'prenatal testing'),
(15, 'electrocardiography'),
(16, 'skin test'),
(17, 'Bilirubin blood test');

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `wardid` int(11) NOT NULL,
  `wardname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `noofbeds` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`wardid`, `wardname`, `address`, `phone`, `noofbeds`) VALUES
(1, 'Dermatology', 'Floor A Room 234 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 970 9215', 45),
(2, 'Urology', 'Queen\'s Medical Centre, Derby Rd, Lenton, Nottingham NG7 2UG', '0115 870 9215', 43),
(3, 'Orthopaedics ', 'Floor C Room 234 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 678 9215', 33),
(4, 'Accident and emergency', 'Queen\'s Medical Centre, Derby Rd, Lenton, Nottingham NG7 2UH', '0115 986 9215', 66),
(5, 'Cardiology', 'Floor A Room 32 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 986 6578', 67);

-- --------------------------------------------------------

--
-- Table structure for table `wardpatientaddmission`
--

CREATE TABLE `wardpatientaddmission` (
  `pid` varchar(100) NOT NULL,
  `wardid` int(11) NOT NULL,
  `consultantid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wardpatientaddmission`
--

INSERT INTO `wardpatientaddmission` (`pid`, `wardid`, `consultantid`, `date`, `time`, `status`) VALUES
('NHS1234', 4, 'doc02', '2025-12-12', '07:49:50', 1),
('W0456', 4, 'doc02', '2025-12-12', '19:54:42', 1),
('W20616', 1, 'QM004', '2022-10-07', '09:23:19', 1),
('W20616', 2, 'QM122', '2023-10-01', '07:23:19', 1),
('W20616', 3, 'QM009', '2018-12-07', '08:13:55', 1),
('W20616', 5, 'QM267', '2022-06-07', '21:23:19', 0),
('W20620', 4, 'QM267', '2021-10-07', '08:08:08', 1),
('W21028', 2, 'CH007', '2021-11-07', '08:23:19', 0),
('W21758', 2, 'QM122', '2018-11-27', '23:55:56', 0),
('W21758', 4, 'QT001', '2023-09-29', '08:23:19', 1),
('W21814', 3, 'QM003', '2023-02-17', '08:33:33', 1),
('W21895', 4, 'CH007', '2023-06-07', '21:23:19', 0),
('W21961', 5, 'QM009', '2019-10-18', '08:34:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`staffno`);

--
-- Indexes for table `parking_permit`
--
ALTER TABLE `parking_permit`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`NHSno`);

--
-- Indexes for table `patientexamination`
--
ALTER TABLE `patientexamination`
  ADD PRIMARY KEY (`patientid`,`doctorid`,`date`,`time`);

--
-- Indexes for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD PRIMARY KEY (`pid`,`testid`,`date`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`testid`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`wardid`);

--
-- Indexes for table `wardpatientaddmission`
--
ALTER TABLE `wardpatientaddmission`
  ADD PRIMARY KEY (`pid`,`wardid`,`consultantid`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `parking_permit`
--
ALTER TABLE `parking_permit`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
