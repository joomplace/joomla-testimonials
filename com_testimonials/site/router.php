<?php
/**
 * Testimonials Component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined( '_JEXEC' ) or die;

function TestimonialsBuildRoute( &$query ) {
	$segments = array();
	
	$app =& JFactory::getApplication();
	$menu =& $app->getMenu();

    if(!isset($query['Itemid']) || !$query['Itemid']){
        foreach($menu->getMenu() as $id => $item){
            if(!array_diff_assoc($item->query,$query) && !empty($item->query)){
                $query['Itemid'] = $id;
                break;
            }
        }
	}

	$menu_item = $menu->getItem($query['Itemid']);
	
	foreach($query as $key => $val){
		if($key=='option'){
			continue;
		}
		if($query[$key] == $menu_item->query[$key]){
			unset($query[$key]);
		}
	}
	$query = array_filter($query);
	
	if(isset($query['catid'])){
		jimport('joomla.application.categories');
		$categories = new JCategories(array('extension'=>'com_testimonials','access'=>true));
		$cat = $categories->get($query['catid']);
		/* 
		 * removing root from path path
		 * only needed of there only one root 
		 */
		 /*
			$path = explode('/',$cat->path);
			unset($path[0]);
		*/
		$segments[] = $cat->alias;
		unset($query['catid']);
	}
	
	/* will terminate duplicating links */
	if (isset($query['start'])) {
		unset($query['anc']);
	}

    return $segments;
}

function TestimonialsParseRoute($segments) {
	
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$item = $menu->getActive();
	$vars = $item->query;
	$option = $item->query['option'];
	if(!$option) $option = 'com_testimonials';
	
	$segments = array_reverse($segments);
	$segment = str_replace(':','-',$segments[0]);
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select($db->qn('id'))
		->from($db->qn('#__categories'))
		->where($db->qn('extension').' = '.$db->q('com_testimonials'))
		->where($db->qn('alias').' = '.$db->q($segment));
	$db->setQuery($query,0,1);
	if($vars['catid']=$db->loadResult()){
		unset($segments[0]);
	}

    return $vars;
}
