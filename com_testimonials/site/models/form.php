<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/topic.php';

/**
 * Topic model.
 *
 */
class TestimonialsModelForm extends TestimonialsModelTopic
{
	protected function populateState($ordering = null, $direction = null){
		parent::populateState($ordering, $direction);
		$app = JFactory::getApplication();
		$menuitem = $app->getMenu()->getActive();
		if($menuitem){
			if($menuitem->params){
				$layout = $menuitem->params->get('testimonials_category',0);
			}
		}
		$this->setState('catid', $app->input->get('catid', $app->input->get('testimonials_category', $layout)));
	}
	
	public function getCustomFields()
	{ 
		$id = JFactory::getApplication()->input->getInt('id', 0);
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select('c.id, c.name, c.type, eif.value, c.required, c.descr');
		$query->from('`#__tm_testimonials_custom` AS `c`');
		$query->join('LEFT', '#__tm_testimonials_items_fields AS eif ON eif.field_id = c.id AND eif.item_id='.$id);
		$query->where('c.published=1');	
		$query->group('c.id');		
		$query->order('c.ordering');	
		$db->setQuery($query->__toString());			
		$fields = $db->loadObjectList();
		$error = JFactory::getApplication()->input->getBool('error', false);
		if($error){
		    $posted_data = JFactory::getApplication()->getUserState('com_testimonials.edit.form.data', array());
		    foreach($fields as &$field){
			if($field->type == 'url'){
			    $field->value = array('', '');
			    if(!empty($posted_data['customs_link'][$field->id])) $field->value[0] = $posted_data['customs_link'][$field->id];
			    if(!empty($posted_data['customs_name'][$field->id])) $field->value[1] = $posted_data['customs_name'][$field->id];
			    $field->value = implode('|', $field->value);
			}else{
			    if(!empty($posted_data['customs'][$field->id])) $field->value = $posted_data['customs'][$field->id];
			}
		    }
		    unset($field);
		}
		return $fields;
	}
	
	public function getForm($data = array(), $loadData = true){
	    $form = $this->loadForm('com_testimonials.form', 'topic',
				    array('control' => 'jform', 'load_data' => $loadData));
	    if (empty($form)) 
	    {
		return false;
	    }
	    $params =  TestimonialsHelper::getParams();
	    if($params->get('show_authorname') == 0){
		$form->setFieldAttribute('t_author', 'required', 'false');
	    }
	    return $form;
        }
	
	protected function loadFormData(){
	    $data = parent::loadFormData();

	    $error = JFactory::getApplication()->input->getBool('error', false);
	    if($error){
			$posted_data = JFactory::getApplication()->getUserState('com_testimonials.edit.form.data', array());
			foreach($posted_data as $id=>$value){
				$data[$id] = $value;
			}
	    }

		$data['catid'] = $this->getState('catid');
		
	    return $data;
	}
	
	public function getTags(){
	    $return = new stdClass();
	    $return->selected = array();
	    $db		= JFactory::getDbo();
	    $query	= $db->getQuery(true);

	    $query->select('t.id AS value, t.tag_name AS text ');
	    $query->from('#__tm_testimonials_tags AS t');
	    $query->where('t.published=1');
	    $query->order('t.tag_name ASC');
	    $db->setQuery($query);

	    $return->tags = $db->loadObjectList();
	    $id = JFactory::getApplication()->input->getInt('id', 0);
	    if($id > 0){
		$query	= $db->getQuery(true);
		$query->select('id_tag ');
		$query->from('#__tm_testimonials_conformity');
		$query->where('id_ti='.(int)$id);
		$db->setQuery($query);
		$sel = $db->loadObjectList();
		foreach($sel as $tag_id) $return->selected[] = $tag_id->id_tag;
	    }
	    return $return;
	}
}
