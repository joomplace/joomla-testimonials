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

$value = explode(',',$value);
if($value)
	foreach($value as &$tg){
		if($tg){
			list($id,$text) = explode("::",$tg);
			$tg = '<a href="'.JRoute::_('index.php?option=com_testimonials&tag='.$id.'&Itemid='.TestimonialsHelper::getClosesItemId('index.php?option=com_testimonials&tag='.$id)).'" class="label label-info">'.$text.'</a>';
		}
	}
	
$value = implode(' ',$value);

echo $value;
