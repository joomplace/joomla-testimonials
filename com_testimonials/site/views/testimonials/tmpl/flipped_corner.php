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
.tl".$uid." .block-text {
	
}
.tl".$uid." .testimonial-body:before, .testimonial-body:after {
    content: '';
    position: absolute;
    right: -5px;
    bottom: -5px;
}
.tl".$uid." .testimonial-body{
    border: 5px solid #e2e6e6;
    margin: 0;
    padding: 31px 36px 26px;
    position: relative;
    margin-bottom: 20px;
}
.tl".$uid." .testimonial-body:after {
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 30px 30px;
    border-color: transparent transparent #ffffff transparent;
}
.tl".$uid." .testimonial-body{
    font-size: 16px;
    line-height: 1.4em;
}
.tl".$uid." .testimonial-body:before {
    width: 33px;
    height: 33px;
    background: #e2e6e6;
}
.tl".$uid." .testim-auth-info:after{
	display: table;
	content: '';
	clear: both;
}
.tl".$uid." .testimonial .testim-auth-info img {
    border: 1px solid #f5f5f5;
    border-radius: 150px !important;
    height: 75px;
    padding: 3px;
    width: 75px;
}
.tl".$uid." .testimonial .testim-auth-info {
    overflow: hidden;
}
.tl".$uid." .testimonial .testim-auth-info img {
    margin-right: 15px;
}
.tl".$uid." .testimonial .testim-auth-info span {
    display: block;
}
.tl".$uid." .testimonial span.testimonials-name {
    color: #e6400c;
    font-size: 16px;
    font-weight: 300;
    margin: 23px 0 7px;
}
.tl".$uid." .testimonial{
	margin-bottom: 30px;
}
.tl".$uid." .testimonial .testim-title{
	font-weight: bold;
	margin-bottom: 1em;
}
.tl".$uid." .testimonial span.testimonials-post {
    color: #656565;
    font-size: 12px;
}
");
?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
<article class="testimonials-layout tl<?php echo $uid; ?>">
    <?php 
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
	
	$cat_id = $this->category->id;
	if($cat_id){
		?>
		<h3><?php echo $this->escape($this->category->title); ?></h3>
		<?php if($this->category->description){ ?>
		<p>
			<?php echo $this->category->description; ?>
		</p>
		<?php } ?>
		<?php
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
			<a href="<?php echo JRoute::_('index.php?option=com_testimonials&view=form'.$tmpl.($cat_id?'&catid='.$cat_id:'').'&Itemid='.TestimonialsHelper::getClosesItemId('index.php?option=com_testimonials&view=form'.($cat_id?'&catid='.$cat_id:''))); ?>" class="modal_com_testim btn btn-success" rel="{handler:'iframe',size:{x: (0.8*((jQuery('.testimonials-layout').width())?jQuery('.testimonials-layout').width():jQuery('.container').width())), y: (0.8*jQuery(window).height())}}"><?php echo (JText::_('COM_TESTIMONIALS_ADD')); ?></a><br />
		</div>
	<?php
	}
?>
    <?php
    $menuitem = JFactory::getApplication()->getMenu()->getActive();
    $no_save_ordering = ($menuitem->params->get('show_ordering') === null) ? true : false;
    if($menuitem && ((int)$menuitem->params->get('show_ordering') == 1 || $no_save_ordering)){
    ?>
    <div class="text-right">
		<fieldset id="jform_MetaAuthor" class="btn-group btn-group-yesno radio">
			<input type="hidden" id="order" name="ordering" value="t_caption">
			<label for="order" class="btn <?php echo $this->order['name'] ?>" onclick="document.adminForm.ordering.value='t_caption'; document.adminForm.submit();"><?php echo JTEXT::_('COM_TESTIMONIALS_TOPIC_BY_NAME'); ?></label>
			<label for="order" class="btn <?php echo $this->order['order'] ?>" onclick="document.adminForm.ordering.value='ordering'; document.adminForm.submit();"><?php echo JTEXT::_('COM_TESTIMONIALS_TOPIC_BY_ORDER'); ?></label>
		</fieldset>
	</div>
    <?php } ?>
	
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
