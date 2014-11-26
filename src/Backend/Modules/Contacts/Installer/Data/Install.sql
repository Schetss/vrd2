CREATE  TABLE IF NOT EXISTS `contacts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The unique ID for this contact.' ,
  `user_id` INT(11) NOT NULL COMMENT 'The ID of the user that added this contact.' ,
  `language` VARCHAR(10) NOT NULL COMMENT 'The language of this contact.' ,
  `name` VARCHAR(128) NOT NULL COMMENT 'The original author of this contact.' ,
  `contact` TEXT NOT NULL COMMENT 'The actual contact.' ,
  `hidden` ENUM('N', 'Y') NOT NULL COMMENT 'Whether this contact is shown or not.' ,
  `sequence` INT(11) NOT NULL COMMENT 'The sequence of this contact.' ,
  `created_on` DATETIME NOT NULL COMMENT 'The date and time this contact was created.' ,
  `edited_on` DATETIME NOT NULL COMMENT 'The date and time this contact was last edited.' ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci;
