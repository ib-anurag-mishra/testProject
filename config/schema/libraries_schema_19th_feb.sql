-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2010 at 09:20 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
  `library_domain_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `library_template_id` int(11) NOT NULL,
  `library_contact_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `library_contact_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `library_contact_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `library_user_download_limit` int(11) NOT NULL DEFAULT '0',
  `library_admin_id` int(11) NOT NULL,
  `library_download_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `library_download_limit` int(11) NOT NULL DEFAULT '0',
  `library_current_downloads` int(11) NOT NULL DEFAULT '0',
  `library_total_downloads` int(11) NOT NULL DEFAULT '0',
  `library_available_downloads` int(11) NOT NULL DEFAULT '0',
  `library_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `library_image_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `library_block_explicit_content` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;