<?php
/**
 * Testimonials Module for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

/* generating 'read more' link */
$link = '';
$addnew = '';

$jinput = JFactory::getApplication()->input;
if ($jinput->get('view', '') == 'testimonials') {
    $tm_itemid = $jinput->get('Itemid', 0, 'int');
}

if ($params->get('show_readmore') == 1) {
	$link = $helper->generateTestimModuleLinkReadMore(JRoute::_("index.php?option=com_testimonials&view=testimonials&Itemid=$tm_itemid".'#anc_'.$value->id), JText::_('MOD_TESTIMONIALS_READ'),false);
}

/* generating 'add new' link */
if ($show_add_new && JFactory::getUser()->authorise('core.create', 'com_testimonials')) {
	$addnew = $helper->generateTestimModuleLink(JRoute::_("index.php?option=com_testimonials&view=form&".(($modal)?'tmpl=component':'')."&Itemid=$tm_itemid"), JText::_('MOD_TESTIMONIALS_NEW'), $modal);
}

/* generating testimonial output */

if ($params->get('show_caption')) { ?>
	<div class='mod_testimonials_caption'>
		<?php echo $value->t_caption;?>
	</div>
<?php } ?>
	<div class="mod_testimonial_text">
	<?php
		if ($params->get('show_avatar_module')) {
			if(empty($value->avatar)) $value->avatar = '';
			$avatar = $helper->getAvatar($value->photo, $value->avatar);
			if(!empty($avatar)){
				?><div class="tstmnl_avatar"><?php echo $avatar;?></div><?php
			}
		}
		
		$testim = strip_tags($value->testimonial);
		$length = $params->get('chars_count',200);
		$dots="...";
		$spacebar= ' ';
		/* need to make sure that slice is on spacebar */
		if(strlen($testim)>$length){
			$part = substr($testim, 0 , $length);
			if(strpos($part,$spacebar)) while(substr($part, -1)!=$spacebar){
				$part = substr($part, 0, strlen($part)-1);
			}
			$testim = $part.$dots;
		}
		echo $testim; ?>
	</div>
	<?php
	if($params->get('show_first')) {
		echo '<div class="testimonials_buttons">';
			if($params->get('show_readmore')){ ?>
				<?php echo $link;?> 
			<?php 	}
			if($show_add_new){ ?>
				<?php echo $addnew;?>
			<?php	}
		echo '</div>';
	}
	?>
	<br style="clear:both;" />
	<?php
	if($params->get('show_author_module')) { ?>
		<div class="mod_testimonial_author"><?php echo nl2br($value->t_author);?></div>
		<div class="mod_testimonial_author_desc"><?php echo $value->author_description;?></div>
		<br style="clear:both;" />
	<?php	}
	if(!$params->get('show_first')) {
		echo '<div class="testimonials_buttons">';
			if($params->get('show_readmore')){ ?>
				<?php echo $link;?>
			<?php 	}
			if($show_add_new){ ?>
				<?php echo $addnew;?>
			<?php	}
		echo '</div>';
	}

