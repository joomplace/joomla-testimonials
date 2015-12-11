<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');
 
/**
 * Custom fields Controller
 */
class TestimonialsControllerCustoms extends JControllerAdmin
{
	protected $view_list = 'customs'; 

	/**
	 * Proxy for getModel.
	 * @since       1.6
	 */
	public function getModel($name = 'Custom', $prefix = 'TestimonialsModel', $config = array('ignore_request' => true)) 
	{
			$model = parent::getModel($name, $prefix, $config);
			return $model;
	}
           
	public function saveOrderAjax()
	{
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	
	public function setrequire(){
		$input = JFactory::getApplication()->input;
		$table = $this->getModel()->getTable();
		
		$ids = JFactory::getApplication()->input->get('cid',array(),'array');
		foreach($ids as $id){
			$table->load($id);
			if($table->id){
				$table->required = 1;
			}
			$table->store();
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view='.$this->view_list, false));
	}
	
	public function unsetrequire(){
		$input = JFactory::getApplication()->input;
		$table = $this->getModel()->getTable();
		
		$ids = JFactory::getApplication()->input->get('cid',array(),'array');
		foreach($ids as $id){
			$table->load($id);
			if($table->id){
				$table->required = 0;
			}
			$table->store();
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view='.$this->view_list, false));
	}
	
	public function display(){
		$input = JFactory::getApplication()->input;
		$table = $this->getModel()->getTable();
		
		$ids = JFactory::getApplication()->input->get('cid',array(),'array');
		foreach($ids as $id){
			$table->load($id);
			if($table->id){
				$table->display = 1;
			}
			$table->store();
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view='.$this->view_list, false));
	}
	
	public function undisplay(){
		$input = JFactory::getApplication()->input;
		$table = $this->getModel()->getTable();
		
		$ids = JFactory::getApplication()->input->get('cid',array(),'array');
		foreach($ids as $id){
			$table->load($id);
			if($table->id){
				$table->display = 0;
			}
			$table->store();
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_testimonials&view='.$this->view_list, false));
	}
	
}