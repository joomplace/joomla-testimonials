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

//if ($_SERVER['REMOTE_ADDR'] == '172.68.11.174') {
//echo "<pre>";
//print_r($value);
//echo "</pre>";
//}
if(JHtml::_('thumbler.getthumb', $value)){
	echo JHtml::_('thumbler.renderthumb', $value);
}else{
	echo '';
}
