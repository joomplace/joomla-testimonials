<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
 defined('_JEXEC') or die('Restricted access');

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
        $this->helper       = new TestimonialsHelper();
		$this->params		= $params =  $this->helper->getParams();
		
		$Gparams = JFactory::getApplication()->getParams();
		
		if($params->get('show_captcha') && !$params->get('show_recaptcha'))
		{
			TestimonialsHelper::enableCaptcha();
		}
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if (empty($this->item->id)) {
		    $authorised = ($user->authorise('core.create', 'com_testimonials'));
		}
		else {
			$authorised = $user->authorise('core.edit', 'com_testimonials');
		}
		
		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		
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
?>