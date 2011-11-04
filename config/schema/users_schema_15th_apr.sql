ALTER TABLE `libraries` ADD `library_language` CHAR( 5 ) NOT NULL DEFAULT 'en' AFTER `library_unlimited` ;
CREATE TABLE `sony`.`languages` (
`id` INT( 10 ) NOT NULL ,
`short_name` VARCHAR( 30 ) NOT NULL ,
`full_name` VARCHAR( 100 ) NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `languages` ADD `created` DATETIME NULL AFTER `full_name` ,
ADD `modified` DATETIME NULL AFTER `created` ;

ALTER TABLE `languages` ADD `status` ENUM( 'active', 'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive' AFTER `full_name` ;
ALTER TABLE `languages` CHANGE `id_language` `id` INT( 10 ) NOT NULL AUTO_INCREMENT 

ALTER TABLE `variables` ADD `message_no` VARCHAR( 100 ) NULL AFTER `comparison_operator` ;
ALTER TABLE `variables` CHANGE `message_no` `message_no` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '64';


ALTER TABLE `variables` ADD `result_arr` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'variable' AFTER `comparison_operator` 

For running indexer on DB1 after indexer run on DB1 we need to establish a ssh connection and execute the command there..for that we need to add the public key for ing1 on DB1 as this will not ask for password prompt and we can go the spinx directory and run the indexer there.



INSERT INTO `sony`.`groups` (
`id` ,
`type`
)
VALUES (
NULL , 'Consortium'
);

ALTER TABLE `users` ADD `consortium` VARCHAR( 255 ) NULL AFTER `sales` 
 