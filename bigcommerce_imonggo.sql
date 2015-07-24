-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 24, 2015 at 07:39 AM
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
  `bigcommerce_id` varchar(7) collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Dumping data for table `product_invoice`
-- 

INSERT INTO `product_invoice` (`imonggo_id`, `bigcommerce_id`) VALUES 
('950612', '232');
