<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JLoader::register('TestimonialsHelper', JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'testimonials.php');
JFactory::getDocument()->addStyleSheet('components/com_testimonials/assets/css/testimonials_bootstrap3.css');
JFactory::getDocument()->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');

$controller = JControllerLegacy::getInstance('Testimonials');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
