CREATE SCHEMA IF NOT EXISTS `html5wiki` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `html5wiki` ;

-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `User` (
  `id`		int(11) NOT NULL AUTO_INCREMENT ,
  `email`	VARCHAR(255) NOT NULL ,
  `name`	VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `MediaVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `MediaVersion` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `timestamp` int(10) NOT NULL ,
  `userId` INT NOT NULL ,
  `permalink` VARCHAR(255) NOT NULL ,
  `state` ENUM('PUBLISHED','DRAFT','TRASH') NOT NULL DEFAULT PUBLISHED ,
  `versionComment` VARCHAR(140) NULL ,
  `mediaVersionType` ENUM('ARTICLE','FILE') NOT NULL DEFAULT ARTICLE ,
  PRIMARY KEY (`id`, `timestamp`) ,
  UNIQUE INDEX `permalink_UNIQUE` (`permalink` ASC) ,
  INDEX `fk_MediaVersion_User` (`userId` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `Tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Tag` (
  `tag` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`tag`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `ArticleVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ArticleVersion` (
  `mediaVersionId` int(11) NOT NULL ,
  `mediaVersionTimestamp` int(10) NOT NULL ,
  `title` VARCHAR(200) NOT NULL ,
  `content` TEXT NOT NULL ,
  INDEX `fk_ArticleVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `Mimetype`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Mimetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(60) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `type_UNIQUE` (`type` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `License`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `License` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(140) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `FileVersion`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `FileVersion` (
  `mediaVersionId` int(11) NOT NULL ,
  `mediaVersionTimestamp` int(10) NOT NULL ,
  `name` VARCHAR(200) NOT NULL ,
  `filepath` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `origin` VARCHAR(140) NULL ,
  `author` VARCHAR(140) NULL ,
  `mimetypeId` int(11) NOT NULL ,
  `licenseId` int(11) NOT NULL ,
  INDEX `fk_FileVersion_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) ,
  INDEX `fk_FileVersion_Mimetype1` (`mimetypeId` ASC) ,
  INDEX `fk_FileVersion_License1` (`licenseId` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `MediaVersionTag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `MediaVersionTag` (
  `tagTag` VARCHAR(50) NOT NULL ,
  `mediaVersionId` int(11) NOT NULL ,
  `mediaVersionTimestamp` int(10) NOT NULL ,
  PRIMARY KEY (`tagTag`, `mediaVersionId`, `mediaVersionTimestamp`) ,
  INDEX `fk_Tag_has_MediaVersion_Tag1` (`tagTag` ASC) ,
  INDEX `fk_MediaVersionTags_MediaVersion1` (`mediaVersionId` ASC, `mediaVersionTimestamp` ASC) )
ENGINE = MyISAM;
