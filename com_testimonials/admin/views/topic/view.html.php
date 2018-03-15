<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
 defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
 
/**
 * HTML View class for the Testimonials Component
 */
class TestimonialsViewTopic extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	
        public function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'topic';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	 
        	$this->settings = TestimonialHelper::getSettings();
        	$this->leftmenu = $leftmenu;
 			$this->state	= $this->get('State');
			$this->item		= $this->get('Item');
			$this->form		= $this->get('Form');
			$this->custom_fields = $this->get('CustomFields');
			$this->item->date_added = ($this->item->date_added) ? $this->item->date_added : JFactory::getDate();
			
			if ($this->settings->get('use_cb'))
			{
				$this->avatar	= $this->get('CBAvatar');
			}
			if ($this->settings->get('use_jsoc'))
			{
				$this->avatar	= $this->get('JSAvatar');
			}
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
			JToolBarHelper::apply('topic.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('topic.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('topic.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('topic.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			JToolBarHelper::cancel('topic.cancel', 'JTOOLBAR_CANCEL');
			JToolBarHelper::divider();
			JToolBarHelper::help('JHELP_COMPONENTS_WEBLINKS_LINKS_EDIT');
		}
}
?>
