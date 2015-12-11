<?php
/**
 * Testimonials Component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class com_testimonialsInstallerScript {

    function install($parent) {
        $db = JFactory::getDBO();

        @mkdir(JPATH_SITE . "/images/testimonials");
        @chmod(JPATH_SITE . "/images/testimonials", 0757);

        @mkdir(JPATH_SITE . "/images/com_testimonials");
        @chmod(JPATH_SITE . "/images/com_testimonials", 0757);

        echo '<font style="font-size:2em; color:#55AA55;" >' . JText::_('COM_TESTIMONIAL_INSTALL_TEXT') . '</font><br/><br/>';
        $this->greetingText();
    }

    function uninstall($parent) {
        echo '<p>' . JText::_('COM_TESTIMONIAL_UNINSTALL_TEXT') . '</p>';
    }

    function update($parent) {
        $errors = false;
        $db = JFactory::getDBO();
        $query = 'CREATE TABLE IF NOT EXISTS `#__tm_testimonials_dashboard_items` (
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
            $query = "INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES
                (1, 'Testimonials Management', 'index.php?option=com_testimonials&view=topics', '".JURI::base()."components/com_testimonials/assets/images/management48.png', 1),
                (2, 'Testimonials Settings', 'index.php?option=com_config&view=component&component=com_testimonials', '".JURI::base()."components/com_testimonials/assets/images/settings48.png', 1),
                (3, 'Help', 'http://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm', '".JURI::base()."components/com_testimonials/assets/images/help48.png', 1);
                ";
            $db->setQuery($query);
            $errors = $db->execute();
        }else{
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_testimonials&view=topics' , `icon`='".JURI::base()."components/com_testimonials/assets/images/management48.png' WHERE `title` = 'Testimonials Management';";
			$db->setQuery($query);
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_config&view=component&component=com_testimonials' , `icon`='".JURI::base()."components/com_testimonials/assets/images/settings48.png' WHERE `title` = 'Testimonials Settings';";
			$db->setQuery($query);
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `icon`='".JURI::base()."components/com_testimonials/assets/images/help48.png' WHERE `title` = 'Help';";
			$db->setQuery($query);
			$db->execute();
		}

        $query = 'SELECT `params` FROM `#__tm_testimonials_settings` WHERE `id`=1 LIMIT 1';
        $db->setQuery($query);
        $params = $db->loadResult();
        if ($params) {
            $query = 'UPDATE `#__extensions` SET `params`=\'' . $params . '\' WHERE `name`="com_testimonials"';
            $db->setQuery($query);
            $db->execute();
        }
        
        $params = JComponentHelper::getParams("com_testimonials");
        if(!$params->get('tm_version'))
            $params->set('tm_version','1.7.1 (build 006)');
        if(!$params->get('curr_date'))
            $params->set('curr_date','');


        $query = 'DROP TABLE IF EXISTS `#__tm_version`;';
        $db->setQuery($query);
        $errors = $db->execute();

        if ($errors) {
            echo '<font style="font-size:2em; color:#55AA55;" >' . JText::_('COM_TESTIMONIAL_UPDATE_TEXT') . '</font><br/><br/>';
            $this->greetingText();
        } else {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
		
    }

    function preflight($type, $parent) {
		
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_testimonials/testimonials.xml');
		$this->version_from = $version = preg_split( '/(\s|\.)/', $xml->version );
		if($version[0]){
			if($version[2]<2){
				// update default template
				$templates = array(
					'default' => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption\"><p class=\"h3\" itemprop=\"name\">[caption]</p></div>\r\n<div class=\"row row-fluid\">\r\n<div class=\"span3 col-xs-12 col-sm-3\">\r\n<div class=\"testimonial_image\" itemprop=\"image\"><a href=\"javascript:void(0);\" class=\"thumbnail\">[avatar]</a></div>\r\n</div>\r\n<blockquote class=\"span9 col-xs-12 col-sm-9\">\r\n<p class=\"testimonial_text\" itemprop=\"reviewBody\">\r\n[testimonial]\r\n</p>\r\n<p>[rating]</p>\r\n<small class=\"testimonial_author\" itemprop=\"author\">[author]</small>\r\n</blockquote>\r\n</div>\r\n<div class=\"testimonial_tags text-right\">[tags]</div>\r\n</div>',
					'black' => '<div class=\"testimonial_inner\">\\r\\n<div class=\"testimonial_caption \"><h4>[caption]<!--x--></h4></div>	\\r\\n<div class=\"testimonial_image\">[avatar]</div>\\r\\n<div class=\"testimonial_text\">[testimonial]\\r\\n<div class=\"testimonial_text_separator \"><!--x--></div>			\\r\\n</div>			\\r\\n<div class=\"testimonial_author \">[author]<!--x--></div>\\r\\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\\r\\n<div class=\"tm_clr\"><!--x--></div>\\r\\n</div>',
					'black2' => '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n<div class=\"testimonial_text_separator \"><!--x--></div>\r\n</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div>\r\n</div>',
					'blacktip' => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_inner\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]</div>\r\n</div> 	\r\n</div>\r\n<div class=\"avatar_on\">\r\n<div class=\"testimonial_steam \"><!--x--></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div> 	\r\n</div>',
					'classic' => '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">		\r\n<div class=\"testimonial_caption\" itemprop=\"name\">[caption]</div>\r\n<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td width=\"61\" valign=\"top\" class=\"testimonial_text\" itemprop=\"reviewBody\">[avatar][testimonial]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\" itemprop=\"author\">[author]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\">[author_descr]</td>		\r\n</tr>	\r\n</tbody>\r\n</table>	\r\n</div>',
					'gray' => '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">			\r\n   <div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>			\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>			\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]				\r\n     <div class=\"testimonial_text_separator \"><!--x--></div>			\r\n   </div>	\r\n   <div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>			\r\n   <div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>			\r\n   <div class=\"tm_clr\"><!--x--></div>		\r\n</div>',
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
			if($version[2]<4){
				// move template to file
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*')
					->from('`#__tm_testimonials_templates`');
				$db->setQuery($query);
				$tmpls = $db->loadObjectList();
				$main_tmpl_file = file_get_contents(JPATH_SITE .'/components/com_testimonials/views/testimonials/tmpl/default.php');
				foreach($tmpls as $tmpl){
					if(!strpos(trim($tmpl->temp_name),' ')){
						$template = trim($tmpl->temp_name);
						$css = file_get_contents(JPATH_SITE .'/components/com_testimonials/templates/'.$template.'/css/style.css');
						$css_declaration = '<?php JFactory::getDocument()->addStyleDeclaration("'.addcslashes($css,'\'"').'"); ?>';
						$tmpl->html = preg_replace('/\[(.*?)\]/','<?php echo \$item->$1; ?>',$tmpl->html);
						preg_match_all('/\[(.*?)\]/','<?php echo \$item->$1; ?>', $tmpl->html, $matches, PREG_SET_ORDER);
						// No matches, skip this
						if ($matches)
						{
							foreach ($matches as $match)
							{
								$output = '<?php echo $item->'.$db->escape( str_replace(' ','',strtolower($match[1]))).'; ?>';
								// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
								$tmpl->html = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $tmpl->html, 1);
							}
						}
						
						$php = '<?php foreach($this->items as $item){ ?>'."\r\n".$tmpl->html."\r\n".'<?php } ?>';
						
						$tmpl_id = $tmpl->id;
						
						$tmpl = fopen(JPATH_SITE .'/components/com_testimonials/views/testimonials/tmpl/'.$template.'.php', "w+");
						if($tmpl){
							fwrite($tmpl, $main_tmpl_file."\r\n".$css_declaration);
							fclose($tmpl);
						}else{
							echo "can`t create file for ".$template." layout";
						}
						
						$tmpl = fopen(JPATH_SITE .'/components/com_testimonials/views/testimonials/tmpl/'.$template.'_items.php', "w+");
						if($tmpl){
							fwrite($tmpl, $php);
							fclose($tmpl);
							$query->clear()
								->update('`#__tm_testimonials_templates`')
								->where('`id`="'.$tmpl_id.'"')
								->set('`temp_name` = concat("migrated( ",`temp_name`," )")');
							$db->setQuery($query)->execute();
						}else{
							echo "can`t create _items file for ".$template." layout";
						}
					}
				}
				$db = JFactory::getDbo();
				$columns = $db->getTableColumns('#__tm_testimonials_custom');
				if(empty($columns['system_name'])){
					$query = 'ALTER TABLE `#__tm_testimonials_custom` ADD  `system_name` VARCHAR( 255 ) NOT NULL AFTER  `id`';
					$db->setQuery($query)->execute();
				}
				$query = $db->getQuery(true);
				$query->select('`id`, `name`')
					->from('`#__tm_testimonials_custom`');
				$customs = $db->setQuery($query)->loadObjectList();
				foreach($customs as $c){
					$c->sys_name = $db->escape( str_replace(' ','',strtolower($c->name)) );
					$query->clear();
					$query->update('`#__tm_testimonials_custom`')
						->set('`system_name` = '.$db->q($c->sys_name).'')
						->where('`id` = '.$db->q($c->id).'');
					$db->setQuery($query)->execute();
				}
				
				$query->clear();
				$query->select('*')
					->from('`#__menu`')
					->where('`link` LIKE "%com_testimonials%"');
				$db->setQuery($query);
				$menu_items = $db->loadObjectList();
				foreach($menu_items as $mi){
					$mi->params = json_decode($mi->params);
					if(!$mi->params->layout){
						$mi->params->layout = $mi->params->template;
						$mi->params = json_encode($mi->params);
						$query->clear();
						$query->update('`#__menu`')
							->where('`id` = '.$mi->id)
							->set('`params` = '.$db->q($mi->params).'');
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
			if($version[2]<5){
				// creating default category in com_categories
				$extension = 'com_testimonials';
				$title     = 'Uncategorised';
				$desc      = 'A default category for the testimonials.';
				$parent_id = 1;
				
				// skip if exists
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('id')
					->from('#__categories')
					->where('`extension`="'.$extension.'"')
					->where('`parent_id`="'.$parent_id.'"');
				$exists = count($db->setQuery($query)->loadObjectList());
				
				if(!$exists)
					$category = $this->createCategory($extension, $title, $desc, $parent_id);
				else
					$category = $db->setQuery($query)->loadObject();
			
				$columns = $db->getTableColumns('#__tm_testimonials');
				if(empty($columns['catid'])){
					$query = 'ALTER TABLE `#__tm_testimonials` ADD  `catid` INT( 11 ) NOT NULL DEFAULT "'.$category->id.'" AFTER `id`';
					$db->setQuery($query)->execute();
					$query = 'UPDATE `#__tm_testimonials` SET  `catid` = "'.$category->id.'" ';
					$db->setQuery($query)->execute();
				}
				
				/* custom filds update logics */
				$columns = $db->getTableColumns('#__tm_testimonials_custom');
				if(empty($columns['display'])){
					$query = 'ALTER TABLE `#__tm_testimonials_custom` ADD  `display` INT( 2 ) NOT NULL DEFAULT "1" AFTER `required`';
					$db->setQuery($query)->execute();
				}
				
			}
		}
		
		$this->defaultCategoryCheck();
        
    }
	
	
	
	function createCategory($extension, $title, $desc, $parent_id=1, $note='', $published=1, $access = 1, $params = '{"target":"","image":""}', $metadata = '{"page_title":"","author":"","robots":""}', $language = '*'){	
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
		   JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}

		// Initialize a new category
		$category = JTable::getInstance('Category');
		$category->extension = $extension;
		$category->title = $title;
		$category->description = $desc;
		$category->note = $note;
		$category->published = $published;
		$category->access = $access;
		$category->params = $params;
		$category->metadata = $metadata;
		$category->language = $language;

		$category->setLocation($parent_id, 'last-child');
		if (!$category->check())
		{
		   JError::raiseNotice(500, $category->getError());
		   return false;
		}
		if (!$category->store(true))
		{
		   JError::raiseNotice(500, $category->getError());
		   return false;
		}
		
		$category->rebuildPath($category->id);
		
		return $category;
	}

    function postflight($type, $parent) {
	
		$this->version_from;
		
        $db = JFactory::getDBO();

		$query = 'SELECT * FROM `#__tm_testimonials_custom`;';
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows() === 0) {
			$query = "INSERT INTO `#__tm_testimonials_custom` (`id`, `name`, `published`, `ordering`, `type`, `required`, `descr`) VALUES
						 (1, 'Website', 1, 2, 'url', 0, ''),
						 (2, 'Email', 1, 3, 'textemail', 0, ''),
						 (3, 'Rating', 1, 0, 'rating', 1, 'rating');";
			$db->setQuery($query);
			$db->execute();
		}

        $return = urlencode(base64_encode((string) JUri::getInstance('index.php?option=com_testimonials')));
		$query = 'SELECT * FROM `#__tm_testimonials_dashboard_items`;';
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows() === 0) {
			$query = "INSERT INTO `#__tm_testimonials_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES
					(1, 'Testimonials Management', 'index.php?option=com_testimonials&view=topics', '".JURI::base()."components/com_testimonials/assets/images/management48.png', 1),
					(2, 'Testimonials Settings', 'index.php?option=com_config&view=component&component=com_testimonials', '".JURI::base()."components/com_testimonials/assets/images/settings48.png', 1),
					(3, 'Help', 'http://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm', '".JURI::base()."components/com_testimonials/assets/images/help48.png', 1)";
			$db->setQuery($query);
			$db->execute();
		}else{
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_testimonials&view=topics' , `icon`='".JURI::base()."components/com_testimonials/assets/images/management48.png' WHERE `title` = 'Testimonials Management';";
			$db->setQuery($query);
			$db->execute();
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `url`='index.php?option=com_config&view=component&component=com_testimonials' , `icon`='".JURI::base()."components/com_testimonials/assets/images/settings48.png' WHERE `title` = 'Testimonials Settings';";
			$db->setQuery($query);
			$db->execute();
			$query = "UPDATE `#__tm_testimonials_dashboard_items` SET `icon`='".JURI::base()."components/com_testimonials/assets/images/help48.png' WHERE `title` = 'Help';";
			$db->setQuery($query);
			$db->execute();
		}

		$query = 'SELECT * FROM `#__tm_testimonials_settings`;';
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows() === 0) {
			$query = 'INSERT INTO `#__tm_testimonials_settings` (`id`, `params`) VALUES
				(1, \'{"template":"black","show_title":"0","texttitle":"","show_caption":"1","show_captcha":"1","show_lasttofirst":"1","use_editor":"1","show_addimage":"1","show_recaptcha":"0","recaptcha_publickey":"","recaptcha_privatekey":"","show_tags":"1","use_jsoc":"0","show_avatar":"1","use_cb":"0","thumb_width":"110","show_authorname":"1","show_authordesc":"1","addingbyuser":"","addingbyunreg":"","allow_photo":"1","show_tagsforusers":"0","autoapprove":"0","timeout":"4","symb_qty":"200","th_width":"110","show_avatar_module":"1","show_readmore":"0","show_add_new":"0","show_first":"0","tag_options":"0","tm_version":"1.7.1 (build 006)","curr_date":""}\')';
			$db->setQuery($query);
			$db->execute();
		}

        $sql = "SHOW COLUMNS FROM #__tm_testimonials";
        $db->setQuery($sql);
        $results = $db->loadObjectList();

		$newColumns = array(
			'testimonials' => array(
				'catid' => "int(11) NOT NULL",
				'date_added' => "VARCHAR( 255 ) NOT NULL",
				'images' => "TEXT NOT NULL",
				'comment' => "TEXT NOT NULL",
				'is_approved' => "int(11) NOT NULL default '1'"
			)
		);

		foreach ($newColumns as $table => $fields)
		{
			$oldColumns = $db->getTableColumns('#__tm_'.$table);

			foreach ( $fields as $key => $value)
			{
				if ( empty($oldColumns[$key]) )
				{
					$db->setQuery('ALTER TABLE `#__tm_'.$table.'` ADD `'.$key.'` '.$value);
					$db->execute();
				}
				else if($key=='is_approved'){
					$db->setQuery('ALTER TABLE `#__tm_'.$table.'` CHANGE  `'.$key.'`  `'.$key.'` INT( 11 ) NOT NULL DEFAULT  "1"');
					$db->execute();
				}
			}
		}

        $query = 'SELECT `params` FROM `#__tm_testimonials_settings` WHERE `id`=1 LIMIT 1';
        $db->setQuery($query);
        $params = $db->loadResult();
        if ($params) {
            $query = 'UPDATE `#__extensions` SET `params`=\'' . $params . '\' WHERE `name`="com_testimonials"';
            $db->setQuery($query);
            $db->execute();
        }

        $query = "SHOW INDEX FROM #__tm_testimonials_conformity WHERE Key_name = 'id_ti';";
        $db->setQuery($query);
        $indexCheck = $db->loadResult();
        if (!$indexCheck) {
            $query = "ALTER TABLE  `#__tm_testimonials_conformity` ADD INDEX id_ti (  `id_ti` ,  `id_tag` ) ;";
            $db->setQuery($query);
            $db->execute();
        }
    }

    function greetingText() {
        ?>
        <table border="1" cellpadding="5" width="100%" style="background-color: #F7F8F9; border: solid 1px #d5d5d5; width: 100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px ">
                    <strong><img src="<?php echo JURI::root(); ?>/administrator/components/com_testimonials/assets/images/tick.png"> Getting started.</strong> Helpfull links:
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px">
                    <div style="font-size:1.2em">
                        <ul>
                            <li><a href="index.php?option=com_testimonials&task=sample_data">Install Sample Data</a></li>
                            <li><a href="index.php?option=com_testimonials&view=topics">Manage Testimonials</a></li>
                            <li><a href="http://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm" target="_blank">Component's help</a></li>
                            <li><a href="http://www.joomplace.com/forum/testimonials-component/page-1.html" target="_blank">Support forum</a></li>
                            <li><a href="http://www.joomplace.com/support/helpdesk/department/presale-questions" target="_blank">Submit request to our technicians</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px ">
                    <strong><img src="<?php echo JURI::root(); ?>/administrator/components/com_testimonials/assets/images/tick.png"> Say your "Thank you" to Joomla community</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px">
                    <div style="font-size:1.2em">
                        <p style="font-size:12px;">
                            <span style="font-size:14pt;">Say your "Thank you" to Joomla community</span> for WonderFull Joomla CMS and <span style="font-size:14pt;">help it</span> by sharing your experience with this component. It will only take 1 min for registration on <a href="http://extensions.joomla.org" target="_blank">http://extensions.joomla.org</a> and 3 minutes to write useful review! A lot of people will thank you!<br />
                            <a href="http://extensions.joomla.org/extensions/contacts-and-feedback/testimonials-a-suggestions/11304" target="_blank"><img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" title="Rate Us!" alt="Rate us at Extensions Joomla.org"  style="padding-top:5px;"/></a>
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:14px; font-weight:400; line-height:18px ">
                    <strong><img src="<?php echo JURI::root(); ?>/administrator/components/com_testimonials/assets/images/tick.png">Latest changes: </strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px" align="justify">
                    -------------------- 1.7.1 [January-2013] ---------------<br />
                    - release for Joomla! 3
                </td>
            </tr>
        </table>
        <?php
    }
	
	function defaultCategoryCheck()
	{
		/* checking default category quizzes */
		$extension = 'com_testimonials';
		$title     = 'Uncategorised';
		$desc      = 'A default category for the testimonials.';
		$parent_id = 1;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__categories')
			->where('`extension`="'.$extension.'"')
			->where('`parent_id`="'.$parent_id.'"');
		$exists = count($db->setQuery($query)->loadObjectList());
		
		if(!$exists)
			$this->createCategory($extension, $title, $desc, $parent_id);
	}

}