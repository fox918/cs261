SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `ordermgmt` ;
CREATE SCHEMA IF NOT EXISTS `ordermgmt` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `ordermgmt` ;

-- -----------------------------------------------------
-- Table `ordermgmt`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`users` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`users` (
  `uId` INT NOT NULL AUTO_INCREMENT ,
  `uName` VARCHAR(30) NOT NULL ,
  `uPw` VARCHAR(45) NOT NULL ,
  `uType` SET('admin','worker','store') NOT NULL ,
  `uLastLogin` DATETIME NULL ,
  `uSettings` TEXT NULL ,
  `uPhone` VARCHAR(45) NULL ,
  `uMobile` VARCHAR(45) NULL ,
  `uRealname` VARCHAR(45) NULL ,
  `uWage` FLOAT NULL ,
  `uAuthToken` VARCHAR(45) NULL ,
  PRIMARY KEY (`uId`) ,
  UNIQUE INDEX `uId_UNIQUE` (`uId` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`clients`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`clients` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`clients` (
  `cId` INT NOT NULL AUTO_INCREMENT ,
  `cName` VARCHAR(45) NOT NULL ,
  `cType` ENUM('business','retail') NOT NULL ,
  `cGender` ENUM('m','f','b') NOT NULL ,
  `cPhone` VARCHAR(45) NULL ,
  `cMobile` VARCHAR(45) NULL ,
  `cStreet` VARCHAR(45) NULL ,
  `cCity` VARCHAR(45) NULL ,
  PRIMARY KEY (`cId`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`jobs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`jobs` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`jobs` (
  `jId` INT NOT NULL AUTO_INCREMENT ,
  `jName` VARCHAR(45) NOT NULL ,
  `jDesc` TEXT NULL ,
  `jStage` ENUM('evaluation','processing','billing','finished') NULL ,
  `jResp` INT NOT NULL ,
  `clients_cId` INT NOT NULL ,
  `Creator users_uId` INT NOT NULL ,
  `jCreationDate` DATE NOT NULL ,
  PRIMARY KEY (`jId`, `clients_cId`, `Creator users_uId`) ,
  INDEX `fk_jobs_users1_idx` (`jResp` ASC) ,
  INDEX `fk_jobs_clients1_idx` (`clients_cId` ASC) ,
  INDEX `fk_jobs_users2_idx` (`Creator users_uId` ASC) ,
  UNIQUE INDEX `jId_UNIQUE` (`jId` ASC) ,
  CONSTRAINT `fk_jobs_users1`
    FOREIGN KEY (`jResp` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jobs_clients1`
    FOREIGN KEY (`clients_cId` )
    REFERENCES `ordermgmt`.`clients` (`cId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jobs_users2`
    FOREIGN KEY (`Creator users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`shedule`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`shedule` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`shedule` (
  `sId` INT NOT NULL AUTO_INCREMENT ,
  `sStart` DATETIME NOT NULL ,
  `sStop` DATETIME NOT NULL ,
  `jobs_jId` INT NOT NULL ,
  `sComment` TEXT NULL ,
  `users_uId` INT NOT NULL ,
  PRIMARY KEY (`sId`, `jobs_jId`, `users_uId`) ,
  INDEX `fk_shedule_jobs1_idx` (`jobs_jId` ASC) ,
  INDEX `fk_shedule_users1_idx` (`users_uId` ASC) ,
  CONSTRAINT `fk_shedule_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_shedule_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`materials`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`materials` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`materials` (
  `mId` INT NOT NULL AUTO_INCREMENT ,
  `mName` VARCHAR(45) NOT NULL ,
  `mDesc` TEXT NULL ,
  `mState` ENUM('order','arrived','used') NULL ,
  `mDelDate` DATE NULL ,
  `mPrice` FLOAT NULL ,
  `mQuantity` INT NOT NULL ,
  `jobs_jId` INT NOT NULL ,
  PRIMARY KEY (`mId`, `jobs_jId`) ,
  INDEX `fk_materials_jobs1_idx` (`jobs_jId` ASC) ,
  CONSTRAINT `fk_materials_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`comText`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`comText` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`comText` (
  `coTextId` INT NOT NULL AUTO_INCREMENT ,
  `coTitle` VARCHAR(45) NOT NULL ,
  `coText` TEXT NULL ,
  `coDate` DATETIME NULL ,
  `coChange` DATETIME NULL ,
  `jobs_jId` INT NOT NULL ,
  `users_uId` INT NOT NULL ,
  PRIMARY KEY (`coTextId`, `jobs_jId`, `users_uId`) ,
  INDEX `fk_comments_jobs1_idx` (`jobs_jId` ASC) ,
  INDEX `fk_comments_users1_idx` (`users_uId` ASC) ,
  CONSTRAINT `fk_comments_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`comWork`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`comWork` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`comWork` (
  `coWorkId` INT NOT NULL AUTO_INCREMENT ,
  `coTitle` VARCHAR(45) NOT NULL ,
  `coDesc` TEXT NULL ,
  `coTime` INT NULL ,
  `coDate` DATETIME NULL ,
  `coChange` DATETIME NULL ,
  `users_uId` INT NOT NULL ,
  `jobs_jId` INT NOT NULL ,
  `jobs_clients_cId` INT NOT NULL ,
  `jobs_Creator users_uId` INT NOT NULL ,
  PRIMARY KEY (`coWorkId`, `users_uId`, `jobs_jId`, `jobs_clients_cId`, `jobs_Creator users_uId`) ,
  INDEX `fk_comWork_users1_idx` (`users_uId` ASC) ,
  INDEX `fk_comWork_jobs1_idx` (`jobs_jId` ASC, `jobs_clients_cId` ASC, `jobs_Creator users_uId` ASC) ,
  CONSTRAINT `fk_comWork_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comWork_jobs1`
    FOREIGN KEY (`jobs_jId` , `jobs_clients_cId` , `jobs_Creator users_uId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` , `clients_cId` , `Creator users_uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordermgmt`.`comAttach`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`comAttach` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`comAttach` (
  `coAtId` INT NOT NULL AUTO_INCREMENT ,
  `coTitle` VARCHAR(45) NOT NULL ,
  `coDesc` TEXT NULL ,
  `coDate` DATETIME NULL ,
  `coChange` DATETIME NULL ,
  `coResource` TEXT NULL ,
  `users_uId` INT NOT NULL ,
  `jobs_jId` INT NOT NULL ,
  `jobs_clients_cId` INT NOT NULL ,
  `jobs_Creator users_uId` INT NOT NULL ,
  PRIMARY KEY (`coAtId`, `users_uId`, `jobs_jId`, `jobs_clients_cId`, `jobs_Creator users_uId`) ,
  INDEX `fk_comAttach_users1_idx` (`users_uId` ASC) ,
  INDEX `fk_comAttach_jobs1_idx` (`jobs_jId` ASC, `jobs_clients_cId` ASC, `jobs_Creator users_uId` ASC) ,
  CONSTRAINT `fk_comAttach_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comAttach_jobs1`
    FOREIGN KEY (`jobs_jId` , `jobs_clients_cId` , `jobs_Creator users_uId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` , `clients_cId` , `Creator users_uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ordermgmt`.`history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`history` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`history` (
  `hTime` DATETIME NOT NULL ,
  `hType` VARCHAR(45) NOT NULL ,
  `hText` TEXT NOT NULL ,
  `jobs_jId` INT NOT NULL ,
  `materials_mId` INT NOT NULL ,
  `shedule_sId` INT NOT NULL ,
  `comments_coId` INT NOT NULL ,
  `comWork_coWorkId` INT NOT NULL ,
  `comAttach_coAtId` INT NOT NULL ,
  PRIMARY KEY (`jobs_jId`) ,
  INDEX `fk_history_materials1_idx` (`materials_mId` ASC) ,
  INDEX `fk_history_shedule1_idx` (`shedule_sId` ASC) ,
  INDEX `fk_history_comments1_idx` (`comments_coId` ASC) ,
  INDEX `fk_history_comWork1_idx` (`comWork_coWorkId` ASC) ,
  INDEX `fk_history_comAttach1_idx` (`comAttach_coAtId` ASC) ,
  CONSTRAINT `fk_history_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `ordermgmt`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_materials1`
    FOREIGN KEY (`materials_mId` )
    REFERENCES `ordermgmt`.`materials` (`mId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_shedule1`
    FOREIGN KEY (`shedule_sId` )
    REFERENCES `ordermgmt`.`shedule` (`sId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comments1`
    FOREIGN KEY (`comments_coId` )
    REFERENCES `ordermgmt`.`comText` (`coTextId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comWork1`
    FOREIGN KEY (`comWork_coWorkId` )
    REFERENCES `ordermgmt`.`comWork` (`coWorkId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comAttach1`
    FOREIGN KEY (`comAttach_coAtId` )
    REFERENCES `ordermgmt`.`comAttach` (`coAtId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `ordermgmt`.`updates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ordermgmt`.`updates` ;

CREATE  TABLE IF NOT EXISTS `ordermgmt`.`updates` (
  `history_jobs_jId` INT NOT NULL ,
  `users_uId` INT NOT NULL ,
  PRIMARY KEY (`history_jobs_jId`, `users_uId`) ,
  INDEX `fk_updates_users1_idx` (`users_uId` ASC) ,
  CONSTRAINT `fk_updates_history1`
    FOREIGN KEY (`history_jobs_jId` )
    REFERENCES `ordermgmt`.`history` (`jobs_jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_updates_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `ordermgmt`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
