<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$count = $displayData->count;
$value = $displayData->value;
$title = (strtoupper($displayData->system_name)!=JText::_(strtoupper($displayData->system_name)))?JText::_(strtoupper($displayData->system_name)):$displayData->name;
?>
<span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
	<span>
		<span class="tm_stars">
		<?php
			for($a=1;$a<6;$a++){
				$marked = (isset($value) && $value >= $a)?true:false;
		?>
			<i class="icon-star<?php echo ($marked ? '' : '-empty'); ?> fa fa-star<?php echo ($marked ? '' : '-o'); ?> ">&nbsp;&nbsp;</i>
		<?php
			}
		?>
		</span>
		(<span itemprop="ratingValue"><?php echo $value; ?></span>/<span itemprop="bestRating">5.0</span>)
        <meta itemprop="worstRating" content="0"/>
		by <span class="clients" itemprop="ratingCount"><?php echo $count ?></span> clients
	</span>
	<meta itemprop="itemreviewed" content="Services" />
</span>