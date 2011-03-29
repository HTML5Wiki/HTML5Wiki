SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `html5wiki` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `html5wiki` ;

-- -----------------------------------------------------
-- Table `html5wiki`.`User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersion` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `timestamp` TIMESTAMP NOT NULL ,
  `previousMediaVersionTimtestamp` TIMESTAMP NULL ,
  `userId` INT NOT NULL ,
  `permalink` VARCHAR(255) NOT NULL ,
  `state` ENUM('PUBLISHED','DRAFT','TRASH') NOT NULL DEFAULT PUBLISHED ,
  `versionComment` VARCHAR(140) NULL ,
  `mediaVersionType` ENUM('ARTICLE','FILE') NOT NULL DEFAULT ARTICLE ,
  PRIMARY KEY (`id`, `timestamp`) ,
  UNIQUE INDEX `permalink_UNIQUE` (`permalink` ASC) ,
  INDEX `fk_MediaVersion_User` (`userId` ASC) ,
  INDEX `fk_MediaVersion_MediaVersion1` (`previousMediaVersionTimtestamp` ASC) ,
  CONSTRAINT `fk_MediaVersion_User`
    FOREIGN KEY (`userId` )
    REFERENCES `html5wiki`.`User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MediaVersion_MediaVersion1`
    FOREIGN KEY (`previousMediaVersionTimtestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`timestamp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`Tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`Tag` (
  `tag` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`tag`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`ArticleVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`ArticleVersion` (
  `mediaVersionId` INT NOT NULL ,
  `mediaVersionTimestamp` TIMESTAMP NOT NULL ,
  `title` VARCHAR(200) NOT NULL ,
  `content` TEXT NOT NULL ,
  INDEX `fk_ArticleVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) ,
  CONSTRAINT `fk_ArticleVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` , `mediaVersionTimestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` , `timestamp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`Mimetype`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`Mimetype` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(60) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `type_UNIQUE` (`type` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`License`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`License` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(140) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`FileVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`FileVersion` (
  `mediaVersionId` INT NOT NULL ,
  `mediaVersionTimestamp` TIMESTAMP NOT NULL ,
  `name` VARCHAR(200) NOT NULL ,
  `filepath` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `origin` VARCHAR(140) NULL ,
  `author` VARCHAR(140) NULL ,
  `mimetypeId` INT NOT NULL ,
  `licenseId` INT NOT NULL ,
  INDEX `fk_FileVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) ,
  INDEX `fk_FileVersion_Mimetype1` (`mimetypeId` ASC) ,
  INDEX `fk_FileVersion_License1` (`licenseId` ASC) ,
  CONSTRAINT `fk_FileVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` , `mediaVersionTimestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` , `timestamp` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FileVersion_Mimetype1`
    FOREIGN KEY (`mimetypeId` )
    REFERENCES `html5wiki`.`Mimetype` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FileVersion_License1`
    FOREIGN KEY (`licenseId` )
    REFERENCES `html5wiki`.`License` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersionTags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersionTags` (
  `tagTag` VARCHAR(50) NOT NULL ,
  `mediaVersionId` INT NOT NULL ,
  PRIMARY KEY (`tagTag`, `mediaVersionId`) ,
  INDEX `fk_Tag_has_MediaVersion_MediaVersion1` (`mediaVersionId` ASC) ,
  INDEX `fk_Tag_has_MediaVersion_Tag1` (`tagTag` ASC) ,
  CONSTRAINT `fk_Tag_has_MediaVersion_Tag1`
    FOREIGN KEY (`tagTag` )
    REFERENCES `html5wiki`.`Tag` (`tag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tag_has_MediaVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
