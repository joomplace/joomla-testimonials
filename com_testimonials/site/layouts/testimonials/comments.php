<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

$comments_count = count($displayData->comments);

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

$user = JFactory::getUser();
$can_comment = $user->authorise('core.comment', 'com_testimonials');
$can_reply = $user->authorise('core.reply', 'com_testimonials');
$can_delete_commentnreply = $user->authorise('core.deletecomments', 'com_testimonials');

if(!isset($GLOBALS['testimonials']) || !$GLOBALS['testimonials']['comments']['template_default']){
/* need to add html tempalte to JS */
/* and do it only once */
ob_start();
?>
	<div>
		<p>{{text}}</p>
		<p class="text-right"><small><?php echo ($user->id)?$user->name.' on ':''; ?><?php echo JHtml::_('date'); ?></small></p>
		<div class="label-bar abs-pos">
			<?php if(1 /* as I can quickly delete mine */){ ?><a class="label label-warning delete-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials'); ?>" data-id="{{comment}}">Delete</a> <?php } ?>
			<?php if($can_reply){ ?><a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial={{testimonial}}&comment={{comment}}'); ?>">Add reply</a> <?php } ?>
		</div>
	</div>
<?php
$js_templ = ob_get_contents();
ob_end_clean();
$document->addScriptDeclaration('var template_default = "'.addcslashes(str_replace(array("\r","\n"),'',$js_templ),'"').'";');
$GLOBALS['testimonials']['comments']['template_default'] = 1;
}

$document->addScript($js_file);
$document->addStyleSheet($css_file);
?>
<div class="comment initial-comment">
	<div class="text-right">
		<p><span class="btn btn-xs btn-default hide-comments toggle-comments hidden">hide comment</span> <span class="btn btn-xs btn-default toggle-comments show-comments <?php if(!$comments_count){ ?>hidden<?php } ?>">show comments (<span class="count"><?php echo $comments_count; ?></span>)</span> <?php if($can_comment){ ?><a class="btn btn-xs btn-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id); ?>">Add comment</a> <?php } ?></p>
	</div>
</div>
<div class="collapse comments">
	<?php if($comments_count){ ?>
		<?php $lvl = 0; ?>
		<?php foreach($displayData->comments as $comment){ ?>
			<?php if($comment->level <= $lvl){ ?>
				<?php for($i=0;$i <= $lvl - $comment->level; $i++){ ?>
				</div>
				<?php } ?>
			<?php } ?>
			<?php $lvl = $comment->level; ?>
			<div class="comment">
				<div>
					<p><?php echo nl2br($comment->text); ?></p>
					<p class="text-right"><small><?php echo ($comment->user)?JFactory::getUser($comment->user)->name.' on ':''; ?><?php echo JHtml::_('date',strtotime($comment->date)); ?></small></p>
					<div class="label-bar abs-pos">
						<?php if($can_delete_commentnreply || ($user->id == $comment->user && $user->id)){ ?><a class="label label-warning delete-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials'); ?>" data-id="<?php echo $comment->id; ?>">Delete</a> <?php } ?>
						<?php if($can_reply){ ?><a class="label label-default add-reply" href="<?php echo JRoute::_('index.php?option=com_testimonials&view=comment&testimonial='.$displayData->id.'&comment='.$comment->id); ?>">Add reply</a> <?php } ?>
					</div>
				</div>
		<?php } ?>
		<?php if(1 <= $lvl){ ?>
			<?php for($i=0;$i <= $lvl - 1; $i++){ ?>
			</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>