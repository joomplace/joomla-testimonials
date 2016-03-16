<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;
 
/**
 * Testimonials component helper.
 */
class TestimonialHelper
{
        public static function getVersion() 
        {
			// deprecated method
        	// return TestimonialHelper::getSetting('tm_version');
			
			/* new version is */
			
			$xml = JFactory::getXML(JPATH_COMPONENT_ADMINISTRATOR .'/testimonials.xml');
			return (string)$xml->version;
				
        }
        
        public static function getSettings()
        {
        	$db = JFactory::getDbo();
			$db->setQuery('SELECT `params` FROM #__tm_testimonials_settings LIMIT 1');
			$sets = $db->loadResult();
			$settings = new JRegistry();
 			$settings->loadString($sets, 'JSON');
 			
 			if ($settings->get('use_cb') || $settings->get('use_jsoc'))
 			{
 				if (!file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_comprofiler'.DS.'admin.comprofiler.php'))
 				{
 					$settings->set('use_cb',0);
 				}
 				if (!file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_community'.DS.'admin.community.php'))
 				{
 					$settings->set('use_jsoc',0);
 				}
 			}
 			
 			return $settings;
        }
        
        public static function getSetting($path)
        {
            $db = JFactory::getDbo();
			$db->setQuery('SELECT `params` FROM #__tm_testimonials_settings LIMIT 1');
			$sets = $db->loadResult();
			$settings = new JRegistry();
 			$settings->loadString($sets, 'JSON');
 						 			
 			return $settings->get($path);
        }
        
         public static function setSetting($path, $value)
        {
                        $db = JFactory::getDbo();
			$db->setQuery('SELECT `params` FROM #__tm_testimonials_settings LIMIT 1');
			$sets = $db->loadResult();
			$settings = new JRegistry();
 			$settings->loadString($sets, 'JSON');
                        $settings->set($path, $value);
 			$params =  $settings->toString();
                        $db->setQuery('UPDATE #__tm_testimonials_settings SET `params`=\''.$params.'\'');
 			return $db->execute();
        }
        
        public static function getNotifyUserEmails()
        {
			return array();
		 }
        
        public static function showSubmenu($submenu) 
        {       	
                /*JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TOPICS'), 'index.php?option=com_testimonials&view=topics', $submenu == 'topics');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TAGS'), 'index.php?option=com_testimonials&view=tags', $submenu == 'tags');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS'), 'index.php?option=com_testimonials&view=customs', $submenu == 'customs');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_TEMPL'), 'index.php?option=com_testimonials&view=templates', $submenu == 'templates');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_HELP'), 'index.php?option=com_testimonials&view=help', $submenu == 'help');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_SUPPORT'), 'index.php?option=com_testimonials&view=support', $submenu == 'support');
                JSubMenuHelper::addEntry(JText::_('COM_TESTIMONIALS_SUBMENU_ABOUT'), 'index.php?option=com_testimonials&view=testimonials', $submenu == 'testimonials');*/
        }
        
		public static function addSubmenu($vName)
		{
			JHtmlSidebar::addEntry(
				JText::_('COM_JOOMLAQUIZ_SUBMENU_SETUP_CATEGORY'),
				'index.php?option=com_categories&extension=com_joomlaquiz',
				$vName == 'quizcategories'
			);
			JHtmlSidebar::addEntry(
				JText::_('COM_JOOMLAQUIZ_SUBMENU_QUESTIONS_CATEGORY'),
				'index.php?option=com_categories&extension=com_joomlaquiz.questions',
				$vName == 'questcategories'
			);
			JHtmlSidebar::addEntry(
				JText::_('COM_JOOMLAQUIZ_SUBMENU_SETUP_QUIZ'),
				'index.php?option=com_joomlaquiz&view=quizzes',
				$vName == 'quizzes');
			JHtmlSidebar::addEntry(
				JText::_('COM_JOOMLAQUIZ_SUBMENU_SETUP_QUEST'),
				'index.php?option=com_joomlaquiz&view=questions',
				$vName == 'questions');
		}
		
         public static function showTitle($submenu)  
         {       
         	$document = JFactory::getDocument();
         		$title = JText::_('COM_TESTIMONIALS_ADMINISTRATION_'.strtoupper($submenu));
               	$document->setTitle($title);
               	JToolBarHelper::title($title, $submenu);                	               	              
        }
        
        public static function getCSSJS()  
         {
         	$document = JFactory::getDocument();
         	/*$view = JFactory::getApplication()->input->getCmd('view');
         	$task = JFactory::getApplication()->input->getCmd('task');
         	$task = $view?$view:$task;
         	switch ( $task ) 
         	{
         		case 'tags': 	case 'tag':		
         		case 'topics': 	case 'topic':
         		case 'customs': case 'custom':
         		case 'templates': case 'template':
         		$show = 1;
         		break;

         		case 'settings':	case 'editcss':	case 'add_lang':
				case 'menu_man':
         		case 'mod_man':	case 'moder_man':	case 'add_mod':
         		$show = 2;
         		break;
         		
         		case 'help': case 'support':
         		$show = 4;
         		break;
         		default: $show = 0; break;
         	}
         	//JS 
         	ob_start();
               	?>
			    // <!--
			          window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {show:<?php echo $show;?> ,onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false}); });
			    // -->
               	<?php
				$js = ob_get_contents();
				ob_get_clean();
             $document->addScriptDeclaration($js);*/
             
             //CSS
             $document->addStyleSheet(JURI::root(true).'/administrator/components/com_testimonials/assets/css/testimonials.css');
         }
         
       public static function getLeftMenu()  
         {
           jimport('joomla.html.html.bootstrap');
	   JHtml::_('bootstrap.framework');
           $view = JFactory::getApplication()->input->getCmd('view');
           $task = JFactory::getApplication()->input->getCmd('task');
	   $task = $view?$view:$task;
	   switch ( $task ){
		case 'tags': case 'tag':		
		case 'topics': case 'topic':
		case 'customs': case 'custom':
		case 'templates': case 'template':
		    $my_params = array('active'=>2);
		break;
		case 'help': case 'support':
		    $my_params = array('active'=>5);
		break;
		default:
		    $my_params = array('active'=>1);
		break;
	   }
	   
           $menu = '';
           switch($task){
             case 'tags': case 'tag': case 'topics': case 'topic': case 'customs': case 'custom': case 'templates': case 'template':
                 $menu .=  '
                    <ul class="nav nav-list">
                        <li'.($task=='topics' || $task == 'topic' ? ' class="active"' : '').'>
                            <a href="index.php?option=com_testimonials&view=topics">'.JText::_('COM_TESTIMONIALS_SUBMENU_TOPICS').'</a>
                        </li>
                        <li'.($task=='customs' || $task == 'custom' ? ' class="active"' : '').'>
                            <a href="index.php?option=com_testimonials&view=customs">'.JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS').'</a>
                        </li>
                        <li'.($task=='categories' || $task == 'categories' ? ' class="active"' : '').'>
                            <a href="index.php?option=com_categories&extension=com_testimonials">'.JText::_('COM_TESTIMONIALS_SUBMENU_CATEGORIES').'</a>
                        </li>
                        <li'.($task=='tags' || $task == 'tag' ? ' class="active"' : '').'>
                            <a href="index.php?option=com_testimonials&view=tags">'.JText::_('COM_TESTIMONIALS_SUBMENU_TAGS').'</a>
                        </li>
                    </ul>';
             break;
             default: $menu .= '';
             break;             
           }
           
           return $menu;
         }
	 
	 public static function addFileUploadFull($uploadHandler='server/php/', $fileInputId='fileupload', $preloadImages = array()){
                $document = JFactory::getDocument();
                JHTML::stylesheet(JURI::root(true).'/components/com_testimonials/assets/bootstrap/css/font-awesome.css');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/jplace.jquery.js');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/bootstrap/js/bootstrap.min.js');
                JHTML::stylesheet(JURI::root(true).'/components/com_testimonials/assets/file-upload/css/jquery.fileupload-ui.css');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/file-upload/js/vendor/jquery.ui.widget.js');
                JHTML::script(JURI::root(true).'/administrator/components/com_testimonials/assets/js/tmpl.min.js');
                JHTML::script(JURI::root(true).'/administrator/components/com_testimonials/assets/js/load-image.min.js');
                JHTML::script(JURI::root(true).'/administrator/components/com_testimonials/assets/js/canvas-to-blob.min.js');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/file-upload/js/jquery.iframe-transport.js');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/file-upload/js/jquery.fileupload.js');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/file-upload/js/jquery.fileupload-fp.js');
                JHTML::script(JURI::root(true).'/components/com_testimonials/assets/file-upload/js/jquery.fileupload-ui.js');
                $document->addCustomTag('<!--[if gte IE 8]><script src="'.JURI::root(true).'/components/com_testimonials/assets/file-upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->');
                $uploadInit = "
                                <script type='text/javascript'>
                                (function($) {
                                    $(document).ready(function () {
                                  $('#$fileInputId').fileupload({
                                      // Uncomment the following to send cross-domain cookies:
                                      //xhrFields: {withCredentials: true},
                                      url: '$uploadHandler',
                                      formData: {task: 'images.addImage'}
                                  });
                                  var result = ".  json_encode($preloadImages).";
                                  if (result && result.length) {
                                      $('#$fileInputId').fileupload('option', 'done').call($('#$fileInputId'), null, {result: result});
                                  }
                                    });
                                })(jplace.jQuery);
                                </script>
                                ";
                $document->addCustomTag($uploadInit);
      
   }


   public static function notifyAdmin(){
       jimport( 'joomla.mail.mail' );
       $mailer = JFactory::getMailer();
   }
}