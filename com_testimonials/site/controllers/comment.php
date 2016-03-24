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
class TestimonialsControllerComment extends JControllerForm
{
  	public function getModel($name = 'Comment', $prefix = 'TestimonialsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}  
	
	
	public function delete() 
	{
		$app = JFactory::getApplication();
		$id = $app->input->get('id',0);
		$ajax = $app->input->get('ajax',0,'INT');
		$model = $this->getModel();
		$user = JFactory::getUser();
		$table = $model->getTable();
		$table->load($id);
		if($user->authorise('core.delete_comments', 'com_testimonials') || ($user->id && $user->id == $table->user)){
			$data = $model->delete($id);
		}
		
		if($ajax){
			echo json_encode($data);			
			$app->close(200);
		}else{
			$app->redirect($_SERVER['HTTP_REFERER']);
		}
	}
	      
	      
	public function save($key = null, $urlVar = null) {
		
		$app = JFactory::getApplication();
		
		$jform = $app->input->post->get('jform',array(),'array');
		$ajax = $app->input->get('ajax',0,'INT');
		$user = JFactory::getUser();
		if($jform['comment']?$user->authorise('core.reply', 'com_testimonials'):$user->authorise('core.comment', 'com_testimonials')){
			$model = $this->getModel();
			$data = $model->save($jform);
		}
		
		if($ajax){
			echo json_encode($data);			
			$app->close(200);
		}else{
			$app->redirect($_SERVER['HTTP_REFERER']);
		}
		
	}
}
