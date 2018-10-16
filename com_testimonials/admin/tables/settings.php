<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');
 
/**
 * Settings Table class
 */
class TestimonialsTableSettings extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
        	parent::__construct('#__tm_testimonials_settings', 'id', $db);
        	//jimport('joomla.access.rules');
           $this->_trackAssets = true;
        }
        
        function store($updateNulls = false)
        {

        	$data = JFactory::getApplication()->input->getVar('jform');
			
        	  $params = new JRegistry();
        	  //$params->def('template', $data['template']);
        	  $params->def('show_title', $data['show_title']);
        	  $params->def('texttitle', $data['texttitle']);
        	  $params->def('show_caption', $data['show_caption']);
        	  $params->def('show_captcha', $data['show_captcha']);
        	  $params->def('show_lasttofirst', $data['show_lasttofirst']);
        	  $params->def('use_editor', $data['use_editor']);
        	  $params->def('show_avatar', $data['show_avatar']);
        	  $params->def('use_cb', $data['use_cb']);
        	  $params->def('use_jsoc', $data['use_jsoc']);
        	  $params->def('thumb_width', $data['thumb_width']);
        	  $params->def('show_authorname', $data['show_authorname']);
        	  $params->def('show_authordesc', $data['show_authordesc']);
        	  $params->def('addingbyuser', $data['addingbyuser']);
        	  $params->def('addingbyunreg', $data['addingbyunreg']);
        	  $params->def('allow_photo', $data['allow_photo']);
        	  $params->def('show_tagsforusers', $data['show_tagsforusers']);
        	  $params->def('autoapprove', $data['autoapprove']);
        	  $params->def('timeout', $data['timeout']);
        	  $params->def('symb_qty', $data['symb_qty']);
        	  $params->def('th_width', $data['th_width']);
        	  $params->def('show_avatar_module', $data['show_avatar_module']);
        	  $params->def('show_readmore', $data['show_readmore']);
        	  $params->def('show_add_new', $data['show_add_new']);
        	  $params->def('show_first', $data['show_first']);
        	  $params->def('show_author_module', $data['show_author_module']);
        	  $params->def('tag_options', $data['tag_options']);
        	  $params->def('show_tags', $data['show_tags']);
			  $params->def('show_addimage', $data['show_addimage']);
 			  $saveparams = (string)$params;
 			  
 			  $this->params = $saveparams;
 			  $this->_db->setQuery('SELECT `id` FROM #__tm_testimonials_settings');
 			  $id = $this->_db->loadResult();
 			  $id?$this->id=$id:'';
 			  
 			  if (isset($data['params']['rules'])) {
					$rules	= new JAccessRules($data['params']['rules']);
					$this->setRules($rules);
					unset ($data['params']['rules']);
			}
 			  
 			  return parent::store();
        }
        
    protected function _getAssetName()
	{
		return 'com_testimonials';
	}
	
	protected function _getAssetTitle()
	{
		return 'testimonials';
	}

	protected function _getAssetParentId()
	{
		//$asset = JTable::getInstance('Asset');
		//$asset->loadByName('com_testimonials');
		return 1;
	}
        
}