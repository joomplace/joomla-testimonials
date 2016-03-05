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
.tl".$uid." .testimonial {
    border: 1px solid #333;
    box-shadow: 0px 2px 4px #ccc, inset 0px -3px 0px #2D6987;
    padding: 12px 12px 24px;
}
.tl".$uid." .testimonial_caption{
    margin: -10px -10px 15px;
    padding: 8px 20px 8px;
    background: #333;
    color: #FFF;
    border-bottom: 3px solid #2D6987;
}
.tl".$uid." .testimonials-list .h3{
    margin: 0px;
    font-size: 1.4em;
}
.tl".$uid." .testimonial_tags{
	margin-top: 10px;
	margin-bottom: 30px;
}
.tl".$uid." .testimonial_body p{
    font-size: 16.25px;
    font-weight: 300;
    line-height: 1.25;
}
.tl".$uid." .testimonial_body small{
	display: block;
    font-size: 80%;
    line-height: 1.42857143;
    color: #777;
}
.tl".$uid." .testimonial_image img{
	display: inline-block;
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

	if($helper->can("Create"))
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