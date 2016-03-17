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
$comments_count = 3;

$document = JFactory::getDocument();
// need to prettyfy
$current_folder = str_replace(array(JPATH_SITE.'/',JPATH_SITE.'\\','\\'),array('','','/'),dirname(__FILE__));
$file_name = basename(__FILE__, ".php");
if(file_exists(JPATH_SITE.'/'.$current_folder.'/'.$file_name.'.js')){
	$js_file = $current_folder.'/'.$file_name.'.js';
}else{
	$js_file = 'components/com_testimonials/layouts/testimonials/comments.js';
}
if(file_exists(JPATH_SITE.'/'.$current_folder.'/'.$file_name.'.css')){
	$css_file = $current_folder.'/'.$file_name.'.css';
}else{
	$css_file = 'components/com_testimonials/layouts/testimonials/comments.css';
}
$document->addScript($js_file);
$document->addStyleSheet($css_file);
$can_comment = 1;
$can_reply = 1;
?>
<div class="comment initial-comment">
	<div class="text-right">
		<p><span class="btn btn-xs btn-default toggle-comments">show comment(<?php echo $comments_count; ?>)</span> <?php if($can_comment){ ?><a class="btn btn-xs btn-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add comment</a> <?php } ?></p>
	</div>
</div>
<div class="collapse comments">
	<?php if($comments_count){ ?>
	<div class="comment">	
		<div>
			<p>This is boutgh review, don`t believe it everyone!</p>
			<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
		</div>
		<div class="comment">
			<div>
			<p>We never boutgh any reviews, so stay off</p>
			<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
			</div>
			<div class="comment">
				<div>
					<p>Ohh, how rude</p>
					<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
				</div>
			</div>
			<div class="comment">
				<div>
					<p>Yes? then how can any one be happy with your services?</p>
					<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
				</div>
			</div>
		</div>
		<div class="comment">
			<div>
				<p>Don`t say anything unless you can prove it...</p>
				<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
			</div>
			<div class="comment">
				<div>
					<p>Say it with your chest!</p>
					<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
				</div>
			</div>
		</div>
		<div class="comment">
			<div>
				<p>Well, I never get anything for this review.</p>
				<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
			</div>
		</div>
	</div>
	<div class="comment">
		<div>
			<p>I think so too!..</p>
			<?php if($can_reply){ ?><a class="label label-default abs-pos add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment=3'); ?>">Add reply</a> <?php } ?>
		</div>
	</div>
	<?php } ?>
</div>