-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250707.de50d366ca
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2025 at 06:08 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cemeterease`
--

-- --------------------------------------------------------

--
-- Table structure for table `grave_points`
--

CREATE TABLE `grave_points` (
  `grave_id` int NOT NULL,
  `block` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `category` enum('bronze','silver','platinum','diamond') NOT NULL,
  `status` enum('available','reserved','occupied') NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `coordinates` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `grave_points`
--

INSERT INTO `grave_points` (`grave_id`, `block`, `category`, `status`, `label`, `coordinates`) VALUES
(1, 'A', 'diamond', 'occupied', NULL, '123.79769285129, 10.249193799482'),
(2, 'A', 'diamond', 'available', NULL, '123.79772218795, 10.249206732589'),
(3, 'A', 'silver', 'available', NULL, '123.79775692256, 10.249221975178'),
(4, 'A', 'silver', 'available', NULL, '123.7977887235, 10.249236063025'),
(5, 'A', 'diamond', 'available', NULL, '123.79773427465, 10.24917878784'),
(6, 'A', 'diamond', 'available', NULL, '123.79770376452, 10.249166316629'),
(7, 'A', 'bronze', 'available', NULL, '123.79782322341, 10.249251074665'),
(8, 'A', 'platinum', 'available', NULL, '123.79776900926, 10.249193799482'),
(9, 'A', 'platinum', 'available', NULL, '123.79780116224, 10.249206963537'),
(10, 'A', 'bronze', 'reserved', NULL, '123.79783613154, 10.249222206126');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activity`
--

CREATE TABLE `tbl_activity` (
  `act_id` int NOT NULL,
  `user_id` int NOT NULL,
  `act_title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `act_description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `act_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_activity`
--

INSERT INTO `tbl_activity` (`act_id`, `user_id`, `act_title`, `act_description`, `act_date`) VALUES
(68, 1, 'Order ORD-JGLL-7997 accepted', 'Order #ORD-JGLL-7997 is now active and payment has been confirmed.', '2025-06-08 14:31:13'),
(69, 1, 'Order ORD-XRFI-6388 accepted', 'Order #ORD-XRFI-6388 is now active and payment has been confirmed.', '2025-06-08 14:33:05'),
(70, 1, 'Order ORD-XRFI-6388 completed', 'Order #ORD-XRFI-6388 has been completed.', '2025-06-08 14:36:20'),
(71, 1, 'Order ORD-XRFI-6388 completed', 'Order #ORD-XRFI-6388 has been completed.', '2025-06-08 14:36:49'),
(72, 1, 'Order ORD-TMJD-0181 accepted', 'Order #ORD-TMJD-0181 is now active and payment has been confirmed.', '2025-06-16 19:39:01'),
(73, 1, 'Order ORD-VJBO-0174 accepted', 'Order #ORD-VJBO-0174 is now active and payment has been confirmed.', '2025-06-16 19:39:02'),
(74, 1, 'Order ORD-TMJD-0181 completed', 'Order #ORD-TMJD-0181 has been completed.', '2025-06-16 19:39:04'),
(75, 1, 'Order ORD-VJBO-0174 completed', 'Order #ORD-VJBO-0174 has been completed.', '2025-06-16 19:39:07'),
(76, 1, 'Order ORD-OFSH-3512 accepted', 'Order #ORD-OFSH-3512 is now active and payment has been confirmed.', '2025-06-19 17:47:46'),
(77, 1, 'Order ORD-HQKX-0706 accepted', 'Order #ORD-HQKX-0706 is now active and payment has been confirmed.', '2025-06-19 17:47:47'),
(78, 1, 'Order ORD-LNFU-2608 accepted', 'Order #ORD-LNFU-2608 is now active and payment has been confirmed.', '2025-06-19 17:47:47'),
(79, 1, 'Order ORD-FZOC-3846 accepted', 'Order #ORD-FZOC-3846 is now active and payment has been confirmed.', '2025-06-19 17:47:48'),
(80, 1, 'Order ORD-OFSH-3512 completed', 'Order #ORD-OFSH-3512 has been completed.', '2025-06-19 17:47:52'),
(81, 1, 'Order ORD-HQKX-0706 completed', 'Order #ORD-HQKX-0706 has been completed.', '2025-06-19 17:47:54'),
(82, 1, 'Order ORD-LNFU-2608 completed', 'Order #ORD-LNFU-2608 has been completed.', '2025-06-19 17:47:56'),
(83, 1, 'Order ORD-FZOC-3846 completed', 'Order #ORD-FZOC-3846 has been completed.', '2025-06-19 17:47:57'),
(84, 1, 'Order ORD-YUCU-7056 accepted', 'Order #ORD-YUCU-7056 is now active and payment has been confirmed.', '2025-06-20 17:40:40'),
(85, 1, 'Order ORD-YUCU-7056 completed', 'Order #ORD-YUCU-7056 has been completed.', '2025-06-20 17:49:09'),
(86, 1, 'Order ORD-NQYN-9236 accepted', 'Order #ORD-NQYN-9236 is now active and payment has been confirmed.', '2025-07-15 14:53:23'),
(87, 1, 'Order ORD-MHWY-7351 accepted', 'Order #ORD-MHWY-7351 is now active and payment has been confirmed.', '2025-07-15 14:53:24'),
(88, 1, 'Order ORD-GLGT-8597 accepted', 'Order #ORD-GLGT-8597 is now active and payment has been confirmed.', '2025-07-17 18:11:52'),
(89, 1, 'Order ORD-NQYN-9236 completed', 'Order #ORD-NQYN-9236 has been completed.', '2025-07-17 18:11:54'),
(90, 1, 'Order ORD-MHWY-7351 completed', 'Order #ORD-MHWY-7351 has been completed.', '2025-07-17 18:11:58'),
(91, 1, 'Order ORD-GLGT-8597 completed', 'Order #ORD-GLGT-8597 has been completed.', '2025-07-17 18:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `customer_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `religion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `citizenship` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customers`
--

INSERT INTO `tbl_customers` (`customer_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `nickname`, `address`, `contact_number`, `birth_date`, `gender`, `religion`, `citizenship`, `status`, `occupation`, `date_created`, `date_modified`) VALUES
(23, 14, 'Lucy', '', 'Ababa', 'archiealbarico69@gmail.com', 'chiekay', 'tunghaan, minglanilla, cebu, 6046', '09231226478', '2000-10-24', 'male', 'catholic', 'filipino', 'single', 'Private Employee', '2025-06-07 19:04:46', '2025-07-17 18:22:09'),
(24, 1, 'Florence', 'Brielle Velasquez', 'Nichols', 'caja@mailinator.com', 'Margaret Butler', 'Labore eveniet nobi', '+1 (591) 793-7254', '1998-01-12', 'male', NULL, NULL, '', NULL, '2025-06-08 11:57:26', '2025-06-22 14:39:34'),
(25, 15, 'lebron', 'king', 'james', 'bunujig@mailinator.com', 'Bron', 'Omnis possimus reru', '09491853866', '1995-07-17', 'female', 'Incidunt lorem expl', 'Ipsa non atque qui ', 'seperated', 'Self-Employed', '2025-06-08 12:18:52', '2025-06-08 13:39:11'),
(26, 17, 'cemeterease', '', 'memorial', 'cemeterease.memorial@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '2025-06-08 13:34:42', '2025-06-08 13:34:42'),
(29, NULL, 'Kyrie', '', 'Irving', 'archiealbarico69@gmail.com', 'Jolie Stark', 'Dolor ipsum enim po', '09634636306', '2004-05-27', 'male', 'Nesciunt deserunt d', 'Lorem culpa itaque ', 'married', 'Self-Employed', '2025-06-16 17:10:40', '2025-06-20 14:55:13'),
(34, NULL, 'Luca', '', 'Doncic', 'voni@mailinator.com', 'Luca', 'Aperiam veritatis mo', '09231226478', '1991-10-05', 'male', 'Culpa eos minima nis', 'Consequat Officia i', 'single', 'Government Employee', '2025-07-17 18:20:18', '2025-07-17 18:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_deceased`
--

CREATE TABLE `tbl_deceased` (
  `record_id` int NOT NULL,
  `grave_id` int NOT NULL,
  `lot_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `dead_fullname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dead_gender` enum('male','female') COLLATE utf8mb4_general_ci NOT NULL,
  `dead_citizenship` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dead_civil_status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dead_relationship` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dead_message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dead_bio` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dead_profile_link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dead_interment` date NOT NULL,
  `dead_birth_date` date NOT NULL,
  `dead_date_death` date NOT NULL,
  `dead_visibility` enum('public','private') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_deceased`
--

INSERT INTO `tbl_deceased` (`record_id`, `grave_id`, `lot_id`, `customer_id`, `dead_fullname`, `dead_gender`, `dead_citizenship`, `dead_civil_status`, `dead_relationship`, `dead_message`, `dead_bio`, `dead_profile_link`, `dead_interment`, `dead_birth_date`, `dead_date_death`, `dead_visibility`) VALUES
(34, 1, 28, 23, 'Lebron James', 'female', 'catholic', 'Married', 'Father-In-Law', 'Loving mother and grandmother', NULL, NULL, '2014-03-24', '2000-10-27', '2008-05-09', 'private'),
(35, 19, 29, 25, 'Cardo Dalisay', 'male', 'catholic', 'Seperated', 'Mother-In-Law', 'Forever in our hearts and memories', NULL, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1733845017/grave_images/grave_67585fea385ed.jpg', '2019-01-19', '1974-01-27', '2019-01-01', 'public');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_files`
--

CREATE TABLE `tbl_files` (
  `id` int NOT NULL,
  `grave_filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `record_id` int DEFAULT NULL,
  `location_id` int DEFAULT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_files`
--

INSERT INTO `tbl_files` (`id`, `grave_filename`, `record_id`, `location_id`, `date_uploaded`) VALUES
(48, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1752756875/Grave_Maintenance_-_Standard_copy_mzxqpt.jpg', 19, NULL, '2024-12-10'),
(49, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1752756582/9457a7ca-fa2f-4331-b32e-d0223db1fd8a_-_Edited_xkre2p.png', 19, NULL, '2024-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_locations`
--

CREATE TABLE `tbl_locations` (
  `location_id` int NOT NULL,
  `location_title` varchar(255) NOT NULL,
  `location_description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_locations`
--

INSERT INTO `tbl_locations` (`location_id`, `location_title`, `location_description`, `created_at`, `updated_at`) VALUES
(1, 'playground', 'this is the 1st playground', '2025-07-22 18:02:04', '2025-07-22 18:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lot`
--

CREATE TABLE `tbl_lot` (
  `lot_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `grave_id` int NOT NULL,
  `type` enum('deluxe','standard') COLLATE utf8mb4_general_ci NOT NULL,
  `payment_type` enum('installment','full') COLLATE utf8mb4_general_ci NOT NULL,
  `payment_frequency` enum('monthly','annually','none') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `last_payment_date` date DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `lot_status` enum('active','completed','cancelled') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_lot`
--

INSERT INTO `tbl_lot` (`lot_id`, `customer_id`, `grave_id`, `type`, `payment_type`, `payment_frequency`, `start_date`, `last_payment_date`, `next_due_date`, `lot_status`) VALUES
(28, 23, 1, 'standard', 'full', 'none', '2025-06-08', NULL, NULL, 'completed'),
(29, 25, 19, 'standard', 'installment', 'annually', '2025-06-08', NULL, '2026-06-08', 'active'),
(30, 29, 11, 'standard', 'installment', 'monthly', '2025-06-20', NULL, '2025-06-30', 'active'),
(31, 34, 96, 'deluxe', 'full', 'none', '2025-07-17', NULL, NULL, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notif_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` enum('error','success','info','warning') COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notif_id`, `title`, `message`, `link`, `type`, `is_read`, `created_at`) VALUES
(45, 'New Service Request', 'A new service request (ID: ORD-NJYZ-5854) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(46, 'New Service Request', 'A new service request (ID: ORD-JGLL-7997) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(47, 'New Service Request', 'A new service request (ID: ORD-XRFI-6388) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(48, 'New Service Request', 'A new service request (ID: ORD-TMJD-0181) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(49, 'New Service Request', 'A new service request (ID: ORD-VJBO-0174) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(50, 'New Service Request', 'A new service request (ID: ORD-OFSH-3512) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(51, 'New Service Request', 'A new service request (ID: ORD-HQKX-0706) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(52, 'New Service Request', 'A new service request (ID: ORD-LNFU-2608) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(53, 'New Service Request', 'A new service request (ID: ORD-FZOC-3846) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(54, 'New Service Request', 'A new service request (ID: ORD-YUCU-7056) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(55, 'New Service Request', 'A new service request (ID: ORD-NQYN-9236) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(56, 'New Service Request', 'A new service request (ID: ORD-MHWY-7351) for plot maintenance has been created.', NULL, 'success', 1, '2025-06-24 06:25:58'),
(57, 'New Service Request', 'A new service request (ID: ORD-GLGT-8597) for plot maintenance has been created.', NULL, 'success', 0, '2025-07-17 17:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `service_id` int NOT NULL,
  `grave_id` int NOT NULL,
  `deceased_id` int NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `payment_status` enum('paid','unpaid','cancelled') COLLATE utf8mb4_general_ci NOT NULL,
  `order_refnumber` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `instruction` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'None',
  `order_status` enum('complete','active','cancelled','pending') COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `customer_id`, `service_id`, `grave_id`, `deceased_id`, `payment_method`, `payment_status`, `order_refnumber`, `instruction`, `order_status`, `order_date`) VALUES
(116, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-OFSH-3512', '', 'complete', '2025-06-19'),
(118, 25, 12, 19, 35, 'in-person', 'paid', 'ORD-LNFU-2608', '', 'complete', '2025-06-19'),
(119, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-FZOC-3846', '', 'complete', '2025-06-19'),
(120, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-YUCU-7056', 'Butang sa grave', 'complete', '2025-06-20'),
(121, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-NQYN-9236', '', 'complete', '2025-06-24'),
(122, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-MHWY-7351', '', 'complete', '2025-06-24'),
(123, 25, 10, 19, 35, 'in-person', 'paid', 'ORD-GLGT-8597', '', 'complete', '2025-07-18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `service_id` int NOT NULL,
  `service_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `service_cost` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `service_availability` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `service_completion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`service_id`, `service_name`, `service_cost`, `service_availability`, `service_completion`) VALUES
(10, 'Grave Full Restoration', '1000', 'available', '3'),
(12, 'Grave Maintenance', '599', 'available', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int NOT NULL,
  `user_type` enum('admin','user','staff') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verification_token_expires` datetime DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `auth_provider` enum('manual','google') COLLATE utf8mb4_general_ci DEFAULT 'manual',
  `is_verified` tinyint(1) DEFAULT '0',
  `user_status` enum('active','inactive','archived') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_type`, `username`, `password`, `remember_token`, `reset_token`, `reset_token_expires`, `verification_token`, `verification_token_expires`, `google_id`, `auth_provider`, `is_verified`, `user_status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '$2y$10$lhap42nMrL87xdMPjom9E.ibylrODmbePjqv5gmzUbfjcUFlsA4Fm', NULL, 'ab970e7023b80876f2180215611f0e6a4eba1b36f540fcd7eae5d30ab184bb5e', '2025-06-10 16:56:08', NULL, NULL, NULL, 'manual', 1, 'active', '2025-07-18 07:42:20', '2025-06-07 23:30:41', '2025-07-18 07:42:20'),
(14, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '110789000195227629652', 'google', 1, 'active', NULL, '2025-06-08 03:04:46', '2025-06-08 20:02:22'),
(15, 'user', 'user', '$2y$10$20mKrz6yQiNOgD4Odv3SQ.t9.CyLkzutyHbJfjrl2WSMUXqQ0mcvC', NULL, NULL, NULL, NULL, NULL, NULL, 'manual', 1, 'active', '2025-07-21 14:32:07', '2025-06-08 19:12:28', '2025-07-21 14:32:07'),
(16, 'staff', 'staff', '$2y$10$xIeTHXr3M0WnPY6jRtov7OKZt8tIVS2E..0tssWErx3w4hL6DZfMG', NULL, NULL, NULL, NULL, NULL, NULL, 'manual', 1, 'active', '2025-07-15 23:53:03', '2025-06-08 19:12:47', '2025-07-15 23:53:03'),
(17, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '114207335796793648840', 'google', 1, 'active', NULL, '2025-06-08 21:34:42', '2025-06-08 21:34:42'),
(23, 'user', 'xoppal', '$2y$10$.lZoKQb2P.hIGai6Vnr4ye9v0Xr0PvXhEfTrCo0zDy9xuaBtox9wG', NULL, NULL, NULL, 'e8dd70ad45a532cfaad78932f767c614cb438f8247c675b12feadf0f36d06878', '2025-06-25 01:16:44', NULL, 'manual', 0, 'active', NULL, '2025-06-24 01:16:44', '2025-06-24 01:16:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grave_points`
--
ALTER TABLE `grave_points`
  ADD PRIMARY KEY (`grave_id`);

--
-- Indexes for table `tbl_activity`
--
ALTER TABLE `tbl_activity`
  ADD PRIMARY KEY (`act_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_deceased`
--
ALTER TABLE `tbl_deceased`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `grave_id` (`grave_id`),
  ADD KEY `lot_id` (`lot_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tbl_files`
--
ALTER TABLE `tbl_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `tbl_lot`
--
ALTER TABLE `tbl_lot`
  ADD PRIMARY KEY (`lot_id`),
  ADD KEY `grave_id` (`grave_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notif_id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orderer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`,`grave_id`,`deceased_id`),
  ADD KEY `deceased_id` (`deceased_id`),
  ADD KEY `grave_id` (`grave_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grave_points`
--
ALTER TABLE `grave_points`
  MODIFY `grave_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_activity`
--
ALTER TABLE `tbl_activity`
  MODIFY `act_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tbl_deceased`
--
ALTER TABLE `tbl_deceased`
  MODIFY `record_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_files`
--
ALTER TABLE `tbl_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  MODIFY `location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_lot`
--
ALTER TABLE `tbl_lot`
  MODIFY `lot_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notif_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_activity`
--
ALTER TABLE `tbl_activity`
  ADD CONSTRAINT `tbl_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

--
-- Constraints for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD CONSTRAINT `tbl_customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

--
-- Constraints for table `tbl_deceased`
--
ALTER TABLE `tbl_deceased`
  ADD CONSTRAINT `tbl_deceased_ibfk_1` FOREIGN KEY (`grave_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_deceased_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `tbl_lot` (`lot_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_deceased_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_files`
--
ALTER TABLE `tbl_files`
  ADD CONSTRAINT `tbl_files_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_files_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `tbl_locations` (`location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tbl_lot`
--
ALTER TABLE `tbl_lot`
  ADD CONSTRAINT `tbl_lot_ibfk_1` FOREIGN KEY (`grave_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_lot_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `tbl_services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_orders_ibfk_3` FOREIGN KEY (`deceased_id`) REFERENCES `tbl_deceased` (`record_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_orders_ibfk_4` FOREIGN KEY (`grave_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_orders_ibfk_5` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
