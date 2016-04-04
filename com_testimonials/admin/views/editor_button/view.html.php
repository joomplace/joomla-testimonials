<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class TestimonialsViewEditor_Button extends JViewLegacy
{
    protected $items = null;
	function display($tpl = null)
	{
		$controller = TestimonialsController::getInstance('');
		$comments_model = $controller->getModel('Editor_button');
		// Get the Data
		$this->form = $comments_model->get('Form');
		$this->item = $comments_model->get('Item');

		// Check for errors.
		if (count($errors = $comments_model->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}


		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}
	
}
