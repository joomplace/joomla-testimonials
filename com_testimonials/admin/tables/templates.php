<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');
 
/**
 * Templates Table class
 */
class TestimonialsTableTemplates extends JTable
{
        function __construct(&$db) 
        {
                parent::__construct('#__tm_testimonials_templates', 'id', $db);
        }
}