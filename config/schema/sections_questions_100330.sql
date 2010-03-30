--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` VALUES(0, 'General', '2010-03-25 13:02:08', '2010-03-25 13:02:08');

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `question` text COLLATE utf8_unicode_ci,
  `answer` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` VALUES(1, 0, 'I can''t find the artists I''m looking for.', 'We continue to grow our catalogue everyday. We already have over 400k tracks from your favorite Sony artists. We invite you to browse our entire inventory -we''re sure you''ll find something you like.', '2010-03-02 08:25:20', '2010-03-02 08:28:41');
INSERT INTO `questions` VALUES(2, 0, 'How can I transfer songs to my iPod/iPhone/iTouch?', '<p>Every song you download from Freegal Music is fully compatible with your iPod/iPhone/iTouch! However, you can only put music on to an iPod/iPhone/iTouch from iTunes, so you''ll need to transfer your Freegal Music downloads to iTunes in order to play them on your iPod/iPhone/iTouch.</p>\r\n\r\n<p>Here''s how to do it:</p>\r\n\r\n<p>- Once you''ve downloaded your music, it will be saved in a folder on your computer.</p>\r\n<p>- If you open this folder, you''ll see all the individual song files grouped together by album.</p>\r\n<p>- Drag each song over to your iTunes Library and release your mouse button with the song hovering over the Library.</p>\r\n<p>- The song will automatically be added to your iTunes Library.</p>\r\n<p>- Once your Freegal Music is successfully transferred to iTunes, you can add it to your iPod just like you would any other song.</p>\r\n', '2010-03-09 15:01:52', '2010-03-09 16:59:29');
