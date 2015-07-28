-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 28, 2015 at 07:26 AM
-- Server version: 5.0.45
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `bigcommerce_imonggo`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `customer`
-- 

CREATE TABLE `customer` (
  `imonggo_id` varchar(10) collate latin1_general_ci NOT NULL,
  `bigcommerce_id` varchar(10) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `imonggo_id` (`imonggo_id`,`bigcommerce_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `customer`
-- 

INSERT INTO `customer` (`imonggo_id`, `bigcommerce_id`) VALUES 
('384394', '1'),
('384395', '2'),
('384396', '4'),
('384397', '5'),
('384398', '6'),
('384399', '7');

-- --------------------------------------------------------

-- 
-- Table structure for table `invoices`
-- 

CREATE TABLE `invoices` (
  `post_id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `post_date` datetime NOT NULL,
  PRIMARY KEY  (`post_id`),
  UNIQUE KEY `post_date` (`post_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=166 ;

-- 
-- Dumping data for table `invoices`
-- 

INSERT INTO `invoices` (`post_id`, `post_date`) VALUES 
(00165, '2015-07-28 04:00:53'),
(00164, '2015-07-28 03:10:28'),
(00163, '2015-07-28 03:07:45'),
(00162, '2015-07-28 03:05:50'),
(00161, '2015-07-28 03:03:00'),
(00160, '2015-07-28 03:01:52'),
(00159, '2015-07-28 03:01:02'),
(00158, '2015-07-28 03:00:32'),
(00157, '2015-07-28 02:59:55'),
(00156, '2015-07-28 02:58:30'),
(00155, '2015-07-28 02:57:13'),
(00154, '2015-07-28 02:56:09'),
(00153, '2015-07-28 02:55:33'),
(00152, '2015-07-28 02:53:55'),
(00151, '2015-07-28 02:51:29'),
(00150, '2015-07-28 02:49:49'),
(00149, '2015-07-28 02:47:36'),
(00148, '2015-07-28 02:45:47'),
(00147, '2015-07-28 02:43:54'),
(00146, '2015-07-28 02:42:38'),
(00145, '2015-07-28 02:41:18'),
(00144, '2015-07-28 02:39:39'),
(00143, '2015-07-28 02:38:16'),
(00142, '2015-07-28 02:37:05'),
(00141, '2015-07-28 02:33:38'),
(00140, '2015-07-28 02:22:46'),
(00139, '2015-07-28 02:18:30');

-- --------------------------------------------------------

-- 
-- Table structure for table `last_invoice_posting`
-- 

CREATE TABLE `last_invoice_posting` (
  `id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `last_invoice_posting`
-- 

INSERT INTO `last_invoice_posting` (`id`, `date`) VALUES 
(00007, '2015-07-28 04:00:53');

-- --------------------------------------------------------

-- 
-- Table structure for table `product_invoice`
-- 

CREATE TABLE `product_invoice` (
  `imonggo_id` varchar(7) collate latin1_general_ci NOT NULL,
  `bigcommerce_id` varchar(7) collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `product_invoice`
-- 

INSERT INTO `product_invoice` (`imonggo_id`, `bigcommerce_id`) VALUES 
('950610', '92'),
('980063', '91'),
('969086', '90'),
('969105', '89');
