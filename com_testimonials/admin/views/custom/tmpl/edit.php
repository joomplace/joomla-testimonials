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
jimport('joomla.filesystem.file');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'custom.cancel' || document.formvalidator.isValid(document.id('custom-form'))) {
			Joomla.submitform(task, document.getElementById('custom-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="custom-form" class="form-validate">
    
    <div id="j-sidebar-container" class="span2">
	 <?php echo $this->leftmenu;?>
    </div>
    <div id="j-main-container" class="span8 form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo empty($this->item->id) ? JText::_('COM_TESTIMONIALS_NEW_CUSTOM') : JText::sprintf('COM_TESTIMONIALS_EDIT_CUSTOM', $this->item->id); ?></legend>
		<div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('name'); ?>
            </div>
			<div class="controls">
				<?php echo $this->form->getInput('name'); ?>
			</div>
		</div>
		<div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('required'); ?>
			</div>
            <div class="controls">
				<?php echo $this->form->getInput('required'); ?>
			</div>
		</div>
		<div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('type'); ?>
            </div>
			<div class="controls">
				<?php echo $this->form->getInput('type'); ?>
			</div>
		</div>
		<div class="control-group">
            <div class="control-label">
			    <?php echo $this->form->getLabel('descr'); ?>
            </div>
			<div class="controls">
				<?php echo $this->form->getInput('descr'); ?>
			</div>
		</div>
	</fieldset>
    </div>
    <div id="j-right-sidebar-container" class="span2">
	<fieldset class="panelform">
	    <legend><?php echo JText::_('COM_TESTIMONIALS_PUBLIC_DETAILS'); ?></legend>
		<?php echo $this->form->getLabel('id'); ?>
		<?php echo $this->form->getInput('id'); ?>

		<?php echo $this->form->getLabel('published'); ?>
		<?php echo $this->form->getInput('published'); ?>

		<?php echo $this->form->getLabel('ordering'); ?>
		<?php echo $this->form->getInput('ordering'); ?>
	</fieldset>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
