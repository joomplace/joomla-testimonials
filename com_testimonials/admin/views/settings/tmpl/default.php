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

$setting_form = $this->form->getFieldset('settings');
foreach($setting_form as $setting)
{
	$fieldname = $setting->fieldname;
	$value = $this->settings->get($fieldname);
	$this->form->setValue($fieldname,null,$value);	
}

$setting_form = $this->form->getFieldset('module');
foreach($setting_form as $setting)
{
	$fieldname = $setting->fieldname;
	$value = $this->settings->get($fieldname);
	$this->form->setValue($fieldname,null,$value);	
}

?>
<?php echo $this->loadTemplate('menu');?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'settings.cancel' || document.formvalidator.isValid(document.id('settings-form'))) {
			<?php //echo $this->form->getField('testimonial')->save(); 
			?>
			Joomla.submitform(task, document.getElementById('settings-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="settings-form" class="form-validate">
	<table class="admin" width="100%">
	<tr>
		<?php if (JFactory::getApplication()->input->get('tmpl')!='component') { ?>
			<td valign="top" class="lefmenutd">	<div> <?php echo $this->leftmenu;?>	</div> </td>
		<?php } else 
			{
		?>
				<td valign="top" class="fltrt">	
				<div class="width-10 fltrt">
					<input type="button" class="button" name="save" value="Save" onclick="javascript:Joomla.submitbutton('settings.apply')" />
					<input type="hidden" name="tmpl" value="component" />
				</div> 
				</td>
			</tr>
			<tr>
		<?php
			}?>
		<td valign="top" width="100%">
			<?php echo JHtml::_('tabs.start','topics-tabs', array('useCookie'=>1));?>
			<?php echo JHtml::_('tabs.panel',JText::_('COM_TESTIMONIALS_SETTINGS_TEMPLATES'), 'template-details');?>
				<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_TEMPL'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('show_title'); ?>
							<?php echo $this->form->getInput('show_title'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('texttitle'); ?>
							<?php echo $this->form->getInput('texttitle'); ?>
						</li>						
					</ul>
				</fieldset>
				<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_FIELDS'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('show_caption'); ?>
							<?php echo $this->form->getInput('show_caption'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_captcha'); ?>
							<?php echo $this->form->getInput('show_captcha'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_tags'); ?>
							<?php echo $this->form->getInput('show_tags'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_addtestifirst'); ?>
							<?php echo $this->form->getInput('show_addtestifirst'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_lasttofirst'); ?>
							<?php echo $this->form->getInput('show_lasttofirst'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('use_editor'); ?>
							<?php echo $this->form->getInput('use_editor'); ?>
						</li>
					</ul>
				</fieldset>
			<?php echo JHtml::_('tabs.panel',JText::_('COM_TESTIMONIALS_SETTINGS_USERINFO'), 'user-details');?>
				<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_IMAGE'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('show_avatar'); ?>
							<?php echo $this->form->getInput('show_avatar'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('use_cb'); ?>
							<?php echo $this->form->getInput('use_cb'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('use_jsoc'); ?>
							<?php echo $this->form->getInput('use_jsoc'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('thumb_width'); ?>
							<?php echo $this->form->getInput('thumb_width'); ?>
						</li>						
					</ul>
				</fieldset>
				<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_INFO'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('show_authorname'); ?>
							<?php echo $this->form->getInput('show_authorname'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_authordesc'); ?>
							<?php echo $this->form->getInput('show_authordesc'); ?>
						</li>
					</ul>
				</fieldset>
			<div class="clr"></div>
			<?php echo JHtml::_('tabs.panel',JText::_('COM_TESTIMONIALS_SETTINGS_MODULE'), 'module-details');?>
			<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_MODULE'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('timeout'); ?>
							<?php echo $this->form->getInput('timeout'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('symb_qty'); ?>
							<?php echo $this->form->getInput('symb_qty'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('th_width'); ?>
							<?php echo $this->form->getInput('th_width'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_author_module'); ?>
							<?php echo $this->form->getInput('show_author_module'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_avatar_module'); ?>
							<?php echo $this->form->getInput('show_avatar_module'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_readmore'); ?>
							<?php echo $this->form->getInput('show_readmore'); ?>
						</li>						
						<li>
							<?php echo $this->form->getLabel('show_add_new'); ?>
							<?php echo $this->form->getInput('show_add_new'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_first'); ?>
							<?php echo $this->form->getInput('show_first'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('tag_options'); ?>
							<?php echo $this->form->getInput('tag_options'); ?>
						</li>
						
					</ul>
				</fieldset>
				<?php
				$file_path = JPATH_SITE.'/modules/mod_testimonials/tmpl/style.css';
       				if (file_exists($file_path) && is_writable($file_path))
       				{
       			?>
       					<div class="button2-left">
							<div class="blank">
								<a href="index.php?option=com_testimonials&amp;task=settings.editmodcss&amp;tmpl=component" rel="{handler: 'iframe', size: {x: 500, y: 400}, onClose: function() {}}" class="modal"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CSS'); ?></a>
							</div>
						</div>
       		<?php	}
       		else
       		if (!is_writable($file_path))
       		{
       			?>
       			<font color="red">Module css file <?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_UNWRITEABLE'); ?></font>
       			<?php
       		}
       		 ?>
				
			<div class="clr"></div>
			<?php echo JHtml::_('tabs.panel',JText::_('COM_TESTIMONIALS_SETTINGS_PERMISSION'), 'permission-details');?>
			<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_PERMISSION'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('allow_photo'); ?>
							<?php echo $this->form->getInput('allow_photo'); ?>
						</li>
						<li>
							<?php echo $this->form->getLabel('show_tagsforusers'); ?>
							<?php echo $this->form->getInput('show_tagsforusers'); ?>
						</li>
					</ul>
				</fieldset>
			<fieldset class="settingfieldset">
					<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_APPR'); ?></legend>
					<ul class="adminformlist">
						<li>
							<?php echo $this->form->getLabel('autoapprove'); ?>
							<?php echo $this->form->getInput('autoapprove'); ?>
						</li>
					</ul>
				</fieldset>
				
				<fieldset class="settingfieldset">
				<legend><?php echo JText::_('COM_TESTIMONIALS_SETTINGS_COMPER'); ?></legend>
					<?php echo $this->form->getLabel('rules', 'params'); ?>
					<?php echo
					$this->form->getInput('rules', 'params');
					?>
				</fieldset>
				
			<div class="clr"></div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
			<div class="clr"></div>			
			<?php echo JHtml::_('tabs.end'); ?>
		</td>
  </tr>
</table>
</form>
