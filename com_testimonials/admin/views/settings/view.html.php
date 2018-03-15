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
 * Customs HTML View class for the Testimonials Component
 */
 
class TestimonialsViewSettings extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	
        function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'settings';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	 $this->settings = TestimonialHelper::getSettings();
        	
        	$this->leftmenu = $leftmenu;
 			$this->state	= $this->get('State');
			$this->item		= $this->get('Item');
			$this->form		= $this->get('Form');
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
	
			$this->addToolbar();
			parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
        	//JFactory::getApplication()->input->set('hidemainmenu', true);
			$user		= JFactory::getUser();
			$isNew		= ($this->item->id == 0);
			JToolBarHelper::apply('settings.apply', 'JTOOLBAR_APPLY');
        }
}
