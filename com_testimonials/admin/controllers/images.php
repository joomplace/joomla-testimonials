<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');
 
/**
 * Custom fields Controller
 */
class TestimonialsControllerImages extends JControllerAdmin
{
    
    protected function allowEdit($data = array(), $key = 'id'){
	return JFactory::getUser()->authorise('core.edit', 'com_testimonials');             
    }
    
    public function addImage(){
	if($this->allowEdit()){
	    $model = parent::getModel('Images', 'TestimonialsModel', array('ignore_request' => true));
	    $model->uploadImage();
	}
	die();
    }
    
    public function deleteImage(){
	if($this->allowEdit()){
	    $model = parent::getModel('Images', 'TestimonialsModel', array('ignore_request' => true));
	    $model->deleteImage();
	}
	die();
    }
    
    public function imageThumb(){
	$TimgHelper = new TimgHelper();
	$image = JFactory::getApplication()->input->getString('image');
	$image = basename($image);
	$width = JFactory::getApplication()->input->getInt('width',80);
	$height = JFactory::getApplication()->input->getInt('height',80);
	if(file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)){
	    echo $TimgHelper->show($TimgHelper->resize(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image, $width, $height));
	}else{
	    echo JText::_('COM_TESTIMONIALS_NO_FILE');
	}
	die();
    }
}
?>
