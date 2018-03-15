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
 * Ratings HTML View class for the Testimonials Component
 */
 
class TestimonialsViewRatings extends JViewLegacy
{
	protected $items;
	
        function display($tpl = null) 
        {
        	$this->items = $this->get('Items');
        	$this->state = $this->get('State');
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
 			  
                parent::display($tpl);
        }
}