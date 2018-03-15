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
class TestimonialsModelTags extends JModelList
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
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id','tag_name','published', 'section', 'category', 
				'article', 'menu',
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
   
                $selectart = '( SELECT GROUP_CONCAT( `title` ) FROM `#__content` WHERE `id` IN	(SELECT `aid` FROM `#__tm_testimonials_tag_assign` AS `art` WHERE `art`.`type` = \'article\' AND `art`.`tag_id` = `t`.id)) AS `articles`';
       			$selectcat = '( SELECT GROUP_CONCAT( `title` ) FROM `#__categories` WHERE `id` IN	(SELECT `aid` FROM `#__tm_testimonials_tag_assign` AS `cat` WHERE `cat`.`type` = \'category\' AND `cat`.`tag_id` = `t`.id)) AS `categories`';
       			$selectmen = '( SELECT GROUP_CONCAT( `title` ) FROM `#__menu` WHERE `id` IN	(SELECT `aid` FROM `#__tm_testimonials_tag_assign` AS `men` WHERE `men`.`type` = \'menu\' AND `men`.`tag_id` = `t`.id)) AS `menu`';
       		
       		$query->select('t.*');
       		$query->select($selectart);
       		$query->select($selectcat);
       		$query->select($selectmen);
            $query->from('`#__tm_testimonials_tags` AS `t`');

                // Filter by search in title
				$search = $this->getUserStateFromRequest('tags.search', 'filter_search');
				$this->setState('filter.search', $search);
				$search = $this->getState('filter.search');
				
				if (!empty($search)) {
						$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
						$query->where('(t.tag_name LIKE  '.$search.' )');
					}
                
                $orderCol	= $this->state->get('list.ordering', 'tag_name');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		$query->order($db->escape($orderCol.' '.$orderDirn));
            //$query->order($db->getEscaped($this->getState('list.ordering', 'tag_name')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
                return $query;
        }
}
