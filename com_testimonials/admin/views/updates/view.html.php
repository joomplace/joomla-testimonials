<?php
/**
 * Testimonials component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Updates View
 */
class TestimonialsViewUpdates extends JView
{
	/**
	 * Updates view display method
	 * @return void
	 */
	function display($tpl = null)
	{
$this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');		
// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		JFactory::getApplication()->input->set('hidemainmenu', true);

		JToolBarHelper::title(JText::_('COM_TESTIMONIALS_TOOLBAR_TITLE_UPDATE'),'update');
		
		// Display the template
		parent::display($tpl);
	}
}