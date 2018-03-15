<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Custom model.
 *
 */
class TestimonialsModelCustom extends JModelAdmin
{
	protected $text_prefix = 'COM_TESTIMONIALS';
		
	public function getTable($type = 'customs', $prefix = 'TestimonialsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{
		$result = parent::getItem($pk);
		return $result;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_testimonials.edit.custom.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('topic.id') == 0) {
				$app = JFactory::getApplication();
				$id = $app->getUserState('com_testimonials.edit.custom.id');
				if ($id) $data->set('id', JFactory::getApplication()->input->getInt('id', $id));
			}
		}

		return $data;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		$app	= JFactory::getApplication();

		$form = $this->loadForm('com_testimonials.customs', 'custom', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}	

	
}