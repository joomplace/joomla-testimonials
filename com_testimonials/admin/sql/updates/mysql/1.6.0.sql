--
--  `#__tm_testimonials_settings`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_settings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials` (
  `id` int(11) NOT NULL auto_increment,
  `published` int(11) NOT NULL default '1',
  `ordering` int(11) NOT NULL default '0',
  `t_caption` varchar(100) NOT NULL default '',
  `t_author` text NOT NULL,
  `testimonial` text NOT NULL,
  `author_description` text NOT NULL,
  `ip_addr` varchar(15) NOT NULL default '',
  `user_id_t` int(11) NOT NULL default '0',
  `photo` varchar(250) NOT NULL default '',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `blocked` enum('0','1') default NULL,
  `date_added` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_rating`
--

CREATE TABLE IF NOT EXISTS `#__tm_rating` (
  `tm_id` int(10) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `voteval` tinyint(1) NOT NULL,
  PRIMARY KEY (`tm_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials_conformity`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_conformity` (
  `id_ti` int(11) NOT NULL default '0',
  `id_tag` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials_custom`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_custom` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `type` varchar(10) NOT NULL default 'text',
  `required` int(2) default '0',
  `descr` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials_items_fields`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_items_fields` (
  `item_id` int(11) NOT NULL default '0',
  `field_id` int(11) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`item_id`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


--
--  `#__tm_testimonials_tags`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag_name` varchar(100) NOT NULL default '',
  `published` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials_tag_assign`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_tag_assign` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL default '',
  `tag_id` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--  `#__tm_testimonials_templates`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_templates` (
  `id` int(3) NOT NULL auto_increment,
  `temp_name` varchar(45) NOT NULL default 'default',
  `html` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- 

--
--  `#__tm_testimonials_dashboard_items`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------
-- 
INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `name`, `published`, `ordering`, `type`, `required`, `descr`) VALUES
(1, 'Website', 1, 2, 'url', 0, ''),
(2, 'Email', 1, 3, 'textemail', 0, ''),
(3, 'Rating', 1, 0, 'rating', 1, 'rating');

INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES
(1, 'Testimonials Management', 'index.php?option=com_testimonials&view=topics', '/administrator/components/com_testimonials/assets/images/management48.png', 1),
(2, 'Testimonials Settings', 'index.php?option=com_config&view=component&component=com_testimonials&return=aHR0cDovL2xvY2FsaG9zdC9qb29tbGEzMS9hZG1pbmlzdHJhdG9yL2luZGV4LnBocD9vcHRpb249Y29tX3Rlc3RpbW9uaWFscyZ2aWV3PWRhc2hib2FyZA%3D%3D', '/administrator/components/com_testimonials/assets/images/settings48.png', 1),
(3, 'Help', 'http://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm', '/administrator/components/com_testimonials/assets/images/help48.png', 1);


INSERT INTO `#__tm_testimonials_settings` (`id`, `params`) VALUES
(1, '{"template":"black","show_title":"0","texttitle":"","show_caption":"1","show_captcha":"1","show_lasttofirst":"1","use_editor":"1","show_addimage":"1","show_recaptcha":"0","recaptcha_publickey":"","recaptcha_privatekey":"","show_tags":"1","use_jsoc":"0","show_avatar":"1","use_cb":"0","thumb_width":"110","show_authorname":"1","show_authordesc":"1","addingbyuser":"","addingbyunreg":"","allow_photo":"1","show_tagsforusers":"0","autoapprove":"0","timeout":"4","symb_qty":"200","th_width":"110","show_avatar_module":"1","show_readmore":"0","show_add_new":"0","show_first":"0","tag_options":"0","tm_version":"1.7.1 (build 004)","curr_date":""}');


INSERT INTO `#__tm_testimonials_templates` (`id`, `temp_name`, `html`) VALUES
(1, 'default', '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n   <div class=\"testimonial_caption\"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n       <div class=\"testimonial_text_separator\"><!--x--></div>\r\n   </div>\r\n   <div class=\"testimonial_author\" itemprop=\"author\">[author] [Website]<!--x--></div>\r\n   <div class=\"testimonial_author_descr\">[author_descr] <b>[Email]<!--x--></b></div>\r\n   <div class=\"tm_clr\"><!--x--></div>\r\n</div>'),
(2, 'black', '<div class=\"testimonial_inner\">\\r\\n<div class=\"testimonial_caption \"><h4>[caption]<!--x--></h4></div>	\\r\\n<div class=\"testimonial_image\">[avatar]</div>\\r\\n<div class=\"testimonial_text\">[testimonial]\\r\\n<div class=\"testimonial_text_separator \"><!--x--></div>			\\r\\n</div>			\\r\\n<div class=\"testimonial_author \">[author]<!--x--></div>\\r\\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\\r\\n<div class=\"tm_clr\"><!--x--></div>\\r\\n</div>'),
(3, 'black2', '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n<div class=\"testimonial_text_separator \"><!--x--></div>\r\n</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div>\r\n</div>'),
(4, 'blacktip', '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_inner\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]</div>\r\n</div> 	\r\n</div>\r\n<div class=\"avatar_on\">\r\n<div class=\"testimonial_steam \"><!--x--></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div> 	\r\n</div>'),
(5, 'classic', '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">		\r\n<div class=\"testimonial_caption\" itemprop=\"name\">[caption]</div>\r\n<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td width=\"61\" valign=\"top\" class=\"testimonial_text\" itemprop=\"reviewBody\">[avatar][testimonial]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\" itemprop=\"author\">[author]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\">[author_descr]</td>		\r\n</tr>	\r\n</tbody>\r\n</table>	\r\n</div>'),
(6, 'gray', '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">			\r\n   <div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>			\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>			\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]				\r\n     <div class=\"testimonial_text_separator \"><!--x--></div>			\r\n   </div>	\r\n   <div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>			\r\n   <div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>			\r\n   <div class=\"tm_clr\"><!--x--></div>		\r\n</div>'),
(7, 'business', '<span itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_text\">\r\n<h4 class=\"testimonial_caption\" itemprop=\"name\">[caption]</h4>\r\n<blockquote>\r\n<p itemprop=\"reviewBody\">[testimonial]</p>\r\n</blockquote>\r\n</div>\r\n<div class=\"testimonial_sign\" itemprop=\"image\">\r\n[avatar]\r\n<cite>\r\n<span class=\"testimonial_author\" itemprop=\"author\">[author]</span>\r\n<span class=\"testimonial_author_descr\">[author_descr][Website]</span>\r\n</cite>\r\n</div>\r\n</span>'),
(8, 'Test1', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_text"><p>[testimonial]</p>
			<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>'),
(9, 'Test2', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_bg_left"><img src="img/bg_left.png" alt=""></div>
		<div class="testimonial_text"><p>[testimonial]</p>
			<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_bg_right"><img src="img/bg_right.png" alt=""></div>	
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>'),
(10, 'Test3', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_text"><p>[testimonial]</p>
		<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>'),
(11, 'Test4', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_text"><p>[testimonial]</p>
		<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>'),
(12, 'Test5', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_text"><p>[testimonial]</p>
		<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>'),
(13, 'Test6', '<div class="testimonial_inner">
		<div class="testimonial_caption"><h4>[caption]<!--x--></h4></div>
		<div class="testimonial_image">[avatar]</div>
		<div class="testimonial_text"><p>[testimonial]</p>
		<div class="testimonial_text_separator"><!--x--></div>
		</div>
	<div class="testimonial_author">[author] [Website]<!--x--></div>
	<div class="testimonial_author_descr">[author_descr]  <b><!--x--></b></div>
	<div class="tm_clr"><!--x--></div></div>');
