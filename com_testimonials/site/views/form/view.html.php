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
	
	function renderLayout($layout, $data = null, $sublayout=''){
		if(!$sublayout) $sublayout = ($this->getLayout()=='default')?'':$this->getLayout();
		if(!$data) $data = (object)array('value'=>'');
		$field_layout = new JLayoutFile($layout);
		$field_layout->setComponent('com_testimonials');
		$html = $field_layout->sublayout($sublayout,$data);
		if(!$html) $html = $field_layout->render($data);				
		if(!$html) $html = $data->value;
		
		return $html;
	}
	
	public function display($tpl = null) {
		
		$this->renderLayout('testimonials.framework');
		
		$user		= JFactory::getUser();
		
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->tags			= $this->get('Tags');
		$this->custom_fields = $this->get('CustomFields');
        $this->helper       = new TestimonialsHelper();
		$this->params		= $params =  $this->helper->getParams();
		
		$Gparams = JFactory::getApplication()->getParams();
		
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
		}else{		
			$document = JFactory::getDocument();
			JHTML::stylesheet('components/com_testimonials/assets/submit-form/css/template_testimonials.css');
			JHtml::_('jquery.framework');
			$document->addScript('components/com_testimonials/assets/submit-form/js/main.js');
			if(($params->get('allow_photo') || $params->get('show_addimage')) && $params->get('show_avatar')){
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