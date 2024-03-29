<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

$helper = $this->helper;
$params = $helper->getParams();
$uid = uniqid();
JFactory::getDocument()->addStyleDeclaration("
.tl".$uid." .label + .label{
	margin-left: 10px;
}
.tl".$uid." .tm_stars{
	color: #ff8800;
}
.tl".$uid." .testimonial_image{
	text-align: center;
}
.tl".$uid." .testimonial_image > a{
	display: inline-block;
}
.tl".$uid." .testimonial_image img{
	border-radius: 9px;
}
.tl".$uid." .testimonial{
	margin-bottom: 40px;
}
.tl".$uid." .testimonial blockquote {
  border-left: 5px solid #eee;
  padding-left: 20px;
  word-break: break-word;
}
.tl".$uid." .testimonial blockquote p {
	font-size: 15.5px;
}
.tl".$uid." .testim-manage-block {
    text-align: right;
    border: 1px solid #AFAEAE;
    border-width: 0px 1px 1px 1px;
    display: inline-block;
    border-radius: 0px 0px 4px 4px;
    box-shadow: 0px 2px 3px #B3B3B3;
    padding: 4px;
    background: #FFF;
    margin: -1px 5px 1px;
}
.tl".$uid." .testim-manage-block-wrap {
    border-top: 1px solid #AFAEAE;
    margin-top: 5px;
}
.tl".$uid." .testimonials-list{
    margin-top: 20px;
}
.tl".$uid." .testimonial blockquote p:before {
  content: '';
}
.tl".$uid." .testimonial blockquote p:after {
  content: '';
}
.autor_discription :only-child{
  color: #777;
}
.autor_discription{
  color: #777;
  font-size: 15.5px;
}
");
?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
<article class="testimonials-layout tl<?php echo $uid; ?>">
    <?php
	$cat_id = !empty($this->category) ? $this->category->id : 0;
	if($cat_id){ ?>
        <?php if(!empty($this->category->title)): ?>
		    <h3><?php echo $this->escape($this->category->title); ?></h3>
        <?php endif; ?>
        <?php if(!empty($this->category->description)): ?>
            <p><?php echo $this->category->description; ?></p>
        <?php endif; ?>
		<?php
	}
	
	if($helper->getActiveItem()->params){
		if($helper->getActiveItem()->params->get('show_page_heading')){ ?>
        <div class="page-header">
            <h1> <?php echo $this->escape($helper->getActiveItem()->params->get('page_heading')); ?> </h1>
        </div>
    <?php } ?>
    <?php if($helper->getActiveItem()->params->get('tm_intro_text')){ ?>
        <div class="testimonials-intro-text">
            <?php echo $this->processText($helper->getActiveItem()->params->get('tm_intro_text')); ?>
        </div>
    <?php }
    }

	if($helper->can("Create") && $this->show_button)
	{
		$tmpl = '';
		if($params->get('modal_on_new')){
			JHTML::_( 'behavior.modal','a.modal_com_testim' );
			$tmpl = '&tmpl=component';
		}
		?>
		<div class="text-right">
			<a href="<?php echo JRoute::_('index.php?option=com_testimonials&view=form'.$tmpl.($cat_id?'&catid='.$cat_id:'').'&Itemid='.TestimonialsFEHelper::getClosesItemId('index.php?option=com_testimonials&view=form'.($cat_id?'&catid='.$cat_id:''))); ?>" class="modal_com_testim btn btn-success" rel="{handler:'iframe',size:{x: (0.8*((jQuery('.testimonials-layout').width())?jQuery('.testimonials-layout').width():jQuery('.container').width())), y: (0.8*jQuery(window).height())}}"><?php echo (JText::_('COM_TESTIMONIALS_ADD')); ?></a><br /><br/>
		</div>
	<?php
	}

	if(JFactory::getApplication()->input->get('option', '') == 'com_testimonials') {
        $menuitem = JFactory::getApplication()->getMenu()->getActive();
        if(!empty($menuitem) && !empty($menuitem->params)
            && !empty($menuitem->params->get('show_ordering')) && (int)$menuitem->params->get('show_ordering') == 1){
            ?>
            <div class="text-right">
                <fieldset id="jform_MetaAuthor" class="btn-group btn-group-yesno radio">
                    <input type="hidden" id="order" name="ordering" value="t_caption">
                    <label for="order" class="btn <?php echo empty($this->order['name'])?'':$this->order['name'] ?>"
                           onclick="document.adminForm.ordering.value='t_caption'; document.adminForm.submit();"><?php echo JTEXT::_('COM_TESTIMONIALS_TOPIC_BY_NAME'); ?></label>
                    <label for="order" class="btn <?php echo empty($this->order['order'])?'':$this->order['order'] ?>"
                           onclick="document.adminForm.ordering.value='ordering'; document.adminForm.submit();"><?php echo JTEXT::_('COM_TESTIMONIALS_TOPIC_BY_ORDER'); ?></label>
                </fieldset>
            </div>
        <?php }
    }
    ?>
	
	<div class="testimonials-list">
		<?php
			echo $this->loadTemplate('items');
		?>
	</div>
</article>
<?php if($this->pagination){ ?>
    <div class="pagination">
        <?php echo $this->pagination->getListFooter();?>
    </div>
<?php } ?>
</form>