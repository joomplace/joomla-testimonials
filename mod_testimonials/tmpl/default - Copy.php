<?php
/**
 * Testimonials Module for Joomla 1.6
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
if ($modal) {
    JHtml::stylesheet(JURI::root() . 'components/com_testimonials/assets/jquery.fancybox-1.3.4.css');
    JHtml::script(JURI::base() . 'components/com_testimonials/assets/jquery.fancybox-1.3.4.js');
}

//JFactory::getDocument()->addStyleDeclaration(".".$params->get('moduleclass_sfx')." {height: ".$params->get('height')."px !important ;}");
?>
<script>
    var interval<?php echo $module->id; ?>;
    (function($) {
        $(document).ready(function() {
<?php if (($show_add_new && JFactory::getUser()->authorise('core.create', 'com_testimonials')) || $modal) : ?>
                $("a.modtm_iframe").fancybox({'type': 'iframe', 'width': 820, 'height': 750});
<?php endif; ?>
            var height_max = 0;
            var width_div = 0;
            $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem').each(function() {
                var height = 0;
                $(this).width($('#mod_testimonial_div_<?php echo $module->id; ?>').width());

                height += $(this).children('.mod_testimonial_text').height();
                height += $(this).children('.mod_testimonials_caption').height();
                height += $(this).children('.testimonials_buttons').height();
                height += $(this).children('.mod_testimonial_author').height();
                height += $(this).children('.mod_testimonial_author_desc').height();

                height += 10;

                if (height > height_max)
                    height_max = height;
            });

            $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem').each(function(index) {
                var crop_top = 0;

                $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem  > .mod_testimonial_text').css('overflow', 'hidden');
                $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem  > .mod_testimonial_text').css('text-overflow', 'ellipsis');
                $("head").append($('<style type="text/css">.crop' + index + ':after { content: "\u00a0\u00a0\u00a0\u00a0\u2026"; float: right; margin-left: -6em; padding-right: 5px; position: relative; text-align: right; top: -' + crop_top + 'px; box-sizing: content-box; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; background-size: 100% 100%; }</style>'));
                $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem  > .mod_testimonial_text').addClass('crop' + index);



                if (index > 0) {
                    $(this).css('display', 'none');
                    $(this).removeClass('fade');
                }


                width_div += $('#mod_testimonial_div_<?php echo $module->id; ?>').width();
            });
            if (width_div)
                $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div').width(width_div);
            if (height_max)
                $('#mod_testimonial_div_<?php echo $module->id; ?>').height(height_max);

<?php if (!$params->get('isstatic') && count($list) > 1) : ?>
                $('#mod_testimonial_div_<?php echo $module->id; ?>').mouseover(function() {
                    clearInterval(interval<?php echo $module->id; ?>);
                });
                $('#mod_testimonial_div_<?php echo $module->id; ?>').mouseout(function() {
                    interval<?php echo $module->id; ?> = setInterval(slideShow<?php echo $module->id; ?>, <?php echo $params->get('timeout', 5); ?>000);
                });
<?php endif; ?>
        });
        slideShow<?php echo $module->id; ?> = function() {
            var selectedEffect = '<?php echo $params->get('slideshow_effect', 'slide'); ?>';
            var displayToggled = false;
            var current1 = $('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem:visible');
            var nextSlide = current1.next('#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div > .testimModItem');
            if (selectedEffect == 'slide') {
                var hideoptions = {
                    "direction": "left",
                    "mode": "hide"
                };
                var showoptions = {
                    "direction": "right",
                    "mode": "show"
                };
            } else {
                var hideoptions = {
                    "mode": "hide"
                };
                var showoptions = {
                    "mode": "show"
                };
            }
            if (current1.is(':last-child')) {
                current1.effect(selectedEffect, hideoptions, 1000);
                $("#mod_testimonial_div_<?php echo $module->id; ?> > #mod_testimonial_div").children("#testimModItem_0").effect(selectedEffect, showoptions, 1000);
            }
            else {
                current1.effect(selectedEffect, hideoptions, 1000);
                nextSlide.effect(selectedEffect, showoptions, 1000);
            }
        }
<?php if (!$params->get('isstatic') && count($list) > 1) : ?>
            interval<?php echo $module->id; ?> = setInterval(slideShow<?php echo $module->id; ?>, <?php echo $params->get('timeout', 5); ?>000);
<?php endif; ?>
    })(jplace.jQuery);
</script>


<?php
if ($isStatic) {
    $list = array($list[rand(0, count($list) - 1)]);
}
?>
<div class='mod_testimonial_div' id="mod_testimonial_div_<?php echo $module->id; ?>" style="overflow: hidden;">
    <div id="mod_testimonial_div">
    <?php foreach ($list as $key => $value) {
        ?>
        <div class="testimModItem<?php if ($key > 0) : ?> fade<?php endif; ?> <?php echo $moduleclass_sfx ?>" id="testimModItem_<?php echo $key; ?>" style="float:left; ">
            <?php require(JModuleHelper::getLayoutPath('mod_testimonials', $params->get('layout', 'default_body'))); ?>
        </div>
        <?php
    }
    ?>
    </div>
</div>


