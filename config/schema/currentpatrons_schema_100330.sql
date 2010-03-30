CREATE TABLE IF NOT EXISTS `currentpatrons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libid` int(11) NOT NULL,
  `patronid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;