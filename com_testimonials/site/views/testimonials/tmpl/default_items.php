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
foreach($this->items as $item){ ?>
<div class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
	<meta itemprop="itemreviewed" content="Services" />
	<div id="anc_<?php echo $item->id; ?>" class="testimonial_caption"><p class="h3" itemprop="name"><?php echo $item->caption; ?></p></div>
	<div class="row row-fluid">
		<div class="col-xs-12 col-sm-3">
		<?php if($item->avatar && $settings->get('show_avatar')){ ?>
			<div class="testimonial_image" itemprop="image"><a href="javascript:void(0);" class="thumbnail"><?php echo $item->avatar; ?></a></div>
		<?php } ?>
		</div>
		<blockquote class="col-xs-12 col-sm-9">
			<p class="testimonial_text" itemprop="reviewBody">
			<?php echo $item->testimonial; ?>
			</p>
			<?php if(isset($item->custom_fields) && $item->custom_fields){ ?>
				<table class="customs-table">
				<?php foreach($item->custom_fields as $field){ ?>
					<?php echo $field; ?>
				<?php } ?>
				</table>
			<?php } ?>
			<small class="testimonial_author" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $item->author; ?></span></small>
            <?php if (!empty($item->author_description)): ?>
                <p class="testimonial_text" itemprop="reviewBody">
                    <div class="autor_discription">
                        <?php echo $item->author_description; ?>
                    </div>
                </p>
            <?php endif; ?>
		</blockquote>
	</div>
	<?php if($item->images){ ?>
		<div class="row row-fluid image-list">
			<?php echo $item->images; ?>
		</div>
	<?php } ?>
	<div class="testimonial_tags text-right"><?php echo $item->tags; ?></div>
	<?php echo $this->renderLayout('testimonials.comments',$item); ?>
	<?php echo $this->renderLayout('testimonials.actions',$item); ?>
</div>
<?php } ?>