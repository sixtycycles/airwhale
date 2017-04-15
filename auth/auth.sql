/*set up your data base as this name first. make sure you have the user testUser and password set */
USE ORONOISSUE;
/*table for auth and emailing. represents the user entity*/
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `userID`     INT(11)         NOT NULL AUTO_INCREMENT,
  `userName`   VARCHAR(100)    NOT NULL,
  `userEmail`  VARCHAR(100)    NOT NULL UNIQUE,
  `userPass`   VARCHAR(100)    NOT NULL,
  `userStatus` ENUM ('Y', 'N','A') NOT NULL DEFAULT 'N',
  `tokenCode`  VARCHAR(100)    NOT NULL,

  PRIMARY KEY (`userID`)
);

/*represents the individual problem occurances*/
CREATE TABLE IF NOT EXISTS `Problems` (
  `id`      INT          NOT NULL AUTO_INCREMENT
    PRIMARY KEY,
  `name`    VARCHAR(60)  NOT NULL,
  `lat`     VARCHAR(60)  NULL,
  `lon`     VARCHAR(60)  NULL,
  `address` VARCHAR(100) NOT NULL,
  `type`    VARCHAR(30)  NOT NULL
);








