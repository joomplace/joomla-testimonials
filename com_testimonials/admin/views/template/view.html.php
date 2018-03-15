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
 * HTML View class for the Testimonials Component
 */
class TestimonialsViewTemplate extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	
        public function display($tpl = null) 
        {
            $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');
        	$submenu = 'templates';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	
        	$this->leftmenu = $leftmenu;
 			$this->state	= $this->get('State');
			$this->item		= $this->get('Item');
			$this->form		= $this->get('Form');
			$this->custom_fields = $this->get('CustomFields');
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
	
			$this->addToolbar();
			parent::display($tpl);
        }
        
        protected function addToolbar()
		{
			JFactory::getApplication()->input->set('hidemainmenu', true);
			$user		= JFactory::getUser();
			$isNew		= ($this->item->id == 0);
			JToolBarHelper::apply('template.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('template.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CANCEL');
			JToolBarHelper::divider();
			JToolBarHelper::help('JHELP_COMPONENTS_TESTIMONIALS_TEMPLATE_EDIT');
		}
}
?>
