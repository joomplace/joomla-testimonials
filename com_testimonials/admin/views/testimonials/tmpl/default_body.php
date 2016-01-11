<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
$imgpath = JURI::root(true).'/administrator/components/com_testimonials/assets/images/';
?>
<tr>
	<td valign="top" class="lefmenutd">
		<div>
			<?php echo $this->leftmenu;?>				
		</div>
	</td>
	<td valign="top" width="85%">
		<table border="1" width="100%" class="about_table" >
			<tr>
				<th colspan="2" style="font-size:16px !important;">
					<strong>Testimonials</strong> component for Joomla! 3 Developed by  
						<a href="http://www.JoomPlace.com">JoomPlace</a>.
				</th>
			</tr>
			<tr>
				<td width="120"  align="left">Installed version:</td>
				<td align="left">
				 	&nbsp;<b><?php echo $this->version;?></b>
				</td>
			</tr>
			<tr>
				<td align="left">Latest version:</td>
				<td>
					<div id="tm_LatestVersion">
						<a href="check_now" onclick="return tm_CheckVersion();" class="update_link">
							Check now
						</a>
					</div>
				</td>
			 </tr>
			 <tr>
				<td valign="top" align="left">About:</td>
				<td align="left">
					This component gives opportunity to create testimonials.
				</td>
			</tr>						
			<tr>
				<td align="left">Support forum:</td>
				<td align="left">
					<a target="_blank" href="http://www.JoomPlace.com/support">http://www.JoomPlace.com/support</a>
				</td>
			</tr>
		  	<tr>
		  		<td colspan="2">
		  			<br />
		  			<div class="clr"></div>
				  	<table border="1" cellpadding="5" width="100%" class="thank_tabl">
						<tr>
							<th colspan="2" class="thank_ftd">
								<strong>
									<img src="<?php echo $imgpath;?>tick.png">Say your "Thank you" to Joomla community for WonderFull Joomla CMS
								</strong>
							</th>
						</tr>					
						<tr>
							<td style="padding-left:20px">
								<div class="thank_fdiv">
									<p style="font-size:12px;">
										<span style="font-size:14pt;">Say your "Thank you" to Joomla community</span> for WonderFull Joomla CMS and <span style="font-size:14pt;">help it</span> by sharing your experience with this component. It will only take 1 min for registration on <a href="http://extensions.joomla.org/extensions/contacts-and-feedback/testimonials-a-suggestions/11304" target="_blank">http://extensions.joomla.org/</a> and 3 minutes to write useful review! A lot of people will thank you!
									</p>
								</div>
								<div style="float:left;margin:5px">
									<a href="http://extensions.joomla.org/extensions/contacts-and-feedback/testimonials-a-suggestions/11304" target="_blank">
										<img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png">
									</a>
								</div>
								<div style="clear:both;margin:5px;padding-top:5px;">
									<hr color="#CCCCCC"/>
								</div>
								<div style="float:left; width:680px;">
									<p style="font-size:12px;">
										Alternatively here  is a quick way to rate US on largest Script website - just make your choice and Vote:
									</p>
								</div>
								<div class="thank_rate">
									<form action="http://www.hotscripts.com/rate/94749/?RID=N578805" method="post" class="thank_form">
										<strong>Like our script?</strong> <br /> Rate it at
										<a target="_blank" href="http://www.hotscripts.com/category/php/scripts-programs/plugins-modules-add-ons/joomla--mambo-modules/?RID=N578805" style="color: #fff; text-decoration: none;" >PHP</a> > 
										<a target="_blank" href="http://www.hotscripts.com/?RID=N578805" style="color: #fff; text-decoration: none;">Hot Scripts</a>
										<br />
											<select name="rate" style="width: 98px; overflow: hidden; font: normal 11px/12px Arial, Helvetica, sans-serif; color: #000; float: right; margin: 5px 4px 0 0; padding: 0; clear: none;">
												<option value="5">Excellent</option>
												<option value="4">Very Good</option>
												<option value="3">Good</option>
												<option value="2">Fair</option>
												<option value="1">Poor</option>
											</select>
										<input type="image" src="http://cdn.hotscripts.com/img/widgets/btn_vote-3.gif" style="width: 49px; height: 22px; overflow: hidden; float: right; margin: 4px 0 0; clear: none; padding: 0; border: 0;" />
									</form>
								</div>
							
								<div style="clear:both"><!--x--></div>
							</td>
						</tr>
						<tr>
							<th colspan="2" class="about_news"><strong><img src="<?php echo JURI::root(true);?>/administrator/components/com_testimonials/assets/images/tick.png" />Joomplace news/campaigns</strong></th>
						</tr>
						<tr>
							<td colspan="2" style="padding-left:20px" align="justify">
								<div id="tm_LatestNews" style="width:496px;">
									<script type="text/javascript" language="javascript">
										<!--//--><![CDATA[//><!-- 
										tm_CheckNews(); 
										//--><!]]>
									</script>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>