<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 
defined('_JEXEC') or die;

$value = $displayData->value;

echo JHtml::_('date', $value, $this->params->get('date_format',JText::_('DATE_FORMAT_LC1')) );
