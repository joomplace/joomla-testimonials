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
class TestimonialsViewCustom extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	
        public function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'customs';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	
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
        
        protected function addToolbar()
		{
			JFactory::getApplication()->input->set('hidemainmenu', true);
			$user		= JFactory::getUser();
			$isNew		= ($this->item->id == 0);
			JToolBarHelper::apply('custom.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('custom.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('custom.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('custom.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			JToolBarHelper::cancel('custom.cancel', 'JTOOLBAR_CANCEL');
			JToolBarHelper::divider();
			JToolBarHelper::help('JHELP_COMPONENTS_TESTIMONIALS_CUSTOM_EDIT');
		}
}
?>
