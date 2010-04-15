ALTER TABLE `libraries` ADD `library_authentication_method` VARCHAR( 100 ) NOT NULL DEFAULT 'referral_url' AFTER `library_domain_name`;
ALTER TABLE `users` ADD `library_id` BIGINT NOT NULL AFTER `email`;
INSERT INTO `sony`.`groups` (`id` ,`type`)VALUES ('5', 'Library Patron');