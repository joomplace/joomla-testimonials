<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
 
/**
 * Help HTML View class for the Testimonials Component
 */
class TestimonialsViewHelp extends JViewLegacy
{
        function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'help';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	 $this->leftmenu = $leftmenu; 
		 $this->addToolbar();
            parent::display($tpl);
        }
	
	protected function addToolbar(){
	    JToolbarHelper::preferences('com_testimonials');
	}
}