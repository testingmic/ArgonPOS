-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2020 at 01:36 PM
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
(1, 'Visamine Staff', 'Analitica Innovare Staffs', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\",\"developer\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"}}}'),
(2, 'Admin', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"}}}'),
(3, 'Sales Manager', 'Admin', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"},\"access_levels\":{\"view\":\"0\",\"update\":\"0\"},\"branches\":{\"view\":\"0\",\"add\":\"0\",\"update\":\"0\",\"delete\":\"0\"}}}'),
(4, 'Sales Officer', '', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}');

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
(1, 'w9KT0OBztNEy', '3OKokdt8wveI', 'Warehouse', 'Emmallen Networks Branch', 'badge-purple', '', '0550107770', 'emmallob14@gmail.com', NULL, NULL, 0.00, 0.00, '1', '0'),
(2, 'rgMbqG4aUyvF', '3OKokdt8wveI', 'Store', 'Madina - Zongo Junction Branch', NULL, 'Madina', '0909885887', 'madinabranch@mail.com', 'assets/images/logo.png', NULL, 0.00, 0.00, '0', '0'),
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
  `status` enum('1','0') COLLATE utf8_unicode_ci DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `clientId`, `branchId`, `customer_id`, `source`, `title`, `firstname`, `lastname`, `role`, `phone_1`, `phone_2`, `email`, `date_of_birth`, `user_image`, `description`, `residence`, `postal_address`, `country`, `nationality`, `gender`, `city`, `user_type`, `website`, `industry`, `balance`, `rating`, `owner_id`, `date_log`, `preferred_payment_type`, `comments`, `status`) VALUES
(1, '3OKokdt8wveI', 1, 'POS732143158965', 'Evelyn', 'Mr', 'Asamoah', 'John', NULL, '0908877859', NULL, 'asamoahjohn@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'J7Sa5j8FYPGVXKp', '2020-04-10 21:11:14', NULL, NULL, '1'),
(2, '3OKokdt8wveI', 1, 'POS499688133725', 'Evelyn', 'Mr', 'Amoah', 'Danso', NULL, '0550107770', NULL, 'emmallob14@mail.com', NULL, 'assets/images/users/user-4.jpg', NULL, 'Accra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3tYcj10KTedBJ6XsiVqnkN94rFlL', '2020-04-10 21:27:47', NULL, NULL, '1'),
(3, '3OKokdt8wveI', 1, 'WalkIn', 'Argon', NULL, 'WalkIn', 'Customer', NULL, NULL, NULL, NULL, NULL, 'assets/images/users/user-4.jpg', NULL, NULL, NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-11 09:13:25', NULL, NULL, '1');

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
(19, 3, 'settings', '3OKokdt8wveI', '{\"id\":\"1\",\"clientId\":\"3OKokdt8wveI\",\"client_name\":\"Emmallen Networks\",\"client_email\":\"emmallob14@gmail.com\",\"client_website\":\"\",\"client_logo\":\"assets\\/images\\/logo.png\",\"primary_contact\":\"0550107770\",\"secondary_contact\":\"\",\"payment_options\":\"cash,credit\",\"shop_opening_days\":\"Monday,Tuesday,Wednesday,Thursday,Friday\",\"address_1\":\"\",\"address_2\":null,\"receipt_message\":\"Thank you for trading with us.\",\"terms_and_conditions\":\"\",\"manager_signature\":null,\"reports_sales_attendant\":\"sales-attendant-performance\",\"reports_period\":\"all-time\",\"total_expenditure\":\"0.00\",\"space_per_square_foot\":\"0.00\",\"default_currency\":\"GHS\",\"print_receipt\":\"\",\"expiry_notification_days\":\"1 MONTH\",\"allow_product_return\":\"1\",\"default_discount\":\"0.00\",\"fiscal_year_start\":\"2020-01-01\",\"display_clock\":\"1\",\"theme_color\":\"{\\\"bg_colors\\\":\\\"bg-gradient-darker text-white no-border\\\",\\\"bg_color_code\\\":\\\"#000\\\",\\\"bg_color_light\\\":\\\"#3c3939\\\",\\\"btn_outline\\\":\\\"btn-outline-dark\\\"}\",\"theme_color_code\":\"darker\",\"setup_info\":\"{\\\"type\\\":\\\"alpha\\\",\\\"verified\\\":1,\\\"setup_date\\\":\\\"2020-04-10\\\",\\\"expiry_date\\\":\\\"2020-04-24\\\",\\\"outlets\\\":\\\"5\\\",\\\"initializing\\\":\\\"1\\\"}\"}', 'J7Sa5j8FYPGVXKp', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-11 18:19:41');

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
(2, 'Y7CSuzFLDd6K', '3', 'general', 'Y7CSuzFLDd6K', '{\"recipients_list\":[{\"fullname\":\"Helena Oduro\",\"email\":\"helenaoduro@mail.com\",\"customer_id\":\"JmtzvChyWXs0ecj\",\"branchId\":\"3\"}]}', 'Setup - Helena Oduro&#39;s Fashion Shop \\[Argon POS\\]', 'Hello Helena Oduro,\nThank you for registering your store <strong>Helena Oduro&#39;s Fashion Shop</strong> with Argon POS. We are pleased to have you join and experiment with our platform.\n\nOne of our personnel will get in touch shortly to assist you with additional setup processes that is required to aid you quick start the usage of the application.\n\nIn the mean time please use the following credentials to login into the system.\n\n<strong>Username:</strong> helenaoduro@mail.com\n<a href=\'https://dev.localhost.com/pos/verify/act?tk=FCvt2emE1UuhqD8fRdxbwWp7YOi9gnzj6IJcAVMXTlBaZHksPLGS0y\'><strong>Click Here</strong></a> to verify your Email Address\n\n', '2020-04-12 00:58:11', '0', 'JmtzvChyWXs0ecj', NULL, '0');

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
(2, 'PDTX2IazQq8ycvT', 10, '2020-04-10 21:23:36', NULL, '1600.00', '2020-04-10 21:23:36', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 2);

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
(1, '3OKokdt8wveI', 1, 'db', 'PDT00012', 'PCAT85769', 'HP Laptop', NULL, 'This is an HP Laptop that i want to add to the Store', 'assets/images/products/default.png', 1600.00, '1200.00', NULL, '2020-04-10 21:19:51', '2020-08-27', 'J7Sa5j8FYPGVXKp', '1', 5, 35),
(2, '3OKokdt8wveI', 2, 'db', 'PDT00012', 'PCAT85769', 'HP Laptop', NULL, 'This is an HP Laptop that i want to add to the Store', 'assets/images/products/default.png', 1600.00, '1200.00', NULL, '2020-04-10 21:23:36', NULL, 'J7Sa5j8FYPGVXKp', '1', 5, 10);

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
(3, '3OKokdt8wveI', 'PCAT92358', 'Furniture');

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
(2, '3OKokdt8wveI', '2', 'HtoVnCgbFYIz', 2, '1200.00', '1600.00', 0, 10, 10, 5, 'J7Sa5j8FYPGV', '2020-04-10 21:23:36');

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
  `revert` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `payment_date` datetime DEFAULT NULL,
  `payment_type` enum('cash','card','credit','MoMo') COLLATE utf8_unicode_ci DEFAULT 'cash',
  `transaction_id` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `clientId`, `source`, `mode`, `branchId`, `order_id`, `customer_id`, `ordered_by_id`, `customer_type`, `sale_lead_id`, `recorded_by`, `currency`, `credit_sales`, `order_discount`, `order_amount_balance`, `overall_order_amount`, `order_amount_paid`, `order_comments`, `order_date`, `order_status`, `log_date`, `deleted`, `revert`, `payment_date`, `payment_type`, `transaction_id`) VALUES
(1, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100002', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 1600.00, 1600.00, NULL, '2020-04-10 21:24:29', 'confirmed', '2020-04-10 21:24:29', '0', '0', NULL, 'cash', '428152359978'),
(2, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100003', 'POS732143158965', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, 0.00, 3200.00, 3200.00, NULL, '2020-04-10 21:25:40', 'confirmed', '2020-04-10 21:25:40', '0', '0', NULL, 'cash', '732869344567'),
(3, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100004', 'POS499688133725', NULL, 'customer', NULL, '3tYcj10KTedBJ6XsiVqnkN94rFlL', 'GH¢', '0', 0.00, 20.00, 1600.00, 1600.00, NULL, '2020-04-10 21:31:25', 'confirmed', '2020-04-10 21:31:25', '0', '0', NULL, 'cash', '927854862141'),
(4, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100005', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 100.00, 0.00, 1600.00, 1500.00, NULL, '2020-04-11 17:59:12', 'confirmed', '2020-04-11 17:59:12', '0', '0', NULL, 'cash', '256563784171'),
(5, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100006', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:29:56', 'cancelled', '2020-04-12 10:29:56', '1', '0', '2020-04-12 10:35:19', 'MoMo', '811962648739'),
(6, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100007', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:36:58', 'cancelled', '2020-04-12 10:36:58', '0', '0', '2020-04-12 10:37:08', 'MoMo', '645195628417'),
(7, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100008', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 10:40:13', 'cancelled', '2020-04-12 10:40:13', '0', '0', '2020-04-12 10:41:05', 'MoMo', '944695526782'),
(8, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100009', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:00:54', 'cancelled', '2020-04-12 11:00:54', '0', '0', '2020-04-12 11:01:56', 'MoMo', '256329891417'),
(9, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100010', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:03:22', 'cancelled', '2020-04-12 11:03:22', '0', '0', '2020-04-12 11:03:41', 'MoMo', '924173539867'),
(10, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100011', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:03:48', 'cancelled', '2020-04-12 11:03:48', '1', '0', NULL, 'MoMo', '714932958567'),
(11, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100012', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:20:19', 'cancelled', '2020-04-12 11:20:19', '0', '0', '2020-04-12 11:20:43', 'MoMo', '767499831325'),
(12, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100013', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:44:07', 'cancelled', '2020-04-12 11:44:07', '1', '0', NULL, 'MoMo', '599573678112'),
(13, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100014', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 11:45:37', 'cancelled', '2020-04-12 11:45:37', '0', '0', '2020-04-12 11:46:26', 'MoMo', '612823579484'),
(14, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100015', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -17600.00, 17600.00, 17600.00, NULL, '2020-04-12 11:49:21', 'cancelled', '2020-04-12 11:49:21', '0', '0', '2020-04-12 11:49:31', 'MoMo', '724635678298'),
(15, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100016', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 12:22:22', 'pending', '2020-04-12 12:22:22', '0', '0', NULL, 'MoMo', '543127858629'),
(16, '3OKokdt8wveI', 'Argon', 'online', 1, 'POS2020040100017', 'WalkIn', NULL, 'customer', NULL, 'J7Sa5j8FYPGVXKp', 'GH¢', '0', 0.00, -1600.00, 1600.00, 1600.00, NULL, '2020-04-12 12:23:12', 'cancelled', '2020-04-12 12:23:12', '0', '0', '2020-04-12 12:24:23', 'MoMo', '757254669839');

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
(1, '4T5ESahsZbOr3yWXeY78MUgcu', '3OKokdt8wveI', 1, 'POS2020040100002', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-10 21:24:29'),
(2, 'FBH4yo2xklMifQ5phKLVawdAs', '3OKokdt8wveI', 1, 'POS2020040100003', '1', 2, '1200.00', 1600.00, 3200.00, '2020-04-10 21:25:40'),
(3, '0aybnzKCLmiJBO4QEgxDwVGpt', '3OKokdt8wveI', 1, 'POS2020040100004', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-10 21:31:25'),
(4, 'ouQAdV2JnWp0H6GPhrKZYL7ys', '3OKokdt8wveI', 1, 'POS2020040100005', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-11 17:59:12'),
(5, 'cDqmwlKb2SfyBj8Frg3zJtOeY', '3OKokdt8wveI', 1, 'POS2020040100006', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 10:29:56'),
(6, 'Y3Lasz210vip6JQqBFnIw7jOf', '3OKokdt8wveI', 1, 'POS2020040100007', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 10:36:58'),
(7, 'jlJ14ByK9OmaSsXDzx5AvCGQn', '3OKokdt8wveI', 1, 'POS2020040100008', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 10:40:13'),
(8, 'ukPgD4VoI1y7MAZGJ9mO8w2Tb', '3OKokdt8wveI', 1, 'POS2020040100009', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:00:54'),
(9, 'iPNQxbc6DJuSHdUpmLWajq4Ot', '3OKokdt8wveI', 1, 'POS2020040100010', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:03:22'),
(10, 'RZkKn5a1fxOsjDq2LbMrymPvT', '3OKokdt8wveI', 1, 'POS2020040100011', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:03:48'),
(11, 'u84qRBahzF1fgciLj5Es29bNY', '3OKokdt8wveI', 1, 'POS2020040100012', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:20:19'),
(12, 'esIPoNTjhZkfWBAtrDpdm2c0n', '3OKokdt8wveI', 1, 'POS2020040100013', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:44:07'),
(13, '5LSwmtuQpFUGlaCXNsWEq7jDB', '3OKokdt8wveI', 1, 'POS2020040100014', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 11:45:37'),
(14, 'L02wlIHMEFJBORkWrKu4vCfbY', '3OKokdt8wveI', 1, 'POS2020040100015', '1', 11, '1200.00', 1600.00, 17600.00, '2020-04-12 11:49:21'),
(15, 'iS4ksDoVYv6Gbm98HgQMAXCI1', '3OKokdt8wveI', 1, 'POS2020040100016', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 12:22:22'),
(16, 'c8RZFIGH4mvoQiwg9a6KsUOn0', '3OKokdt8wveI', 1, 'POS2020040100017', '1', 1, '1200.00', 1600.00, 1600.00, '2020-04-12 12:23:12');

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

INSERT INTO `settings` (`id`, `clientId`, `client_name`, `client_email`, `client_website`, `client_logo`, `primary_contact`, `secondary_contact`, `payment_options`, `shop_opening_days`, `address_1`, `address_2`, `receipt_message`, `terms_and_conditions`, `manager_signature`, `reports_sales_attendant`, `reports_period`, `total_expenditure`, `space_per_square_foot`, `default_currency`, `print_receipt`, `expiry_notification_days`, `allow_product_return`, `default_discount`, `fiscal_year_start`, `display_clock`, `theme_color`, `theme_color_code`, `setup_info`, `deleted`) VALUES
(1, '3OKokdt8wveI', 'Emmallen Networks', 'emmallob14@gmail.com', '', 'assets/images/logo.png', '0550107770', '', 'cash,credit,MoMo', 'Monday,Tuesday,Wednesday,Thursday,Friday', '', NULL, 'Thank you for trading with us.', '', NULL, 'sales-attendant-performance', 'this-week', 0.00, 0.00, 'GHS', '', '1 MONTH', '1', 0.00, '2020-01-01', '1', '{\"bg_colors\":\"bg-green text-white no-border\",\"bg_color_code\":\"#24a46d\",\"bg_color_light\":\"#2dce89\",\"btn_outline\":\"btn-outline-success\"}', 'green', '{\"type\":\"alpha\",\"verified\":0,\"setup_date\":\"2020-03-10\",\"expiry_date\":\"2020-03-24\",\"outlets\":\"5\"}', '0'),
(2, 'Y7CSuzFLDd6K', 'Helena Oduro&#39;s Fashion Shop', 'helenaoduro@mail.com', NULL, 'assets/images/logo.png', '0987654321', NULL, 'cash,credit,MoMo', 'Monday,Tuesday,Wednesday,Thursday,Friday', NULL, NULL, 'Thank you for trading with us.', NULL, NULL, 'sales-attendant-performance', 'this-week', 0.00, 0.00, 'GHS', 'no', '1 MONTH', '1', 0.00, '2020-04-11', '1', '{\"bg_colors\":\"bg-purple text-white no-border\",\"bg_color_code\":\"#8965e0\",\"bg_color_light\":\"#97a5f1\",\"btn_outline\":\"btn-outline-primary\"}', 'purple', '{\"type\":\"trial\",\"verified\":1,\"setup_date\":\"2020-04-11\",\"expiry_date\":\"2020-04-25\",\"outlets\":\"5\",\"initializing\":1,\"setup_initializing\":1}', '0');

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
  `related_to` text NOT NULL DEFAULT 'general',
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

INSERT INTO `system_notices` (`id`, `uniqueId`, `notice_type`, `related_to`, `modal`, `modal_function`, `header`, `content`, `seen_by`, `status`, `date_log`) VALUES
(1, '09oN3tYcj10KTedBdonjBGvfcDrefaOplKimJhnbYtfPld89J6XsiVqnkN94rFlL', 'setup_initializing', 'general', 'WelcomeModal', 'generalNoticeHandler', 'You are warmly welcomed', '<div class=\"modal fade WelcomeModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-header\">\r\n        <h5 class=\"modal-title mt-0\" id=\"myLargeModalLabel\">\r\n          Welcome to join the family!\r\n        </h5>\r\n        <button type=\"button\" class=\"close\" aria-hidden=\"true\">×</button>\r\n      </div>\r\n      <div class=\"modal-body\" style=\"min-height: 200px; padding-top: 0px\">Hello {{clientName}},<br><br>\r\nThis is to welcome you as you join the numerous retailers using {{appName}} to manage their inventory and make informed decisions based on the insights provided on the Analytics Section of the App. <br><br>Please take time to <a href=\"{{appURL}}/explore\">explore</a> the various features. <br><p>It will guide you through a step by step procedure that will enable you to fully setup your store.</p>\r\n<p class=\"text-right\"><a href=\"{{appURL}}/explore\" class=\"btn btn-outline-success\">Take Tour</a></p>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '1', '1', '2020-04-11 22:55:47'),
(2, '3tYjSdaWEkj761234VDLPOkN94rFlLhjsa22F3F3A4J3ddIkMkkh09P', 'promotional_message', 'general', 'discountModal', 'generalNoticeHandler', 'Awesome Discount Promotion', '<div class=\"modal discountModal fade\" id=\"discountModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">\r\n  <div class=\"modal-dialog\" role=\"document\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-body text-center\"><button type=\"button\" class=\"close\" aria-hidden=\"true\">×</button>\r\n        <div class=\"icon text-danger\">\r\n          <i class=\"fas fa-gift\"></i>\r\n        </div>\r\n\r\n        <div class=\"notice\">\r\n          <h4>Get 50% Discount</h4>\r\n          <p>For the next 24 hours you can get any product at half-price.</p>\r\n          \r\n          <p>Use promo code <span class=\"badge badge-info\">50-OFF</span> at checkout.</p>\r\n        </div>\r\n        <div class=\"code\"></div>\r\n      </div>\r\n      <div class=\"modal-footer d-flex justify-content-between\">\r\n        <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Nevermind</button>\r\n        <button type=\"button\" class=\"btn btn-danger\">Get Code</button>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>', '1', '1', '2020-04-11 22:55:47');

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
(1, '3OKokdt8wveI', 1, 'J7Sa5j8FYPGVXKp', 'Emmanuel Obeng', NULL, 'emmallob14@gmail.com', '0550107770', 'emmallob14@gmail.com', '$2y$10$CkSzxyvLAglcF/CTwqXFH.qg5Y9FZfh21m2HapS4JMCzfqIdqpi5q', 2, 1, '2020-04-10 20:59:51', NULL, 0.00, 0.00, 0.00, 0, '2020-04-10 20:59:51', NULL, NULL, NULL, '2020-04-10 20:59:51', NULL, 'avatar.png'),
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
(1, 'Y7CSuzFLDd6K', '3', 'initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 08:39:29'),
(2, 'Y7CSuzFLDd6K', '3', 'initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 08:40:42'),
(3, 'Y7CSuzFLDd6K', '3', 'initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 08:42:43'),
(4, 'Y7CSuzFLDd6K', '3', 'initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 08:42:58'),
(5, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 08:44:07'),
(6, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:13:05'),
(7, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:13:53'),
(8, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:36:13'),
(9, 'Y7CSuzFLDd6K', '3', 'promotional_message', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:38:22'),
(10, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:44:40'),
(11, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:45:26'),
(12, 'Y7CSuzFLDd6K', '3', 'promotional_message', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:47:26'),
(13, 'Y7CSuzFLDd6K', '3', 'promotional_message', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:54:36'),
(14, 'Y7CSuzFLDd6K', '3', 'setup_initializing', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 09:55:57'),
(15, 'Y7CSuzFLDd6K', '3', 'settings', 'Y7CSuzFLDd6K', 'JmtzvChyWXs0ecj', 'Updated the payment options of the Company.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:01:02'),
(16, '3OKokdt8wveI', '1', 'setup_initializing', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:01:27'),
(17, '3OKokdt8wveI', '1', 'setup_initializing', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:08:10'),
(18, '3OKokdt8wveI', '1', 'promotional_message', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:08:14'),
(19, '3OKokdt8wveI', '1', 'setup_initializing', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:29:05'),
(20, '3OKokdt8wveI', '1', 'promotional_message', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Have acknowledged of having seen the notification shared across the Application.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:29:08'),
(21, '3OKokdt8wveI', '1', 'settings', '3OKokdt8wveI', 'J7Sa5j8FYPGVXKp', 'Updated the payment options of the Company.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:29:32'),
(22, '3OKokdt8wveI', '1', 'pos', 'POS2020040100006', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:29:56'),
(23, '3OKokdt8wveI', '1', 'pos', 'POS2020040100007', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:36:58'),
(24, '3OKokdt8wveI', '1', 'pos', 'POS2020040100008', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 10:40:13'),
(25, '3OKokdt8wveI', '1', 'pos', 'POS2020040100009', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:00:55'),
(26, '3OKokdt8wveI', '1', 'pos', 'POS2020040100010', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:03:22'),
(27, '3OKokdt8wveI', '1', 'pos', 'POS2020040100011', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:03:48'),
(28, '3OKokdt8wveI', '1', 'pos', 'POS2020040100012', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:20:19'),
(29, '3OKokdt8wveI', '1', 'pos', 'POS2020040100013', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:44:07'),
(30, '3OKokdt8wveI', '1', 'pos', 'POS2020040100014', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:45:37'),
(31, '3OKokdt8wveI', '1', 'pos', 'POS2020040100015', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 11:49:21'),
(32, '3OKokdt8wveI', '1', 'pos', 'POS2020040100016', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 12:22:22'),
(33, '3OKokdt8wveI', '1', 'pos', 'POS2020040100017', 'J7Sa5j8FYPGVXKp', 'Recorded a new Sale at the POS', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 12:23:13'),
(34, '3OKokdt8wveI', '1', 'pos-revert', 'POS2020040100016', 'J7Sa5j8FYPGVXKp', 'The Transaction recorded at the Point of Sale has been reverted. Reason: User cancelled payment.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 12:24:23'),
(35, '3OKokdt8wveI', '1', 'pos-revert', 'POS2020040100017', 'J7Sa5j8FYPGVXKp', 'The Transaction recorded at the Point of Sale has been reverted. Reason: User cancelled payment.', 'Windows 10 | Chrome | 127.0.0.1', '2020-04-12 12:24:23');

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
(15, '3OKokdt8wveI', '1', 'emmallob14@gmail.com', '127.0.0.1', 'Chrome|Windows 10', 'J7Sa5j8FYPGVXKp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36', '2020-04-12 10:01:22');

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
(1, 'J7Sa5j8FYPGVXKp', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"}}}', '2020-04-10 20:59:51'),
(2, '3tYcj10KTedBJ6XsiVqnkN94rFlL', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"0\",\"inventory_branches\":\"0\"},\"quotes\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"orders\":{\"view\":\"0\",\"update\":\"0\",\"add\":\"0\",\"delete\":\"0\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\"}}}', '2020-04-10 21:07:35'),
(3, 'JmtzvChyWXs0ecj', '{\"permissions\":{\"contacts\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"customers\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"products\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"inventory_branches\":\"1\",\"category_add\":\"1\",\"category_edit\":\"1\",\"category_delete\":\"1\"},\"sales\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"users\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\",\"accesslevel\":\"1\"},\"reports\":{\"view\":\"1\",\"generate\":\"1\",\"sales-team-performance\":\"1\",\"branch-performance\":\"1\",\"sales-overview\":\"1\"},\"access_levels\":{\"view\":\"1\",\"update\":\"1\"},\"quotes\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"orders\":{\"view\":\"1\",\"update\":\"1\",\"add\":\"1\",\"delete\":\"1\"},\"branches\":{\"view\":\"1\",\"add\":\"1\",\"update\":\"1\",\"delete\":\"1\",\"monitoring\":\"1\"},\"settings\":{\"view\":\"1\",\"update\":\"1\"},\"executive_dashboard\":{\"view\":\"1\"}}}', '2020-04-12 00:58:11');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_monitoring`
--
ALTER TABLE `data_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `email_list`
--
ALTER TABLE `email_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_settings`
--
ALTER TABLE `email_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industry`
--
ALTER TABLE `industry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products_stocks`
--
ALTER TABLE `products_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales_details`
--
ALTER TABLE `sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users_login_history`
--
ALTER TABLE `users_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
