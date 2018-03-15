<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class TestimonialsViewDashboard extends JViewLegacy
{

    public $messageTrigger = false;

	function display($tpl = null) 
	{
		$submenu = 'about';
        	TestimonialHelper::showTitle($submenu);
        	$this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');
		$this->dashboardItems = $this->get('Items');
        	$document = JFactory::getDocument();
        	$document->addScript(JURI::root(true).'/administrator/components/com_testimonials/assets/js/js.js');
        	$this->version = TestimonialHelper::getVersion();
		$this->addToolbar();
                $this->setDocument();
                $this->messageTrigger = $this->get('CurrDate');
        	
                parent::display($tpl);
	}

	protected function addToolBar() 
	{
		JToolBarHelper::title(JText::_('COM_TESTIMONIALS').': '.JText::_('COM_TESTIMONIALS_MANAGER_DASHBOARD'), 'dashboard');
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_TESTIMONIALS').': '.JText::_('COM_TESTIMONIALS_MANAGER_DASHBOARD'));
		//$document->addScript('administrator/components/com_testimonials/assets/js/js.js');
	}




}