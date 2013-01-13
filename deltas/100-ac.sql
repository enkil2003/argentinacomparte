CREATE  TABLE IF NOT EXISTS `argentinacomparte`.`banners` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `image` VARCHAR(255) NOT NULL ,
  `title` VARCHAR(255) NULL DEFAULT NULL ,
  `position` INT(4) NOT NULL DEFAULT 0 ,
  `href` VARCHAR(255) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `image_UNIQUE` (`image` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_spanish_ci

