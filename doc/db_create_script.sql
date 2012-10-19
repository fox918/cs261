SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`users` (
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
-- Table `mydb`.`clients`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`clients` (
  `cId` INT NOT NULL ,
  `cName` VARCHAR(45) NULL ,
  `cType` ENUM('business','retail') NULL ,
  `cGender` ENUM('m','f','b') NULL ,
  `cPhone` VARCHAR(45) NULL ,
  `cMobile` VARCHAR(45) NULL ,
  `cStreet` VARCHAR(45) NULL ,
  `cPLZ` INT NULL ,
  PRIMARY KEY (`cId`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`jobs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`jobs` (
  `jId` INT NOT NULL ,
  `jName` VARCHAR(45) NULL ,
  `jDesc` TEXT NULL ,
  `jStage` ENUM('evaluation','processing','billing','finished') NULL ,
  `jResp` INT NOT NULL ,
  `clients_cId` INT NOT NULL ,
  `Creator users_uId` INT NOT NULL ,
  PRIMARY KEY (`jId`, `clients_cId`, `Creator users_uId`) ,
  INDEX `fk_jobs_users1_idx` (`jResp` ASC) ,
  INDEX `fk_jobs_clients1_idx` (`clients_cId` ASC) ,
  INDEX `fk_jobs_users2_idx` (`Creator users_uId` ASC) ,
  UNIQUE INDEX `jId_UNIQUE` (`jId` ASC) ,
  CONSTRAINT `fk_jobs_users1`
    FOREIGN KEY (`jResp` )
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jobs_clients1`
    FOREIGN KEY (`clients_cId` )
    REFERENCES `mydb`.`clients` (`cId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jobs_users2`
    FOREIGN KEY (`Creator users_uId` )
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`shedule`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`shedule` (
  `sId` INT NOT NULL ,
  `sStart` DATETIME NULL ,
  `sStop` DATETIME NULL ,
  `jobs_jId` INT NOT NULL ,
  `sComment` TEXT NULL ,
  `users_uId` INT NOT NULL ,
  PRIMARY KEY (`sId`, `jobs_jId`, `users_uId`) ,
  INDEX `fk_shedule_jobs1_idx` (`jobs_jId` ASC) ,
  INDEX `fk_shedule_users1_idx` (`users_uId` ASC) ,
  CONSTRAINT `fk_shedule_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `mydb`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_shedule_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`materials`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`materials` (
  `mId` INT NOT NULL ,
  `mName` VARCHAR(45) NULL ,
  `mDesc` TEXT NULL ,
  `mState` ENUM('order','arrived','used') NULL ,
  `mDelDate` DATE NULL ,
  `mPrice` FLOAT NULL ,
  `mQuantity` INT NULL ,
  `jobs_jId` INT NOT NULL ,
  PRIMARY KEY (`mId`, `jobs_jId`) ,
  INDEX `fk_materials_jobs1_idx` (`jobs_jId` ASC) ,
  CONSTRAINT `fk_materials_jobs1`
    FOREIGN KEY (`jobs_jId` )
    REFERENCES `mydb`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`comText`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`comText` (
  `coTextId` INT NOT NULL ,
  `coTitle` VARCHAR(45) NULL ,
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
    REFERENCES `mydb`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`comWork`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`comWork` (
  `coWorkId` INT NOT NULL ,
  `coTitle` VARCHAR(45) NULL ,
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
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comWork_jobs1`
    FOREIGN KEY (`jobs_jId` , `jobs_clients_cId` , `jobs_Creator users_uId` )
    REFERENCES `mydb`.`jobs` (`jId` , `clients_cId` , `Creator users_uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`comAttach`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`comAttach` (
  `coAtId` INT NOT NULL ,
  `coTitle` VARCHAR(45) NULL ,
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
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comAttach_jobs1`
    FOREIGN KEY (`jobs_jId` , `jobs_clients_cId` , `jobs_Creator users_uId` )
    REFERENCES `mydb`.`jobs` (`jId` , `clients_cId` , `Creator users_uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`history`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`history` (
  `hTime` DATETIME NULL ,
  `hType` VARCHAR(45) NULL ,
  `hText` TEXT NULL ,
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
    REFERENCES `mydb`.`jobs` (`jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_materials1`
    FOREIGN KEY (`materials_mId` )
    REFERENCES `mydb`.`materials` (`mId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_shedule1`
    FOREIGN KEY (`shedule_sId` )
    REFERENCES `mydb`.`shedule` (`sId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comments1`
    FOREIGN KEY (`comments_coId` )
    REFERENCES `mydb`.`comText` (`coTextId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comWork1`
    FOREIGN KEY (`comWork_coWorkId` )
    REFERENCES `mydb`.`comWork` (`coWorkId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_comAttach1`
    FOREIGN KEY (`comAttach_coAtId` )
    REFERENCES `mydb`.`comAttach` (`coAtId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `mydb`.`updates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`updates` (
  `history_jobs_jId` INT NOT NULL ,
  `users_uId` INT NOT NULL ,
  PRIMARY KEY (`history_jobs_jId`, `users_uId`) ,
  INDEX `fk_updates_users1_idx` (`users_uId` ASC) ,
  CONSTRAINT `fk_updates_history1`
    FOREIGN KEY (`history_jobs_jId` )
    REFERENCES `mydb`.`history` (`jobs_jId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_updates_users1`
    FOREIGN KEY (`users_uId` )
    REFERENCES `mydb`.`users` (`uId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `mydb`.`view1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`view1` (`id` INT);

-- -----------------------------------------------------
-- View `mydb`.`view1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`view1`;
USE `mydb`;
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
