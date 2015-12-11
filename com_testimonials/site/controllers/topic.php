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
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
		
		$model		= $this->getModel();
		$table		= $model->getTable();
		$table->load($id);
		if ($table->publish(array($id),$table->published?0:1))
		{
			if(JFactory::getApplication()->input->getVar('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully '.($table->published?'Published':'Unpublished'));
		}else
		return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		
	}

	public function approve()
	{
		$id = JFactory::getApplication()->input->getInt('id');
        $user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_testimonials') || !$id)
		{
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		$model		= $this->getModel();
		$table		= $model->getTable();
		$table->load($id);
		if ($table->approve(array($id),$table->is_approved?0:1))
		{
			if(JFactory::getApplication()->input->getVar('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully '.($table->published?'Approved':'Disapproved'));
			//$this->setRedirect(JRoute::_('index.php?option=com_testimonials'.$tmpl, false), 'Successfully '.($table->published?'Published':'Unpublished'));
		}else
		return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));

	}
	
	
	public function delete() 
	{
		$id = JFactory::getApplication()->input->getInt('id');
		if (!$this->allowDelete() || !$id)
		{
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
		
		$model		= $this->getModel();
		$table		= $model->getTable();
		if ($table->delete($id))
		{
			if(JFactory::getApplication()->input->getVar('tmpl')=='component') $tmpl="&tmpl=component"; else $tmpl='';
                        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false), 'Successfully deleted');
			//$this->setRedirect(JRoute::_('index.php?option=com_testimonials'.$tmpl, false), 'Successfully deleted');
		}else
		return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		
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
	
		$helper = new TestimonialsHelper();
		$params = $helper->getParams();
		$urlAppends = '';
		if($params->get('modal_on_new')){
			$urlAppends = '&tmpl=component';
		}else{
			$Itemid=(int) JFactory::getApplication()->input->getInt('Itemid',0);
			$urlAppends = '&Itemid='.$Itemid;
		}
	
		$params = TestimonialsHelper::getParams();
		$db = JFactory::getDBO();

        $data = $_REQUEST['jform'];
        $caption = $data['t_caption'];
        $author = $data['t_author'];
        $isEdited = array_key_exists('user_id_t',$data);
		require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'recaptchalib.php');
		if(!JFactory::getUser()->authorise('core.edit', 'com_testimonials')){
			if ($params->get('show_recaptcha')) {
				$resp = recaptcha_check_answer ($params->get('recaptcha_privatekey'), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
				if (!$resp->is_valid) {
					$this->saveInputFields();
					$id = JFactory::getApplication()->input->getInt('id', 0);
					$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=form'.$urlAppends.'&id='.$id.'&error=true'), JText::_('COM_TESTIMONIALS_CHECK_CAPTCHA'), 'error');
					return NULL;
				}
			} else {
				if ($params->get('show_captcha') && !$params->get('show_recaptcha')) {
				$sessions = JFactory::getSession();
				if ($sessions->get("captcha") && $sessions->get("captcha")!=JFactory::getApplication()->input->getVar('captcha_value','','post')) {
					$this->saveInputFields();
					$id = JFactory::getApplication()->input->getInt('id', 0);
					$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=form'.$urlAppends.'&id='.$id.'&error=true'), JText::_('COM_TESTIMONIALS_CHECK_CAPTCHA'), 'error');
					return NULL;
				}
				}
			}
		}

		if (parent::save()) {
			JFactory::getApplication()->setUserState('com_testimonials.edit.form.data', array());
			$id	= JFactory::getApplication()->input->getInt('id');

			$model		= $this->getModel();
			$table		= $model->getTable();
			//$params = TestimonialHelper::getSettings();

            if ($id == 0) {
                $sql = "SELECT id FROM #__tm_testimonials ORDER BY id DESC LIMIT 1";
                $db->setQuery($sql);
                $t_id = $db->loadResult();
                $id = $t_id;
            }else {
                $t_id = $id;
            }

			$rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";

			$exist = JFactory::getApplication()->input->getVar('jform');
			$remove_image = JFactory::getApplication()->input->getVar('remove_image');
			$remove_image = trim($remove_image, '|');
			$remove_image = explode('|', $remove_image);
			$images = explode("|", $exist['exist_images']);
			foreach ($images as $id => $image) {
				jimport( 'joomla.filesystem.file' );
				if (is_array($remove_image) && in_array($image, $remove_image)) {
				    if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) JFile::delete(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image);
				    unset($images[$id]);
				}
				if(!empty($images[$id]) && !JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) unset($images[$id]);
			}

			if (count($images) > 0) {
				$sql = "UPDATE #__tm_testimonials SET images='".implode('|', $images)."' WHERE id='".$t_id."'";
				$db->setQuery($sql);
				$db->execute();
			}

			if ($params->get('autoapprove')== 0)
			{
                if(!TestimonialsHelper::can('manage')){
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
				$msg = JText::_('JLIB_APPLICATION_SUBMIT_SAVE_SUCCESS');
			}

            TestimonialsHelper::notifyAdmin($caption, $author, $isEdited);

			/*
			$help = new TestimonialsHelper();
			
			$item = $help->getActiveItem();
			if($item->id) $this->setRedirect(JRoute::_('index.php?option=com_testimonials&itemid='.$item->id, false),$msg);	
			else $this->setRedirect(JRoute::_('index.php?option=com_testimonials', false),$msg);	
			*/
			$this->setRedirect(JRoute::_('index.php?option=com_testimonials&task=succesfully'.$urlAppends.'', false),$msg);
		}		
	}
	
	public function saveInputFields(){
	    $data  = JFactory::getApplication()->input->getVar('jform', array(), 'post', 'array');
	    $data['remove_image'] = JFactory::getApplication()->input->getVar('remove_image', '');
	    $customs = JFactory::getApplication()->input->getVar('customs_name', array(), 'post', 'array');
	    if(count($customs)>0){
		foreach($customs as $id=>$value){
		    $data['customs_name'][$id] = $value;
		}
	    }
	    $customs = JFactory::getApplication()->input->getVar('customs_link', array(), 'post', 'array');
	    if(count($customs)>0){
		foreach($customs as $id=>$value){
		    $data['customs_link'][$id] = $value;
		}
	    }
	    $customs = JFactory::getApplication()->input->getVar('customs', array(), 'post', 'array');
	    if(count($customs)>0){
		foreach($customs as $id=>$value){
		    $data['customs'][$id] = $value;
		}
	    }
	    JFactory::getApplication()->setUserState('com_testimonials.edit.form.data', $data);
	}
}
