<?php foreach($this->items as $item){ ?>
<div class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
	<div class="testimonial_caption"><p class="h3" itemprop="name"><?php echo $item->caption; ?></p></div>
	<div class="testimonial_body">
		<?php if($item->images){ ?>
			<div class="row row-fluid image-list">
				<?php echo $item->images; ?>
			</div>
		<?php } ?>
		<div class="row row-fluid">
			<div class="span12 col-xs-12 col-sm-12">
				<p class="testimonial_text" itemprop="reviewBody">
				<?php echo $item->testimonial; ?>
				</p>
			</div>
		</div>
		<div class="row row-fluid">
			<div class="span6 col-xs-12 col-sm-6">
				<?php if($item->custom_fields) foreach($item->custom_fields as $field){ ?>
					<p><?php echo $field; ?></p>
				<?php } ?>
			</div>
		<?php if($item->avatar){ ?>
			<div class="span3 col-xs-12 col-sm-3 text-right">
		<?php }else{ ?>
			<div class="span6 col-xs-12 col-sm-6 text-right">
		<?php } ?>
				<div class="testimonial_author" itemprop="author"><?php echo $item->author; ?></div>
				<small class="testimonial_text" itemprop="reviewBody">
				<?php echo $item->author_description; ?>
				</small>
			</div>
			<?php if($item->avatar){ ?>
			<div class="span3 col-xs-12 col-sm-3">
				<div class="testimonial_image text-right" itemprop="image"><?php echo $item->avatar; ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
<?php echo $this->renderLayout('testimonials.comments',$item); ?>
<?php echo $this->renderLayout('testimonials.actions',$item); ?>
<?php } ?>