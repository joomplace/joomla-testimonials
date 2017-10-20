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
class TestimonialsViewTestimonials extends JViewLegacy
{
	public $show_button = true;
	protected $state = null;
	protected $item = null;
	protected $items = null;
	protected $pagination = null;
	protected $order = array();
	
   function definePath()
   {
		$this->_setPath('helper', JPATH_SITE.'/components/com_testimonials/helpers');
		$this->_setPath('template', JPATH_SITE.'/templates/'.JFactory::getApplication()->getTemplate().'/html/com_testimonials/testimonials');
		$this->_addPath('template', JPATH_SITE.'/components/com_testimonials/views/testimonials/tmpl');
   }
   
   function display($tpl = null)
   {
	   $this->assignData();
	   $jinput = JFactory::getApplication()->input;

		
	   // retrieving pagination
	   if(!$jinput->get('embed','')) $this->pagination	= $this->get('Pagination');
	   $order = $jinput->get('ordering', '');
	   $this->SelectedOrder($order, 'btn-success');
		
        parent::display($tpl);
		
    }
	
	function renderLayout($layout, $data = null, $sublayout=''){
		if(!$sublayout) $sublayout = ($this->getLayout()=='default')?'':$this->getLayout();
		if(!$data) $data = (object)array('value'=>'');
		$field_layout = new JLayoutFile($layout);
		$field_layout->setComponent('com_testimonials');
		$html = $field_layout->sublayout($sublayout,$data);
        if(!$html) $html = $field_layout->render($data);
        if ((!$html) && isset($data->value)) $html = $data->value;
		
		return $html;
	}
	
	function assignData(){	
		// get state variables, filter, etc.
        $this->state		= $this->get('State');
		// assign helper class
        $this->helper       = new TestimonialsHelper();
		// get items data list
        $this->items	    = $this->get('Items');
		
		// getting additional data
		$controller = new TestimonialsController();
		JLoader::register('TestimonialsModelComment', JPATH_SITE.'/components/com_testimonials/models/comment.php');
		$comments_model = new TestimonialsModelComment();/* new - caused by can`t get needed instance in other component */
		//$this->setModel($comments_model);
        //$comments	    = $this->get('CommentsTree','Comment');
		
		// getting additional data
        $this->category	    = $this->get('Category');
		// categories model
        $this->categories	= $this->get('Categories');

		// check for DB errors
        if (count($errors = $this->get('Errors'))) { 
			JError::raiseWarning(500, implode("\n", $errors));	
			return false;
		}
		$layout = $this->get('UserLayout');
		$this->setLayout($layout);
		
		// merging custom fields data to items data + getting comments
        foreach($this->items as &$item){
			$item->comments = $comments_model->getComments($item->id);
			
			$model = $this->getModel();
            $item = (object)array_merge((array)$item, (array)$model->getCustomFields($item->id));
			/**
			* JLayoutFile can be overrided with template
			* to do this copy 
			*	/components/com_testimonials/layouts/ 
			* folder content to 
			*	/templates/YOUR_TEMPLATE/html/layouts/com_testimonials/ 
			* or
			*	/templates/YOUR_TEMPLATE/html/layouts/ 
			* and change what you like
			**
			**
			* Also there can be additional field layout for every view layout.
			* To do so place fields to subfolder in layouts and name it as view layout. Example:
			* 	LAYOUTS/rating.php
			* will be overrided with 
			* 	LAYOUTS/rating/black2.php
			* if view layout is "black2"
			**/
			// rendering everything that can be rendered with layout
			$item->custom_fields = array();
			foreach($item as &$field){
				// only if object
				if(is_object($field)){
					// condition for renderable fields
					if($field->custom_field){
						if(!$field->type) $field->type = 'default';
						if($field->display){
							$field = $this->renderLayout('testimonials.'.$field->type,$field);
							$item->custom_fields[] = $field;
						}else{
							$field = $this->renderLayout('testimonials.'.$field->type,$field);
						}
					}
				}
			}
			
			// adding specific fields to render
			// render avatar
			$item->avatar = $this->renderLayout('testimonials.avatar',(object)array('value' => $item->avatar));
			// render images
			$item->images = $this->renderLayout('testimonials.images',(object)array('value' => $item->images));
			// render tags
			$item->tags = $this->renderLayout('testimonials.tags',(object)array('value' => $item->tags));
        }
		
		$this->renderLayout('testimonials.framework');
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
                    $rating = round($data->votes_summary/$data->total_votes);
                    $replace = $this->renderLayout('testimonials.agg_rating',(object)array('value' => $rating));
                    $text = str_ireplace('['.$fieldTag.'_summary]', $replace, $text);
                }else{
                    $text = str_ireplace('['.$fieldTag.'_summary]', '', $text);
                }
            }
        }
        $text = JHTML::_( 'content.prepare', $text );

        return $text;
    }

	public function SelectedOrder($order, $value) {
		switch ($order) {
			case ('ordering'): $this->order['order'] = $value;
				break;
			default: $this->order['name'] = $value;
		}
	}

	/* move this to layouts */	
    public function adminForm($id=0, $published=0, $approved = 0)
    {
    	if ($this->helper->can('admin') || $this->helper->can('publish') || $this->helper->can('edit') || $this->helper->can('delete') || $this->helper->can('comment'))
    	{
    	?>
		<div class="testim-manage-block">
			<?php 
			if ($this->helper->can('admin'))
				{
			?>
			<a class="btn btn-warning" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.approve&id='.$id); ?>" title="<?php echo ($approved? Jtext::_('COM_TESTIMONIALS_UNAPPROVE'):Jtext::_('COM_TESTIMONIALS_APPROVE')); ?>" >
				<i class="icon-<?php echo $approved?'ban-circle':'checkmark'; ?> fa fa-<?php echo $approved?'ban':'check-square-o'; ?>" alt="<?php echo ($approved?Jtext::_('COM_TESTIMONIALS_UNAPPROVE'):Jtext::_('COM_TESTIMONIALS_APPROVE')); ?>" ></i>
			</a>
			<?php }

			if ($this->helper->can('publish'))
				{
					$img = $published?'tick.png':'publish_x.png';
			?>
			<a class="btn btn-warning" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.state&id='.$id); ?>" title="<?php echo ($published? Jtext::_('COM_TESTIMONIALS_UNPUBLISH'):Jtext::_('COM_TESTIMONIALS_PUBLISH')); ?>" >
				<i class="icon-<?php echo $published?'minus-circle':'plus-circle'; ?> fa fa-<?php echo $approved?'minus-circle':'check-circle'; ?>" alt="<?php echo ($published?Jtext::_('COM_TESTIMONIALS_UNPUBLISH'):Jtext::_('COM_TESTIMONIALS_PUBLISH')); ?>" ></i>
			</a>
			<?php }

			$helper = $this->helper;
			$params = $helper->getParams();
			$tmpl = '';
            if($params->get('modal_on_new')){
				$tmpl = '&tmpl=component';
			}
			
			if ($this->helper->can('edit'))
			{ ?>
			<a class="modal_com_testim btn btn-primary"  href="<?php echo JRoute::_('index.php?option=com_testimonials&view=form'.$tmpl.'&Itemid='.TestimonialsHelper::getClosesItemId('index.php?option=com_testimonials&view=form')).'&id='.$id; ?>" rel="{handler:'iframe',size:{x: (0.8*jQuery('#t3-mainbody').width()), y: (0.8*jQuery(window).height())}}" title="<?php echo Jtext::_('COM_TESTIMONIALS_EDIT'); ?>">
				<i class="icon-pencil-2 fa fa-pencil" alt="<?php echo Jtext::_('COM_TESTIMONIALS_EDIT'); ?>"></i>
			</a>
			<?php 
			}
			if ($this->helper->can('delete'))
				{
			?>
			<a class="btn btn-danger" href="javascript:void(0)" onclick="javascript:if (confirm('<?php echo JText::_('COM_TESTIMONIALS_CONFIRM_DELETE'); ?>')){ document.location.href='<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.delete&id='.$id); ?>';}else return;" title="<?php echo Jtext::_('COM_TESTIMONIALS_DELETE'); ?>" >
                <i class="icon-trash fa fa-trash-o"></i>
			</a>
			<?php }
			if ($this->helper->can('comment') && 0) {
			?>
			<a class="btn btn-info" href="javascript:void(0)" onclick="jQuery('#add_comment<?php echo $id;?>').slideToggle();" title="<?php echo JText::_('COM_TESTIMONIALS_CAN_COMMENT');?>" >
				<i class="icon-reply fa fa-reply" alt="<?php echo Jtext::_('COM_TESTIMONIALS_CAN_COMMENT'); ?>"></i>
			</a>
			<?php }
			?>
		</div>
		<?php
    	}
    }
	
}
