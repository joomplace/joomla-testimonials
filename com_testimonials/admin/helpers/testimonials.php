<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * HelloWorld component helper.
 */
$content = file_get_contents(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_testimonials' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'testimonials.php');
eval(str_replace('<?php', '',str_replace('TestimonialsHelper', 'TestimonialsHelperBase', $content)));

abstract class TestimonialsHelper
{
	public static function __callStatic($method, $arguments) {
        if(method_exists('TestimonialsHelperBase', $method)) {
            return TestimonialsHelperBase::$method($arguments);
        }
    }

    public function __call($method, $arguments) {
        if(method_exists('TestimonialsHelperBase', $method)) {
            return TestimonialsHelperBase::$method($arguments);
        }
    }
	
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TOPICS'),
		                         'index.php?option=com_testimonials&view=topics', $submenu == 'topics');
		JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_CATEGORIES'),
		                         'index.php?option=com_categories&extension=com_testimonials',
		                         $submenu == 'categories');
		JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS'),
		                         'index.php?option=com_testimonials&view=customs',
		                         $submenu == 'customs');
		JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TAGS'),
		                         'index.php?option=com_testimonials&view=tags',
		                         $submenu == 'tags');
		
		$document = JFactory::getDocument();
		
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_TESTIMONIALS_CATEGORIES'));
		}
	}
}