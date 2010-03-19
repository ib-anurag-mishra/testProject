-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2010 at 09:22 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `sony`
--

-- --------------------------------------------------------

--
-- Table structure for table `library_templates`
--

DROP TABLE IF EXISTS `library_templates`;

CREATE TABLE IF NOT EXISTS `library_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_color` varchar(50) NOT NULL,
  `template_css_path` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;