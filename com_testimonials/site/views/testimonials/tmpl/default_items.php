<?php foreach($this->items as $item){ ?>
<div class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
	<meta itemprop="itemreviewed" content="Product" />
	<div class="testimonial_caption"><p class="h3" itemprop="name"><?php echo $item->caption; ?></p></div>
	<div class="row row-fluid">
		<div class="col-xs-12 col-sm-3">
		<?php if($item->avatar){ ?>
			<div class="testimonial_image" itemprop="image"><span class="thumbnail"><?php echo $item->avatar; ?></span></div>
			<p class="text-center">
				<span class="testimonial_author" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $item->author; ?></span></span><br/>
				<?php echo ($item->company)?($item->company.'<br/>'):''; ?>
				<?php echo ($item->position)?($item->position.'<br/>'):''; ?>
			</p>
		<?php } ?>
		</div>
		<blockquote class="col-xs-12 col-sm-9">
			<p class="testimonial_text" itemprop="reviewBody"><?php echo trim($item->testimonial); ?></p>
			<p class="testimonial_text">
			<?php echo $item->author_description; ?>
			</p>
			<?php
				$cust_data = array_filter(array($item->city,$item->country,$item->company));
				echo implode(', ',$cust_data);
			?>
			<?php if($item->custom_fields){ ?>
				<table class="customs-table">
				<?php foreach($item->custom_fields as $field){ ?>
					<?php echo $field; ?>
				<?php } ?>
				</table>
			<?php } ?>
		</blockquote>
	</div>
	<?php if($item->images){ ?>
		<div class="row row-fluid image-list">
			<?php echo $item->images; ?>
		</div>
	<?php } ?>
	<?php /* ?>
	<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	<?php */ ?>
	<?php echo $this->renderLayout('testimonials.comments',$item); ?>
	<?php echo $this->renderLayout('testimonials.actions',$item); ?>
</div>
<?php } ?>