<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');
 
/**
 * Settings Controller
 */
class TestimonialsControllerSettings extends JControllerForm
{
    function editmodcss()
	{jimport('joomla.filesystem.file');
		
       	$file_path = JPATH_SITE.'/modules/mod_testimonials/tmpl/style.css';
       	if (file_exists($file_path))
				if (is_writeable($file_path)) 
				{
					$editor = & JFactory::getEditor('codemirror');
					$params = array( 'smilies'=> '0' ,
					                 'style'  => '0' ,  
					                 'layer'  => '0' , 
					                 'table'  => '0' ,
					                 'buttons'  => 'no' ,
					                 'class'=>'template-editor',
					                 'clear_entities'=>'0',
					                ' editor=>'=>'codemirror',
					                'filter'=>'raw'
					                );
					ob_start();
					?>	Joomla.submitbutton = function(task)
						{
								<?php echo $editor->save( 'Array' ) ; ?>
								Joomla.submitform(task, document.getElementById('adminForm'));							
						}
					<?php
						$js = ob_get_contents();
						ob_get_clean();
						$document =& JFactory::getDocument();
						$document->addScriptDeclaration($js);
					?>
					<form action="<?php echo JRoute::_('index.php?option=com_testimonials&task=settings.editmodcss'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
					<?php
					echo '<div>'.JText::_('COM_TESTIMONIALS_TEMPLATE_CSS').': '.$file_path.'</div>';
					echo $editor->display( 'css', JFile::read($file_path), '450', '350', '60', '20', false, $params ) ;
					?>
					<div>
						<input type="button" class="button" name="save" value="<?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_SAVECSS'); ?>" onclick="javascript:Joomla.submitbutton('settings.csssave');" />
						<input type="hidden" name="task" value="" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
					</form>
					<?php 
				}
				else echo 'unwriteable file';
	}      

	function csssave()
	{
		jimport('joomla.filesystem.file');
		
		$file_path = JPATH_SITE.'/modules/mod_testimonials/tmpl/style.css';
       		if (is_writeable($file_path)) 
				{
					$content = JFactory::getApplication()->input->getVar('css');
					if (JFile::write($file_path,$content))
						{
							$this->setMessage(JText::_('COM_TESTIMONIALS_TEMPLATE_SAVESUCCESS'));
						}
						else {
							$this->setMessage('Failed to open file for writing!');
						}
					
				}
		else {$this->setMessage(JText::_('COM_TESTIMONIALS_TEMPLATE_UNWRITEABLE'));}
	$this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false));
	}      
}
