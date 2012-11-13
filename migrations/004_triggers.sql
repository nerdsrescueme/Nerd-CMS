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
-- Trigger for nerd_page versioning. Automatically add old data into the
-- versioning table before updating an older page record.
--

DELIMITER $$
DROP TRIGGER IF EXISTS `before_nerd_pages`;
CREATE TRIGGER `before_nerd_pages`
  BEFORE UPDATE ON `nerd_pages` FOR EACH ROW
  BEGIN
    INSERT INTO `nerd_page_history` (
      `page_id`,
      `site_id`,
      `title`,
      `subtitle`,
      `uri`,
      `description`,
      `status`
    ) VALUES (
      OLD.`id`,
      OLD.`site_id`,
      OLD.`title`,
      OLD.`subtitle`,
      OLD.`uri`,
      OLD.`description`,
      OLD.`status`);
  END
$$
DELIMITER ;


SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;