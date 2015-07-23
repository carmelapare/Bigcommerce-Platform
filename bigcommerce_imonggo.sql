-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 16, 2015 at 10:31 AM
-- Server version: 5.0.45
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `bigcommerce_imonggo`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `product_invoice`
-- 

CREATE TABLE `product_invoice` (
  `imonggo_id` varchar(7) collate latin1_general_ci NOT NULL,
  `product_name` varchar(20) collate latin1_general_ci NOT NULL,
  `bigcommerce_id` varchar(7) collate latin1_general_ci NOT NULL,
  UNIQUE KEY `product_name` (`product_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `product_invoice`
-- 

INSERT INTO `product_invoice` (`imonggo_id`, `product_name`, `bigcommerce_id`) VALUES 
('969088', 'Leather Purse', '141'),
('950610', 'Denim Romper', '140'),
('969105', 'Italian Formal Shoes', '139'),
('969086', 'Crystal Pumps', '138'),
('969079', 'Shiny Moccasins', '137'),
('950612', 'Stacked Heel Penny L', '136'),
('950611', 'Suede Loafers', '135');
