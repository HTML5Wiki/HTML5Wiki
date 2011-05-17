
-- -----------------------------------------------------
-- Table `html5wiki`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`User` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`User` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`MediaVersion` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersion` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `timestamp` INT NOT NULL ,
  `userId` INT NOT NULL ,
  `permalink` VARCHAR(255) NOT NULL ,
  `state` ENUM('PUBLISHED','DRAFT','TRASH') NOT NULL DEFAULT PUBLISHED ,
  `versionComment` VARCHAR(140) NULL ,
  `mediaVersionType` ENUM('ARTICLE','FILE') NOT NULL DEFAULT ARTICLE ,
  PRIMARY KEY (`id`, `timestamp`)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`Tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`Tag` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`Tag` (
  `tag` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`tag`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`ArticleVersion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`ArticleVersion` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`ArticleVersion` (
  `mediaVersionId` INT NOT NULL ,
  `mediaVersionTimestamp` INT NOT NULL ,
  `title` VARCHAR(200) NOT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`Mimetype`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`Mimetype` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`Mimetype` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(60) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `type_UNIQUE` (`type` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`License`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`License` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`License` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(140) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`FileVersion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`FileVersion` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`FileVersion` (
  `mediaVersionId` INT NOT NULL ,
  `mediaVersionTimestamp` INT NOT NULL ,
  `name` VARCHAR(200) NOT NULL ,
  `filepath` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `origin` VARCHAR(140) NULL ,
  `author` VARCHAR(140) NULL ,
  `mimetypeId` INT NOT NULL ,
  `licenseId` INT NOT NULL ,
  PRIMARY KEY (`mediaVersionId`, `mediaVersionTimestamp`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `html5wiki`.`MediaVersionTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `html5wiki`.`MediaVersionTag` ;

CREATE  TABLE IF NOT EXISTS `html5wiki`.`MediaVersionTag` (
  `tagTag` VARCHAR(50) NOT NULL ,
  `mediaVersionId` INT NOT NULL ,
  `mediaVersionTimestamp` INT NOT NULL ,
  PRIMARY KEY (`tagTag`, `mediaVersionId`, `mediaVersionTimestamp`) )
ENGINE = MyISAM;