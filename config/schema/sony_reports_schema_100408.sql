CREATE TABLE `sony`.`sony_reports` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`report_name` VARCHAR( 255 ) NOT NULL ,
`report_location` VARCHAR( 255 ) NOT NULL ,
`is_uploaded` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no',
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL
) ENGINE = MYISAM ;
