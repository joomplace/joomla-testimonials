<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

/**
 * Testimonials Component Controller
 */
class TestimonialsController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = array())
        {
        	$view = JFactory::getApplication()->input->getCmd('view', 'Dashboard');
                JFactory::getApplication()->input->set('view', $view);
                parent::display($cachable);
        }

        public function showpicture()
        {
        	$settings =  TestimonialHelper::getSettings();
        	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'Timg.php');
        	$image = JFactory::getApplication()->input->getString('image');
        	$tid = JFactory::getApplication()->input->getInt('tid',0);
        	$width = JFactory::getApplication()->input->getInt('width',300);
        	$height = JFactory::getApplication()->input->getInt('height',300);


        	$database = JFactory::getDBO();
        	$query = $database->getQuery(true);
        	if ($tid)
        	{
        		if ($settings->get('use_cb') == 1)
        		{
        			$query->select('CONCAT("images/comprofiler/",compr.avatar)');
        			$query->join('LEFT','`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');

        		}
        		if ($settings->get('use_jsoc') == 1)
        		{
        			$query->select('jsoc.thumb as `avatar`');
        			$query->join('LEFT','`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');

        		}
        		else
        		{
        			$query->select('t.photo');
        		}
        		$query->from('`#__tm_testimonials` AS `t`');
        		$query->where('t.`id`= '.$tid);
        		$database->setQuery($query);
        		$imagepath = $database->loadResult();
        	}

        	if (isset($imagepath))
        	{
        		$imclass= new TimgHelper();
        		echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$imagepath,$width,$height));
        	}else if(isset($image)) //for BE and old vers
        	{
        		$imclass= new TimgHelper();
        		echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$image,$width,$height));

        	}
        	exit();
        }
        
         public function datedb()
            {
             echo date("Y-m-d");
                TestimonialHelper::setSetting('curr_date', date("Y-m-d"));                
            }

        function sample_data()
        {
        	$msg = JText::_('COM_TESTIMONIALS_MSG_SAMPLE');
        	$db = JFactory::getDBO();

	        	$query = "SELECT `id` FROM `#__tm_testimonials_tags` WHERE `tag_name` = 'sample tag'";
				$db->setQuery($query);
				$tagid = $db->loadResult();
					if (!$tagid)
						{
							$query = "INSERT INTO `#__tm_testimonials_tags` (`tag_name`, `published`) VALUES ('sample tag', 1);";
							$db->setQuery($query);
							$db->execute();
							$tagid = $db->insertid();

							$msg .= JText::_('COM_TESTIMONIALS_MSG_SAMPLE_ADST');
						}

				$query = "SELECT COUNT(*) FROM `#__tm_testimonials_custom`";
				$db->setQuery($query);

				if (!$db->loadResult()){
					$query = "INSERT INTO `#__tm_testimonials_custom` (`name`, `published`, `ordering`, `type`, `required`, `descr`) VALUES ('Website', 1, 2, 'url', 0, ''),('Email', 1, 3, 'textemail', 0, ''),('Efforts', 1, 1, 'text', 1, '');";
					$db->setQuery($query);
					$db->execute();
					$msg .= JText::_('COM_TESTIMONIALS_MSG_SAMPLE_ADSCF');
				}

				$query = "SELECT `id` FROM `#__tm_testimonials` WHERE `t_caption` = 'Franck Scipion' OR `t_caption` = 'Using the Force' OR t_caption = 'Intuitive Website Solutions, inc.Website'";
				$db->setQuery($query);
    			if (!$db->loadResult())
    			{
    				$query = "SELECT max(ordering) FROM #__tm_testimonials";
					$db->setQuery( $query );
					$ord = $db->loadResult();

					$db->setQuery("SELECT id FROM `#__tm_testimonials_custom` WHERE name='Website' AND type='url'");
					$webid = $db->loadResult();

					$ord++;
    				$db->setQuery("INSERT INTO #__tm_testimonials (published, ordering, t_caption, t_author, testimonial, date_added)
    									 VALUES (1, $ord, 'Franck Scipion', 'Franck Scipion, www.55thinking.com', 'Thanks a lot for allowing us to test your excellent products, and we will not hesitate to recommend your solution. It does what it says, and does it well !!!', '".date('Y-m-d H:i:s', time())."');");
					$db->execute();
					$tmid = $db->insertid();

					$db->setQuery("INSERT INTO #__tm_testimonials_conformity (`id_ti`, `id_tag`) VALUES ($tmid, $tagid)");
					$db->execute();

					$query = "INSERT INTO `#__tm_testimonials_items_fields` (`item_id`, `field_id`, `value`) VALUES (".$tmid.", ".$webid.", 'http://www.55thinking.com |www.55thinking.com ')";
					$db->setQuery($query);
					$db->execute();

					$ord++;
					$db->setQuery("INSERT INTO `#__tm_testimonials` (published, ordering, t_caption, t_author, testimonial, date_added)
								   VALUES (1, $ord, 'Using the Force', 'Dr. John Kenworthy', 'I\'ve used several powerpoint to flash converters and powerpoint force has some considerable advantages... not least the very easy integration with Wordforce (a lovely way to put large documents online) and Quizforce (simple, straightforward and very easy to use). Then the very easy and effective integration with joomlalms as a scorm course. The support given is excellent. Does not cause MS office to hang, throw errors or have a hissy fit (unlike many others I could mention!). What powerpoint force doesn\'t do, to be honest, you don\'t need. The same is true of joomlalms. All I wish is that surveyforce allowed graphic scales and the whole thing integrates with jomsocial - now that would make a killer elearning platform. Great job.', '".date('Y-m-d H:i:s', time())."');");
					$db->execute();
					$tmid = $db->insertid();

					$db->setQuery("INSERT INTO #__tm_testimonials_conformity (`id_ti`, `id_tag`) VALUES ($tmid, $tagid)");
					$db->execute();

					$ord++;
					$db->setQuery("INSERT INTO #__tm_testimonials (published, ordering, t_caption, t_author, testimonial, date_added)
								   VALUES (1, $ord, 'Intuitive Website Solutions, inc.Website','Robert McLeod President Intuitive Website Solutions, inc. www.iws3.comIntuitive ', 'Were do I send the Beer! You guys are awesome. You are my Joomla, PHP, MySql go to guru and will always come to you for all my trouble shooting and custom development needs. By the way I had a call today from another client that was having the same problem that you found a fix for, she had never checked her site after the php upgrade like I and requested, so I did as you did and all works fine on that site now. Looks to me as the problem was with the php upgrade (cmod/file owner changes were made) like you said in the beginning. I had paid two other companies to fix this issue but they could not figure it out. Just wanted to let you know how grateful I am to you and your team. Please share with your team. ', '".date('Y-m-d H:i:s', time())."');");
					$db->execute();
					$tmid =  $db->insertid();

					$db->setQuery("INSERT INTO #__tm_testimonials_conformity (`id_ti`, `id_tag`) VALUES ($tmid, $tagid)");
					$db->execute();

					$query = "INSERT INTO `#__tm_testimonials_items_fields` (`item_id`, `field_id`, `value`) VALUES (".$tmid.", ".$webid.", 'http://www.iws3.comIntuitive |www.iws3.comIntuitive ')";
					$db->setQuery($query);
					$db->execute();

					$msg .= JText::_('COM_TESTIMONIALS_MSG_SAMPLE_ADSTEST');
    			}
				$this->setRedirect('index.php?option=com_testimonials&view=topics',$msg);
        }

        function reset_templ()
        {
            jimport('joomla.filesystem.file');
            jimport('joomla.filesystem.folder');
            $msg = JText::_('COM_TESTIMONIALS_MSG_RESET_TEMPLATES');
        	$db = JFactory::getDBO();
        		$query = "DELETE FROM `#__tm_testimonials_templates`";
				$db->setQuery($query);
				$db->execute();

			$query = "INSERT INTO `#__tm_testimonials_templates` (`id`, `temp_name`, `html`) VALUES
(1, 'default', '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n   <div class=\"testimonial_caption\"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n       <div class=\"testimonial_text_separator\"><!--x--></div>\r\n   </div>\r\n   <div class=\"testimonial_author\" itemprop=\"author\">[author] [Website]<!--x--></div>\r\n   <div class=\"testimonial_author_descr\">[author_descr] <b>[Email]<!--x--></b></div>\r\n   <div class=\"tm_clr\"><!--x--></div>\r\n</div>'),
(2, 'black', '<div class=\\\"testimonial_inner\\\"><div class=\\\"testimonial_caption \\\"><h4>[caption]<!--x--></h4></div><div class=\\\"testimonial_image\\\">[avatar]</div><div class=\\\"testimonial_text\\\">[testimonial]<div class=\\\"testimonial_text_separator \\\"><!--x--></div></div><div class=\\\"testimonial_author \\\">[author]<!--x--></div><div class=\\\"testimonial_author_descr \\\">[author_descr]<!--x--></div><div class=\\\"tm_clr\\\"><!--x--></div></div>'),
(3, 'black2', '<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]\r\n<div class=\"testimonial_text_separator \"><!--x--></div>\r\n</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div>\r\n</div>'),
(4, 'blacktip', '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_inner\">\r\n<div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>\r\n<div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]</div>\r\n</div> 	\r\n</div>\r\n<div class=\"avatar_on\">\r\n<div class=\"testimonial_steam \"><!--x--></div>\r\n<div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>\r\n<div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>\r\n<div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>\r\n<div class=\"tm_clr\"><!--x--></div> 	\r\n</div>'),
(5, 'classic', '<div class=\"testimonial\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">		\r\n<div class=\"testimonial_caption\" itemprop=\"name\">[caption]</div>\r\n<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td width=\"61\" valign=\"top\" class=\"testimonial_text\" itemprop=\"reviewBody\">[avatar][testimonial]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\" itemprop=\"author\">[author]</td>		\r\n</tr>		\r\n<tr>			\r\n<td align=\"right\" valign=\"top\" class=\"testimonial_author\">[author_descr]</td>		\r\n</tr>	\r\n</tbody>\r\n</table>	\r\n</div>'),
(6, 'gray', '\r\n<div class=\"testimonial_inner\" itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">			\r\n   <div class=\"testimonial_caption \"><h4 itemprop=\"name\">[caption]<!--x--></h4></div>			\r\n   <div class=\"testimonial_image\" itemprop=\"image\">[avatar]</div>			\r\n   <div class=\"testimonial_text\" itemprop=\"reviewBody\">[testimonial]				\r\n     <div class=\"testimonial_text_separator \"><!--x--></div>			\r\n   </div>	\r\n   <div class=\"testimonial_author \" itemprop=\"author\">[author]<!--x--></div>			\r\n   <div class=\"testimonial_author_descr \">[author_descr]<!--x--></div>			\r\n   <div class=\"tm_clr\"><!--x--></div>		\r\n</div>'),
(7, 'business', '<span itemprop=\"reviews\" itemscope itemtype=\"http://schema.org/Review\">\r\n<div class=\"testimonial_text\">\r\n<h4 class=\"testimonial_caption\" itemprop=\"name\">[caption]</h4>\r\n<blockquote>\r\n<p itemprop=\"reviewBody\">[testimonial]</p>\r\n</blockquote>\r\n</div>\r\n<div class=\"testimonial_sign\" itemprop=\"image\">\r\n[avatar]\r\n<cite>\r\n<span class=\"testimonial_author\" itemprop=\"author\">[author]</span>\r\n<span class=\"testimonial_author_descr\">[author_descr][Website]</span>\r\n</cite>\r\n</div>\r\n</span>')";
			$db->setQuery($query);
			$db->execute();
                        $list_templates = JFolder::folders(JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'templates');
                        
                        foreach($list_templates as $template){
			
                        JFile::write(JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css', 
                        JFile::read(JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style_bak.css'));
                        
                       
                        }
			
                        $this->setRedirect('index.php?option=com_testimonials&view=templates',$msg);

        }

        function latestVersion()
        {
        	require_once(JPATH_BASE.'/components/com_testimonials/assets/Snoopy.class.php' );
			$tm_version = TestimonialHelper::getVersion();
			$s = new Snoopy();
			$s->read_timeout = 90;
			$s->referer = JURI::root();
			@$s->fetch('http://www.joomplace.com/version_check/componentVersionCheck.php?component=testimonial&current_version='.urlencode($tm_version));
			$version_info = $s->results;
			$version_info_pos = strpos($version_info, ":");
			if ($version_info_pos === false) {
				$version = $version_info;
				$info = null;
			} else {
				$version = substr( $version_info, 0, $version_info_pos );
				$info = substr( $version_info, $version_info_pos + 1 );
			}
			if($s->error || $s->status != 200){
		    	echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		    } else if($version == $tm_version){
		    	echo '<font color="green">' . $version . '</font>' . $info;
		    } else {
		    	echo '<font color="red">' . $version . '</font>&nbsp;<a href="http://www.joomplace.com/members-area.html" target="_blank">(Upgrade to the latest version)</a>' ;
		    }
		    exit();
        }

        public function latestNews()
        {
        	require_once(JPATH_BASE.'/components/com_testimonials/assets/Snoopy.class.php' );

			$s = new Snoopy();
			$s->read_timeout = 10;
			$s->referer = JURI::root();
			@$s->fetch('http://www.joomplace.com/news_check/componentNewsCheck.php?component=testimonial');
			$news_info = $s->results;

			if($s->error || $s->status != 200){
		    	echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		    } else {
			echo $news_info;
		    }
		    exit();
        }
}