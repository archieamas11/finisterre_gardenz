-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250707.de50d366ca
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2025 at 12:04 AM
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
  `block` varchar(255) NOT NULL,
  `status` enum('vacant','reserved','occupied1','occupied2') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'vacant',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `coordinates` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `grave_points`
--

INSERT INTO `grave_points` (`grave_id`, `block`, `status`, `label`, `coordinates`) VALUES
(1, 'A', 'occupied1', NULL, '123.79532987333, 10.251576886059'),
(2, 'A', 'vacant', NULL, '123.79538997542, 10.251603769059'),
(3, 'A', 'vacant', NULL, '123.79545250587, 10.251632444257'),
(4, 'A', 'vacant', NULL, '123.79551442923, 10.251659924652'),
(5, 'A', 'vacant', NULL, '123.79557331713, 10.251686210245'),
(6, 'A', 'vacant', NULL, '123.79563159794, 10.251712495837'),
(7, 'A', 'vacant', NULL, '123.79536326338, 10.25151714605'),
(8, 'A', 'vacant', NULL, '123.79542215128, 10.251548210856'),
(9, 'A', 'vacant', NULL, '123.79548225337, 10.251582860059'),
(10, 'A', 'vacant', NULL, '123.79554296254, 10.251612730059'),
(11, 'A', 'reserved', NULL, '123.79560063626, 10.251640210456'),
(12, 'A', 'vacant', NULL, '123.79565527452, 10.25166888565'),
(13, 'A', 'vacant', NULL, '123.79540272434, 10.251452626828'),
(14, 'A', 'vacant', NULL, '123.79546039806, 10.251487276041'),
(15, 'A', 'vacant', NULL, '123.79551928596, 10.25151893825'),
(16, 'A', 'vacant', NULL, '123.79557695968, 10.251551197856'),
(17, 'A', 'vacant', NULL, '123.79563038376, 10.251582262659'),
(18, 'A', 'vacant', NULL, '123.79568137946, 10.251613924858'),
(19, 'A', 'occupied1', NULL, '123.79544036403, 10.251389899794'),
(20, 'A', 'vacant', NULL, '123.79549560938, 10.251419172411'),
(21, 'A', 'vacant', NULL, '123.79555631856, 10.251452029428'),
(22, 'A', 'vacant', NULL, '123.79561399228, 10.251489665642'),
(23, 'A', 'vacant', NULL, '123.79566984472, 10.25151714605'),
(24, 'A', 'vacant', NULL, '123.79571719788, 10.251542236855'),
(25, 'A', 'vacant', NULL, '123.79548043209, 10.251322990944'),
(26, 'A', 'vacant', NULL, '123.79553324907, 10.251353458368'),
(27, 'A', 'vacant', NULL, '123.79558970861, 10.251388107593'),
(28, 'A', 'vacant', NULL, '123.79565163197, 10.251421562013'),
(29, 'A', 'vacant', NULL, '123.79570687732, 10.25145740603'),
(30, 'A', 'vacant', NULL, '123.7957524092, 10.251478912438'),
(31, 'B', 'vacant', NULL, '123.79572994681, 10.251756703417'),
(32, 'B', 'vacant', NULL, '123.7957821567, 10.251780002004'),
(33, 'B', 'vacant', NULL, '123.79583800914, 10.25180330059'),
(34, 'B', 'vacant', NULL, '123.79588961194, 10.251827196573'),
(35, 'B', 'vacant', NULL, '123.79594728566, 10.251851689954'),
(36, 'B', 'vacant', NULL, '123.79599646009, 10.251872598936'),
(37, 'B', 'vacant', NULL, '123.79574998083, 10.251717275035'),
(38, 'B', 'vacant', NULL, '123.79580219073, 10.251745352823'),
(39, 'B', 'vacant', NULL, '123.79585622189, 10.25176984621'),
(40, 'B', 'vacant', NULL, '123.79591025306, 10.251793742196'),
(41, 'B', 'vacant', NULL, '123.79596671259, 10.251815248581'),
(42, 'B', 'vacant', NULL, '123.79602135085, 10.251838547164'),
(43, 'B', 'vacant', NULL, '123.79577608578, 10.251673067449'),
(44, 'B', 'vacant', NULL, '123.7958264744, 10.251701742641'),
(45, 'B', 'vacant', NULL, '123.79587929138, 10.25172922303'),
(46, 'B', 'vacant', NULL, '123.79593210836, 10.251756106017'),
(47, 'B', 'vacant', NULL, '123.79598431826, 10.251784781201'),
(48, 'B', 'vacant', NULL, '123.79603834942, 10.251815248581'),
(49, 'B', 'vacant', NULL, '123.7958112971, 10.251609743059'),
(50, 'B', 'vacant', NULL, '123.7958628999, 10.251640807856'),
(51, 'B', 'vacant', NULL, '123.79591389561, 10.25166769085'),
(52, 'B', 'vacant', NULL, '123.79596307004, 10.251697560842'),
(53, 'B', 'vacant', NULL, '123.79601527994, 10.251727430831'),
(54, 'B', 'vacant', NULL, '123.79607598911, 10.251763872213'),
(55, 'B', 'vacant', NULL, '123.79610452243, 10.251723846432'),
(56, 'B', 'vacant', NULL, '123.79604138488, 10.251685612846'),
(57, 'B', 'vacant', NULL, '123.79598917499, 10.251652158454'),
(58, 'B', 'vacant', NULL, '123.79594060765, 10.251621691058'),
(59, 'B', 'vacant', NULL, '123.79588900485, 10.251589431459'),
(60, 'B', 'vacant', NULL, '123.79584165169, 10.251556574457'),
(61, 'C', 'vacant', NULL, '123.79553021362, 10.251241147063'),
(62, 'C', 'vacant', NULL, '123.79558242351, 10.251272809297'),
(63, 'C', 'vacant', NULL, '123.79564252559, 10.251303874127'),
(64, 'C', 'vacant', NULL, '123.79570262768, 10.251336731155'),
(65, 'C', 'vacant', NULL, '123.79575787303, 10.251364808977'),
(66, 'C', 'vacant', NULL, '123.79556724621, 10.251180809591'),
(67, 'C', 'vacant', NULL, '123.7955976008, 10.251124653912'),
(68, 'C', 'vacant', NULL, '123.79562916957, 10.251076861838'),
(69, 'C', 'vacant', NULL, '123.79564981069, 10.251040420377'),
(70, 'C', 'vacant', NULL, '123.79570384186, 10.251069095625'),
(71, 'C', 'vacant', NULL, '123.79575848012, 10.25110314748'),
(72, 'C', 'vacant', NULL, '123.7958191893, 10.251136004529'),
(73, 'C', 'vacant', NULL, '123.79587322046, 10.251164679769'),
(74, 'C', 'vacant', NULL, '123.79568077237, 10.251106134485'),
(75, 'C', 'vacant', NULL, '123.795737839, 10.251138991533'),
(76, 'C', 'vacant', NULL, '123.79579733399, 10.251170653777'),
(77, 'C', 'vacant', NULL, '123.79585015098, 10.251201121216'),
(78, 'C', 'vacant', NULL, '123.79565102488, 10.251153926554'),
(79, 'C', 'vacant', NULL, '123.79570869859, 10.2511879784'),
(80, 'C', 'vacant', NULL, '123.79576819359, 10.251221432841'),
(81, 'C', 'vacant', NULL, '123.79581979639, 10.251251302875'),
(82, 'C', 'vacant', NULL, '123.79561824192, 10.251211277029'),
(83, 'C', 'vacant', NULL, '123.7956789511, 10.251242939265'),
(84, 'C', 'vacant', NULL, '123.79573419645, 10.2512757963'),
(85, 'C', 'vacant', NULL, '123.79579187017, 10.25130745853'),
(86, 'D', 'vacant', NULL, '123.79593939346, 10.251409016606'),
(87, 'D', 'vacant', NULL, '123.79600131682, 10.251447847626'),
(88, 'D', 'vacant', NULL, '123.79605291963, 10.251480704639'),
(89, 'D', 'vacant', NULL, '123.79611180753, 10.251519535651'),
(90, 'D', 'vacant', NULL, '123.79617190961, 10.251554782257'),
(91, 'D', 'vacant', NULL, '123.7962314046, 10.251598989859'),
(92, 'D', 'vacant', NULL, '123.79628543577, 10.251628262457'),
(93, 'D', 'vacant', NULL, '123.79631700454, 10.251644989655'),
(94, 'D', 'vacant', NULL, '123.7959588204, 10.251348679165'),
(95, 'D', 'vacant', NULL, '123.79602013667, 10.251385120591'),
(96, 'D', 'reserved', NULL, '123.79606688274, 10.251415588009'),
(97, 'D', 'vacant', NULL, '123.79612637773, 10.251450237227'),
(98, 'D', 'vacant', NULL, '123.79618708691, 10.251488470842'),
(99, 'D', 'vacant', NULL, '123.79625083154, 10.251527899252'),
(100, 'D', 'vacant', NULL, '123.79630911235, 10.251562548458'),
(101, 'D', 'vacant', NULL, '123.7963643577, 10.251599587259'),
(102, 'D', 'vacant', NULL, '123.79597885443, 10.251291328715'),
(103, 'D', 'vacant', NULL, '123.79603652815, 10.251324185745'),
(104, 'D', 'vacant', NULL, '123.79609420187, 10.251358237572'),
(105, 'D', 'vacant', NULL, '123.79615491104, 10.251394678997'),
(106, 'D', 'vacant', NULL, '123.79620287129, 10.251424549014'),
(107, 'D', 'vacant', NULL, '123.79626904429, 10.251464574833'),
(108, 'D', 'vacant', NULL, '123.79632975347, 10.251501613646'),
(109, 'D', 'vacant', NULL, '123.79641231795, 10.251550003056'),
(110, 'D', 'vacant', NULL, '123.79599828137, 10.251233978255'),
(111, 'D', 'vacant', NULL, '123.79605291963, 10.251263848288'),
(112, 'D', 'vacant', NULL, '123.79611302171, 10.251298497522'),
(113, 'D', 'vacant', NULL, '123.79617555216, 10.25133015975'),
(114, 'D', 'vacant', NULL, '123.79622047695, 10.251352263567'),
(115, 'D', 'vacant', NULL, '123.79628907832, 10.251392289395'),
(116, 'D', 'vacant', NULL, '123.79635403714, 10.251428133416'),
(117, 'D', 'vacant', NULL, '123.79646817039, 10.251486081241'),
(118, 'D', 'vacant', NULL, '123.7960256005, 10.25116527717'),
(119, 'D', 'vacant', NULL, '123.79607173947, 10.251194549808'),
(120, 'D', 'vacant', NULL, '123.79613669829, 10.251230991252'),
(121, 'D', 'vacant', NULL, '123.79619862165, 10.251263848288'),
(122, 'D', 'vacant', NULL, '123.79623686843, 10.251287744312'),
(123, 'D', 'vacant', NULL, '123.79630729107, 10.251326575347'),
(124, 'D', 'vacant', NULL, '123.79637285699, 10.251366601178'),
(125, 'C', 'vacant', NULL, '123.79577729996, 10.251070290427'),
(126, 'C', 'vacant', NULL, '123.79584590133, 10.251090004659'),
(127, 'C', 'vacant', NULL, '123.79590782469, 10.251113303295');

-- --------------------------------------------------------

--
-- Table structure for table `grave_points_backup`
--

CREATE TABLE `grave_points_backup` (
  `grave_id` int NOT NULL,
  `block` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('vacant','occupied1','occupied2','reserved') COLLATE utf8mb4_general_ci NOT NULL,
  `label` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `coordinates` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grave_points_backup`
--

INSERT INTO `grave_points_backup` (`grave_id`, `block`, `status`, `label`, `coordinates`) VALUES
(1, 'A', 'occupied1', '', '123.795299160422203, 10.25162079742365'),
(2, 'A', 'vacant', '', '123.795334064117114, 10.251637793619759'),
(3, 'A', 'vacant', '', '123.79536608915673, 10.251651603028426'),
(4, 'A', 'vacant', '', '123.795404951002567, 10.251669661485005'),
(5, 'A', 'vacant', '', '123.795436256378409, 10.251682762717563'),
(6, 'A', 'vacant', '', '123.795468641249954, 10.251698696648321'),
(7, 'A', 'vacant', '', '123.795500306457669, 10.251711089705028'),
(8, 'A', 'vacant', '', '123.795534130656833, 10.25172666954705'),
(9, 'A', 'vacant', '', '123.79556363687314, 10.251740124864549'),
(10, 'A', 'vacant', '', '123.795596021744686, 10.251754642443311'),
(11, 'A', 'reserved', '', '123.795321470000388, 10.251576890579488'),
(12, 'B', 'vacant', '', '123.795357453191002, 10.251592470428115'),
(13, 'A', 'vacant', '', '123.795390557726336, 10.251607696188531'),
(14, 'A', 'vacant', '', '123.795425821253147, 10.251625046472764'),
(15, 'A', 'vacant', '', '123.795456766797059, 10.251638501794577'),
(16, 'A', 'vacant', '', '123.795488432004774, 10.251653373465389'),
(17, 'A', 'vacant', '', '123.795519377548686, 10.251667536960753'),
(18, 'A', 'vacant', '', '123.795552841915949, 10.251682408630202'),
(19, 'A', 'occupied1', '', '123.795583067796059, 10.251696572124267');

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
  `record_id` int NOT NULL,
  `date_uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_files`
--

INSERT INTO `tbl_files` (`id`, `grave_filename`, `record_id`, `date_uploaded`) VALUES
(48, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1752756875/Grave_Maintenance_-_Standard_copy_mzxqpt.jpg', 19, '2024-12-10'),
(49, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1752756582/9457a7ca-fa2f-4331-b32e-d0223db1fd8a_-_Edited_xkre2p.png', 19, '2024-12-10');

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
-- Table structure for table `tbl_plot_files`
--

CREATE TABLE `tbl_plot_files` (
  `plot_files_id` int NOT NULL,
  `grave_id` int NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_plot_files`
--

INSERT INTO `tbl_plot_files` (`plot_files_id`, `grave_id`, `file_name`, `created_at`, `updated_at`) VALUES
(2, 60, 'https://res.cloudinary.com/djrkvgfvo/image/upload/v1733845017/grave_images/grave_67585fea385ed.jpg', '2025-07-17 19:30:39', '2025-07-17 19:30:39');

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
(15, 'user', 'user', '$2y$10$20mKrz6yQiNOgD4Odv3SQ.t9.CyLkzutyHbJfjrl2WSMUXqQ0mcvC', NULL, NULL, NULL, NULL, NULL, NULL, 'manual', 1, 'active', '2025-07-18 02:26:17', '2025-06-08 19:12:28', '2025-07-18 02:26:17'),
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
-- Indexes for table `grave_points_backup`
--
ALTER TABLE `grave_points_backup`
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
  ADD KEY `record_id` (`record_id`);

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
-- Indexes for table `tbl_plot_files`
--
ALTER TABLE `tbl_plot_files`
  ADD PRIMARY KEY (`plot_files_id`),
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
  MODIFY `grave_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `grave_points_backup`
--
ALTER TABLE `grave_points_backup`
  MODIFY `grave_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
-- AUTO_INCREMENT for table `tbl_plot_files`
--
ALTER TABLE `tbl_plot_files`
  MODIFY `plot_files_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `tbl_files_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `tbl_plot_files`
--
ALTER TABLE `tbl_plot_files`
  ADD CONSTRAINT `tbl_plot_files_ibfk_1` FOREIGN KEY (`grave_id`) REFERENCES `grave_points` (`grave_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
