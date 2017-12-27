CREATE TABLE `users_birthday` (
  `birthday_id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `birthday_uid`         INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `birthday_date`        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `birthday_photo`       VARCHAR(255)     NOT NULL,
  `birthday_description` TEXT             NOT NULL,
  `birthday_firstname`   VARCHAR(150)     NOT NULL,
  `birthday_lastname`    VARCHAR(150)     NOT NULL,
  `birthday_comments`    INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`birthday_id`),
  KEY `birthday_lastname` (`birthday_lastname`),
  KEY `birthday_date` (`birthday_date`),
  KEY `birthday_uid` (`birthday_uid`)
)
  ENGINE = MyISAM;
