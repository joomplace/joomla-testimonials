<?php
// No direct access to this file
defined('_JEXEC') or die;

abstract class TestimonialsAdminHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu)
    {
        JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TOPICS'),
            'index.php?option=com_testimonials&view=topics', $submenu == 'topics');
        JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_CATEGORIES'),
            'index.php?option=com_categories&extension=com_testimonials', $submenu == 'categories');
        JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS'),
            'index.php?option=com_testimonials&view=customs', $submenu == 'customs');
        JHtmlSidebar::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TAGS'),
            'index.php?option=com_testimonials&view=tags', $submenu == 'tags');

        $document = JFactory::getDocument();
        if ($submenu == 'categories')
        {
            $document->setTitle(JText::_('COM_TESTIMONIALS_CATEGORIES'));
        }
    }
}