<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('JPATH_BASE') or die;

jimport('joomla.html.html.select');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Testimonials.
 *
 */
class JFormFieldTags extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Tags';


	protected function getInput()
	{
		$option = JFactory::getApplication()->input->getVar('option');
		
		// Initialize variables.
		$html = array();
		$attr = '';
		$this->multiple = true;

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		
		// Get the field options.
		
		$options = (array) $this->getOptions();
		array_unshift($options,JText::_('COM_TESTIMONIALS_TOPIC_SELECT_TAGS'));
		$selected = (array)$this->getSelected();
		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $selected, $this->id);
		}
		return implode($html);
	}
	
	protected function getSelected()
	{
		$id = JFactory::getApplication()->input->getInt('id');
		$selected =  $menuselected = array();
		
		if (sizeof($this->value)>0 && is_array($this->value))
		{
			foreach ( $this->value as $val ) 
			{
				$menuselected[] = $val;
			}
		}

		if ($id)
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			$query->select('id_tag ');
			$query->from('#__tm_testimonials_conformity');
			$query->where('id_ti='.(int)$id);
			// Get the options.
			$db->setQuery($query);
	
			$res = $db->loadAssocList();
			foreach($res as $sel){
			    $selected[] = $sel['id_tag'];
			}
		}
		$selected = array_merge($menuselected, $selected);
		return $selected;
	}
	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('t.id AS value, t.tag_name AS text ');
		$query->from('#__tm_testimonials_tags AS t');
		$query->where('t.published=1');
		$query->order('t.tag_name ASC');
		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
}