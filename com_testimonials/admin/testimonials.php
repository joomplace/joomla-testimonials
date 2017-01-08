<?php
/**
 * Testimonials Component for Joomla 3
 *
 * @package   Testimonials
 * @author    JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

JLoader::register('TestimonialsHelper',
    JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers'
    . DIRECTORY_SEPARATOR . 'testimonials.php'
);
JLoader::register('TestimonialHelper',
    JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers'
    . DIRECTORY_SEPARATOR . 'testimonial.php'
);
JLoader::register('TimgHelper',
    JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers'
    . DIRECTORY_SEPARATOR . 'Timg.php'
);

$controller = JControllerLegacy::getInstance('Testimonials');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();