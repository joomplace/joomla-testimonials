<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
 
/**
 * Topics HTML View class for the Testimonials Component
 */
 
class TestimonialsViewTags extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
        function display($tpl = null) 
        {
            if($this->getLayout()=='button'){
                $controller = TestimonialsController::getInstance('');
                $comments_model = $controller->getModel('Editor_button');
                // Get the Data
                $this->form = $comments_model->getForm();
                //$this->item = $comments_model->get('Item');
            }else{
                $this->addTemplatePath(JPATH_BASE.'/components/com_testimonials/helpers/html');
                $submenu = 'tags';
                 TestimonialHelper::showTitle($submenu);
                 TestimonialHelper::showSubmenu($submenu);
                 TestimonialHelper::getCSSJS();
                 $this->addToolBar();

                $items 		= $this->get('Items');
                $pagination = $this->get('Pagination');
                $state		= $this->get('State');

                    if (count($errors = $this->get('Errors')))
                    {
                            JError::raiseError(500, implode('<br />', $errors));
                            return false;
                    }


                  $this->items = $items;
                  $this->pagination = $pagination;
                  $this->state = $state;


                TestimonialsAdminHelper::addSubmenu('tags');
                $this->sidebar = JHtmlSidebar::render();
            }
			
            parent::display($tpl);
        }
       
        /*  
        * Setting the toolbar
         */
        protected function addToolBar() 
        {
        	JToolBarHelper::addNew('tag.add');
        	JToolBarHelper::editList('tag.edit');
        	JToolBarHelper::divider();
			JToolBarHelper::custom('tags.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('tags.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
        	JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'tags.delete');
        }
}