<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomplace\Component\Testimonials\Admin;

defined('_JEXEC') or die;
define('DS',DIRECTORY_SEPARATOR);

jimport('controller',JPATH_ADMINISTRATOR.DS.'components/com_testimonials/');
jimport('framework.controller',JPATH_ADMINISTRATOR.DS.'components/com_testimonials/');
jimport('framework.model',JPATH_ADMINISTRATOR.DS.'components/com_testimonials/');
jimport('framework.view',JPATH_ADMINISTRATOR.DS.'components/com_testimonials/');

$component = new Controller();
$component->execute();

//Joomla\Component\Content\Admin\View\Article();

/*$controller = JController::getInstance('Users');
$controller->execute(JFactory::getApplication()->input->get('action'));
$controller->redirect();*/
