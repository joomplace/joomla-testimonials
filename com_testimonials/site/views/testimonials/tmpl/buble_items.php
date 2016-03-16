
<?php foreach($this->items as $item){ ?>
<div class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
	<div class="block-text rel zmin">
		<span class="testim-title" title="" itemprop="name"><?php echo $item->caption; ?></span>
		<?php if($item->custom_fields) foreach($item->custom_fields as $field){ ?>
			<div><?php echo $field; ?></div>
		<?php } ?>
		<p itemprop="reviewBody">
			<?php echo $item->testimonial; ?>
		</p>
		<?php if($item->images){ ?>
			<div class="row row-fluid image-list">
				<?php echo $item->images; ?>
			</div>
		<?php } ?>
		<ins class="ab zmin sprite sprite-i-triangle block"></ins>
	</div>
	<div class="person-text rel">
		<?php echo $item->avatar; ?>
		<span title="" itemprop="author"><?php echo $item->author; ?></span>
		<i><?php echo $item->author_description; ?></i>
	</div>
	<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	<?php echo $this->renderLayout('testimonials.actions',$item); ?>
	<?php echo $this->renderLayout('testimonials.comments',$item); ?>
</div>
<?php } ?>
