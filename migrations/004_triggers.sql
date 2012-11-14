--
-- Nerd-CMS data integrity triggers
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
-- Trigger for nerd_page versioning. Automatically add old data into the
-- versioning table before updating an older page record.
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


SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;