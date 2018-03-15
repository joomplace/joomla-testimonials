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
 * Tags Model
 */
class TestimonialsModelCustoms extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id','name','published', 'ordering', 'type', 
				'required', 'descr',
			);
		}
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
       		$query->select('c.*');
            $query->from('`#__tm_testimonials_custom` AS `c`');
	    $orderCol	= $this->state->get('list.ordering', 'name');
	    $orderDirn	= $this->state->get('list.direction', 'ASC');
	    $query->order($db->escape($orderCol.' '.$orderDirn));
            //$query->order($db->getEscaped($this->getState('list.ordering', 'name')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
          return $query;
        }
}
