<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

/**
 * Testimonials Component Controller
 */
class TestimonialsController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = array())
	{
		
		$user = JFactory::getUser();
		$v = JFactory::getApplication()->input->getCmd('view', 'Testimonials');
		$id		= JFactory::getApplication()->input->getInt('id');
		if(!JFactory::getApplication()->input->get('temp') && ($v == 'testimonials' || $v=='topics')) JFactory::getApplication()->input->set('temp',  'default');
        if ($v=='topics') JFactory::getApplication()->input->set('view',  'Testimonials');
        if ($v=='topic') JFactory::getApplication()->input->set('view',  'form');
             parent::display();
	}
	public function _showpicture()
	{
		$settings =  TestimonialHelper::getSettings();
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'Timg.php');
		
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
			//echo $query->__toString(); die;
		}
		if (isset($imagepath))
		{
			$imclass= new TimgHelper();
			echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$imagepath,$width,$height));
		}else if (isset($image)) //for BE and old vers
		{
			$imclass= new TimgHelper();
			echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$image,$width,$height));
		}
		exit();
	}
	
	public function succesfully()
	{
        $helper = new TestimonialsHelper();
		$params = $helper->getParams();
		if($params->get('modal_on_new')){
			?>
			<script type="text/javascript">
				setTimeout(function(){ parent.location.href=parent.location.href; }, 3000);
			</script>
			<?php
		}else{
			$Itemid=(int) JFactory::getApplication()->input->getInt('Itemid',0);
			$catid=(int) JFactory::getApplication()->input->getInt('catid',0);
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_testimonials&view=testimonials&catid='.$catid.'&Itemid='.$Itemid));
		}
	}
	
	public function new_captcha() 
	{
		$params =  TestimonialHelper::getSettings();
		$img 	 = JURI::base().'index.php?option=com_testimonials&task=captcha.show&unique='.  time();
		$img_ref = JURI::root(true).'/components/com_testimonials/templates/black/images/refresh.png';
		@ob_end_clean();
		header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header ('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		echo '<response><response><![CDATA['.$img.']]></response></response>';
		die;
	}
	
		function check_captcha() {
        $sessions = JFactory::getSession();
		if (defined('_JEXEC')) {
			$captcha = $sessions->get("__default")["captcha"];
		}else {
			$captcha = $sessions->get("captcha");
		}
		$usr_captcha = JFactory::getApplication()->input->get('captcha');
		
		if ($captcha != $usr_captcha) {
			@ob_end_clean();
			header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
			header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header ('Cache-Control: no-cache, must-revalidate');
			header ('Pragma: no-cache');
			header ('Content-Type: text/xml');
			echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
			echo '<response><response>BAD</response></response>';
			die;
		}
	
		@ob_end_clean();
		header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header ('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		echo '<response><response>OK</response></response>';
		die;
	}
	
	public function get_testimonial()
	{
		if (!class_exists('modTestimonialsHelper'))
		{
			require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'mod_testimonials'.DIRECTORY_SEPARATOR.'helper.php');
		}
		
		$settings =  TestimonialHelper::getSettings();
		
		$lang =& JFactory::getLanguage();
		$lang->load('mod_testimonials', JPATH_SITE);
		$id = JFactory::getApplication()->input->getInt('id',0);
		$option = "com_testimonials";
		$Itemid = JFactory::getApplication()->input->getInt('Itemid',0);
		$modal = JFactory::getApplication()->input->getInt('modal',0);
		$database = JFactory::getDBO();
		$query = $database->getQuery(true);
		
		$query->select('t.*');
 		$query->from('`#__tm_testimonials` AS `t`');
		if ($settings->get('use_cb') == 1) 
		{
			 $query->select('compr.avatar');
			  $query->join('LEFT','`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');
		       
		}
		if ($settings->get('use_jsoc'))
		        {
			        $query->select('jsoc.thumb AS avatar');
			        $query->join('LEFT','`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');
		       }
		$query->where('t.`published`= 1');
		$query->where('t.`id`= '.$id);
		$database->setQuery($query);
		$quotes = $database->loadObjectList();

	if (empty($quotes)) 
	{
			$query = $database->getQuery(true);
			$query->select('t.*');
	 		$query->from('`#__tm_testimonials` AS `t`');
			if ($settings->get('use_cb') == 1) 
			{
				 $query->select('compr.avatar');
				  $query->join('LEFT','`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');
			       
			}
			if ($settings->get('use_jsoc'))
		        {
			        $query->select('jsoc.thumb AS avatar');
			        $query->join('LEFT','`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');
		       }
			$query->where('t.`published`= 1');
			$database->setQuery($query);
			$quotes = $database->LoadObjectList();
	} else {
		$quoter = $quotes[0];
	}

	$quotecount = count($quotes);
	$rand = rand( 0, @$quotecount-1 );
	$quoter = $quotes[$rand];
	
	$quote = '';
	$quote_author = '';
	$quote_caption = '';
	$quote_avatar = '';
	$quote_author_description = '';
	$q_id = 0;

	if ($quotecount) {
		$rand = rand( 0, $quotecount-1 );
		
		$link = "";
		$addnew = "";
			if ($modal)
			{
				$link .= "<a id='tstmnl_link_cm' href='".JRoute::_("index.php?option=$option&Itemid=$Itemid&read=true&rid=".$quotes[$rand]->id."#anc_".$quotes[$rand]->id)."'>";
			}
			else
			{
				$link .= "<a id='tstmnl_link_cm' href='".JRoute::_("index.php?option=$option&Itemid=$Itemid&id=".$quotes[$rand]->id."#anc_".$quotes[$rand]->id)."'>";
			}
			
			$link .= stripslashes(JText::_('MOD_TESTIMONIALS_READ'))."</a><br />";
			$addnew .= "<a id='tstmnl_addnew_cm' href='".JRoute::_("index.php?option=com_testimonials&add=open&Itemid=$Itemid#add")."'>";
			$addnew .= stripslashes(JText::_('MOD_TESTIMONIALS_NEW')) . "</a>";
		
		$quote = $quoter->testimonial;
			if(intval($settings->get('symb_qty'))) {
						$quote1 = modTestimonialsHelper::tmm_string_substr($quote,0,$settings->get('symb_qty'));
						if (strlen($quote) > $settings->get('symb_qty')) 
						{
							$quote = $quote1.'...';
						}
			}
		$quote = strip_tags($quote);
		$quote_author = nl2br($quoter->t_author);
		$quote_caption = $quoter->t_caption;
		
		preg_match_all('|href="(.*?)"|',$quoter->author_description, $out);

		$out = $out[1];

		foreach ($out as $key => $val) {

			$origin[] = $val;

			$new[] = $val.'" id="value'.$key;

			$ids[] = 'value'.$key;

		}

		$quote_author_description = str_replace(@$origin,@$new,$quotes[$rand]->author_description);

		$strs = '';

		for ($i = 0; $i < 5; $i++) {

			if (empty($ids[$i])) {

				$strs .= "<input type='hidden' id='value".$i."'>";

			}

		}
		
		
		if ($settings->get('show_avatar_module')== 1) {
		if ($quoter->photo != '' && !$settings->get('use_cb') && !$settings->get('use_jsoc')){
			
			$tmp_name = strrchr($quoter->photo, "/");
			$photo_name = substr($tmp_name, 1);
			$pathfolder = str_replace($tmp_name,'',$quoter->photo);
			
			if (file_exists(JPATH_BASE.DIRECTORY_SEPARATOR.$pathfolder.DIRECTORY_SEPARATOR."th_".$photo_name)) 
			{
				list($width, $height, $type) = getimagesize(JPATH_BASE.DIRECTORY_SEPARATOR.$pathfolder.DIRECTORY_SEPARATOR."th_".$photo_name);
						if ($width == $settings->get('th_width')){
							$image = "/images/testimonials/th_".$photo_name;
						} else {
							unlink(JPATH_BASE.DIRECTORY_SEPARATOR.$pathfolder.DIRECTORY_SEPARATOR."th_".$photo_name);
							modTestimonialsHelper::ResizeImage($photo_name);
							$image = "/images/testimonials/th_".$photo_name;
						}
					}else {
						if (file_exists(JPATH_BASE.DIRECTORY_SEPARATOR.$quoter->photo)){
							modTestimonialsHelper::ResizeImage($photo_name);
							$avatar = "/images/testimonials/th_".$photo_name;
						} else {
							$avatar = JURI::base()."components/com_testimonials/templates/black/images/tnnophoto.jpg";
						}
					}
		}else{
			if ($settings->get('use_cb') == 1) {
				$check = explode('/',@$quoter->avatar);
				$check = $check[0];
				if (isset($quoter->avatar) && $check != 'gallery') {
					if (file_exists(JPATH_BASE."/images/comprofiler/tn".$quoter->avatar)) {
						$avatar = JURI::base()."/images/comprofiler/tn".$quoter->avatar;
					}else{
						$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
					}
				}elseif (isset($quoter->avatar) && $check == 'gallery') {
					if (file_exists(JPATH_BASE."/images/comprofiler/".$quoter->avatar)) {
						$avatar = JURI::base()."/images/comprofiler/".$quoter->avatar;
					}else{
						$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
					}
				}else{
					$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
				}
			}
			 if ($settings->get('use_jsoc') == 1) {
				if (isset($quoter->avatar)) {
					if (file_exists(JPATH_BASE."/".$quoter->avatar)) {
						$avatar = JURI::base()."/".$quoter->avatar;
					}else{
						$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
					}
				}else{
					$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
				}
			}else if ($settings->get('use_cb') != 1){
				$avatar = JURI::base()."/components/com_testimonials/templates/black/images/tnnophoto.jpg";
			}
		}
	}else{
		$image = "";
	}

		if (empty($avatar) && !empty($image)){			
			$quote_avatar = "<img class=\"avatar\" align=\"left\" src=\"".JURI::root()."$image\" border=\"0\" style='padding-right: 5px;' alt=\"\"/>";
		}elseif(empty($image) && !empty($avatar)){
			$quote_avatar = "<img class=\"avatar\" align=\"left\" src=\"$avatar\" border=\"0\" style='padding-right: 5px;' alt=\"\" width=\"61\"/>";
		}else{
			$quote_avatar = '';
		}
		
		$quote = $quote_avatar.$quote;

		$q_id = $quoter->id;
	}
	
	header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
	header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header ('Cache-Control: no-cache, must-revalidate');
	header ('Pragma: no-cache');
	header ('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
	echo '<response>' . "\n";
	echo "\t" . '<task>testimonial</task>' . "\n";
	echo "\t" . '<quote><![CDATA['.$quote.'<!--xx-->]]></quote>' . "\n";
	echo "\t" . '<id>'.$q_id.'<!--xx--></id>' . "\n";
	echo "\t" . '<quote_author><![CDATA['.$quote_author.'<!--xx-->]]></quote_author>' . "\n";
	echo "\t" . '<quote_caption><![CDATA['.$quote_caption.'<!--xx-->]]></quote_caption>' . "\n";
	echo "\t" . '<quote_author_description><![CDATA['.@$quote_author_description.'<!--xx-->]]></quote_author_description>' . "\n";
	echo "\t" . '<strs><![CDATA['.@$strs.'<!--xx-->]]></strs>' . "\n";
	echo "\t" . '<quote_link><![CDATA['.@$link.'<!--xx-->]]></quote_link>' . "\n";
	echo "\t" . '<quote_addnew><![CDATA['.@$addnew.'<!--xx-->]]></quote_addnew>' . "\n";
	echo "\t" . '<quote_avt><![CDATA['.$quote_avatar.'<!--xx-->]]></quote_avt>' . "\n";
	echo '</response>' . "\n";
	die;
}

    public function new_image(){
	$rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";
	$return = array('image'=>'', 'status'=>'bad', 'message'=>'');
    $imageFile = JFactory::getApplication()->input->files->get('image');
	if(!empty($imageFile['tmp_name'])) {
	    $id = JFactory::getApplication()->input->getInt('id');
	    $user = JFactory::getUser();
	    $return['create'] = $user->authorise('core.create', 'com_testimonials');
	    $return['edit'] = $user->authorise('core.edit', 'com_testimonials');
	    if(!$user->authorise('core.create', 'com_testimonials') && !$user->authorise('core.edit', 'com_testimonials')){
		$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		echo(json_encode($return));
		die();
	    }
	    if(!$user->authorise('core.edit', 'com_testimonials') && $id != 0){
		$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		echo(json_encode($return));
		die();
	    }
	    jimport( 'joomla.filesystem.file' );
	    $ext = JFile::getExt($imageFile['name']);
	    $new_name = md5(time() . $imageFile['name']) . '.' . $ext;
	    if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {
		if(JFile::upload($imageFile['tmp_name'], JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $new_name)){
		    if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $new_name)){
			$return['image'] = $new_name;
			$return['status'] = 'ok';
		    }else{
			$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		    }
		}else{
		    $return['message'] = JText::_('COM_TESTIMONIALS_CHECK_PERMITIONS');
		}
	    }else{
		$return['message'] = JText::_('COM_TESTIMONIALS_WRONG_FILE_TYPE');
	    }
	}else $return['message'] = JText::_('COM_TESTIMONIALS_NO_FILE');
	echo(json_encode($return));
	die();
    }
	
    public function new_avatar(){
	$rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";
	$return = array('image'=>'', 'status'=>'bad', 'message'=>'');
    $avatarFile = JFactory::getApplication()->input->files->get('avatar');
	if(!empty($avatarFile['tmp_name'])) {
	    $id = (int)JFactory::getApplication()->input->getInt('id');
	    $user = JFactory::getUser();
	    $return['create'] = $user->authorise('core.create', 'com_testimonials');
	    $return['edit'] = $user->authorise('core.edit', 'com_testimonials');
	    if(!$user->authorise('core.create', 'com_testimonials') && !$user->authorise('core.edit', 'com_testimonials')){
		$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		echo(json_encode($return));
		die();
	    }
	    if(!$user->authorise('core.edit', 'com_testimonials') && $id != 0){
		$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		echo(json_encode($return));
		die();
	    }
	    jimport( 'joomla.filesystem.file' );
	    $ext = JFile::getExt($avatarFile['name']);
	    $new_name = md5(time() . $avatarFile['name']) . '.' . $ext;
	    if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {
		if(JFile::upload($avatarFile['tmp_name'], JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $new_name)){
		    if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $new_name)){
			$return['image'] = $new_name;
			$return['status'] = 'ok';
		    }else{
			$return['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		    }
		}else{
		    $return['message'] = JText::_('COM_TESTIMONIALS_CHECK_PERMITIONS');
		}
	    }else{
		$return['message'] = JText::_('COM_TESTIMONIALS_WRONG_FILE_TYPE');
	    }
	}else $return['message'] = JText::_('COM_TESTIMONIALS_NO_FILE');
	echo(json_encode($return));
	die();
    }
	
}