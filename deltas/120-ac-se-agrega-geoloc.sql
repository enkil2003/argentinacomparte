SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE  TABLE IF NOT EXISTS `argentinacomparte`.`gelocalization` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `description` TEXT NULL DEFAULT NULL ,
  `lat` VARCHAR(255) NOT NULL ,
  `lang` VARCHAR(255) NOT NULL ,
  `active` TINYINT(4) NOT NULL ,
  `tramite` INT(11) NULL DEFAULT NULL ,
  `news` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_gelocalization_tramite1` (`tramite` ASC) ,
  INDEX `fk_gelocalization_news1` (`news` ASC) ,
  CONSTRAINT `fk_gelocalization_tramite1`
    FOREIGN KEY (`tramite` )
    REFERENCES `argentinacomparte`.`tramite` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_gelocalization_news1`
    FOREIGN KEY (`news` )
    REFERENCES `argentinacomparte`.`news` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_spanish_ci
COMMENT = 'desc';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
