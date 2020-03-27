<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
 defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the Testimonials Component
 */
class TestimonialsViewForm extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $return_page;
	protected $state;
	
	function renderLayout($layout, $data = null, $sublayout=''){
		if(!$sublayout) $sublayout = ($this->getLayout()=='default')?'':$this->getLayout();
		if(!$data) $data = (object)array('value'=>'');
		$field_layout = new JLayoutFile($layout);
		$field_layout->setComponent('com_testimonials');
		$html = $field_layout->sublayout($sublayout,$data);
		if(!$html) $html = $field_layout->render($data);

        if(!$html) $html = $data->value;

		return $html;
	}
	
	public function display($tpl = null) {

        $this->renderLayout('testimonials.framework');

        $user		= JFactory::getUser();
		
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');

        $this->tags			= $this->get('Tags');
        $this->custom_fields = $this->get('CustomFields');
        $this->helper       = new TestimonialsHelper();
        $this->params		= $params =  $this->helper->getParams();

        $Gparams = JFactory::getApplication()->getParams();


        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if (empty($this->item->id)) {
		    $authorised = ($user->authorise('core.create', 'com_testimonials'));
		}
		else {
			$authorised = $user->authorise('core.edit', 'com_testimonials');
		}
		
		if ($authorised !== true) {
			JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
		}else{		
			$document = JFactory::getDocument();
			JHTML::stylesheet('components/com_testimonials/assets/submit-form/css/template_testimonials.css');
			JHtml::_('jquery.framework');
			$document->addScript('components/com_testimonials/assets/submit-form/js/main.js');
			if($params->get('allow_photo') || $params->get('show_addimage')){
				$document->addScript('components/com_testimonials/assets/file-upload/js/vendor/jquery.ui.widget.js');
				$document->addScript('components/com_testimonials/assets/file-upload/js/jquery.iframe-transport.js');
				$document->addScript('components/com_testimonials/assets/file-upload/js/jquery.fileupload.js');
				$document->addCustomTag('<!--[if gte IE 8]><script src="/components/com_testimonials/assets/file-upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->');
			}
			$document->addCustomTag('<!--[if lt IE 9]><script src="/components/com_testimonials/assets/html5.js"></script><![endif]-->');
			if($params->get('use_editor')){
				$document->addScript('components/com_testimonials/assets/wysihtml/advanced.js');
				$document->addScript('components/com_testimonials/assets/wysihtml/wysihtml5-0.3.0_rc2.js');
			}
			$document->addCustomTag('<meta http-equiv="X-UA-Compatible" content="IE=Edge" />');
			parent::display($tpl);
		}
	}

    public function processText($text){
        if(preg_match_all('|\[([^\]]+)_summary\]|i', $text, $matches)){
            $tags = $this->helper->getActiveItem()->params->get('tags');

            $db = JFactory::getDbo();
            foreach($matches[1] as $fieldTag){
                $query	= $db->getQuery(true);
                $query->select('tf.value, tf.item_id');
                $query->from('#__tm_testimonials_items_fields tf');
                $query->join('INNER', '#__tm_testimonials_custom tc ON tc.id = tf.field_id');
                $query->join('INNER', '#__tm_testimonials t ON t.id = tf.item_id');
                $query->where('t.published = "1"');
                $query->where('t.is_approved = "1"');
                if (sizeof($tags)>0 && !$this->helper->getActiveItem()->params->get('all_tags')){
                    $query->join('INNER', '#__tm_testimonials_conformity AS tcon ON tf.item_id = tcon.id_ti');
                    $query->where('tcon.`id_tag` IN ('.implode(',',$tags).')');
                }
                /* adding categories selection */
                $catid = $this->helper->getActiveItem()->params->get('testimonials_category');
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
                    $rating = round($data->votes_summary/$data->total_votes,1);
                    $replace = $this->renderLayout('testimonials.agg_rating',(object)array('value' => $rating, 'count' => $data->total_votes));
                    $text = str_ireplace('['.$fieldTag.'_summary]', $replace, $text);
                }else{
                    $text = str_ireplace('['.$fieldTag.'_summary]', '', $text);
                }
            }
        }
        $text = JHTML::_( 'content.prepare', $text );

        return $text;
    }
}