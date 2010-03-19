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
-- Table structure for table `library_purchases`
--

DROP TABLE IF EXISTS `library_purchases`;

CREATE TABLE IF NOT EXISTS `library_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `library_id` int(11) NOT NULL,
  `purchased_order_num` varchar(255) NOT NULL,
  `purchased_tracks` int(11) NOT NULL,
  `purchased_amount` double NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchased_order_num` (`purchased_order_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;