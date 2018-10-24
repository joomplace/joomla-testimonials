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

$url = explode("|", $value);
if(!empty($url[0])){
	if(!preg_match('|^https?://|i', $url[0])) $url[0] = 'http://'.$url[0];
}
$value = '<a href="'.(isset($url[0])?$url[0]:(isset($url[1])?$url[1]:'java:void(0)')).'" name="testimonial_link" target="_blank">'.(isset($url[1])?$url[1]:(isset($url[0])?$url[0]:'')).'</a>'.'&nbsp;';

echo JHTML::_( 'content.prepare', $value );
