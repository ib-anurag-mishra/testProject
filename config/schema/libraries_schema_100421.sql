ALTER TABLE `libraries` ADD `library_authentication_num` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `library_authentication_method`  ,
ADD `library_authentication_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `library_authentication_num`; 