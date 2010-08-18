ALTER TABLE `variables` ADD `comparison_operator` CHAR( 1 ) NOT NULL AFTER `authentication_response` ;

UPDATE variables SET comparison_operator = '=';