<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');
 
/**
 * Customs Table class
 */
class TestimonialsTableCustoms extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__tm_testimonials_custom', 'id', $db);
        }
        function store($updateNulls = false)
        {
			$db = JFactory::getDbo();
			// creating system(programmatic) name
			$this->system_name =  $db->escape( str_replace(' ','',strtolower($this->name)) );
			
			//storing data
        	$res = parent::store($updateNulls);
        	//$this->reorder();
			return $res;
        }
}