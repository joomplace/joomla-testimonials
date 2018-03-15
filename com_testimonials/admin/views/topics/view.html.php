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
 * Topics HTML View class for the Testimonials Component
 */
 
class TestimonialsViewTopics extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
        function display($tpl = null) 
        {
        $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');	
            $submenu = 'topics';
        	 TestimonialHelper::showTitle($submenu);
        	 TestimonialHelper::showSubmenu($submenu);
			 
        	 TestimonialHelper::getCSSJS();
        	 $this->addToolBar();
        	 
        	 
        	$items 		= $this->get('Items');
            $pagination = $this->get('Pagination');
            $state		= $this->get('State');
                
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
              
		  
              $this->items = $items;
              $this->pagination = $pagination;
 			  $this->state = $state;
			  
			TestimonialsHelper::addSubmenu('topics');
			$this->sidebar = JHtmlSidebar::render();
 			  
                parent::display($tpl);
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
        	JToolBarHelper::addNew('topic.add');
        	JToolBarHelper::editList('topic.edit');
        	JToolBarHelper::divider();
			JToolBarHelper::custom('topics.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('topics.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::custom('topics.approve', 'publish.png', 'publish_f2.png','COM_TESTIMONIALS_TOPICS_APPROVE', true);
            JToolBarHelper::custom('topics.disapprove', 'unpublish.png', 'unpublish_f2.png', 'COM_TESTIMONIALS_TOPICS_DISAPPROVE', true);
        	JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'topics.delete');
        }
	
	protected function getSortFields()
	{
		return array(
			't_caption' => JText::_('COM_TESTIMONIALS_NAME'),
			't_author' => JText::_('JGRID_HEADING_CREATED_BY'),
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'published' => JText::_('JPUBLISHED'),
			'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
