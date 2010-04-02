CREATE TABLE `sony`.`pages` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`page_name` VARCHAR( 100 ) NOT NULL ,
`page_content` TEXT NOT NULL ,
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL ,
UNIQUE (
`page_name`
)) ENGINE =  MYISAM ;