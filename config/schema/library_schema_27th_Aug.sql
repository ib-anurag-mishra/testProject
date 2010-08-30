ALTER TABLE `libraries` ADD `library_ezproxy_secret` VARCHAR( 100 ) NULL AFTER `library_sip_location` ,
ADD `library_ezproxy_referral` VARCHAR( 255 ) NULL AFTER `library_ezproxy_secret` 