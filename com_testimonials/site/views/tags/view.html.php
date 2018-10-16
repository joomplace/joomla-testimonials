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
 * Topics HTML View class for the Testimonials Component
 */
 
class TestimonialsViewTags extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
        function display($tpl = null) 
        {
        	$submenu = 'tags';
        	$items 		= $this->get('Items');
		$pagination = $this->get('Pagination');
		$state		= $this->get('State');
                
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage($this->get('Errors'), 'error');
                        return false;
                }
		$this->items = $items;
		$this->state = $state;
		JHTML::stylesheet(JURI::root(true).'/components/com_testimonials/assets/bootstrap/css/bootstrap.min.css');
 		parent::display($tpl);
        }

}