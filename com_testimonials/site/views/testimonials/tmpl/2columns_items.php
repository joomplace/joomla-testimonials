<?php 
/**
 * Testimonials Component for Joomla 3
 *
 * @package   Testimonials
 * @author    JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
//$settings = $this->params;
$settings = JComponentHelper::getParams('com_testimonials');
$i = 0; ?>
<div class="row">
<?php 
foreach($this->items as $item){ 
	if($i%2==0 && $i!=0){
?>
</div>
<div class="row">
<?php
	}
?>
	<div class="testimonial col-xs-12 col-sm-6" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
		<?php echo $this->renderLayout('testimonials.actions',$item); ?>
		<div id="anc_<?php echo $item->id; ?>" class="testimonial_caption"><p class="h3" itemprop="name"><?php echo $item->caption; ?></p></div>
		<div class="row">
			<div class="col-xs-12">
			<?php if($item->avatar && $settings->get('show_avatar')){ ?>
				<div class="testimonial_image" itemprop="image"><a href="javascript:void(0);" class="thumbnail"><?php echo $item->avatar; ?></a></div>
			<?php } ?>
			</div>
			<blockquote class="col-xs-12">
				<p class="testimonial_text" itemprop="reviewBody">
				    <?php echo $item->testimonial; ?>
				</p>
				<?php if($item->custom_fields) foreach($item->custom_fields as $field){ ?>
					<p class="testimonial_custom-fields"><?php echo $field; ?></p>
				<?php } ?>
				<small class="testimonial_author" itemprop="author"><?php echo $item->author; ?></small>
                <?php if (!empty($item->author_description)): ?>
                <p class="testimonial_text" itemprop="reviewBody">
                    <div class="autor_discription">
                        <?php echo $item->author_description; ?>
                    </div>
                </p>
                <?php endif; ?>
                <?php if (!empty($item->date_added)): ?>
                    <meta itemprop="datePublished" content="<?php echo explode(' ', $item->date_added)[0]; ?>">
                    <p class="testimonial_date">
                        <?php echo $item->date_added; ?>
                    </p>
                <?php endif; ?>
			</blockquote>
		</div>
		<?php if($item->images){ ?>
			<div class="row image-list">
				<?php echo $item->images; ?>
			</div>
		<?php } ?>
		<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	</div>
<?php 
	$i++;
}
 ?>
</div>




