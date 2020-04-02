-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2020 at 12:51 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_levels`
--

CREATE TABLE `access_levels` (
  `id` int(11) NOT NULL,
  `access_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `access_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_permissions` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `access_levels`
--

INSERT INTO `access_levels` (`id`, `access_name`, `access_description`, `default_permissions`) VALUES
(1, 'AI Staff', 'Analitica Innovare Staffs', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\"}}}'),
(2, 'Executive', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\"}}}'),
(3, 'Sales Manager', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"access_levels\":{\"view\":\"0\",\"update\":\"0\"},\"branches\":{\"view\":\"0\",\"add\":\"0\",\"update\":\"0\",\"delete\":\"0\"}}}'),
(4, 'Sales Officer', '', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"0\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_id` varchar(12) DEFAULT NULL,
  `clientId` varchar(25) DEFAULT NULL,
  `branch_type` enum('Store','Warehouse') NOT NULL DEFAULT 'Store',
  `branch_name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `branch_contact` varchar(255) DEFAULT NULL,
  `branch_email` varchar(255) DEFAULT NULL,
  `branch_logo` varchar(255) DEFAULT NULL,
  `square_feet_area` varchar(255) DEFAULT NULL,
  `recurring_expenses` double(12,2) NOT NULL DEFAULT 0.00,
  `fixed_expenses` double(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_id`, `clientId`, `branch_type`, `branch_name`, `location`, `branch_contact`, `branch_email`, `branch_logo`, `square_feet_area`, `recurring_expenses`, `fixed_expenses`, `status`, `deleted`) VALUES
(1, '1234567', '54345231', 'Warehouse', 'Main Office Branch', 'final test location', '0244578968', 'testnewlocation@mail.com', 'assets/images/logo.png', '20.8', 3000.00, 2500.00, '1', '0'),
(2, 'PprJ7QiRUKGC', '54345231', 'Store', 'Makola Branch', 'Accra Central', '0550107770', 'makolabranch@mail.com', 'assets/images/company/7SuX10eVOmyElYtcH3psvNATI.png', '36', 700.00, 2600.00, '1', '0'),
(3, '6uSXCt4QYm30', '54345231', 'Store', 'Madina Branch', 'Accra', '0498989093', 'thisemail@mail.com', 'assets/images/company/jYT5ipPBfwIRhg4Ono0drK8My.png', '83.09', 1908.00, 2000.00, '1', '0'),
(4, 'ITnZeCdjy5bh', '54345231', 'Store', 'Adjiringanor Branch', 'lastedit@mail.com', '090839983', 'thisisit@mail.com', 'assets/images/company/jYT5ipPBfwIRhg4Ono0drK8My.png', '98.43', 1990.00, 254.00, '1', '0'),
(5, 'xC8M4JAfDhdq', '54345231', 'Warehouse', 'Another Branch', 'another location', '090989808', 'thisisthemail@mail.com', 'assets/images/company/jYT5ipPBfwIRhg4Ono0drK8My.png', '12.39', 1720.00, 1730.00, '1', '0'),
(6, 'p6VZwojXMRq9', '54345231', 'Store', 'New branch information', 'new branch location', '0987657636', 'newbranch@lamc.com', 'assets/images/company/jYT5ipPBfwIRhg4Ono0drK8My.png', '12', 100.00, 100.00, '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`, `country_code`) VALUES
(1, 'Afghanistan', 'AF'),
(2, 'Aland Islands', 'AX'),
(3, 'Albania', 'AL'),
(4, 'Algeria', 'DZ'),
(5, 'American Samoa', 'AS'),
(6, 'Andorra', 'AD'),
(7, 'Angola', 'AO'),
(8, 'Anguilla', 'AI'),
(9, 'Antarctica', 'AQ'),
(10, 'Antigua and Barbuda', 'AG'),
(11, 'Argentina', 'AR'),
(12, 'Armenia', 'AM'),
(13, 'Aruba', 'AW'),
(14, 'Australia', 'AU'),
(15, 'Austria', 'AT'),
(16, 'Azerbaijan', 'AZ'),
(17, 'Bahamas', 'BS'),
(18, 'Bahrain', 'BH'),
(19, 'Bangladesh', 'BD'),
(20, 'Barbados', 'BB'),
(21, 'Belarus', 'BY'),
(22, 'Belgium', 'BE'),
(23, 'Belize', 'BZ'),
(24, 'Benin', 'BJ'),
(25, 'Bermuda', 'BM'),
(26, 'Bhutan', 'BT'),
(27, 'Bolivia, Plurinational State of', 'BO'),
(28, 'Bonaire, Sint Eustatius and Saba', 'BQ'),
(29, 'Bosnia and Herzegovina', 'BA'),
(30, 'Botswana', 'BW'),
(31, 'Bouvet Island', 'BV'),
(32, 'Brazil', 'BR'),
(33, 'British Indian Ocean Territory', 'IO'),
(34, 'Brunei Darussalam', 'BN'),
(35, 'Bulgaria', 'BG'),
(36, 'Burkina Faso', 'BF'),
(37, 'Burundi', 'BI'),
(38, 'Cambodia', 'KH'),
(39, 'Cameroon', 'CM'),
(40, 'Canada', 'CA'),
(41, 'Cape Verde', 'CV'),
(42, 'Cayman Islands', 'KY'),
(43, 'Central African Republic', 'CF'),
(44, 'Chad', 'TD'),
(45, 'Chile', 'CL'),
(46, 'China', 'CN'),
(47, 'Christmas Island', 'CX'),
(48, 'Cocos (Keeling) Islands', 'CC'),
(49, 'Colombia', 'CO'),
(50, 'Comoros', 'KM'),
(51, 'Congo', 'CG'),
(52, 'Congo, the Democratic Republic of the', 'CD'),
(53, 'Cook Islands', 'CK'),
(54, 'Costa Rica', 'CR'),
(55, 'Cote d\'Ivoire', 'CI'),
(56, 'Croatia', 'HR'),
(57, 'Cuba', 'CU'),
(58, 'Curacao', 'CW'),
(59, 'Cyprus', 'CY'),
(60, 'Czech Republic', 'CZ'),
(61, 'Denmark', 'DK'),
(62, 'Djibouti', 'DJ'),
(63, 'Dominica', 'DM'),
(64, 'Dominican Republic', 'DO'),
(65, 'Ecuador', 'EC'),
(66, 'Egypt', 'EG'),
(67, 'El Salvador', 'SV'),
(68, 'Equatorial Guinea', 'GQ'),
(69, 'Eritrea', 'ER'),
(70, 'Estonia', 'EE'),
(71, 'Ethiopia', 'ET'),
(72, 'Falkland Islands (Malvinas)', 'FK'),
(73, 'Faroe Islands', 'FO'),
(74, 'Fiji', 'FJ'),
(75, 'Finland', 'FI'),
(76, 'France', 'FR'),
(77, 'French Guiana', 'GF'),
(78, 'French Polynesia', 'PF'),
(79, 'French Southern Territories', 'TF'),
(80, 'Gabon', 'GA'),
(81, 'Gambia', 'GM'),
(82, 'Georgia', 'GE'),
(83, 'Germany', 'DE'),
(84, 'Ghana', 'GH'),
(85, 'Gibraltar', 'GI'),
(86, 'Greece', 'GR'),
(87, 'Greenland', 'GL'),
(88, 'Grenada', 'GD'),
(89, 'Guadeloupe', 'GP'),
(90, 'Guam', 'GU'),
(91, 'Guatemala', 'GT'),
(92, 'Guernsey', 'GG'),
(93, 'Guinea', 'GN'),
(94, 'Guinea-Bissau', 'GW'),
(95, 'Guyana', 'GY'),
(96, 'Haiti', 'HT'),
(97, 'Heard Island and McDonald Islands', 'HM'),
(98, 'Holy See (Vatican City State)', 'VA'),
(99, 'Honduras', 'HN'),
(100, 'Hong Kong', 'HK'),
(101, 'Hungary', 'HU'),
(102, 'Iceland', 'IS'),
(103, 'India', 'IN'),
(104, 'Indonesia', 'ID'),
(105, 'Iran, Islamic Republic of', 'IR'),
(106, 'Iraq', 'IQ'),
(107, 'Ireland', 'IE'),
(108, 'Isle of Man', 'IM'),
(109, 'Israel', 'IL'),
(110, 'Italy', 'IT'),
(111, 'Jamaica', 'JM'),
(112, 'Japan', 'JP'),
(113, 'Jersey', 'JE'),
(114, 'Jordan', 'JO'),
(115, 'Kazakhstan', 'KZ'),
(116, 'Kenya', 'KE'),
(117, 'Kiribati', 'KI'),
(118, 'Korea, Democratic People\'s Republic of', 'KP'),
(119, 'Korea, Republic of', 'KR'),
(120, 'Kuwait', 'KW'),
(121, 'Kyrgyzstan', 'KG'),
(122, 'Lao People\'s Democratic Republic', 'LA'),
(123, 'Latvia', 'LV'),
(124, 'Lebanon', 'LB'),
(125, 'Lesotho', 'LS'),
(126, 'Liberia', 'LR'),
(127, 'Libya', 'LY'),
(128, 'Liechtenstein', 'LI'),
(129, 'Lithuania', 'LT'),
(130, 'Luxembourg', 'LU'),
(131, 'Macao', 'MO'),
(132, 'Macedonia, the Former Yugoslav Republic of', 'MK'),
(133, 'Madagascar', 'MG'),
(134, 'Malawi', 'MW'),
(135, 'Malaysia', 'MY'),
(136, 'Maldives', 'MV'),
(137, 'Mali', 'ML'),
(138, 'Malta', 'MT'),
(139, 'Marshall Islands', 'MH'),
(140, 'Martinique', 'MQ'),
(141, 'Mauritania', 'MR'),
(142, 'Mauritius', 'MU'),
(143, 'Mayotte', 'YT'),
(144, 'Mexico', 'MX'),
(145, 'Micronesia, Federated States of', 'FM'),
(146, 'Moldova, Republic of', 'MD'),
(147, 'Monaco', 'MC'),
(148, 'Mongolia', 'MN'),
(149, 'Montenegro', 'ME'),
(150, 'Montserrat', 'MS'),
(151, 'Morocco', 'MA'),
(152, 'Mozambique', 'MZ'),
(153, 'Myanmar', 'MM'),
(154, 'Namibia', 'NA'),
(155, 'Nauru', 'NR'),
(156, 'Nepal', 'NP'),
(157, 'Netherlands', 'NL'),
(158, 'New Caledonia', 'NC'),
(159, 'New Zealand', 'NZ'),
(160, 'Nicaragua', 'NI'),
(161, 'Niger', 'NE'),
(162, 'Nigeria', 'NG'),
(163, 'Niue', 'NU'),
(164, 'Norfolk Island', 'NF'),
(165, 'Northern Mariana Islands', 'MP'),
(166, 'Norway', 'NO'),
(167, 'Oman', 'OM'),
(168, 'Pakistan', 'PK'),
(169, 'Palau', 'PW'),
(170, 'Palestine, State of', 'PS'),
(171, 'Panama', 'PA'),
(172, 'Papua New Guinea', 'PG'),
(173, 'Paraguay', 'PY'),
(174, 'Peru', 'PE'),
(175, 'Philippines', 'PH'),
(176, 'Pitcairn', 'PN'),
(177, 'Poland', 'PL'),
(178, 'Portugal', 'PT'),
(179, 'Puerto Rico', 'PR'),
(180, 'Qatar', 'QA'),
(181, 'Reunion', 'RE'),
(182, 'Romania', 'RO'),
(183, 'Russian Federation', 'RU'),
(184, 'Rwanda', 'RW'),
(185, 'Saint Barthelemy', 'BL'),
(186, 'Saint Helena, Ascension and Tristan da Cunha', 'SH'),
(187, 'Saint Kitts and Nevis', 'KN'),
(188, 'Saint Lucia', 'LC'),
(189, 'Saint Martin (French part)', 'MF'),
(190, 'Saint Pierre and Miquelon', 'PM'),
(191, 'Saint Vincent and the Grenadines', 'VC'),
(192, 'Samoa', 'WS'),
(193, 'San Marino', 'SM'),
(194, 'Sao Tome and Principe', 'ST'),
(195, 'Saudi Arabia', 'SA'),
(196, 'Senegal', 'SN'),
(197, 'Serbia', 'RS'),
(198, 'Seychelles', 'SC'),
(199, 'Sierra Leone', 'SL'),
(200, 'Singapore', 'SG'),
(201, 'Sint Maarten (Dutch part)', 'SX'),
(202, 'Slovakia', 'SK'),
(203, 'Slovenia', 'SI'),
(204, 'Solomon Islands', 'SB'),
(205, 'Somalia', 'SO'),
(206, 'South Africa', 'ZA'),
(207, 'South Georgia and the South Sandwich Islands', 'GS'),
(208, 'South Sudan', 'SS'),
(209, 'Spain', 'ES'),
(210, 'Sri Lanka', 'LK'),
(211, 'Sudan', 'SD'),
(212, 'Suriname', 'SR'),
(213, 'Svalbard and Jan Mayen', 'SJ'),
(214, 'Swaziland', 'SZ'),
(215, 'Sweden', 'SE'),
(216, 'Switzerland', 'CH'),
(217, 'Syrian Arab Republic', 'SY'),
(218, 'Taiwan, Province of China', 'TW'),
(219, 'Tajikistan', 'TJ'),
(220, 'Tanzania, United Republic of', 'TZ'),
(221, 'Thailand', 'TH'),
(222, 'Timor-Leste', 'TL'),
(223, 'Togo', 'TG'),
(224, 'Tokelau', 'TK'),
(225, 'Tonga', 'TO'),
(226, 'Trinidad and Tobago', 'TT'),
(227, 'Tunisia', 'TN'),
(228, 'Turkey', 'TR'),
(229, 'Turkmenistan', 'TM'),
(230, 'Turks and Caicos Islands', 'TC'),
(231, 'Tuvalu', 'TV'),
(232, 'Uganda', 'UG'),
(233, 'Ukraine', 'UA'),
(234, 'United Arab Emirates', 'AE'),
(235, 'United Kingdom', 'GB'),
(236, 'United States', 'US'),
(237, 'United States Minor Outlying Islands', 'UM'),
(238, 'Uruguay', 'UY'),
(239, 'Uzbekistan', 'UZ'),
(240, 'Vanuatu', 'VU'),
(241, 'Venezuela, Bolivarian Republic of', 'VE'),
(242, 'Viet Nam', 'VN'),
(243, 'Virgin Islands, British', 'VG'),
(244, 'Virgin Islands, U.S.', 'VI'),
(245, 'Wallis and Futuna', 'WF'),
(246, 'Western Sahara', 'EH'),
(247, 'Yemen', 'YE'),
(248, 'Zambia', 'ZM'),
(249, 'Zimbabwe', 'ZW');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci DEFAULT '54345231',
  `branchId` int(11) DEFAULT 1,
  `customer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Evelyn',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'assets/images/users/user-4.jpg',
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `residence` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('M','F') COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `lead_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `relationship_manager` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rating` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_log` datetime DEFAULT current_timestamp(),
  `preferred_payment_type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `clientId`, `branchId`, `customer_id`, `source`, `title`, `firstname`, `lastname`, `role`, `phone_1`, `phone_2`, `email`, `date_of_birth`, `user_image`, `description`, `residence`, `postal_address`, `country`, `nationality`, `gender`, `city`, `user_type`, `website`, `industry`, `company_id`, `balance`, `lead_id`, `relationship_manager`, `department`, `rating`, `owner_id`, `date_log`, `preferred_payment_type`, `comments`, `status`) VALUES
(3, '54345231', 1, 'EVC465398287316', 'Evelyn', 'Mr', 'Emmanuel', 'Obeng', NULL, '0550107770', '0203317732', 'emmanuel.obeng@analiticainnovare.com', '1992-03-20', 'assets/images/users/user-4.jpg', NULL, 'Adjiringanor, East Legon', NULL, '84', NULL, 'M', 'Accra', 'Organization', NULL, NULL, '22', NULL, 'null', 'null', NULL, '3', 'SEF2202', '2019-08-06 10:46:08', 'cash', '', '1'),
(4, '54345231', 1, 'EVC427416398569', 'Evelyn', 'Prof', 'Gideon', 'Afoh', NULL, '002002002', '02120212020', 'afohgideon@gmail.com', '2019-08-14', 'assets/images/users/user-4.jpg', NULL, 'Atansodl 2002 AF L12', NULL, '232', NULL, 'M', 'Atandor', 'Individual', NULL, NULL, '17', NULL, 'null', 'null', NULL, '1', 'SEF2202', '2019-08-06 15:20:15', 'cash', 'Good sir', '1'),
(6, '54345231', 1, 'EVC579927164188', 'Evelyn', 'Dr', 'Michael', 'Ampofo', NULL, '0546140378', '0215241202', 'michael@analiticainnovare.com', '1985-07-17', 'assets/images/users/user-4.jpg', NULL, 'No. 8 Berlin Street, HQ', NULL, '83', NULL, 'M', 'Berlin', 'Organization', NULL, NULL, '22', NULL, NULL, 'null', 'null', '1', 'SEF2202', '2019-08-14 15:27:25', 'cash', '', '1'),
(7, '54345231', 1, 'EVC611798467452', 'Evelyn', 'Miss', 'Grace', 'Yeboah', NULL, '0240553604', '', 'graciellaob@gmail.com', '1995-08-04', 'assets/images/users/user-4.jpg', NULL, 'Koplan 92 Avenue, Maglan Street', NULL, '80', NULL, 'F', 'kampala', 'Individual', NULL, NULL, '16', NULL, NULL, 'SEF2202', '7', NULL, 'SEF2202', '2019-08-15 10:28:43', 'cash', '', '1'),
(8, '54345231', 1, 'EVC261795342893', 'Evelyn', 'Miss', 'Abena', 'Darko', NULL, '0554218963', '', 'abenadarko@gmail.com', '2001-09-04', 'assets/images/users/user-4.jpg', NULL, '', NULL, '11', NULL, 'F', 'Berzling', 'Individual', NULL, NULL, '14', NULL, NULL, 'SEF2202', '8', NULL, 'SEF2202', '2019-08-15 10:30:35', 'cash', '', '1'),
(9, '54345231', 1, 'EVC718437356928', 'Evelyn', 'Prof', 'Felix', 'Amoah', NULL, '0212032532', '', 'laconzy@hotmail.com', '1990-06-23', 'assets/images/users/user-4.jpg', NULL, 'NYC852, Mambey Street, PO021', NULL, '236', NULL, 'M', 'New York City', 'Organization', NULL, NULL, '22', NULL, NULL, 'SEF2202', '1', NULL, 'SEF2202', '2019-08-15 11:13:39', 'cash', '', '1'),
(53, '54345231', 2, 'EVC4653982847316', 'Evelyn', 'Mr', 'Emmanuel', 'Obeng', NULL, '0550107770', '0203317732', 'emmanuel.obeng@analiticainnovare.com', '1992-03-20', 'assets/images/users/user-4.jpg', NULL, 'Adjiringanor, East Legon', NULL, '84', NULL, 'M', 'Accra', 'Organization', NULL, NULL, '22', NULL, 'null', 'null', NULL, '3', 'SEF2202', '2019-08-06 10:46:08', 'cash', '', '1'),
(54, '54345231', 2, 'EVC4274146398569', 'Evelyn', 'Prof', 'Gideon', 'Afoh', NULL, '0541588844', '02120212020', 'afohgideon@gmail.com', '2019-08-14', 'assets/images/users/user-4.jpg', NULL, 'Atansodl 2002 AF L12', NULL, '232', NULL, 'M', 'Atandor', 'Individual', NULL, NULL, '17', NULL, 'null', 'null', NULL, '1', 'SEF2202', '2019-08-06 15:20:15', 'cash', 'Good sir', '1'),
(55, '54345231', 2, 'EVC5794927164188', 'Evelyn', 'Dr', 'Michael', 'Ampofo', NULL, '0546140378', '0215241202', 'michael@analiticainnovare.com', '1985-07-17', 'assets/images/users/user-4.jpg', NULL, 'No. 8 Berlin Street, HQ', NULL, '83', NULL, 'M', 'Berlin', 'Organization', NULL, NULL, '22', NULL, NULL, 'null', 'null', '1', 'SEF2202', '2019-08-14 15:27:25', 'credit', '', '1'),
(56, '54345231', 2, 'EVC6114798467452', 'Evelyn', 'Miss', 'Grace', 'Yeboah', NULL, '0240553604', '', 'graciellaob@gmail.com', '1995-08-04', 'assets/images/users/user-4.jpg', NULL, 'Koplan 92 Avenue, Maglan Street', NULL, '80', NULL, 'F', 'kampala', 'Individual', NULL, NULL, '16', NULL, NULL, 'SEF2202', '7', NULL, 'SEF2202', '2019-08-15 10:28:43', 'credit', '', '1'),
(57, '54345231', 2, 'EVC2641795342893', 'Evelyn', 'Miss', 'Abena', 'Darko', NULL, '0554218963', '', 'abenadarko@gmail.com', '2001-09-04', 'assets/images/users/user-4.jpg', NULL, '', NULL, '11', NULL, 'F', 'Berzling', 'Individual', NULL, NULL, '14', NULL, NULL, 'SEF2202', '8', NULL, 'SEF2202', '2019-08-15 10:30:35', 'cash', '', '1'),
(58, '54345231', 2, 'EVC7148437356928', 'Evelyn', 'Prof', 'Felix', 'Amoah', NULL, '0212032532', '', 'laconzy@hotmail.com', '1990-06-23', 'assets/images/users/user-4.jpg', NULL, 'NYC852, Mambey Street, PO021', NULL, '236', NULL, 'M', 'New York City', 'Organization', NULL, NULL, '22', NULL, NULL, 'SEF2202', '1', NULL, 'SEF2202', '2019-08-15 11:13:39', 'cash', '', '1'),
(59, '54345231', 2, 'EVC836312984427', 'Evelyn', 'Miss', 'Grace', 'Obeng', NULL, '0550107770', NULL, NULL, NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oDGirP31jNnXYK6dza20m', '2020-03-17 07:50:15', 'cash', NULL, '1'),
(62, '54345231', 1, 'WalkIn', 'Evelyn', NULL, 'Walk In', 'Customer', NULL, NULL, NULL, NULL, NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-03-24 05:01:06', 'cash', NULL, '1'),
(63, '54345231', 1, 'EVC951937465721', 'Evelyn', 'Mr', 'Music De', 'Entertainer', NULL, '0809777679', NULL, 'music@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-25 23:58:49', '', NULL, '1'),
(64, '54345231', 1, 'EVC792158792336', 'Evelyn', 'Mr', 'Nature', 'The Boy', NULL, '09092998983', NULL, NULL, NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-25 23:59:27', '', NULL, '1'),
(65, '54345231', 1, 'EVC423589361967', 'Evelyn', 'Mr', 'Eric', 'Adjetey Boy', NULL, '0909283900', NULL, 'adjeteyboy@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Lapaz, Accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-26 00:05:14', '', NULL, '1'),
(66, '54345231', 1, 'EVC941374268936', 'Evelyn', 'Miss', 'Salomey', 'Obenewaa', NULL, '0909883990', NULL, 'thissalomey@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-26 00:06:15', '', NULL, '1'),
(67, '54345231', 1, 'EVC532694487286', 'Evelyn', 'Mr', 'Abena', 'Bermaa', NULL, '0909388930', NULL, 'abena@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-26 00:08:30', '', NULL, '1'),
(68, '54345231', 1, 'EVC218799756628', 'Evelyn', 'Mr', 'New', 'Customer', NULL, '0209099090', NULL, 'mail@newcustomer.com', NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-03-26 10:48:30', '', NULL, '1'),
(69, '54345231', 1, 'EVC143364821582', 'Evelyn', 'Prof', 'Gideon', 'Afoh', NULL, '0541588844', NULL, 'afohgideon@gmail.com', NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019DevAI', '2020-04-02 09:06:29', NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `customers_account`
--

CREATE TABLE `customers_account` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) NOT NULL DEFAULT '0',
  `customer_id` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `account_type` varchar(150) NOT NULL,
  `parent_account` int(11) NOT NULL,
  `industry` varchar(255) NOT NULL,
  `employees` int(11) NOT NULL,
  `annual_revenue` decimal(10,2) NOT NULL,
  `account_description` varchar(500) NOT NULL,
  `billing_st_address` varchar(150) NOT NULL,
  `billing_city` varchar(120) NOT NULL,
  `billing_state` varchar(120) NOT NULL,
  `billing_zip` varchar(10) NOT NULL,
  `billing_country` varchar(150) NOT NULL,
  `shipping_st_address` varchar(150) NOT NULL,
  `shipping_city` varchar(120) NOT NULL,
  `shipping_state` varchar(120) NOT NULL,
  `shipping_zip` varchar(10) NOT NULL,
  `shipping_country` varchar(150) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers_account`
--

INSERT INTO `customers_account` (`id`, `clientId`, `customer_id`, `account_name`, `fax`, `website`, `account_type`, `parent_account`, `industry`, `employees`, `annual_revenue`, `account_description`, `billing_st_address`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `shipping_st_address`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `status`) VALUES
(2, '54345231', '1', 'Giddi Account', '', '', '', 19, '1', 0, '0.00', '', '', '', '', '', '', '', '', '', '', '', '1'),
(3, '54345231', '2', 'Giddi Account', '', '', '', 18, '1', 0, '0.00', '', '', '', '', '', '', '', '', '', '', '', '1'),
(4, '54345231', '3', 'hvfcghbtvdcfg', '201202052', 'https://analiticainnovare.com', 'Joint Account', 17, '1', 0, '0.00', '', 'EAST LEGON', 'Accra', 'Greater Accra', '00233', 'Ghana', 'EAST LEGON', 'Accra', 'Greater Accra', '00233', 'Ghana', '1'),
(5, '54345231', '2', 'xfghj', '', '', '', 3, '1', 0, '0.00', '', '', '', '', '', '', '', '', '', '', '', '1'),
(6, '54345231', '2', 'Giddi Account', '', '', '', 16, '1', 0, '0.00', '', '', '', '', '', '', '', '', '', '', '', '1'),
(7, '54345231', '1', 'Comedy Group', '', 'https://analiticainnovare.com', 'Group Account', 4, '1', 0, '0.00', '', '', '', '', '', '', '', '', '', '', '', '1'),
(8, '54345231', '4', 'Fidelity Entertainment', '', 'https://accra.com', 'Joint Account', 9, '1', 0, '0.00', '', 'ag fsgf sg', 'sg fsgf ', 'sg fsg f', '', '', 'fs gfs gfs gfs gf', 's gfs', 'g fsg fs gf', '', '', '1'),
(9, '54345231', '5', 'Call To Action', '244899761', '', 'Savings Account', 15, '1', 0, '0.00', '', 'Madina\r\nAccra', 'Accra', 'Greater Accra', '', '', 'Madina\r\nAccra', '', '', '', '', '1'),
(10, '54345231', '6', 'Call To Action', '244899761', '', 'Savings Account', 15, '1', 0, '0.00', '', 'Madina\r\nAccra', 'Accra', 'Greater Accra', '', '', 'Madina\r\nAccra', '', '', '', '', '1'),
(11, '54345231', '4', 'Call To Action', '244899761', '', 'Saving Acc', 15, '1', 0, '0.00', '', 'Madina\r\nAccra', 'Accra', 'Greater Accra', '', '', 'Madina\r\nAccra', '', '', '', '', '1'),
(12, '54345231', '7', 'Call To Action', '244899761', '', 'Joint Account', 15, '1', 0, '0.00', '', 'Madina\r\nAccra', 'Accra', 'Greater Accra', '', '', 'Madina\r\nAccra', '', '', '', '', '1'),
(14, '54345231', 'EVC427416398569', 'This is a test account', '123456789', 'https://www.testaccount.com', 'Personal Account', 33, '1', 39, '300000.00', 'This is a test account and i am hoping that all the information will successfully insert into the database', 'P. O. Box AF 2582, Accra', 'Accra', 'Thank You', 'This is pr', 'ghana', 'P. O. Box AF 2582, Accra', 'Very Much', 'Hello world', 'cool', 'ghana', '1'),
(15, '54345231', 'EVC427416398569', 'This is a test account', '123456789', 'https://www.testaccount.com', 'Personal Account', 33, '1', 39, '300000.00', 'This is a test account and i am hoping that all the information will successfully insert into the database', 'P. O. Box AF 2582, Accra', 'Accra', 'Thank You', 'This is pr', '15', 'P. O. Box AF 2582, Accra', 'Very Much', 'Hello world', 'cool', '14', '1'),
(16, '54345231', 'EVC427416398569', 'This is a test account', '123456789', 'https://www.testaccount.com', 'Personal Account', 33, '1', 39, '300000.00', 'This is a test account and i am hoping that all the information will successfully insert into the database', 'P. O. Box AF 2582, Accra', 'Accra', 'Thank You', 'This is pr', '15', 'P. O. Box AF 2582, Accra', 'Very Much', 'Hello world', 'cool', '14', '1'),
(17, '54345231', 'EVC427416398569', 'This is a test account', '123456789', 'https://www.testaccount.com', 'Personal Account', 33, '1', 39, '300000.00', 'This is a test account and i am hoping that all the information will successfully insert into the database', 'P. O. Box AF 2582, Accra', 'Accra', 'Thank You', 'This is co', '15', 'P. O. Box AF 2582, Accra', 'Very Much', 'Hello world', 'cool', '14', '1');

-- --------------------------------------------------------

--
-- Table structure for table `customers_company`
--

CREATE TABLE `customers_company` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `item_utype` enum('customer','lead','admin','sales_team') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `owner_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers_company`
--

INSERT INTO `customers_company` (`id`, `clientId`, `item_utype`, `name`, `contact`, `contact_2`, `address`, `email`, `industry`, `comments`, `status`, `owner_id`, `website`, `date_added`) VALUES
(3, '54345231', 'customer', 'Emmallen Networks', '0550107770', NULL, 'P. O. Box AF, 2582, Adentan', 'emmallob14@gmail.com', NULL, NULL, '1', 'SEF2202', NULL, NULL),
(4, '54345231', 'customer', 'Commey Technologies', '0221202120', NULL, 'commey@mail.com', 'commey@mail.com', NULL, NULL, '1', 'SEF2202', NULL, NULL),
(5, '54345231', 'customer', 'Ernest Kwofie Limited', '0550107770', NULL, 'P. O. BOX KSI 215, Amakom', 'ernestkwoffie@trialemail.com', NULL, NULL, '1', 'SEF2202', NULL, NULL),
(6, '54345231', 'customer', 'Analitica Innovare', '05215212512', NULL, '', 'info@analiticainnovare.com', '1', NULL, '1', 'SEF2202', NULL, NULL),
(9, '54345231', 'customer', 'Laconzy Clothings', '0215212421', NULL, 'Accra, High Street, BIL989', 'info@laconzyclothings.com', '1', NULL, '1', 'SEF2202', NULL, NULL),
(14, '54345231', '', 'Philips Consult', '0215241201', NULL, '', 'info@philipsconsult.com', '1', '', '1', 'SEF2202', NULL, NULL),
(15, '54345231', '', 'Test Company', '0215421502', NULL, '', '', 'null', '', '1', 'SEF2202', NULL, NULL),
(16, '54345231', '', 'Joshs Lab', '021352631', NULL, '', 'jcommey@mail.com', 'null', '', '1', 'SEF2202', '', NULL),
(17, '54345231', '', 'Eric Inc.', '0557689532', '', 'P. O. Box AF, 258662, Adentan', 'info@ericinc.com', 'Agriculture', 'This is the new organization.', '1', 'SEF2202', 'https://hellow-test.com', NULL),
(18, '54345231', '', 'Vodafone', '021524120', NULL, '', 'info@voda.com', '1', '', '0', 'SEF2202', NULL, NULL),
(19, '54345231', '', 'Vodafone Ghana', '0215202021', NULL, '', 'info@vodafone.com', '1', '', '0', 'SEF2202', 'https://vodafone.com.gh', NULL),
(20, '54345231', '', 'Vodafone Ghana', '0215202021', NULL, '', 'info@vodafone.com', '1', '', '0', 'SEF2202', 'https://vodafone.com.gh', NULL),
(21, '54345231', '', 'Vodafone Ghana', '0215202021', NULL, '', 'info@vodafone.com', '1', '', '0', 'SEF2202', 'https://vodafone.com.gh', NULL),
(22, '54345231', '', 'Vodafone Ghana', '0243332333', '0243332334', 'P. O. Box, AF 25125, Accra Main Box', 'info@vodafone.com', 'Telecommunications', '', '1', 'SEF2202', 'https://vodafone.com.gh', NULL),
(23, '54345231', NULL, 'Test Company 1', '0214521521', NULL, 'accra', 'testcompany@me.com', NULL, 'this is the company that I am inserting using the bulk data', '1', 'SEF2202', 'www.hello.com', NULL),
(24, '54345231', NULL, 'Test company 2', '02152154221', NULL, 'achimota', 'testcompany2@me.com', NULL, 'this is the next test company that I am inserting', '1', 'SEF2202', 'www.thatsme.com', NULL),
(25, '54345231', NULL, 'Test company 3', '0212452102', NULL, 'saint john grammer', 'testcompany3@maklj.com', NULL, 'this is the final test company that I want to insert into the system', '1', 'SEF2202', 'www.thisislast.com', NULL),
(26, '54345231', '', 'Accra City hotel', 'mr Choi', NULL, '', 'wil199@yahoo.com', '1', '', '1', '2019DevAI', '', NULL),
(28, '54345231', NULL, 'Ace Technologies', '0557689819', '', '', 'info@ericinc.com', 'Agriculture', NULL, '1', NULL, 'www.yahoo.com', NULL),
(29, '54345231', NULL, 'My Company details', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL),
(30, '54345231', NULL, 'Dr. Asamoah and Associates', '0557689819', '', 'Very Far from here', 'info@something.com', 'Information Technology', NULL, '1', NULL, 'something.com', NULL),
(33, '54345231', NULL, 'Test Company Account Information 5', '0243332333', '0557689532', 'Not so far from here', 'info@something.com', 'IT & Telecom', NULL, '1', NULL, 'something.com', NULL),
(38, '54345231', NULL, 'JoshuaCom', '0244587656', '', 'Here', 'info@somethingthing.com', 'Information Technology', NULL, '1', NULL, 'somethingthing.com', NULL),
(44, '54345231', NULL, 'Emmallen Estates', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL),
(45, '54345231', NULL, 'Simon Kortey', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL),
(46, '54345231', NULL, 'Solo Ltd.', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL),
(47, '54345231', NULL, 'Gados IT Solutions', '0302905385', '', 'Madina', 'afohgideon@gmail.com', 'Software Company', NULL, '1', NULL, '', NULL),
(48, '54345231', NULL, 'Emmallen Networks', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_monitoring`
--

CREATE TABLE `data_monitoring` (
  `id` int(11) NOT NULL,
  `clientId` int(11) DEFAULT 1,
  `data_type` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_set` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_log` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `title` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `performance_rating` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `overall_sales` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `clientId`, `title`, `description`, `performance_rating`, `overall_sales`) VALUES
(1, '54345231', 'HR', NULL, NULL, NULL),
(2, '54345231', 'Operations', NULL, NULL, NULL),
(3, '54345231', 'Sales', NULL, NULL, NULL),
(4, '54345231', 'Production', NULL, NULL, NULL),
(5, '54345231', 'Finance', NULL, NULL, NULL),
(6, '54345231', 'Administration', NULL, NULL, NULL),
(7, '54345231', 'Information Technology', NULL, NULL, NULL),
(8, '54345231', 'Technical', NULL, NULL, NULL),
(9, '54345231', 'Transport', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_list`
--

CREATE TABLE `email_list` (
  `id` int(11) NOT NULL,
  `clientId` varchar(32) DEFAULT 'NULL',
  `branchId` varchar(12) DEFAULT NULL,
  `template_type` enum('general','invoice','sign_up','login','recovery','request') DEFAULT NULL,
  `itemId` varchar(55) DEFAULT NULL,
  `recipients_list` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_requested` datetime DEFAULT current_timestamp(),
  `sent_status` enum('0','1') DEFAULT '0',
  `request_performed_by` varchar(32) DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `deleted` enum('0','1') DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `email_list`
--

INSERT INTO `email_list` (`id`, `clientId`, `branchId`, `template_type`, `itemId`, `recipients_list`, `subject`, `message`, `date_requested`, `sent_status`, `request_performed_by`, `date_sent`, `deleted`) VALUES
(1, '54345231', '1', 'request', 'QT20200300016', '{\"recipients_list\":[{\"fullname\":\"New Customer\",\"email\":\"mail@newcustomer.com\",\"customer_id\":null,\"branchId\":\"1\"}]}', NULL, NULL, '2020-03-26 10:49:42', '0', '2019DevAI', NULL, '0'),
(2, '54345231', '1', 'request', 'POS2020040100137', '{\"recipients_list\":[{\"fullname\":\"Emmanuel Obeng\",\"email\":\"emmanuel.obeng@analiticainnovare.com\",\"customer_id\":\"EVC465398287316\",\"branchId\":\"1\"}]}', NULL, NULL, '2020-04-01 09:31:25', '0', '2019DevAI', NULL, '0'),
(3, '54345231', '1', 'recovery', '2019DevAI', '{\"recipients_list\":[{\"fullname\":\"Visaminet User\",\"email\":\"admin@mail.com\",\"customer_id\":\"2019DevAI\",\"branchId\":\"1\"}]}', '[Argon POS] Change Password', 'Hi Visaminet User<br>You have requested to reset your password at Argon POS<br><br>The scoreg are your login details:<br><br><br>Before you can reset your password please follow this link.<br><br><a class=\"alert alert-success\" href=\"https://dev.localhost.com/pos/verify?pwd&tk=cgaAD1x50GsNydnplVUv2ZPCfB6hJu7t4qSjYWHILETO3XrbFoRwz98eMki\">Click Here to Reset Password</a><br><br>If it does not work please copy this link and place it in your browser url.<br><br>https://dev.localhost.com/pos/verify?pwd&tk=cgaAD1x50GsNydnplVUv2ZPCfB6hJu7t4qSjYWHILETO3XrbFoRwz98eMki', '2020-04-01 12:27:03', '0', '2019DevAI', NULL, '1'),
(4, '54345231', '1', 'recovery', '2019DevAI', '{\"recipients_list\":[{\"fullname\":\"Visaminet User\",\"email\":\"admin@mail.com\",\"customer_id\":\"2019DevAI\",\"branchId\":\"1\"}]}', '[Argon POS] Change Password', 'Hi Visaminet User<br>You have requested to reset your password at Argon POS<br><br>The scoreg are your login details:<br><br><br>Before you can reset your password please follow this link.<br><br><a class=\"alert alert-success\" href=\"https://dev.localhost.com/pos/verify?pwd&tk=ucocYigYTS5QhjRevrw1VbseWZlWh3lLCm54A0dH2b0kC7sinZ49qRNDEII9vTmM6yQOPLKt\">Click Here to Reset Password</a><br><br>If it does not work please copy this link and place it in your browser url.<br><br>https://dev.localhost.com/pos/verify?pwd&tk=ucocYigYTS5QhjRevrw1VbseWZlWh3lLCm54A0dH2b0kC7sinZ49qRNDEII9vTmM6yQOPLKt', '2020-04-01 12:28:50', '0', '2019DevAI', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE `email_settings` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_port` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`id`, `clientId`, `name`, `email_type`, `email_port`, `email_host`, `email_username`, `email_password`, `status`) VALUES
(1, '54345231', 'Emmanuel Obeng', 'gmail', NULL, NULL, 'emmallob14@gmail.com', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `industry`
--

CREATE TABLE `industry` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `industry`
--

INSERT INTO `industry` (`id`, `name`) VALUES
(1, 'Information Technology'),
(2, 'Telecommunications');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `inventory_date` datetime DEFAULT current_timestamp(),
  `cost_price` decimal(12,2) DEFAULT NULL,
  `selling_price` decimal(12,2) DEFAULT NULL,
  `log_date` datetime DEFAULT current_timestamp(),
  `clientId` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recorded_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branchId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `inventory_date`, `cost_price`, `selling_price`, `log_date`, `clientId`, `recorded_by`, `branchId`) VALUES
(1, '5', 5, '2020-03-14 11:09:25', NULL, '1900.00', '2020-03-14 11:09:25', '54345231', '2019DevAI', 1),
(2, '6', 5, '2020-03-14 11:09:25', NULL, '3200.00', '2020-03-14 11:09:25', '54345231', '2019DevAI', 1),
(3, '8', 10, '2020-03-14 11:12:03', NULL, '200.00', '2020-03-14 11:12:03', '54345231', '2019DevAI', 1),
(4, '6', 5, '2020-03-14 11:12:03', NULL, '3200.00', '2020-03-14 11:12:03', '54345231', '2019DevAI', 1),
(5, '6', 5, '2020-03-14 11:13:15', NULL, '3200.00', '2020-03-14 11:13:15', '54345231', '2019DevAI', 1),
(6, '6', 5, '2020-03-14 11:14:41', NULL, '3200.00', '2020-03-14 11:14:41', '54345231', '2019DevAI', 1),
(7, '8', 10, '2020-03-14 11:14:41', NULL, '200.00', '2020-03-14 11:14:41', '54345231', '2019DevAI', 1),
(8, '5', 2, '2020-03-14 11:17:31', NULL, '1900.00', '2020-03-14 11:17:31', '54345231', '2019DevAI', 1),
(9, '6', 2, '2020-03-14 11:17:31', NULL, '3200.00', '2020-03-14 11:17:31', '54345231', '2019DevAI', 1),
(10, '7', 2, '2020-03-14 11:17:31', NULL, '850.00', '2020-03-14 11:17:31', '54345231', '2019DevAI', 1),
(11, '5', 1, '2020-03-14 11:20:49', NULL, '1900.00', '2020-03-14 11:20:49', '54345231', '2019DevAI', 1),
(12, '6', 1, '2020-03-14 11:20:49', NULL, '3200.00', '2020-03-14 11:20:49', '54345231', '2019DevAI', 1),
(13, '7', 1, '2020-03-14 11:20:49', NULL, '850.00', '2020-03-14 11:20:49', '54345231', '2019DevAI', 1),
(14, 'PDT8b4OgIfa7QKV', 1, '2020-03-14 11:27:30', NULL, '1900.00', '2020-03-14 11:27:30', '54345231', '2019DevAI', 1),
(15, 'PDT0Roc2HhYBleu', 1, '2020-03-14 11:27:30', NULL, '3200.00', '2020-03-14 11:27:30', '54345231', '2019DevAI', 1),
(16, 'PDTqODR15MfeBtu', 1, '2020-03-14 11:27:30', NULL, '850.00', '2020-03-14 11:27:30', '54345231', '2019DevAI', 1),
(17, 'PDT8b4OgIfa7QKV', 1, '2020-03-14 11:33:05', NULL, '1900.00', '2020-03-14 11:33:05', '54345231', '2019DevAI', 1),
(18, 'PDT0Roc2HhYBleu', 1, '2020-03-14 11:33:05', NULL, '3200.00', '2020-03-14 11:33:05', '54345231', '2019DevAI', 1),
(19, 'PDTqODR15MfeBtu', 1, '2020-03-14 11:33:05', NULL, '850.00', '2020-03-14 11:33:05', '54345231', '2019DevAI', 1),
(20, 'PDTKCB0UmoOnEZW', 10, '2020-03-14 11:33:05', NULL, '200.00', '2020-03-14 11:33:05', '54345231', '2019DevAI', 1),
(21, 'PDT8b4OgIfa7QKV', 7, '2020-03-14 11:42:21', NULL, '1900.00', '2020-03-14 11:42:21', '54345231', '2019DevAI', 2),
(22, 'PDT0Roc2HhYBleu', 6, '2020-03-14 11:48:11', NULL, '3200.00', '2020-03-14 11:48:11', '54345231', '2019DevAI', 2),
(23, 'PDTqODR15MfeBtu', 5, '2020-03-14 11:48:11', NULL, '850.00', '2020-03-14 11:48:11', '54345231', '2019DevAI', 2),
(24, 'PDTqODR15MfeBtu', 5, '2020-03-14 12:56:11', NULL, '850.00', '2020-03-14 12:56:11', '54345231', '2019DevAI', 1),
(25, 'null', 5, '2020-03-15 00:40:23', NULL, '1900.00', '2020-03-15 00:40:23', '54345231', '2019DevAI', 5),
(26, 'null', 5, '2020-03-15 00:40:24', NULL, '850.00', '2020-03-15 00:40:24', '54345231', '2019DevAI', 5),
(27, 'null', 5, '2020-03-15 00:40:41', NULL, '1900.00', '2020-03-15 00:40:41', '54345231', '2019DevAI', 5),
(28, 'null', 5, '2020-03-15 00:40:41', NULL, '850.00', '2020-03-15 00:40:41', '54345231', '2019DevAI', 5),
(29, 'null', 5, '2020-03-15 00:42:28', NULL, '1900.00', '2020-03-15 00:42:28', '54345231', '2019DevAI', 5),
(30, 'null', 5, '2020-03-15 00:42:28', NULL, '850.00', '2020-03-15 00:42:28', '54345231', '2019DevAI', 5),
(31, 'null', 5, '2020-03-15 00:51:39', NULL, '1900.00', '2020-03-15 00:51:39', '54345231', '2019DevAI', 5),
(32, 'null', 5, '2020-03-15 00:51:39', NULL, '850.00', '2020-03-15 00:51:39', '54345231', '2019DevAI', 5),
(33, 'PDT8b4OgIfa7QKV', 3, '2020-03-16 08:17:12', NULL, '1900.00', '2020-03-16 08:17:12', '54345231', 'oDGirP31jNnXYK6dza20m', 2),
(34, 'PDTqODR15MfeBtu', 17, '2020-03-16 08:17:36', NULL, '850.00', '2020-03-16 08:17:36', '54345231', 'oDGirP31jNnXYK6dza20m', 2),
(35, 'PDTKCB0UmoOnEZW', 10, '2020-03-17 05:12:15', NULL, '200.00', '2020-03-17 05:12:15', '54345231', 'oDGirP31jNnXYK6dza20m', 3),
(36, 'PDTKCB0UmoOnEZW', 10, '2020-03-17 05:13:14', NULL, '200.00', '2020-03-17 05:13:14', '54345231', 'oDGirP31jNnXYK6dza20m', 2),
(37, 'null', 15, '2020-03-18 07:43:05', NULL, '1900.00', '2020-03-18 07:43:05', '54345231', '2019DevAI', 2),
(38, 'null', 15, '2020-03-18 07:43:05', NULL, '3200.00', '2020-03-18 07:43:05', '54345231', '2019DevAI', 2),
(39, 'null', 15, '2020-03-18 07:43:06', NULL, '850.00', '2020-03-18 07:43:06', '54345231', '2019DevAI', 2),
(40, 'null', 15, '2020-03-18 07:43:06', NULL, '200.00', '2020-03-18 07:43:06', '54345231', '2019DevAI', 2),
(41, 'null', 5, '2020-03-25 16:58:15', NULL, '20.00', '2020-03-25 16:58:15', '54345231', '2019DevAI', 1),
(42, 'null', 50, '2020-03-25 16:58:15', NULL, '6.00', '2020-03-25 16:58:15', '54345231', '2019DevAI', 1),
(43, 'null', 50, '2020-03-25 16:58:15', NULL, '4.00', '2020-03-25 16:58:15', '54345231', '2019DevAI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Evelyn',
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_type_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_price` double(13,2) DEFAULT 0.00,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `performance_rating` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `added_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `threshold` int(11) DEFAULT 7,
  `quantity` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `clientId`, `branchId`, `source`, `product_id`, `category_id`, `product_title`, `product_type_id`, `product_description`, `product_image`, `product_price`, `cost_price`, `performance_rating`, `date_added`, `added_by`, `status`, `threshold`, `quantity`) VALUES
(1, '54345231', 1, 'Evelyn', 'PDT8b4OgIfa7QKV', 'PCAT00001', 'Lenovo Thinkpad i7 Laptop', NULL, 'This is the lenovo thinkpad laptop useable at all levels of computing', 'assets/images/products/kX19AHu5FtGjdEeQPaJ7hMrvq.png', 1900.00, '1500.00', NULL, '2020-03-14 10:45:41', '2019DevAI', '1', 5, 35),
(2, '54345231', 1, 'Evelyn', 'PDT0Roc2HhYBleu', 'PCAT00001', 'HP Pavilion i5 Laptop', NULL, 'This is the HP Pavilion Laptop that i love that much.', 'assets/images/products/hvgNJEKlOMYxcVuXZ5AeB27to.png', 3200.00, '2000.00', NULL, '2020-03-14 10:48:06', '2019DevAI', '1', 5, 6),
(3, '54345231', 1, 'Evelyn', 'PDTqODR15MfeBtu', 'PCAT00003', 'Samsung Fridge U87', NULL, 'This is the samsung fridge', 'assets/images/products/7BGE3gmH02bM5SpwWxF8ol9fT.png', 850.00, '600.00', NULL, '2020-03-14 10:49:18', '2019DevAI', '1', 5, 7),
(4, '54345231', 1, 'Evelyn', 'PDTKCB0UmoOnEZW', 'PCAT00001', 'Binatone Rice cooker A3456', NULL, '', 'assets/images/products/default.png', 200.00, '70.00', NULL, '2020-03-14 10:50:00', '2019DevAI', '1', 5, 20),
(24, '54345231', 2, 'Evelyn', 'PDT8b4OgIfa7QKV', 'PCAT00001', 'Lenovo Thinkpad i7 Laptop', NULL, 'This is the lenovo thinkpad laptop useable at all levels of computing', 'assets/images/products/kX19AHu5FtGjdEeQPaJ7hMrvq.png', 1900.00, '1500.00', NULL, '2020-03-18 07:43:05', '2019DevAI', '1', 5, 0),
(25, '54345231', 2, 'Evelyn', 'PDT0Roc2HhYBleu', 'PCAT00001', 'HP Pavilion i5 Laptop', NULL, 'This is the HP Pavilion Laptop that i love that much.', 'assets/images/products/hvgNJEKlOMYxcVuXZ5AeB27to.png', 3200.00, '2000.00', NULL, '2020-03-18 07:43:05', '2019DevAI', '1', 5, 0),
(26, '54345231', 2, 'Evelyn', 'PDTqODR15MfeBtu', 'PCAT00001', 'Samsung Fridge U87', NULL, 'This is the samsung fridge', 'assets/images/products/7BGE3gmH02bM5SpwWxF8ol9fT.png', 850.00, '600.00', NULL, '2020-03-18 07:43:06', '2019DevAI', '1', 5, 0),
(27, '54345231', 2, 'Evelyn', 'PDTKCB0UmoOnEZW', 'PCAT00001', 'Binatone Rice cooker A3456', NULL, '', 'assets/images/products/default.png', 200.00, '70.00', NULL, '2020-03-18 07:43:06', '2019DevAI', '1', 5, 4),
(28, '54345231', 1, 'Evelyn', 'PDTYSeqaZbnTijz', 'PCAT00002', 'Rice Cooker', NULL, '', 'assets/images/products/s5EjTIoMRr9dLmDe4SCPXpkf8.png', 200.00, '120.00', NULL, '2020-03-17 07:52:56', 'oDGirP31jNnXYK6dza20m', '1', 5, 0),
(29, '54345231', 1, 'Evelyn', 'PDTreKIGODg5JPX', 'PCAT00002', 'Test Low Price', NULL, '', 'assets/images/products/default.png', 4.00, '2.00', NULL, '2020-03-24 05:49:49', '2019DevAI', '1', 10, 59),
(30, '54345231', 5, 'Evelyn', 'PDT2faJrtN1j8Gp', 'PCAT00003', 'Note Book', NULL, '', 'assets/images/products/default.png', 20.00, '15.00', NULL, '2020-03-25 16:18:26', '2019DevAI', '1', 10, 10),
(31, '54345231', 5, 'Evelyn', 'PDT3V8oiamnrlQc', 'PCAT00003', 'Pencil', NULL, '', 'assets/images/products/default.png', 6.00, '2.00', NULL, '2020-03-25 16:20:17', '2019DevAI', '1', 5, 255),
(34, '54345231', 5, 'Evelyn', 'PDTMAbXIYQsHlai', 'PCAT00003', 'Pen', NULL, '', 'assets/images/products/default.png', 4.00, '1.00', NULL, '2020-03-25 16:34:20', '2019DevAI', '1', 30, 205),
(35, '54345231', 1, 'Evelyn', 'PDT2faJrtN1j8Gp', 'PCAT00003', 'Note Book', NULL, '', 'assets/images/products/default.png', 20.00, '15.00', NULL, '2020-03-25 16:58:14', '2019DevAI', '1', 10, 198),
(36, '54345231', 1, 'Evelyn', 'PDT3V8oiamnrlQc', 'PCAT00003', 'Pencil', NULL, '', 'assets/images/products/default.png', 6.00, '2.00', NULL, '2020-03-25 16:58:15', '2019DevAI', '1', 5, 40),
(37, '54345231', 1, 'Evelyn', 'PDTMAbXIYQsHlai', 'PCAT00003', 'Pen', NULL, '', 'assets/images/products/default.png', 4.00, '1.00', NULL, '2020-03-25 16:58:15', '2019DevAI', '1', 30, 38);

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

CREATE TABLE `products_categories` (
  `id` int(11) NOT NULL,
  `clientId` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`id`, `clientId`, `category_id`, `category`) VALUES
(1, '54345231', 'PCAT00001', ' Electronics & Appliances'),
(2, '54345231', 'PCAT00002', 'Food'),
(3, '54345231', 'PCAT00003', 'Furniture');

-- --------------------------------------------------------

--
-- Table structure for table `products_stocks`
--

CREATE TABLE `products_stocks` (
  `id` int(11) NOT NULL,
  `clientId` varchar(12) NOT NULL DEFAULT 'NULL',
  `branchId` varchar(12) NOT NULL DEFAULT 'NULL',
  `auto_id` varchar(32) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `retail_price` decimal(12,2) DEFAULT NULL,
  `previous_quantity` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `threshold` int(11) NOT NULL DEFAULT 0,
  `recorded_by` varchar(12) DEFAULT NULL,
  `date_log` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products_stocks`
--

INSERT INTO `products_stocks` (`id`, `clientId`, `branchId`, `auto_id`, `product_id`, `cost_price`, `retail_price`, `previous_quantity`, `quantity`, `total_quantity`, `threshold`, `recorded_by`, `date_log`) VALUES
(1, '54345231', '5', 'zlgeS0c9rGCj', 30, '15.00', '20.00', 0, 10, 10, 10, '2019DevAI', '2020-03-25 16:18:26'),
(2, '54345231', '5', 'qP2iQVTIwFA3', 31, '2.00', '6.00', 0, 300, 300, 1, '2019DevAI', '2020-03-25 16:20:17'),
(4, '54345231', '5', 'Uy8Iu5QbBOaY', 34, '1.00', '4.00', 0, 250, 250, 30, '2019DevAI', '2020-03-25 16:34:20'),
(5, '54345231', '5', 'tyZVoHkxO89dFiKDfvU7LzBgj', 30, '15.00', '20.00', 10, 5, 15, 10, '2019DevAI', '2020-03-25 16:43:32'),
(6, '54345231', '5', 'tyZVoHkxO89dFiKDfvU7LzBgj', 31, '2.00', '6.00', 300, 5, 305, 5, '2019DevAI', '2020-03-25 16:43:32'),
(7, '54345231', '5', 'tyZVoHkxO89dFiKDfvU7LzBgj', 34, '1.00', '4.00', 250, 5, 255, 30, '2019DevAI', '2020-03-25 16:43:32'),
(8, '54345231', '1', 'OoWMSYILD7Zc', 35, '15.00', '20.00', 0, 5, 5, 10, '2019DevAI', '2020-03-25 16:58:15'),
(9, '54345231', '1', 'OoWMSYILD7Zc', 36, '2.00', '6.00', 0, 50, 50, 5, '2019DevAI', '2020-03-25 16:58:15'),
(10, '54345231', '1', 'OoWMSYILD7Zc', 37, '1.00', '4.00', 0, 50, 50, 30, '2019DevAI', '2020-03-25 16:58:15'),
(11, '54345231', '1', '0iDajuRK9t4kz3ZNLw8m6WJAs', 28, '120.00', '200.00', 0, 20, 20, 5, '2019DevAI', '2020-03-25 17:01:45'),
(12, '54345231', '1', 'CH7LWdTkBqOpPgQczyVmn6RMi', 3, '600.00', '850.00', 0, 10, 10, 5, '2019DevAI', '2020-03-31 07:33:27'),
(13, '54345231', '1', 'CH7LWdTkBqOpPgQczyVmn6RMi', 4, '70.00', '200.00', 0, 20, 20, 5, '2019DevAI', '2020-03-31 07:33:27'),
(14, '54345231', '1', 'CH7LWdTkBqOpPgQczyVmn6RMi', 35, '15.00', '20.00', 0, 200, 200, 10, '2019DevAI', '2020-03-31 07:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `request_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_type` enum('Quote','Order') COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'GH¢',
  `request_overall` double(12,2) NOT NULL DEFAULT 0.00,
  `request_discount` double(13,2) NOT NULL DEFAULT 0.00,
  `request_total` double(13,2) DEFAULT 0.00,
  `request_date` datetime DEFAULT current_timestamp(),
  `request_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'pending',
  `recorded_by` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `clientId`, `branchId`, `request_id`, `customer_id`, `request_type`, `currency`, `request_overall`, `request_discount`, `request_total`, `request_date`, `request_status`, `recorded_by`, `deleted`) VALUES
(1, '54345231', 1, 'QT2020030002', 'EVC579927164188', 'Quote', 'GH¢', 0.00, 0.00, 12150.00, '2020-03-17 05:44:52', 'pending', '2019DevAI', '0'),
(2, '54345231', 1, 'QT2020030003', 'EVC611798467452', 'Quote', 'GH¢', 0.00, 0.00, 4250.00, '2020-03-17 05:48:32', 'pending', '2019DevAI', '0'),
(3, '54345231', 1, 'QT2020030004', 'EVC579927164188', 'Quote', 'GH¢', 0.00, 0.00, 15262.00, '2020-03-17 05:56:22', 'pending', '2019DevAI', '0'),
(4, '54345231', 1, 'QT2020030005', 'EVC427416398569', 'Quote', 'GH¢', 0.00, 0.00, 4050.00, '2020-03-18 11:46:12', 'pending', '2019DevAI', '0'),
(5, '54345231', 1, 'QT2020030006', 'EVC465398287316', 'Quote', 'GH¢', 0.00, 297.50, 5950.00, '2020-03-18 11:51:32', 'pending', '2019DevAI', '0'),
(6, '54345231', 1, 'QT2020030007', 'EVC611798467452', 'Quote', 'GH¢', 0.00, 250.00, 19450.00, '2020-03-18 11:53:34', 'pending', '2019DevAI', '0'),
(7, '54345231', 1, 'ORD2020030008', 'EVC579927164188', 'Order', 'GH¢', 0.00, 120.00, 6350.00, '2020-03-18 11:55:26', 'pending', '2019DevAI', '1'),
(9, '54345231', 1, 'ORD20200300009', 'EVC465398287316', 'Order', 'GH¢', 5950.00, 0.00, 5950.00, '2020-03-26 08:12:13', 'pending', '2019DevAI', '1'),
(10, '54345231', 1, 'ORD20200300011', 'EVC718437356928', 'Order', 'GH¢', 6150.00, 100.00, 6050.00, '2020-03-26 08:16:23', 'pending', '2019DevAI', '1'),
(11, '54345231', 1, 'QT20200300012', 'EVC423589361967', 'Quote', 'GH¢', 6150.00, 0.00, 6150.00, '2020-03-26 08:24:25', 'pending', '2019DevAI', '0'),
(12, '54345231', 1, 'ORD20200300013', 'EVC465398287316', 'Order', 'GH¢', 6354.00, 635.40, 5719.00, '2020-03-26 08:25:33', 'pending', '2019DevAI', '0'),
(13, '54345231', 1, 'QT20200300014', 'EVC465398287316', 'Quote', 'GH¢', 5964.00, 0.00, 5964.00, '2020-03-26 08:29:02', 'pending', '2019DevAI', '0'),
(14, '54345231', 1, 'QT20200300015', 'EVC792158792336', 'Quote', 'GH¢', 6380.00, 0.00, 6380.00, '2020-03-26 08:30:18', 'pending', '2019DevAI', '0'),
(15, '54345231', 1, 'QT20200300016', 'EVC218799756628', 'Quote', 'GH¢', 5512.00, 200.00, 5312.00, '2020-03-26 10:49:13', 'pending', '2019DevAI', '0'),
(16, '54345231', 1, 'ORD20200300017', 'EVC951937465721', 'Order', 'GH¢', 1380.00, 0.00, 1380.00, '2020-03-26 10:52:11', 'pending', '2019DevAI', '0'),
(17, '54345231', 1, 'ORD20200300018', 'EVC532694487286', 'Order', 'GH¢', 5110.00, 0.00, 5110.00, '2020-03-30 19:59:07', 'pending', '2019DevAI', '0'),
(18, '54345231', 1, 'ORD20200300019', 'EVC218799756628', 'Order', 'GH¢', 5106.00, 0.00, 5106.00, '2020-03-30 20:00:53', 'pending', '2019DevAI', '0');

-- --------------------------------------------------------

--
-- Table structure for table `requests_details`
--

CREATE TABLE `requests_details` (
  `id` int(11) NOT NULL,
  `auto_id` varchar(32) DEFAULT NULL,
  `request_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `product_price` double(12,2) NOT NULL DEFAULT 0.00,
  `product_total` double(12,2) NOT NULL DEFAULT 0.00,
  `request_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requests_details`
--

INSERT INTO `requests_details` (`id`, `auto_id`, `request_id`, `product_id`, `product_quantity`, `product_price`, `product_total`, `request_date`) VALUES
(1, NULL, 'QT2020030002', 3, 3, 850.00, 2550.00, '2020-03-17 05:44:52'),
(2, NULL, 'QT2020030002', 2, 3, 3200.00, 9600.00, '2020-03-17 05:44:52'),
(3, NULL, 'QT2020030003', 2, 1, 3200.00, 3200.00, '2020-03-17 05:48:32'),
(4, NULL, 'QT2020030003', 3, 1, 850.00, 850.00, '2020-03-17 05:48:32'),
(5, NULL, 'QT2020030003', 4, 1, 200.00, 200.00, '2020-03-17 05:48:32'),
(6, NULL, 'QT2020030004', 1, 3, 1900.00, 5700.00, '2020-03-17 05:56:22'),
(7, NULL, 'QT2020030004', 2, 2, 3200.00, 6400.00, '2020-03-17 05:56:22'),
(8, NULL, 'QT2020030004', 3, 3, 850.00, 2550.00, '2020-03-17 05:56:22'),
(9, NULL, 'QT2020030004', 4, 3, 200.00, 600.00, '2020-03-17 05:56:22'),
(10, NULL, 'QT2020030004', 9, 1, 12.00, 12.00, '2020-03-17 05:56:22'),
(11, NULL, 'QT2020030005', 2, 1, 3200.00, 3200.00, '2020-03-18 11:46:12'),
(12, NULL, 'QT2020030005', 3, 1, 850.00, 850.00, '2020-03-18 11:46:12'),
(13, NULL, 'QT2020030006', 2, 1, 3200.00, 3200.00, '2020-03-18 11:51:32'),
(14, NULL, 'QT2020030006', 1, 1, 1900.00, 1900.00, '2020-03-18 11:51:32'),
(15, NULL, 'QT2020030006', 3, 1, 850.00, 850.00, '2020-03-18 11:51:32'),
(16, NULL, 'QT2020030007', 3, 1, 850.00, 850.00, '2020-03-18 11:53:34'),
(17, NULL, 'QT2020030007', 4, 3, 200.00, 600.00, '2020-03-18 11:53:34'),
(18, NULL, 'QT2020030007', 28, 4, 200.00, 800.00, '2020-03-18 11:53:34'),
(19, NULL, 'QT2020030007', 2, 3, 3200.00, 9600.00, '2020-03-18 11:53:34'),
(20, NULL, 'QT2020030007', 1, 4, 1900.00, 7600.00, '2020-03-18 11:53:34'),
(21, NULL, 'ORD2020030008', 3, 1, 850.00, 850.00, '2020-03-18 11:55:26'),
(22, NULL, 'ORD2020030008', 4, 1, 200.00, 200.00, '2020-03-18 11:55:26'),
(23, NULL, 'ORD2020030008', 28, 1, 200.00, 200.00, '2020-03-18 11:55:26'),
(24, NULL, 'ORD2020030008', 2, 1, 3200.00, 3200.00, '2020-03-18 11:55:26'),
(25, NULL, 'ORD2020030008', 1, 1, 1900.00, 1900.00, '2020-03-18 11:55:26'),
(32, NULL, 'ORD20200300009', 1, 1, 1900.00, 1900.00, '2020-03-26 08:12:13'),
(33, NULL, 'ORD20200300009', 2, 1, 3200.00, 3200.00, '2020-03-26 08:12:13'),
(34, NULL, 'ORD20200300009', 3, 1, 850.00, 850.00, '2020-03-26 08:12:13'),
(35, NULL, 'ORD20200300011', 4, 1, 200.00, 200.00, '2020-03-26 08:16:23'),
(36, NULL, 'ORD20200300011', 2, 1, 3200.00, 3200.00, '2020-03-26 08:16:23'),
(37, NULL, 'ORD20200300011', 3, 1, 850.00, 850.00, '2020-03-26 08:16:23'),
(38, NULL, 'ORD20200300011', 1, 1, 1900.00, 1900.00, '2020-03-26 08:16:23'),
(39, NULL, 'QT20200300012', 1, 1, 1900.00, 1900.00, '2020-03-26 08:24:25'),
(40, NULL, 'QT20200300012', 2, 1, 3200.00, 3200.00, '2020-03-26 08:24:25'),
(41, NULL, 'QT20200300012', 3, 1, 850.00, 850.00, '2020-03-26 08:24:25'),
(42, NULL, 'QT20200300012', 4, 1, 200.00, 200.00, '2020-03-26 08:24:25'),
(43, NULL, 'ORD20200300013', 1, 1, 1900.00, 1900.00, '2020-03-26 08:25:33'),
(44, NULL, 'ORD20200300013', 2, 1, 3200.00, 3200.00, '2020-03-26 08:25:33'),
(45, NULL, 'ORD20200300013', 3, 1, 850.00, 850.00, '2020-03-26 08:25:33'),
(46, NULL, 'ORD20200300013', 4, 1, 200.00, 200.00, '2020-03-26 08:25:33'),
(47, NULL, 'ORD20200300013', 29, 1, 4.00, 4.00, '2020-03-26 08:25:33'),
(48, NULL, 'ORD20200300013', 28, 1, 200.00, 200.00, '2020-03-26 08:25:33'),
(49, NULL, 'QT20200300014', 2, 1, 3200.00, 3200.00, '2020-03-26 08:29:02'),
(50, NULL, 'QT20200300014', 3, 1, 850.00, 850.00, '2020-03-26 08:29:02'),
(51, NULL, 'QT20200300014', 29, 1, 4.00, 4.00, '2020-03-26 08:29:02'),
(52, NULL, 'QT20200300014', 36, 1, 6.00, 6.00, '2020-03-26 08:29:02'),
(53, NULL, 'QT20200300014', 37, 1, 4.00, 4.00, '2020-03-26 08:29:02'),
(54, NULL, 'QT20200300014', 1, 1, 1900.00, 1900.00, '2020-03-26 08:29:02'),
(55, NULL, 'QT20200300015', 4, 1, 200.00, 200.00, '2020-03-26 08:30:18'),
(56, NULL, 'QT20200300015', 3, 1, 850.00, 850.00, '2020-03-26 08:30:18'),
(57, NULL, 'QT20200300015', 2, 1, 3200.00, 3200.00, '2020-03-26 08:30:18'),
(58, NULL, 'QT20200300015', 1, 1, 1900.00, 1900.00, '2020-03-26 08:30:18'),
(59, NULL, 'QT20200300015', 28, 1, 200.00, 200.00, '2020-03-26 08:30:18'),
(60, NULL, 'QT20200300015', 36, 1, 6.00, 6.00, '2020-03-26 08:30:18'),
(61, NULL, 'QT20200300015', 35, 1, 20.00, 20.00, '2020-03-26 08:30:18'),
(62, NULL, 'QT20200300015', 29, 1, 4.00, 4.00, '2020-03-26 08:30:18'),
(63, NULL, 'QT20200300016', 2, 1, 3203.00, 3203.00, '2020-03-26 10:49:13'),
(64, NULL, 'QT20200300016', 1, 1, 1906.00, 1906.00, '2020-03-26 10:49:13'),
(65, NULL, 'QT20200300016', 4, 1, 203.00, 203.00, '2020-03-26 10:49:13'),
(66, NULL, 'QT20200300016', 28, 1, 200.00, 200.00, '2020-03-26 10:49:13'),
(67, NULL, 'ORD20200300017', 35, 5, 20.00, 100.00, '2020-03-26 10:52:11'),
(68, NULL, 'ORD20200300017', 29, 20, 4.00, 80.00, '2020-03-26 10:52:11'),
(69, NULL, 'ORD20200300017', 28, 6, 200.00, 1200.00, '2020-03-26 10:52:11'),
(70, NULL, 'ORD20200300018', 1, 1, 1900.00, 1900.00, '2020-03-30 19:59:07'),
(71, NULL, 'ORD20200300018', 2, 1, 3200.00, 3200.00, '2020-03-30 19:59:07'),
(72, NULL, 'ORD20200300018', 29, 1, 4.00, 4.00, '2020-03-30 19:59:07'),
(73, NULL, 'ORD20200300018', 36, 1, 6.00, 6.00, '2020-03-30 19:59:07'),
(74, NULL, 'ORD20200300019', 1, 1, 1900.00, 1900.00, '2020-03-30 20:00:53'),
(75, NULL, 'ORD20200300019', 2, 1, 3200.00, 3200.00, '2020-03-30 20:00:53'),
(76, NULL, 'ORD20200300019', 36, 1, 6.00, 6.00, '2020-03-30 20:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Evelyn',
  `mode` enum('online','offline') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'online',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `order_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordered_by_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'customer',
  `sale_lead_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recorded_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'GH¢',
  `credit_sales` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `order_discount` double(13,2) NOT NULL DEFAULT 0.00,
  `order_amount_balance` double(13,2) NOT NULL DEFAULT 0.00,
  `overall_order_amount` double(13,2) NOT NULL DEFAULT 0.00,
  `order_amount_paid` double(13,2) DEFAULT 0.00,
  `order_comments` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'pending',
  `log_date` datetime DEFAULT current_timestamp(),
  `deleted` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `payment_date` datetime DEFAULT NULL,
  `payment_type` enum('cash','card','credit','MoMo') COLLATE utf8_unicode_ci DEFAULT 'cash',
  `transaction_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `clientId`, `source`, `mode`, `branchId`, `order_id`, `customer_id`, `ordered_by_id`, `customer_type`, `sale_lead_id`, `recorded_by`, `currency`, `credit_sales`, `order_discount`, `order_amount_balance`, `overall_order_amount`, `order_amount_paid`, `order_comments`, `order_date`, `order_status`, `log_date`, `deleted`, `payment_date`, `payment_type`, `transaction_id`) VALUES
(1, '54345231', 'Evelyn', 'online', 2, 'INV2020030002', 'EVC2641795342893', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 4250.00, 4250.00, NULL, '2020-03-18 07:44:32', 'confirmed', '2020-03-18 07:44:32', '0', NULL, 'cash', '371652487994'),
(2, '54345231', 'Evelyn', 'online', 2, 'INV2020030003', 'EVC4274146398569', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 50.00, 6150.00, 6150.00, NULL, '2020-03-18 07:44:50', 'confirmed', '2020-03-18 07:44:50', '0', NULL, 'cash', '483276284131'),
(3, '54345231', 'Evelyn', 'online', 2, 'INV2020030004', 'EVC6114798467452', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '1', 200.00, 5950.00, 6150.00, 5950.00, NULL, '2020-03-18 07:45:32', 'confirmed', '2020-03-18 07:45:32', '0', NULL, 'credit', '389787431525'),
(4, '54345231', 'Evelyn', 'online', 2, 'INV2020030005', 'EVC2641795342893', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 15300.00, 15300.00, NULL, '2020-03-18 07:46:24', 'confirmed', '2020-03-18 07:46:24', '0', NULL, 'cash', '234516877236'),
(5, '54345231', 'Evelyn', 'online', 1, 'INV2020030006', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 150.00, 4250.00, 4250.00, NULL, '2020-03-18 07:47:18', 'confirmed', '2020-03-18 07:47:18', '0', NULL, 'cash', '917438252913'),
(6, '54345231', 'Evelyn', 'online', 1, 'INV2020030007', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 4900.00, 4900.00, NULL, '2020-03-18 07:47:44', 'confirmed', '2020-03-18 07:47:44', '0', NULL, 'cash', '326941742858'),
(7, '54345231', 'Evelyn', 'online', 1, 'INV2020030008', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 4050.00, 4050.00, NULL, '2020-03-17 07:48:46', 'confirmed', '2020-03-17 07:48:46', '0', NULL, 'cash', '858544123991'),
(8, '54345231', 'Evelyn', 'online', 1, 'INV2020030009', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 200.00, 200.00, 200.00, NULL, '2020-03-17 07:48:59', 'confirmed', '2020-03-17 07:48:59', '0', NULL, 'credit', '964917232586'),
(9, '54345231', 'Evelyn', 'online', 1, 'INV2020030010', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 5100.00, 5100.00, NULL, '2020-03-17 07:49:19', 'confirmed', '2020-03-17 07:49:19', '0', NULL, 'cash', '115642247693'),
(10, '54345231', 'Evelyn', 'online', 2, 'INV2020030011', 'EVC4274146398569', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 5100.00, 5100.00, NULL, '2020-03-17 07:49:56', 'confirmed', '2020-03-17 07:49:56', '0', NULL, 'cash', '521895264871'),
(11, '54345231', 'Evelyn', 'online', 2, 'INV2020030012', 'EVC836312984427', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 4250.00, 4250.00, NULL, '2020-03-17 07:51:57', 'confirmed', '2020-03-17 07:51:57', '0', NULL, 'cash', '834253568176'),
(12, '54345231', 'Evelyn', 'online', 1, 'INV2020030013', 'EVC579927164188', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 600.00, 600.00, NULL, '2020-03-17 07:53:32', 'confirmed', '2020-03-17 07:53:32', '0', NULL, 'cash', '726543621973'),
(13, '54345231', 'Evelyn', 'online', 1, 'INV2020030014', 'EVC427416398569', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 1250.00, 1250.00, NULL, '2020-03-17 07:53:46', 'confirmed', '2020-03-17 07:53:46', '0', NULL, 'cash', '358772594124'),
(14, '54345231', 'Evelyn', 'online', 1, 'INV2020030015', 'EVC718437356928', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '1', 0.00, 1050.00, 1050.00, 1050.00, NULL, '2020-03-17 07:53:59', 'confirmed', '2020-03-17 07:53:59', '0', NULL, 'credit', '973319817854'),
(16, '54345231', 'Evelyn', 'online', 1, 'INV2020030016', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 6350.00, 6350.00, NULL, '2020-03-18 07:56:43', 'confirmed', '2020-03-18 07:56:43', '0', NULL, 'cash', '585271639687'),
(17, '54345231', 'Evelyn', 'online', 1, 'INV2020030018', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3400.00, 3400.00, NULL, '2020-03-18 10:09:33', 'confirmed', '2020-03-18 10:09:33', '0', NULL, 'cash', '216978573892'),
(18, '54345231', 'Evelyn', 'online', 1, 'INV202003_1_0019', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 50.00, 100.00, 4050.00, 4000.00, NULL, '2020-03-18 15:28:51', 'confirmed', '2020-03-18 15:28:51', '0', NULL, 'cash', '215863441876'),
(19, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0020', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 4250.00, 4250.00, NULL, '2020-03-18 15:30:43', 'confirmed', '2020-03-18 15:30:43', '0', NULL, 'cash', '382514664797'),
(20, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0021', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 406.50, 56.50, 13550.00, 13143.50, NULL, '2020-03-18 16:12:52', 'confirmed', '2020-03-18 16:12:52', '0', NULL, 'cash', '699836414132'),
(21, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0022', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1250.00, 1250.00, NULL, '2020-03-18 20:23:57', 'confirmed', '2020-03-18 20:23:57', '0', NULL, 'cash', '769243915178'),
(22, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0023', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1250.00, 1250.00, NULL, '2020-03-19 04:34:22', 'confirmed', '2020-03-19 04:34:22', '0', NULL, 'cash', '324758114957'),
(23, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0024', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 200.00, 200.00, 200.00, NULL, '2020-03-19 04:34:37', 'confirmed', '2020-03-19 04:34:37', '0', NULL, 'credit', '564751969243'),
(24, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0025', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 50.00, 0.00, 1250.00, 1200.00, NULL, '2020-03-19 04:34:58', 'confirmed', '2020-03-19 04:34:58', '0', NULL, 'cash', '371887414952'),
(25, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0026', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 50.00, 0.00, 12750.00, 12700.00, NULL, '2020-03-19 04:35:37', 'confirmed', '2020-03-19 04:35:37', '0', NULL, 'cash', '175833241926'),
(26, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0027', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 850.00, 850.00, NULL, '2020-03-19 05:04:25', 'confirmed', '2020-03-19 05:04:25', '0', NULL, 'cash', '943621485271'),
(27, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0028', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 200.00, 200.00, NULL, '2020-03-19 05:08:37', 'confirmed', '2020-03-19 05:08:37', '0', NULL, 'cash', '738914365961'),
(28, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_0029', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 400.00, 400.00, NULL, '2020-03-19 06:18:21', 'confirmed', '2020-03-19 06:18:21', '0', NULL, 'cash', '769825445136'),
(29, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_00030', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 50.00, 0.00, 6350.00, 6300.00, NULL, '2020-03-19 11:08:44', 'confirmed', '2020-03-19 11:08:44', '0', NULL, 'cash', '915153772688'),
(30, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_00031', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 200.00, 0.00, 4650.00, 4450.00, NULL, '2020-03-19 14:36:46', 'confirmed', '2020-03-19 14:36:46', '0', NULL, 'cash', '876345581976'),
(31, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_00032', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 2950.00, 2950.00, NULL, '2020-03-19 14:37:24', 'confirmed', '2020-03-19 14:37:24', '0', NULL, 'cash', '725988317634'),
(32, '54345231', 'Evelyn', 'online', 1, 'INV202003_01_00033', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 1050.00, 1050.00, 1050.00, NULL, '2020-03-19 14:39:20', 'confirmed', '2020-03-19 14:39:20', '0', NULL, 'credit', '489823927711'),
(33, '54345231', 'Evelyn', 'online', 1, 'INV2020030100034', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1900.00, 1900.00, NULL, '2020-03-20 07:14:42', 'confirmed', '2020-03-20 07:14:42', '0', NULL, 'cash', '618449291256'),
(34, '54345231', 'Evelyn', 'online', 1, 'INV2020030100035', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3400.00, 3400.00, NULL, '2020-03-20 07:19:17', 'confirmed', '2020-03-20 07:19:17', '0', NULL, 'cash', '362899575124'),
(35, '54345231', 'Evelyn', 'online', 1, 'INV2020030100036', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 100.00, 0.00, 3600.00, 3500.00, NULL, '2020-03-20 07:19:46', 'confirmed', '2020-03-20 07:19:46', '0', NULL, 'cash', '468391397765'),
(36, '54345231', 'Evelyn', 'online', 1, 'INV2020030100037', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 3400.00, 3400.00, 3400.00, NULL, '2020-03-20 07:35:03', 'confirmed', '2020-03-20 07:35:03', '0', NULL, 'credit', '587613695143'),
(37, '54345231', 'Evelyn', 'online', 2, 'INV2020030200038', 'EVC4653982847316', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 4250.00, 4250.00, NULL, '2020-03-20 07:35:42', 'confirmed', '2020-03-20 07:35:42', '0', NULL, 'cash', '528735486139'),
(38, '54345231', 'Evelyn', 'online', 2, 'INV2020030200039', 'EVC2641795342893', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-20 07:36:00', 'confirmed', '2020-03-20 07:36:00', '0', NULL, 'cash', '298396517461'),
(39, '54345231', 'Evelyn', 'offline', 1, 'INV5765739303628', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 4450.00, 4450.00, NULL, '2020-03-20 08:11:45', 'confirmed', '2020-03-20 08:12:17', '0', NULL, 'cash', '118040903060'),
(40, '54345231', 'Evelyn', 'offline', 1, 'INV5869421973848', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 6350.00, 6350.00, NULL, '2020-03-20 08:11:01', 'confirmed', '2020-03-20 08:12:17', '0', NULL, 'cash', '335726444349'),
(41, '54345231', 'Evelyn', 'offline', 1, 'INV9737257398367', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3400.00, 3400.00, NULL, '2020-03-20 08:10:19', 'confirmed', '2020-03-20 08:12:17', '0', NULL, 'cash', '142565223964'),
(42, '54345231', 'Evelyn', 'online', 1, 'INV2020030100043', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3550.00, 3550.00, NULL, '2020-03-20 11:53:08', 'confirmed', '2020-03-20 11:53:08', '0', NULL, 'cash', '149158623872'),
(43, '54345231', 'Evelyn', 'online', 1, 'INV2020030100044', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-20 11:54:09', 'confirmed', '2020-03-20 11:54:09', '0', NULL, 'cash', '487564653282'),
(44, '54345231', 'Evelyn', 'online', 2, 'INV2020030200045', 'EVC4653982847316', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 4050.00, 4050.00, NULL, '2020-03-20 14:12:16', 'confirmed', '2020-03-20 14:12:16', '0', NULL, 'cash', '192133744825'),
(45, '54345231', 'Evelyn', 'online', 1, 'INV2020030100046', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 13750.00, 13750.00, NULL, '2020-03-20 14:29:02', 'confirmed', '2020-03-20 14:29:02', '0', NULL, 'cash', '541894765967'),
(46, '54345231', 'Evelyn', 'online', 1, 'INV2020030100047', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 451.50, 0.00, 15050.00, 14598.50, NULL, '2020-03-20 19:39:24', 'confirmed', '2020-03-20 19:39:24', '0', NULL, 'cash', '436259168427'),
(47, '54345231', 'Evelyn', 'online', 1, 'INV2020030100048', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 101.00, 0.00, 5050.00, 4949.00, NULL, '2020-03-20 19:39:54', 'confirmed', '2020-03-20 19:39:54', '0', NULL, 'cash', '943598763224'),
(48, '54345231', 'Evelyn', 'online', 1, 'INV2020030100049', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 2950.00, 2950.00, 2950.00, NULL, '2020-03-20 20:05:08', 'confirmed', '2020-03-20 20:05:08', '0', NULL, 'credit', '336499285587'),
(49, '54345231', 'Evelyn', 'online', 1, 'INV2020030100050', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3600.00, 3600.00, NULL, '2020-03-20 20:05:23', 'confirmed', '2020-03-20 20:05:23', '0', NULL, 'cash', '579434395182'),
(50, '54345231', 'Evelyn', 'online', 1, 'INV2020030100051', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 612.00, 0.00, 15300.00, 14688.00, NULL, '2020-03-20 20:05:56', 'confirmed', '2020-03-20 20:05:56', '0', NULL, 'cash', '915389517786'),
(51, '54345231', 'Evelyn', 'online', 1, 'INV2020030100052', 'EVC465398287316', NULL, 'customer', NULL, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', 'GH¢', '0', 0.00, 0.00, 3600.00, 3600.00, NULL, '2020-03-20 20:07:31', 'confirmed', '2020-03-20 20:07:31', '0', NULL, 'cash', '579518492714'),
(52, '54345231', 'Evelyn', 'online', 1, 'INV2020030100053', 'EVC427416398569', NULL, 'customer', NULL, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', 'GH¢', '1', 0.00, 4050.00, 4050.00, 4050.00, NULL, '2020-03-20 20:07:54', 'confirmed', '2020-03-20 20:07:54', '0', NULL, 'credit', '552478723696'),
(53, '54345231', 'Evelyn', 'online', 1, 'INV2020030100054', 'EVC427416398569', NULL, 'customer', NULL, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', 'GH¢', '1', 0.00, 1250.00, 1250.00, 1250.00, NULL, '2020-03-20 20:08:08', 'confirmed', '2020-03-20 20:08:08', '0', NULL, 'credit', '648237312997'),
(54, '54345231', 'Evelyn', 'online', 1, 'INV2020030100055', 'EVC718437356928', NULL, 'customer', NULL, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', 'GH¢', '0', 100.00, 150.00, 6150.00, 6050.00, NULL, '2020-03-20 20:08:33', 'confirmed', '2020-03-20 20:08:33', '0', NULL, 'cash', '661948241393'),
(55, '54345231', 'Evelyn', 'online', 1, 'INV2020030100056', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 1.00, -1904.00, 6350.00, 6349.00, NULL, '2020-03-20 20:15:12', 'confirmed', '2020-03-20 20:15:12', '0', NULL, 'cash', '736414796219'),
(56, '54345231', 'Evelyn', 'online', 1, 'INV2020030100057', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 6150.00, 6150.00, NULL, '2020-03-20 21:40:21', 'confirmed', '2020-03-20 21:40:21', '0', NULL, 'cash', '533464195798'),
(57, '54345231', 'Evelyn', 'online', 1, 'INV2020030100058', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 200.00, 200.00, NULL, '2020-03-21 07:32:50', 'confirmed', '2020-03-21 07:32:50', '0', NULL, 'cash', '346139527179'),
(58, '54345231', 'Evelyn', 'online', 1, 'INV2020030100059', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 600.00, 600.00, NULL, '2020-03-21 07:33:18', 'confirmed', '2020-03-21 07:33:18', '0', NULL, 'cash', '665379478221'),
(59, '54345231', 'Evelyn', 'online', 1, 'INV2020030100060', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 2100.00, 2100.00, NULL, '2020-03-22 23:06:45', 'confirmed', '2020-03-22 23:06:45', '0', NULL, 'cash', '714285586691'),
(60, '54345231', 'Evelyn', 'online', 1, 'INV2020030100061', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-22 23:07:03', 'confirmed', '2020-03-22 23:07:03', '0', NULL, 'cash', '194536895776'),
(61, '54345231', 'Evelyn', 'online', 1, 'INV2020030100062', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 150.00, 0.00, 6350.00, 6200.00, NULL, '2020-03-22 23:07:47', 'confirmed', '2020-03-22 23:07:47', '0', NULL, 'cash', '935568418322'),
(62, '54345231', 'Evelyn', 'online', 1, 'INV2020030100063', 'EVC579927164188', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 300.00, 0.00, 34650.00, 34350.00, NULL, '2020-03-22 23:08:20', 'confirmed', '2020-03-22 23:08:20', '0', NULL, 'cash', '587634228396'),
(63, '54345231', 'Evelyn', 'online', 2, 'INV2020030200064', 'EVC4653982847316', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 4250.00, 4250.00, NULL, '2020-03-22 23:09:46', 'confirmed', '2020-03-22 23:09:46', '0', NULL, 'cash', '656292541187'),
(64, '54345231', 'Evelyn', 'online', 2, 'INV2020030200065', 'EVC5794927164188', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '1', 0.00, 4450.00, 4450.00, 4450.00, NULL, '2020-03-22 23:10:04', 'confirmed', '2020-03-22 23:10:04', '0', NULL, 'credit', '465289383514'),
(65, '54345231', 'Evelyn', 'online', 2, 'INV2020030200066', 'EVC7148437356928', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 200.00, 0.00, 22300.00, 22100.00, NULL, '2020-03-22 23:10:37', 'confirmed', '2020-03-22 23:10:37', '0', NULL, 'cash', '194766918275'),
(66, '54345231', 'Evelyn', 'online', 1, 'INV2020030100067', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1250.00, 1250.00, NULL, '2020-03-13 23:40:48', 'confirmed', '2020-03-13 23:40:48', '0', NULL, 'cash', '345917678392'),
(67, '54345231', 'Evelyn', 'online', 1, 'INV2020030100068', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 200.00, 200.00, 200.00, NULL, '2020-03-13 23:41:00', 'confirmed', '2020-03-13 23:41:00', '0', NULL, 'credit', '283561493798'),
(68, '54345231', 'Evelyn', 'online', 1, 'INV2020030100069', 'EVC465398287316', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 50.00, 0.00, 2300.00, 2250.00, NULL, '2020-03-13 23:41:30', 'confirmed', '2020-03-13 23:41:30', '0', NULL, 'cash', '766329414897'),
(69, '54345231', 'Evelyn', 'online', 1, 'INV2020030100070', 'EVC579927164188', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 100.00, 0.00, 4250.00, 4150.00, NULL, '2020-03-13 23:41:54', 'confirmed', '2020-03-13 23:41:54', '0', NULL, 'cash', '279865637341'),
(70, '54345231', 'Evelyn', 'online', 1, 'INV2020030100071', 'EVC465398287316', NULL, 'customer', NULL, 'eqLTHf9MW7x0NBGP5Vs83vp', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-13 23:42:21', 'confirmed', '2020-03-13 23:42:21', '0', NULL, 'cash', '931358954176'),
(71, '54345231', 'Evelyn', 'online', 1, 'INV2020030100072', 'EVC579927164188', NULL, 'customer', NULL, 'eqLTHf9MW7x0NBGP5Vs83vp', 'GH¢', '0', 0.00, 0.00, 3150.00, 3150.00, NULL, '2020-03-13 23:42:38', 'confirmed', '2020-03-13 23:42:38', '0', NULL, 'cash', '317792483648'),
(72, '54345231', 'Evelyn', 'online', 1, 'INV2020030100073', 'EVC611798467452', NULL, 'customer', NULL, 'eqLTHf9MW7x0NBGP5Vs83vp', 'GH¢', '1', 0.00, 400.00, 400.00, 400.00, NULL, '2020-03-13 23:42:50', 'confirmed', '2020-03-13 23:42:50', '0', NULL, 'credit', '975462363498'),
(73, '54345231', 'Evelyn', 'online', 1, 'INV2020030100074', 'EVC261795342893', NULL, 'customer', NULL, 'eqLTHf9MW7x0NBGP5Vs83vp', 'GH¢', '0', 0.00, 0.00, 4050.00, 4050.00, NULL, '2020-03-15 23:43:18', 'confirmed', '2020-03-15 23:43:18', '0', NULL, 'cash', '239174896256'),
(74, '54345231', 'Evelyn', 'online', 1, 'INV2020030100075', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 540.00, 0.00, 10800.00, 10260.00, NULL, '2020-03-15 23:44:32', 'confirmed', '2020-03-15 23:44:32', '0', NULL, 'cash', '225743687993'),
(75, '54345231', 'Evelyn', 'online', 1, 'INV2020030100076', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 89.00, 0.00, 4450.00, 4361.00, NULL, '2020-03-15 23:44:52', 'confirmed', '2020-03-15 23:44:52', '0', NULL, 'cash', '165194624283'),
(76, '54345231', 'Evelyn', 'online', 1, 'INV2020030100077', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 100.00, 0.00, 5100.00, 5000.00, NULL, '2020-03-23 00:03:45', 'confirmed', '2020-03-23 00:03:45', '0', NULL, 'cash', '219644195273'),
(77, '54345231', 'Evelyn', 'online', 1, 'INV2020030100078', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 645.00, 0.00, 21500.00, 20855.00, NULL, '2020-03-23 00:04:37', 'confirmed', '2020-03-23 00:04:37', '0', NULL, 'cash', '133676998445'),
(78, '54345231', 'Evelyn', 'online', 1, 'INV2020030100079', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 100.00, 0.00, 3200.00, 3100.00, NULL, '2020-03-23 07:32:39', 'confirmed', '2020-03-23 07:32:39', '0', NULL, 'cash', '398552713466'),
(79, '54345231', 'Evelyn', 'online', 1, 'INV2020030100080', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 400.00, 400.00, 400.00, NULL, '2020-03-23 07:32:50', 'confirmed', '2020-03-23 07:32:50', '0', NULL, 'credit', '835118529269'),
(80, '54345231', 'Evelyn', 'online', 1, 'INV2020030100081', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 38.00, 0.00, 1900.00, 1862.00, NULL, '2020-03-23 07:33:12', 'confirmed', '2020-03-23 07:33:12', '0', NULL, 'cash', '385417963695'),
(81, '54345231', 'Evelyn', 'online', 1, 'INV2020030100082', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 477.50, 9072.50, 9550.00, 9072.50, NULL, '2020-03-23 07:35:43', 'confirmed', '2020-03-23 07:35:43', '0', NULL, 'credit', '984362737514'),
(82, '54345231', 'Evelyn', 'online', 1, 'INV2020030100083', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 592.50, 0.00, 19750.00, 19157.50, NULL, '2020-03-23 08:16:39', 'confirmed', '2020-03-23 08:16:39', '0', NULL, 'cash', '869497723841'),
(83, '54345231', 'Evelyn', 'online', 1, 'POS2020030100084', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 5.00, 5.00, 200.00, 195.00, NULL, '2020-03-23 16:34:22', 'confirmed', '2020-03-23 16:34:22', '0', NULL, 'cash', '428661357742'),
(84, '54345231', 'Evelyn', 'online', 1, 'POS2020030100085', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 4050.00, 4050.00, NULL, '2020-03-24 03:41:20', 'confirmed', '2020-03-24 03:41:20', '0', NULL, 'cash', '791693542254'),
(85, '54345231', 'Evelyn', 'online', 1, 'POS2020030100086', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 200.00, 200.00, NULL, '2020-03-24 03:51:11', 'confirmed', '2020-03-24 03:51:11', '0', NULL, 'cash', '223176316547'),
(86, '54345231', 'Evelyn', 'online', 1, 'POS2020030100087', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:44:48', 'pending', '2020-03-24 04:44:48', '0', NULL, 'card', '125641838756'),
(87, '54345231', 'Evelyn', 'online', 1, 'POS2020030100088', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:45:55', 'confirmed', '2020-03-24 04:45:55', '0', NULL, 'cash', '972534165834'),
(88, '54345231', 'Evelyn', 'online', 1, 'POS2020030100089', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:47:41', 'confirmed', '2020-03-24 04:47:41', '0', NULL, 'cash', '323676849551'),
(89, '54345231', 'Evelyn', 'online', 1, 'POS2020030100090', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:48:42', 'confirmed', '2020-03-24 04:48:42', '0', NULL, 'cash', '387416397545'),
(90, '54345231', 'Evelyn', 'online', 1, 'POS2020030100091', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -180.00, 200.00, 200.00, NULL, '2020-03-24 04:50:24', 'confirmed', '2020-03-24 04:50:24', '0', NULL, 'cash', '194739813527'),
(91, '54345231', 'Evelyn', 'online', 1, 'POS2020030100092', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -180.00, 200.00, 200.00, NULL, '2020-03-24 04:51:51', 'confirmed', '2020-03-24 04:51:51', '0', NULL, 'cash', '917326286355'),
(92, '54345231', 'Evelyn', 'online', 1, 'POS2020030100093', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:54:02', 'confirmed', '2020-03-24 04:54:02', '0', NULL, 'cash', '862774518296'),
(93, '54345231', 'Evelyn', 'online', 1, 'POS2020030100094', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 04:55:32', 'confirmed', '2020-03-24 04:55:32', '0', NULL, 'cash', '814315637726'),
(94, '54345231', 'Evelyn', 'online', 1, 'POS2020030100095', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 200.00, 200.00, NULL, '2020-03-24 05:00:18', 'confirmed', '2020-03-24 05:00:18', '0', NULL, 'cash', '561873284764'),
(95, '54345231', 'Evelyn', 'online', 1, 'POS2020030100096', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 05:32:34', 'cancelled', '2020-03-24 05:32:34', '0', '2020-03-24 05:32:59', 'MoMo', '883259147676'),
(96, '54345231', 'Evelyn', 'online', 1, 'POS2020030100097', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 05:46:50', 'cancelled', '2020-03-24 05:46:50', '0', '2020-03-24 05:47:00', 'card', '376146922981'),
(97, '54345231', 'Evelyn', 'online', 1, 'POS2020030100098', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -200.00, 200.00, 200.00, NULL, '2020-03-24 05:47:07', 'cancelled', '2020-03-24 05:47:07', '0', '2020-03-24 05:47:15', 'cash', '595967842632'),
(98, '54345231', 'Evelyn', 'online', 1, 'POS2020030100099', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 05:50:06', 'cancelled', '2020-03-24 05:50:06', '0', '2020-03-24 05:50:14', 'MoMo', '939327652148'),
(99, '54345231', 'Evelyn', 'online', 1, 'POS2020030100100', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 05:50:37', 'cancelled', '2020-03-24 05:50:37', '1', NULL, 'MoMo', '723466587934'),
(100, '54345231', 'Evelyn', 'online', 1, 'POS2020030100101', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:07:17', 'pending', '2020-03-24 06:07:17', '0', NULL, 'MoMo', '837645247915'),
(101, '54345231', 'Evelyn', 'online', 1, 'POS2020030100102', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:09:19', 'confirmed', '2020-03-24 06:09:19', '0', '2020-03-24 06:44:01', 'card', '822117799554'),
(102, '54345231', 'Evelyn', 'online', 1, 'POS2020030100103', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:10:07', 'pending', '2020-03-24 06:10:07', '0', NULL, 'card', '327641795356'),
(103, '54345231', 'Evelyn', 'online', 1, 'POS2020030100104', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:11:03', 'pending', '2020-03-24 06:11:03', '0', NULL, 'card', '484278651569'),
(104, '54345231', 'Evelyn', 'online', 1, 'POS2020030100105', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:12:30', 'pending', '2020-03-24 06:12:30', '0', NULL, 'card', '512743216388'),
(105, '54345231', 'Evelyn', 'online', 1, 'POS2020030100106', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:14:40', 'confirmed', '2020-03-24 06:14:40', '0', '2020-03-24 06:15:14', 'card', '374564865989'),
(106, '54345231', 'Evelyn', 'online', 1, 'POS2020030100107', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-24 06:18:30', 'confirmed', '2020-03-24 06:18:30', '0', '2020-03-24 06:18:48', 'card', '596418392724'),
(107, '54345231', 'Evelyn', 'online', 1, 'POS2020030100108', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -204.00, 204.00, 204.00, NULL, '2020-03-24 06:22:56', 'cancelled', '2020-03-24 06:22:56', '0', '2020-03-24 06:24:06', 'MoMo', '471316973954'),
(108, '54345231', 'Evelyn', 'online', 1, 'POS2020030100109', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -204.00, 204.00, 204.00, NULL, '2020-03-24 06:25:02', 'pending', '2020-03-24 06:25:02', '0', NULL, 'MoMo', '236988442961'),
(109, '54345231', 'Evelyn', 'online', 1, 'POS2020030100110', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -204.00, 204.00, 204.00, NULL, '2020-03-24 06:25:39', 'pending', '2020-03-24 06:25:39', '0', NULL, 'MoMo', '415682468973'),
(110, '54345231', 'Evelyn', 'online', 1, 'POS2020030100111', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -204.00, 204.00, 204.00, NULL, '2020-03-24 06:26:04', 'pending', '2020-03-24 06:26:04', '0', NULL, 'MoMo', '139741296547'),
(111, '54345231', 'Evelyn', 'online', 1, 'POS2020030100112', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -204.00, 204.00, 204.00, NULL, '2020-03-24 06:26:24', 'cancelled', '2020-03-24 06:26:24', '0', '2020-03-24 06:26:32', 'MoMo', '479867153412'),
(112, '54345231', 'Evelyn', 'online', 1, 'POS2020030100113', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 204.00, 204.00, NULL, '2020-03-24 06:26:43', 'confirmed', '2020-03-24 06:26:43', '0', NULL, 'cash', '967639352454'),
(113, '54345231', 'Evelyn', 'online', 1, 'POS2020030100114', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 4050.00, 4050.00, 4050.00, NULL, '2020-03-24 10:01:00', 'confirmed', '2020-03-24 10:01:00', '0', NULL, 'credit', '732496486523'),
(114, '54345231', 'Evelyn', 'online', 1, 'POS2020030100115', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-24 10:02:18', 'confirmed', '2020-03-24 10:02:18', '0', NULL, 'cash', '797645643251'),
(115, '54345231', 'Evelyn', 'online', 1, 'POS2020030100116', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1106.00, 1106.00, NULL, '2020-03-24 10:08:20', 'confirmed', '2020-03-24 10:08:20', '0', NULL, 'cash', '386441251759'),
(116, '54345231', 'Evelyn', 'online', 1, 'POS2020030100117', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 1050.00, 1050.00, NULL, '2020-03-25 11:06:40', 'confirmed', '2020-03-25 11:06:40', '0', NULL, 'cash', '334977461865'),
(117, '54345231', 'Evelyn', 'online', 1, 'POS2020030100118', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 1050.00, 1050.00, 1050.00, NULL, '2020-03-25 11:07:10', 'confirmed', '2020-03-25 11:07:10', '0', NULL, 'credit', '598118534672'),
(118, '54345231', 'Evelyn', 'online', 1, 'POS2020030100119', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 85.00, 0.00, 4250.00, 4165.00, NULL, '2020-03-25 11:33:08', 'confirmed', '2020-03-25 11:33:08', '0', NULL, 'cash', '859327814164'),
(119, '54345231', 'Evelyn', 'online', 1, 'POS2020030100120', 'EVC427416398569', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 8.00, 8.00, 8.00, NULL, '2020-03-25 11:33:35', 'confirmed', '2020-03-25 11:33:35', '0', NULL, 'credit', '691325843856'),
(120, '54345231', 'Evelyn', 'online', 1, 'POS2020030100121', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 3200.00, 3200.00, 3200.00, NULL, '2020-03-26 00:11:22', 'confirmed', '2020-03-26 00:11:22', '0', NULL, 'credit', '639423578195'),
(121, '54345231', 'Evelyn', 'online', 1, 'POS2020030100122', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 200.00, 0.00, 7850.00, 7650.00, NULL, '2020-03-26 08:40:41', 'confirmed', '2020-03-26 08:40:41', '0', NULL, 'cash', '284571136547'),
(122, '54345231', 'Evelyn', 'online', 1, 'POS2020030100123', 'EVC423589361967', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 20.00, 20.00, NULL, '2020-03-27 10:14:20', 'confirmed', '2020-03-27 10:14:20', '0', NULL, 'cash', '361254771436'),
(123, '54345231', 'Evelyn', 'online', 1, 'POS2020030100124', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 100.00, 3100.00, 3200.00, 3100.00, NULL, '2020-03-27 18:45:12', 'confirmed', '2020-03-27 18:45:12', '0', NULL, 'credit', '716268149537'),
(124, '54345231', 'Evelyn', 'online', 1, 'POS2020030100125', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 20.00, 1294.00, 1294.00, NULL, '2020-03-27 18:45:41', 'confirmed', '2020-03-27 18:45:41', '0', NULL, 'cash', '984516147329'),
(125, '54345231', 'Evelyn', 'online', 1, 'POS2020030100126', 'EVC218799756628', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 524.00, 0.00, 13100.00, 12576.00, NULL, '2020-03-27 18:46:15', 'confirmed', '2020-03-27 18:46:15', '0', NULL, 'cash', '182466853271'),
(126, '54345231', 'Evelyn', 'online', 2, 'POS2020030200127', 'WalkIn', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 0.00, 0.00, 2100.00, 2100.00, NULL, '2020-03-27 18:47:04', 'confirmed', '2020-03-27 18:47:04', '0', NULL, 'cash', '665791728431'),
(127, '54345231', 'Evelyn', 'online', 2, 'POS2020030200128', 'EVC4274146398569', NULL, 'customer', NULL, 'oDGirP31jNnXYK6dza20m', 'GH¢', '0', 312.00, 1824.00, 7800.00, 7488.00, NULL, '2020-03-27 18:47:31', 'confirmed', '2020-03-27 18:47:31', '0', NULL, 'cash', '362944873711'),
(128, '54345231', 'Evelyn', 'online', 1, 'POS2020030100129', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 30.00, 30.00, NULL, '2020-03-28 08:41:09', 'confirmed', '2020-03-28 08:41:09', '0', NULL, 'cash', '657593882972'),
(129, '54345231', 'Evelyn', 'online', 1, 'POS2020030100130', 'EVC465398287316', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 14.00, 14.00, NULL, '2020-03-28 17:09:10', 'confirmed', '2020-03-28 17:09:10', '0', NULL, 'cash', '519965712648'),
(130, '54345231', 'Evelyn', 'online', 1, 'POS2020030100131', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 404.00, 404.00, NULL, '2020-03-28 17:09:24', 'confirmed', '2020-03-28 17:09:24', '0', NULL, 'cash', '645268591338'),
(131, '54345231', 'Evelyn', 'online', 1, 'POS2020030100132', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 5100.00, 5100.00, 5100.00, NULL, '2020-03-28 17:11:42', 'confirmed', '2020-03-28 17:11:42', '0', NULL, 'credit', '471612993478'),
(132, '54345231', 'Evelyn', 'online', 1, 'POS2020030100133', 'EVC611798467452', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 208.00, 208.00, NULL, '2020-03-28 17:12:00', 'confirmed', '2020-03-28 17:12:00', '0', NULL, 'cash', '817619438327'),
(133, '54345231', 'Evelyn', 'online', 1, 'POS2020030100134', 'EVC951937465721', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 100.00, 0.00, 5300.00, 5200.00, NULL, '2020-03-28 18:36:57', 'confirmed', '2020-03-28 18:36:57', '0', NULL, 'cash', '574319512668'),
(134, '54345231', 'Evelyn', 'online', 1, 'POS2020030100135', 'EVC718437356928', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 2004.00, 2004.00, NULL, '2020-03-28 19:09:26', 'confirmed', '2020-03-28 19:09:26', '0', NULL, 'cash', '917556212886'),
(135, '54345231', 'Argon', 'online', 1, 'POS2020030100136', 'EVC532694487286', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 200.00, 200.00, NULL, '2020-03-30 01:04:40', 'confirmed', '2020-03-30 01:04:40', '0', NULL, 'cash', '691549365881'),
(136, '54345231', 'Argon', 'online', 1, 'POS2020030100137', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3200.00, 3200.00, NULL, '2020-03-30 01:07:53', 'confirmed', '2020-03-30 01:07:53', '0', NULL, 'cash', '819725635963'),
(137, '54345231', 'Argon', 'online', 1, 'POS2020030100138', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 120.00, 0.00, 5100.00, 4980.00, NULL, '2020-03-30 01:09:33', 'confirmed', '2020-03-30 01:09:33', '0', NULL, 'cash', '964455963727'),
(138, '54345231', 'Argon', 'online', 1, 'POS2020030100139', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 3406.00, 3406.00, NULL, '2020-03-30 01:11:37', 'confirmed', '2020-03-30 01:11:37', '0', NULL, 'cash', '837112684265'),
(139, '54345231', 'Argon', 'online', 1, 'POS2020030100140', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -5100.00, 5100.00, 5100.00, NULL, '2020-03-30 01:17:51', 'pending', '2020-03-30 01:17:51', '0', NULL, 'card', '425683163945'),
(140, '54345231', 'Argon', 'online', 1, 'POS2020030100141', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 5100.00, 5100.00, NULL, '2020-03-30 01:18:02', 'confirmed', '2020-03-30 01:18:02', '0', NULL, 'cash', '325766379291'),
(141, '54345231', 'Argon', 'online', 1, 'POS2020030100142', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 926.50, 46.50, 18530.00, 17603.50, NULL, '2020-03-30 19:02:40', 'confirmed', '2020-03-30 19:02:40', '0', NULL, 'cash', '784561369972'),
(142, '54345231', 'Argon', 'online', 1, 'POS2020030100143', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 0.00, 10.00, 10.00, 10.00, NULL, '2020-03-31 05:34:28', 'confirmed', '2020-03-31 05:34:28', '0', NULL, 'credit', '767894565824'),
(143, '54345231', 'Argon', 'online', 1, 'POS2020030100144', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 160.00, 0.00, 3200.00, 3040.00, NULL, '2020-03-31 07:14:01', 'confirmed', '2020-03-31 07:14:01', '0', NULL, 'cash', '819671793536'),
(144, '54345231', 'Argon', 'online', 1, 'POS2020030100145', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-31 23:11:41', 'pending', '2020-03-31 23:11:41', '0', NULL, 'MoMo', '615495423377'),
(145, '54345231', 'Argon', 'online', 1, 'POS2020030100146', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-03-31 23:12:35', 'pending', '2020-03-31 23:12:35', '0', NULL, 'MoMo', '786251593298'),
(146, '54345231', 'Argon', 'online', 1, 'POS2020030100147', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -3200.00, 3200.00, 3200.00, NULL, '2020-03-31 23:14:05', 'pending', '2020-03-31 23:14:05', '0', NULL, 'MoMo', '137582814967'),
(147, '54345231', 'Argon', 'online', 1, 'POS2020030100148', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, -3200.00, 3200.00, 3200.00, NULL, '2020-03-31 23:16:09', 'pending', '2020-03-31 23:16:09', '0', NULL, 'MoMo', '583156234724'),
(148, '54345231', 'Argon', 'online', 1, 'POS2020040100149', 'WalkIn', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 40.00, 850.00, 850.00, NULL, '2020-04-01 23:02:44', 'confirmed', '2020-04-01 23:02:44', '0', NULL, 'cash', '436528971271'),
(149, '54345231', 'Argon', 'online', 1, 'POS2020040100150', 'EVC792158792336', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 880.00, 880.00, NULL, '2020-04-01 23:03:23', 'confirmed', '2020-04-01 23:03:23', '0', NULL, 'cash', '135819592237'),
(150, '54345231', 'Argon', 'online', 1, 'POS2020040100151', 'EVC218799756628', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '1', 42.50, 807.50, 850.00, 807.50, NULL, '2020-04-01 23:03:45', 'confirmed', '2020-04-01 23:03:45', '0', NULL, 'credit', '621954375487'),
(151, '54345231', 'Argon', 'online', 1, 'POS2020040100152', 'EVC261795342893', NULL, 'customer', NULL, '2019DevAI', 'GH¢', '0', 0.00, 0.00, 30.00, 30.00, NULL, '2020-04-02 11:42:22', 'confirmed', '2020-04-02 11:42:22', '0', NULL, 'cash', '244125813786');

-- --------------------------------------------------------

--
-- Table structure for table `sales_details`
--

CREATE TABLE `sales_details` (
  `id` int(11) NOT NULL,
  `auto_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `order_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0',
  `product_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `product_cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `product_unit_price` double(13,2) DEFAULT 0.00,
  `product_total` double(13,2) DEFAULT 0.00,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_details`
--

INSERT INTO `sales_details` (`id`, `auto_id`, `clientId`, `branchId`, `order_id`, `product_id`, `product_quantity`, `product_cost_price`, `product_unit_price`, `product_total`, `order_date`) VALUES
(1, 'g1Oyk6KMm3qDNcBwEFaxQeGTA', '54345231', 2, 'INV2020030002', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:44:32'),
(4, '4zROu270PK1e6Agp9MnwBkiFQ', '54345231', 2, 'INV2020030003', '24', 1, '1500.00', 1900.00, 1900.00, '2020-03-18 07:44:50'),
(5, 'o8be1CmPD5gik2jLu4zBwAVxU', '54345231', 2, 'INV2020030003', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:44:50'),
(6, 'qcDiBY9JSuUk3Nfb0MweEs7FV', '54345231', 2, 'INV2020030003', '26', 1, '600.00', 850.00, 850.00, '2020-03-18 07:44:50'),
(7, 'P6EJpD2nyhvtN4b7WUamFGoAg', '54345231', 2, 'INV2020030003', '27', 1, '70.00', 200.00, 200.00, '2020-03-18 07:44:50'),
(8, '3dZwmzXKjItUi640WEBA21Nlf', '54345231', 2, 'INV2020030004', '24', 1, '1500.00', 1900.00, 1900.00, '2020-03-18 07:45:32'),
(9, 'jY86RdpxPnJFgCUabQHrS0LXN', '54345231', 2, 'INV2020030004', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:45:32'),
(10, 'Rf8vs3cQMne195BhG2Hb6LXZA', '54345231', 2, 'INV2020030004', '26', 1, '600.00', 850.00, 850.00, '2020-03-18 07:45:32'),
(11, 'OrPUZgdaH7kR38tEuTFwySG2M', '54345231', 2, 'INV2020030004', '27', 1, '70.00', 200.00, 200.00, '2020-03-18 07:45:32'),
(12, 'jLOoRs71dGPQ4yWun0aHmYheC', '54345231', 2, 'INV2020030005', '24', 3, '1500.00', 1900.00, 5700.00, '2020-03-18 07:46:24'),
(13, 'mrVflsn7dHCKLkAMSUDGu0FY3', '54345231', 2, 'INV2020030005', '25', 3, '2000.00', 3200.00, 9600.00, '2020-03-18 07:46:24'),
(14, 'wAXva4ysIUJ2tOYDfMFz6h03o', '54345231', 1, 'INV2020030006', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:47:18'),
(15, '5UmiXwV6pzFcjnABJG0lktb27', '54345231', 1, 'INV2020030006', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 07:47:18'),
(16, 'n6cTzDfMI24ijyhQgYpv3dwtN', '54345231', 1, 'INV2020030006', '4', 1, '70.00', 200.00, 200.00, '2020-03-18 07:47:18'),
(17, 'DfiTXBPywFmvO9nASxzHhL0eV', '54345231', 1, 'INV2020030007', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:47:44'),
(18, 'cAigIX8rl67tbkm94aWMD2OCe', '54345231', 1, 'INV2020030007', '3', 2, '600.00', 850.00, 1700.00, '2020-03-18 07:47:44'),
(19, 'C10qWfEiJrNQHvkTZzKg9VG6e', '54345231', 1, 'INV2020030008', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-17 07:48:46'),
(20, 'uckZAKerNQ5fRxj4zXB3ahT08', '54345231', 1, 'INV2020030008', '3', 1, '600.00', 850.00, 850.00, '2020-03-17 07:48:46'),
(21, 'gQZBcTWLemJr7h1OUzAwuknbR', '54345231', 1, 'INV2020030009', '4', 1, '70.00', 200.00, 200.00, '2020-03-17 07:48:59'),
(22, 'dkoT1W8rPnYOZxhS5w2yQGbEf', '54345231', 1, 'INV2020030010', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-17 07:49:19'),
(23, '1xMDNsf783a4niehTKcyguQz6', '54345231', 1, 'INV2020030010', '3', 2, '600.00', 850.00, 1700.00, '2020-03-17 07:49:19'),
(24, 'BwXTlGhS2e3u0qnFkDYKPzEUR', '54345231', 1, 'INV2020030010', '4', 1, '70.00', 200.00, 200.00, '2020-03-17 07:49:19'),
(25, 'nAzVlkHKm0pNCgUWiuYXROEIT', '54345231', 2, 'INV2020030011', '24', 1, '1500.00', 1900.00, 1900.00, '2020-03-17 07:49:56'),
(26, 'tSzXIW41nPBKLvJa6MwGyx3Er', '54345231', 2, 'INV2020030011', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-17 07:49:56'),
(27, 'q8sAfVLxYRwlJSBmaQzFEidKe', '54345231', 2, 'INV2020030012', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-17 07:51:57'),
(28, 'd3MVgbEDmJwsxFr8ABocaHtZv', '54345231', 2, 'INV2020030012', '26', 1, '600.00', 850.00, 850.00, '2020-03-17 07:51:57'),
(29, 'hwAPGMIK8vYONnxtHleEpobuj', '54345231', 2, 'INV2020030012', '27', 1, '70.00', 200.00, 200.00, '2020-03-17 07:51:57'),
(30, 'OcBrgYQJ1KuV8sdUlDnS32LeI', '54345231', 1, 'INV2020030013', '4', 1, '70.00', 200.00, 200.00, '2020-03-17 07:53:32'),
(31, 'kpvW7qNluSAr0gFtJd6DK3Y4G', '54345231', 1, 'INV2020030013', '28', 2, '120.00', 200.00, 400.00, '2020-03-17 07:53:32'),
(32, '69eUFmfB0g5WJGLt2QMh7Dyso', '54345231', 1, 'INV2020030014', '3', 1, '600.00', 850.00, 850.00, '2020-03-17 07:53:46'),
(33, 'kFKjqY7cJ4IUiN1WmzOEBntux', '54345231', 1, 'INV2020030014', '4', 1, '70.00', 200.00, 200.00, '2020-03-17 07:53:46'),
(34, 'FYO5Qi2GlmHNyAuw8fVacPJh1', '54345231', 1, 'INV2020030014', '28', 1, '120.00', 200.00, 200.00, '2020-03-17 07:53:46'),
(35, 'TAQG6sJSiqlKEetZ94WRNjfxg', '54345231', 1, 'INV2020030015', '3', 1, '600.00', 850.00, 850.00, '2020-03-17 07:53:59'),
(36, '9WsCBrMPdnNF2Vj4z1ZUAkfiX', '54345231', 1, 'INV2020030015', '28', 1, '120.00', 200.00, 200.00, '2020-03-17 07:53:59'),
(37, 'd5RaVOTYS0zNm9gXUAJwK3fDF', '54345231', 1, 'INV2020030016', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-18 07:56:43'),
(38, 'kbRio5QKWcNOF6rXztG39plPq', '54345231', 1, 'INV2020030016', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 07:56:43'),
(39, 'NJjleSLuVwAYb6gzyQD7OaT8i', '54345231', 1, 'INV2020030016', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 07:56:43'),
(40, 'HgfU4sZeQ0I6vdX81umb5LElt', '54345231', 1, 'INV2020030016', '4', 1, '70.00', 200.00, 200.00, '2020-03-18 07:56:43'),
(41, '2amkfLpzcxw4nCjZiMBIAPKrG', '54345231', 1, 'INV2020030016', '28', 1, '120.00', 200.00, 200.00, '2020-03-18 07:56:43'),
(42, 'f1wqI5WBDblxGJ4TR6s0PFrLO', '54345231', 1, 'INV2020030018', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 10:09:33'),
(43, 'Pwp8j3bcFQyHmO2V7tN1d5xBJ', '54345231', 1, 'INV2020030018', '4', 1, '70.00', 200.00, 200.00, '2020-03-18 10:09:33'),
(44, 'UzoqEnA0j4WagFmklxOQt25w9', '54345231', 1, 'INV202003_1_0019', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 15:28:51'),
(45, 'q7xkF3ZPW5htHij6R8AeEYoQT', '54345231', 1, 'INV202003_1_0019', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 15:28:51'),
(46, 'lJsiGIEHRvNZurwzTbPYpU358', '54345231', 1, 'INV202003_01_0020', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-18 15:30:43'),
(47, 'va8QtLFWPkI74M2UfHT5Z1bS6', '54345231', 1, 'INV202003_01_0020', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 15:30:43'),
(48, '53MTQl9AaRL4JbPXpSKYBqUoO', '54345231', 1, 'INV202003_01_0020', '4', 1, '70.00', 200.00, 200.00, '2020-03-18 15:30:43'),
(49, '2AYXRBMOlg7FyhmbKzDPTaUd4', '54345231', 1, 'INV202003_01_0021', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-18 16:12:52'),
(50, 'vpMZHKsNbP2ygnDaLYAk4QuS8', '54345231', 1, 'INV202003_01_0021', '2', 3, '2000.00', 3200.00, 9600.00, '2020-03-18 16:12:52'),
(51, 'klZ7OAspWH8f6txiFvJbM1oGN', '54345231', 1, 'INV202003_01_0021', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 16:12:52'),
(52, 'mJdH1jcEDT0IWhPQsVetr8xNn', '54345231', 1, 'INV202003_01_0021', '4', 3, '70.00', 200.00, 600.00, '2020-03-18 16:12:52'),
(53, 'ig5YWyZ4s2defwI7XU6H9Savk', '54345231', 1, 'INV202003_01_0021', '28', 3, '120.00', 200.00, 600.00, '2020-03-18 16:12:52'),
(54, '4LuAJCBMnVN3ZqhwvgfeEYaK1', '54345231', 1, 'INV202003_01_0022', '3', 1, '600.00', 850.00, 850.00, '2020-03-18 20:23:57'),
(55, 'XIwNyW7f19LzoZs8M2QpGHd30', '54345231', 1, 'INV202003_01_0022', '4', 1, '70.00', 200.00, 200.00, '2020-03-18 20:23:57'),
(56, 'AUDLNEjo9CSQFKZYpBJPn3MXW', '54345231', 1, 'INV202003_01_0022', '28', 1, '120.00', 200.00, 200.00, '2020-03-18 20:23:57'),
(57, 'oncl1Di6hmxbROp3yL2AXusCE', '54345231', 1, 'INV202003_01_0023', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 04:34:22'),
(58, 'IwaFPtWzUfT4Y7h1HbLJoNy6v', '54345231', 1, 'INV202003_01_0023', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 04:34:22'),
(59, 'SHFzmZo0W1KLT2A79VxPDQJNs', '54345231', 1, 'INV202003_01_0023', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 04:34:22'),
(60, 'hDtQZCJxyTvgjB3H4kNpf1X6U', '54345231', 1, 'INV202003_01_0024', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 04:34:37'),
(61, 'bErA5L2li4vWKm0zXoSHpMwyN', '54345231', 1, 'INV202003_01_0025', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 04:34:58'),
(62, 'SAIJOsNRo6UEPlWxgMvH94b2k', '54345231', 1, 'INV202003_01_0025', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 04:34:58'),
(63, 'Cr935fUa2euHymq0vGTLiKknW', '54345231', 1, 'INV202003_01_0025', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 04:34:58'),
(64, 'Y4n17uSEdmPw6D3JLtpvZrOjh', '54345231', 1, 'INV202003_01_0026', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-19 04:35:37'),
(65, 'S3ZU4w5lvoCBX6VLpJsMEc8iN', '54345231', 1, 'INV202003_01_0026', '2', 3, '2000.00', 3200.00, 9600.00, '2020-03-19 04:35:37'),
(66, 'saxtogdJeIO1iWc5SFvK3rpVX', '54345231', 1, 'INV202003_01_0026', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 04:35:37'),
(67, 'qmiu95oa4xPDGSw7AJUbzYKH1', '54345231', 1, 'INV202003_01_0026', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 04:35:37'),
(68, 'WZiERLnVquJAaBhlyCIKd1NmU', '54345231', 1, 'INV202003_01_0026', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 04:35:37'),
(69, 'EoSAWxemB58Q7wkUrnhJ9cvp2', '54345231', 1, 'INV202003_01_0027', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 05:04:25'),
(70, 'MXkb8cqngrDjzpLd3HSABes1w', '54345231', 1, 'INV202003_01_0028', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 05:08:37'),
(71, 'wJ1txQXzEyprMga5Gdkv8oTWP', '54345231', 1, 'INV202003_01_0029', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 06:18:21'),
(72, 'kYfc1WZpx8usiIzAe5ol4TtUM', '54345231', 1, 'INV202003_01_0029', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 06:18:21'),
(73, 'DtVuS7dnQcIjmHxAXBOaLviJN', '54345231', 1, 'INV202003_01_00030', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-19 11:08:44'),
(74, 'ft5HuSvyQLC7zcp8rFmU20WZG', '54345231', 1, 'INV202003_01_00030', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-19 11:08:44'),
(75, 'wV2DGI1vCtjS8zRoQA5JFqrul', '54345231', 1, 'INV202003_01_00030', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 11:08:44'),
(76, 'IanJPWivY2Mpq8hNSQsVc30tX', '54345231', 1, 'INV202003_01_00030', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 11:08:44'),
(77, 'A18NXoacQDEm7R9v3Ywq6iFUs', '54345231', 1, 'INV202003_01_00030', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 11:08:44'),
(78, '1z4dBFYEJGCOkrHQ9ZWc0mtN8', '54345231', 1, 'INV202003_01_00031', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-19 14:36:46'),
(79, 'R3NeSLgjZdXV5Qxnptk1yYF87', '54345231', 1, 'INV202003_01_00031', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 14:36:46'),
(80, 'iMCFh92ameQUxwY8Js6WSGKIg', '54345231', 1, 'INV202003_01_00031', '4', 3, '70.00', 200.00, 600.00, '2020-03-19 14:36:46'),
(81, 'seEmXL6T3Pkhin2cq8YOdxCgo', '54345231', 1, 'INV202003_01_00032', '3', 3, '600.00', 850.00, 2550.00, '2020-03-19 14:37:24'),
(82, 'XdrQJhvLoeqkmV8AE1ZpW0wau', '54345231', 1, 'INV202003_01_00032', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 14:37:24'),
(83, 'xdqXzpLun8B4FeMc1HGj7IfWV', '54345231', 1, 'INV202003_01_00032', '28', 1, '120.00', 200.00, 200.00, '2020-03-19 14:37:24'),
(84, 'HqAxVQXLcmnroYGBal8Wt9Ujv', '54345231', 1, 'INV202003_01_00033', '3', 1, '600.00', 850.00, 850.00, '2020-03-19 14:39:20'),
(85, 'GJ1Yi46RMZvq0wexnh7AyHa3L', '54345231', 1, 'INV202003_01_00033', '4', 1, '70.00', 200.00, 200.00, '2020-03-19 14:39:20'),
(86, 'fdJ2S3KpHm7GgxXyWDolLtjFB', '54345231', 1, 'INV2020030100034', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 07:14:42'),
(87, 'VAfySKX0ohrtI52DG43qxl8Fp', '54345231', 1, 'INV2020030100035', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 07:19:17'),
(88, 'l9pH5PDzZdsfvwT2USWOkxy8N', '54345231', 1, 'INV2020030100035', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 07:19:17'),
(89, 'Cqt01vMPp8WXIfOlkn4Q2a3SA', '54345231', 1, 'INV2020030100036', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 07:19:46'),
(90, 'voArH1DW5PCGsRqtw3ONkZfT7', '54345231', 1, 'INV2020030100036', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 07:19:46'),
(91, 'SGMCUhl5Q2cnAPWD9m6dxEsaR', '54345231', 1, 'INV2020030100036', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 07:19:46'),
(92, 'meY7H0514xyKDTNszEXlvokiq', '54345231', 1, 'INV2020030100037', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 07:35:04'),
(93, 'eHOpCauzdkcWVJl71b0wIK5Yh', '54345231', 1, 'INV2020030100037', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 07:35:04'),
(94, 'bYlLHaxVSonyJw2KEv1XfW0mI', '54345231', 2, 'INV2020030200038', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 07:35:42'),
(95, 'vmD4pGfeEoqyNRUPSctxuCnXd', '54345231', 2, 'INV2020030200038', '26', 1, '600.00', 850.00, 850.00, '2020-03-20 07:35:42'),
(96, '1OKnds5vRyN0j8teupbLwf2gQ', '54345231', 2, 'INV2020030200038', '27', 1, '70.00', 200.00, 200.00, '2020-03-20 07:35:42'),
(97, '768prcqQXhyEVFkzmWAD2MxTZ', '54345231', 2, 'INV2020030200039', '26', 1, '600.00', 850.00, 850.00, '2020-03-20 07:36:00'),
(98, '0CTYl8wWMVuoe9SDc7U3RXfEy', '54345231', 2, 'INV2020030200039', '27', 1, '70.00', 200.00, 200.00, '2020-03-20 07:36:00'),
(99, 'OlDnAUIuj3G6n7P7JTt5', '54345231', 1, 'INV5765739303628', '28', 1, '0.00', 200.00, 200.00, '2020-03-20 08:11:45'),
(100, 'b2BTNQTbemZFCRy7G6Db', '54345231', 1, 'INV5765739303628', '3', 1, '0.00', 850.00, 850.00, '2020-03-20 08:11:45'),
(101, '83pmF8CsMvSFPgOqH5gl', '54345231', 1, 'INV5765739303628', '4', 1, '0.00', 200.00, 200.00, '2020-03-20 08:11:45'),
(102, '2VHnQlm6IiElrfBnGdfq', '54345231', 1, 'INV5765739303628', '2', 1, '0.00', 3200.00, 3200.00, '2020-03-20 08:11:45'),
(103, 'qwqUtQ1G6sas44wrApJ6', '54345231', 1, 'INV5869421973848', '1', 1, '0.00', 1900.00, 1900.00, '2020-03-20 08:11:01'),
(104, '6vIe9dxn3wLlgkvKvJIr', '54345231', 1, 'INV5869421973848', '2', 1, '0.00', 3200.00, 3200.00, '2020-03-20 08:11:01'),
(105, 'tU7tDOsrqupAml3QtyyR', '54345231', 1, 'INV5869421973848', '4', 1, '0.00', 200.00, 200.00, '2020-03-20 08:11:01'),
(106, 'wpP8EtWEWDJOWPX49T3R', '54345231', 1, 'INV5869421973848', '3', 1, '0.00', 850.00, 850.00, '2020-03-20 08:11:01'),
(107, 'WhyQyO713uDSndnYX37J', '54345231', 1, 'INV5869421973848', '28', 1, '0.00', 200.00, 200.00, '2020-03-20 08:11:01'),
(108, 'kP6HhnqGXrCV2D8fbjjq', '54345231', 1, 'INV9737257398367', '28', 1, '0.00', 200.00, 200.00, '2020-03-20 08:10:19'),
(109, '7FfSZXT6ZraEtw2HlPXz', '54345231', 1, 'INV9737257398367', '2', 1, '0.00', 3200.00, 3200.00, '2020-03-20 08:10:19'),
(110, '7hOqXm3eHwEFLMncbpyuPVT2R', '54345231', 1, 'INV2020030100043', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 11:53:08'),
(111, 'I0vMUdP5n6uAkWRoGmJYBisT3', '54345231', 1, 'INV2020030100043', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 11:53:08'),
(112, 'bF7zB8r1Gh4Kdx2E3XiCD5SeO', '54345231', 1, 'INV2020030100043', '4', 3, '70.00', 200.00, 600.00, '2020-03-20 11:53:08'),
(113, 'iwBnCu7gW3L6U9H5qbMAa8Jkc', '54345231', 1, 'INV2020030100043', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 11:53:08'),
(114, 'IzxfTUDP6Z1dXiHWtaVbMFLp0', '54345231', 1, 'INV2020030100044', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 11:54:10'),
(115, 'siymqwU4X2IfxFEzMSh76Jb1V', '54345231', 1, 'INV2020030100044', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 11:54:10'),
(116, 's0woAD6VNYIiGKmUdbzc1qCZp', '54345231', 2, 'INV2020030200045', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 14:12:16'),
(117, 'lr7aYZ5t0CuA6zvG3owbJQhVS', '54345231', 2, 'INV2020030200045', '26', 1, '600.00', 850.00, 850.00, '2020-03-20 14:12:16'),
(118, 'EHPuRo5TOwY9rfKjNz3lhCxm4', '54345231', 1, 'INV2020030100046', '1', 5, '1500.00', 1900.00, 9500.00, '2020-03-20 14:29:02'),
(119, 'rY9LBZKHlATWpRC1P5i8EQ7Xf', '54345231', 1, 'INV2020030100046', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 14:29:02'),
(120, 'BjFkplfozugLiAYZtK0wMNObm', '54345231', 1, 'INV2020030100046', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 14:29:02'),
(121, 'bUcfgYTaMlF8GexDQStsLumPB', '54345231', 1, 'INV2020030100046', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 14:29:02'),
(122, 'LkOm3PhvcsZfY7THi1x4nUpCG', '54345231', 1, 'INV2020030100047', '1', 3, '1500.00', 1900.00, 5700.00, '2020-03-20 19:39:25'),
(123, 'pwuMzWHkhb70DONGn9oL6qB8Z', '54345231', 1, 'INV2020030100047', '2', 2, '2000.00', 3200.00, 6400.00, '2020-03-20 19:39:25'),
(124, 'YLuIWxZQ3kw15dUEiD28o4hRr', '54345231', 1, 'INV2020030100047', '3', 3, '600.00', 850.00, 2550.00, '2020-03-20 19:39:25'),
(125, 'VXL9rIn5UbGWjdKYE1xJz4kpF', '54345231', 1, 'INV2020030100047', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 19:39:25'),
(126, 'm57oWVfdHbZGezj6NAhiLTYMR', '54345231', 1, 'INV2020030100047', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 19:39:25'),
(127, 'mz5Ck4Qgql7I6FEbO2NRd9H1T', '54345231', 1, 'INV2020030100048', '3', 5, '600.00', 850.00, 4250.00, '2020-03-20 19:39:54'),
(128, 'weTqdfHSIAhJNu7VyQML4RK2F', '54345231', 1, 'INV2020030100048', '4', 4, '70.00', 200.00, 800.00, '2020-03-20 19:39:54'),
(129, 'z7D8k6XnH5iKShvVOqYjC4umZ', '54345231', 1, 'INV2020030100049', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 20:05:08'),
(130, 'Jd9FPpOiXUgDRq3ry6zxA7sfC', '54345231', 1, 'INV2020030100049', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 20:05:08'),
(131, 'xfNSwgGZvFoAj62eyaU0rEHCh', '54345231', 1, 'INV2020030100049', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:05:08'),
(132, 't9QpgiubIGEzCnoAkH6Z0fsDv', '54345231', 1, 'INV2020030100050', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 20:05:23'),
(133, 'BA4y925lNz7PHGEYm63TaD1Qv', '54345231', 1, 'INV2020030100050', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:05:23'),
(134, '1XfBlLYCcRqpuMxPsrZoDtdyW', '54345231', 1, 'INV2020030100050', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:05:23'),
(135, 'GqICJhHvFf43UXTBsgxw5Npkd', '54345231', 1, 'INV2020030100051', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 20:05:56'),
(136, 'rLyJAfvN60TDZ5kiXP2RQVIoj', '54345231', 1, 'INV2020030100051', '2', 3, '2000.00', 3200.00, 9600.00, '2020-03-20 20:05:56'),
(137, 'qUCb0EHNXpL2tAncw8I9a3iOv', '54345231', 1, 'INV2020030100051', '3', 4, '600.00', 850.00, 3400.00, '2020-03-20 20:05:56'),
(138, 'BCbxvWJhgGy8HFMnU5lKSPRm6', '54345231', 1, 'INV2020030100051', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:05:56'),
(139, '5FvPlaXzxITQRpcgnA8jNHq3C', '54345231', 1, 'INV2020030100051', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:05:56'),
(140, 'FkqNowc1feSH8AQylzdDi206a', '54345231', 1, 'INV2020030100052', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 20:07:31'),
(141, 'oqcEVkLzwKeQuYRdhmxr0aD71', '54345231', 1, 'INV2020030100052', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:07:31'),
(142, '5x4GBAsYfXPuEKQejvUpMTi3V', '54345231', 1, 'INV2020030100052', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:07:31'),
(143, '2JklD3WatBZQTgIXhOCHEY54u', '54345231', 1, 'INV2020030100053', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 20:07:54'),
(144, 'CvGFu1UdAVLEM87h2RljBToXi', '54345231', 1, 'INV2020030100053', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 20:07:54'),
(145, '4AxmRgwtQI7ykUVef38NKJduH', '54345231', 1, 'INV2020030100054', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 20:08:08'),
(146, '4lGJU8PEh670NMCwk3iQumFIx', '54345231', 1, 'INV2020030100054', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:08:08'),
(147, 'QNOJXvnrRghlMzUf3iEjtwp7d', '54345231', 1, 'INV2020030100054', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:08:08'),
(148, 'L9o8vlm6aXqIj1MQhYifZbOEw', '54345231', 1, 'INV2020030100055', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 20:08:33'),
(149, 'GyNJBLxKVvYAsUOChewigknXI', '54345231', 1, 'INV2020030100055', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 20:08:33'),
(150, 'Qs8EkJ2a5wByY0eoWIAFcp6xM', '54345231', 1, 'INV2020030100055', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 20:08:33'),
(151, 'd4jkiU3YBQeXtsJNaKLA9EOvh', '54345231', 1, 'INV2020030100055', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:08:33'),
(152, 'jg4yRJsmMCoX6D9nhV02HZxvq', '54345231', 1, 'INV2020030100056', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 20:15:12'),
(153, 'r1bcKMCwimdBfh0YoPnusqaUX', '54345231', 1, 'INV2020030100056', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 20:15:12'),
(154, 'l2Bqpkx4vSUbjCVHZeJot3nQw', '54345231', 1, 'INV2020030100056', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 20:15:12'),
(155, 'PNCs2biMY93Q1Epx7gTkUelhn', '54345231', 1, 'INV2020030100056', '4', 1, '70.00', 200.00, 200.00, '2020-03-20 20:15:12'),
(156, 'PSaQMGiecI5doFgk6NAz4ERLu', '54345231', 1, 'INV2020030100056', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 20:15:12'),
(157, 'Z5vKOye3sQzkg4DBFitMh0JpU', '54345231', 1, 'INV2020030100057', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-20 21:40:21'),
(158, 'nsax5vU6A8IGfPgklEOe1VJFW', '54345231', 1, 'INV2020030100057', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-20 21:40:21'),
(159, '3TCBERUrv0lt627V4DFoWjcqL', '54345231', 1, 'INV2020030100057', '3', 1, '600.00', 850.00, 850.00, '2020-03-20 21:40:21'),
(160, 'vOQ6FyM1CS9aKpPiH4bLReDow', '54345231', 1, 'INV2020030100057', '28', 1, '120.00', 200.00, 200.00, '2020-03-20 21:40:21'),
(161, 'Dmg5OajzVIKoQJ6tfWihrHTwS', '54345231', 1, 'INV2020030100058', '28', 1, '120.00', 200.00, 200.00, '2020-03-21 07:32:50'),
(162, 'U483cNlgtk7nqHTarCXhjmVM2', '54345231', 1, 'INV2020030100059', '4', 3, '70.00', 200.00, 600.00, '2020-03-21 07:33:18'),
(163, 'iLcns3etvgj7XSpMKUZRQq02b', '54345231', 1, 'INV2020030100060', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-22 23:06:45'),
(164, 'mQYK79NfaAwi4uzJ3bd0WBoO8', '54345231', 1, 'INV2020030100060', '28', 1, '120.00', 200.00, 200.00, '2020-03-22 23:06:45'),
(165, 'KRDqaBmF1s7TYIHwEx4GZXUf5', '54345231', 1, 'INV2020030100061', '3', 1, '600.00', 850.00, 850.00, '2020-03-22 23:07:03'),
(166, '52AZePGvx7kgwMmjRzhuyJrCq', '54345231', 1, 'INV2020030100061', '28', 1, '120.00', 200.00, 200.00, '2020-03-22 23:07:03'),
(167, 'OhfHY8coPzQvN57uLtsmbV0nD', '54345231', 1, 'INV2020030100062', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-22 23:07:47'),
(168, 'qmys9pXwCde0LQbrBg8hAtT1P', '54345231', 1, 'INV2020030100062', '3', 3, '600.00', 850.00, 2550.00, '2020-03-22 23:07:47'),
(169, '6EuCrIo4zsVc5NX9PWeqfZKkJ', '54345231', 1, 'INV2020030100062', '28', 3, '120.00', 200.00, 600.00, '2020-03-22 23:07:47'),
(170, 'cHsMLjSrnk6bVQ2uRfJxFiTpe', '54345231', 1, 'INV2020030100063', '1', 5, '1500.00', 1900.00, 9500.00, '2020-03-22 23:08:20'),
(171, 'iNpQV5luOJq7w01UBgLI2DRrm', '54345231', 1, 'INV2020030100063', '2', 6, '2000.00', 3200.00, 19200.00, '2020-03-22 23:08:20'),
(172, 'CFpMLIlu1iwahjVq7dcsO4X8k', '54345231', 1, 'INV2020030100063', '3', 7, '600.00', 850.00, 5950.00, '2020-03-22 23:08:20'),
(173, 'Bp1gNTx8mzXk59cWtnOoCGF7M', '54345231', 2, 'INV2020030200064', '25', 1, '2000.00', 3200.00, 3200.00, '2020-03-22 23:09:46'),
(174, 'YXU6oNOiHAjrwW57CpV4cThgb', '54345231', 2, 'INV2020030200064', '26', 1, '600.00', 850.00, 850.00, '2020-03-22 23:09:46'),
(175, 'e5yXnzOvH8jEGYNlDWqxAp3ft', '54345231', 2, 'INV2020030200064', '27', 1, '70.00', 200.00, 200.00, '2020-03-22 23:09:46'),
(176, 'i0F5SMkHhuWzVgjY7cOEvbGJZ', '54345231', 2, 'INV2020030200065', '26', 5, '600.00', 850.00, 4250.00, '2020-03-22 23:10:04'),
(177, '9yFLCUSaefQpXdGi4qH75R6Vl', '54345231', 2, 'INV2020030200065', '27', 1, '70.00', 200.00, 200.00, '2020-03-22 23:10:04'),
(178, 'tEmFX6lIKGkfQ73Cj5HnhDxsz', '54345231', 2, 'INV2020030200066', '24', 4, '1500.00', 1900.00, 7600.00, '2020-03-22 23:10:37'),
(179, 'WINJjitEoUwHKBrXdOvp1qm7n', '54345231', 2, 'INV2020030200066', '25', 4, '2000.00', 3200.00, 12800.00, '2020-03-22 23:10:37'),
(180, 'iAkaXJHeFU2NPBsh307YIWcgw', '54345231', 2, 'INV2020030200066', '26', 2, '600.00', 850.00, 1700.00, '2020-03-22 23:10:37'),
(181, 'OLvTUSJ8DkYqiBsQpmtRgXj35', '54345231', 2, 'INV2020030200066', '27', 1, '70.00', 200.00, 200.00, '2020-03-22 23:10:37'),
(182, 'UxKd0Fu9hr2DcplaeJXVEbyIw', '54345231', 1, 'INV2020030100067', '3', 1, '600.00', 850.00, 850.00, '2020-03-13 23:40:48'),
(183, 'y0PESMLbZkRXf8Wndwrapctmi', '54345231', 1, 'INV2020030100067', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:40:48'),
(184, 'IEQGP0T2mLfd9bp47ZYzeCgWh', '54345231', 1, 'INV2020030100067', '28', 1, '120.00', 200.00, 200.00, '2020-03-13 23:40:48'),
(185, 'YkTz80JNEeLwBWCno2IjiqVO3', '54345231', 1, 'INV2020030100068', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:41:00'),
(186, 'SbMs4GnNoxLXtBdZUikzcRwpC', '54345231', 1, 'INV2020030100069', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-13 23:41:30'),
(187, 'X0Y49zDt7QKpmJ6i25wUjObcF', '54345231', 1, 'INV2020030100069', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:41:30'),
(188, 'ZUvym3Pf1dLoc7JN2AbIXSHpi', '54345231', 1, 'INV2020030100069', '28', 1, '120.00', 200.00, 200.00, '2020-03-13 23:41:30'),
(189, 'GImqTwlJunvA1KLzFQU89j6Ht', '54345231', 1, 'INV2020030100070', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-13 23:41:54'),
(190, 'sTpjbr5gdqR6XfIHWkoNwctJK', '54345231', 1, 'INV2020030100070', '3', 1, '600.00', 850.00, 850.00, '2020-03-13 23:41:54'),
(191, 'o0yqZ7rzeFJGmI3jT6t95pnQh', '54345231', 1, 'INV2020030100070', '28', 1, '120.00', 200.00, 200.00, '2020-03-13 23:41:54'),
(192, 'yl3R7xw59gp6ePNHOtATYcWQf', '54345231', 1, 'INV2020030100071', '3', 1, '600.00', 850.00, 850.00, '2020-03-13 23:42:21'),
(193, 'EVORInTGoBU12r3FcMLjdfq6b', '54345231', 1, 'INV2020030100071', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:42:21'),
(194, 'e45Q0DCuL681gFHsSJhcTqRtY', '54345231', 1, 'INV2020030100072', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-13 23:42:38'),
(195, 'uwe6tBMmRpcGoV1j8aNI5bPxi', '54345231', 1, 'INV2020030100072', '3', 1, '600.00', 850.00, 850.00, '2020-03-13 23:42:38'),
(196, '9Kr3dvhX7FsCSNymeWQLgA4i5', '54345231', 1, 'INV2020030100072', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:42:38'),
(197, '15TOcn9zLDxpFPuRM6rGN4VYq', '54345231', 1, 'INV2020030100072', '28', 1, '120.00', 200.00, 200.00, '2020-03-13 23:42:38'),
(198, 's6AkbyPKI24wuam90W1TRNVlf', '54345231', 1, 'INV2020030100073', '4', 1, '70.00', 200.00, 200.00, '2020-03-13 23:42:50'),
(199, 'DMWwE82hj0KpLCG75QxHuO3zR', '54345231', 1, 'INV2020030100073', '28', 1, '120.00', 200.00, 200.00, '2020-03-13 23:42:50'),
(200, 'lydEViKqbC58xhZL71OogkuvB', '54345231', 1, 'INV2020030100074', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-15 23:43:18'),
(201, 'vhBciGNTfsodyA9zkm3uw1aDU', '54345231', 1, 'INV2020030100074', '3', 1, '600.00', 850.00, 850.00, '2020-03-15 23:43:18'),
(202, 'Mc4jdeXUFPZfI1H5haT3Wtn87', '54345231', 1, 'INV2020030100075', '1', 3, '1500.00', 1900.00, 5700.00, '2020-03-15 23:44:32'),
(203, 'UV8lITJZjDGLvOuHa92o1AibR', '54345231', 1, 'INV2020030100075', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-15 23:44:32'),
(204, 'ByXFwR5mluIHQ2oVxnhkiqZ9c', '54345231', 1, 'INV2020030100075', '3', 2, '600.00', 850.00, 1700.00, '2020-03-15 23:44:32'),
(205, 'GaFqZDpMEXkoxnBb3ecuLmjlw', '54345231', 1, 'INV2020030100075', '4', 1, '70.00', 200.00, 200.00, '2020-03-15 23:44:32'),
(206, '8UvQVmrNg2YoAXS40ku7RJKDI', '54345231', 1, 'INV2020030100076', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-15 23:44:52'),
(207, 'DWhO4mAXn8ebHuBYkCTJ6qI0U', '54345231', 1, 'INV2020030100076', '3', 1, '600.00', 850.00, 850.00, '2020-03-15 23:44:52'),
(208, 'KP4CQwrZOkSn2Go8uFzsdLyxl', '54345231', 1, 'INV2020030100076', '4', 1, '70.00', 200.00, 200.00, '2020-03-15 23:44:52'),
(209, 'n0JmIMy9qcDzoew6Y4OLhWjEK', '54345231', 1, 'INV2020030100076', '28', 1, '120.00', 200.00, 200.00, '2020-03-15 23:44:52'),
(210, 'eONysib2vWFHD96VrRCozaQxK', '54345231', 1, 'INV2020030100077', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-23 00:03:45'),
(211, 'PWv3MsKFi71HgdIVznYcfwj2A', '54345231', 1, 'INV2020030100077', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-23 00:03:45'),
(212, 'kZhP3VRxie1nNT0pjaAIcUm5S', '54345231', 1, 'INV2020030100078', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-23 00:04:37'),
(213, 'V6KrcXtRFYD8Id7AH9E0SW2Q4', '54345231', 1, 'INV2020030100078', '2', 5, '2000.00', 3200.00, 16000.00, '2020-03-23 00:04:37'),
(214, 'XACEPTejiyzpsFMRNq7a92Sol', '54345231', 1, 'INV2020030100078', '3', 4, '600.00', 850.00, 3400.00, '2020-03-23 00:04:37'),
(215, 'cW28KZb3t059owaJlVpHeFIOE', '54345231', 1, 'INV2020030100078', '4', 1, '70.00', 200.00, 200.00, '2020-03-23 00:04:37'),
(216, 'K7uTWxlEz5qXb9vyMcjPQdLte', '54345231', 1, 'INV2020030100079', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-23 07:32:39'),
(217, 'wuoI1QOD6scGbvVlByt2R9mdL', '54345231', 1, 'INV2020030100080', '4', 1, '70.00', 200.00, 200.00, '2020-03-23 07:32:50'),
(218, 'xGPAJB9U8iOp0dELyZbft2oIV', '54345231', 1, 'INV2020030100080', '28', 1, '120.00', 200.00, 200.00, '2020-03-23 07:32:50'),
(219, '9UuJYk5f1C4bHIcWXqVTLNeER', '54345231', 1, 'INV2020030100081', '3', 2, '600.00', 850.00, 1700.00, '2020-03-23 07:33:12'),
(220, 'INcYGn5LSX03hxB4Zt7l1EbqC', '54345231', 1, 'INV2020030100081', '4', 1, '70.00', 200.00, 200.00, '2020-03-23 07:33:12'),
(221, '1RNviWXtMsgCPB7oVp2IJmAK5', '54345231', 1, 'INV2020030100082', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-23 07:35:43'),
(222, 'GhULWpwgSP0vYCEnV4Fsd8QxB', '54345231', 1, 'INV2020030100082', '2', 2, '2000.00', 3200.00, 6400.00, '2020-03-23 07:35:43'),
(223, '06ydSWzcbkNn4rUtuIKC5BxLA', '54345231', 1, 'INV2020030100082', '3', 1, '600.00', 850.00, 850.00, '2020-03-23 07:35:43'),
(224, '1CDxfKeV4dqriGUzZj2m60pwH', '54345231', 1, 'INV2020030100082', '4', 1, '70.00', 200.00, 200.00, '2020-03-23 07:35:43'),
(225, 'mYiyvfdXh4HNksWoJIq69OxrS', '54345231', 1, 'INV2020030100082', '28', 1, '120.00', 200.00, 200.00, '2020-03-23 07:35:43'),
(226, 'drVOFMQZIo0qfHecWTlhvasLg', '54345231', 1, 'INV2020030100083', '1', 3, '1500.00', 1900.00, 5700.00, '2020-03-23 08:16:39'),
(227, '0W92DJP3kZfRL5gOm74KMnF8Y', '54345231', 1, 'INV2020030100083', '2', 4, '2000.00', 3200.00, 12800.00, '2020-03-23 08:16:39'),
(228, 'ZwFtzgMbWHBp8JIuRSDrkVGh1', '54345231', 1, 'INV2020030100083', '3', 1, '600.00', 850.00, 850.00, '2020-03-23 08:16:39'),
(229, 'txNWGbedOF3S1qmkJXr5DZ4fv', '54345231', 1, 'INV2020030100083', '4', 1, '70.00', 200.00, 200.00, '2020-03-23 08:16:39'),
(230, 'y9WHTeta3lAIhU1fXxbvkYjn2', '54345231', 1, 'INV2020030100083', '28', 1, '120.00', 200.00, 200.00, '2020-03-23 08:16:39'),
(231, 'Ts7tFdnCX4c5NBhZ6LYRUylEV', '54345231', 1, 'POS2020030100084', '28', 1, '120.00', 200.00, 200.00, '2020-03-23 16:34:22'),
(232, 'EXYy2cZInpsiod7DgAOxHwkV4', '54345231', 1, 'POS2020030100085', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-24 03:41:20'),
(233, 'v6re5SG3bPMlkqLHWfsK9go0Q', '54345231', 1, 'POS2020030100085', '3', 1, '600.00', 850.00, 850.00, '2020-03-24 03:41:20'),
(234, 'qQnfZdL0YShWaJDNp94XOKVME', '54345231', 1, 'POS2020030100086', '28', 1, '120.00', 200.00, 200.00, '2020-03-24 03:51:11'),
(235, '7vKi2JTtk4d3UqezhlA5wcxI1', '54345231', 1, 'POS2020030100087', '28', 1, '120.00', 200.00, 200.00, '2020-03-24 04:44:48'),
(236, 'wfQNVOX5P72poe8uqR4TDAmHt', '54345231', 1, 'POS2020030100088', '28', 1, '120.00', 200.00, 200.00, '2020-03-24 04:45:55'),
(237, 'Z6aHOgQqGF19K32CmA5p7PrJw', '54345231', 1, 'POS2020030100089', '28', 1, '120.00', 200.00, 200.00, '2020-03-24 04:47:41'),
(238, 'mKuQ24k9E7OTzbi3JgMIfjrdh', '54345231', 1, 'POS2020030100090', '28', 1, '120.00', 200.00, 200.00, '2020-03-24 04:48:42'),
(239, 'hHVK45ZPE2gkUf1DnTRMSojGX', '54345231', 1, 'POS2020030100091', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 04:50:24'),
(240, 'rCBQSbxXLMaiJ7sl24NvVGe6d', '54345231', 1, 'POS2020030100092', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 04:51:51'),
(241, 'D0kz9i6w4f7mUosnlXTrShvGW', '54345231', 1, 'POS2020030100093', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 04:54:02'),
(242, 'TtilUa0nbz5IjJHZQqKA8eCRX', '54345231', 1, 'POS2020030100094', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 04:55:32'),
(243, 'vfRi2nqjhAO80JNLxUI1Cz6Br', '54345231', 1, 'POS2020030100095', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 05:00:18'),
(244, 'H8QfzPZksoF6CRlyWIMeEGXdb', '54345231', 1, 'POS2020030100096', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 05:32:34'),
(245, 'RKZUCdEMTWD1vQkPnr3bFBxXa', '54345231', 1, 'POS2020030100097', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 05:46:50'),
(246, 'C5hOk7iu6PUS8A1avZlQDF3nJ', '54345231', 1, 'POS2020030100098', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 05:47:07'),
(247, 'ESWRMrIcAuTYZJUboV1NdGtsk', '54345231', 1, 'POS2020030100099', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 05:50:06'),
(248, 'q4EHzrDIh19R6lWVYZCjLcvXF', '54345231', 1, 'POS2020030100100', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 05:50:37'),
(249, 'eusviJ8ZTHxwW0NUEad436Knf', '54345231', 1, 'POS2020030100101', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:07:17'),
(250, '8vVEpkKRdjLZaYz0726TxnqWJ', '54345231', 1, 'POS2020030100102', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:09:20'),
(251, 'lNQsOR8akgWpynCuG5KHBfvDA', '54345231', 1, 'POS2020030100103', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:10:07'),
(252, 'd1v6SxgyAM5jXJUulZ4iDH7Vq', '54345231', 1, 'POS2020030100104', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:11:03'),
(253, 'XBGHxCwyz8b6oJU4gZDE5jIhq', '54345231', 1, 'POS2020030100105', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:12:30'),
(254, 'YZU1LRwFoPVDjpAi7HWGhmEyz', '54345231', 1, 'POS2020030100106', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:14:40'),
(255, 'Z3rWflIHTEdQ8VMgzyea4CxSF', '54345231', 1, 'POS2020030100107', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:18:30'),
(256, 'vicyfnxuU3B9lR6XK8EF4zG7P', '54345231', 1, 'POS2020030100108', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:22:56'),
(257, 'ZLRvY4zCUDEkqgc0oFNeX68yO', '54345231', 1, 'POS2020030100108', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:22:56'),
(258, 'z1x3nY8SEQsiJM4mCv56WwXKL', '54345231', 1, 'POS2020030100109', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:25:02'),
(259, 'KF2i4CrNlBkcIqf0g5HGXaAPO', '54345231', 1, 'POS2020030100109', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:25:02'),
(260, 'mencRGoUs3hyFATjDf1E6xqtC', '54345231', 1, 'POS2020030100110', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:25:39'),
(261, 'erJ0huqSKm9EDOMt7APzovC6U', '54345231', 1, 'POS2020030100110', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:25:39'),
(262, 'syrL3tlHZKYOhuJzNXVGf1gvk', '54345231', 1, 'POS2020030100111', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:26:04'),
(263, 'WNd2QFXfkq7oVTY0RU8jb6ESx', '54345231', 1, 'POS2020030100111', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:26:04'),
(264, 'uH2mCZNV8pkoyK5PdTncwbSYf', '54345231', 1, 'POS2020030100112', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:26:24'),
(265, 'ScrOJvy4iEFxQYk27qWbGt8Lg', '54345231', 1, 'POS2020030100112', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:26:24'),
(266, 'CRLyIKte28c0hHZvJXMYz1jT5', '54345231', 1, 'POS2020030100113', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 06:26:43'),
(267, 'urF05ALmsUvyYGZt2817Nn6ED', '54345231', 1, 'POS2020030100113', '29', 1, '2.00', 4.00, 4.00, '2020-03-24 06:26:43'),
(268, '0RvVg62sM5Y8kljtzAcIJNeqp', '54345231', 1, 'POS2020030100114', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-24 10:01:00'),
(269, 'OCpB8XNekUzfPs1u29ZA4dYv5', '54345231', 1, 'POS2020030100114', '3', 1, '600.00', 850.00, 850.00, '2020-03-24 10:01:00'),
(270, 'gH60kOCl5KLWn4YJhzepjyNwm', '54345231', 1, 'POS2020030100115', '3', 1, '600.00', 850.00, 850.00, '2020-03-24 10:02:18'),
(271, 'CyG9LApfXw2zP0hoUSjJWaiOV', '54345231', 1, 'POS2020030100115', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 10:02:18'),
(272, 'MZdYKT2e1jbV7vUySDwFIWAL8', '54345231', 1, 'POS2020030100116', '3', 1, '600.00', 850.00, 850.00, '2020-03-24 10:08:20'),
(273, 'QxJsXfd7CeR9poTU1iz28jKFE', '54345231', 1, 'POS2020030100116', '4', 1, '70.00', 200.00, 200.00, '2020-03-24 10:08:20'),
(274, 'ze8Ea5R6w4TlNtyn7SiIr1xov', '54345231', 1, 'POS2020030100116', '29', 14, '2.00', 4.00, 56.00, '2020-03-24 10:08:20'),
(275, 'CYfEBqrAJOsIcZn9y4mp6i3W8', '54345231', 1, 'POS2020030100117', '3', 1, '600.00', 850.00, 850.00, '2020-03-25 11:06:40'),
(276, 'LuZTrJefG0XsMlhcAOK5pB92W', '54345231', 1, 'POS2020030100117', '4', 1, '70.00', 200.00, 200.00, '2020-03-25 11:06:40'),
(277, '0qJ5rUiWymwjDQEXs9RlTcAgN', '54345231', 1, 'POS2020030100118', '3', 1, '600.00', 850.00, 850.00, '2020-03-25 11:07:10'),
(278, '0725p4LoZeMUKWgPOCjJNTxqz', '54345231', 1, 'POS2020030100118', '4', 1, '70.00', 200.00, 200.00, '2020-03-25 11:07:10'),
(279, 'vwQxlo8Ghuz39TpY0kRMBaIim', '54345231', 1, 'POS2020030100119', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-25 11:33:09'),
(280, 'CA2kv6Zhm95KlNWyT3s47UYD0', '54345231', 1, 'POS2020030100119', '3', 1, '600.00', 850.00, 850.00, '2020-03-25 11:33:09'),
(281, 'OmVfR90l4whPgWKvQMZA1XHx2', '54345231', 1, 'POS2020030100119', '4', 1, '70.00', 200.00, 200.00, '2020-03-25 11:33:09'),
(282, 'ILx0Vg9YUBtPFXhdjTfDbyu7Z', '54345231', 1, 'POS2020030100120', '29', 2, '2.00', 4.00, 8.00, '2020-03-25 11:33:35'),
(283, 'Wpf7VcTzQFvrgO6lLU8Ko1CD2', '54345231', 1, 'POS2020030100121', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-26 00:11:22'),
(284, 'Ak9VinLKy41bJZoOBhaqHINw0', '54345231', 1, 'POS2020030100122', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-26 08:40:41'),
(285, 'QNutT5LI8Jks94Gn1cgzjKwvF', '54345231', 1, 'POS2020030100122', '3', 5, '600.00', 850.00, 4250.00, '2020-03-26 08:40:41'),
(286, 'WIb5xuwSRGYv0Kg3hofdXPVmA', '54345231', 1, 'POS2020030100122', '4', 1, '70.00', 200.00, 200.00, '2020-03-26 08:40:41'),
(287, 'dRiezXtLHK8slpF6BUP1YjIDw', '54345231', 1, 'POS2020030100122', '28', 1, '120.00', 200.00, 200.00, '2020-03-26 08:40:41'),
(288, 'NciXzUuMqlO786Q2IH9PVBjLF', '54345231', 1, 'POS2020030100123', '35', 1, '15.00', 20.00, 20.00, '2020-03-27 10:14:20'),
(289, '7eBN3rQ8sUkhARZtc9qTXfOyW', '54345231', 1, 'POS2020030100124', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-27 18:45:12'),
(290, '6JMXui7QKhcH15L0DwWk9pFTS', '54345231', 1, 'POS2020030100125', '4', 1, '70.00', 200.00, 200.00, '2020-03-27 18:45:41'),
(291, 'uaNM1U9mqvG8hsfL7WneR0YQV', '54345231', 1, 'POS2020030100125', '28', 5, '120.00', 200.00, 1000.00, '2020-03-27 18:45:41'),
(292, 'irKnVO1TydEN6AkDhMc2wFZpC', '54345231', 1, 'POS2020030100125', '29', 1, '2.00', 4.00, 4.00, '2020-03-27 18:45:41'),
(293, '0mbIlLWXq8Pt5MjKHfaUVdDp9', '54345231', 1, 'POS2020030100125', '35', 4, '15.00', 20.00, 80.00, '2020-03-27 18:45:41'),
(294, 'MCkA0IvEmTczZQKtHWRDVJFX7', '54345231', 1, 'POS2020030100125', '36', 1, '2.00', 6.00, 6.00, '2020-03-27 18:45:41'),
(295, 'ktQO380nEHLwvzUMoZrW4AYuj', '54345231', 1, 'POS2020030100125', '37', 1, '1.00', 4.00, 4.00, '2020-03-27 18:45:41'),
(296, 'ftwyeqrvuchCGx8SN9LiZX2EO', '54345231', 1, 'POS2020030100126', '1', 5, '1500.00', 1900.00, 9500.00, '2020-03-27 18:46:15'),
(297, 'DnewY68VJS4A2gsOEWiNMP0hy', '54345231', 1, 'POS2020030100126', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-27 18:46:15'),
(298, 'xF08meAvH5M9odQwtzPZ4O16i', '54345231', 1, 'POS2020030100126', '4', 1, '70.00', 200.00, 200.00, '2020-03-27 18:46:15'),
(299, 'XH3VNgmnWspPRFzICbSojUYM1', '54345231', 1, 'POS2020030100126', '28', 1, '120.00', 200.00, 200.00, '2020-03-27 18:46:15'),
(300, 'f47jlLGBH29WTN0wOYdRtxrUm', '54345231', 2, 'POS2020030200127', '24', 1, '1500.00', 1900.00, 1900.00, '2020-03-27 18:47:04'),
(301, 'wJ2EzqCiHmUYeABSRWkrV4sXZ', '54345231', 2, 'POS2020030200127', '27', 1, '70.00', 200.00, 200.00, '2020-03-27 18:47:04'),
(302, 'sFMdylcGorCELWSqOQXpJwmIK', '54345231', 2, 'POS2020030200128', '24', 4, '1500.00', 1900.00, 7600.00, '2020-03-27 18:47:31'),
(303, 'b2qmEOHei7ch6uoyCVL0SdUvk', '54345231', 2, 'POS2020030200128', '27', 1, '70.00', 200.00, 200.00, '2020-03-27 18:47:31'),
(304, '6nS3xvoDAeFJb1RkzmTgpCr0V', '54345231', 1, 'POS2020030100129', '36', 3, '2.00', 6.00, 18.00, '2020-03-28 08:41:09'),
(305, 'OBCU1HVspJZGzm8jeSkn5E0RD', '54345231', 1, 'POS2020030100129', '37', 3, '1.00', 4.00, 12.00, '2020-03-28 08:41:09'),
(306, 'dqFbZR2A4gBm5CukxtWy9rESV', '54345231', 1, 'POS2020030100130', '29', 1, '2.00', 4.00, 4.00, '2020-03-28 17:09:10'),
(307, 'V3GhZ0oADYxWq4il87dmbrfge', '54345231', 1, 'POS2020030100130', '36', 1, '2.00', 6.00, 6.00, '2020-03-28 17:09:10'),
(308, 'bKdw6vV7JXcLsqtWxPAmURD2T', '54345231', 1, 'POS2020030100130', '37', 1, '1.00', 4.00, 4.00, '2020-03-28 17:09:10'),
(309, 'KzXEMvPmnh3ws5xtiYBg0dDWS', '54345231', 1, 'POS2020030100131', '4', 1, '70.00', 200.00, 200.00, '2020-03-28 17:09:24'),
(310, 'gRHuwMEQFNGIvO64VokhJTaYU', '54345231', 1, 'POS2020030100131', '28', 1, '120.00', 200.00, 200.00, '2020-03-28 17:09:24'),
(311, 'bqXGx9wAWuadvmgVY8fp7c6sy', '54345231', 1, 'POS2020030100131', '29', 1, '2.00', 4.00, 4.00, '2020-03-28 17:09:24'),
(312, 'Mln7z5pwYrqbGj3SaX0CQH9iO', '54345231', 1, 'POS2020030100132', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-28 17:11:42'),
(313, 'gS0Fj9KwzAIUxOcGWDV2L6aX4', '54345231', 1, 'POS2020030100132', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-28 17:11:42'),
(314, 'kROhBNMQiDoefypbdXEPU67lg', '54345231', 1, 'POS2020030100133', '28', 1, '120.00', 200.00, 200.00, '2020-03-28 17:12:00'),
(315, 'BaU0OfR9VqStFNkCxLKsojTyw', '54345231', 1, 'POS2020030100133', '29', 1, '2.00', 4.00, 4.00, '2020-03-28 17:12:00'),
(316, 'Cuz0EZqDpoU5VlIb9XOKmRLPM', '54345231', 1, 'POS2020030100133', '37', 1, '1.00', 4.00, 4.00, '2020-03-28 17:12:00'),
(317, 'wDCeBq75jJSWKuZN0VvaPxQz1', '54345231', 1, 'POS2020030100134', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-28 18:36:57'),
(318, '9qtusOGV6p0IJN2e5BczSjdKD', '54345231', 1, 'POS2020030100134', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-28 18:36:57'),
(319, 'mEfIcZRDx0qFsb78r4lNw6dPU', '54345231', 1, 'POS2020030100134', '28', 1, '120.00', 200.00, 200.00, '2020-03-28 18:36:57'),
(320, 'TE3ND6nHxC5X9bKrgolQyjveJ', '54345231', 1, 'POS2020030100135', '28', 10, '120.00', 200.00, 2000.00, '2020-03-28 19:09:26'),
(321, 'kMRDUpGK4SClYxHh3fVqTsiZQ', '54345231', 1, 'POS2020030100135', '29', 1, '2.00', 4.00, 4.00, '2020-03-28 19:09:26'),
(322, 'JcfdBoSxDsZa5zMRG8WlhPQt1', '54345231', 1, 'POS2020030100136', '4', 1, '70.00', 200.00, 200.00, '2020-03-30 01:04:40'),
(323, 'wGnmxQ0Lg1pVRoFXfydWhzl7D', '54345231', 1, 'POS2020030100137', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-30 01:07:54'),
(324, 'z6EuODLJ3jUV1xqacsIwBA5Hk', '54345231', 1, 'POS2020030100138', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-30 01:09:33'),
(325, 'PhUO4JYH7Bb1mZ8icLXN9TKvD', '54345231', 1, 'POS2020030100138', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-30 01:09:33'),
(326, 'wEKhrW3lycdig8RYIsD6q7ZCe', '54345231', 1, 'POS2020030100139', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-30 01:11:37'),
(327, 'QBSnVg3FAsHXcPD8m2v4WOY1e', '54345231', 1, 'POS2020030100139', '4', 1, '70.00', 200.00, 200.00, '2020-03-30 01:11:37'),
(328, 'laUG6eF9dbPK8TcnOum5sx7tS', '54345231', 1, 'POS2020030100139', '36', 1, '2.00', 6.00, 6.00, '2020-03-30 01:11:37'),
(329, 'pDbf1I2hcGnulgUqOBa8VjFLS', '54345231', 1, 'POS2020030100140', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-30 01:17:51'),
(330, 'FOpGSXqdjshC1957bxPL48wya', '54345231', 1, 'POS2020030100140', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-30 01:17:51'),
(331, 'bGkA9vZ2gVTFN63XsfRrmcMnI', '54345231', 1, 'POS2020030100141', '1', 1, '1500.00', 1900.00, 1900.00, '2020-03-30 01:18:02'),
(332, 'UrznQkFBH1D9lgNPOL073GYtK', '54345231', 1, 'POS2020030100141', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-30 01:18:02'),
(333, 'AfnGXbxNRz1pWvd85cQKHme03', '54345231', 1, 'POS2020030100142', '1', 3, '1500.00', 1900.00, 5700.00, '2020-03-30 19:02:40'),
(334, 'Bt07RnZ1TbVe8hNKrWJGjSiYl', '54345231', 1, 'POS2020030100142', '2', 4, '2000.00', 3200.00, 12800.00, '2020-03-30 19:02:40'),
(335, 'tsV2vxNTKYbLE9er7Oiw6HXBF', '54345231', 1, 'POS2020030100142', '29', 5, '2.00', 4.00, 20.00, '2020-03-30 19:02:40'),
(336, 'TopNgKjCmwiJaDek0L8UWtZvB', '54345231', 1, 'POS2020030100142', '36', 1, '2.00', 6.00, 6.00, '2020-03-30 19:02:40'),
(337, 'JNBTOxm2t90YopFMqIjKA1gai', '54345231', 1, 'POS2020030100142', '37', 1, '1.00', 4.00, 4.00, '2020-03-30 19:02:40'),
(338, '4HduJkRlG72qoWZ1BAXCLe9DI', '54345231', 1, 'POS2020030100143', '36', 1, '2.00', 6.00, 6.00, '2020-03-31 05:34:28'),
(339, 'rqkgFC9GPczRo637dWiQJwYup', '54345231', 1, 'POS2020030100143', '37', 1, '1.00', 4.00, 4.00, '2020-03-31 05:34:28'),
(340, '9KEOSoaM7C3TfXVpHq6A8z2ex', '54345231', 1, 'POS2020030100144', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-31 07:14:01'),
(341, 'e4qGzhYsZWoS0JIkiyDa15Alb', '54345231', 1, 'POS2020030100145', '37', 1, '1.00', 4.00, 4.00, '2020-03-31 23:11:41'),
(342, 'jWk57bUGi8h41pZaLTKlSrANv', '54345231', 1, 'POS2020030100146', '37', 1, '1.00', 4.00, 4.00, '2020-03-31 23:12:35'),
(343, 'src5wCbz1HlST3kX049G2yMLu', '54345231', 1, 'POS2020030100147', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-31 23:14:05'),
(344, '7MSPYEzNh4oZjRC1b9OH36BnX', '54345231', 1, 'POS2020030100148', '2', 1, '2000.00', 3200.00, 3200.00, '2020-03-31 23:16:09'),
(345, 'MOP4TzJGbqQ2ycCXevjKsWkp8', '54345231', 1, 'POS2020040100149', '3', 1, '600.00', 850.00, 850.00, '2020-04-01 23:02:44'),
(346, 'AWdpNDCiMQ1aERvBbjzoGZqPU', '54345231', 1, 'POS2020040100150', '3', 1, '600.00', 850.00, 850.00, '2020-04-01 23:03:23'),
(347, 'SZQlcb0Aspo2w64yWF3XLfV59', '54345231', 1, 'POS2020040100150', '35', 1, '15.00', 20.00, 20.00, '2020-04-01 23:03:23'),
(348, 'HMyv04capUjrO2JxkWIVgGfT5', '54345231', 1, 'POS2020040100150', '36', 1, '2.00', 6.00, 6.00, '2020-04-01 23:03:23'),
(349, 'opRSlVx846edQcKmWaU3EYGr7', '54345231', 1, 'POS2020040100150', '37', 1, '1.00', 4.00, 4.00, '2020-04-01 23:03:23'),
(350, 'CObxUVvzj16dBkc3oStZHualq', '54345231', 1, 'POS2020040100151', '3', 1, '600.00', 850.00, 850.00, '2020-04-01 23:03:45'),
(351, '3jOJ9Pw4N5usBLg2iSGrQnvfm', '54345231', 1, 'POS2020040100152', '35', 1, '15.00', 20.00, 20.00, '2020-04-02 11:42:22'),
(352, 'mxRyNdfonSka9r3TDZPH5lzp8', '54345231', 1, 'POS2020040100152', '36', 1, '2.00', 6.00, 6.00, '2020-04-02 11:42:22'),
(353, '1vr7aOH5gnh8eNkKiLscCTz6M', '54345231', 1, 'POS2020040100152', '37', 1, '1.00', 4.00, 4.00, '2020-04-02 11:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_website` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'assets/images/logo.png',
  `primary_contact` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secondary_contact` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_options` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_opening_days` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt_message` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `terms_and_conditions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `manager_signature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reports_sales_attendant` enum('sales-attendant-performance','team-performance') COLLATE utf8_unicode_ci DEFAULT 'sales-attendant-performance',
  `reports_period` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'today',
  `total_expenditure` double(12,2) NOT NULL DEFAULT 0.00,
  `space_per_square_foot` double(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `clientId`, `client_name`, `client_email`, `client_website`, `client_logo`, `primary_contact`, `secondary_contact`, `payment_options`, `shop_opening_days`, `address_1`, `address_2`, `receipt_message`, `terms_and_conditions`, `manager_signature`, `reports_sales_attendant`, `reports_period`, `total_expenditure`, `space_per_square_foot`) VALUES
(1, '54345231', 'E-Inventory Ltd', 'info@einventory.com', 'www.einventory.com', 'assets/images/company/jYT5ipPBfwIRhg4Ono0drK8My.png', '+123123456789', '+123123456789', 'cash,MoMo,credit,card', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', '2821 Kensington Road,', NULL, 'Thank you for trading with us.', '&lt;ul class=&quot;pl-3&quot;&gt;&lt;li&gt;&lt;small&gt;All accounts are to be paid within 7 days from receipt of invoice.&lt;/small&gt;&lt;/li&gt;&lt;li&gt;&lt;small&gt;To be paid by cheque or credit card or direct payment online.&lt;/small&gt;&lt;/li&gt;&lt;li&gt;&lt;small&gt;If account is not paid within 7 days the credits details supplied as confirmation&lt;br&gt;of work undertaken will be charged the agreed quoted fee noted above.&lt;/small&gt;&lt;/li&gt;&lt;/ul&gt;', 'assets/images/company/YeEZaxpd6DOuvFlXwK8s9IgfC.png', 'sales-attendant-performance', 'this-week', 18602.00, 262.71);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) NOT NULL DEFAULT '0',
  `branchId` int(11) DEFAULT 1,
  `user_id` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `access_level` int(11) NOT NULL DEFAULT 6,
  `theme` enum('1','2') NOT NULL DEFAULT '1',
  `font_style` varchar(50) DEFAULT NULL,
  `font_size` varchar(50) DEFAULT NULL,
  `status` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `online` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT current_timestamp(),
  `daily_target` double(12,2) NOT NULL DEFAULT 0.00,
  `weekly_target` double(12,2) NOT NULL DEFAULT 0.00,
  `monthly_target` double(12,2) NOT NULL DEFAULT 0.00,
  `last_login_attempts` int(11) DEFAULT 0,
  `last_login_attempts_time` datetime DEFAULT current_timestamp(),
  `login_session` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `clientId`, `branchId`, `user_id`, `name`, `gender`, `email`, `phone`, `login`, `password`, `access_level`, `theme`, `font_style`, `font_size`, `status`, `online`, `last_login`, `daily_target`, `weekly_target`, `monthly_target`, `last_login_attempts`, `last_login_attempts_time`, `login_session`, `country_id`, `city_id`, `city`, `created_on`, `created_by`, `image`) VALUES
(1, '54345231', 1, '2019DevAI', 'Visaminet User', 'Male', 'admin@mail.com', '0576641131', 'AiDevUser', '$2y$10$gHcSrrsHfvMTedpSeMJbNu6a4fmscHbh3KrZwUsJZEsrZifhkBizi', 1, '1', NULL, NULL, 1, 1, '2020-04-02 08:42:07', 2000.00, 4000.00, 10000.00, 1, '2020-01-23 14:52:33', NULL, 84, NULL, NULL, '2020-01-25 10:17:35', NULL, 'avatar.png'),
(2, '54345231', 1, 'eqLTHf9MW7x0NBGP5Vs83vp', 'Branch Manger', 'Male', 'manager@mail.com', '0244022044', 'BranchManger', '$2y$10$CAg0..AR3iojlzqlG5yS0Oqf//s49AlsEVykfk3sy0m8E8h8VSHte', 3, '1', NULL, NULL, 1, 1, '2020-03-27 18:48:16', 0.00, 0.00, 0.00, 0, '2020-02-04 12:29:16', NULL, 84, NULL, NULL, '2020-02-04 12:29:16', NULL, 'avatar.png'),
(3, '54345231', 1, 'oDGirP31jNnXYK6dza20m', 'Company Owner', 'Male', 'owner@mail.com', '0201202052', 'CompaneyOwner', '$2y$10$CAg0..AR3iojlzqlG5yS0Oqf//s49AlsEVykfk3sy0m8E8h8VSHte', 2, '1', NULL, NULL, 1, 1, '2020-03-13 23:41:09', 7000.00, 12000.00, 25000.00, 0, '2020-02-05 12:53:03', NULL, 84, NULL, NULL, '2020-02-05 12:53:03', NULL, 'avatar.png'),
(4, '54345231', 1, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', 'Sales Officer', 'Male', 'sales@mail.com', '0550001110', 'SalesOfficer', '$2y$10$CAg0..AR3iojlzqlG5yS0Oqf//s49AlsEVykfk3sy0m8E8h8VSHte', 4, '1', NULL, NULL, 1, 1, '2020-03-20 20:07:12', 0.00, 0.00, 0.00, 0, '2020-02-05 12:54:24', NULL, 84, NULL, NULL, '2020-02-05 12:54:24', NULL, 'avatar.png'),
(6, '54345231', 2, 'oDGirP31jNnXYK6dza20mw', 'Philip Amponsah', 'Male', 'branch2@mail.com', '0201202052', 'Branch Two User', '$2y$10$CAg0..AR3iojlzqlG5yS0Oqf//s49AlsEVykfk3sy0m8E8h8VSHte', 2, '1', NULL, NULL, 1, 1, '2020-03-31 22:34:38', 0.00, 0.00, 0.00, 0, '2020-02-05 12:53:03', NULL, 84, NULL, NULL, '2020-02-05 12:53:03', NULL, 'avatar.png');

-- --------------------------------------------------------

--
-- Table structure for table `users_activity_logs`
--

CREATE TABLE `users_activity_logs` (
  `id` int(11) NOT NULL,
  `clientId` varchar(255) DEFAULT NULL,
  `branchId` varchar(32) DEFAULT NULL,
  `page` varchar(32) DEFAULT NULL,
  `itemId` varchar(32) DEFAULT NULL,
  `userId` varchar(255) DEFAULT NULL,
  `description` text NOT NULL DEFAULT 'NULL',
  `user_agent` varchar(255) NOT NULL DEFAULT 'NULL',
  `date_recorded` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_activity_logs`
--

INSERT INTO `users_activity_logs` (`id`, `clientId`, `branchId`, `page`, `itemId`, `userId`, `description`, `user_agent`, `date_recorded`) VALUES
(2, NULL, '1', 'pos', 'POS2020040100149', '2019DevAI', 'Recorded a new Sale at the POS', ' |  | 127.0.0.1', '2020-04-01 23:02:44'),
(3, NULL, '1', 'pos', 'POS2020040100150', '2019DevAI', 'Recorded a new Sale at the POS', ' |  | 127.0.0.1', '2020-04-01 23:03:23'),
(4, NULL, '1', 'pos', 'POS2020040100151', '2019DevAI', 'Recorded a new Sale at the POS', ' |  | 127.0.0.1', '2020-04-01 23:03:45'),
(5, '54345231', '1', 'settings', '54345231', '2019DevAI', 'Updated the payment options of the Company.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 00:41:37'),
(6, '54345231', '1', 'customer', 'EVC143364821582', '2019DevAI', 'Added a new Customer', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:06:29'),
(7, '54345231', '1', 'customer', 'EVC423589361967', '2019DevAI', 'Updated the customer details', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:24:41'),
(8, '54345231', '1', 'customer', 'EVC423589361967', '2019DevAI', 'Updated the customer details', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:24:52'),
(9, '54345231', '1', 'customer', 'EVC427416398569', '2019DevAI', 'Updated the customer details', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:26:27'),
(10, '54345231', '1', 'customer', 'EVC951937465721', '2019DevAI', 'Updated the customer details', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:26:56'),
(11, '54345231', '1', 'branches', '3', '2019DevAI', 'Updated the status of the branch and set it as Inactive', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:38:51'),
(12, '54345231', '1', 'branches', '6', '2019DevAI', 'Updated the status of the branch and set it as Inactive', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 09:38:57'),
(13, '54345231', '1', 'branches', '3', '2019DevAI', 'Updated the status of the branch and set it as Active', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 10:10:35'),
(14, '54345231', '1', 'branches', '1234567', '2019DevAI', 'Updated the details of the branch.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 10:57:35'),
(15, '54345231', '1', 'branches', '6uSXCt4QYm30', '2019DevAI', 'Updated the details of the Store Outlet.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 10:58:20'),
(16, '54345231', '1', 'users', '2019DevAI', '2019DevAI', 'Update the user details.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 11:30:15'),
(17, '54345231', '1', 'users', 'oDGirP31jNnXYK6dza20m', '2019DevAI', 'Update the user details.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-02 11:30:46'),
(18, NULL, '1', 'pos', 'POS2020040100152', '2019DevAI', 'Recorded a new Sale at the POS', ' |  | 127.0.0.1', '2020-04-02 11:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `users_login_history`
--

CREATE TABLE `users_login_history` (
  `id` int(11) NOT NULL,
  `clientId` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branchId` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_ipaddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_browser` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_platform` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_logged` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_login_history`
--

INSERT INTO `users_login_history` (`id`, `clientId`, `branchId`, `username`, `log_ipaddress`, `log_browser`, `user_id`, `log_platform`, `date_logged`) VALUES
(1, '54345231', '1', 'admin@mail.com', '127.0.0.1', 'Chrome|Windows 10', '2019DevAI', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36', '2020-04-02 08:42:07');

-- --------------------------------------------------------

--
-- Table structure for table `users_reset_request`
--

CREATE TABLE `users_reset_request` (
  `id` int(11) NOT NULL,
  `token_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'PENDING',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiry_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_logged` datetime DEFAULT current_timestamp(),
  `reset_date` datetime DEFAULT NULL,
  `reset_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_reset_request`
--

INSERT INTO `users_reset_request` (`id`, `token_status`, `username`, `user_id`, `request_token`, `user_agent`, `expiry_time`, `date_logged`, `reset_date`, `reset_agent`) VALUES
(1, 'USED', 'AiDevUser', '2019DevAI', NULL, 'Chrome Windows 10|127.0.0.1', '1585689635', '2020-03-31 22:08:02', '2020-03-31 22:20:35', 'Chrome Windows 10|127.0.0.1'),
(2, 'ANNULED', 'AiDevUser', '2019DevAI', 'cgaAD1x50GsNydnplVUv2ZPCfB6hJu7t4qSjYWHILETO3XrbFoRwz98eMki', 'Chrome Windows 10|127.0.0.1', '1585744023', '2020-04-01 12:27:03', NULL, NULL),
(3, 'PENDING', 'AiDevUser', '2019DevAI', 'ucocYigYTS5QhjRevrw1VbseWZlWh3lLCm54A0dH2b0kC7sinZ49qRNDEII9vTmM6yQOPLKt', 'Chrome Windows 10|127.0.0.1', '1585744130', '2020-04-01 12:28:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permissions` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_logged` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `permissions`, `date_logged`) VALUES
(1, '2019DevAI', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"}}}', '2020-02-18 13:09:49'),
(2, 'eqLTHf9MW7x0NBGP5Vs83vp', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"}}}', '2020-02-18 13:12:05'),
(3, 'oDGirP31jNnXYK6dza20m', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"0\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"branches\":{\"view\":\"0\",\"add\":\"0\",\"update\":\"0\",\"delete\":\"0\"},\"access_levels\":{\"view\":\"0\",\"update\":\"0\"},\"executive_dashboard\":{\"view\":\"1\"}}}', '2020-02-18 13:12:27'),
(4, 'mjbpdYvK3zwC7lns4QeyIkV90WBFHM', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"0\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}', '2020-02-18 13:12:33'),
(6, 'oDGirP31jNnXYK6dza20mw', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"0\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"branches\":{\"view\":\"0\",\"add\":\"0\",\"update\":\"0\",\"delete\":\"0\"},\"access_levels\":{\"view\":\"0\",\"update\":\"0\"},\"executive_dashboard\":{\"view\":\"1\"}}}', '2020-02-18 13:12:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_levels`
--
ALTER TABLE `access_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customers_account`
--
ALTER TABLE `customers_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `parent_account` (`parent_account`);

--
-- Indexes for table `customers_company`
--
ALTER TABLE `customers_company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_monitoring`
--
ALTER TABLE `data_monitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_list`
--
ALTER TABLE `email_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industry`
--
ALTER TABLE `industry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_id` (`category_id`);

--
-- Indexes for table `products_stocks`
--
ALTER TABLE `products_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`request_id`);

--
-- Indexes for table `requests_details`
--
ALTER TABLE `requests_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `sales_details`
--
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_activity_logs`
--
ALTER TABLE `users_activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login_history`
--
ALTER TABLE `users_login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_reset_request`
--
ALTER TABLE `users_reset_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_levels`
--
ALTER TABLE `access_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `customers_account`
--
ALTER TABLE `customers_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customers_company`
--
ALTER TABLE `customers_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `data_monitoring`
--
ALTER TABLE `data_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `email_list`
--
ALTER TABLE `email_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `industry`
--
ALTER TABLE `industry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products_stocks`
--
ALTER TABLE `products_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `requests_details`
--
ALTER TABLE `requests_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_activity_logs`
--
ALTER TABLE `users_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users_login_history`
--
ALTER TABLE `users_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_reset_request`
--
ALTER TABLE `users_reset_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
