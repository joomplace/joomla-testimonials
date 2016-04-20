<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');

class TestimonialHelper
{
	protected $document;
	function __construct() 
	{
		$this->document = JFactory::getDocument();
	}
	
	public function getNotifyUserEmails()
        {
        	$access = JAccess::getAssetRules('com_testimonials')->getData();
			if (!sizeof($access) || !sizeof($access['core.notify'])) return array();
			$tacc = $access['core.notify']->getData();
			$gids = $users = $userlist = $emails = array();
				if (is_array($tacc) && sizeof($tacc)>0)
				{
					foreach ( $tacc as $key => $acc ) 
					{
						if ($acc==1)
						{
							$gids[]=$key;
						}
					}
				}
		
				if (sizeof($gids)>0)
				{
					foreach ( $gids as $gid ) 
					{
						$userlist = JAccess::getUsersByGroup($gid);
						if (sizeof($userlist)>0)
						{
							foreach ( $userlist as $usl )
							{
								$usl?$users[] = $usl:'';
							}
						}
					}
				}
		
				if (sizeof($users)>0)
				{
					$users = array_unique($users);
					foreach ( $users as $usid ) 
					{
						$user = JFactory::getUser($usid);
						$emails[]= $user->email;
					}
				}
			return $emails;
		 }
		 
	public static function needTemplate($template='default', $params)
	{
		JLoader::register('TemplateHelper', JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'template.php');
		$tclass = new TemplateHelper($template);
		return $tclass;
	}
	
	 public static function getSettings()
        {
			$settings = JFactory::getApplication()->getParams();
 			
 			if ($settings->get('use_cb') || $settings->get('use_jsoc'))
 			{
 				if (!file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_comprofiler'.DIRECTORY_SEPARATOR.'admin.comprofiler.php'))
 				{
 					$settings->set('use_cb',0);
 				}
 				if (!file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_community'.DIRECTORY_SEPARATOR.'community.php'))
 				{
 					$settings->set('use_jsoc',0);
 				}
 			}
 			
 			return $settings;
        }
        
    public static function getParams(){
        return JComponentHelper::getParams('com_testimonials');
    }
        
	public static function enableCaptcha()
	{
		JFactory::getDocument()->addScript(JURI::root().'components/com_testimonials/assets/captcha/scripts.js');
		//JS 
		ob_start();
		?>
			captcha_params = new Object();		
			captcha_params.mosConfig_live_site = '<?php echo JURI::root(); ?>';
			captcha_params.msg_invalid_code = '<?php echo JText::_('COM_TESTIMONIALS_ADD_INVALIDCODE'); ?>';
		<?php
		$js = ob_get_contents();
		ob_get_clean();
		JFactory::getDocument()->addScriptDeclaration($js);
	}
	
	public static function enablejQuery() {	
	}

    public static function notifyAdmin($caption, $author, $isEdited){

        $params = self::getParams();
        switch($params->get('enable_email')){
            case '0':
                return;
                break;
            case '1':
                $boxes = explode(',', $params->get('admin_email'));
                $mailer = JFactory::getMailer();
                if($isEdited == true){
                    $body = 'Greetings! A testimonial ( "'.$caption.'" ) was edited on the site by user "'.$author.'". Please review the testimonial.';
                }else{
                    $body = 'Greetings! A new testimonial ( "'.$caption.'" ) was added on the site by user "'.$author.'". Please review the testimonial.';
                }
                $subject = 'Administrator notification';
                $mailer->setSubject($subject);
                $mailer->setBody($body);
                $mailer->addRecipient($boxes);
                $mailer->send();
                break;

            default:
                return;
        }

        return;
    }

}