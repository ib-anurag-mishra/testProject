CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `library_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `patron_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `ProdID` bigint(20) NOT NULL,
  `ProductID` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `ISRC` char(12) COLLATE utf8_unicode_ci NOT NULL,
  `artist` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `album` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `track_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `week_start_date` datetime NOT NULL,
  `week_end_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `library_id` (`library_id`),
  KEY `patron_id` (`patron_id`),
  KEY `week_start_date` (`week_start_date`),
  KEY `week_end_date` (`week_end_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;