<?php
/**
* Testimonials Plugin for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

class plgContentTags_testimonial extends JPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
	
		$regex = '/{testimonial\s(.*?)}/i';
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		
		// No matches, skip this
		if ($matches)
		{
			$lang = JFactory::getLanguage();
			$extension = 'com_testimonials';
			$base_dir = JPATH_SITE;
			$language_tag = $lang->getTag();
			$reload = true;
			$lang->load($extension, $base_dir, $language_tag, $reload);
			
			if(JFactory::getUser()->authorise('core.admin', 'com_testimonials')){
				$app = JFactory::getApplication(); 
				$app->enqueueMessage(JText::_("PLG_CONTENT_TESTIMONIALS_WARNING_OLD_PLUGIN_MARKUP"),"warning"); 
			}
			foreach ($matches as $match)
			{
				$match[1] = explode(' ',$match[1]);
				$limit_pos = count($match[1])-1;
				$change_str = array(0=>array(),1=>$match[1][$limit_pos],2=>'random');
				foreach($match[1] as $key => $value){
					if( $key != $limit_pos){
						$change_str[0][] = $value;
					}
				}
				$change_str[0] = implode(' ',$change_str[0]);
				$change_str = implode('|',$change_str);
				$output = '{testimonials default|tag:'.$change_str.'}';
				$this->replace($article,$match[0],$output);
			}
		}
		
		$regex = '/{testimonials\s(.*?)}/i';

		// Find all instances of plugin and put in $matches for loadposition
		// $matches[0] is full pattern match, $matches[1] is the position
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		
		// No matches, skip this
		if ($matches)
		{
			$lang = JFactory::getLanguage();
			$extension = 'com_testimonials';
			$base_dir = JPATH_SITE;
			$language_tag = $lang->getTag();
			$reload = true;
			$lang->load($extension, $base_dir, $language_tag, $reload);
		
			$this->prepareClasses();
			foreach ($matches as $match)
			{
				$matcheslist = explode('|', $match[1]);
				
				$view = new TestimonialsViewTestimonials();
				$model = new TestimonialsModelTestimonials();
				$model->layout = $matcheslist[0];
				/* category or tag */
				if(strpos($matcheslist[1],'tag:')===0){
					/* set tag */
					$matcheslist[1] = str_replace('tag:','',$matcheslist[1]);
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->select($db->qn('id'))
						->from($db->qn('#__tm_testimonials_tags'))
						->where($db->qn('tag_name').' = '.$db->q($matcheslist[1]));
					$tag_id = $db->setQuery($query)->loadResult();
					
					if($tag_id){
						$model->setTag($tag_id);
					}else{
						$this->replace($article,$match[0]);
						continue;
					}
				}elseif(strpos($matcheslist[1],'id:')===0){
					$matcheslist[1] = str_replace('id:','',$matcheslist[1]);
					$model->anc = $matcheslist[1];
				}else{
					/* set category to model */
					$categories = JHtml::_('category.options','com_testimonials',$config = array('filter.published' => array(1), 'filter.language' => array('*',$language_tag),'filter.access' =>array(1)));
					$category = array_filter(
						$categories,
						function ($e) use ($matcheslist) {
							return $e->text == $matcheslist[1];
						}
					);
					$category = array_shift($category);
					if($category){
						$category->id = $category->value;
						$model->category = $category;
					}else{
						$this->replace($article,$match[0]);
						continue;
					}
				}
				/* list limit */
				$model->setListLimit($matcheslist[2]);
				if(isset($matcheslist[3]) && $matcheslist[3]=='random'){
					$model->random = true;
				}
				
				$view->setModel($model, true);
				
				$view->definePath();
				$view->assignData();
				$output = $view->loadTemplate();
				$this->replace($article,$match[0],$output);
				
			}
		}
	}
	
	protected function replace($article, $from, $to = ''){
		
		// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
		$article->introtext = preg_replace("|".addcslashes($from,'|')."|", addcslashes($to, '\\$'), $article->introtext, 1);
		$article->fulltext = preg_replace("|".addcslashes($from,'|')."|", addcslashes($to, '\\$'), $article->fulltext, 1);
		$article->text = preg_replace("|".addcslashes($from,'|')."|", addcslashes($to, '\\$'), $article->text, 1);
		
		return true;
	}
	
	protected function prepareClasses(){
		JHtml::addIncludePath(JPATH_SITE.'/components/com_testimonials/helpers/html');
		JLoader::register('TestimonialsHelper', JPATH_SITE.'/components/com_testimonials/helpers/testimonials.php');
		JLoader::register('TestimonialsModelTestimonials', JPATH_SITE.'/components/com_testimonials/models/testimonials.php');
		JLoader::register('TestimonialsViewTestimonials', JPATH_SITE.'/components/com_testimonials/views/testimonials/view.html.php');
		JLoader::register('TestimonialsHelper', JPATH_SITE.'/components/com_testimonials/helpers/testimonials.php');
	}
	
}