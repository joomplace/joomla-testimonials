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
				$output = '{testimonials default}';
				// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
				
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
				
				$view->setModel($model, true);
				
				$view->definePath();
				$view->assignData();
				$output = $view->loadTemplate();
				// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
				
			}
		}
	}
	
	protected function prepareClasses(){
		JHtml::addIncludePath(JPATH_SITE.'/components/com_testimonials/helpers/html');
		JLoader::register('TestimonialsHelper', JPATH_SITE.'/components/com_testimonials/helpers/testimonials.php');
		JLoader::register('TestimonialsModelTestimonials', JPATH_SITE.'/components/com_testimonials/models/testimonials.php');
		JLoader::register('TestimonialsViewTestimonials', JPATH_SITE.'/components/com_testimonials/views/testimonials/view.html.php');
		JLoader::register('TestimonialsHelper', JPATH_SITE.'/components/com_testimonials/helpers/testimonials.php');
	}
	
}