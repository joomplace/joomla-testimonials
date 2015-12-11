<?php $i = 0; ?>
<div class="row-fluid">
<?php 
foreach($this->items as $item){ 
	if($i%2==0 && $i!=0){
?>
</div>
<div class="row-fluid">
<?php
	}
?>
	<div class="testimonial col-xs-12 col-sm-6" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
		<?php echo $this->renderLayout('testimonials.actions',$item); ?>
		<div class="testimonial_caption"><p class="h3" itemprop="name"><?php echo $item->caption; ?></p></div>
		<div class="row row-fluid">
			<div class="span3 col-xs-12 col-sm-6">
			<?php if($item->avatar){ ?>
				<div class="testimonial_image" itemprop="image"><a href="javascript:void(0);" class="thumbnail"><?php echo $item->avatar; ?></a></div>
			<?php } ?>
			</div>
			<blockquote class="span9 col-xs-12 col-sm-6">
				<p class="testimonial_text" itemprop="reviewBody">
				<?php echo $item->testimonial; ?>
				</p>
				<p class="testimonial_text" itemprop="reviewBody">
				<?php echo $item->t_author; ?>
				</p>
				<?php if($item->custom_fields) foreach($item->custom_fields as $field){ ?>
					<p><?php echo $field; ?></p>
				<?php } ?>
				<small class="testimonial_author" itemprop="author"><?php echo $item->author; ?></small>
			</blockquote>
		</div>
		<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	</div>
<?php 
	$i++;
}
 ?>
</div>




