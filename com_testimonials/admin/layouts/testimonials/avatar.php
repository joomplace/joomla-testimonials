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

if($value){
?>
<?php echo JHtml::_('thumbler.renderthumb', $value); ?>
<?php
}