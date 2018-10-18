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
<span class="tm_stars">
<?php
	for($a=1;$a<6;$a++){
		$marked = (isset($value) && $value >= $a)?true:false;
?>
	<i style="color:orange!important;" class="icon-star<?php echo ($marked ? '' : '-empty'); ?>">&nbsp;&nbsp;</i>
<?php
	}
?>
</span><br />
