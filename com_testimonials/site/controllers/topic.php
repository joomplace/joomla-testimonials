<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');
 
/**
 * Topic Controller
 */
class TestimonialsControllerTopic extends JControllerForm
{
  	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}  
	
	public function state() 
	{
		$id = JFactory::getApplication()->input->getInt('id');
		if (!$this->allowPublish() || !$id)
		{
            return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		}
		
		$model		= $this->getModel();
		$table		= $model->getTable();
		$table->load($id);
		if ($table->publish(array($id),$table->published?0:1))
		{
			if(JFactory::getApplication()->input->get('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully '.($table->published?'Published':'Unpublished'));
		}else
        return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		
	}

	public function approve()
	{
        $jinput = JFactory::getApplication()->input;
		$id = $jinput->getInt('id', 0);
        $user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_testimonials') || !$id)
		{
            return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		}

		$model		= $this->getModel();
		$table		= $model->getTable();
		$table->load($id);
		if ($table->approve(array($id),$table->is_approved?0:1))
		{
			if($jinput->get('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully '.($table->published?'Approved':'Disapproved'));
			//$this->setRedirect(JRoute::_('index.php?option=com_testimonials'.$tmpl, false), 'Successfully '.($table->published?'Published':'Unpublished'));
		}else
        return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');

	}
	
	
	public function delete() 
	{
        $jinput = JFactory::getApplication()->input;
	    $id = $jinput->getInt('id', 0);
		if (!$this->allowDelete() || !$id)
		{
            return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		}
		
		$model		= $this->getModel();
		$table		= $model->getTable();
		if ($table->delete($id))
		{
			if($jinput->get('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully deleted');
			//$this->setRedirect(JRoute::_('index.php?option=com_testimonials'.$tmpl, false), 'Successfully deleted');
		}else
        return JFactory::getApplication()->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
		
	}
	
	protected function allowEdit($data = array(), $key = 'id')
        {
            return JFactory::getUser()->authorise('core.edit', 'com_testimonials');
        }
    protected function allowPublish()
        {
            return JFactory::getUser()->authorise('core.publish', 'com_testimonials');
        }
    protected function allowDelete()
        {
            return JFactory::getUser()->authorise('core.delete', 'com_testimonials');
             
        }
        
	protected function checkEditId($context, $id)
	{
		return true;
	}
	
	public function getCustomFields()
	{ $id = JFactory::getApplication()->input->getInt('id', 0);
			 $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('c.id, c.name, c.type, eif.value');
                $query->from('`#__tm_testimonials_custom` AS `c`');
				$query->join('LEFT', '#__tm_testimonials_items_fields AS eif ON eif.field_id = c.id AND eif.item_id='.$id);
				$query->group('c.id');		
				$db->setQuery($query->__toString());			
				return $db->loadObjectList();
	} 
	      
	      
	public function save($key = null, $urlVar = null) {
	
  	    $app = JFactory::getApplication();
		$helper = new TestimonialsFEHelper();
		$params = $helper->getParams();
        $Itemid = $app->input->getInt('Itemid',0);

        $urlAppends = '';
        if($params->get('modal_on_new')){
		    if($app->input->get('tmpl', '') == 'component'){
                $urlAppends = '&tmpl=component';
            } else {
                $urlAppends = '&Itemid='.$Itemid;
            }
		}else{
			$urlAppends = '&Itemid='.$Itemid;
		}

        $data = $app->input->get('jform',array(),'ARRAY');

        $caption = $data['t_caption'];
        $author = $data['t_author'];
        $isEdited = array_key_exists('user_id_t',$data);

		if (parent::save()) {
			$app->setUserState('com_testimonials.edit.form.data', array());
			$id	= $app->input->getInt('id');

            $db = JFactory::getDBO();

            if ($id == 0) {
                $sql = "SELECT id FROM #__tm_testimonials ORDER BY id DESC LIMIT 1";
                $db->setQuery($sql);
                $t_id = $db->loadResult();
                $id = $t_id;
            }else {
                $t_id = $id;
            }

            if(!$data['catid']){
                $sql = "SELECT `catid` FROM `#__tm_testimonials` WHERE `id`='".(int)$id."'";
                $db->setQuery($sql);
                $data['catid'] = $db->loadResult();
            }

			$jform = $app->input->get('jform','array','ARRAY');
			$remove_image = $app->input->get('remove_image','','STRING');
			$remove_image = trim($remove_image, '|');
			$remove_image = explode('|', $remove_image);

			if (!empty($jform['exist_images'])){
                $images = explode("|", $jform['exist_images']);
                jimport( 'joomla.filesystem.file' );
			foreach ($images as $id => $image) {
				if (is_array($remove_image) && in_array($image, $remove_image)) {
				    if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) JFile::delete(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image);
				    unset($images[$id]);
				}
				if(!empty($images[$id]) && !JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) unset($images[$id]);
			}

                if (!empty($images)) {
				$sql = "UPDATE #__tm_testimonials SET images='".implode('|', $images)."' WHERE id='".$t_id."'";
				$db->setQuery($sql);
				$db->execute();
			}
            }


			if ($params->get('autoapprove')== 0)
			{
                if(!TestimonialsFEHelper::can('manage')){
                    $msg = JText::_('COM_TESTIMONIALS_MSG_APPROVE');
					$query = "UPDATE `#__tm_testimonials` SET `is_approved`='0' WHERE `id`=".$t_id;
					$db->setQuery($query);
					$db->execute();
                }else{
					$query = "UPDATE `#__tm_testimonials` SET `is_approved`='1' WHERE `id`=".$t_id;
					$db->setQuery($query);
					$db->execute();
                }
			}
			else
			{
				$msg = JText::_('COM_TESTIMONIALS_SUBMIT_SAVE_SUCCESS');
			}

            TestimonialsFEHelper::notifyAdmin($caption, $author, $isEdited, $t_id);

			/*
			$help = new TestimonialsFEHelper();
			
			$item = $help->getActiveItem();
			if($item->id) $this->setRedirect(JRoute::_('index.php?option=com_testimonials&itemid='.$item->id, false),$msg);	
			else $this->setRedirect(JRoute::_('index.php?option=com_testimonials', false),$msg);	
			*/
			$this->setRedirect(JRoute::_('index.php?option=com_testimonials&catid='.$data['catid'].'&task=succesfully'.$urlAppends.'', false),$msg);
		}		
	}
	
	public function saveInputFields()
    {
        $jinput = JFactory::getApplication()->input;
	    $data  = $jinput->get('jform', array(), 'ARRAY');
	    $data['remove_image'] = $jinput->get('remove_image', '', 'STRING');
	    $customs = $jinput->get('customs_name', array(), 'ARRAY');
	    if(count($customs)>0){
            foreach($customs as $id=>$value){
                $data['customs_name'][$id] = $value;
            }
	    }
	    $customs = $jinput->get('customs_link', array(), 'ARRAY');
	    if(count($customs)>0){
            foreach($customs as $id=>$value){
                $data['customs_link'][$id] = $value;
            }
	    }
	    $customs = $jinput->get('customs', array(), 'ARRAY');
	    if(count($customs)>0){
            foreach($customs as $id=>$value){
                $data['customs'][$id] = $value;
            }
	    }
	    JFactory::getApplication()->setUserState('com_testimonials.edit.form.data', $data);
	}
}