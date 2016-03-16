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
// index.php?option=com_testimonials&view=comment&testimonial=$value->id&comment=3
?>
<div class="comments">
	<div class="comment">	
		<div>
			<p>This is boutgh review, don`t believe it everyone!</p>
			<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
		</div>
		<div class="comment">
			<div>
			<p>We never boutgh any reviews, so stay off</p>
			<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
			</div>
			<div class="comment">
				<div>
					<p>Ohh, how rude</p>
					<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
				</div>
			</div>
			<div class="comment">
				<div>
					<p>Yes? then how can any one be happy with your services?</p>
					<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
				</div>
			</div>
		</div>
		<div class="comment">
			<div>
				<p>Don`t say anything unless you can prove it...</p>
				<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
			</div>
			<div class="comment">
				<div>
					<p>Say it with your chest!</p>
					<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
				</div>
			</div>
		</div>
		<div class="comment">
			<div>
				<p>Well, I never get anything for this review.</p>
				<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
			</div>
		</div>
	</div>
	<div class="comment">
		<div>
			<p>I think so too!..</p>
			<a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a>
		</div>
	</div>
</div>