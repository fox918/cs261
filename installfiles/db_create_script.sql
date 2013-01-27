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
    PRIMARY KEY (`uId`) )
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
            `Creator_uId` INT NOT NULL ,
            `jCreationDate` DATE NOT NULL ,
            PRIMARY KEY (`jId`) ,
            FOREIGN KEY (`jResp` )
            REFERENCES `ordermgmt`.`users` (`uId` )
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
            FOREIGN KEY (`clients_cId` )
            REFERENCES `ordermgmt`.`clients` (`cId` )
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
            FOREIGN KEY (`Creator_uId` )
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
                PRIMARY KEY (`sId`) ,
                FOREIGN KEY (`jobs_jId` )
                REFERENCES `ordermgmt`.`jobs` (`jId` )
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
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
                    PRIMARY KEY (`mId`) ,
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
                        PRIMARY KEY (`coTextId`) ,
                        FOREIGN KEY (`jobs_jId` )
                        REFERENCES `ordermgmt`.`jobs` (`jId` )
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION,
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
                            PRIMARY KEY (`coWorkId`) ,
                            FOREIGN KEY (`users_uId` )
                            REFERENCES `ordermgmt`.`users` (`uId` )
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION,
                            FOREIGN KEY (`jobs_jId`)
                            REFERENCES `ordermgmt`.`jobs` (`jId`)
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
                                PRIMARY KEY (`coAtId`) ,
                                FOREIGN KEY (`users_uId` )
                                REFERENCES `ordermgmt`.`users` (`uId` )
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                                FOREIGN KEY (`jobs_jId`)
                                REFERENCES `ordermgmt`.`jobs` (`jId`)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION)
                                ENGINE = InnoDB;


                                -- -----------------------------------------------------
                                -- Table `ordermgmt`.`history`
                                -- -----------------------------------------------------
                                DROP TABLE IF EXISTS `ordermgmt`.`history` ;

                                CREATE  TABLE IF NOT EXISTS `ordermgmt`.`history` (
                                    `hId` INT NOT NULL AUTO_INCREMENT ,
                                    `hTime` DATETIME NOT NULL ,
                                    `hType` VARCHAR(45) NOT NULL ,
                                    `hText` TEXT NOT NULL ,
                                    `jobs_jId` INT NOT NULL ,
                                    `materials_mId` INT,
                                    `shedule_sId` INT,
                                    `comments_coId` INT,
                                    `comWork_coWorkId` INT,
                                    `comAttach_coAtId` INT,
                                    PRIMARY KEY (`hId`),
                                    FOREIGN KEY (`jobs_jId` )
                                    REFERENCES `ordermgmt`.`jobs` (`jId` )
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION,
                                    FOREIGN KEY (`materials_mId` )
                                    REFERENCES `ordermgmt`.`materials` (`mId` )
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION,
                                    FOREIGN KEY (`shedule_sId` )
                                    REFERENCES `ordermgmt`.`shedule` (`sId` )
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION,
                                    FOREIGN KEY (`comments_coId` )
                                    REFERENCES `ordermgmt`.`comText` (`coTextId` )
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION,
                                    FOREIGN KEY (`comWork_coWorkId` )
                                    REFERENCES `ordermgmt`.`comWork` (`coWorkId` )
                                    ON DELETE NO ACTION
                                    ON UPDATE NO ACTION,
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
                                        `jobs_jId` INT NOT NULL ,
                                        `history_hId` INT NOT NULL,
                                        `users_uId` INT NOT NULL ,
                                        FOREIGN KEY (`jobs_jId` )
                                        REFERENCES `ordermgmt`.`jobs` (`jId` )
                                        ON DELETE NO ACTION
                                        ON UPDATE NO ACTION,
                                        FOREIGN KEY (`history_hId` )
                                        REFERENCES `ordermgmt`.`history` (`hId` )
                                        ON DELETE NO ACTION
                                        ON UPDATE NO ACTION,
                                        FOREIGN KEY (`users_uId` )
                                        REFERENCES `ordermgmt`.`users` (`uId` )
                                        ON DELETE NO ACTION
                                        ON UPDATE NO ACTION)
                                    ENGINE = InnoDB;
