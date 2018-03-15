
<?php foreach($this->items as $item){ ?>
<div class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
	<?php echo $this->renderLayout('testimonials.actions',$item); ?>
	<div class="testimonial-body">
		<meta itemprop="itemreviewed" content="Services" />
		<div class="testim-title" title="" itemprop="name"><?php echo $item->caption; ?></div>
		<p itemprop="reviewBody">
			<?php echo $item->testimonial; ?>
		</p>
		<?php if($item->custom_fields){ ?>
			<table class="customs-table">
			<?php foreach($item->custom_fields as $field){ ?>
				<?php echo $field; ?>
			<?php } ?>
			</table>
		<?php } ?>
		<?php if($item->images){ ?>
			<br/>
			<div class="row row-fluid image-list">
				<?php echo $item->images; ?>
			</div>
		<?php } ?>
		<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	</div>
	<div class="testim-auth-info">
		<?php echo $item->avatar; ?>
		<div class="pull-left">
			<span class="testimonial_author" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $item->author; ?></span></span>
			<span class="testimonials-post"><?php echo $item->author_description; ?></span>
		</div>
	</div>
	<?php echo $this->renderLayout('testimonials.comments',$item); ?>
</div>
<?php } ?>
