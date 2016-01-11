<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Topic model.
 *
 */
class TestimonialsModelImages extends JModelList
{		
	public function uploadImage(){
	    $rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";
	    $return = array(array('image'=>'', 'status'=>'bad', 'message'=>''));
	    if(!empty($_FILES['files']['tmp_name'])) {
		$id = (int)JFactory::getApplication()->input->getInt('id');
		$user = JFactory::getUser();
		if(!$user->authorise('core.create', 'com_testimonials') && !$user->authorise('core.edit', 'com_testimonials')){
		    $return[0]['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		    echo(json_encode($return));
		    die();
		}
		if(!$user->authorise('core.edit', 'com_testimonials') && $id != 0){
		    $return[0]['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
		    echo(json_encode($return));
		    die();
		}
		jimport( 'joomla.filesystem.file' );
		$totalFiles = count($_FILES['files']['tmp_name']);
		for($i=0;$i<$totalFiles;$i++){
		    $ext = JFile::getExt($_FILES['files']['name'][$i]);
		    $new_name = md5(time() . $_FILES['files']['name'][$i]) . '.' . $ext;
		    if (preg_match($rEFileTypes, strrchr($new_name, '.'))) {
			$image_path = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR;
			if(JFile::upload($_FILES['files']['tmp_name'][$i], $image_path . $new_name)){
			    if(JFile::exists($image_path . $new_name)){
				$TimgHelper = new TimgHelper();
				$params = JComponentHelper::getParams('com_testimonials');
				$thumb = $TimgHelper->captureImage($TimgHelper->resize($image_path . $new_name, $params->get('th_width', '110'), $params->get('th_width', '110')), $new_name);
				JFile::write($image_path.'th_'.$new_name, $thumb);
				$thumb = $TimgHelper->captureImage($TimgHelper->resize($image_path . $new_name, $params->get('thumb_width', '110'), $params->get('thumb_width', '110')), $new_name);
				JFile::write($image_path.'thumb_'.$new_name, $thumb);
				
				$return[$i]['image'] = $new_name;
				$return[$i]['status'] = 'ok';
				$return[$i]['name'] = $new_name;
				$return[$i]['size'] = filesize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $new_name);
				$return[$i]['url'] = JUri::root(true).'/images/testimonials/' . $new_name;
				$return[$i]['thumbnail_url'] = 'index.php?option=com_testimonials&task=images.imageThumb&id='.$id.'&image='.urlencode($new_name);
				$return[$i]['delete_url'] = 'index.php?option=com_testimonials&task=images.deleteImage&id='.$id.'&image='.urlencode($new_name);
				$return[$i]['delete_type'] = 'DELETE';
			    }else{
				$return[$i]['message'] = JText::_('COM_TESTIMONIALS_ERROR_UPLOADING');
			    }
			}else{
			    $return[$i]['message'] = JText::_('COM_TESTIMONIALS_CHECK_PERMITIONS');
			}
		    }else{
			$return[$i]['message'] = JText::_('COM_TESTIMONIALS_WRONG_FILE_TYPE');
		    }
		}
	    }else $return[0]['message'] = JText::_('COM_TESTIMONIALS_NO_FILE');
	    echo(json_encode($return));
	    die();
	}
	
	public function deleteImage(){
	    $return = array(array('image'=>'', 'status'=>'bad', 'message'=>''));
	    $return[0]['image'] = JFactory::getApplication()->input->getString('image');
	    $return[0]['status'] = 'ok';
	    echo(json_encode($return));
	    die();
	}

}