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
 * Template Controller
 */
class TestimonialsControllerTemplate extends JControllerForm
{
    function apply_template(){
        $db = JFactory::getDbo();
        $tmplid  = $this->input->post->get('cid', array(), 'array');
        
	$model = $this->getModel('Template');
        $template = $model->getItem($tmplid[0]);
        $template_name = $template->temp_name;
        
        $params = JComponentHelper::getParams('com_testimonials');
        $params->set('template', $template_name);
        $params_str = $params->toString();
        $db->setQuery('UPDATE #__extensions SET `params`=\''.$params_str.'\' WHERE `name`="com_testimonials"');
        $db->execute();
        $this->setRedirect(JRoute::_($_SERVER['HTTP_REFERER'], false));
    }   
    
    function preview()
       {
       	 $document = JFactory::getDocument();

       		$tmplid = JFactory::getApplication()->input->getInt( 'id'); if (!$tmplid) exit();

	       		$model = $this->getModel('Template');
	       		$template = $model->getItem($tmplid);
	       		$templateTable = JTable::getInstance('Templates', 'TestimonialsTable');
	       		$tmpl = $template->temp_name?$template->temp_name:'default';
				$body = $template->html;

			$document->addStyleSheet(JURI::root(). '/components/com_testimonials/templates/'.$tmpl.'/css/style.css');

			$avatar = JURI::root()."/components/com_testimonials/templates/".$tmpl."/images/tnnophoto.jpg";

			$replace_fields = $new_fields = array();
			$replace_fields [] = '[caption]';
			$replace_fields [] = '[author]';
			$replace_fields [] = '[author_descr]';
			$replace_fields [] = '[testimonial]';
			$replace_fields [] = '[avatar]';
			$replace_fields [] = '[rating]';
			$replace_fields [] = '[date]';
			$replace_fields [] = '[imagelist]';

			$new_fields		[] = JText::_('COM_TESTIMONIALS_TEMPLATE_SAMPLE_CAPTION');
			$new_fields		[] = JText::_('COM_TESTIMONIALS_TEMPLATE_SAMPLE_AUTHOR');
			$new_fields		[] = JText::_('COM_TESTIMONIALS_TEMPLATE_SAMPLE_DESCRIPTION');
			$new_fields		[] = JText::_('COM_TESTIMONIALS_TEMPLATE_SAMPLE_TESTIMONIAL');
			$new_fields		[] = '<img class="avatar" src="'.$avatar.'" alt=""/>';

			$body = str_replace($replace_fields, $new_fields, $body);
			echo '<div id="testimonials-list">';
			echo '<a name="anc_1"></a><div class="testimonial avatar_on">'.$body.'</div>';
			echo '<a name="anc_2"></a><div class="testimonial avatar_on odd">'.$body.'</div>';
			echo '</div>';
       }

	function editcss()
	{jimport('joomla.filesystem.file');
	    JHtmlBehavior::framework();
		$tmplid = JFactory::getApplication()->input->getInt( 'id'); if (!$tmplid) exit();

	       		$model = $this->getModel('Template');
	       		$template = $model->getItem($tmplid);
	       		$template_name=$template->temp_name?$template->temp_name:'default';
	   			$file_path = JPATH_COMPONENT_SITE.'/templates/'.$template_name.'/css/style.css';
				if (is_writeable($file_path))
				{
					$editor = JFactory::getEditor('codemirror');
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
						$document = JFactory::getDocument();
						$document->addScriptDeclaration($js);
					?>
					<form action="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.editcss&id='.$template->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
					<?php
					echo '<div>'.JText::_('COM_TESTIMONIALS_TEMPLATE_CSS').': '.$file_path.'</div><br />';
					echo $editor->display( 'css', JFile::read($file_path), '100%', '100%', '60', '20', false, $params ) ;
					?>
					<div>
					    <br />
						<input type="button" class="btn button" name="save" value="<?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_SAVECSS'); ?>" onclick="javascript:Joomla.submitbutton('template.csssave');" />
						<input type="hidden" name="template" value="<?php echo $template_name;?>" />
						<input type="hidden" name="task" value="" />
						<?php echo JHtml::_('form.token'); ?>
					</div></div>
					</form>
					<?php
				}
	}



	function csssave()
	{
		jimport('joomla.filesystem.file');
		$template_name = JFactory::getApplication()->input->getVar('template');
		$file_path = JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$template_name.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'style.css';
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
