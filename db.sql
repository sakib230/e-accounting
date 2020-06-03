-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2019 at 08:54 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_new_db`
--


-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE IF NOT EXISTS `account_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_code` varchar(30) NOT NULL,
  `title` varchar(200) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_type_code` (`account_type_code`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`id`, `account_type_code`, `title`, `is_active`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0205190001', 'Cash', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(2, '0205190002', 'Equity', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(3, '0205190003', 'Current Asset', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(4, '0205190004', 'Fixed Asset', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(5, '0205190005', 'Current Liability', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(6, '0205190006', 'Accounts Receivable', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(7, '0205190007', 'Accounts Payable', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(8, '0205190008', 'Income', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(9, '0205190009', 'Expense', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(10, '0205190010', 'Cost of Goods Sold', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_account`
--

CREATE TABLE IF NOT EXISTS `chart_of_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` varchar(30) NOT NULL,
  `account_code` varchar(30) NOT NULL,
  `account_title` varchar(200) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_type` (`account_type`,`account_title`),
  UNIQUE KEY `account_code` (`account_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `chart_of_account`
--

INSERT INTO `chart_of_account` (`id`, `account_type`, `account_code`, `account_title`, `is_active`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0205190001', '0305190001', 'Petty Cash', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(2, '0205190005', '0305190002', 'Tax Payable', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(3, '0205190006', '0305190003', 'Accounts Receivable', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(4, '0205190007', '0305190004', 'Accounts Payable', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(5, '0205190008', '0305190005', 'Sales', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(6, '0205190008', '0305190006', 'General Income', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(7, '0205190009', '0305190007', 'Bank Fees and Charges', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(8, '0205190009', '0305190008', 'Other Expense', 1, '0104190001', '2019-05-01 04:00:00', '0104190001', '2019-05-01 04:00:00'),
(9, '0205190010', '0305190009', 'Cost of Goods Sold', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(10, '0205190001', '0305190010', 'Undeposited Funds', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(12, '0205190005', '0305190011', 'Unearned Revenue', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00'),
(13, '0205190003', '0305190012', 'Prepaid Expense', 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `code_table`
--

CREATE TABLE IF NOT EXISTS `code_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element_code` varchar(10) NOT NULL,
  `serial_no` varchar(15) NOT NULL,
  `serial_date` date DEFAULT NULL,
  `comment` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `element_code` (`element_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `code_table`
--

INSERT INTO `code_table` (`id`, `element_code`, `serial_no`, `serial_date`, `comment`) VALUES
(1, '01', '0002', '2019-04-28', 'user'),
(2, '02', '0010', '2019-05-01', 'Account Type'),
(3, '03', '0012', '2019-05-01', 'Chart of Account'),
(4, '04', '0003', '2019-05-01', 'Contacts'),
(5, '05', '0002', '2019-05-01', 'Tax'),
(6, '06', '0002', '2019-05-01', 'Item'),
(7, '07', '0001', '2019-05-01', 'User Role'),
(9, '08', '0003', '2019-06-01', 'Sale Invoice'),
(10, '09', '0001', '2019-06-01', 'Payment receive'),
(11, '10', '0002', '2019-06-01', 'purchase bill'),
(12, '11', '0001', '2019-06-01', 'payment made');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_code` varchar(30) NOT NULL,
  `contact_account` varchar(30) NOT NULL,
  `contact_name` varchar(200) NOT NULL,
  `mobile_no` varchar(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `company_name` varchar(200) DEFAULT NULL,
  `contact_type` varchar(10) NOT NULL COMMENT 'customer or vendor',
  `opening_balance` decimal(10,2) DEFAULT NULL,
  `total_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `used_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_code` (`contact_code`),
  UNIQUE KEY `contact_name` (`contact_name`,`mobile_no`,`contact_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `contact_code`, `contact_account`, `contact_name`, `mobile_no`, `email`, `address`, `company_name`, `contact_type`, `opening_balance`, `total_balance`, `used_balance`, `is_active`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0405190001', '0305190003', 'Karim Uddin', '01626026705', 'karim@gmail.com', NULL, NULL, 'customer', NULL, '27375.00', '24475.00', 1, '0104190001', '2019-05-01 05:00:00', '0104190001', '2019-06-01 13:43:16'),
(2, '0405190002', '0305190003', 'Rahim Hussain', '01636026803', 'rahim@gmail.com', NULL, NULL, 'customer', NULL, '0.00', '0.00', 1, '0104190001', '2019-05-01 05:00:00', '0104190001', '2019-05-10 18:32:22'),
(3, '0405190003', '0305190004', 'Hasan Ahmed', '01945882352', 'hasan@gmail.com', NULL, NULL, 'vendor', NULL, '5000.00', '1200.00', 1, '0104190001', '2019-05-01 05:00:00', '0104190001', '2019-06-01 09:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `dictionary_table`
--

CREATE TABLE IF NOT EXISTS `dictionary_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `title_code` varchar(50) NOT NULL,
  `title_type` varchar(30) NOT NULL,
  `remarks` text,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_code` (`title_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dictionary_table`
--

INSERT INTO `dictionary_table` (`id`, `title`, `title_code`, `title_type`, `remarks`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`, `is_active`) VALUES
(1, 'Cash', 'cash_pay_mode', 'payment_mode', 'sale payment mode', '0104190001', '2019-05-10 14:00:00', '0104190001', '2019-05-10 14:00:00', 1),
(2, 'Cheque', 'cheque_pay_mode', 'payment_mode', 'sale payment mode', '0104190001', '2019-05-10 14:00:00', '0104190001', '2019-05-10 14:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(30) NOT NULL,
  `item_type` varchar(10) NOT NULL COMMENT 'product or service',
  `title` varchar(200) NOT NULL,
  `unit_name` varchar(30) NOT NULL,
  `sale_rate` decimal(10,2) DEFAULT NULL,
  `sale_account` varchar(30) DEFAULT NULL,
  `sale_description` text,
  `sale_tax` varchar(30) DEFAULT NULL,
  `purchase_rate` decimal(10,2) DEFAULT NULL,
  `purchase_account` varchar(30) DEFAULT NULL,
  `purchase_description` text,
  `purchase_tax` varchar(30) DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_code` (`item_code`),
  UNIQUE KEY `item_type` (`item_type`,`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `item_code`, `item_type`, `title`, `unit_name`, `sale_rate`, `sale_account`, `sale_description`, `sale_tax`, `purchase_rate`, `purchase_account`, `purchase_description`, `purchase_tax`, `is_active`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0605190001', 'product', 'Ladis Bag', 'Piece', '500.00', '0305190006', NULL, '0505190002', '1000.00', '0305190009', NULL, NULL, 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-16 22:14:43'),
(2, '0605190002', 'product', 'Hand Bag', 'Piece', '1000.00', '0305190006', NULL, '0505190002', '1500.00', '0305190009', NULL, NULL, 1, '0104190001', '2019-05-01 03:00:00', '0104190001', '2019-05-01 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_made`
--

CREATE TABLE IF NOT EXISTS `payment_made` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_made_code` varchar(30) NOT NULL,
  `vendor` varchar(30) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(30) NOT NULL,
  `paid_through` varchar(30) NOT NULL,
  `dis_reference_no` varchar(50) DEFAULT NULL,
  `bill` varchar(30) DEFAULT NULL COMMENT 'those which payment made by Bill Payment. this is auto generated payment made',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_receive_code` (`payment_made_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `payment_made`
--

INSERT INTO `payment_made` (`id`, `payment_made_code`, `vendor`, `amount`, `payment_date`, `payment_mode`, `paid_through`, `dis_reference_no`, `bill`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '1106190001', '0405190003', '5000.00', '2019-06-01', 'cash_pay_mode', '0305190001', NULL, NULL, '0104190001', '2019-06-01 09:34:27', '0104190001', '2019-06-01 09:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `payment_receive`
--

CREATE TABLE IF NOT EXISTS `payment_receive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_receive_code` varchar(30) NOT NULL,
  `customer` varchar(30) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(30) NOT NULL,
  `deposit_to` varchar(30) NOT NULL,
  `dis_reference_no` varchar(50) DEFAULT NULL,
  `invoice` varchar(30) DEFAULT NULL COMMENT 'those which payment made by Invoice Payment. this is auto generated payment receive',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_receive_code` (`payment_receive_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `payment_receive`
--

INSERT INTO `payment_receive` (`id`, `payment_receive_code`, `customer`, `amount`, `payment_date`, `payment_mode`, `deposit_to`, `dis_reference_no`, `invoice`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0906190001', '0405190001', '5000.00', '2019-06-01', 'cash_pay_mode', '0305190001', NULL, NULL, '0104190001', '2019-06-01 13:37:50', '0104190001', '2019-06-01 13:37:50');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill`
--

CREATE TABLE IF NOT EXISTS `purchase_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_code` varchar(30) NOT NULL,
  `vendor` varchar(30) NOT NULL,
  `display_reference_no` varchar(50) DEFAULT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `adjustment` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vendor_notes` text,
  `terms_condition` text,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'unpaid 2, partially paid 3, paid 4',
  `payment_tran_ref` varchar(30) DEFAULT NULL COMMENT 'payment bill',
  `bill_tran_ref` varchar(30) DEFAULT NULL COMMENT 'creating bill',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_code` (`bill_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `purchase_bill`
--

INSERT INTO `purchase_bill` (`id`, `bill_code`, `vendor`, `display_reference_no`, `bill_date`, `due_date`, `sub_total`, `adjustment`, `total`, `vendor_notes`, `terms_condition`, `paid_amount`, `status`, `payment_tran_ref`, `bill_tran_ref`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '1006190001', '0405190003', NULL, '2019-06-01', '2019-06-03', '1000.00', NULL, '1000.00', NULL, NULL, '0.00', 2, NULL, 'acc5cf1ef90117b1', '0104190001', '2019-06-01 09:22:56', '0104190001', '2019-06-01 09:22:56'),
(3, '1006190002', '0405190003', NULL, '2019-05-28', '2019-05-30', '2500.00', NULL, '2500.00', NULL, NULL, '1200.00', 3, 'acc5cf1f440e1a86', 'acc5cf1f1f9704eb', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_item`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_code` varchar(30) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `item` varchar(30) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `tax_code` varchar(30) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `purchase_account` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_no` (`reference_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `purchase_bill_item`
--

INSERT INTO `purchase_bill_item` (`id`, `bill_code`, `reference_no`, `item`, `quantity`, `unit`, `rate`, `tax_code`, `tax_rate`, `amount`, `purchase_account`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '1006190001', 'acc5cf1ef9011baa', '0605190001', '1.00', 'Piece', '1000.00', 'null', '0.00', '1000.00', '0305190009', '0104190001', '2019-06-01 09:22:56', '0104190001', '2019-06-01 09:22:56'),
(2, '1006190002', 'acc5cf1f1f970ae7', '0605190002', '1.00', 'Piece', '1500.00', 'null', '0.00', '1500.00', '0305190009', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:33:13'),
(3, '1006190002', 'acc5cf1f1f970e13', '0605190001', '1.00', 'Piece', '1000.00', 'null', '0.00', '1000.00', '0305190009', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice`
--

CREATE TABLE IF NOT EXISTS `sale_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_code` varchar(30) NOT NULL,
  `customer` varchar(30) NOT NULL,
  `display_reference_no` varchar(50) DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `adjustment` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customer_notes` text,
  `terms_condition` text,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'unpaid 2, partially paid 3, paid 4',
  `payment_tran_ref` varchar(30) DEFAULT NULL COMMENT 'payment invoice',
  `invoice_tran_ref` varchar(30) DEFAULT NULL COMMENT 'creating invoice',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_code` (`invoice_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sale_invoice`
--

INSERT INTO `sale_invoice` (`id`, `invoice_code`, `customer`, `display_reference_no`, `invoice_date`, `due_date`, `sub_total`, `adjustment`, `total`, `customer_notes`, `terms_condition`, `paid_amount`, `status`, `payment_tran_ref`, `invoice_tran_ref`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0806190001', '0405190001', NULL, '2019-06-01', '2019-06-02', '1575.00', NULL, '1575.00', NULL, NULL, '1575.00', 4, 'acc5cf22c94024d6', 'acc5cf1eeaf14f6b', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 13:43:16'),
(2, '0806190002', '0405190001', NULL, '2019-05-31', '2019-05-31', '525.00', NULL, '525.00', NULL, NULL, '0.00', 2, NULL, 'acc5cf1eed5c0ebe', '0104190001', '2019-06-01 09:19:49', '0104190001', '2019-06-01 09:19:49'),
(3, '0806190003', '0405190001', NULL, '2019-06-01', '2019-06-02', '2100.00', '-100.00', '2000.00', NULL, NULL, '1000.00', 3, 'acc5cf22c247fcfa', 'acc5cf22b1da00f6', '0104190001', '2019-06-01 13:37:01', '0104190001', '2019-06-01 13:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice_item`
--

CREATE TABLE IF NOT EXISTS `sale_invoice_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_code` varchar(30) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `item` varchar(30) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `tax_code` varchar(30) DEFAULT NULL,
  `tax_rate` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `sale_account` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_no` (`reference_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sale_invoice_item`
--

INSERT INTO `sale_invoice_item` (`id`, `invoice_code`, `reference_no`, `item`, `quantity`, `unit`, `rate`, `tax_code`, `tax_rate`, `amount`, `sale_account`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0806190001', 'acc5cf1eeaf15432', '0605190002', '1.00', 'Piece', '1000.00', '0505190002', '5.00', '1050.00', '0305190006', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 09:19:11'),
(2, '0806190001', 'acc5cf1eeaf1575f', '0605190001', '1.00', 'Piece', '500.00', '0505190002', '5.00', '525.00', '0305190006', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 09:19:11'),
(3, '0806190002', 'acc5cf1eed5c126e', '0605190001', '1.00', 'Piece', '500.00', '0505190002', '5.00', '525.00', '0305190006', '0104190001', '2019-06-01 09:19:49', '0104190001', '2019-06-01 09:19:49'),
(4, '0806190003', 'acc5cf22b1da053d', '0605190002', '2.00', 'Piece', '1000.00', '0505190002', '5.00', '2100.00', '0305190006', '0104190001', '2019-06-01 13:37:01', '0104190001', '2019-06-01 13:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

CREATE TABLE IF NOT EXISTS `tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_code` varchar(30) NOT NULL,
  `tax_account` varchar(30) NOT NULL,
  `title` varchar(200) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_code` (`tax_code`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tax`
--

INSERT INTO `tax` (`id`, `tax_code`, `tax_account`, `title`, `rate`, `is_active`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, '0505190001', '0305190002', 'Purchase Tax 5%', '5.00', 1, '0104190001', '2019-05-01 06:00:00', '0104190001', '2019-05-01 06:00:00'),
(2, '0505190002', '0305190002', 'Normal Tax 10%', '5.00', 1, '0104190001', '2019-05-01 06:00:00', '0104190001', '2019-05-01 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_group_id` varchar(50) NOT NULL,
  `invoice` varchar(30) DEFAULT NULL,
  `invoice_item_ref_no` varchar(50) DEFAULT NULL,
  `payment_receive` varchar(30) DEFAULT NULL,
  `bill` varchar(30) DEFAULT NULL,
  `bill_item_ref_no` varchar(50) DEFAULT NULL,
  `payment_made` varchar(30) DEFAULT NULL,
  `reference_no` varchar(30) DEFAULT NULL COMMENT 'use for multi purpose like invoice create refno, invoice paymnet ref no',
  `transaction_id` varchar(50) NOT NULL,
  `contact_code` varchar(30) DEFAULT NULL,
  `contact_type` varchar(10) DEFAULT NULL COMMENT 'customer or vendor',
  `account` varchar(30) NOT NULL,
  `credit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `debit_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_type` varchar(10) NOT NULL COMMENT 'debit or credit',
  `transaction_for` varchar(100) NOT NULL,
  `tarn_dt_tm` datetime NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `transaction_group_id`, `invoice`, `invoice_item_ref_no`, `payment_receive`, `bill`, `bill_item_ref_no`, `payment_made`, `reference_no`, `transaction_id`, `contact_code`, `contact_type`, `account`, `credit_amount`, `debit_amount`, `transaction_type`, `transaction_for`, `tarn_dt_tm`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`) VALUES
(1, 'acc5cf1eeaf15385', '0806190001', 'acc5cf1eeaf15432', NULL, NULL, NULL, NULL, 'acc5cf1eeaf14f6b', 'acc5cf1eeaf156da', '0405190001', 'customer', '0305190006', '1050.00', '0.00', 'credit', 'invoice_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 09:19:11'),
(2, 'acc5cf1eeaf15385', '0806190001', 'acc5cf1eeaf1575f', NULL, NULL, NULL, NULL, 'acc5cf1eeaf14f6b', 'acc5cf1eeaf15a55', '0405190001', 'customer', '0305190006', '525.00', '0.00', 'credit', 'invoice_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 09:19:11'),
(3, 'acc5cf1eeaf15385', '0806190001', NULL, NULL, NULL, NULL, NULL, 'acc5cf1eeaf14f6b', 'acc5cf1eeaf15a6a', NULL, NULL, '0305190003', '0.00', '1575.00', 'dedit', 'invoice_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:19:11', '0104190001', '2019-06-01 09:19:11'),
(4, 'acc5cf1eed5c11db', '0806190002', 'acc5cf1eed5c126e', NULL, NULL, NULL, NULL, 'acc5cf1eed5c0ebe', 'acc5cf1eed5c1514', '0405190001', 'customer', '0305190006', '525.00', '0.00', 'credit', 'invoice_create', '2019-05-31 00:00:00', '0104190001', '2019-06-01 09:19:49', '0104190001', '2019-06-01 09:19:49'),
(5, 'acc5cf1eed5c11db', '0806190002', NULL, NULL, NULL, NULL, NULL, 'acc5cf1eed5c0ebe', 'acc5cf1eed5c1526', NULL, NULL, '0305190003', '0.00', '525.00', 'dedit', 'invoice_create', '2019-05-31 00:00:00', '0104190001', '2019-06-01 09:19:49', '0104190001', '2019-06-01 09:19:49'),
(6, 'acc5cf1ef9011b15', NULL, NULL, NULL, '1006190001', 'acc5cf1ef9011baa', NULL, 'acc5cf1ef90117b1', 'acc5cf1ef9011e64', '0405190003', 'vendor', '0305190009', '0.00', '1000.00', 'dedit', 'bill_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:22:56', '0104190001', '2019-06-01 09:22:56'),
(7, 'acc5cf1ef9011b15', NULL, NULL, NULL, '1006190001', NULL, NULL, 'acc5cf1ef90117b1', 'acc5cf1ef9011e77', NULL, NULL, '0305190004', '1000.00', '0.00', 'credit', 'bill_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:22:56', '0104190001', '2019-06-01 09:22:56'),
(8, 'acc5cf1f1f970a46', NULL, NULL, NULL, '1006190002', 'acc5cf1f1f970ae7', NULL, 'acc5cf1f1f9704eb', 'acc5cf1f1f970d86', '0405190003', 'vendor', '0305190009', '0.00', '1500.00', 'dedit', 'bill_create', '2019-05-28 00:00:00', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:33:13'),
(9, 'acc5cf1f1f970a46', NULL, NULL, NULL, '1006190002', 'acc5cf1f1f970e13', NULL, 'acc5cf1f1f9704eb', 'acc5cf1f1f9710a0', '0405190003', 'vendor', '0305190009', '0.00', '1000.00', 'dedit', 'bill_create', '2019-05-28 00:00:00', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:33:13'),
(10, 'acc5cf1f1f970a46', NULL, NULL, NULL, '1006190002', NULL, NULL, 'acc5cf1f1f9704eb', 'acc5cf1f1f9710b1', NULL, NULL, '0305190004', '2500.00', '0.00', 'credit', 'bill_create', '2019-05-28 00:00:00', '0104190001', '2019-06-01 09:33:13', '0104190001', '2019-06-01 09:33:13'),
(11, 'acc5cf1f2434c32c', NULL, NULL, NULL, NULL, NULL, '1106190001', NULL, 'acc5cf1f2434c330', '0405190003', 'vendor', '0305190001', '5000.00', '0.00', 'credit', 'payment_made', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:34:27', '0104190001', '2019-06-01 09:34:27'),
(12, 'acc5cf1f2434c32c', NULL, NULL, NULL, NULL, NULL, '1106190001', NULL, 'acc5cf1f2434c339', '0405190003', 'vendor', '0305190012', '0.00', '5000.00', 'dedit', 'payment_made', '2019-06-01 00:00:00', '0104190001', '2019-06-01 09:34:27', '0104190001', '2019-06-01 09:34:27'),
(13, 'acc5cf1f440e1ab1', NULL, NULL, NULL, '1006190002', NULL, NULL, 'acc5cf1f440e1a86', 'acc5cf1f440e1ab4', '0405190003', 'vendor', '0305190012', '1200.00', '0.00', 'credit', 'bill_payment', '2019-06-01 09:42:56', '0104190001', '2019-06-01 09:42:56', '0104190001', '2019-06-01 09:42:56'),
(14, 'acc5cf1f440e1ab1', NULL, NULL, NULL, '1006190002', NULL, NULL, 'acc5cf1f440e1a86', 'acc5cf1f440e1abd', '0405190003', 'vendor', '0305190004', '0.00', '1200.00', 'dedit', 'bill_payment', '2019-06-01 09:42:56', '0104190001', '2019-06-01 09:42:56', '0104190001', '2019-06-01 09:42:56'),
(15, 'acc5cf22b1da04a5', '0806190003', 'acc5cf22b1da053d', NULL, NULL, NULL, NULL, 'acc5cf22b1da00f6', 'acc5cf22b1da07eb', '0405190001', 'customer', '0305190006', '2100.00', '0.00', 'credit', 'invoice_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 13:37:01', '0104190001', '2019-06-01 13:37:01'),
(16, 'acc5cf22b1da04a5', '0806190003', NULL, NULL, NULL, NULL, NULL, 'acc5cf22b1da00f6', 'acc5cf22b1da080b', NULL, NULL, '0305190003', '0.00', '2000.00', 'dedit', 'invoice_create', '2019-06-01 00:00:00', '0104190001', '2019-06-01 13:37:01', '0104190001', '2019-06-01 13:37:01'),
(17, 'acc5cf22b4ea0a0d', NULL, NULL, '0906190001', NULL, NULL, NULL, NULL, 'acc5cf22b4ea0a11', '0405190001', 'customer', '0305190001', '0.00', '5000.00', 'dedit', 'payment_receive', '2019-06-01 00:00:00', '0104190001', '2019-06-01 13:37:50', '0104190001', '2019-06-01 13:37:50'),
(18, 'acc5cf22b4ea0a0d', NULL, NULL, '0906190001', NULL, NULL, NULL, NULL, 'acc5cf22b4ea0a1a', '0405190001', 'customer', '0305190011', '5000.00', '0.00', 'credit', 'payment_receive', '2019-06-01 00:00:00', '0104190001', '2019-06-01 13:37:50', '0104190001', '2019-06-01 13:37:50'),
(19, 'acc5cf22c247fd2c', '0806190003', NULL, NULL, NULL, NULL, NULL, 'acc5cf22c247fcfa', 'acc5cf22c247fd30', '0405190001', 'customer', '0305190011', '0.00', '1000.00', 'dedit', 'invoice_payment', '2019-06-01 13:41:24', '0104190001', '2019-06-01 13:41:24', '0104190001', '2019-06-01 13:41:24'),
(20, 'acc5cf22c247fd2c', '0806190003', NULL, NULL, NULL, NULL, NULL, 'acc5cf22c247fcfa', 'acc5cf22c247fd3a', '0405190001', 'customer', '0305190003', '1000.00', '0.00', 'credit', 'invoice_payment', '2019-06-01 13:41:24', '0104190001', '2019-06-01 13:41:24', '0104190001', '2019-06-01 13:41:24'),
(23, 'acc5cf22c940251f', '0806190001', NULL, NULL, NULL, NULL, NULL, 'acc5cf22c94024d6', 'acc5cf22c9402524', '0405190001', 'customer', '0305190011', '0.00', '1575.00', 'dedit', 'invoice_payment', '2019-06-01 13:43:16', '0104190001', '2019-06-01 13:43:16', '0104190001', '2019-06-01 13:43:16'),
(24, 'acc5cf22c940251f', '0806190001', NULL, NULL, NULL, NULL, NULL, 'acc5cf22c94024d6', 'acc5cf22c9402536', '0405190001', 'customer', '0305190003', '1575.00', '0.00', 'credit', 'invoice_payment', '2019-06-01 13:43:16', '0104190001', '2019-06-01 13:43:16', '0104190001', '2019-06-01 13:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(30) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(11) NOT NULL,
  `address` varchar(300) DEFAULT NULL,
  `profile_image` varchar(50) DEFAULT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `full_name`, `email`, `mobile_no`, `address`, `profile_image`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`, `is_active`) VALUES
(1, '0104190001', 'Rifat Sakib', 'rifatsakib230@gmail.com', '01945882352', 'Dhaka', 'img.jpg', '0104190001', '2019-04-22 02:00:00', '0104190001', '2019-04-22 02:00:00', 1),
(2, '0104190002', 'Rahim Uddin', NULL, '01626026705', NULL, NULL, '0104190001', '2019-04-28 23:48:24', '0104190001', '2019-04-28 23:48:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `password_reset_code` varchar(6) DEFAULT NULL,
  `user_role` varchar(30) NOT NULL DEFAULT '0',
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `user_id`, `username`, `password`, `password_reset_code`, `user_role`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`, `is_active`) VALUES
(1, '0104190001', '01945882352', '81dc9bdb52d04dc20036dbd8313ed055', NULL, '0705190001', '0104190001', '2019-04-22 02:00:00', '0104190001', '2019-04-22 02:00:00', 1),
(2, '0104190002', '01626026705', '81dc9bdb52d04dc20036dbd8313ed055', NULL, '0705190001', '0104190001', '2019-04-28 23:48:24', '0104190001', '2019-04-28 23:48:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_code` varchar(30) NOT NULL,
  `role_title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `permitted_page_code` text NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_dt_tm` datetime NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `updated_dt_tm` datetime NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role_code`, `role_title`, `permitted_page_code`, `created_by`, `created_dt_tm`, `updated_by`, `updated_dt_tm`, `is_active`) VALUES
(1, '0705190001', 'Project Author', '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,19,20,21', '0104190001', '2019-04-22 01:00:00', '0104190001', '2019-04-22 01:00:00', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
