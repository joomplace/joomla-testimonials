<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');	

/**
 * Comment model.
 *
 */
class TestimonialsModelComment extends JModelAdmin
{
	protected function populateState($ordering = null, $direction = null){
		parent::populateState($ordering, $direction);
	}
	
	public function getTable($type = 'Comments', $prefix = 'TestimonialsTable', $config = array())
	{	
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{
		$result = parent::getItem($pk);
		return $result;
	}
	
	public function getForm($data = array(), $loadData = true){
	    $form = $this->loadForm('com_testimonials.comment', 'comment', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)){
			return false;
		}
	    return $form;
	}
	
	public function save($data){
		$table = $this->getTable();
		
		$rootId = $table->getRootId();		 
		if ($rootId === false)
		{
			$rootId = $table->addRoot();
		}
		if(!$data['comment']){
			$data['comment'] = $rootId;
		}
		if(!$data['ip']){
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
		}
		if(!$data['user']){
			$data['user'] = JFactory::getUser()->id;
		}
		
		
		// Specify where to insert the new node.
		$table->setLocation($data['comment'], 'last-child');
		if($table->bind($data)){
			$table->check();
			if($table->store()){
				$data['id'] = $table->id;
				return $data;
			}
		}
	}
	
	public function delete(&$pks){
		$id = $pks;
		$table = $this->getTable();
		return $table->delete($id);
	}
	
	/*
	 *	$id - testimonial id
	 */
	public function getComments($id = 0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$table = $this->getTable('Comments');
		$root_id = $table->getRootId();	
		
		$query->select($db->qn('id'))
			->from($db->qn('#__tm_comments'))
			->where($db->qn('testimonial').' = '.$db->q($id))
			->where($db->qn('parent_id').' = '.$db->q($root_id))
			->order($db->qn('lft').' ASC');
		$result = $db->setQuery($query)->loadColumn();
		$branches = array();
		foreach($result as $r){
			$branches = array_merge($branches,$this->getCommentsTree($r));
		}
		return $branches;
	}
	
	/*
	 *	$id - parent(comment) id
	 */
	public function getCommentsTree($id = 0){
		$table = $this->getTable('Comments');
		if(!$id){
			$id = $table->getRootId();	
		}
		$subtree = $table->getTree($id);
		return $subtree;
	}
	
	protected function loadFormData(){
	    $data = parent::loadFormData();
	    $error = JFactory::getApplication()->input->getBool('error', false);
		$testimonial = JFactory::getApplication()->input->get('testimonial',0);
		if($testimonial){
			$data['testimonial'] = $testimonial;
		}
		$comment = JFactory::getApplication()->input->get('comment',0);
		if($comment){
			$data['comment'] = $comment;
		}
	    if($error){
			$posted_data = JFactory::getApplication()->getUserState('com_testimonials.edit.comment.data', array());
			foreach($posted_data as $id=>$value){
				$data->$id = $value;
			}
	    }
		
	    return $data;
	}
}
