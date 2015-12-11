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
 * Editor Testimonials summary rating buton
 *
 * @package Editors-xtd
 * @since 1.6
 */
class plgButtonTmrating extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onDisplay($name, $asset, $author)
	{
	    if($name != 'jform_params_tm_intro_text') return null;
	    $db = JFactory::getDbo();
	    $query = $db->getQuery(true);
	    $query->select('`name`');
	    $query->from('`#__tm_testimonials_custom`');
	    $query->where("`type`='rating'");
	    $query->where('published=1');
	    $db->setQuery($query);
	    $data = $db->loadObjectList();
	    if(!$data) return null;

	    $js = "
	    function jSetSummaryTag(name) {
		    var tag = '['+name+'_summary]';
		    jInsertEditorText(tag, '".$name."');
		    SqueezeBox.close();
	    }";

	    $doc = JFactory::getDocument();
	    $doc->addScriptDeclaration($js);

		JHTML::_('behavior.modal');

		$link = 'index.php?option=com_testimonials&amp;view=ratings&amp;layout=modal&amp;tmpl=component';

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_TMRATING_BUTTON_TEXT'));
		$button->set('name', 'article');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");
		//if (JRequest::getVar('option')=='com_content')
		return $button; 
		//else return null;
	}
}
