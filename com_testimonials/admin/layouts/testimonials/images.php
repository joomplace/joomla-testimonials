<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 
defined('_JEXEC') or die;
foreach(explode('|',trim($displayData->value,' ')) as $value){
	if($value){
		$value = JUri::root(true).'/images/testimonials/'.$value;
		JHTML::_( 'behavior.modal','a.modal_testimonials_images' );
	?>
		<div class="span4 col-xs-4 text-center">
			<a href="<?php echo $value; ?>" class="thumbnail modal_testimonials_images"><?php echo JHtml::_('thumbler.renderthumb', $value); ?></a>
		</div>
	<?php
	}
}