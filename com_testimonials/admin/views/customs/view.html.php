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
 
class TestimonialsViewCustoms extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
        function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'customs';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $this->addToolBar();
        	 
        	$items 		= $this->get('Items');
            $pagination = $this->get('Pagination');
            $state		= $this->get('State');
                
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage($this->get('Errors'), 'error');
                        return false;
                }
              
		  
              $this->items = $items;
              $this->pagination = $pagination;
 			  $this->state = $state;

            TestimonialsBEHelper::addSubmenu('customs');

			$this->sidebar = JHtmlSidebar::render();
 			  
                parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
        	JToolBarHelper::addNew('custom.add');
        	JToolBarHelper::editList('custom.edit');
        	JToolBarHelper::divider();
			JToolBarHelper::custom('customs.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('customs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
        	JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'customs.delete');
        }
}
