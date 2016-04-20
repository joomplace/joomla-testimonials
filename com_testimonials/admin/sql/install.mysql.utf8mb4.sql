
--
-- Table structure for table `#__tm_comments`
--

CREATE TABLE `#__tm_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testimonial` int(11) NOT NULL,
  `text` text NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `access` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_left_right` (`lft`,`rgt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `#__tm_rating`
--

CREATE TABLE IF NOT EXISTS `#__tm_rating` (
  `tm_id` int(10) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `voteval` tinyint(1) NOT NULL,
  PRIMARY KEY (`tm_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` int(11) NOT NULL DEFAULT '1',
  `is_approved` int(11) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `t_caption` varchar(100) NOT NULL DEFAULT '',
  `t_author` text NOT NULL,
  `testimonial` text NOT NULL,
  `author_description` text NOT NULL,
  `ip_addr` varchar(15) NOT NULL DEFAULT '',
  `user_id_t` int(11) NOT NULL DEFAULT '0',
  `photo` varchar(250) NOT NULL DEFAULT '',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `blocked` enum('0','1') DEFAULT NULL,
  `date_added` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `comment` text NOT NULL,
  `catid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_conformity`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_conformity` (
  `id_ti` int(11) NOT NULL DEFAULT '0',
  `id_tag` int(11) NOT NULL DEFAULT '0',
  KEY `id_ti` (`id_ti`,`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_custom`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `required` int(2) DEFAULT '0',
  `display` int(2) NOT NULL DEFAULT '1',
  `descr` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_dashboard_items`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_items_fields`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_items_fields` (
  `item_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_id`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_settings`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_tags`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL DEFAULT '',
  `published` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_tag_assign`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_tag_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  `tag_id` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `#__tm_testimonials_templates`
--

CREATE TABLE IF NOT EXISTS `#__tm_testimonials_templates` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `temp_name` varchar(45) NOT NULL DEFAULT 'default',
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;
