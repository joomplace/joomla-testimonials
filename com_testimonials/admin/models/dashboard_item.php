<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class TestimonialsModelDashboard_item extends JModelAdmin
{
	protected $context = 'com_testimonials';

	public function getTable($type = 'Dashboard_item', $prefix = 'TestimonialsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		$form = $this->loadForm('com_testimonials.dashboard_item', 'dashboard_item', array('control' => 'jform', 'load_data' => false));
		if (empty($form)) {
			return false;
		}

        $item = $this->getItem();

		$form->bind($item);

		return $form;
	}
}
