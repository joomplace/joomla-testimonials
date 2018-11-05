<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
 defined('_JEXEC') or die('Restricted access');

JLoader::register('TestimonialsModelTopic',
    JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'topic.php'
);

/**
 * HTML View class for the Testimonials Component
 */
class TestimonialsViewForm extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $return_page;
	protected $state;
	
	public function display($tpl = null) {
		$user		= JFactory::getUser();
		
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->tags			= $this->get('Tags');
		$this->custom_fields = $this->get('CustomFields');
        $this->helper       = new TestimonialsFEHelper();
        $this->modelTopic   = new TestimonialsModelTopic();
		$this->params		= $params =  $this->helper->getParams();
		
		$Gparams = JFactory::getApplication()->getParams();
		
		if($params->get('show_captcha') && !$params->get('show_recaptcha'))
		{
			TestimonialsFEHelper::enableCaptcha();
		}
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JFactory::getApplication()->enqueueMessage($this->get('Errors'), 'error');
			return false;
		}
		
		if (empty($this->item->id)) {
		    $authorised = ($user->authorise('core.create', 'com_testimonials'));
		}
		else {
			$authorised = $user->authorise('core.edit', 'com_testimonials');
		}
		
		if ($authorised !== true) {
			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$helper = new TestimonialsFEHelper();
			$params = $helper->getParams();
			if($params->get('modal_on_new')){
				?>
				<script type="text/javascript">
					setTimeout(function(){ parent.location.href=parent.location.href; }, 3000);
				</script>
				<?php
			}else{
				$Itemid=(int) JFactory::getApplication()->input->getInt('Itemid',0);
				JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_testimonials&view=testimonials&Itemid='.$Itemid));
			}
			
		}else{		
			$document = JFactory::getDocument();
			JHTML::stylesheet('components/com_testimonials/assets/submit-form/css/template_testimonials.css');
			JHtml::_('jquery.framework');
			$document->addScript('components/com_testimonials/assets/submit-form/js/main.js');
			if($params->get('allow_photo') || $params->get('show_addimage')){
				$document->addScript('components/com_testimonials/assets/file-upload/js/vendor/jquery.ui.widget.js');
				$document->addScript('components/com_testimonials/assets/file-upload/js/jquery.iframe-transport.js');
				$document->addScript('components/com_testimonials/assets/file-upload/js/jquery.fileupload.js');
				$document->addCustomTag('<!--[if gte IE 8]><script src="/components/com_testimonials/assets/file-upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->');
			}
			$document->addCustomTag('<!--[if lt IE 9]><script src="/components/com_testimonials/assets/html5.js"></script><![endif]-->');
			if($params->get('use_editor')){
				$document->addScript('components/com_testimonials/assets/wysihtml/advanced.js');
				$document->addScript('components/com_testimonials/assets/wysihtml/wysihtml5-0.3.0_rc2.js');
			}
			$document->addCustomTag('<meta http-equiv="X-UA-Compatible" content="IE=Edge" />');
			parent::display($tpl);
		}
	}
}
?>