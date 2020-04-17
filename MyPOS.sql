-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2020 at 04:40 AM
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
(1, 'Visamine Staff', 'Analitica Innovare Staffs', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\",\"developer\":\"1\"},\"expenses\":{\"expenses_add\":\"1\",\"expenses_update\":\"1\",\"expenses_delete\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"}}}'),
(2, 'Admin', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"expenses\":{\"expenses_add\":\"1\",\"expenses_update\":\"1\",\"expenses_delete\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"}}}'),
(3, 'Sales Manager', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"access_levels\":{\"view\":\"0\",\"update\":\"0\"},\"branches\":{\"view\":\"0\",\"add\":\"0\",\"update\":\"0\",\"delete\":\"0\"}}}'),
(4, 'Sales Officer', '', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `username` varchar(55) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `userId`, `username`, `access_token`, `expiry_date`) VALUES
(1, 'J7Sa5j8FYPGVXKp', 'emmallob', '$2y$10$DnjYCCtKl2.VR8C6hOSaS.CDnlpNk8p2tY85aqTnXpaTmjy0Zeu7K', '2020-04-30');

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
  `branch_color` varchar(32) DEFAULT NULL,
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

INSERT INTO `branches` (`id`, `branch_id`, `clientId`, `branch_type`, `branch_name`, `branch_color`, `location`, `branch_contact`, `branch_email`, `branch_logo`, `square_feet_area`, `recurring_expenses`, `fixed_expenses`, `status`, `deleted`) VALUES
(1, 'w9KT0OBztNEy', '3OKokdt8wveI', 'Warehouse', 'Emmallen Networks Outlet', 'badge-purple', '', '0550107770', 'emmallob14@gmail.com', NULL, NULL, 0.00, 0.00, '1', '0'),
(2, 'rgMbqG4aUyvF', '3OKokdt8wveI', 'Store', 'Madina - Zongo Junction Outlet', NULL, 'Madina', '0909885887', 'madinabranch@mail.com', 'assets/images/logo.png', NULL, 0.00, 0.00, '1', '0'),
(3, 'ojLs0gfAqimX', 'Y7CSuzFLDd6K', 'Warehouse', 'Helena Oduro&#39;s Fashion Shop', 'badge-purple', NULL, '0987654321', 'helenaoduro@mail.com', NULL, NULL, 0.00, 0.00, '1', '0');

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
  `clientId` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branchId` int(11) DEFAULT 1,
  `customer_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Argon',
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
  `gender` enum('Male','Female','M','F') COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `rating` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_log` datetime DEFAULT current_timestamp(),
  `preferred_payment_type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` enum('MODIFIED','UPLOADED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UPLOADED',
  `status` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `clientId`, `branchId`, `customer_id`, `source`, `title`, `firstname`, `lastname`, `role`, `phone_1`, `phone_2`, `email`, `date_of_birth`, `user_image`, `description`, `residence`, `postal_address`, `country`, `nationality`, `gender`, `city`, `user_type`, `website`, `industry`, `balance`, `rating`, `owner_id`, `date_log`, `preferred_payment_type`, `comments`, `state`, `status`) VALUES
(1, '3OKokdt8wveI', 1, 'POS732143158965', 'Evelyn', 'Mr', 'Asamoah', 'John', NULL, '0908877859', NULL, 'asamoahjohn@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'J7Sa5j8FYPGVXKp', '2020-04-10 21:11:14', NULL, NULL, 'UPLOADED', '1'),
(2, '3OKokdt8wveI', 1, 'POS499688133725', 'Evelyn', 'Mr', 'Amoah', 'Danso', NULL, '0550107770', NULL, 'emmallob14@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3tYcj10KTedBJ6XsiVqnkN94rFlL', '2020-04-10 21:27:47', NULL, NULL, 'UPLOADED', '1'),
(3, '3OKokdt8wveI', 1, 'WalkIn', 'Argon', NULL, 'WalkIn', 'Customer', NULL, NULL, NULL, NULL, NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-11 09:13:25', NULL, NULL, 'UPLOADED', '1'),
(5, '3OKokdt8wveI', 1, '1', 'Argon', 'Mr', 'Asamoah', 'John', NULL, '0908877859', NULL, 'asamoahjohn@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Accra', NULL, NULL, NULL, NULL, '   ', NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-12 17:36:25', NULL, NULL, 'UPLOADED', '1'),
(6, '3OKokdt8wveI', 1, '2', 'Argon', 'Mr', 'Joshua Commey', 'Mental Health', NULL, '0550107770', NULL, 'joshcommey@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'JHS 2', NULL, NULL, NULL, NULL, '   ', NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-12 17:36:25', NULL, NULL, 'UPLOADED', '1');

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

--
-- Dumping data for table `data_monitoring`
--

INSERT INTO `data_monitoring` (`id`, `clientId`, `data_type`, `unique_id`, `data_set`, `user_id`, `user_agent`, `date_log`) VALUES
(2, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:25:02'),
(3, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:30:47'),
(4, 3, 'branches', 'w9KT0OBztNEy', '{\"id\":\"1\",\"branch_id\":\"w9KT0OBztNEy\",\"clientId\":\"3OKokdt8wveI\",\"branch_type\":\"Warehouse\",\"branch_name\":\"Emmallen Networks\",\"branch_color\":\"badge-purple\",\"location\":\"\",\"branch_contact\":\"0550107770\",\"branch_email\":\"emmallob14@gmail.com\",\"branch_logo\":null,\"square_feet_area\":null,\"recurring_expenses\":\"0.00\",\"fixed_expenses\":\"0.00\",\"status\":\"1\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:33:34'),
(5, 3, 'customers', 'POS499688133725', '{\"id\":\"2\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"POS499688133725\",\"source\":\"Evelyn\",\"title\":\"Mr\",\"firstname\":\"Amoah\",\"lastname\":\"Danso\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":null,\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":null,\"user_type\":null,\"website\":null,\"industry\":null,\"company_id\":null,\"balance\":null,\"lead_id\":null,\"relationship_manager\":null,\"department\":null,\"rating\":null,\"owner_id\":\"3tYcj10KTedBJ6XsiVqnkN94rFlL\",\"date_log\":\"2020-04-10 21:27:47\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:38:32'),
(6, 3, 'products', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"source\":\"db\",\"product_id\":\"PDTX2IazQq8ycvT\",\"category_id\":\"PCAT85769\",\"product_title\":\"HP Laptop\",\"product_type_id\":null,\"product_description\":\"This is an HP Laptop that i want to add to the Store\",\"product_image\":\"assets\\/images\\/products\\/default.png\",\"product_price\":\"1600.00\",\"cost_price\":\"1200.00\",\"performance_rating\":null,\"date_added\":\"2020-04-10 21:19:51\",\"expiry_date\":null,\"added_by\":\"J7Sa5j8FYPGVXKp\",\"status\":\"1\",\"threshold\":\"5\",\"quantity\":\"36\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:43:18'),
(7, 3, 'products', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"source\":\"db\",\"product_id\":\"PDTX2IazQq8ycvT\",\"category_id\":\"PCAT85769\",\"product_title\":\"HP Laptop\",\"product_type_id\":null,\"product_description\":\"This is an HP Laptop that i want to add to the Store\",\"product_image\":\"assets\\/images\\/products\\/default.png\",\"product_price\":\"1600.00\",\"cost_price\":\"1200.00\",\"performance_rating\":null,\"date_added\":\"2020-04-10 21:19:51\",\"expiry_date\":null,\"added_by\":\"J7Sa5j8FYPGVXKp\",\"status\":\"1\",\"threshold\":\"5\",\"quantity\":\"36\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:44:48'),
(8, 3, 'products', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"source\":\"db\",\"product_id\":\"PDTX2IazQq8ycvT\",\"category_id\":\"PCAT85769\",\"product_title\":\"HP Laptop\",\"product_type_id\":null,\"product_description\":\"This is an HP Laptop that i want to add to the Store\",\"product_image\":\"assets\\/images\\/products\\/default.png\",\"product_price\":\"1600.00\",\"cost_price\":\"1200.00\",\"performance_rating\":null,\"date_added\":\"2020-04-10 21:19:51\",\"expiry_date\":null,\"added_by\":\"J7Sa5j8FYPGVXKp\",\"status\":\"1\",\"threshold\":\"5\",\"quantity\":\"36\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:55:20'),
(9, 3, 'products', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"source\":\"db\",\"product_id\":\"PDTX2IazQq8ycvT\",\"category_id\":\"PCAT85769\",\"product_title\":\"HP Laptop\",\"product_type_id\":null,\"product_description\":\"This is an HP Laptop that i want to add to the Store\",\"product_image\":\"assets\\/images\\/products\\/default.png\",\"product_price\":\"1600.00\",\"cost_price\":\"1200.00\",\"performance_rating\":null,\"date_added\":\"2020-04-10 21:19:51\",\"expiry_date\":\"2020-04-11\",\"added_by\":\"J7Sa5j8FYPGVXKp\",\"status\":\"1\",\"threshold\":\"5\",\"quantity\":\"36\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 08:55:36'),
(10, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:43:07'),
(11, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:43:59'),
(12, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:44:14'),
(13, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:44:56'),
(14, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"purple\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:45:16'),
(15, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:45:33'),
(16, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-month\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"purple\",\"setup_info\":\"{\\\"type\\\":\\\"basic\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"2\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 11:47:33'),
(17, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"purple\",\"setup_info\":\"{\\\"type\\\":\\\"alpha\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"5\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 17:53:04'),
(18, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-indigo text-white no-border\\\",\\\"bg_color_code\\\":\\\"#5603ad\\\",\\\"bg_color_light\\\":\\\"#5e72e4\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"indigo\",\"setup_info\":\"{\\\"type\\\":\\\"alpha\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"5\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 17:53:47'),
(19, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"all-time\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-gradient-darker text-white no-border\\\",\\\"bg_color_code\\\":\\\"#000\\\",\\\"bg_color_light\\\":\\\"#3c3939\\\",\\\"btn_outline\\\":\\\"btn-outline-dark\\\"}\",\"theme_color_code\":\"darker\",\"setup_info\":\"{\\\"type\\\":\\\"alpha\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"5\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 18:19:41'),
(20, 3, 'products', '5', '{\"id\":\"5\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"source\":\"db\",\"product_id\":\"PD00012\",\"category_id\":\"PCAT63294\",\"product_title\":\"Hello world\",\"product_type_id\":null,\"product_description\":\"\",\"product_image\":\"assets\\/images\\/products\\/default.png\",\"product_price\":\"6.00\",\"cost_price\":\"2.00\",\"performance_rating\":null,\"date_added\":\"2020-04-12 17:32:04\",\"expiry_date\":\"0000-00-00\",\"added_by\":\"J7Sa5j8FYPGVXKp\",\"status\":\"1\",\"threshold\":\"10\",\"quantity\":\"80\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 17:32:45'),
(21, 3, 'customers', '2', '{\"id\":\"6\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"2\",\"source\":\"Argon\",\"title\":null,\"firstname\":\"Joshua Commey\",\"lastname\":\"Mental Health\",\"role\":null,\"phone_1\":null,\"phone_2\":null,\"email\":\"0099999090 joshcommey@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"JHS 2\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 20:55:40'),
(22, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":null,\"firstname\":\"New Added here\",\"lastname\":\"Maternal And Child Health\",\"role\":null,\"phone_1\":null,\"phone_2\":null,\"email\":\"0987654321 thisnewstuden@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"Class 4\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 20:55:55'),
(23, 3, 'customers', '1', '{\"id\":\"5\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"1\",\"source\":\"Argon\",\"title\":null,\"firstname\":\"Emmanuel Obeng\",\"lastname\":\"Adult Health\",\"role\":null,\"phone_1\":null,\"phone_2\":null,\"email\":\"0550107770 emmallob14@gmail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"SHS 1\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 21:00:13'),
(24, 3, 'customers', '1', '{\"id\":\"5\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"1\",\"source\":\"Argon\",\"title\":\"Mr\",\"firstname\":\"Asamoah\",\"lastname\":\"John\",\"role\":null,\"phone_1\":\"0908877859\",\"phone_2\":null,\"email\":\"asamoahjohn@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"Accra\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 21:01:44'),
(25, 3, 'branches', 'w9KT0OBztNEy', '{\"id\":\"1\",\"branch_id\":\"w9KT0OBztNEy\",\"clientId\":\"3OKokdt8wveI\",\"branch_type\":\"Warehouse\",\"branch_name\":\"Emmallen Networks Branch\",\"branch_color\":\"badge-purple\",\"location\":\"\",\"branch_contact\":\"0550107770\",\"branch_email\":\"emmallob14@gmail.com\",\"branch_logo\":null,\"square_feet_area\":null,\"recurring_expenses\":\"0.00\",\"fixed_expenses\":\"0.00\",\"status\":\"1\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-13 05:40:10'),
(26, 3, 'branches', 'rgMbqG4aUyvF', '{\"id\":\"2\",\"branch_id\":\"rgMbqG4aUyvF\",\"clientId\":\"3OKokdt8wveI\",\"branch_type\":\"Store\",\"branch_name\":\"Madina - Zongo Junction Branch\",\"branch_color\":null,\"location\":\"Madina\",\"branch_contact\":\"0909885887\",\"branch_email\":\"madinabranch@mail.com\",\"branch_logo\":\"assets\\/images\\/logo.png\",\"square_feet_area\":null,\"recurring_expenses\":\"0.00\",\"fixed_expenses\":\"0.00\",\"status\":\"0\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-13 05:40:17'),
(27, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit,MoMo\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"allow_return_days\":\"30\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"trial\\\",\\\"verified\\\":0,\\\"setup_date\\\":\\\"2020-04-06\\\",\\\"expiry_date\\\":\\\"2020-04-20\\\",\\\"outlets\\\":\\\"5\\\"}\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:04:19'),
(28, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mr\",\"firstname\":\"New Added here\",\"lastname\":\"Maternal And Child Health\",\"role\":null,\"phone_1\":\"0987654321\",\"phone_2\":null,\"email\":\"thisnewstuden@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"Class 4\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:20:08'),
(29, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mrs\",\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"090876657\",\"phone_2\":null,\"email\":\"walkincustomer@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"Accra\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:24:30'),
(30, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":null,\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:24:33'),
(31, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mrs\",\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:24:51'),
(32, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mrs\",\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:25:49'),
(33, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mrs\",\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:26:19'),
(34, 3, 'customers', '3', '{\"id\":\"7\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"3\",\"source\":\"Argon\",\"title\":\"Mrs\",\"firstname\":\"WalkIn\",\"lastname\":\"Customer\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-14 18:27:16'),
(35, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit,MoMo\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"yes\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"allow_return_days\":\"30\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-green text-white no-border\\\",\\\"bg_color_code\\\":\\\"#24a46d\\\",\\\"bg_color_light\\\":\\\"#2dce89\\\",\\\"btn_outline\\\":\\\"btn-outline-success\\\"}\",\"theme_color_code\":\"green\",\"setup_info\":\"{\\\"type\\\":\\\"trial\\\",\\\"verified\\\":0,\\\"setup_date\\\":\\\"2020-04-06\\\",\\\"expiry_date\\\":\\\"2020-04-20\\\",\\\"outlets\\\":\\\"5\\\"}\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-14 22:01:59'),
(36, 3, 'customers', '6', 'null', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 16:47:42'),
(37, 3, 'customers', '6', 'null', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 18:03:28'),
(38, 3, 'customers', '2', '{\"id\":\"6\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"2\",\"source\":\"Argon\",\"title\":\"Mr\",\"firstname\":\"Joshua Commey\",\"lastname\":\"Mental Health\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"joshcommey@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"JHS 2\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 18:05:16'),
(39, 3, 'customers', '2', '{\"id\":\"6\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"customer_id\":\"2\",\"source\":\"Argon\",\"title\":\"Mr\",\"firstname\":\"Joshua Commey\",\"lastname\":\"Mental Health\",\"role\":null,\"phone_1\":\"\",\"phone_2\":null,\"email\":\"joshcommey@mail.com\",\"date_of_birth\":null,\"user_image\":\"assets\\/images\\/users\\/user-4.jpg\",\"description\":null,\"residence\":\"JHS 2\",\"postal_address\":null,\"country\":null,\"nationality\":null,\"gender\":null,\"city\":\"\\u00a0 \\u00a0\",\"user_type\":null,\"website\":null,\"industry\":null,\"balance\":null,\"rating\":null,\"owner_id\":null,\"date_log\":\"2020-04-12 17:36:25\",\"preferred_payment_type\":null,\"comments\":null,\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 18:05:25'),
(40, 3, 'branches', 'w9KT0OBztNEy', '{\"id\":\"1\",\"branch_id\":\"w9KT0OBztNEy\",\"clientId\":\"3OKokdt8wveI\",\"branch_type\":\"Warehouse\",\"branch_name\":\"Emmallen Networks Outlet\",\"branch_color\":\"badge-purple\",\"location\":\"\",\"branch_contact\":\"0550107770\",\"branch_email\":\"emmallob14@gmail.com\",\"branch_logo\":null,\"square_feet_area\":null,\"recurring_expenses\":\"0.00\",\"fixed_expenses\":\"0.00\",\"status\":\"1\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 18:54:18'),
(41, 3, 'expenses-category', '2', '{\"id\":\"2\",\"clientId\":\"3OKokdt8wveI\",\"name\":\"Utility Bills\",\"description\":\"This is the expense category for utility bills\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 20:50:18'),
(42, 3, 'expenses-category', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"name\":\"Salary\",\"description\":\"This is the salary of all employees\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 20:50:28'),
(43, 3, 'expenses-category', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"name\":\"Salary\",\"description\":\"This is the salary of all employees. That is good and fair.\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 21:38:09'),
(44, 3, 'expenses-category', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"name\":\"Salary\",\"description\":\"This is the salary of all employees. That is good and fair&#39;d. That is what i hate ooo&#39;h please. Do well to say the best &#39;&#39;\\\"of him\\\" thank you\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-15 21:39:52'),
(45, 3, 'expenses', '2', '{\"id\":\"2\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"category_id\":\"1\",\"start_date\":\"2020-04-15\",\"end_date\":null,\"amount\":\"5400.00\",\"tax\":\"100.00\",\"payment_type\":\"cash\",\"description\":\"This is a dummy expense\",\"created_by\":\"J7Sa5j8FYPGVXKp\",\"date_log\":\"2020-04-16 00:19:48\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-16 23:23:21'),
(46, 3, 'expenses', '1', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"category_id\":\"1\",\"start_date\":\"2020-04-15\",\"end_date\":null,\"amount\":\"5400.00\",\"tax\":\"100.00\",\"payment_type\":\"cash\",\"description\":\"This is a dummy expense\",\"created_by\":\"J7Sa5j8FYPGVXKp\",\"date_log\":\"2020-04-15 23:51:05\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-16 23:25:53'),
(47, 3, 'expenses', '2', '{\"id\":\"2\",\"clientId\":\"3OKokdt8wveI\",\"branchId\":\"1\",\"category_id\":\"1\",\"start_date\":\"2020-04-30\",\"end_date\":null,\"amount\":\"5400.00\",\"tax\":\"100.00\",\"payment_type\":\"cash\",\"description\":\"This is a dummy expense. And i am trying to edit the content. I am confident it will work well as i am expecting it to work\",\"created_by\":\"J7Sa5j8FYPGVXKp\",\"date_log\":\"2020-04-16 00:19:48\",\"status\":\"1\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-16 23:26:05'),
(48, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit,MoMo\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"this-week\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"yes\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"allow_return_days\":\"30\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-purple text-white no-border\\\",\\\"bg_color_code\\\":\\\"#8965e0\\\",\\\"bg_color_light\\\":\\\"#97a5f1\\\",\\\"btn_outline\\\":\\\"btn-outline-primary\\\"}\",\"theme_color_code\":\"purple\",\"setup_info\":\"{\\\"type\\\":\\\"trial\\\",\\\"verified\\\":0,\\\"setup_date\\\":\\\"2020-04-06\\\",\\\"expiry_date\\\":\\\"2020-04-20\\\",\\\"outlets\\\":\\\"5\\\"}\",\"deleted\":\"0\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | ::1', '2020-04-17 00:16:06');

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
(1, '3OKokdt8wveI', '1', 'general', '3OKokdt8wveI', '{\"recipients_list\":[{\"fullname\":\"Emmanuel Obeng\",\"email\":\"emmallob14@gmail.com\",\"customer_id\":\"J7Sa5j8FYPGVXKp\",\"branchId\":\"1\"}]}', 'Setup - Emmallen Networks \\[Argon POS\\]', 'Hello Emmanuel Obeng,\nThank you for registering your store <strong>Emmallen Networks</strong> with Argon POS. We are pleased to have you join and experiment with our platform.\n\nOne of our personnel will get in touch shortly to assist you with additional setup processes that is required to aid you quick start the usage of the application.\n\nIn the mean time please use the following credentials to login into the system.\n\n<strong>Username:</strong> emmallob14@gmail.com\n<a href=\'https://dev.localhost.com/pos/verify/act?tk=RLdSZBDyMwl9aNniVz86t2kUjxOCTKWsFcEprf7eQohqPGJu\'><strong>Click Here</strong></a> to verify your Email Address\n\n', '2020-04-10 20:59:51', '0', 'J7Sa5j8FYPGVXKp', NULL, '0'),
(2, 'Y7CSuzFLDd6K', '3', 'general', 'Y7CSuzFLDd6K', '{\"recipients_list\":[{\"fullname\":\"Helena Oduro\",\"email\":\"helenaoduro@mail.com\",\"customer_id\":\"JmtzvChyWXs0ecj\",\"branchId\":\"3\"}]}', 'Setup - Helena Oduro&#39;s Fashion Shop \\[Argon POS\\]', 'Hello Helena Oduro,\nThank you for registering your store <strong>Helena Oduro&#39;s Fashion Shop</strong> with Argon POS. We are pleased to have you join and experiment with our platform.\n\nOne of our personnel will get in touch shortly to assist you with additional setup processes that is required to aid you quick start the usage of the application.\n\nIn the mean time please use the following credentials to login into the system.\n\n<strong>Username:</strong> helenaoduro@mail.com\n<a href=\'https://dev.localhost.com/pos/verify/act?tk=FCvt2emE1UuhqD8fRdxbwWp7YOi9gnzj6IJcAVMXTlBaZHksPLGS0y\'><strong>Click Here</strong></a> to verify your Email Address\n\n', '2020-04-12 00:58:11', '0', 'JmtzvChyWXs0ecj', NULL, '0'),
(6, '3OKokdt8wveI', '1', 'invoice', 'POS2020040100041', '{\"recipients_list\":[{\"fullname\":\"WalkIn Customer\",\"email\":\"emmallob14@gmail.com\",\"customer_id\":\"WalkIn\",\"branchId\":\"1\"}]}', NULL, NULL, '2020-04-15 19:10:03', '0', 'J7Sa5j8FYPGVXKp', NULL, '0');

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

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `clientId` varchar(25) DEFAULT NULL,
  `branchId` varchar(25) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount` double(12,2) NOT NULL DEFAULT 0.00,
  `tax` double(12,2) NOT NULL DEFAULT 0.00,
  `payment_type` enum('cash','bank','cheque') DEFAULT 'cash',
  `description` varchar(1000) DEFAULT NULL,
  `created_by` varchar(32) NOT NULL DEFAULT 'NULL',
  `date_log` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `clientId`, `branchId`, `category_id`, `start_date`, `end_date`, `amount`, `tax`, `payment_type`, `description`, `created_by`, `date_log`, `status`) VALUES
(1, '3OKokdt8wveI', '1', 1, '2020-04-15', NULL, 5400.00, 100.00, 'cash', 'This is a dummy expense. Edited version here', 'J7Sa5j8FYPGVXKp', '2020-04-15 23:51:05', '1'),
(2, '3OKokdt8wveI', '1', 2, '2020-04-30', NULL, 5400.00, 100.00, 'cash', 'This is a dummy expense. And i am trying to edit the content. I am confident it will work well as i am expecting it to work', 'J7Sa5j8FYPGVXKp', '2020-04-16 00:19:48', '1');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_category`
--

CREATE TABLE `expenses_category` (
  `id` int(11) NOT NULL,
  `clientId` varchar(32) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses_category`
--

INSERT INTO `expenses_category` (`id`, `clientId`, `name`, `description`, `status`) VALUES
(1, '3OKokdt8wveI', 'Salary', 'This is the salary of all employees. That is good and fair&#39;d. That is what i hate ooo&#39;h please. Do well to say the best &#39;&#39;\"of him\" thank you. This is what i wanted to prevent', '1'),
(2, '3OKokdt8wveI', 'Utility Bills', 'This is the expense category for utility bills. This is an edit.', '1');

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
(1, 'PDTX2IazQq8ycvT', 10, '2020-04-10 21:20:45', NULL, '1600.00', '2020-04-10 21:20:45', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 2),
(2, 'PDTX2IazQq8ycvT', 10, '2020-04-10 21:23:36', NULL, '1600.00', '2020-04-10 21:23:36', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 2),
(3, 'PD00016', 50, '2020-04-15 09:15:35', NULL, '25.00', '2020-04-15 09:15:35', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Argon',
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
  `expiry_date` date DEFAULT NULL,
  `added_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '1',
  `threshold` int(11) DEFAULT 7,
  `quantity` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `clientId`, `branchId`, `source`, `product_id`, `category_id`, `product_title`, `product_type_id`, `product_description`, `product_image`, `product_price`, `cost_price`, `performance_rating`, `date_added`, `expiry_date`, `added_by`, `status`, `threshold`, `quantity`) VALUES
(1, '3OKokdt8wveI', 1, 'db', 'PDT00012', 'PCAT85769', 'HP Laptop', NULL, 'This is an HP Laptop that i want to add to the Store', 'assets/images/products/default.png', 1600.00, '1200.00', NULL, '2020-04-10 21:19:51', '2020-08-27', 'J7Sa5j8FYPGVXKp', '1', 5, 40),
(2, '3OKokdt8wveI', 2, 'db', 'PDT00012', 'PCAT85769', 'HP Laptop', NULL, 'This is an HP Laptop that i want to add to the Store', 'assets/images/products/default.png', 1600.00, '1200.00', NULL, '2020-04-10 21:23:36', NULL, 'J7Sa5j8FYPGVXKp', '1', 5, 30),
(3, 'Y7CSuzFLDd6K', 3, 'db', 'PDT00012', 'PC00027', 'The product test', NULL, '', 'assets/images/products/default.png', 350.00, '200.00', NULL, '2020-04-12 16:14:22', '0000-00-00', 'JmtzvChyWXs0ecj', '1', 10, 95),
(4, 'Y7CSuzFLDd6K', 3, 'db', 'PD00023', 'PC010001', 'Hello product title again', NULL, '', 'assets/images/products/J4QHk8cCoBWP59TKtZsL2iyqI.jpg', 200.00, '100.00', NULL, '2020-04-12 16:22:34', '0000-00-00', 'JmtzvChyWXs0ecj', '1', 20, 115),
(5, '3OKokdt8wveI', 1, 'db', 'PD00012', 'PCAT63294', 'Hello world', NULL, '', 'assets/images/products/default.png', 4.00, '2.00', NULL, '2020-04-12 17:32:04', '0000-00-00', 'J7Sa5j8FYPGVXKp', '1', 10, 100),
(6, '3OKokdt8wveI', 1, 'db', 'PD00015', 'PCAT63294', 'Book Templates', NULL, '', 'assets/images/products/default.png', 350.00, '290.00', NULL, '2020-04-13 05:12:46', '0000-00-00', 'J7Sa5j8FYPGVXKp', '1', 5, 40),
(7, '3OKokdt8wveI', 2, 'db', 'PD00016', 'PCAT63294', 'Vaseline Cream', NULL, '', 'assets/images/products/default.png', 25.00, '12.00', NULL, '2020-04-15 09:10:42', '2020-06-30', 'J7Sa5j8FYPGVXKp', '1', 10, 150),
(8, '3OKokdt8wveI', 1, 'Argon', 'PD00016', 'PCAT63294', 'Vaseline Cream', NULL, '', 'assets/images/products/default.png', 25.00, '12.00', NULL, '2020-04-15 09:15:35', '2020-06-30', 'J7Sa5j8FYPGVXKp', '1', 10, 36);

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
(1, '3OKokdt8wveI', 'PCAT63294', 'Fashion'),
(2, '3OKokdt8wveI', 'PCAT85769', 'Electronics'),
(3, '3OKokdt8wveI', 'PCAT92358', 'Furniture Works'),
(4, 'Y7CSuzFLDd6K', 'PC010001', 'Fashion'),
(6, 'Y7CSuzFLDd6K', 'PC24', 'Electronics'),
(7, 'Y7CSuzFLDd6K', 'PC00026', 'Furniture'),
(8, 'Y7CSuzFLDd6K', 'PC00027', 'Office Wares');

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
(1, '3OKokdt8wveI', '1', 'APfDVY1svECT', 1, '1200.00', '1600.00', 0, 50, 50, 5, 'J7Sa5j8FYPGV', '2020-04-10 21:19:51'),
(2, '3OKokdt8wveI', '2', 'HtoVnCgbFYIz', 2, '1200.00', '1600.00', 0, 10, 10, 5, 'J7Sa5j8FYPGV', '2020-04-10 21:23:36'),
(3, 'Y7CSuzFLDd6K', '3', 'pDMfFLC5rPKg', 3, '200.00', '350.00', 0, 100, 100, 10, 'JmtzvChyWXs0', '2020-04-12 16:14:22'),
(4, 'Y7CSuzFLDd6K', '3', '2uG61T079IFs', 4, '100.00', '200.00', 0, 120, 120, 20, 'JmtzvChyWXs0', '2020-04-12 16:22:34'),
(5, '3OKokdt8wveI', '1', 'HtPTEOQcJosw', 5, '2.00', '6.00', 0, 80, 80, 10, 'J7Sa5j8FYPGV', '2020-04-12 17:32:04'),
(6, '3OKokdt8wveI', '1', 'vniLIpVakWy4', 6, '290.00', '350.00', 0, 50, 50, 5, 'J7Sa5j8FYPGV', '2020-04-13 05:12:46'),
(7, '3OKokdt8wveI', '1', 'KBDJ9AFwuXrVQMSWkYb5aHGhn', 5, '2.00', '4.00', 14, 20, 34, 10, 'J7Sa5j8FYPGV', '2020-04-13 05:37:29'),
(8, '3OKokdt8wveI', '2', 'komIVEuTj8Zs', 7, '12.00', '25.00', 0, 200, 200, 10, 'J7Sa5j8FYPGV', '2020-04-15 09:10:42'),
(9, '3OKokdt8wveI', '2', 'n4w6c0KGde3gfLBoAEjFamYWu', 2, '1200.00', '1600.00', 10, 20, 30, 5, 'J7Sa5j8FYPGV', '2020-04-15 09:11:59'),
(10, '3OKokdt8wveI', '1', 'xlmRunsDQvye', 8, '12.00', '25.00', 0, 50, 50, 10, 'J7Sa5j8FYPGV', '2020-04-15 09:15:35'),
(11, '3OKokdt8wveI', '1', 'rVvWedY9iCjX5oOqaNJUh314Z', 5, '2.00', '4.00', 0, 100, 100, 10, 'J7Sa5j8FYPGV', '2020-04-17 01:25:30'),
(12, '3OKokdt8wveI', '1', 'I5rwxvkjGCAtSW8e1P6EBna3y', 1, '1200.00', '1600.00', 7, 33, 40, 5, 'J7Sa5j8FYPGV', '2020-04-17 01:25:57'),
(13, '3OKokdt8wveI', '1', 'I5rwxvkjGCAtSW8e1P6EBna3y', 6, '290.00', '350.00', 7, 33, 40, 5, 'J7Sa5j8FYPGV', '2020-04-17 01:25:57');

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
(1, '3OKokdt8wveI', 1, 'ORD20200400002', 'POS499688133725', 'Order', 'GH¢', 3200.00, 50.00, 3150.00, '2020-04-11 10:03:09', 'pending', 'J7Sa5j8FYPGVXKp', '0');

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
(1, NULL, 'ORD20200400002', 1, 2, 1600.00, 3200.00, '2020-04-11 10:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `clientId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Evelyn',
  `mode` enum('online','offline') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'online',
  `state` enum('MODIFIED','UPLOADED') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UPLOADED',
  `branchId` int(11) NOT NULL DEFAULT 1,
  `unique_id` varchar(65) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `revert` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `payment_date` datetime DEFAULT NULL,
  `payment_type` enum('cash','card','credit','MoMo') COLLATE utf8_unicode_ci DEFAULT 'cash',
  `transaction_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `clientId`, `source`, `mode`, `state`, `branchId`, `unique_id`, `order_id`, `customer_id`, `ordered_by_id`, `customer_type`, `sale_lead_id`, `recorded_by`, `currency`, `credit_sales`, `order_discount`, `order_amount_balance`, `overall_order_amount`, `order_amount_paid`, `order_comments`, `order_date`, `order_status`, `log_date`, `deleted`, `revert`, `payment_date`, `payment_type`, `transaction_id`) VALUES
(1, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100002', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1600.00, 1600.00, NULL, '2020-04-10 21:24:29', 'confirmed', '2020-04-10 21:24:29', '0', '0', NULL, 'cash', '428152359978'),
(2, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100003', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 3200.00, 3200.00, NULL, '2020-04-10 21:25:40', 'confirmed', '2020-04-10 21:25:40', '0', '0', NULL, 'cash', '732869344567'),
(3, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100004', 'POS499688133725', NULL, 'customer', NULL, '3tYcj10KTedBJ6XsiVqnkN94rFlL', 'GH¢', '0', 0.00, 20.00, 1600.00, 1600.00, NULL, '2020-04-10 21:31:25', 'confirmed', '2020-04-10 21:31:25', '0', '0', NULL, 'cash', '927854862141'),
(4, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100005', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 100.00, 0.00, 1600.00, 1500.00, NULL, '2020-04-11 17:59:12', 'confirmed', '2020-04-11 17:59:12', '0', '0', NULL, 'cash', '256563784171'),
(5, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100006', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:29:56', 'cancelled', '2020-04-12 10:29:56', '1', '0', '2020-04-12 12:41:56', 'MoMo', '811962648739'),
(6, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100007', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:36:58', 'cancelled', '2020-04-12 10:36:58', '0', '0', '2020-04-12 10:37:08', 'MoMo', '645195628417'),
(7, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100008', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:40:13', 'cancelled', '2020-04-12 10:40:13', '0', '0', '2020-04-12 10:41:05', 'MoMo', '944695526782'),
(8, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100009', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:00:54', 'cancelled', '2020-04-12 11:00:54', '0', '0', '2020-04-12 11:01:56', 'MoMo', '256329891417'),
(9, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100010', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:03:22', 'cancelled', '2020-04-12 11:03:22', '0', '0', '2020-04-12 11:03:41', 'MoMo', '924173539867'),
(10, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100011', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:03:48', 'cancelled', '2020-04-12 11:03:48', '1', '0', NULL, 'MoMo', '714932958567'),
(11, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100012', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:20:19', 'cancelled', '2020-04-12 11:20:19', '0', '0', '2020-04-12 11:20:43', 'MoMo', '767499831325'),
(12, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100013', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:44:07', 'cancelled', '2020-04-12 11:44:07', '1', '0', NULL, 'MoMo', '599573678112'),
(13, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100014', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:45:37', 'cancelled', '2020-04-12 11:45:37', '0', '0', '2020-04-12 11:46:26', 'MoMo', '612823579484'),
(14, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100015', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -17600.00, 17600.00, 17600.00, NULL, '2020-04-12 11:49:21', 'cancelled', '2020-04-12 11:49:21', '0', '0', '2020-04-12 11:49:31', 'MoMo', '724635678298'),
(15, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100016', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 12:22:22', 'pending', '2020-04-12 12:22:22', '0', '0', NULL, 'MoMo', '543127858629'),
(16, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100017', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 12:23:12', 'cancelled', '2020-04-12 12:23:12', '0', '0', '2020-04-12 12:24:23', 'MoMo', '757254669839'),
(17, 'Y7CSuzFLDd6K', 'Argon', 'online', 'UPLOADED', 3, NULL, 'POS2020040300018', 'WalkIn', NULL, 'customer', NULL, 'JmtzvChyWXs0ecj', 'GH¢', '0', 0.00, 20.00, 2750.00, 2750.00, NULL, '2020-04-12 16:31:07', 'confirmed', '2020-04-12 16:31:07', '0', '0', NULL, 'cash', '685132447975'),
(18, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100019', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-04-12 17:33:21', 'confirmed', '2020-04-12 17:33:21', '0', '0', '2020-04-12 17:33:45', 'MoMo', '759931145362'),
(19, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100020', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 4.00, 1616.00, 1616.00, NULL, '2020-04-12 18:54:08', 'confirmed', '2020-04-12 18:54:08', '0', '0', NULL, 'cash', '947468253511'),
(20, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100021', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 192.48, 1.48, 6416.00, 6223.52, NULL, '2020-04-12 19:59:00', 'confirmed', '2020-04-12 19:59:00', '0', '0', NULL, 'cash', '448153195679'),
(21, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100022', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 40.00, 40.00, NULL, '2020-04-12 19:59:30', 'confirmed', '2020-04-12 19:59:30', '0', '0', NULL, 'cash', '296572168983'),
(22, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100023', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1628.00, 1628.00, NULL, '2020-04-13 04:56:37', 'confirmed', '2020-04-13 04:56:37', '0', '0', NULL, 'cash', '571922634398'),
(23, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100024', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 2960.00, 2960.00, NULL, '2020-04-13 05:25:30', 'confirmed', '2020-04-13 05:25:30', '0', '0', NULL, 'cash', '143734819275'),
(24, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV8796259791650', 'POS499688133725', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 20.00, 26.00, 1954.00, 1960.00, NULL, '2020-04-13 21:37:34', 'confirmed', '2020-04-13 21:40:13', '0', '0', NULL, 'cash', '778584178346'),
(25, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV8981733221171', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1604.00, 1604.00, NULL, '2020-04-13 21:37:08', 'confirmed', '2020-04-13 21:40:13', '0', '0', NULL, 'cash', '127979033017'),
(26, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100027', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-04-14 17:28:43', 'cancelled', '2020-04-14 17:28:43', '0', '0', '2020-04-14 17:29:02', 'MoMo', '367796183124'),
(27, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100028', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-04-14 17:29:12', 'confirmed', '2020-04-14 17:29:12', '0', '0', '2020-04-14 17:29:31', 'MoMo', '963912871472'),
(28, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100029', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -4.00, 4.00, 4.00, NULL, '2020-04-14 17:31:05', 'confirmed', '2020-04-14 17:31:05', '0', '0', '2020-04-14 17:31:29', 'MoMo', '421886393459'),
(29, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100030', '2', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 704.00, 704.00, NULL, '2020-04-14 17:37:57', 'confirmed', '2020-04-14 17:37:57', '0', '0', NULL, 'cash', '382741751296'),
(30, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100031', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 17:38:16', 'confirmed', '2020-04-14 17:38:16', '0', '0', NULL, 'cash', '566897382271'),
(31, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100032', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 4.00, 4.00, NULL, '2020-04-14 17:58:37', 'confirmed', '2020-04-14 17:58:37', '0', '0', NULL, 'cash', '639227416538'),
(32, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100033', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 17:58:54', 'confirmed', '2020-04-14 17:58:54', '0', '0', NULL, 'cash', '372615513674'),
(33, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100034', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1600.00, 1600.00, NULL, '2020-04-14 18:01:52', 'confirmed', '2020-04-14 18:01:52', '0', '0', NULL, 'cash', '288231959467'),
(34, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100035', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 18:03:03', 'confirmed', '2020-04-14 18:03:03', '0', '0', NULL, 'cash', '896698542251'),
(35, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100036', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1950.00, 1950.00, NULL, '2020-04-14 18:03:54', 'confirmed', '2020-04-14 18:03:54', '0', '0', NULL, 'cash', '247726393854'),
(36, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100037', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 18:04:32', 'confirmed', '2020-04-14 18:04:32', '0', '0', NULL, 'cash', '356167231897'),
(37, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100038', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1954.00, 1954.00, NULL, '2020-04-14 18:07:29', 'confirmed', '2020-04-14 18:07:29', '0', '0', NULL, 'cash', '621932855744'),
(38, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100039', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '1', 0.00, 350.00, 350.00, 350.00, NULL, '2020-04-14 18:08:43', 'confirmed', '2020-04-14 18:08:43', '0', '0', NULL, 'credit', '298844123915'),
(39, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100040', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 18:09:12', 'confirmed', '2020-04-14 18:09:12', '0', '0', NULL, 'cash', '526795461498'),
(40, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100041', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1950.00, 1950.00, NULL, '2020-04-14 18:10:11', 'confirmed', '2020-04-14 18:10:11', '0', '0', NULL, 'cash', '272934584668'),
(41, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100042', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 4.00, 4.00, NULL, '2020-04-14 18:11:21', 'confirmed', '2020-04-14 18:11:21', '0', '0', NULL, 'cash', '352971364874'),
(42, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100043', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 18:12:16', 'confirmed', '2020-04-14 18:12:16', '0', '0', NULL, 'cash', '514249385216'),
(43, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100044', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 97.70, 0.70, 1954.00, 1856.30, NULL, '2020-04-14 18:14:42', 'confirmed', '2020-04-14 18:14:42', '0', '0', NULL, 'cash', '343614828599'),
(44, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100045', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-14 23:05:45', 'confirmed', '2020-04-14 23:05:45', '0', '0', NULL, 'cash', '198596568442'),
(45, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100046', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1979.00, 1979.00, NULL, '2020-04-15 09:31:24', 'confirmed', '2020-04-15 09:31:24', '0', '0', NULL, 'cash', '516745323188'),
(46, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100047', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 100.00, 6.00, 1979.00, 1879.00, NULL, '2020-04-15 15:32:47', 'confirmed', '2020-04-15 15:32:47', '0', '0', NULL, 'cash', '351948667529'),
(48, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100049', '2', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 379.00, 379.00, NULL, '2020-04-16 15:36:34', 'confirmed', '2020-04-16 15:36:34', '0', '0', NULL, 'cash', '634594578132'),
(49, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100050', 'POS499688133725', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1979.00, 1979.00, NULL, '2020-04-16 15:37:09', 'confirmed', '2020-04-16 15:37:09', '0', '0', NULL, 'cash', '278829165435'),
(50, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100051', 'POS499688133725', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 100.00, 0.00, 1979.00, 1879.00, NULL, '2020-04-16 15:37:33', 'confirmed', '2020-04-16 15:37:33', '0', '0', NULL, 'cash', '213986584695'),
(51, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100052', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1604.00, 1604.00, NULL, '2020-04-16 16:01:35', 'confirmed', '2020-04-16 16:01:35', '0', '0', NULL, 'cash', '915826529863'),
(52, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100053', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 4.00, 4.00, NULL, '2020-04-16 16:27:30', 'confirmed', '2020-04-16 16:27:30', '0', '0', NULL, 'cash', '135298914427'),
(53, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100054', '2', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 354.00, 354.00, NULL, '2020-04-16 16:28:57', 'confirmed', '2020-04-16 16:28:57', '0', '0', NULL, 'cash', '253531489671'),
(54, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100055', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1604.00, 1604.00, NULL, '2020-04-16 17:04:19', 'confirmed', '2020-04-16 17:04:19', '0', '0', NULL, 'cash', '953148469767'),
(55, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100056', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 375.00, 375.00, NULL, '2020-04-16 17:04:34', 'confirmed', '2020-04-16 17:04:34', '0', '0', NULL, 'cash', '297625839463'),
(56, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV4746444401288', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1604.00, 1604.00, NULL, '2020-04-16 16:38:56', 'confirmed', '2020-04-16 17:05:25', '0', '0', NULL, 'cash', '143658491606'),
(57, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV7166064965038', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1604.00, 1604.00, NULL, '2020-04-16 16:56:33', 'confirmed', '2020-04-16 17:05:25', '0', '0', NULL, 'cash', '687889688680'),
(59, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100059', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '1', 0.00, 354.00, 354.00, 354.00, NULL, '2020-04-16 17:39:05', 'confirmed', '2020-04-16 17:39:05', '0', '0', NULL, 'credit', '854793962871'),
(60, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100061', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 2.00, 354.00, 354.00, NULL, '2020-04-16 17:42:08', 'confirmed', '2020-04-16 17:42:08', '0', '0', NULL, 'cash', '114256697833'),
(61, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100062', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1954.00, 1954.00, NULL, '2020-04-16 17:44:19', 'confirmed', '2020-04-16 17:44:19', '0', '0', NULL, 'cash', '349715289652'),
(62, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100063', 'POS499688133725', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '1', 0.00, 375.00, 375.00, 375.00, NULL, '2020-04-16 17:48:58', 'confirmed', '2020-04-16 17:48:58', '0', '0', NULL, 'credit', '489925363177'),
(63, '3OKokdt8wveI', 'Argon', 'online', 'UPLOADED', 1, NULL, 'POS2020040100064', '2', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 375.00, 375.00, NULL, '2020-04-16 17:50:11', 'confirmed', '2020-04-16 17:50:11', '0', '0', NULL, 'cash', '176641952549'),
(64, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV6425565167703', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 100.00, 0.00, 1975.00, 1875.00, NULL, '2020-04-16 23:51:19', 'confirmed', '2020-04-16 23:53:09', '0', '0', NULL, 'cash', '686907289401'),
(65, '3OKokdt8wveI', 'Evelyn', 'offline', 'UPLOADED', 1, NULL, 'INV9852905360378', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1975.00, 1975.00, NULL, '2020-04-16 23:46:22', 'confirmed', '2020-04-16 23:53:09', '0', '0', NULL, 'cash', '408549117899'),
(66, '3OKokdt8wveI', 'Argon', 'offline', 'UPLOADED', 1, NULL, 'POS4457414445000', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 120.00, 0.00, 1600.00, 1480.00, NULL, '2020-04-17 00:07:38', 'confirmed', '2020-04-17 00:08:59', '0', '0', NULL, 'cash', '643099726440'),
(67, '3OKokdt8wveI', 'Argon', 'offline', 'UPLOADED', 1, NULL, 'POS5684396286857', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 5.00, 25.00, 30.00, NULL, '2020-04-17 00:06:22', 'confirmed', '2020-04-17 00:08:59', '0', '0', NULL, 'cash', '362687615214'),
(68, '3OKokdt8wveI', 'Argon', 'offline', 'UPLOADED', 1, NULL, 'POS7819060463992', '1', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 375.00, 375.00, NULL, '2020-04-17 00:08:22', 'confirmed', '2020-04-17 00:08:59', '0', '0', NULL, 'cash', '039455525696');

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
  `product_returns_count` int(11) NOT NULL DEFAULT 0,
  `product_returns_total` double NOT NULL DEFAULT 0,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales_details`
--

INSERT INTO `sales_details` (`id`, `auto_id`, `clientId`, `branchId`, `order_id`, `product_id`, `product_quantity`, `product_cost_price`, `product_unit_price`, `product_total`, `product_returns_count`, `product_returns_total`, `order_date`) VALUES
(1, '4T5ESahsZbOr3yWXeY78MUgcu', '3OKokdt8wveI', 1, 'POS2020040100002', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-10 21:24:29'),
(2, 'FBH4yo2xklMifQ5phKLVawdAs', '3OKokdt8wveI', 1, 'POS2020040100003', '1', 2, '1200.00', 1600.00, 3200.00, 0, 0, '2020-04-10 21:25:40'),
(3, '0aybnzKCLmiJBO4QEgxDwVGpt', '3OKokdt8wveI', 1, 'POS2020040100004', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-10 21:31:25'),
(4, 'ouQAdV2JnWp0H6GPhrKZYL7ys', '3OKokdt8wveI', 1, 'POS2020040100005', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-11 17:59:12'),
(5, 'cDqmwlKb2SfyBj8Frg3zJtOeY', '3OKokdt8wveI', 1, 'POS2020040100006', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 10:29:56'),
(6, 'Y3Lasz210vip6JQqBFnIw7jOf', '3OKokdt8wveI', 1, 'POS2020040100007', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 10:36:58'),
(7, 'jlJ14ByK9OmaSsXDzx5AvCGQn', '3OKokdt8wveI', 1, 'POS2020040100008', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 10:40:13'),
(8, 'ukPgD4VoI1y7MAZGJ9mO8w2Tb', '3OKokdt8wveI', 1, 'POS2020040100009', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:00:54'),
(9, 'iPNQxbc6DJuSHdUpmLWajq4Ot', '3OKokdt8wveI', 1, 'POS2020040100010', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:03:22'),
(10, 'RZkKn5a1fxOsjDq2LbMrymPvT', '3OKokdt8wveI', 1, 'POS2020040100011', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:03:48'),
(11, 'u84qRBahzF1fgciLj5Es29bNY', '3OKokdt8wveI', 1, 'POS2020040100012', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:20:19'),
(12, 'esIPoNTjhZkfWBAtrDpdm2c0n', '3OKokdt8wveI', 1, 'POS2020040100013', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:44:07'),
(13, '5LSwmtuQpFUGlaCXNsWEq7jDB', '3OKokdt8wveI', 1, 'POS2020040100014', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 11:45:37'),
(14, 'L02wlIHMEFJBORkWrKu4vCfbY', '3OKokdt8wveI', 1, 'POS2020040100015', '1', 11, '1200.00', 1600.00, 17600.00, 0, 0, '2020-04-12 11:49:21'),
(15, 'iS4ksDoVYv6Gbm98HgQMAXCI1', '3OKokdt8wveI', 1, 'POS2020040100016', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 12:22:22'),
(16, 'c8RZFIGH4mvoQiwg9a6KsUOn0', '3OKokdt8wveI', 1, 'POS2020040100017', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 12:23:12'),
(17, 'AGDt1VkdxRYm03IwJaCLTlpjc', 'Y7CSuzFLDd6K', 3, 'POS2020040300018', '3', 5, '200.00', 350.00, 1750.00, 0, 0, '2020-04-12 16:31:07'),
(18, '2IlHa8FzxunJWg0KOsjAL3eD4', 'Y7CSuzFLDd6K', 3, 'POS2020040300018', '4', 5, '100.00', 200.00, 1000.00, 0, 0, '2020-04-12 16:31:07'),
(19, '5F8P6y3m4epLHZOC2jqdIKnGi', '3OKokdt8wveI', 1, 'POS2020040100019', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-12 17:33:21'),
(20, 'jK4La07DqmBvsretiEhzYQUTS', '3OKokdt8wveI', 1, 'POS2020040100020', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-12 18:54:08'),
(21, 'pK2dXrO8kZyGfbLBemj60Anh1', '3OKokdt8wveI', 1, 'POS2020040100020', '5', 4, '2.00', 4.00, 16.00, 0, 0, '2020-04-12 18:54:08'),
(22, 'jzerQLmyEt0nsdpRZVKWNoOUX', '3OKokdt8wveI', 1, 'POS2020040100021', '1', 4, '1200.00', 1600.00, 6400.00, 0, 0, '2020-04-12 19:59:00'),
(23, '0veUnCopAVki2Ir1mZgGNfMlw', '3OKokdt8wveI', 1, 'POS2020040100021', '5', 4, '2.00', 4.00, 16.00, 0, 0, '2020-04-12 19:59:00'),
(24, 'xXy6wqiosfQmrchFgd8BK9EVM', '3OKokdt8wveI', 1, 'POS2020040100022', '5', 10, '2.00', 4.00, 40.00, 0, 0, '2020-04-12 19:59:30'),
(25, 'Aa2Ubp34Rwt850yjJDXLWlcBZ', '3OKokdt8wveI', 1, 'POS2020040100023', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-13 04:56:37'),
(26, 'S7FRV4pJzIoMbwKrgT2NtiXna', '3OKokdt8wveI', 1, 'POS2020040100023', '5', 7, '2.00', 4.00, 28.00, 0, 0, '2020-04-13 04:56:37'),
(27, 'WXv8UnPgHTZdCfhwcJzq9y7ap', '3OKokdt8wveI', 1, 'POS2020040100024', '5', 40, '2.00', 4.00, 160.00, 0, 0, '2020-04-13 05:25:30'),
(28, 'GhokLIsYn0RbZi6DCKrQeNJfp', '3OKokdt8wveI', 1, 'POS2020040100024', '6', 8, '290.00', 350.00, 2800.00, 0, 0, '2020-04-13 05:25:30'),
(29, 'KMYB4qQuFT7YHayOqG06', '3OKokdt8wveI', 1, 'INV8796259791650', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-13 21:37:34'),
(30, 'X8RLDVKVTED987FsIEyW', '3OKokdt8wveI', 1, 'INV8796259791650', '5', 1, '0.00', 4.00, 4.00, 0, 0, '2020-04-13 21:37:34'),
(31, 'H7wKp2hUfZXuSYxpavPL', '3OKokdt8wveI', 1, 'INV8796259791650', '6', 1, '0.00', 350.00, 350.00, 0, 0, '2020-04-13 21:37:34'),
(32, 'tLSZKidfXeO4VtZbA7BA', '3OKokdt8wveI', 1, 'INV8981733221171', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-13 21:37:08'),
(33, 'hpX8qzLyUl9vKbOrpOlj', '3OKokdt8wveI', 1, 'INV8981733221171', '5', 1, '0.00', 4.00, 4.00, 0, 0, '2020-04-13 21:37:08'),
(34, 'f6brxQi7dDhlAWgyPeksLwNJq', '3OKokdt8wveI', 1, 'POS2020040100027', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:28:43'),
(35, 'R2zQ1m47bnIiSlkPMHG0Xqfux', '3OKokdt8wveI', 1, 'POS2020040100028', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:29:12'),
(36, 'JTZ79pIV2RNUaCyMEPWscYdOS', '3OKokdt8wveI', 1, 'POS2020040100029', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:31:05'),
(37, '86IMSznN4Y32bCulGrWv0OPAk', '3OKokdt8wveI', 1, 'POS2020040100030', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:37:57'),
(38, 'mwpiUcXJ8KnaqdLr0IBbW7PVY', '3OKokdt8wveI', 1, 'POS2020040100030', '6', 2, '290.00', 350.00, 700.00, 0, 0, '2020-04-14 17:37:57'),
(39, 'NuJYMw18CWf0tkB7HQLycShd9', '3OKokdt8wveI', 1, 'POS2020040100031', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:38:16'),
(40, 'ICLr12OWQDmzqdhlU0v7FVByH', '3OKokdt8wveI', 1, 'POS2020040100031', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 17:38:16'),
(41, 'saQh7k1R4K6gPwncmWLt5bVyI', '3OKokdt8wveI', 1, 'POS2020040100032', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:58:37'),
(42, 'FvrMkcGdj19ZwYKOEs3x0QUi5', '3OKokdt8wveI', 1, 'POS2020040100033', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 17:58:54'),
(43, 'lrHqSdGTuLnw8CheVBo2P7IgA', '3OKokdt8wveI', 1, 'POS2020040100033', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 17:58:54'),
(44, 'rjox3uk8iYpPUgEMebJDmA0wt', '3OKokdt8wveI', 1, 'POS2020040100034', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-14 18:01:52'),
(45, 'MCgxlVOeKyIrcP6sjUS87E1bn', '3OKokdt8wveI', 1, 'POS2020040100035', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:03:03'),
(46, '4a0LT27GlmJiORSfoNDdBh6KE', '3OKokdt8wveI', 1, 'POS2020040100035', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:03:03'),
(47, 'zE5rcThDXj1ZO2JpAlFGMKmko', '3OKokdt8wveI', 1, 'POS2020040100036', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-14 18:03:54'),
(48, 'oJV9st2vEL1Sw0HRD3c6zgXrn', '3OKokdt8wveI', 1, 'POS2020040100036', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:03:54'),
(49, 'KrhcmVO42dgT3ECGl86A7LBQ9', '3OKokdt8wveI', 1, 'POS2020040100037', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:04:32'),
(50, 'nwEWXdypMb0UlTGQz2HCts76m', '3OKokdt8wveI', 1, 'POS2020040100037', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:04:32'),
(51, 'z2IhTSHsvcOlxqVbE4kXKon1J', '3OKokdt8wveI', 1, 'POS2020040100038', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-14 18:07:29'),
(52, 'uEmxMbLejQU8qJk2YIDnz5Zt7', '3OKokdt8wveI', 1, 'POS2020040100038', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:07:29'),
(53, 'OK09kvuM5pSUcCeogWGRm7ZHQ', '3OKokdt8wveI', 1, 'POS2020040100038', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:07:29'),
(54, 'ZxfTGCdz5F93OspqRnJlwec6V', '3OKokdt8wveI', 1, 'POS2020040100039', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:08:43'),
(55, 'wmQicdRJyeHxKPMF8ghGX3jzk', '3OKokdt8wveI', 1, 'POS2020040100040', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:09:12'),
(56, 'U1X6g8PfpBjVr0bZ9dxlSyTGn', '3OKokdt8wveI', 1, 'POS2020040100040', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:09:12'),
(57, 'QrnL4zpCSoxABlT1P6qItEMc0', '3OKokdt8wveI', 1, 'POS2020040100041', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-14 18:10:11'),
(58, 'B6abtLQGDEWjV7NvAcroInM94', '3OKokdt8wveI', 1, 'POS2020040100041', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:10:11'),
(59, '5DWqvFB9ug7eLZP8pd1oMAaJQ', '3OKokdt8wveI', 1, 'POS2020040100042', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:11:21'),
(60, 'VLeSG3xXNwnmskH78QKhMTODB', '3OKokdt8wveI', 1, 'POS2020040100043', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:12:16'),
(61, 's1Ve6ftloTWydZ723GaKOcFSk', '3OKokdt8wveI', 1, 'POS2020040100043', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:12:16'),
(62, 'u7B8slV4ZGSMKTR1XeLQrfyUi', '3OKokdt8wveI', 1, 'POS2020040100044', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-14 18:14:42'),
(63, 'owRztkyP3AqmMflJQHKUrLh82', '3OKokdt8wveI', 1, 'POS2020040100044', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 18:14:42'),
(64, 'RipFAYEU4s8tfzVHZXhrWcg1j', '3OKokdt8wveI', 1, 'POS2020040100044', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 18:14:42'),
(65, '92MRDrfHs5XPzcehVGiwvOuI3', '3OKokdt8wveI', 1, 'POS2020040100045', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-14 23:05:45'),
(66, 'RdeblJ6E437zOMgmaNoV8nYkI', '3OKokdt8wveI', 1, 'POS2020040100045', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-14 23:05:45'),
(67, 'Hq0IoTtSrfL68G21RwbeJVMFZ', '3OKokdt8wveI', 1, 'POS2020040100046', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-15 09:31:24'),
(68, 'XBlyKbUa1uDk7Qwjz9Rmt2Vv4', '3OKokdt8wveI', 1, 'POS2020040100046', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-15 09:31:24'),
(69, '4DaL9NnAe3IMPRmtbOsVQyifC', '3OKokdt8wveI', 1, 'POS2020040100046', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-15 09:31:24'),
(70, 'bBhvunA65eyEGLNz3wsWdKTMO', '3OKokdt8wveI', 1, 'POS2020040100046', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-15 09:31:24'),
(71, 'xPs2RznY4qEriJpS8vTUfbwWM', '3OKokdt8wveI', 1, 'POS2020040100047', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-15 15:32:47'),
(72, '2XsVovKI4PYrOexw9EBGSQadF', '3OKokdt8wveI', 1, 'POS2020040100047', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-15 15:32:47'),
(73, 'suq9vQxXtVA0F2H5I1arECOzn', '3OKokdt8wveI', 1, 'POS2020040100047', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-15 15:32:47'),
(74, 'AHSFmap6zf2LBqknMWNTYgrhK', '3OKokdt8wveI', 1, 'POS2020040100047', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-15 15:32:47'),
(75, 'WF4iZXwFZl5L2RXFP4Us', '3OKokdt8wveI', 1, 'INV3008709207154', '1', 5, '0.00', 1600.00, 8000.00, 0, 0, '2020-04-16 15:29:01'),
(76, 'adUK9zll18m2Ycnist2H', '3OKokdt8wveI', 1, 'INV3008709207154', '5', 4, '0.00', 4.00, 16.00, 0, 0, '2020-04-16 15:29:01'),
(77, 'V34pj598qN2uf6uNySXV', '3OKokdt8wveI', 1, 'INV3008709207154', '6', 5, '0.00', 350.00, 1750.00, 0, 0, '2020-04-16 15:29:01'),
(78, '4tEHNaQqrpIppUPe5g5G', '3OKokdt8wveI', 1, 'INV3008709207154', '8', 2, '0.00', 25.00, 50.00, 0, 0, '2020-04-16 15:29:01'),
(79, 'XSE71Lz6KwhiPgQIGZTBYutNV', '3OKokdt8wveI', 1, 'POS2020040100049', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 15:36:34'),
(80, 'cNL3UCzEKXpu4Ig6xJH7k1eAT', '3OKokdt8wveI', 1, 'POS2020040100049', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 15:36:34'),
(81, 'JX0SWpmgMEYO8lU2sIBGxQyec', '3OKokdt8wveI', 1, 'POS2020040100049', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 15:36:34'),
(82, '51j7QkUXYw3taFRfqgOsBKMCy', '3OKokdt8wveI', 1, 'POS2020040100050', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-16 15:37:09'),
(83, 'xnfPediqtbphoGr0kYNZyWsFA', '3OKokdt8wveI', 1, 'POS2020040100050', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 15:37:09'),
(84, 'lUps2TYAk81Ghxce4NzfIXuCm', '3OKokdt8wveI', 1, 'POS2020040100050', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 15:37:09'),
(85, 'lpYrh1daD5m3nbkeVjuGBSQwO', '3OKokdt8wveI', 1, 'POS2020040100050', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 15:37:09'),
(86, '3VU6JqAGxIOYnkMRHrEbyfgh4', '3OKokdt8wveI', 1, 'POS2020040100051', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-16 15:37:33'),
(87, 'ZQ0iJhsxl2E9Co8WKYw5HRaeN', '3OKokdt8wveI', 1, 'POS2020040100051', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 15:37:33'),
(88, '36JNb8UcWefwanlE9Z5MzudTk', '3OKokdt8wveI', 1, 'POS2020040100051', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 15:37:33'),
(89, 'VuZH7i6WLdwtnrkQ2ypc0qYg3', '3OKokdt8wveI', 1, 'POS2020040100051', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 15:37:33'),
(90, 'Hj2lQLOim7UE1N8DyPCqoIbwt', '3OKokdt8wveI', 1, 'POS2020040100052', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-16 16:01:35'),
(91, 'TGqKFNO6yZem7hsPpjXJzadfv', '3OKokdt8wveI', 1, 'POS2020040100052', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 16:01:35'),
(92, 'DU6EHhk3ln2yjPIbZa1XTdBt9', '3OKokdt8wveI', 1, 'POS2020040100053', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 16:27:30'),
(93, 'vVQ6iPSjwX5Oktn1sZxg7A098', '3OKokdt8wveI', 1, 'POS2020040100054', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 16:28:57'),
(94, 'iytIG0JxBCEZ2YKmuHr5QWa1N', '3OKokdt8wveI', 1, 'POS2020040100054', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 16:28:57'),
(95, 'LXUr4YcG2pqmzOSQFbe1Rjy39', '3OKokdt8wveI', 1, 'POS2020040100055', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-16 17:04:19'),
(96, 'IfVQLAUh1WGPm3SkctzuOEZx0', '3OKokdt8wveI', 1, 'POS2020040100055', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 17:04:19'),
(97, 'u635GWHNEtqci1MQYwCBXjxny', '3OKokdt8wveI', 1, 'POS2020040100056', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:04:34'),
(98, 'XwVWDI20gnaC1jfSmbdqQt3iE', '3OKokdt8wveI', 1, 'POS2020040100056', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 17:04:34'),
(99, 'THg0hmRZNICZ72AoSCym', '3OKokdt8wveI', 1, 'INV4746444401288', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-16 16:38:56'),
(100, 'xLP9ITh8WCLFInmai6ap', '3OKokdt8wveI', 1, 'INV4746444401288', '5', 1, '0.00', 4.00, 4.00, 0, 0, '2020-04-16 16:38:56'),
(101, 'MF5EvMbUxLTSUJKLj5x6', '3OKokdt8wveI', 1, 'INV7166064965038', '5', 1, '0.00', 4.00, 4.00, 0, 0, '2020-04-16 16:56:33'),
(102, 'YgyFdH96d0D5xu5dzDwU', '3OKokdt8wveI', 1, 'INV7166064965038', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-16 16:56:33'),
(103, 'Wtm3rwKLdolJnuUjqFIsab1BP', '3OKokdt8wveI', 1, 'POS2020040100059', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 17:39:05'),
(104, 'XPYClRsifUaLJW0wy9qdg56Bc', '3OKokdt8wveI', 1, 'POS2020040100059', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:39:05'),
(105, '03Eyt1b9keVTfunIBRQKHUOwz', '3OKokdt8wveI', 1, 'POS2020040100061', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 17:42:08'),
(106, 'I0pOsUQg9jwq1dDk27K6tfe3u', '3OKokdt8wveI', 1, 'POS2020040100061', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:42:08'),
(107, 'D15rTRQkBnCbo4hUPWF7pEf6u', '3OKokdt8wveI', 1, 'POS2020040100062', '1', 1, '1200.00', 1600.00, 1600.00, 0, 0, '2020-04-16 17:44:19'),
(108, 'HoJtZORVWQ19LUTYbKGfp2hvs', '3OKokdt8wveI', 1, 'POS2020040100062', '5', 1, '2.00', 4.00, 4.00, 0, 0, '2020-04-16 17:44:19'),
(109, 'LjxTFlW592uV17iIkJesBXt40', '3OKokdt8wveI', 1, 'POS2020040100062', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:44:19'),
(110, 'HP2Dt1rm8p03CxjwzJTKq9c6N', '3OKokdt8wveI', 1, 'POS2020040100063', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:48:58'),
(111, 'CjgSpAOGxDNHQJVM3IPW9w8y4', '3OKokdt8wveI', 1, 'POS2020040100063', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 17:48:58'),
(112, 'HQSWhqj1KtkoyxNCGdwYM9lFE', '3OKokdt8wveI', 1, 'POS2020040100064', '6', 1, '290.00', 350.00, 350.00, 0, 0, '2020-04-16 17:50:11'),
(113, 'f8P2TJnXrh0pNigwjy6BU59ts', '3OKokdt8wveI', 1, 'POS2020040100064', '8', 1, '12.00', 25.00, 25.00, 0, 0, '2020-04-16 17:50:11'),
(114, 'YclGE5pSkDZ0yYROiatY', '3OKokdt8wveI', 1, 'INV6425565167703', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-16 23:51:19'),
(115, 'Uta1bsCKEMX6jRZEn5us', '3OKokdt8wveI', 1, 'INV6425565167703', '6', 1, '0.00', 350.00, 350.00, 0, 0, '2020-04-16 23:51:19'),
(116, '2ITMQSuzTMx4I9yaB2Cw', '3OKokdt8wveI', 1, 'INV6425565167703', '8', 1, '0.00', 25.00, 25.00, 0, 0, '2020-04-16 23:51:19'),
(117, 'pTk7l3kazCW4EnpQ6WUc', '3OKokdt8wveI', 1, 'INV9852905360378', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-16 23:46:22'),
(118, '6f5r84SGayJCk49GwhqN', '3OKokdt8wveI', 1, 'INV9852905360378', '6', 1, '0.00', 350.00, 350.00, 0, 0, '2020-04-16 23:46:22'),
(119, 'vzYuOHyUJdbrhZV1mfDS', '3OKokdt8wveI', 1, 'INV9852905360378', '8', 1, '0.00', 25.00, 25.00, 0, 0, '2020-04-16 23:46:22'),
(120, 'nHsuXd15A6V8BI4GmfwL', '3OKokdt8wveI', 1, 'POS4457414445000', '1', 1, '0.00', 1600.00, 1600.00, 0, 0, '2020-04-17 00:07:38'),
(121, 'QrEUvfQumly5Z1H7L65T', '3OKokdt8wveI', 1, 'POS5684396286857', '8', 1, '0.00', 25.00, 25.00, 0, 0, '2020-04-17 00:06:22'),
(122, 'aJxvNFKmYD6rd7eApI7Z', '3OKokdt8wveI', 1, 'POS7819060463992', '6', 1, '0.00', 350.00, 350.00, 0, 0, '2020-04-17 00:08:22'),
(123, 'TazrV1qL5HRyKm5Z53VK', '3OKokdt8wveI', 1, 'POS7819060463992', '8', 1, '0.00', 25.00, 25.00, 0, 0, '2020-04-17 00:08:22');

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
  `payment_options` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'cash,credit',
  `shop_opening_days` varchar(400) COLLATE utf8_unicode_ci DEFAULT 'Monday,Tuesday,Wednesday,Thursday,Friday',
  `address_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receipt_message` varchar(1000) COLLATE utf8_unicode_ci DEFAULT 'Thank you for trading with us.',
  `terms_and_conditions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `manager_signature` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reports_sales_attendant` enum('sales-attendant-performance','team-performance') COLLATE utf8_unicode_ci DEFAULT 'sales-attendant-performance',
  `reports_period` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'today',
  `total_expenditure` double(12,2) NOT NULL DEFAULT 0.00,
  `space_per_square_foot` double(12,2) NOT NULL DEFAULT 0.00,
  `default_currency` varchar(25) COLLATE utf8_unicode_ci DEFAULT 'GHS',
  `print_receipt` varchar(12) COLLATE utf8_unicode_ci DEFAULT 'no',
  `expiry_notification_days` varchar(32) COLLATE utf8_unicode_ci DEFAULT '1 MONTH',
  `allow_product_return` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `allow_return_days` int(3) NOT NULL DEFAULT 30,
  `default_discount` double(2,2) NOT NULL DEFAULT 0.00,
  `fiscal_year_start` date DEFAULT '2020-01-01',
  `display_clock` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `theme_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT '{"bg_colors":"bg-purple text-white no-border","bg_color_code":"#8965e0","bg_color_light":"#97a5f1","btn_outline":"btn-outline-primary"}',
  `theme_color_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'purple',
  `setup_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '{"type":"trial","verified":"1","setup_date":"","outlets":"10","initializing":"1"}',
  `deleted` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `clientId`, `client_name`, `client_email`, `client_website`, `client_logo`, `primary_contact`, `secondary_contact`, `payment_options`, `shop_opening_days`, `address_1`, `address_2`, `receipt_message`, `terms_and_conditions`, `manager_signature`, `reports_sales_attendant`, `reports_period`, `total_expenditure`, `space_per_square_foot`, `default_currency`, `print_receipt`, `expiry_notification_days`, `allow_product_return`, `allow_return_days`, `default_discount`, `fiscal_year_start`, `display_clock`, `theme_color`, `theme_color_code`, `setup_info`, `deleted`) VALUES
(1, '3OKokdt8wveI', 'Emmallen Networks', 'emmallob14@gmail.com', '', 'assets/images/logo.png', '0550107770', '', 'cash,credit,MoMo', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday', '', NULL, 'Thank you for trading with us.', '', NULL, 'sales-attendant-performance', 'this-year', 0.00, 0.00, 'GHS', 'yes', '1 MONTH', '1', 30, 0.00, '2020-01-01', '1', '{\"bg_colors\":\"bg-purple text-white no-border\",\"bg_color_code\":\"#8965e0\",\"bg_color_light\":\"#97a5f1\",\"btn_outline\":\"btn-outline-primary\"}', 'purple', '{\"type\":\"trial\",\"verified\":0,\"setup_date\":\"2020-04-06\",\"expiry_date\":\"2020-04-20\",\"outlets\":\"5\"}', '0'),
(2, 'Y7CSuzFLDd6K', 'Helena Oduro&#39;s Fashion Shop', 'helenaoduro@mail.com', NULL, 'assets/images/logo.png', '0987654321', NULL, 'cash,credit,MoMo', 'Monday,Tuesday,Wednesday,Thursday,Friday', NULL, NULL, 'Thank you for trading with us.', NULL, NULL, 'sales-attendant-performance', 'this-week', 0.00, 0.00, 'GHS', 'no', '1 MONTH', '1', 30, 0.00, '2020-04-11', '1', '{\"bg_colors\":\"bg-purple text-white no-border\",\"bg_color_code\":\"#8965e0\",\"bg_color_light\":\"#97a5f1\",\"btn_outline\":\"btn-outline-primary\"}', 'purple', '{\"type\":\"trial\",\"verified\":1,\"setup_date\":\"2020-04-11\",\"expiry_date\":\"2020-04-25\",\"outlets\":\"5\",\"initializing\":1,\"setup_initializing\":1}', '0');

-- --------------------------------------------------------

--
-- Table structure for table `settings_currency`
--

CREATE TABLE `settings_currency` (
  `id` int(11) NOT NULL,
  `currency` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings_currency`
--

INSERT INTO `settings_currency` (`id`, `currency`) VALUES
(1, 'GHS'),
(2, 'USD'),
(3, 'GBP'),
(4, 'SWF'),
(5, 'YEN');

-- --------------------------------------------------------

--
-- Table structure for table `system_notices`
--

CREATE TABLE `system_notices` (
  `id` int(11) NOT NULL,
  `uniqueId` varchar(255) NOT NULL DEFAULT 'NULL',
  `notice_type` varchar(55) DEFAULT NULL,
  `section` varchar(255) DEFAULT 'dashboard,index',
  `related_to` text NOT NULL DEFAULT 'general',
  `persistent` enum('0','1') DEFAULT '0',
  `modal` varchar(255) DEFAULT NULL,
  `modal_function` varchar(25) DEFAULT 'generalNoticeHandler',
  `header` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `seen_by` text DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `date_log` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_notices`
--

INSERT INTO `system_notices` (`id`, `uniqueId`, `notice_type`, `section`, `related_to`, `persistent`, `modal`, `modal_function`, `header`, `content`, `seen_by`, `status`, `date_log`) VALUES
(1, '09oN3tYcj10KTedBdonjBGvfcDrefaOplKimJhnbYtfPld89J6XsiVqnkN94rFlL', 'setup_initializing', 'dashboard,index', 'general', '0', 'WelcomeModal', 'generalNoticeHandler', 'You are warmly welcomed', '<div class=\"modal fade WelcomeModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-header\">\r\n        <h5 class=\"modal-title mt-0\" id=\"myLargeModalLabel\">\r\n          Welcome to join the family!\r\n        </h5>\r\n        <button type=\"button\" class=\"close\" aria-hidden=\"true\">×</button>\r\n      </div>\r\n      <div class=\"modal-body\" style=\"min-height: 200px; padding-top: 0px\">Hello {{clientName}},<br><br>\r\nThis is to welcome you as you join the numerous retailers using {{appName}} to manage their inventory and make informed decisions based on the insights provided on the Analytics Section of the App. <br><br>Please take time to <a href=\"{{appURL}}/inventory\">add products </a> to your intory or <a href=\"{{appURL}}/import\">import</a> your bulk products at a go. <br><p>This would enable you to begin your first sale on {{appName}}.</p>\r\n<p class=\"text-right\"> <a href=\"{{appURL}}/product-types\" class=\"btn btn-outline-success\"> Add  Category</a> <a href=\"{{appURL}}/inventory\" class=\"btn btn-outline-success\"> Add Product</a> <a href=\"{{appURL}}/customers\" class=\"btn btn-outline-success\">Add Customers</a></p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '1', '1', '2020-04-11 22:55:47'),
(2, '3tYjSdaWEkj761234VDLPOkN94rFlLhjsa22F3F3A4J3ddIkMkkh09P', 'promotional_message', 'dashboard,index', 'general', '0', 'discountModal', 'generalNoticeHandler', 'Awesome Discount Promotion', '<div class=\"modal discountModal fade\" id=\"discountModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\" role=\"document\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-body text-center\"><button type=\"button\" class=\"close\" aria-hidden=\"true\">×</button>\r\n        <div class=\"icon text-danger\">\r\n          <i class=\"fas fa-gift\"></i>\r\n        </div>\r\n\r\n        <div class=\"notice\">\r\n          <h4>Get 50% Discount</h4>\r\n          <p>For the next 24 hours you can get any product at half-price.</p>\r\n          \r\n          <p>Use promo code <span class=\"badge badge-info\">50-OFF</span> at checkout.</p>\r\n        </div>\r\n        <div class=\"code\"></div>\r\n      </div>\r\n      <div class=\"modal-footer d-flex justify-content-between\">\r\n        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Nevermind</button>\r\n        <button type=\"button\" class=\"btn btn-danger\">Get Code</button>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '1', '1', '2020-04-11 22:55:47');

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
  `status` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT current_timestamp(),
  `verify_token` varchar(65) DEFAULT NULL,
  `daily_target` double(12,2) NOT NULL DEFAULT 0.00,
  `weekly_target` double(12,2) NOT NULL DEFAULT 0.00,
  `monthly_target` double(12,2) NOT NULL DEFAULT 0.00,
  `last_login_attempts` int(11) DEFAULT 0,
  `last_login_attempts_time` datetime DEFAULT current_timestamp(),
  `login_session` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `clientId`, `branchId`, `user_id`, `name`, `gender`, `email`, `phone`, `login`, `password`, `access_level`, `status`, `last_login`, `verify_token`, `daily_target`, `weekly_target`, `monthly_target`, `last_login_attempts`, `last_login_attempts_time`, `login_session`, `country_id`, `city_id`, `created_on`, `created_by`, `image`) VALUES
(1, '3OKokdt8wveI', 1, 'J7Sa5j8FYPGVXKp', 'Emmanuel Obeng', 'Male', 'emmallob14@gmail.com', '0550107770', 'emmallob14@gmail.com', '$2y$10$CkSzxyvLAglcF/CTwqXFH.qg5Y9FZfh21m2HapS4JMCzfqIdqpi5q', 2, 1, '2020-04-10 20:59:51', NULL, 0.00, 0.00, 0.00, 0, '2020-04-10 20:59:51', NULL, NULL, NULL, '2020-04-10 20:59:51', NULL, 'avatar.png'),
(2, '3OKokdt8wveI', 1, '3tYcj10KTedBJ6XsiVqnkN94rFlL', 'Simon Kortey', 'Male', 'simonkortey@mail.com', '0987654321', NULL, '$2y$10$CkSzxyvLAglcF/CTwqXFH.qg5Y9FZfh21m2HapS4JMCzfqIdqpi5q', 4, 1, '2020-04-10 21:07:35', NULL, 2000.00, 6000.00, 10000.00, 0, '2020-04-10 21:07:35', NULL, NULL, NULL, '2020-04-10 21:07:35', NULL, 'avatar.png'),
(3, 'Y7CSuzFLDd6K', 3, 'JmtzvChyWXs0ecj', 'Helena Oduro', NULL, 'helenaoduro@mail.com', '0987654321', 'helenaoduro@mail.com', '$2y$10$m9AZWiAMqOf5uKdzBmo...H71Y3kqRT99ZpYavXrzUu4MGnW1WRUu', 2, 1, '2020-04-12 00:58:11', NULL, 0.00, 0.00, 0.00, 0, '2020-04-12 00:58:11', NULL, NULL, NULL, '2020-04-12 00:58:11', NULL, 'avatar.png');

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
(1, '3OKokdt8wveI', '1', 'pos', 'POS2020040100049', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 15:36:34'),
(2, '3OKokdt8wveI', '1', 'pos', 'POS2020040100050', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 15:37:09'),
(3, '3OKokdt8wveI', '1', 'pos', 'POS2020040100051', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 15:37:33'),
(4, '3OKokdt8wveI', '1', 'pos', 'POS2020040100052', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 16:01:35'),
(5, '3OKokdt8wveI', '1', 'pos', 'POS2020040100053', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 16:27:30'),
(6, '3OKokdt8wveI', '1', 'pos', 'POS2020040100054', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 16:28:57'),
(7, '3OKokdt8wveI', '1', 'pos', 'POS2020040100055', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:04:19'),
(8, '3OKokdt8wveI', '1', 'pos', 'POS2020040100056', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:04:35'),
(9, '3OKokdt8wveI', '1', 'pos', 'POS2020040100059', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:39:05'),
(10, '3OKokdt8wveI', '1', 'pos', 'POS2020040100061', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:42:08'),
(11, '3OKokdt8wveI', '1', 'pos', 'POS2020040100062', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:44:19'),
(12, '3OKokdt8wveI', '1', 'pos', 'POS2020040100063', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:48:58'),
(13, '3OKokdt8wveI', '1', 'pos', 'POS2020040100064', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | ::1', '2020-04-16 17:50:11'),
(14, '3OKokdt8wveI', '1', 'expenses', '2', 'J7Sa5j8FYPGVXKp', 'Deleted the Expenses Category from the system.', 'Windows 10 | Chrome | ::1', '2020-04-16 23:08:45'),
(15, '3OKokdt8wveI', '1', 'expenses', '2', 'J7Sa5j8FYPGVXKp', 'Updated the expense details already recorded.', 'Windows 10 | Chrome | ::1', '2020-04-16 23:23:22'),
(16, '3OKokdt8wveI', '1', 'expenses', '1', 'J7Sa5j8FYPGVXKp', 'Updated the expense details already recorded.', 'Windows 10 | Chrome | ::1', '2020-04-16 23:25:53'),
(17, '3OKokdt8wveI', '1', 'expenses', '2', 'J7Sa5j8FYPGVXKp', 'Updated the expense details already recorded.', 'Windows 10 | Chrome | ::1', '2020-04-16 23:26:05'),
(18, '3OKokdt8wveI', '1', 'settings', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Updated the sales details tab of the Company.', 'Windows 10 | Chrome | ::1', '2020-04-17 00:16:06');

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
(1, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-10 21:01:10'),
(2, '3OKokdt8wveI', '1', 'simonkortey@mail.com', '127.0.0.1', 'Chrome|Windows 10', '3tYcj10KTedBJ6XsiVqnkN94rFlL', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-10 21:26:33'),
(3, '3OKokdt8wveI', '1', 'simonkortey@mail.com', '127.0.0.1', 'Chrome|Windows 10', '3tYcj10KTedBJ6XsiVqnkN94rFlL', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-10 21:42:19'),
(4, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-10 21:43:32'),
(5, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 08:23:12'),
(6, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 10:26:32'),
(7, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 11:38:07'),
(8, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 13:21:34'),
(9, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 15:57:08'),
(10, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 17:52:26'),
(11, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-11 22:37:31'),
(12, 'Y7CSuzFLDd6K', '3', 'helenaoduro@mail.com', '127.0.0.1', 'Chrome|Windows 10', 'JmtzvChyWXs0ecj', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 00:59:26'),
(13, 'Y7CSuzFLDd6K', '3', 'helenaoduro@mail.com', '127.0.0.1', 'Chrome|Windows 10', 'JmtzvChyWXs0ecj', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 06:56:36'),
(14, 'Y7CSuzFLDd6K', '3', 'helenaoduro@mail.com', '127.0.0.1', 'Chrome|Windows 10', 'JmtzvChyWXs0ecj', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 07:44:52'),
(15, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 10:01:22'),
(16, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 13:16:14'),
(17, 'Y7CSuzFLDd6K', '3', 'helenaoduro@mail.com', '127.0.0.1', 'Chrome|Windows 10', 'JmtzvChyWXs0ecj', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 15:28:24'),
(18, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 17:13:43'),
(19, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 17:25:47'),
(20, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 21:44:14'),
(21, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 04:49:18'),
(22, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 18:57:02'),
(23, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:20:53'),
(24, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:22:27'),
(25, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:23:00'),
(26, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:47:33'),
(27, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:48:57'),
(28, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:51:26'),
(29, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:55:16'),
(30, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 20:59:54'),
(31, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:07:42'),
(32, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:11:34'),
(33, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:12:34'),
(34, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:13:44'),
(35, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:25:39'),
(36, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 21:36:16'),
(37, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-13 22:10:56'),
(38, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-14 17:27:58'),
(39, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-14 17:45:45'),
(40, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-14 20:13:56'),
(41, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-15 01:04:45'),
(42, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-15 01:05:09'),
(43, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Android', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Mobile Safari/537.36', '2020-04-15 08:48:24'),
(44, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', '2020-04-15 12:05:52'),
(45, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', '2020-04-15 13:07:14'),
(46, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', '2020-04-15 15:03:20'),
(47, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', '2020-04-15 16:38:06'),
(48, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', '2020-04-15 18:01:33'),
(49, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 14:00:23'),
(50, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 15:49:01'),
(51, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 15:52:49'),
(52, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 15:58:50'),
(53, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 16:31:43'),
(54, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 16:34:09'),
(55, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 16:36:39'),
(56, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 16:38:23'),
(57, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-16 20:50:28'),
(58, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '::1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36', '2020-04-17 01:21:55');

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
(1, 'J7Sa5j8FYPGVXKp', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"expenses\":{\"expenses_add\":\"1\",\"expenses_update\":\"1\",\"expenses_delete\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"}}}', '2020-04-10 20:59:51'),
(2, '3tYcj10KTedBJ6XsiVqnkN94rFlL', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}', '2020-04-10 21:07:35'),
(3, 'JmtzvChyWXs0ecj', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"expenses\":{\"expenses_add\":\"1\",\"expenses_update\":\"1\",\"expenses_delete\":\"1\",\"category_add\":\"1\",\"category_update\":\"1\",\"category_delete\":\"1\"}}}', '2020-04-12 00:58:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_levels`
--
ALTER TABLE `access_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `branch_id` (`branch_id`);

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
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_category`
--
ALTER TABLE `expenses_category`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientId` (`clientId`);

--
-- Indexes for table `settings_currency`
--
ALTER TABLE `settings_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_notices`
--
ALTER TABLE `system_notices`
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
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `data_monitoring`
--
ALTER TABLE `data_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `email_list`
--
ALTER TABLE `email_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses_category`
--
ALTER TABLE `expenses_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `industry`
--
ALTER TABLE `industry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products_stocks`
--
ALTER TABLE `products_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requests_details`
--
ALTER TABLE `requests_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings_currency`
--
ALTER TABLE `settings_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_notices`
--
ALTER TABLE `system_notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_activity_logs`
--
ALTER TABLE `users_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users_login_history`
--
ALTER TABLE `users_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users_reset_request`
--
ALTER TABLE `users_reset_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
