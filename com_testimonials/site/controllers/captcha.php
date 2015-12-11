<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die ('Restricted accecss');

jimport('joomla.application.component.controllerform');

class TestimonialsControllerCaptcha extends JControllerForm {
	
	public function getModel($name = 'captcha', $prefix = '', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function show() {
		$image='';
		require_once (JPATH_BASE.'/components/com_testimonials/assets/captcha/image.php');
		die();
		die ($image);
	}

}