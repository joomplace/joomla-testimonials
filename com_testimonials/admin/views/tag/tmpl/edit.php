<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<?php echo $this->loadTemplate('menu');?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'tag.cancel' || document.formvalidator.isValid(document.id('topic-form'))) {
			Joomla.submitform(task, document.getElementById('topic-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="topic-form" class="form-validate">
    
    <div id="j-sidebar-container" class="span2">
	 <?php echo $this->leftmenu;?>
    </div>
    <div id="j-main-container" class="span8 form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo empty($this->item->id) ? JText::_('COM_TESTIMONIALS_NEW_TAG') : JText::sprintf('COM_TESTIMONIALS_EDIT_TAG', $this->item->id); ?></legend>
		<div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('tag_name'); ?>
            </div>
			<div class="controls">
				<?php echo $this->form->getInput('tag_name'); ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TESTIMONIALS_TAG_ASSINGS');?></legend>
			<table class="adminlist" border="0">
				<thead>
					<th>Menu</th>
					<th>Categories</th>
					<th>Articles</th>
				<thead>
				<tr>
					<td valign="top" align="center"><?php echo $this->form->getInput('menu');?> </td>
					<td valign="top" align="center"><?php echo $this->form->getInput('categories');?></td>
					<td valign="top" align="center"><?php echo $this->form->getInput('articles');?></td>
				</tr>
			</table>
	</fieldset>
    </div>
    <div id="j-right-sidebar-container" class="span2">
	<fieldset class="panelform">
	    <legend><?php echo JText::_('COM_TESTIMONIALS_PUBLIC_DETAILS'); ?></legend>
		<?php echo $this->form->getLabel('id'); ?>
		<?php echo $this->form->getInput('id'); ?>

		<?php echo $this->form->getLabel('published'); ?>
		<?php echo $this->form->getInput('published'); ?>

	</fieldset>
    </div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
