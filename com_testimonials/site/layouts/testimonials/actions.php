<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$user = JFactory::getUser();
$user->authorise('core.'.$action, 'com_testimonials');

if($user->authorise('core.publish', 'com_testimonials') || $user->authorise('core.publish', 'com_testimonials') || $user->authorise('core.edit', 'com_testimonials') || $user->authorise('core.delete', 'com_testimonials')){
?>
<div class="testim-manage-block-wrap">
	<div class="testim-manage-block">
		<?php if($user->authorise('core.moderate', 'com_testimonials')){ ?>
			<a class="btn btn-xs btn-default" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.approve&id='.$displayData->id); ?>" title="<?php echo ($displayData->is_approved? JText::_('COM_TESTIMONIALS_UNAPPROVE'):JText::_('COM_TESTIMONIALS_APPROVE')); ?>" >
				<i class="fa fa-<?php echo $displayData->is_approved?'times':'check-square-o'; ?>" alt="<?php echo ($displayData->is_approved?JText::_('COM_TESTIMONIALS_UNAPPROVE'):JText::_('COM_TESTIMONIALS_APPROVE')); ?>" ></i>
			</a>
		<?php } ?>
		
		<?php if($user->authorise('core.publish', 'com_testimonials')){ ?>
			<a class="btn btn-xs btn-default" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.state&id='.$displayData->id); ?>" title="<?php echo ($displayData->published? JText::_('COM_TESTIMONIALS_UNPUBLISH'):JText::_('COM_TESTIMONIALS_PUBLISH')); ?>" >
				<i class="fa fa-<?php echo $displayData->published?'minus-circle':'check-circle'; ?>" alt="<?php echo ($displayData->published?JText::_('COM_TESTIMONIALS_UNPUBLISH'):JText::_('COM_TESTIMONIALS_PUBLISH')); ?>" ></i>
			</a>
		<?php } ?>

		<?php

		$params = TestimonialsHelper::getParams();
		$tmpl = $params->get('modal_on_new')?'&tmpl=component':'';
		if($params->get('modal_on_new')) JHtml::_('behavior.modal', 'a.modal_com_testim');
		
		if ($user->authorise('core.create', 'com_testimonials')){ ?>
			<a class="modal_com_testim btn btn-xs btn-default"  href="<?php echo JRoute::_('index.php?option=com_testimonials&catid='.$displayData->catid.'&view=form'.$tmpl).'&id='.$displayData->id; ?>" rel="{handler:'iframe',size:{x: (0.8*((jQuery('main').width())?jQuery('main').width():jQuery('.container').width())), y: (0.8*jQuery(window).height())}}" title="<?php echo JText::_('COM_TESTIMONIALS_EDIT'); ?>">
				<i class="icon-pencil-2 fa fa-pencil" alt="<?php echo JText::_('COM_TESTIMONIALS_EDIT'); ?>"></i>
			</a>
		<?php } ?>
		
		<?php if ($user->authorise('core.delete', 'com_testimonials')){ ?>
		<a class="btn btn-xs btn-danger" href="javascript:void(0)" onclick="javascript:if (confirm('<?php echo JText::_('COM_TESTIMONIALS_CONFIRM_DELETE'); ?>')){ document.location.href='<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.delete&id='.$displayData->id); ?>';}else return;" title="<?php echo JText::_('COM_TESTIMONIALS_DELETE'); ?>" >
			<i class="icon-trash fa fa-trash-o"></i>
		</a>
		<?php } ?>
		
		<?php if ($user->authorise('core.comment', 'com_testimonials') && 0) {	?>
		<a class="btn btn-xs btn-info" href="javascript:void(0)" onclick="jQuery('#add_comment<?php echo $displayData->id;?>').slideToggle();" title="<?php echo JText::_('COM_TESTIMONIALS_CAN_COMMENT');?>" >
			<i class="icon-reply fa fa-reply" alt="<?php echo JText::_('COM_TESTIMONIALS_CAN_COMMENT'); ?>"></i>
		</a>
		<?php }	?>
	</div>
</div>
<?php
}
