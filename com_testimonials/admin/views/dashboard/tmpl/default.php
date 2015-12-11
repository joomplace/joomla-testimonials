<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');

function subfolding($r_url){
	$r_url = '/'.trim(str_replace(JUri::root(),'',$r_url),'/');
	$subfold = JUri::root(true);
	if($subfold){
		if(strpos('/'.$ditem->icon,$subfold)===false){
			return $subfold.$r_url;
		}else{
			return $r_url;
		}
	}else{
		return $r_url;
	}
}
?>
<?php echo $this->loadTemplate('menu');?>
<div id="pgm_dashboard">
    <?php
    foreach($this->dashboardItems as $ditem) {
        ?>
        <div onclick="window.location ='<?php echo $ditem->url; ?>'" class="pgm-dashboard_button">
            <?php if ($ditem->icon) { ?>
                <img src="<?php echo subfolding($ditem->icon); ?>" class="pmg-dashboard_item_icon"/>
            <?php } ?>
            <?php echo '<div class="pgm-dashboard_button_text">'.$ditem->title.'</div>'?>
        </div>
   <?php } ?>
<div id="dashboard_items" ><a href="index.php?option=com_testimonials&view=dashboard_items"><?php echo JText::_('COM_TESTIMONIALS_MANAGE_DASHBOARD_ITEMS');?></a></div>
</div>
<div id="pgm_collapse">
<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a style="text-decoration: underline !important;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                About Joomla Testimonials
            </a>
        </div>
        <div id="collapseOne" class="accordion-body collapse in">
            <div class="accordion-inner">
                <table border="1" width="100%" class="about_table" >
                    <tr>
                        <th colspan="2" class="a_comptitle">
                            <strong><?php echo JText::_('COM_TESTIMONIALS'); ?></strong> component for Joomla! 3.0 Developed by
                            <a href="http://www.JoomPlace.com">JoomPlace</a>.
                        </th>
                    </tr>
                    <tr>
                        <td width="13%"  align="left">Installed version:</td>
                        <td align="left">&nbsp;<b><?php echo TestimonialHelper::getVersion();?></b>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">Latest version:</td>
                        <td>
                            <div id="tm_LatestVersion">
                                <script type="text/javascript">
                                    tm_CheckVersion();
                                </script>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="left">About:</td>
                        <td align="left"><?php echo JText::_('COM_TESTIMONIALS_ABOUT_TEXT'); ?></td>
                    </tr>
                    <tr>
                        <td align="left">Community Forum:</td>
                        <td align="left"><a target="_blank" href="http://www.joomplace.com/forum/joomla-components/testimonials-component.html">http://www.joomplace.com/forum/joomla-components/testimonials-component.html</a></td>
                    </tr>
                    <tr>
                        <td align="left">Support Helpdesk:</td>
                        <td align="left"><a target="_blank" href="http://www.joomplace.com/support/helpdesk/post-purchase-questions/ticket/create">http://www.joomplace.com/support/helpdesk/post-purchase-questions/ticket/create</a></td>
                    </tr>
                    </table>
            </div>
        </div>
    </div>
    <div class="accordion-group">
        <div class="accordion-heading">
            <a style="text-decoration: underline !important" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                <?php echo JText::_("COM_TESTIMONIALS_ABOUT_SAYTHANKSTITLE"); ?>
            </a>
        </div>
        <div id="collapseTwo" class="accordion-body collapse">
            <div class="accordion-inner">
                <div class="thank_fdiv" style="font-size:12px;margin-left: 4px;">
                    <?php echo JText::_("COM_TESTIMONIALS_ABOUT_SAYTHANKS1"); ?>
                    <a href="http://extensions.joomla.org/extensions/vertical-markets/education-a-culture/quiz/11302" target="_blank">http://extensions.joomla.org/</a>
                    <?php echo JText::_("COM_TESTIMONIALS_ABOUT_SAYTHANKS2"); ?>
                </div>
                <div style="float:right; margin:3px 5px 5px 5px;">
                    <a href="http://extensions.joomla.org/extensions/vertical-markets/education-a-culture/quiz/11302" target="_blank">
                        <img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php if ($this->messageTrigger) { ?>
<div id="notification" class="jqd-survey-wrap clearfix" style="clear: both">
    <div class="jqd-survey">
        <span><?php echo JText::_("COM_TESTIMONIALS_NOTIFICMES1"); ?><a onclick="tm_dateAjaxRef()" style="cursor: pointer" rel="nofollow" target="_blank"><?php echo JText::_("COM_TESTIMONIALS_NOTIFICMES2"); ?></a><?php echo JText::_("COM_TESTIMONIALS_NOTIFICMES3"); ?><i id="close-icon" class="icon-remove" onclick="tm_dateAjaxIcon()"></i></span>
    </div>
</div>
<?php } ?>