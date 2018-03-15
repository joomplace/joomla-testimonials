<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$params = TestimonialsHelper::getParams();

if($params->get('bootstrap',1)){
	// include localized(wrapped) bootstrap
	JFactory::getDocument()->addStyleSheet('components/com_testimonials/assets/css/testimonials_bootstrap3.css');
}
if($params->get('fontawesome',1)){
	// include font-awesome
	JFactory::getDocument()->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
}
