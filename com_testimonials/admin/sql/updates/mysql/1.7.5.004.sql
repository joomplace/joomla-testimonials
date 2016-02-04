CREATE TABLE IF NOT EXISTS `#__tm_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testimonial` int(11) NOT NULL,
  `comment` int(11) DEFAULT '0',
  `text` text NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `photo` tinytext NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;