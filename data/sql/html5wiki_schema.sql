SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `html5wiki` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `html5wiki` ;

-- -----------------------------------------------------
-- Table `html5wiki`.`User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`User` (
  `id`		int(11) NOT NULL AUTO_INCREMENT ,
  `email`	VARCHAR(255) NOT NULL ,
  `name`	VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersion` (
  `id`			int(11) NOT NULL AUTO_INCREMENT ,
  `timestamp`	int(10) NOT NULL ,
  `previousMediaVersionTimtestamp` int(10) NULL ,
  `userId`		int(10) NOT NULL ,
  `permalink`	varchar(255) NOT NULL ,
  `state` ENUM('PUBLISHED','DRAFT','TRASH') NOT NULL,
  `versionComment` VARCHAR(140) NULL ,
  `mediaVersionType` ENUM('ARTICLE','FILE') NOT NULL,
  PRIMARY KEY (`id`, `timestamp`) ,
  UNIQUE INDEX `permalink_UNIQUE` (`permalink` ASC) ,
  INDEX `fk_MediaVersion_User` (`userId` ASC) ,
  INDEX `fk_MediaVersion_MediaVersion1` (`previousMediaVersionTimtestamp` ASC) ,
  CONSTRAINT `fk_MediaVersion_User`
    FOREIGN KEY (`userId` )
    REFERENCES `html5wiki`.`User` (`id` ),
  CONSTRAINT `fk_MediaVersion_MediaVersion1`
    FOREIGN KEY (`previousMediaVersionTimtestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`timestamp` ))
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`Tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`Tag` (
  `tag` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`tag`) )
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`ArticleVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`ArticleVersion` (
  `mediaVersionId` int(11) NOT NULL,
  `mediaVersionTimestamp` int(10) NOT NULL ,
  `title` varchar(200) NOT NULL ,
  `content` text NOT NULL ,
  INDEX `fk_ArticleVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) ,
  CONSTRAINT `fk_ArticleVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` , `mediaVersionTimestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` , `timestamp` ))
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`Mimetype`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`Mimetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `type` varchar(60) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `type_UNIQUE` (`type` ASC) )
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`License`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`License` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `name` varchar(140) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`FileVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`FileVersion` (
  `mediaVersionId` int(11)NOT NULL ,
  `mediaVersionTimestamp` int(10) NOT NULL ,
  `name` varchar(200) NOT NULL ,
  `filepath` varchar(255) NOT NULL ,
  `description` TEXT NULL ,
  `origin` varchar(140) NULL ,
  `author` varchar(140) NULL ,
  `mimetypeId` int(11) NOT NULL ,
  `licenseId` int(11) NOT NULL ,
  INDEX `fk_FileVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) ,
  INDEX `fk_FileVersion_Mimetype1` (`mimetypeId` ASC) ,
  INDEX `fk_FileVersion_License1` (`licenseId` ASC) ,
  CONSTRAINT `fk_FileVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` , `mediaVersionTimestamp` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` , `timestamp` ),
  CONSTRAINT `fk_FileVersion_Mimetype1`
    FOREIGN KEY (`mimetypeId` )
    REFERENCES `html5wiki`.`Mimetype` (`id` ),
  CONSTRAINT `fk_FileVersion_License1`
    FOREIGN KEY (`licenseId` )
    REFERENCES `html5wiki`.`License` (`id` ))
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersionTags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersionTags` (
  `tagTag` VARCHAR(50) NOT NULL ,
  `mediaVersionId` int(11) NOT NULL ,
  PRIMARY KEY (`tagTag`, `mediaVersionId`) ,
  INDEX `fk_Tag_has_MediaVersion_MediaVersion1` (`mediaVersionId` ASC) ,
  INDEX `fk_Tag_has_MediaVersion_Tag1` (`tagTag` ASC) ,
  CONSTRAINT `fk_Tag_has_MediaVersion_Tag1`
    FOREIGN KEY (`tagTag` )
    REFERENCES `html5wiki`.`Tag` (`tag` ),
  CONSTRAINT `fk_Tag_has_MediaVersion_MediaVersion1`
    FOREIGN KEY (`mediaVersionId` )
    REFERENCES `html5wiki`.`MediaVersion` (`id` ))
ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
