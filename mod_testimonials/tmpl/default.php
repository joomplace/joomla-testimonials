<?php
/**
 * Testimonials Module for Joomla 1.6
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addStyleSheet(JUri::root(true).'/modules/mod_testimonials/css/swiper.min.css');
JFactory::getDocument()->addStyleDeclaration('
	.swiper-container'.str_replace(' ','.',str_replace('  ',' ',$moduleclass_sfx)).' {
		width: 100%;
		height: 100%;
		margin-left: auto;
		margin-right: auto;
		padding: 10px 0px 20px;
	}
');
JFactory::getDocument()->addScript(JUri::root(true).'/modules/mod_testimonials/js/swiper.min.js');
?>
<div id="testom_module_<?php echo $module->id; ?>" class="swiper-container swiper-container<?php echo $moduleclass_sfx ?>">
	<div class="swiper-wrapper">
		<?php foreach ($list as $key => $value) { ?>
			<div class="swiper-slide"><?php  require(JModuleHelper::getLayoutPath('mod_testimonials', $params->get('layout', 'default_body'))); ?></div>
		<?php } ?>
	</div>
	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
</div>
<script>
var swiper = new Swiper('#testom_module_<?php echo $module->id; ?>.swiper-container<?php echo str_replace(' ','.',str_replace('  ',' ',$moduleclass_sfx)); ?>', {
	pagination: '.swiper-pagination',
	paginationClickable: true,
	loop: true,
	keyboardControl: true,
	autoplay: <?php echo $params->get('timeout', 5)*1000; ?>,
	autoplayDisableOnInteraction: false
});
</script>