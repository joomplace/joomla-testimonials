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
 * HTML View class for the Testimonials Component
 */
class TestimonialsViewTestimonials extends JViewLegacy
{
        function display($tpl = null) 
        {
        	
        	$submenu = 'about';
        	TestimonialHelper::showTitle($submenu);
        	$this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');
			
        	$document = JFactory::getDocument();
        	$document->addScript(JURI::root(true).'/administrator/components/com_testimonials/assets/js/js.js');
        	$this->version = TestimonialHelper::getVersion();
			$this->addToolbar();
            TestimonialsBEHelper::addSubmenu('topics');
        	
             parent::display($tpl);
        }
        
        protected function addToolbar(){
            JToolbarHelper::preferences('com_testimonials');
        }
}