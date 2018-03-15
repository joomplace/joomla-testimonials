<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
$form = $this->form;
$user = JFactory::getUser();
?>
<div id="comment_form_wrap">
	<div class="comment">
		<form action="<?php echo JRoute::_('index.php?option=com_testimonials&task=comment.save'); ?>">
			<?php echo $form->getInput('id'); ?>
			<?php echo $form->getInput('testimonial'); ?>
			<?php echo $form->getInput('user'); ?>
			<div class="form-group">
				<?php echo $form->getInput('text'); ?>
				<?php echo $form->getInput('comment'); ?>
			</div>
			<div class="form-group">
				<div class="text-right">
					<button type="submit" class="btn btn-xs btn-primary">Submit reply as <?php echo ($user->id)?$user->name:'Annonymous'; ?></button>
				</div>
			</div>
		</form>
	</div>
</div>