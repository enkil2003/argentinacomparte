SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER SCHEMA `argentinacomparte`  DEFAULT COLLATE latin1_spanish_ci ;

USE `argentinacomparte`;

ALTER TABLE `argentinacomparte`.`news_has_category` DROP FOREIGN KEY `fk_news_has_category_news1` ;

ALTER TABLE `argentinacomparte`.`geolocalization` DROP FOREIGN KEY `fk_gelocalization_tramite1` , DROP FOREIGN KEY `fk_gelocalization_news1` ;

ALTER TABLE `argentinacomparte`.`category` COLLATE = latin1_spanish_ci ;

ALTER TABLE `argentinacomparte`.`images` COLLATE = latin1_spanish_ci ;

ALTER TABLE `argentinacomparte`.`news_has_category` COLLATE = latin1_spanish_ci , 
  ADD CONSTRAINT `fk_news_has_category_news1`
  FOREIGN KEY (`news_id` )
  REFERENCES `argentinacomparte`.`news` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `argentinacomparte`.`user` COLLATE = latin1_spanish_ci ;

ALTER TABLE `argentinacomparte`.`faq` COLLATE = latin1_spanish_ci , DROP COLUMN `modification_date` , DROP COLUMN `user` , CHANGE COLUMN `active` `active` INT(2) NULL DEFAULT NULL  AFTER `creation_date` , CHANGE COLUMN `title` `title` VARCHAR(60) NOT NULL  , CHANGE COLUMN `copy` `copy` TEXT NOT NULL  , CHANGE COLUMN `body` `body` TEXT NOT NULL  , CHANGE COLUMN `modified_by` `modified_by` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `creation_date` `creation_date` DATE NULL DEFAULT NULL  , CHANGE COLUMN `news_id` `news_id` INT(11) NOT NULL  , 
  ADD CONSTRAINT `fk_faq_news1`
  FOREIGN KEY (`news_id` )
  REFERENCES `argentinacomparte`.`news` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, DROP PRIMARY KEY 
, ADD PRIMARY KEY (`id`) 
, ADD INDEX `fk_faq_news1` (`news_id` ASC) 
, DROP INDEX `fk_news_news1` 
, DROP INDEX `fk_news_user1` 
, DROP INDEX `fk_news_user` ;

ALTER TABLE `argentinacomparte`.`poll` CHANGE COLUMN `creation_date` `creation_date` DATETIME NULL DEFAULT NULL  ;

ALTER TABLE `argentinacomparte`.`geolocalization` DROP COLUMN `address` , CHANGE COLUMN `lat` `lat` VARCHAR(255) NOT NULL  , CHANGE COLUMN `lang` `lang` VARCHAR(255) NOT NULL  , CHANGE COLUMN `active` `active` TINYINT(4) NOT NULL  , 
  ADD CONSTRAINT `fk_geolocalization_tramite1`
  FOREIGN KEY (`tramite` )
  REFERENCES `argentinacomparte`.`tramite` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE, 
  ADD CONSTRAINT `fk_geolocalization_news1`
  FOREIGN KEY (`news` )
  REFERENCES `argentinacomparte`.`news` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
, ADD INDEX `fk_geolocalization_tramite1` (`tramite` ASC) 
, ADD INDEX `fk_geolocalization_news1` (`news` ASC) 
, DROP INDEX `fk_gelocalization_news1` 
, DROP INDEX `fk_gelocalization_tramite1` ;

DROP TABLE IF EXISTS `argentinacomparte`.`predeterminar` ;

ALTER TABLE `argentinacomparte`.`news` DROP FOREIGN KEY `fk_news_user1` , DROP FOREIGN KEY `fk_news_user` ;

ALTER TABLE `argentinacomparte`.`news` ADD COLUMN `draft` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0  AFTER `user`


ALTER TABLE `argentinacomparte`.`news`
ADD COLUMN `default` TINYINT(1) NULL DEFAULT 0 COMMENT 'La queria nombrar highlight pero por algun motivo el doctrine se me colgaba con ese nombre.'  AFTER `draft`;

ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `user` `user` INT(10) UNSIGNED NOT NULL  AFTER `news_id`;

ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `title` `title` VARCHAR(60) CHARACTER SET 'latin1' COLLATE 'latin1_spanish_ci' NULL DEFAULT NULL;


ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `title` `title` VARCHAR(60) CHARACTER SET 'latin1' COLLATE 'latin1_spanish_ci' NULL DEFAULT NULL;

ALTER TABLE `argentinacomparte`.`news`
CHANGE COLUMN `active` `active` INT(2) NOT NULL DEFAULT '1'  ,




ALTER TABLE `argentinacomparte`.`news` 
  ADD CONSTRAINT `fk_news_user1`
  FOREIGN KEY (`user` )
  REFERENCES `argentinacomparte`.`user` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

  
ALTER TABLE `argentinacomparte`.`news` 
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`)
;
  
  
  ALTER TABLE `argentinacomparte`.`news` 
DROP INDEX `fk_news_user1` 
,ADD INDEX `fk_news_user1` (`user` ASC);

ALTER TABLE `argentinacomparte`.`news`
 DROP INDEX `fk_news_user` ;

-- ALTER TABLE `argentinacomparte`.`news` CHANGE COLUMN `draft` `draft` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0  AFTER `user` , CHANGE COLUMN `default` `default` TINYINT(1) NULL DEFAULT 0 COMMENT 'La queria nombrar highlight pero por algun motivo el doctrine se me colgaba con ese nombre.'  AFTER `draft` ;

 
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
