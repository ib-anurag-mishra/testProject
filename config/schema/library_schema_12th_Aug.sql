ALTER TABLE `downloads` ADD `history` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `ip`;
UPDATE variables SET created = NOW(),modified = NOW();