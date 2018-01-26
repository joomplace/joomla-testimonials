<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * Testimonials Model
 */
class TestimonialsModelTestimonials extends JModelList
{
	protected 	$tag;
	protected 	$limit;
	public 		$layout;
	public 		$random = false;
	public 		$anc = false;
	public 		$avoidacl = false;
	public 		$category='';
	public 		$categories='';

   /**
	 * Method to build an SQL query to load the list data
	 */

	public function getUserLayout(){
		if($this->layout){
			return $this->layout;
		}else{
			$layout = 'default';
			$menuitem = JFactory::getApplication()->getMenu()->getActive();
			if($menuitem){
				if($menuitem->params){
					$layout = $menuitem->params->get('layout','default');
				}
			}
			return JFactory::getApplication()->input->get('layout', $layout);
		}
	}

	protected function populateState($ordering = null, $direction = null){
		parent::populateState($ordering, $direction);
		$app = JFactory::getApplication();
		$menuitem = $app->getMenu()->getActive();
		$category = 0;
		if($menuitem){
			if($menuitem->params){
				$category = $menuitem->params->get('testimonials_category',0);
			}
		}
		$this->avoidacl = $app->input->get('avoidacl', false);
		$category = $app->input->get('catid', $app->input->get('testimonials_category', $category));
		if($this->category){
			$category = $this->category->id;
		}
		if(!$this->anc)
			$this->anc = JFactory::getApplication()->input->get('anc',0,'INT');

		$limit = $this->getListLimit();
		//$app->setUserState('com_testimonials.list.limit', $limit);
		$this->setState('list.limit', $limit);
		$start = $app->input->get('start', $app->input->get('limitstart', 0, 'uint'), 'uint');
		$this->setState('list.start', $start);

		$this->setState('catid', $category);
	}

	protected function getListQuery()
	{
        $params = TestimonialsHelper::getParams();
        $menuitem = JFactory::getApplication()->getMenu()->getActive();
		$input = JFactory::getApplication()->input;
        $tags = "";
        $alltags = "";
        if($menuitem){
            $tags = $menuitem->params->get('tags');
            $alltags = $menuitem->params->get('all_tags');
        }

		$db		= $this->getDbo();

        $query = $db->getQuery(true);
		$select_tapper = '';
		$order_tapper = '';
		if($this->anc){
			$select_tapper = ', IF( `t`.`id` = '.$db->quote($this->anc).', 1, 0 ) AS  `tap_order`';
			$order_tapper = '`tap_order` DESC,';
			/* SEO prevent duplicates */
			$doc = JFactory::getDocument();
			$doc->addHeadLink(JURI::root(true).JUri::getInstance()->getPath(), 'canonical');
			if (!$doc->_metaTags['standard']['robots'])
			{
				$doc->setMetadata('robots', 'noindex, follow');
			}else{
				$doc->_metaTags['standard']['robots'] = 'noindex, follow';
			}
		}
        $query->select('t.*, t_author as `author`, t_caption as `caption`, author_description as `author_descr`, photo as `avatar`, u.name, u.email, GROUP_CONCAT(DISTINCT concat(tag.id, "::", tag.tag_name)) as tags'.$select_tapper)
            ->from('#__tm_testimonials as t')
            ->leftJoin('#__users as u ON t.user_id_t = u.id')
            ->leftJoin('#__tm_testimonials_conformity as tc ON t.id = tc.id_ti')
            ->leftJoin('(SELECT * FROM `#__tm_testimonials_tags` WHERE `published` = "1") as tag ON tc.id_tag = tag.id')
            ->group('t.id');

        $catid = $menuitem ? $menuitem->params->get('testimonials_category', 0) : 0;

		if($catid){

			jimport('joomla.application.categories');
			$categories = new JCategories(array('extension'=>'com_testimonials','access'=>true));
			$this->categories = $categories;
			$cur_cat = $categories->get($catid);
			$this->category = $cur_cat;
			$rel_level = $cur_cat->level;
			$ids = array($cur_cat->id);
			if($cur_cat){
				$subs = $cur_cat->getChildren(true);
				foreach($subs as $s){
					$ids[] = $s->id;
				}

				$query->where('`t`.`catid` IN('.implode(',',$ids).')')
					->select('`c`.`title`')
					->leftJoin('`#__categories` as `c` ON `t`.`catid` = `c`.`id`');
			}
		}

        if($this->tag) $tag = $this->tag;
        else $tag = JFactory::getApplication()->input->get('tag');
        if (($tags && !$alltags) || $tag)
        {
            if($tag) $tags = array($tag);
            $query->where('tc.`id_tag` IN ('.implode(',',$tags).')');
			if(JFactory::getApplication()->input->get->get('tag')){
				/* SEO prevent duplicates */
				$doc = JFactory::getDocument();
				$doc->addHeadLink(JURI::root(true).JUri::getInstance()->getPath(), 'canonical');
				if (!$doc->_metaTags['standard']['robots'])
				{
					$doc->setMetadata('robots', 'noindex, follow');
				}else{
					$doc->_metaTags['standard']['robots'] = 'noindex, follow';
				}
			}
        }

        if ($params->get('use_cb'))
        {
            $query->select('compr.avatar as avatar');
            $query->join('LEFT', '#__comprofiler AS compr ON compr.user_id = t.user_id_t');
        }
        if ($params->get('use_jsoc'))
        {
            $query->select('jsoc.thumb as avatar');
            $query->join('LEFT', '#__community_users AS jsoc ON jsoc.userid = t.user_id_t');
        }
        if(!JFactory::getUser()->authorise('core.moderate','com_testimonials') && !$this->avoidacl)
            $query->where('t.is_approved=1');
        $query->group('t.id');

        if (!JFactory::getUser()->authorise('core.publish', 'com_testimonials') && !$this->avoidacl)
        {
            $query->where('t.`published`=1');
        }
		if(!$this->random){
			$orderBy = $input->get('ordering', 't_caption', 'WORD');
			if ($params->get('show_lasttofirst')==0)
			{
				$query->order($order_tapper.'t.'.$orderBy.' DESC');
			} else $query->order($order_tapper.'t.'.$orderBy.' ASC');
		}else{
			$query->order($order_tapper.' RAND()');
		}

		return $query;
	}

    public function getCustomFields($id=0)
    {
        $db		= $this->getDbo();
        $query	= $db->getQuery(true);
        $query->select('`f`.*,`v`.`value`, 1 AS `custom_field`');
        $query->from('`#__tm_testimonials_custom` AS `f`');
        $query->join('LEFT', '`#__tm_testimonials_items_fields` AS `v` ON `v`.`field_id` = `f`.`id`');
        $query->where('`v`.`item_id`='.$db->quote($id));
        $query->where('`f`.`published`=1');
        $query->order('`f`.`ordering`');
        $db->setQuery($query);

        return $db->loadObjectList('system_name');
    }

	public function setListLimit($limit){
		return $this->limit = $limit;
	}

	public function setTag($tag){
		return $this->tag = $tag;
	}

	protected function getListLimit(){
		if(!$this->limit){
			$app = JFactory::getApplication();
			$params = TestimonialsHelper::getParams();
			$this->limit = $app->getUserStateFromRequest('com_testimonials.list.limit', 'limit', $params->get('list_limit'), 'uint');
		}
		return $this->limit;
	}

	public function getCategory(){
		return $this->category;
	}

	public function getCategories(){
		return $this->categories;
	}
}
