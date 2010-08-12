CREATE TABLE `sony`.`variables` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`library_id` INT( 11 ) NOT NULL ,
`authentication_variable` VARCHAR( 100 ) NOT NULL ,
`authentication_response` VARCHAR( 100 ) NOT NULL ,
`error_msg` VARCHAR( 200 ) NOT NULL ,
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL
) ENGINE = MYISAM ;