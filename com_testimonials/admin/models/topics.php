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
 * Testimonials Model
 */
class TestimonialsModelTopics extends JModelList
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
				'id','published','is_approved','ordering','t_caption','category','t_author','testimonial',
				'author_description','ip_addr','user_id_t','photo','width','height','blocked',
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
                
                $tagquery = '(SELECT GROUP_CONCAT(`tag_name`) FROM `#__tm_testimonials_tags` WHERE id IN (SELECT id_tag
								FROM `#__tm_testimonials_conformity` WHERE id_ti=`t`.id) AND published=1)';
				                
                $query->select('`t`.*');
                $query->select($tagquery.' AS `tags`');
                $query->select('`c`.`title` AS `category`');
                $query->from('`#__tm_testimonials` AS `t`');
				$query->join('LEFT','`#__categories` AS `c` ON `c`.`id` = `t`.`catid` ');
                
                // Filter by search in title
				$search = $this->getUserStateFromRequest('topics.search', 'filter_search');
				$this->setState('filter.search', $search);
				$search = $this->getState('filter.search');
				
				if (!empty($search)) {
						//$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
						$search = $db->Quote('%'.$search.'%');
						$query->where('('.$tagquery.' LIKE  '.$search.' OR t.`t_caption` LIKE  '.$search.' OR t.`testimonial` LIKE  '.$search.' )');
					}
                $orderCol	= $this->state->get('list.ordering', 't_caption');
		
		$orderDirn	= $this->state->get('list.direction', 'ASC');
                $query->order($db->escape($orderCol.' '.$orderDirn));
                /*if($this->getState('list.ordering', 't_caption')){
                    $query->order($db->getEscaped($this->getState('list.ordering', 't_caption')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
                }*/
                return $query;
        }
        
        public function getItemsByItemId($settings=0)
        {
        	$Itemid = JFactory::getApplication()->input->getInt('Itemid',0);
        	$option = JFactory::getApplication()->input->getVar('option','');
        	$db = JFactory::getDBO();
            $query = $db->getQuery(true);
           
            $tags_menu_ids = $tags_articles_ids = $tags_category_ids = array();
           
	        if ($option == 'com_content') 
	           {
	           	$content_id = (int)JFactory::getApplication()->input->getInt('id', 0 );
	           		if ($content_id)
	           		{
		           		$query->select('tag_id');
			            $query->from('`#__tm_testimonials_tag_assign` AS `t`');
			            $query->where('t.`aid`='.$content_id.' AND t.`type`="article" ');
			            $db->setQuery($query);
			            $tags_articles_ids = $db->loadResultArray();
		           		
		           		$query = $db->getQuery(true);
		           		$query->select('catid');
			           	$query->from('`#__content`');
			           	$query->where('id='.$content_id);
			            $db->setQuery($query);
			            $category_id = $db->loadResult();
			            
			            if ($category_id)
			            {
			            	$query = $db->getQuery(true);
			            	$query->select('tag_id');
				            $query->from('`#__tm_testimonials_tag_assign` AS `t`');
				            $query->where('t.`aid`='.$content_id.' AND t.`type`="category" ');
				            $db->setQuery($query);
				            $tags_category_ids = $db->loadResultArray();
			            }
	           		}
	           }

            if ($Itemid)
            {
            	$query = $db->getQuery(true);
            	$query->select('tag_id');
	            $query->from('`#__tm_testimonials_tag_assign` AS `t`');
	            $query->where('t.`aid`='.$Itemid.' AND t.`type`="menu" ');
	            $db->setQuery($query);
	            $tags_menu_ids = $db->loadResultArray();
            }
            
            $tags = @array_merge($tags_menu_ids, $tags_articles_ids, $tags_category_ids);
	        
	        if ($tags) 
	        {
	        	$assign = true;
	        	$tags = @implode(',', $tags );
	        }
	        
	        $query = $db->getQuery(true);
	        $query->select('DISTINCT t.*');

	        $query->from('`#__tm_testimonials` AS `t`');
	        $query->where('t.`published`=1 ');
	        $query->where('t.`is_approved`=1 ');
	        $query->join('LEFT','`#__tm_testimonials_conformity` AS `c` ON t.id = c.id_ti');
		        if ($settings->get('use_cb'))
		        {
					$db->setQuery("SELECT COUNT(id) FROM #__comprofiler");
					$comprofiler_exists = $db->loadResult();
			
					if($comprofiler_exists){
						$query->select('compr.avatar');
						$query->join('LEFT','`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');
					}
		        }
		         if ($settings->get('use_jsoc'))
		        {
					$db->setQuery("SELECT COUNT(userid) FROM `#__community_users`");
					$jomsocial_exists = $db->loadResult();
			
					if($jomsocial_exists){
						$query->select('jsoc.thumb AS avatar');
						$query->join('LEFT','`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');
					}
		        }
			
			 $tagopt = $settings->get('tag_options');
			
			   if ($tags) 
			   {
			   	if ($tagopt==1 )
				 {
				 	$query->where('(c.`id_tag` IN ('.$tags.')  OR c.id_tag IS NULL OR c.id_tag =0)'); 
				 }
				 else if ($tagopt==2 )
				 {				 
				 	$query->where('c.`id_tag` IN ('.$tags.')'); 
				 }
			   }
			  
			   if ($tagopt==2 )
				 {				 
				 	$query->where('c.`id_tag` IS NOT NULL'); 
				 }
				 
				 $query->order('RAND()');
			 $db->setQuery($query, 0, 10);
			 $items = $db->loadObjectList();
			 //echo "<hr /> SMT Debug:<pre>"; print_R($query->__toString()); echo "</pre><hr />";
			return($items);
        }
}
