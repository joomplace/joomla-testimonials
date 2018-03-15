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
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
jimport('joomla.filesystem.file');

$template_name = $this->item->temp_name;
$custom_fields = $this->custom_fields;
?>
<?php echo $this->loadTemplate('menu');?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'template.cancel' || document.formvalidator.isValid(document.id('template-form'))) {
			<?php echo $this->form->getField('html')->save(); ?>
			Joomla.submitform(task, document.getElementById('template-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	function insertTag(field){
			var str = field.text;
			jInsertEditorText('['+str.toLowerCase()+']', 'jform_html');
			return true;
		}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="template-form" class="form-validate">
    
    <div id="j-sidebar-container" class="span2">
	 <?php echo $this->leftmenu;?>
    </div>
    <div id="j-main-container" class="span10 form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_INFO');?> </legend>
		<table cellspacing="1" class="admintable">
				<tbody>
					<tr>
						<td width="300" valign="top" class="key"><?php echo JText::_('COM_TESTIMONIALS_NAME');?></td>
						<td valign="top"><?php echo $template_name; ?></td>
					</tr>
					<tr>
						<td width="300" valign="top" class="key"><?php echo JText::_('COM_TESTIMONIALS_DESC');?></td>
						<td valign="top"><?php echo ucfirst($this->item->temp_name).' '.JText::_('COM_TESTIMONIALS_TEMPLATE_END_DESCR'); ?></td>
					</tr>
					<tr>
						<td width="300" valign="top" class="key"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CSS_LABEL');?></td>
						<td valign="top">
									<?php
										$file_path = JPATH_COMPONENT_SITE.'/templates/'.$template_name.'/css/style.css';
										if (is_writeable($file_path)) {
											?>
											<a class="btn modal" rel="{handler: 'iframe', size: {x: 875, y: 300}, iframeOptions: {scrolling: 'no'}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.editcss&id='.$this->item->id.'&tmpl=component');?>"><i class="icon-edit"></i> <?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CSS'); ?></a>
									<?php
										}else {
											?>
											<font color="red"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_UNWRITEABLE'); ?></font>
									<?php
									}
									?>
						</td>
					</tr>
					<tr>
						<td width="300" valign="top" class="key"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_PREVIEW');?></td>
							<td valign="top">
								<a class="btn modal" rel="{handler: 'iframe', size: {x: 875, y: 450}, iframeOptions: {scrolling: 'no'}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.preview&id='.$this->item->id.'&tmpl=component');?>"><i class="icon-eye-open"></i> <?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_PREVIEW'); ?></a>
						    </td>
					</tr>
				</tbody>
				</table>
	</fieldset>
	<fieldset class="adminform">
				<legend><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS');?> </legend>
				<div><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CUSTOMF');?></div>
				<?php
				 foreach ($custom_fields as $custom_field)
				 {?>
					<a class="btn" rel="" onClick="insertTag(this);" id="<?php echo 'field_'.$custom_field->id;?>"><?php echo $custom_field->name;?></a> 
				<?php }?>
					<a class="btn" id="field_5" onclick="insertTag(this);" rel="">Date</a>
					<a class="btn" id="field_6" onclick="insertTag(this);" rel="">Imagelist</a>
		</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_SOURCE');  ?></legend>
			<?php echo $this->form->getInput('html'); ?>
	</fieldset>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
