<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Testimonials.
 *
 */
class JFormFieldTemplates extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Templates';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$templates = array();
		$folders = @scandir(JPATH_SITE.'/components/com_testimonials/templates');
		if (sizeof($folders))
		foreach( $folders AS $folder) 
		{
			if( !in_array($folder, array('.','..','index.html')) ) $templates[$folder] = $folder;
		}
		return $templates;
	}
}