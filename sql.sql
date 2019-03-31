CREATE  TABLE `labProj_users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(250) NOT NULL ,
  `password` VARCHAR(250) NOT NULL ,
  `avatar` VARCHAR(250) ,
  `email` VARCHAR(250) NOT NULL ,
  `dob` DATE NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )

CREATE  TABLE `labProj_videos` (
  `userid` INT(11) NOT NULL ,
  `vidurl` VARCHAR(255) NOT NULL ,
  `views` INT NULL DEFAULT 0 ,
  `date` DATETIME NOT NULL ,
  `descreption` TEXT NULL ,
  `title` VARCHAR(250) NOT NULL ,
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) );

  CREATE  TABLE `labProj_files` (
  `fileurl` VARCHAR(255) NOT NULL ,
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `vidid` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `vidid_idx` (`vidid` ASC) ,
  CONSTRAINT `vidid`
    FOREIGN KEY (`vidid` )
    REFERENCES `201601310`.`labProj_videos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE  TABLE `labProj_comments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `vidid` INT(11) NOT NULL ,
  `parentid` INT NULL DEFAULT 0 ,
  `content` TEXT NOT NULL ,
  `userid` INT(11) NOT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `userid_idx` (`userid` ASC) ,
  INDEX `vidid_idx` (`vidid` ASC) ,
  CONSTRAINT `useridforeigncons`
    FOREIGN KEY (`userid` )
    REFERENCES `201601310`.`labProj_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `vididforeigncons`
    FOREIGN KEY (`vidid` )
    REFERENCES `201601310`.`labProj_videos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

    CREATE  TABLE `labProj_likes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `userid` INT NOT NULL ,
  `videoid` INT NOT NULL ,
  `type` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `vidIdForeign_idx` (`videoid` ASC) ,
  INDEX `userIdForeign_idx` (`userid` ASC) ,
  CONSTRAINT `vidIdForeign`
    FOREIGN KEY (`videoid` )
    REFERENCES `201601310`.`labProj_videos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `userIdForeign`
    FOREIGN KEY (`userid` )
    REFERENCES `201601310`.`labProj_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


