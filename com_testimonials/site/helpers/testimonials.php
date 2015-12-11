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

class TestimonialsHelper
{
	protected $document;
	protected $template;
	protected $menuitem;
	protected $params;
	protected $cust_fields;
	protected static $default_view = "testimonials";

	function __construct($template = '')
	{
		$this->document = JFactory::getDocument();
        $this->params   = self::getParams();
        $this->menuitem = JFactory::getApplication()->getMenu()->getActive();
	}

    public function setCustomFileds($array = array()){
        if($array) $this->cust_fields = $array;
        else{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);
            $query->select('CONCAT("[",c.name,"]") AS `key`');
            $query->from('`#__tm_testimonials_custom` AS c');
            $query->where('c.`published`=1');
            $query->order(' c.ordering ');
            $db->setQuery($query);
            $data = $db->loadAssocList('key');
            $this->cust_fields = array_keys($data);
        }
    }

    public static function getCustomFileds(){
        if(!isset($this)){
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);
            $query->select('CONCAT("[",c.name,"]") AS `key`');
            $query->from('`#__tm_testimonials_custom` AS c');
            $query->where('c.`published`=1');
            $query->order(' c.ordering ');
            $db->setQuery($query);
            $data = $db->loadAssocList('key');
            return array_keys($data);
        }else{
            return $this->cust_fields;
        }
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

    public function getActiveItem()
    {
        if(!$this->menuitem){
            $this->menuitem = JFactory::getApplication()->getMenu()->getItem($this->getClosesItemId(JFactory::getURI()->getQuery(true)));
        }
		if(!$this->menuitem)
			$this->menuitem = JFactory::getApplication()->getMenu()->getActive();
        return $this->menuitem;
    }

    public static function getParams()
    {
        return JComponentHelper::getParams('com_testimonials');
    }

    public function renderText($text){
        if(preg_match_all('|\[([^\]]+)_summary\]|i', $text, $matches)){
            $tags = $this->menuitem->params->get('tags');

            $db = JFactory::getDbo();
            foreach($matches[1] as $fieldTag){
                $query	= $db->getQuery(true);
                $query->select('tf.value, tf.item_id');
                $query->from('#__tm_testimonials_items_fields tf');
                $query->join('INNER', '#__tm_testimonials_custom tc ON tc.id = tf.field_id');
                $query->join('INNER', '#__tm_testimonials t ON t.id = tf.item_id');
                $query->where('t.published = "1"');
                $query->where('t.is_approved = "1"');
                if (sizeof($tags)>0 && !$this->menuitem->params->get('all_tags')){
                    $query->join('INNER', '#__tm_testimonials_conformity AS c ON tf.item_id = c.id_ti');
                    $query->where('c.`id_tag` IN ('.implode(',',$tags).')');
                }
				/* adding categories selection */
				$catid = $this->menuitem->params->get('testimonials_category');
				if($catid){
				
					jimport('joomla.application.categories');
					$categories = new JCategories(array('extension'=>'com_testimonials','access'=>true));
					$this->categories = $categories;
					$cur_cat = $categories->get($catid);
					$this->category = $cur_cat;
					$subs = $cur_cat->getChildren(true);
					$rel_level = $cur_cat->level;
					
					$ids = array($cur_cat->id);
					foreach($subs as $s){
						$ids[] = $s->id;
					}
					
					$query->where('`t`.`catid` IN('.implode(',',$ids).')')
						->select('`c`.`title`')
						->leftJoin('`#__categories` as `c` ON `t`.`catid` = `c`.`id`');
				}
				
                $query->where('tc.name='.$db->quote(trim($fieldTag)));
                $query->where('tc.published=1');
                $query->where('t.published=1');
                $query->group('t.id');
                $query2 = $db->getQuery(true);
                $query2->select('SUM( value ) AS votes_summary, COUNT( item_id ) AS total_votes');
                $query2->from("($query) AS tt");
                $db->setQuery($query2);
                $data = $db->loadObject();
                if($data->total_votes>0){
                    $rating = round($data->votes_summary/$data->total_votes);
                    $replace = $this->template->rating('',$rating);
                    $text = str_ireplace('['.$fieldTag.'_summary]', $replace, $text);
                }else{
                    $text = str_ireplace('['.$fieldTag.'_summary]', '', $text);
                }
            }
        }
        $text = JHTML::_( 'content.prepare', $text );

        return $text;
    }

    public static function can($action){
        return JFactory::getUser()->authorise('core.'.$action, 'com_testimonials');
    }

    public static function enableCaptcha()
    {
        JFactory::getDocument()->addScript(JURI::root().'components/com_testimonials/assets/captcha/scripts.js');
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

    public static function uriToArray($url_params){
        if(!is_array($url_params)){
            $url = str_replace(array(JUri::base(),"index.php","?"),'',$url_params);
            $url = explode('&',$url);
            $url_params = array();
            foreach($url as $part){
                unset($key);
                unset($var);
                list($key,$var) = explode('=',$part);
                $url_params[$key] = $var;
            }
        }
        return $url_params;
    }

    public static function processURL($url_params){
        $url_params = self::uriToArray($url_params);
        return JRoute::_('index.php?'.implode('&',$url_params).'&ItemId='.self::getClosesItemId($url_params));
    }


    public static function getQueryByItemId($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("`id`,`link`")
            ->from('#__menu ')
            ->where('`id`="'.$id.'"')
            ->where('`published` = "1"');
        $db->setQuery($query);
        $link = $db->loadObject();
        $link->vars = explode('?', $link->link);
        $link->vars = $link->vars[1];
        $link->vars = explode('&', $link->vars);
        foreach($link->vars as $var){
            list($key, $value) = explode('=',$var);
            $link->params[$key] = $value;
        }
        return $link->params;
    }

    public static function getClosesItemId($needles = array(),$dinasty = array('form'=>'testimonials','topic'=>'testimonials','topics'=>'testimonials')){
        $needles = self::uriToArray($needles);
        if(empty($dinasty)) $dinasty = self::$dinasty;

        if(!array_key_exists('option',$needles)) $needles['option'] = 'com_testimonials';
        else if(!$needles['option']) $needles['option'] = 'com_testimonials';
        if(!array_key_exists('view',$needles)) $needles['view'] = self::$default_view;
        else if(!$needles['view']) $needles['view'] = self::$default_view;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("`id`,`link`")
            ->from('#__menu ')
            ->where('`published` = "1"');
        if(isset($needles['view'])) $query->where('`link` LIKE "%option='.$needles['option'].'%view='.$needles['view'].'%"');
        else $query->where('`link` LIKE "%option='.$needles['option'].'%"');
        $db->setQuery($query);
        $links = $db->loadObjectList();
        if(empty($links)){
            if(array_key_exists($needles['view'],$dinasty)){
                $query->clear('where')
                    ->where('`link` LIKE "%option='.$needles['option'].'%view='.$dinasty[$needles['view']].'%"')
                    ->where('`published` = "1"');
                $db->setQuery($query);
                $links = $db->loadObjectList();
            }
            if(empty($links)){
                $query->clear('where')
                    ->where('`link` LIKE "%option='.$needles['option'].'%view='.self::$default_view.'%"')
                    ->where('`published` = "1"');
                $db->setQuery($query,0,1);
                $link = $db->loadObject();
                if(!$link){
                    $query->clear('where')
                        ->where('`link` LIKE "%option='.$needles['option'].'%"')
                        ->where('`published` = "1"');
                    $db->setQuery($query,0,1);
                    $link = $db->loadObject();
                }
                return $link->id;
            }
        }
        $defItemId='';
        $itemId='';

        if($links){
            foreach($links as &$link){
                $link->vars = explode('?', $link->link);
                $link->vars = $link->vars[1];
                $link->vars = explode('&', $link->vars);
                foreach($link->vars as $var){
                    list($key, $value) = explode('=',$var);
                    if($key!='view' || $value != self::$default_view){
                        $link->params[$key] = $value;
                        $itemId = $link->id;
                    }
                }
                if(array_key_exists('view',$link->vars)){
                    $defItemId = $link->id;
                }
                unset($link->vars);
            }
            unset($link);
            return $itemId;
        }
        else return false;
    }

    protected static function _findItem($needles = null)
    {
        $app		= JFactory::getApplication();
        $menus		= $app->getMenu('site');
        $language	= isset($needles['language']) ? $needles['language'] : '*';

        // Prepare the reverse lookup array.
        if (!isset(self::$lookup[$language]))
        {
            self::$lookup[$language] = array();
            $component	= JComponentHelper::getComponent('com_testimonials');
            $attributes = array('component_id');
            $values = array($component->id);
            if ($language != '*')
            {
                $attributes[] = 'language';
                $values[] = array($needles['language'], '*');
            }

            $items		= $menus->getItems($attributes, $values);
            foreach ($items as $item)
            {
                if (isset($item->query) && isset($item->query['view']))
                {
                    $view = $item->query['view'];
                    if (!isset(self::$lookup[$language][$view]))
                    {
                        self::$lookup[$language][$view] = array();
                    }
                    if (isset($item->query['id'])) {
                        if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
                        {
                            self::$lookup[$language][$view][$item->query['id']] = $item->id;
                        }
                    }
                }
            }
        }

        if ($needles)
        {
            foreach ($needles as $view => $ids)
            {
                if (isset(self::$lookup[$language][$view]))
                {
                    foreach ($ids as $id)
                    {
                        if (isset(self::$lookup[$language][$view][(int) $id]))
                        {
                            return self::$lookup[$language][$view][(int) $id];
                        }
                    }
                }
            }
        }

        // Check if the active menuitem matches the requested language
        $active = $menus->getActive();
        if ($active && $active->component == 'com_goals' && ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled()))
        {
            return $active->id;
        }

        // If not found, return language specific home link
        $default = $menus->getDefault($language);
        return !empty($default->id) ? $default->id : null;
    }
}