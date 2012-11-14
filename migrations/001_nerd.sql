--
-- Initial Nerd-CMS database structure dump
--

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
-- Nerd Sessions
--

DROP TABLE IF EXISTS `nerd_sessions`;
CREATE TABLE IF NOT EXISTS `nerd_sessions` (
  `id` char(50) NOT NULL,
  `user_id` int(5) unsigned DEFAULT NULL,
  `data` text(4000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- Timestamp trigger

DELIMITER $$
DROP TRIGGER IF EXISTS `timestamp_nerd_session`;
CREATE TRIGGER `timestamp_nerd_session`
  BEFORE INSERT ON `nerd_sessions` FOR EACH ROW
  BEGIN
    SET NEW.`created_at` = CURRENT_TIMESTAMP();
  END
$$
DELIMITER ;

--
-- Nerd Cities
--

DROP TABLE IF EXISTS `nerd_cities`;
CREATE TABLE IF NOT EXISTS `nerd_cities` (
  `city` char(50) NOT NULL,
  `state` char(2) NOT NULL,
  `zip` int(5) unsigned zerofill NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `county` char(50) NOT NULL,
  PRIMARY KEY (`zip`),
  KEY `county` (`county`),
  KEY `state` (`state`),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Nerd Components
--

DROP TABLE IF EXISTS `nerd_components`;
CREATE TABLE IF NOT EXISTS `nerd_components` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


--
-- Nerd Keywords
--

DROP TABLE IF EXISTS `nerd_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` char(32) NOT NULL COMMENT 'min(3)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


--
-- Nerd Pages
--

DROP TABLE IF EXISTS `nerd_pages`;
CREATE TABLE IF NOT EXISTS `nerd_pages` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(2) unsigned NOT NULL,
  `layout_id` char(32) NOT NULL DEFAULT 'default' COMMENT 'min(3)',
  `title` char(160) NOT NULL COMMENT 'min(3)',
  `subtitle` char(160) DEFAULT NULL,
  `uri` char(200) NOT NULL COMMENT 'uri',
  `description` char(200) DEFAULT NULL,
  `status` enum('one','two') DEFAULT 'one',
  `priority` int(2) unsigned zerofill NOT NULL DEFAULT '05' COMMENT 'max(10), min(1)',
  `change_frequency` enum('always','hourly','daily','weekly','monthly','yearly','never') COLLATE utf8_bin DEFAULT 'weekly',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- Timestamp trigger

DELIMITER $$
DROP TRIGGER IF EXISTS `timestamp_nerd_pages`;
CREATE TRIGGER `timestamp_nerd_pages`
  BEFORE INSERT ON `nerd_pages` FOR EACH ROW
  BEGIN
    SET NEW.`created_at` = CURRENT_TIMESTAMP();
  END
$$
DELIMITER ;


--
-- Nerd Page History
--

DROP TABLE IF EXISTS `nerd_page_history`;
CREATE TABLE IF NOT EXISTS `nerd_page_history` (
  `page_id` int(8) unsigned NOT NULL,
  `site_id` int(2) unsigned NOT NULL,
  `title` char(160) NOT NULL,
  `subtitle` char(160) DEFAULT NULL,
  `uri` char(200) NOT NULL,
  `description` char(200) DEFAULT NULL,
  `status` char(32) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`,`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Versioning trigger
--
-- Automatically add old data into the versioning table before updating an
-- older page record.
--

DELIMITER $$
DROP TRIGGER IF EXISTS `before_nerd_pages`;
CREATE TRIGGER `before_nerd_pages`
  BEFORE UPDATE ON `nerd_pages` FOR EACH ROW
  BEGIN
 -- INSERT old data into the page history
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

 -- DELETE all but the last 10 versions of this page.
    DELETE FROM `nerd_page_history` WHERE `created_at` NOT IN (
      SELECT `created_at`
      FROM (
        SELECT `created_at`
          FROM `nerd_page_history`
          WHERE `page_id` = OLD.`id`
          ORDER BY `created_at` DESC
          LIMIT 10
      ) `history`
    );
  END
$$
DELIMITER ;


--
-- Nerd Pages - Data
--

INSERT INTO `nerd_pages` (`id`, `site_id`, `layout_id`, `title`, `subtitle`, `uri`, `description`, `status`, `priority`, `change_frequency`, `updated_at`, `created_at`) VALUES
(1, 1, 'default', 'Home Page', '', '@@HOME', '', 'one', 10, 'daily', '2012-01-01 00:00:01', '2012-01-01 00:00:01'),
(2, 1, 'default', '404', 'Page Not Found', '@@404', '', 'one', 1, 'monthly', '2012-01-01 00:00:01', '2012-01-01 00:00:01'),
(3, 1, 'default', '500', 'Internal Server Error', '@@500', '', 'one', 1, 'monthly', '2012-01-01 00:00:01', '2012-01-01 00:00:01');


--
-- Nerd Page Keywords
--

DROP TABLE IF EXISTS `nerd_page_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_page_keywords` (
  `page_id` int(8) unsigned NOT NULL,
  `keyword_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`keyword_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Nerd Regions
--

DROP TABLE IF EXISTS `nerd_regions`;
CREATE TABLE IF NOT EXISTS `nerd_regions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) COLLATE utf8_bin NOT NULL COMMENT 'min(3)',
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


--
-- Nerd Sites
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
-- Nerd Sites - Data
--

INSERT INTO `nerd_sites` (`id`, `host`, `theme`, `active`, `maintaining`, `description`) VALUES
(1, 'localhost', 'default', 1, 0, 'Default site upon installation. This defaults to a locally hosted site.');


--
-- Nerd Site Keywords
--

DROP TABLE IF EXISTS `nerd_site_keywords`;
CREATE TABLE IF NOT EXISTS `nerd_site_keywords` (
  `site_id` int(2) unsigned NOT NULL,
  `keyword_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`site_id`,`keyword_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Nerd Site Users
--

DROP TABLE IF EXISTS `nerd_site_users`;
CREATE TABLE IF NOT EXISTS `nerd_site_users` (
  `site_id` int(2) unsigned NOT NULL,
  `user_id` int(5) unsigned NOT NULL,
  PRIMARY KEY (`site_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Nerd Snippets
--

DROP TABLE IF EXISTS `nerd_snippets`;
CREATE TABLE IF NOT EXISTS `nerd_snippets` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(8) unsigned NOT NULL,
  `key` char(32) NOT NULL COMMENT 'min(3)',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


--
-- Nerd States
--

DROP TABLE IF EXISTS `nerd_states`;
CREATE TABLE IF NOT EXISTS `nerd_states` (
  `code` char(2) NOT NULL COMMENT 'Min(2)',
  `name` char(32) NOT NULL COMMENT 'Min(4)',
  PRIMARY KEY (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Nerd States - Data
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


--
-- Nerd Users
--

DROP TABLE IF EXISTS `nerd_users`;
CREATE TABLE IF NOT EXISTS `nerd_users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `super` tinyint(1) NOT NULL DEFAULT '0',
  `username` char(32) NOT NULL COMMENT 'username',
  `email` char(255) NOT NULL COMMENT 'email',
  `password` char(81) NOT NULL COMMENT 'password',
  `password_reset_hash` char(81) DEFAULT NULL,
  `temp_password` char(81) DEFAULT NULL,
  `remember` char(81) DEFAULT NULL,
  `activation_hash` char(81) DEFAULT NULL,
  `ip` char(45) NOT NULL COMMENT 'ip',
  `status` enum('inactive','active','banned') NOT NULL DEFAULT 'inactive',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `locaters` (`username`,`email`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- Timestamp trigger

DELIMITER $$
DROP TRIGGER IF EXISTS `timestamp_nerd_users`;
CREATE TRIGGER `timestamp_nerd_users`
  BEFORE INSERT ON `nerd_users` FOR EACH ROW
  BEGIN
    SET NEW.`created_at` = CURRENT_TIMESTAMP();
  END
$$
DELIMITER ;

--
-- Nerd Users Metadata
--

DROP TABLE IF EXISTS `nerd_user_metadata`;
CREATE TABLE IF NOT EXISTS `nerd_user_metadata` (
  `user_id` int(5) unsigned NOT NULL,
  `first_name` char(36) DEFAULT NULL COMMENT 'min(3)',
  `last_name` char(36) DEFAULT NULL COMMENT 'min(3)',
  `zip` int(5) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;




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
