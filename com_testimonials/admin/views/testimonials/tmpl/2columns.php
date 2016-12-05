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
}
.tl".$uid." .testimonial blockquote p {
	font-size: 15.5px;
}
.tl".$uid." .testim-manage-block {
    text-align: right;
    border: 1px solid #AFAEAE;
    border-width: 1px 1px 0px 1px;
    display: inline-block;
    border-radius: 4px 4px 0px 0px;
    box-shadow: 0px -2px 3px #B3B3B3;
    padding: 4px;
    background: #FFF;
    margin: 1px 5px -1px;
}
.tl".$uid." .testim-manage-block-wrap {
    border-bottom: 1px solid #AFAEAE;
    margin-bottom: 5px;
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
");
?>
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
	
	$cat_id = '';
	if($cat_id){
		$cat_id = $this->category->id;
		?>
		<h3><?php echo $this->escape($this->category->title); ?></h3>
		<?php if($this->category->description){ ?>
		<p>
			<?php echo $this->category->description; ?>
		</p>
		<?php } ?>
		<?php
	}

	if($helper->can("Create"))
	{
		$tmpl = '';
		if($params->get('modal_on_new')){
			JHTML::_( 'behavior.modal','a.modal_com_testim' );
			$tmpl = '&tmpl=component';
		}
		?>
		<div class="text-right">
			<a href="<?php echo JRoute::_('index.php?option=com_testimonials&view=form'.$tmpl.($cat_id?'&catid='.$cat_id:'').'&Itemid='.TestimonialsHelper::getClosesItemId('index.php?option=com_testimonials&view=form'.($cat_id?'&catid='.$cat_id:''))); ?>" class="modal_com_testim btn btn-success" rel="{handler:'iframe',size:{x: (0.8*((jQuery('main').width())?jQuery('main').width():jQuery('.container').width())), y: (0.8*jQuery(window).height())}}"><?php echo (JText::_('COM_TESTIMONIALS_ADD')); ?></a><br />
		</div>
	<?php
	}
?>
	<div class="testimonials-list">
		<?php
			echo $this->loadTemplate('items');
		?>
	</div>
</article>
<?php if($this->pagination){ ?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <div class="pagination">
        <?php echo $this->pagination->getListFooter();?>
    </div>
</form>
<?php } ?>