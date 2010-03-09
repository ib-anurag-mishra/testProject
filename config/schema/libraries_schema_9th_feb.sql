-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2010 at 11:21 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sony`
--

-- --------------------------------------------------------

--
-- Table structure for table `libraries`
--

DROP TABLE IF EXISTS `libraries`;
CREATE TABLE IF NOT EXISTS `libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `library_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `referrer_url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `download_limit` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `library_download_daily_limit` int(11) NOT NULL,
  `library_download_weekly_limit` int(11) NOT NULL,
  `library_download_monthly_limit` int(11) NOT NULL,
  `library_download_annual_limit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `libraries`
--

INSERT INTO `libraries` (`id`, `library_name`, `referrer_url`, `download_limit`, `admin_id`, `library_download_daily_limit`, `library_download_weekly_limit`, `library_download_monthly_limit`, `library_download_annual_limit`) VALUES
(1, 'Library', 'http://url.com', 10, 4, 0, 0, 0, 0),
(9, 'wefwe', 'espnstar.com', 5, 13, 0, 0, 0, 10);
