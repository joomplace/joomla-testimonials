<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

class NTemplateHelper
{
    protected $html;
    protected $params;
    protected $replacement = array('from'=>array(),'to'=>array());
    protected $template;
    protected $markup;
    protected $methods;

    function __construct($template, $html){
        $this->template = $template;
        $this->params   = TestimonialsHelper::getParams();
        $this->markup   = $html;
        $this->methods  = get_class_methods("NTemplateHelper");

        if(is_dir(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $this->template)){
            JFactory::getDocument()->addStyleSheet(JURI::root().'components/com_testimonials/templates/'.$this->template.'/css/style.css');
        }
    }

    function pluginProcess($text){
        $text = JHTML::_( 'content.prepare', $text );
        return $text;
    }

    function processMarkup($item){
        $this->html = $this->markup;
        $this->replacement = array('from'=>array(),'to'=>array());


        $this->prerender('caption',$item->t_caption);
        $this->prerender('testimonial',$item->testimonial);
        $this->prerender('author',$item->t_author);
        $this->prerender('descr',$item->author_description);
        $this->prerender('date',$item->date_added);


        if ($this->params->get('use_cb') == 1 || $this->params->get('use_jsoc') == 1){
            $this->prerender('avatar',$item->avatar);
        }else{
            $this->prerender('avatar',$item->photo);
        }


        $this->prerender('imagelist');/* need to migrate */


        /*images*/
        /*
        if (!empty($row->images)) {
            $list = explode("|", $row->images);
            $str = '<ul class="imagelist">';
            foreach ($list as $i=>$item) {
                if(!empty($item)) $str .= '<li><a class="gallery" rel="gal'.$row->id.'" href="'.JURI::base().'images/testimonials/'.$item.'">'.JHtml::_('thumbler.renderthumb', JPATH_BASE.'/images/testimonials/'.$item, array( 'set_attrs' => true)).'</a></li>';
            }
            $str .= '</ul>

		<script>
			jQuery.noConflict();
			(function($) {
				$(function() {
					$(\'a.gallery\').fancybox({
						\'transitionIn\'	:	\'elastic\',
						\'transitionOut\'	:	\'elastic\',
						\'speedIn\'		:	600,
						\'speedOut\'		:	200,
						\'overlayShow\'	:	false
					});
				});
			})(jplace.jQuery);
		</script>';

        } else {
            $str = '';
        }
        */
        if(isset($item->customs))
            if($item->customs)
                foreach($item->customs as $custom){
                    $custom->key = trim($custom->key,'[]');
                    $this->prerender($custom->type.'.'.$custom->key,$custom->value);
                }

        $this->prerender('tags',$item->tags);

        /*
         * if($this->params->get('force_replace')){
         */
        if(1){
            foreach(TestimonialsHelper::getCustomFileds() as $field){
                if(!in_array($field,$this->replacement['from'])){
                    $this->replacement['from'][] = $field;
                    $this->replacement['to'][] = '';
                }
            }
        }

        $this->html = str_ireplace($this->replacement['from'],$this->replacement['to'],$this->html);

        if($item->comment){
            $iCanComment = TestimonialsHelper::can('comment');
            $comment = explode('|',$this->html);
            $user 	 = JFactory::getUser($comment[0]);
            $this->html .= '<div class="owner_comment"><span class="comment_user_name"><strong>'.JText::_('COM_TESTIMONIALS_OWNER_REPLY').'</strong></span>';
            $this->html .= ($iCanComment) ? '<span class="dlt-comment"><img src="'.JURI::root().'components/com_testimonials/assets/images/stop.png" alt="'.JText::_('COM_TESTIMONIALS_COMMENT_DELETE').'" title="'.JText::_('COM_TESTIMONIALS_COMMENT_DELETE').'" onclick="javascript:deleteComment('.$item->id.');"/></span>' : '';
            $this->html .= '<br/><span class="comment_text">'.$comment[1].'</span></div>';
            if($iCanComment){
                $this->html .= '<div id="add_comment'.$item->id.'" style="display: none;"><textarea name="comment" class="comment-textarea">'.((!empty($item->comment)) ? $comment[1] : '').'</textarea><br /><input type="button" name="add_comment" onclick="storeComment(\''.$item->id.'\');" value="'.JText::_('COM_TESTIMONIALS_CAN_COMMENT').'" class="submit-tstl-comment" /></div>';
            }
        }

        return $this->html;
    }

    function prerender($field,$value = ''){
        if(in_array($field,$this->methods)){
            $func = $field;
            $this->$func($field,$value);
        }else{
            if(strpos($field,'.')){
                list($style,$field) = explode('.',$field);
                if(in_array($style,$this->methods)){
                    $func = $style;
                    $this->$func($field,$value);
                }else{
					$this->field($field,$value);
				}
            }else{
                $this->field($field,$value);
            }
        }
    }

    function field($field,$value){
        $this->replacement['from'][] = '['.$field.']';
        /*$this->replacement['to'][] = $this->pluginProcess($value);*/
        $this->replacement['to'][] = $value;
    }

    function avatar($field,$value){
        if($this->params->get('show_avatar')){
            $this->field($field, JHtml::_('thumbler.renderthumb', $value));
        }
        else $this->field($field,'');
    }

    function caption($field,$value){
        if($this->params->get('show_caption')){
            $this->field($field,$value);
        }
        else $this->field($field,'');
    }

    function testimonial($field,$value){
        $this->field($field,$this->pluginProcess($value));
    }

    function author($field,$value){
        if($this->params->get('show_authorname')){
            $this->field($field,$value);
        }
        else $this->field($field,'');
    }

    function descr($field,$value){
        if($this->params->get('show_authorname')){
            $this->field('show_authordesc',$value);
        }
        else $this->field($field,'');
    }

    function date($field,$value){
        $value = JHtml::_('date', $value, $this->params->get('date_format',JText::_('DATE_FORMAT_LC1')) );

        if($field) $this->field($field,$value);
        else return $value;
    }

    function rating($field,$value){
        $replaseFrom[] 	= $field;
        $replace = '';
        for($a=1;$a<6;$a++){
            $replace .= '<i class="icon-star'.((isset($value) && $value >= $a) ? '' : '-empty').' fa fa-star'.((isset($value) && $value >= $a) ? '' : '-o').'">&nbsp;&nbsp;</i>';
        }

        $value = '<span class="tm_stars">'.$replace.'</span>';

        if($field) $this->field($field,$value);
        else return $value;
    }

    function email($field,$value){

        if($field) $this->field($field,$this->pluginProcess($value));
        else return $value;
    }

    function url($field,$value){

        $url = explode("|", $value);
        if(!empty($url[0])){
            if(!preg_match('|^https?://|i', $url[0])) $url[0] = 'http://'.$url[0];
        }
        $value = '<a href="'.(isset($url[0])?$url[0]:(isset($url[1])?$url[1]:'java:void(0)')).'" name="testimonial_link" target="_blank">'.(isset($url[1])?$url[1]:(isset($url[0])?$url[0]:'')).'</a>';

        if($field) $this->field($field,$this->pluginProcess($value));
        else return $value;
    }

    function tags($field,$value){
        $value = explode(',',$value);
        if($value)
            foreach($value as &$tg){
				if($tg){
					list($id,$text) = explode("::",$tg);
					$tg = '<a href="'.JRoute::_('index.php?option=com_testimonials&tag='.$id.'&Itemid='.TestimonialsHelper::getClosesItemId('index.php?option=com_testimonials&tag='.$id)).'" class="label label-info">'.$text.'</a>';
				}
            }
        $value = implode(' ',$value);

        if($field) $this->field($field,$value);
        else return $value;
    }

}