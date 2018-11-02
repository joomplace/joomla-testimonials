<?php
/**
 * Testimonials Module for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access

defined('_JEXEC') or die;
jimport('joomla.application.component.model');

if (!class_exists('JModel')) {
    require_once(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_testimonials' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'topics.php');
}

JModelLegacy::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_testimonials' . DIRECTORY_SEPARATOR . 'models');

if (!class_exists('TestimonialsFEHelper')) {
    require_once(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_testimonials' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'testimonials.php');
}
if (!class_exists('JHtmlThumbler')) {
    JHtml::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR .'components/com_testimonials/helpers/html');
}

class modTestimonialsHelper {

    public function getList($params) {
        $model = JModelLegacy::getInstance($name = 'Topics', $prefix = 'TestimonialsModel', array('ignore_request' => true));
        $items = $model->getItemsByItemId($params);
        return $items;
    }
    
    public function getListByTags($params, $tags_id){
        $items = $this->getItemsByTags($params, $tags_id);
        return $items;
    }
    
     public function getItemsByTags($settings = 0, $tags_id) {
        $Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);
        $option = JFactory::getApplication()->input->getVar('option', '');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        if ($tags_id) {
            $assign = true;
            $tags_id = @implode(',', $tags_id);
        }
        $query = $db->getQuery(true);
        $query->select('DISTINCT t.*');
        $query->from('`#__tm_testimonials` AS `t`');
        $query->where('t.`published`=1 ');
        $query->where('t.`is_approved`=1 ');
        $query->join('LEFT', '`#__tm_testimonials_conformity` AS `c` ON t.id = c.id_ti');
        if ($settings->get('use_cb') && file_exists(JPATH_SITE.'/components/com_comprofiler/comprofiler.php')) {
            $db->setQuery("SELECT COUNT(id) FROM #__comprofiler");
            $comprofiler_exists = $db->loadResult();

            if ($comprofiler_exists) {
                $query->select('compr.avatar');
                $query->join('LEFT', '`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');
            }
        }
        if ($settings->get('use_jsoc') && file_exists(JPATH_SITE.'/components/com_community/community.php')) {
            $db->setQuery("SELECT COUNT(userid) FROM `#__community_users`");
            $jomsocial_exists = $db->loadResult();

            if ($jomsocial_exists) {
                $query->select('jsoc.thumb AS avatar');
                $query->join('LEFT', '`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');
            }
        }
        $tagopt = $settings->get('all_tags');
        if ($tags_id) {
            if ($tagopt == 1) {
                $query->where('c.`id_tag` IS NOT NULL');
            } else if ($tagopt == 0) {
                $query->where('c.`id_tag` IN (' . $tags_id . ')');
            }
        }
        $query->order('RAND()');
        $db->setQuery($query, 0, 10);
        $items = $db->loadObjectList();
        //print_r($tagopt.'--'.$tags_id);die();
        return $items;
    }

    public function getModuleSettings() {
        $module = JModuleHelper::getModule('mod_testimonials');
        $params = new JRegistry;
        $params->loadString($module->params);
        return $params;
    }

    public function getComponentSettings() {
        return JComponentHelper::getParams('com_testimonials');
    }

    function getAvatar($photo = '', $cb_avatar = '') {
        $this->config = $this->getModuleSettings();
        $com_params = modTestimonialsHelper::getComponentSettings();
        $this->config = $this->getModuleSettings();
        $width_avatar = $this->config->get('th_width','110');
        $this->nophoto = false;
        $avatar = '';
        if ($photo != '') {
            if (file_exists(JPATH_SITE . "/" . $photo)) {
                $avatar = JHtml::_('thumbler.getthumb', $photo, array('set_attrs' => true, 'width' => $width_avatar,'square'=> true, 'sizing' => 'filled', 'compression' => 95 ));
            }else {
                $this->nophoto = true;
            }
        } else {
            if ($this->config->get('use_cb') == 1) {
                $check = explode('/', @$cb_avatar);
                $check = $check[0];
                if (isset($cb_avatar) && $check != 'gallery') {
                    if (file_exists(JPATH_SITE . "/images/comprofiler/tn" . $cb_avatar)) {
                        $avatar = "images/comprofiler/tn" . $cb_avatar;
                    } else {
                        $this->nophoto = true;
                    }
                } elseif (isset($cb_avatar) && $check == 'gallery') {
                    if (file_exists(JPATH_SITE . "/images/comprofiler/" . $cb_avatar)) {
                        $avatar = "images/comprofiler/" . $cb_avatar;
                    } else {
                        $this->nophoto = true;
                    }
                } else {
                    $this->nophoto = true;
                }
            }
        }
        if ($com_params->get('use_jsoc') == 1 && $photo) {
                if (file_exists(JPATH_SITE . "/" . $photo)) {
                    $photo = preg_replace('/(th_){1,}/', '', $photo);
                    $photo = preg_replace('/(stream_){1,}/', '', $photo);                    
                    $photo = preg_replace('/(thumb_){1,}/', '', $photo);
                    $avatar = $photo;                   
                    $this->nophoto = false;
                } else {
                    $this->nophoto = true;
                }
            }
            if (!$avatar)
                $this->nophoto = true;
        if (!empty($avatar))
            $avatar = '<img class="avatar" id="t_avatar" align="left" src="' . $avatar . '" border="0" width="' . $width_avatar . '" style="padding-right: 5px;" alt=""/>';
        return $avatar;
    }

    public function tmItemId() {
        $tmItemId = TestimonialsFEHelper::getClosesItemId('index.php?option=com_testimonials&view=testimonials');
        return $tmItemId;
    }

    function __ResizeImage($photo, $ipath = "images/testimonials") {
        $filepath = JPATH_BASE . DIRECTORY_SEPARATOR . $ipath . DIRECTORY_SEPARATOR . $photo;
        $filepath_thumb = JPATH_BASE . DIRECTORY_SEPARATOR . "images/testimonials" . DIRECTORY_SEPARATOR . "th_" . $photo;
        if (file_exists($filepath) && is_file($filepath)) {
            list($width, $height, $type) = getimagesize($filepath);
            if ($width > TestimonialHelper::getSettings()->get('th_width', 150)) {
                $new_width = TestimonialHelper::getSettings()->get('th_width', 150);
                $new_height = round(($height * $new_width) / $width);
                $image_p = imagecreatetruecolor($new_width, $new_height);
                switch ($type) {
                    case 3:
                        $image = imagecreatefrompng($filepath);
                        break;
                    case 2:
                        $image = imagecreatefromjpeg($filepath);
                        break;
                    case 1:
                        $image = imagecreatefromgif($filepath);
                        break;
                    case 6:
                        $image = imagecreatefromwbmp($filepath);
                        break;
                }
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                // saving
                switch ($type) {
                    case 3:
                        @imagepng($image_p, $filepath_thumb);
                        break;
                    case 2:
                        @imagejpeg($image_p, $filepath_thumb);
                        break;
                    case 1:
                        @imagegif($image_p, $filepath_thumb);
                        break;
                }
            } else {
                copy($filepath, $filepath_thumb);
            }
        }
        return true;
    }

    function tmm_UTF8string_check($string) {
        return preg_match('%(?:
		[\xC2-\xDF][\x80-\xBF]
		|\xE0[\xA0-\xBF][\x80-\xBF]
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
		|\xED[\x80-\x9F][\x80-\xBF]
		|\xF0[\x90-\xBF][\x80-\xBF]{2}
		|[\xF1-\xF3][\x80-\xBF]{3}
		|\xF4[\x80-\x8F][\x80-\xBF]{2}
		)+%xs', $string);
    }

    function tmm_string_substr($str, $offset, $length = NULL) {
        if (modTestimonialsHelper::tmm_UTF8string_check($str)) {
            return modTestimonialsHelper::tmm_UTF8string_substr($str, $offset, $length);
        } else {
            return substr($str, $offset, $length);
        }
    }

    function tmm_UTF8string_substr($str, $offset, $length = NULL) {
        if ($offset >= 0 && $length >= 0) {
            if ($length === NULL) {
                $length = '*';
            } else {
                if (!preg_match('/^[0-9]+$/', $length)) {
                    trigger_error('utf8_substr expects parameter 3 to be long', E_USER_WARNING);
                    return ''; //FALSE;
                }
                $strlen = strlen(utf8_decode($str));
                if ($offset > $strlen) {
                    return '';
                }
                if (( $offset + $length ) > $strlen) {
                    $length = '*';
                } else {
                    $length = '{' . $length . '}';
                }
            }
            if (!preg_match('/^[0-9]+$/', $offset)) {
                trigger_error('utf8_substr expects parameter 2 to be long', E_USER_WARNING);
                return ''; //FALSE;
            }
            $pattern = '/^.{' . $offset . '}(.' . $length . ')/us';
            preg_match($pattern, $str, $matches);

            if (isset($matches[1])) {
                return $matches[1];
            }
            return ''; //FALSE;
        } else {
            preg_match_all('/./u', $str, $ar);
            if ($length !== NULL) {
                return join('', array_slice($ar[0], $offset, $length));
            } else {
                return join('', array_slice($ar[0], $offset));
            }
        }
    }

    function generateTestimModuleLinkReed($url, $text, $modal = true, $suf_class=' btn btn-default'){
        return '<a href="'.$url.'" class="'.($modal?'modtm_modal ':'').'modtm_iframe'.$suf_class.'" >'.$text.'</a>';
    }

	function generateTestimModuleLink($url, $text, $modal = true, $suf_class=' btn btn-default'){
        return '<a href="'.$url.'" class="'.($modal?'modtm_modal ':'').'modtm_iframe'.$suf_class.'" rel="{handler:\'iframe\',size:{x: (0.8*((jQuery(\'main\').width())?jQuery(\'main\').width():jQuery(\'.container\').width())), y: (0.8*jQuery(window).height())}}">'.$text.'</a>';
	}

}

