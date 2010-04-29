CREATE TABLE `sony`.`siteconfigs` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`soption` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`svalue` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;


INSERT INTO `sony`.`siteconfigs` (
`id` ,
`soption` ,
`svalue`
)
VALUES (
NULL , 'suggestion_counter', '40'
);
