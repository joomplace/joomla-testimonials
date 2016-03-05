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

?>
<img alt="" src="<?php echo JHtml::_('thumbler.getthumb', $value, array('sizing'=>'filled','square'=>true,'width'=>70,'height'=>70)); ?>" style="border-radius: 200px; box-shadow: 1px 2px 3px;">