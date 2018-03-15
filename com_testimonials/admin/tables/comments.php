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
 * Testimonials Comments Table class
 */
class TestimonialsTableComments extends JTableNested
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
			parent::__construct('#__tm_comments', 'id', $db);
	}
	
	/**
	 * Add the root node to an empty table.
	 *
	 * @return    mixed  The id of the new root node or false on error.
	 */
	public function addRoot()
	{
		$db = JFactory::getDbo();
	 
		$query = $db->getQuery(true)
			->insert('#__tm_comments')
			->set('parent_id = 0')
			->set('lft = 0')
			->set('rgt = 1')
			->set('level = 0')
			->set('title = ' . $db->quote('root'))
			->set('alias = ' . $db->quote('root'))
			->set('access = 1')
			->set('path = ' . $db->quote(''));
		$db->setQuery($query);
	 
		if(!$db->execute())
		{
			return false;
		}
	 
		return $db->insertid();
	}
		
}