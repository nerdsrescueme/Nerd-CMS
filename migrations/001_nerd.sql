-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 24, 2012 at 03:38 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `new_nerd`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_sessions`
--

DROP TABLE IF EXISTS `nerd_sessions`;
CREATE TABLE IF NOT EXISTS `nerd_sessions` (
  `id` char(50) COLLATE utf8_bin NOT NULL,
  `user_id` int(5) unsigned DEFAULT NULL,
  `data` text(4000) COLLATE utf8_bin NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Nerd database sessions table' COLLATE=utf8_bin;


--
-- Table structure for table `nerd_cities`
--

DROP TABLE IF EXISTS `nerd_cities`;
CREATE TABLE IF NOT EXISTS `nerd_cities` (
  `city` char(50) COLLATE utf8_bin NOT NULL,
  `state` char(2) COLLATE utf8_bin NOT NULL,
  `zip` int(5) unsigned zerofill NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `county` char(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`zip`),
  KEY `county` (`county`),
  KEY `state` (`state`),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All cities in the USA' COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Table structure for table `nerd_components`
--

DROP TABLE IF EXISTS `nerd_components`;
CREATE TABLE IF NOT EXISTS `nerd_components` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) COLLATE utf8_bin NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Nerd page components' AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `nerd_components`:
--   `page_id`
--       `nerd_pages` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_keywords`
--

DROP TABLE IF EXISTS `nerd_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` char(32) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Keywords for use in search and SEO' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `nerd_pages`
--

DROP TABLE IF EXISTS `nerd_pages`;
CREATE TABLE IF NOT EXISTS `nerd_pages` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(2) unsigned NOT NULL,
  `layout_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT 'default' COMMENT 'min(3)',
  `title` char(160) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  `subtitle` char(160) COLLATE utf8_bin DEFAULT NULL,
  `uri` char(200) COLLATE utf8_bin NOT NULL COMMENT 'uri',
  `description` char(200) COLLATE utf8_bin DEFAULT NULL,
  `status` enum('one','two') COLLATE utf8_bin DEFAULT 'one',
  `priority` int(2) unsigned zerofill NOT NULL DEFAULT '05' COMMENT 'max(10), min(1)',
  `change_frequency` enum('always','hourly','daily','weekly','monthly','yearly','never') COLLATE utf8_bin DEFAULT 'weekly',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Nerd pages' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `nerd_pages`
--

INSERT INTO `nerd_pages` (`id`, `site_id`, `layout_id`, `title`, `subtitle`, `uri`, `description`, `status`, `priority`, `change_frequency`, `updated_at`, `created_at`) VALUES
(1, 1, 'default', 'Home Page', '', '@@HOME', '', 'one', 10, 'daily', '2012-01-01 00:00:01', '2012-01-01 00:00:01'),
(2, 1, 'default', '404', 'Page Not Found', '@@404', '', 'one', 1, 'monthly', '2012-01-01 00:00:01', '2012-01-01 00:00:01'),
(3, 1, 'default', '500', 'Internal Server Error', '@@500', '', 'one', 1, 'monthly', '2012-01-01 00:00:01', '2012-01-01 00:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `nerd_page_keywords`
--

DROP TABLE IF EXISTS `nerd_page_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_page_keywords` (
  `page_id` int(8) unsigned NOT NULL,
  `keyword_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`keyword_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Link table for page keywords';

--
-- RELATIONS FOR TABLE `nerd_page_keywords`:
--   `keyword_id`
--       `nerd_keywords` -> `id`
--   `page_id`
--       `nerd_pages` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_regions`
--

DROP TABLE IF EXISTS `nerd_regions`;
CREATE TABLE IF NOT EXISTS `nerd_regions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='HTML regions within a page' AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `nerd_regions`:
--   `page_id`
--       `nerd_pages` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_sites`
--

DROP TABLE IF EXISTS `nerd_sites`;
CREATE TABLE IF NOT EXISTS `nerd_sites` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT COMMENT 'min(1), max(99)',
  `host` char(180) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  `theme` char(32) COLLATE utf8_bin NOT NULL DEFAULT 'default' COMMENT 'min(3)',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `maintaining` tinyint(1) NOT NULL DEFAULT '0',
  `description` char(200) COLLATE utf8_bin NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nerd_sites`
--

INSERT INTO `nerd_sites` (`id`, `host`, `theme`, `active`, `maintaining`, `description`) VALUES
(1, 'localhost', 'default', 1, 0, 'Default site upon installation. This defaults to a locally hosted site.');

-- --------------------------------------------------------

--
-- Table structure for table `nerd_site_keywords`
--

DROP TABLE IF EXISTS `nerd_site_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_site_keywords` (
  `site_id` int(2) unsigned NOT NULL,
  `keyword_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`site_id`,`keyword_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Link table for site keywords';

--
-- RELATIONS FOR TABLE `nerd_site_keywords`:
--   `keyword_id`
--       `nerd_keywords` -> `id`
--   `site_id`
--       `nerd_sites` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_site_users`
--

DROP TABLE IF EXISTS `nerd_site_users`;
CREATE TABLE IF NOT EXISTS `nerd_site_users` (
  `site_id` int(2) unsigned NOT NULL,
  `user_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`site_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  COMMENT='Link table for site users' COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `nerd_site_users`:
--   `site_id`
--       `nerd_sites` -> `id`
--   `user_id`
--       `nerd_users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_snippets`
--

DROP TABLE IF EXISTS `nerd_snippets`;
CREATE TABLE IF NOT EXISTS `nerd_snippets` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Nerd snippets' AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `nerd_snippets`:
--   `page_id`
--       `nerd_pages` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_states`
--

DROP TABLE IF EXISTS `nerd_states`;
CREATE TABLE IF NOT EXISTS `nerd_states` (
  `code` char(2) COLLATE utf8_bin NOT NULL COMMENT 'Min(2)',
  `name` char(32) COLLATE utf8_bin NOT NULL COMMENT 'Min(4)',
  PRIMARY KEY (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All of the United States' COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `nerd_states`:
--   `code`
--       `nerd_cities` -> `state`
--

--
-- Dumping data for table `nerd_states`
--

INSERT INTO `nerd_states` (`code`, `name`) VALUES
('AL', 'Alabama'),
('AK', 'Alaska'),
('AZ', 'Arizona'),
('AR', 'Arkansas'),
('CA', 'California'),
('CO', 'Colorado'),
('CT', 'Connecticut'),
('DE', 'Delaware'),
('DC', 'District of Columbia'),
('FL', 'Florida'),
('GA', 'Georgia'),
('HI', 'Hawaii'),
('ID', 'Idaho'),
('IL', 'Illinois'),
('IN', 'Indiana'),
('IA', 'Iowa'),
('KS', 'Kansas'),
('KY', 'Kentucky'),
('LA', 'Louisiana'),
('ME', 'Maine'),
('MD', 'Maryland'),
('MA', 'Massachusetts'),
('MI', 'Michigan'),
('MN', 'Minnesota'),
('MS', 'Mississippi'),
('MO', 'Missouri'),
('MT', 'Montana'),
('NE', 'Nebraska'),
('NV', 'Nevada'),
('NH', 'New Hampshire'),
('NJ', 'New Jersey'),
('NM', 'New Mexico'),
('NY', 'New York'),
('NC', 'North Carolina'),
('ND', 'North Dakota'),
('OH', 'Ohio'),
('OK', 'Oklahoma'),
('OR', 'Oregon'),
('PA', 'Pennsylvania'),
('RI', 'Rhode Island'),
('SC', 'South Carolina'),
('SD', 'South Dakota'),
('TN', 'Tennessee'),
('TX', 'Texas'),
('UT', 'Utah'),
('VT', 'Vermont'),
('VA', 'Virginia'),
('WA', 'Washington'),
('WV', 'West Virginia'),
('WI', 'Wisconsin'),
('WY', 'Wyoming');

-- --------------------------------------------------------

--
-- Table structure for table `nerd_users`
--

DROP TABLE IF EXISTS `nerd_users`;
CREATE TABLE IF NOT EXISTS `nerd_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `super` tinyint(1) NOT NULL DEFAULT '0',
  `username` char(32) COLLATE utf8_bin NOT NULL COMMENT 'username',
  `email` char(255) COLLATE utf8_bin NOT NULL COMMENT 'email',
  `password` char(81) COLLATE utf8_bin NOT NULL COMMENT 'password',
  `password_reset_hash` char(81) COLLATE utf8_bin DEFAULT NULL,
  `temp_password` char(81) COLLATE utf8_bin DEFAULT NULL,
  `remember` char(81) COLLATE utf8_bin DEFAULT NULL,
  `activation_hash` char(81) COLLATE utf8_bin DEFAULT NULL,
  `ip` char(45) COLLATE utf8_bin NOT NULL COMMENT 'ip',
  `status` enum('inactive','active','banned') COLLATE utf8_bin NOT NULL DEFAULT 'inactive',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `locaters` (`username`,`email`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- RELATIONS FOR TABLE `nerd_users`:
--   `id`
--       `nerd_user_metadata` -> `user_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `nerd_user_metadata`
--

DROP TABLE IF EXISTS `nerd_user_metadata`;
CREATE TABLE IF NOT EXISTS `nerd_user_metadata` (
  `user_id` int(5) unsigned NOT NULL,
  `first_name` char(36) COLLATE utf8_bin DEFAULT NULL COMMENT 'min(3)',
  `last_name` char(36) COLLATE utf8_bin DEFAULT NULL COMMENT 'min(3)',
  `zip` int(5) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nerd_sessions`
--
ALTER TABLE `nerd_sessions`
  ADD CONSTRAINT `nerd_sessions-user_id-nerd_users-id` FOREIGN KEY (`user_id`) REFERENCES `nerd_users` (`id`);

--
-- Constraints for table `nerd_components`
--
ALTER TABLE `nerd_components`
  ADD CONSTRAINT `nerd_components-page_id-nerd_pages-id` FOREIGN KEY (`page_id`) REFERENCES `nerd_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nerd_page_keywords`
--
ALTER TABLE `nerd_page_keywords`
  ADD CONSTRAINT `nerd_page_keywords-keyword_id-nerd_keywords-id` FOREIGN KEY (`keyword_id`) REFERENCES `nerd_keywords` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nerd_page_keywords_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `nerd_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nerd_regions`
--
ALTER TABLE `nerd_regions`
  ADD CONSTRAINT `nerd_regions-page_id-nerd_pages-id` FOREIGN KEY (`page_id`) REFERENCES `nerd_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nerd_site_keywords`
--
ALTER TABLE `nerd_site_keywords`
  ADD CONSTRAINT `nerd_site_keywords-keyword_id-nerd_keywords-id` FOREIGN KEY (`keyword_id`) REFERENCES `nerd_keywords` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nerd_site_keywords-site_id-nerd_sites-id` FOREIGN KEY (`site_id`) REFERENCES `nerd_sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nerd_site_users`
--
ALTER TABLE `nerd_site_users`
  ADD CONSTRAINT `nerd_site_users-user_id-nerd_users-id` FOREIGN KEY (`user_id`) REFERENCES `nerd_users` (`id`),
  ADD CONSTRAINT `nerd_site_users-site_id-nerd_sites-id` FOREIGN KEY (`site_id`) REFERENCES `nerd_sites` (`id`);

--
-- Constraints for table `nerd_snippets`
--
ALTER TABLE `nerd_snippets`
  ADD CONSTRAINT `nerd_snippets-page_id-nerd_pages-id` FOREIGN KEY (`page_id`) REFERENCES `nerd_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nerd_cities`
--
ALTER TABLE `nerd_cities`
  ADD CONSTRAINT `nerd_cities-state-nerd_states-code` FOREIGN KEY (`state`) REFERENCES `nerd_states` (`code`);

--
-- Constraints for table `nerd_users`
--
ALTER TABLE `nerd_user_metadata`
  ADD CONSTRAINT `nerd_user_metadata-user_id-nerd_users-id` FOREIGN KEY (`user_id`) REFERENCES `nerd_users` (`id`) ON DELETE CASCADE;


SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
