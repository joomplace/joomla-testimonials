<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$params = TestimonialsHelper::getParams();
$document = JFactory::getDocument();

$document->addStyleSheet('components/com_testimonials/assets/css/testimonials.css');

if($params->get('bootstrap',1)){
	// include localized(wrapped) bootstrap
	$document->addStyleSheet('components/com_testimonials/assets/css/testimonials_bootstrap3.css');
}
if($params->get('fontawesome',1)){
	// include font-awesome
	$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
}
/* fixing joomla squeezebox scrolling page to top issue */
JFactory::getDocument()->addStyleDeclaration('
	div#sbox-window {
		top: 10%!important;
	}
');

$document->addScriptDeclaration("
	jQuery(document).ready(function($){
		$('.testimonials-list').on('click', '.add-reply', function(e){
			e.preventDefault();
			$(this).closest('.testimonials-list').find('.comment > form').parent().remove();
			var comment_block = $(this).closest('.comment');
			$.get($(this).attr('href'),{tmpl:\"component\"},function( data ) {
				$($(data).find('.comment').parent().html()).insertAfter(comment_block.find('>div:first-child'));
			});
			$(this).closest('.testimonials-list').find('.add-reply').removeClass('hidden');
			$(this).addClass('hidden');
			return false;
		});
	});
");