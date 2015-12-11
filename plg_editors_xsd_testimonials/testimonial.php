<?php
/**
* Testimonials plugin for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Editor Testimonial buton
 *
 * @package Editors-xtd
 * @since 1.6
 */
class plgButtonTestimonial extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onDisplay($name, $asset, $author)
	{
		$js = "function insertTestimonials() { jInsertEditorText('{testimonials default}', '".$name."'); }";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_testimonials&amp;view=tags&amp;layout=modal&amp;tmpl=component';

		$button = new JObject();
		$button->set('modal', false);
		$button->set('link', '#');
		$button->set('text', JText::_('PLG_TESTIMONIAL_BUTTON_TEXT'));
		$button->set('name', 'list-2');
		$button->set('class', 'btn');
		$button->onclick = 'insertTestimonials(); return false;';
		//$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");
		//if (JRequest::getVar('option')=='com_content')
		return $button; 
		//else return null;
	}
}
