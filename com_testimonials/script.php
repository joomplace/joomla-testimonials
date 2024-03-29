<?php
/**
 * Testimonials Component for Joomla 3
 *
 * @package   Testimonials
 * @author    JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class com_testimonialsInstallerScript
{

    function install($parent)
    {
        $db = JFactory::getDBO();

        @mkdir(JPATH_SITE . "/images/testimonials");
        @chmod(JPATH_SITE . "/images/testimonials", 0757);

        @mkdir(JPATH_SITE . "/images/com_testimonials");
        @chmod(JPATH_SITE . "/images/com_testimonials", 0757);

        $this->checkUCM();
    }

    function uninstall($parent)
    {
        echo '<p>' . JText::_('COM_TESTIMONIAL_UNINSTALL_TEXT') . '</p>';
    }

    function update($parent)
    {
        $errors = false;
        $db     = JFactory::getDBO();
        $query
                = 'CREATE TABLE IF NOT EXISTS `#__tm_testimonials_dashboard_items` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(255) NOT NULL,
                `url` varchar(255) NOT NULL,
                `icon` varchar(255) NOT NULL,
                `published` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;';
        $db->setQuery($query);
        $errors = $db->execute();

        $query = 'SELECT * FROM `#__tm_testimonials_dashboard_items` WHERE 1;';
        $db->setQuery($query);
        $db->execute();
        if ($db->getNumRows() === 0) {
            $query
                = "INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES
                (1, 'Testimonials Management', 'index.php?option=com_testimonials&view=topics', '/components/com_testimonials/assets/images/management48.png', 1),
                (2, 'Testimonials Settings', 'index.php?option=com_config&view=component&component=com_testimonials', '/components/com_testimonials/assets/images/settings48.png', 1),
                (3, 'Help', 'https://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm', '/components/com_testimonials/assets/images/help48.png', 1);";
            $db->setQuery($query);
            $errors = $db->execute();
        } else {
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_testimonials&view=topics' , `icon`='/components/com_testimonials/assets/images/management48.png' WHERE `title` = 'Testimonials Management';";
            $db->setQuery($query);
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_config&view=component&component=com_testimonials' , `icon`='/components/com_testimonials/assets/images/settings48.png' WHERE `title` = 'Testimonials Settings';";
            $db->setQuery($query);
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `icon`='/components/com_testimonials/assets/images/help48.png' WHERE `title` = 'Help';";
            $db->setQuery($query);
            $db->execute();
        }

        if ($errors) {
            //echo '<font style="font-size:2em; color:#55AA55;" >' . JText::_('COM_TESTIMONIAL_UPDATE_TEXT') . '</font><br/><br/>';
            // $this->greetingText();
        } else {
            JFactory::getApplication()->enqueueMessage($errors, 'error');
            return false;
        }

        $this->checkUCM();
    }

    function preflight($type, $parent)
    {
        if (file_exists(JPATH_ADMINISTRATOR . '/components/com_testimonials/testimonials.xml')) {
            $xml = simplexml_load_file(JPATH_ADMINISTRATOR . '/components/com_testimonials/testimonials.xml');
            $this->version_from = $version = preg_split('/(\s|\.)/', $xml->version);
            if ($version[0]) {
                if ($version[2] < 2) {
                    // update default template
                    $templates = array(
                        'default'  => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption\"><p class=\"h3\" itemprop=\"name\">[caption]</p></div>\r\n<div class=\"row row-fluid\">\r\n<div class=\"span3 col-xs-12 col-sm-3\">\r\n<div class=\"testimonial_image\" itemprop=\"image\"><a href=\"javascript:void(0);\" class=\"thumbnail\">[avatar]</a></div>\r\n</div>\r\n<blockquote class=\"span9 col-xs-12 col-sm-9\">\r\n<p class=\"testimonial_text\" itemprop=\"reviewBody\">\r\n[testimonial]\r\n</p>\r\n<p>[rating]</p>\r\n<small class=\"testimonial_author\" itemprop=\"author\">[author]</small>\r\n</blockquote>\r\n</div>\r\n<div class=\"testimonial_tags text-right\">[tags]</div>\r\n</div>',
                        'black'    => '<div class=\"testimonial_inner\">\\r\\n<div class=\"testimonial_caption \"><h4>[caption]<!--x--></h4></div>	\\r\\n<div class=\"testimonial_image\">[avatar]</div>\\r\\n<div class=\"testimonial_text\">[testimonial]\\r\\n<div class=\"testimonial_text_separator \"><!--x--></div>			\\r\\n</div>			\\r\\n<div class=\"testimonial_author \">[author]<!--x--></div>\\r\\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\\r\\n<div class=\"tm_clr\"><!--x--></div>\\r\\n</div>',
                        'black2'   => '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n<div class=\"testimonial_text_separator \"><!--x--></div>\r\n</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div>\r\n</div>',
                        'blacktip' => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_inner\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]</div>\r\n</div> 	\r\n</div>\r\n<div class=\"avatar_on\">\r\n<div class=\"testimonial_steam \"><!--x--></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div> 	\r\n</div>',
                        'classic'  => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">		\r\n<div class=\"testimonial_caption\" itemprop=\"name\">[caption]</div>\r\n<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td width=\"61\" valign=\"top\" class=\"testimonial_text\" itemprop=\"reviewBody\">[avatar][testimonial]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\" itemprop=\"author\">[author]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\">[author_descr]</td>		\r\n</tr>	\r\n</tbody>\r\n</table>	\r\n</div>',
                        'gray'     => '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">			\r\n   <div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>			\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>			\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]				\r\n     <div class=\"testimonial_text_separator \"><!--x--></div>			\r\n   </div>	\r\n   <div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>			\r\n   <div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>			\r\n   <div class=\"tm_clr\"><!--x--></div>		\r\n</div>',
                        'business' => '<span itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_text\">\r\n<h4 class=\"testimonial_caption\" itemprop=\"name\">[caption]</h4>\r\n<blockquote>\r\n<p itemprop=\"reviewBody\">[testimonial]</p>\r\n</blockquote>\r\n</div>\r\n<div class=\"testimonial_sign\" itemprop=\"image\">\r\n[avatar]\r\n<cite>\r\n<span class=\"testimonial_author\" itemprop=\"author\">[author]</span>\r\n<span class=\"testimonial_author_descr\">[author_descr][Website]</span>\r\n</cite>\r\n</div>\r\n</span>'
                    );

                    $db = JFactory::getDbo();
                    $query = 'DELETE FROM `#__tm_testimonials_templates` WHERE `temp_name` = "default";';
                    $db->setQuery($query);
                    $errors = $db->execute();

                    foreach ($templates as $temp_name => $temp_html) {
                        $query = 'SELECT id FROM `#__tm_testimonials_templates` WHERE `temp_name` = "' . $temp_name . '";';
                        $db->setQuery($query);
                        if (!$db->loadResult()) {
                            $query = 'INSERT INTO `#__tm_testimonials_templates` (`temp_name`, `html`) VALUES (\'' . $temp_name . '\',\'' . $temp_html . '\')';
                            $db->setQuery($query);
                            $errors = $db->execute();
                        }
                    }
                }
                if ($version[2] < 4) {
                    // move template to file
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('*')
                        ->from('`#__tm_testimonials_templates`');
                    $db->setQuery($query);
                    $tmpls = $db->loadObjectList();
                    $main_tmpl_file = file_get_contents(JPATH_SITE . '/components/com_testimonials/views/testimonials/tmpl/default.php');
                    foreach ($tmpls as $tmpl) {
                        if (!strpos(trim($tmpl->temp_name), ' ')) {
                            $template = trim($tmpl->temp_name);
                            $css  = file_get_contents(JPATH_SITE . '/components/com_testimonials/templates/' . $template . '/css/style.css');
                            $css_declaration
                                        = '<?php JFactory::getDocument()->addStyleDeclaration("'
                                . addcslashes($css, '\'"') . '"); ?>';
                            $tmpl->html = preg_replace('/\[(.*?)\]/',
                                '<?php echo \$item->$1; ?>', $tmpl->html);
                            preg_match_all('/\[(.*?)\]/',
                                '<?php echo \$item->$1; ?>', $tmpl->html,
                                $matches, PREG_SET_ORDER);
                            // No matches, skip this
                            if ($matches) {
                                foreach ($matches as $match) {
                                    $output = '<?php echo $item->'
                                        . $db->escape(str_replace(' ', '',
                                            strtolower($match[1]))) . '; ?>';
                                    // We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
                                    $tmpl->html = preg_replace("|$match[0]|",
                                        addcslashes($output, '\\$'),
                                        $tmpl->html, 1);
                                }
                            }

                            if (is_array($tmpl->html)) {
                                $tmpl->html = implode('', $tmpl->html);
                            }
                            $php = '<?php foreach($this->items as $item){ ?>'
                                . "\r\n" . $tmpl->html . "\r\n" . '<?php } ?>';

                            $tmpl_id = $tmpl->id;

                            $tmpl = fopen(JPATH_SITE
                                . '/components/com_testimonials/views/testimonials/tmpl/'
                                . $template . '.php', "w+");
                            if ($tmpl) {
                                fwrite($tmpl, $main_tmpl_file . "\r\n"
                                    . $css_declaration);
                                fclose($tmpl);
                            } else {
                                echo "can`t create file for " . $template
                                    . " layout";
                            }

                            $tmpl = fopen(JPATH_SITE
                                . '/components/com_testimonials/views/testimonials/tmpl/'
                                . $template . '_items.php', "w+");
                            if ($tmpl) {
                                fwrite($tmpl, $php);
                                fclose($tmpl);
                                $query->clear()
                                    ->update('`#__tm_testimonials_templates`')
                                    ->where('`id`="' . $tmpl_id . '"')
                                    ->set('`temp_name` = concat("migrated( ",`temp_name`," )")');
                                $db->setQuery($query)->execute();
                            } else {
                                echo "can`t create _items file for " . $template
                                    . " layout";
                            }
                        }
                    }
                    $db = JFactory::getDbo();
                    $columns
                        = $db->getTableColumns('#__tm_testimonials_custom');
                    if (empty($columns['system_name'])) {
                        $query
                            = 'ALTER TABLE `#__tm_testimonials_custom` ADD  `system_name` VARCHAR( 255 ) NOT NULL AFTER  `id`';
                        $db->setQuery($query)->execute();
                    }
                    $query = $db->getQuery(true);
                    $query->select('`id`, `name`')
                        ->from('`#__tm_testimonials_custom`');
                    $customs = $db->setQuery($query)->loadObjectList();
                    foreach ($customs as $c) {
                        $c->sys_name = $db->escape(str_replace(' ', '',
                            strtolower($c->name)));
                        $query->clear();
                        $query->update('`#__tm_testimonials_custom`')
                            ->set('`system_name` = ' . $db->q($c->sys_name)
                                . '')
                            ->where('`id` = ' . $db->q($c->id) . '');
                        $db->setQuery($query)->execute();
                    }

                    $query->clear();
                    $query->select('*')
                        ->from('`#__menu`')
                        ->where('`link` LIKE "%com_testimonials%"');
                    $db->setQuery($query);
                    $menu_items = $db->loadObjectList();
                    foreach ($menu_items as $mi) {
                        $mi->params = json_decode($mi->params);
                        if (!$mi->params->layout) {
                            $mi->params->layout = $mi->params->template;
                            $mi->params         = json_encode($mi->params);
                            $query->clear();
                            $query->update('`#__menu`')
                                ->where('`id` = ' . $mi->id)
                                ->set('`params` = ' . $db->q($mi->params) . '');
                            $db->setQuery($query);
                            $db->execute();
                        }
                    }
                }
                if ($version[2] < 5) {
                    // creating default category in com_categories
                    $extension = 'com_testimonials';
                    $title     = 'Uncategorised';
                    $desc      = 'A default category for the testimonials.';
                    $parent_id = 1;

                    // skip if exists
                    $db    = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select('id')
                        ->from('#__categories')
                        ->where('`extension`="' . $extension . '"')
                        ->where('`parent_id`="' . $parent_id . '"');
                    $exists = count($db->setQuery($query)->loadObjectList());

                    if (!$exists) {
                        $category = $this->createCategory($extension, $title,
                            $desc, $parent_id);
                    } else {
                        $category = $db->setQuery($query)->loadObject();
                    }

                    $columns = $db->getTableColumns('#__tm_testimonials');
                    if (empty($columns['catid'])) {
                        $query
                            = 'ALTER TABLE `#__tm_testimonials` ADD  `catid` INT( 11 ) NOT NULL DEFAULT "'
                            . $category->id . '" AFTER `id`';
                        $db->setQuery($query)->execute();
                        $query = 'UPDATE `#__tm_testimonials` SET  `catid` = "'
                            . $category->id . '" ';
                        $db->setQuery($query)->execute();
                    }

                    /* custom filds update logics */
                    $columns
                        = $db->getTableColumns('#__tm_testimonials_custom');
                    if (empty($columns['display'])) {
                        $query
                            = 'ALTER TABLE `#__tm_testimonials_custom` ADD  `display` INT( 2 ) NOT NULL DEFAULT "1" AFTER `required`';
                        $db->setQuery($query)->execute();
                    }

                }
            }
        }

    }


    function createCategory(
        $extension,
        $title,
        $desc,
        $parent_id = 1,
        $note = '',
        $published = 1,
        $access = 1,
        $params = '{"target":"","image":""}',
        $metadata = '{"page_title":"","author":"","robots":""}',
        $language = '*'
    ) {
        if (version_compare(JVERSION, '3.0', 'lt')) {
            JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
        }

        // Initialize a new category
        $category              = JTable::getInstance('Category');
        $category->extension   = $extension;
        $category->title       = $title;
        $category->description = $desc;
        $category->note        = $note;
        $category->published   = $published;
        $category->access      = $access;
        $category->params      = $params;
        $category->metadata    = $metadata;
        $category->language    = $language;

        $category->setLocation($parent_id, 'last-child');
        if (!$category->check()) {
            JFactory::getApplication()->enqueueMessage($category->getError(), 'notice');

            return false;
        }
        if (!$category->store(true)) {
            JFactory::getApplication()->enqueueMessage($category->getError(), 'notice');

            return false;
        }

        $category->rebuildPath($category->id);

        return $category;
    }

    function postflight($type, $parent)
    {
        $this->version_from;

        $db = JFactory::getDBO();

        $query = 'SELECT * FROM `#__tm_testimonials_custom`;';
        $db->setQuery($query);
        $db->execute();
        if ($db->getNumRows() === 0) {
            $query
                = "INSERT INTO `#__tm_testimonials_custom` (`id`, `name`, `published`, `ordering`, `type`, `required`, `descr`) VALUES
						 (1, 'Website', 1, 2, 'url', 0, ''),
						 (2, 'Email', 1, 3, 'textemail', 0, ''),
						 (3, 'Rating', 1, 0, 'rating', 1, 'rating');";
            $db->setQuery($query);
            $db->execute();
        }

        $return = urlencode(base64_encode((string)JUri::getInstance('index.php?option=com_testimonials')));
        $query = 'SELECT * FROM `#__tm_testimonials_dashboard_items`;';
        $db->setQuery($query);
        $db->execute();
        if ($db->getNumRows() === 0) {
            $query = "INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES
					(1, 'Testimonials Management', 'index.php?option=com_testimonials&view=topics', '/components/com_testimonials/assets/images/management48.png', 1),
					(2, 'Testimonials Settings', 'index.php?option=com_config&view=component&component=com_testimonials', '/components/com_testimonials/assets/images/settings48.png', 1),
					(3, 'Help', 'https://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm', '/components/com_testimonials/assets/images/help48.png', 1)";
            $db->setQuery($query);
            $db->execute();
        } else {
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_testimonials&view=topics' , `icon`='/components/com_testimonials/assets/images/management48.png' WHERE `title` = 'Testimonials Management';";
            $db->setQuery($query);
            $db->execute();
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_config&view=component&component=com_testimonials' , `icon`='/components/com_testimonials/assets/images/settings48.png' WHERE `title` = 'Testimonials Settings';";
            $db->setQuery($query);
            $db->execute();
            $query = "UPDATE `#__tm_testimonials_dashboard_items` SET `icon`='/components/com_testimonials/assets/images/help48.png' WHERE `title` = 'Help';";
            $db->setQuery($query);
            $db->execute();
        }

        $query = 'SELECT * FROM `#__tm_testimonials_settings`;';
        $db->setQuery($query);
        $db->execute();
        if ($db->getNumRows() === 0) {
            $query
                = 'INSERT INTO `#__tm_testimonials_settings` (`id`, `params`) VALUES
				(1, \'{"template":"black","show_title":"0","texttitle":"","show_caption":"1","show_captcha":"1","show_lasttofirst":"1","use_editor":"1","show_addimage":"1","show_recaptcha":"0","recaptcha_publickey":"","recaptcha_privatekey":"","show_tags":"1","use_jsoc":"0","show_avatar":"1","use_cb":"0","thumb_width":"110","show_authorname":"1","show_authordesc":"1","addingbyuser":"","addingbyunreg":"","allow_photo":"1","show_tagsforusers":"0","autoapprove":"0","timeout":"4","symb_qty":"200","th_width":"110","show_avatar_module":"1","show_readmore":"0","show_add_new":"0","show_first":"0","tag_options":"0","tm_version":"1.7.1 (build 006)","curr_date":""}\')';
            $db->setQuery($query);
            $db->execute();
        }

        $sql = "SHOW COLUMNS FROM #__tm_testimonials";
        $db->setQuery($sql);
        $results = $db->loadObjectList();

        $newColumns = array(
            'testimonials' => array(
                'catid'       => "int(11) NOT NULL",
                'date_added'  => "VARCHAR( 255 ) NOT NULL",
                'images'      => "TEXT NOT NULL",
                'comment'     => "TEXT NOT NULL",
                'is_approved' => "int(11) NOT NULL default '1'"
            )
        );

        foreach ($newColumns as $table => $fields) {
            $oldColumns = $db->getTableColumns('#__tm_' . $table);

            foreach ($fields as $key => $value) {
                if (empty($oldColumns[$key])) {
                    $db->setQuery('ALTER TABLE `#__tm_' . $table . '` ADD `'
                        . $key . '` ' . $value);
                    $db->execute();
                } else {
                    if ($key == 'is_approved') {
                        $db->setQuery('ALTER TABLE `#__tm_' . $table
                            . '` CHANGE  `' . $key . '`  `' . $key
                            . '` INT( 11 ) NOT NULL DEFAULT  "1"');
                        $db->execute();
                    }
                }
            }
        }

        $query
            = 'SELECT `params` FROM `#__extensions` WHERE `element`="com_testimonials" LIMIT 1';
        $db->setQuery($query);
        $params = $db->loadResult();
        if (empty(json_encode($params, true))) {
            $data_json = '{"bootstrap":"1","fontawesome":"1","modal_on_new":"1","autoapprove":"0","enable_email":"0","admin_email":"","list_limit":20,"show_lasttofirst":"1","show_tags":"1","show_testimmore":"0","use_editor":"1","allow_photo":"1","show_addimage":"1","show_caption":"1","show_avatar":"1","use_cb":"0","use_jsoc":"0","show_authorname":"1","show_authordesc":"1","thmbl_noimage":"","thmbl_title":"","thmbl_alt":"","thmbl_square":"1","thmbl_width":"","thmbl_height":"","thmbl_sizing":"full","thmbl_fill_color":"#ffffff","thmbl_compression":"85"}';
            $query = 'UPDATE `#__extensions` SET `params`=\'' . $data_json
                . '\' WHERE `name`="com_testimonials"';
            $db->setQuery($query);
            $db->execute();
        }

        $query
            = "SHOW INDEX FROM #__tm_testimonials_conformity WHERE Key_name = 'id_ti';";
        $db->setQuery($query);
        $indexCheck = $db->loadResult();
        if (!$indexCheck) {
            $query
                = "ALTER TABLE  `#__tm_testimonials_conformity` ADD INDEX id_ti (  `id_ti` ,  `id_tag` ) ;";
            $db->setQuery($query);
            $db->execute();
        }

        $this->defaultCategoryCheck();
    }

    function defaultCategoryCheck()
    {
        /* checking default category quizzes */
        $extension = 'com_testimonials';
        $title     = 'Uncategorised';
        $desc      = 'A default category for the testimonials.';
        $parent_id = 1;

        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__categories')
            ->where('`extension`="' . $extension . '"')
            ->where('`parent_id`="' . $parent_id . '"');
        $exists = count($db->setQuery($query)->loadObjectList());

        if (!$exists) {
            $this->createCategory($extension, $title, $desc, $parent_id);
        }
    }

    function greetingText()
    {

    }

    protected function checkUCM(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->qn('type_id'))
            ->from($db->qn('#__content_types'))
            ->where($db->qn('type_alias').'='.$db->q('com_testimonials.category'));
        if(!$db->setQuery($query,0,1)->loadResult()){
            $query->clear();

            $columns = array('type_title', 'type_alias', 'table', 'rules', 'field_mappings', 'router', 'content_history_options');
            $values = array(
                $db->q('Testimonials Category'),
                $db->q('com_testimonials.category'),
                $db->q('{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":   {"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}'),
                $db->q(''),
                $db->q('{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}'),
                $db->q(''),
                $db->q('{"formFile":"administrator\\/components\\/com_categories\\/models\\/forms\\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}')
            );

            $query->insert($db->qn('#__content_types'))
                ->columns($db->qn($columns))
                ->values(implode(',', $values));

            $db->setQuery($query)
                ->execute();
        }
    }

}