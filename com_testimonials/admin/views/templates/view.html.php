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
 * Templates HTML View class for the Testimonials Component
 */
 
class TestimonialsViewTemplates extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
        function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'templates';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
        	 TestimonialHelper::getCSSJS();
        	 $leftmenu = TestimonialHelper::getLeftMenu();
        	 $this->addToolBar();
        	 
        	$items 		= $this->get('Items');
            $pagination = $this->get('Pagination');
            $state		= $this->get('State');
                
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
              
		  
              $this->leftmenu = $leftmenu;
              $this->items = $items;
              $this->pagination = $pagination;
 			  $this->state = $state;
 			  
                parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
        	
        	//JToolBarHelper::apply('template.apply_template', 'JTOOLBAR_APPLY');
        	JToolBarHelper::editList('template.edit');
        	
            
        }
}
