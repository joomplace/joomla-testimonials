<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');
 
/**
 * Ratings Model
 */
class TestimonialsModelRatings extends JModelList
{
     /**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
      
       /**
         * Method to build an SQL query to load the list data.
         *
         * @return      string  An SQL query
         */
        protected function getListQuery()
        {        
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
		$query->select('`name`, `id`, `published`');
		$query->from('`#__tm_testimonials_custom`');
		$query->where("`type`='rating'");
		$query->where('published=1');
                return $query;
        }
}
