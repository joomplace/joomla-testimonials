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
 * Testimonials Table class
 */
class TestimonialsTableTags extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__tm_testimonials_tags', 'id', $db);
        }
        
        public function store($updateNulls = false)
		{
			if ($this->id)
			{
			    $table = clone $this;
			    if ($table->load(array('tag_name' => $this->tag_name)) && $table->id != $this->id){
				$this->setError(JText::_('COM_TESTIMONIALS_ERROR_UNIQUE_TAG_NAME'));
				return false;
			    }
        	if (isset($_POST['jform']['menu']))
			{
				$menus = $_POST['jform']['menu'];
				if (is_array($menus))
				{
					if(sizeof($menus)>0)
					{
						$assignTable = JTable::getInstance('Assign', 'TestimonialsTable');
						$issetmenus = $assignTable->loadIds($this->id, 'menu');
						foreach ( $menus as $menid ) 
							{
								if ( $menid>0 )
								if (in_array($menid, $issetmenus))
								{
									unset($issetmenus[array_search($menid, $issetmenus)]);
								}
								else 
								{
									$assignTable->storeWithType($assignTable->bind(array('type'=>"menu",'tag_id'=>$this->id,'aid'=>$menid)), 'menu');
								} 						
							}
							if (sizeof($issetmenus)>0)
								{
									foreach ( $issetmenus as $menid ) 
									{
										$assignTable->deleteWithType($this->id,$menid, 'menu');
									}								
								}
							}
					}
				}
			if (isset($_POST['jform']['categories']))
			{
				$categories = $_POST['jform']['categories'];
				if (is_array($categories))
				{
					if(sizeof($categories)>0)
					{
						$assignTable = JTable::getInstance('Assign', 'TestimonialsTable');
						$issetcategories = $assignTable->loadIds($this->id, 'category');
						foreach ( $categories as $catid ) 
							{
								if ( $catid>0 )
								if (in_array($catid, $issetcategories))
								{
									unset($issetcategories[array_search($catid, $issetcategories)]);
								}
								else 
								{
									$assignTable->storeWithType($assignTable->bind(array('type'=>"category",'tag_id'=>$this->id,'aid'=>$catid)), 'category');
								} 						
							}
							if (sizeof($issetcategories)>0)
								{
									foreach ( $issetcategories as $catid ) 
									{
										$assignTable->deleteWithType($this->id,$catid, 'category');
									}								
								}
							}
					}
				}
			if (isset($_POST['jform']['articles']))
			{
				$articles = $_POST['jform']['articles'];
				if (is_array($articles))
				{
					if(sizeof($articles)>0)
					{
						$assignTable = JTable::getInstance('Assign', 'TestimonialsTable');
						$issetarticles = $assignTable->loadIds($this->id, 'article');
						foreach ( $articles as $artid ) 
							{
								if ( $artid>0 )
								if (in_array($artid, $issetarticles))
								{
									unset($issetarticles[array_search($artid, $issetarticles)]);
								}
								else 
								{
									$assignTable->storeWithType($assignTable->bind(array('type'=>"article",'tag_id'=>$this->id,'aid'=>$artid)), 'article');
								} 						
							}
							if (sizeof($issetarticles)>0)
								{
									foreach ( $issetarticles as $artid ) 
									{
										$assignTable->deleteWithType($this->id,$artid, 'article');
									}								
								}
							}
					}
				}
			}else{
			    $table = clone $this;
			    if ($table->load(array('tag_name' => $this->tag_name))){
				$this->setError(JText::_('COM_TESTIMONIALS_ERROR_UNIQUE_TAG_NAME'));
				return false;
			    }
			}
			return parent::store($updateNulls);	
		}
}