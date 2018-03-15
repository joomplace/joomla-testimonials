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
JFormHelper::loadFieldClass('groupedlist');

// Import the com_menus helper.
require_once realpath(JPATH_ADMINISTRATOR.'/components/com_menus/helpers/menus.php');

/**
 * Supports an HTML select list of menu item
 *
 */
class JFormFieldMenuTag extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 */
	public $type = 'MenuTag';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get the field groups.
		$groups = (array) $this->getGroups();
		$selected = (array)$this->getSelected();
		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('select.groupedlist', $groups, null, array('list.attr' => $attr, 'id' => $this->id, 'list.select' => $selected, 'group.items' => null, 'option.key.toHtml' => false, 'option.text.toHtml' => false));
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('select.groupedlist', $groups, $this->name, array('list.attr' => $attr, 'id' => $this->id, 'list.select' => $selected, 'group.items' => null, 'option.key.toHtml' => false, 'option.text.toHtml' => false));
		}
		
		return implode($html);
	}
	
	protected function getSelected()
	{
		$id = JFactory::getApplication()->input->getInt('id');
		$selected = array();
		if ($id)
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			$query->select('aid');
			$query->from('#__tm_testimonials_tag_assign');
			$query->where('type='."'menu'");
			$query->where('tag_id='.(int)$id);
			$db->setQuery($query);
			
			$selected = $db->loadResultArray();
		}
		return $selected;
	}
	/**
	 * Method to get the field option groups.
	 *
	 * @return	array	The field option objects as a nested array in groups.
	 */
	protected function getGroups()
	{
		// Initialize variables.
		$groups = array();
		// Initialize some field attributes.
		$menuType = (string) $this->element['menu_type'];
		$published = $this->element['published'] ? explode(',', (string) $this->element['published']) : array();
		$disable = $this->element['disable'] ? explode(',', (string) $this->element['disable']) : array();

		// Get the menu items.
		$items = MenusHelper::getMenuLinks($menuType, 0, 0, $published);

		$groups[][] = JHtml::_('select.option', 0, JText::_('COM_TESTIMONIALS_SELECT_ITEMS'), 'value', 'text');
		
		// Build group for a specific menu type.
		if ($menuType) {
			// Initialize the group.
			$groups[$menuType] = array();

			// Build the options array.
			foreach($items as $link) {
				$groups[$menuType][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, $disable));
			}
		}

		// Build groups for all menu types.
		else {
			// Build the groups arrays.
			foreach($items as $menu)
			{
				// Initialize the group.
				$groups[$menu->menutype] = array();

				// Build the options array.
				foreach($menu->links as $link) {
					$groups[$menu->menutype][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, $disable));
				}
			}
		}

		// Merge any additional groups in the XML definition.
		$groups = array_merge(parent::getGroups(), $groups);

		return $groups;
	}
}