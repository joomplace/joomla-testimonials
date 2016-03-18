ALTER TABLE `#__tm_comments` 
ADD `lft` INT( 11 ) NOT NULL DEFAULT '0',
ADD `rgt` INT( 11 ) NOT NULL DEFAULT  '0',
ADD `level` INT( 10 ) NOT NULL DEFAULT  '0';
ADD `title` varchar(255) NOT NULL,
ADD `alias` varchar(255) NOT NULL DEFAULT '',
ADD `access` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
ADD `path` varchar(255) NOT NULL DEFAULT '',
ADD KEY `idx_left_right` (`lft`,`rgt`);