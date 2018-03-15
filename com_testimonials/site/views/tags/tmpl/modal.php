<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
 
JHtml::_('behavior.tooltip');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $ordering = $listOrder == 'ordering';
$user		= JFactory::getUser();
$userId		= $user->get('id');
$extension  = 'com_testimonials';
$function	= JRequest::getCmd('function', 'jSelectTestimonial');
?>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=tags'); ?>" method="post" name="adminForm" class="form-inline">


				<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_TESTIMONIALS_FILETERBYTAG'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
				<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
	<div class="clr"> </div><br />
		<table class="table table-striped">
			<thead>
			<tr>
			        <th>
			             <?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_TAGS_NAME', 'tag_name', $listDirn, $listOrder); ?> 
			        </th>
			        <th width="15%">
						<?php echo JText::_('COM_TESTIMONIALS_TAGS_MENU'); ?>
					</th>
			        <th width="15%">
			               <?php echo JText::_('COM_TESTIMONIALS_TAGS_ARTICLE'); ?>
			         </th>
			         <th width="15%">
						<?php echo JText::_('COM_TESTIMONIALS_TAGS_CATEGORY'); ?>
					</th>
			        <th width="5%">
			               <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
			         </th>
			        <th width="5">
			        	<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_ID', 'id', $listDirn, $listOrder); ?>
			        </th>
				</tr>
			</thead>
			<tbody>	
					<?php 
					if (count ($this->items))
					{
					foreach($this->items as $i => $item): 
						$canEdit	= $user->authorise('core.edit',			$extension.'.tags.'.$item->id);
						$canCheckin	= $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
						$canChange	= $user->authorise('core.edit.state',	$extension.'.tags.'.$item->id) && $canCheckin;
					?>
					        <tr>
					               
					                <td>
						               <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->tag_name)); ?>');">
											<?php echo $this->escape($item->tag_name); ?>
					                </td>
					                <td valign="top">
					                        <?php 
					                        	$menu = $item->menu;
					                        	if (strlen($menu)>0)
					                        	{
					                        		$tmp_menu_mass = explode(',',$menu);
					                        		sort($tmp_menu_mass);
					                        		echo implode('<br />',$tmp_menu_mass);
					                        	} 
					                        ?>
					                </td>
					                 <td valign="top">
					                         <?php 
					                        	$articles = $item->articles;
					                        	if (strlen($articles)>0)
					                        	{
					                        		$tmp_art_mass = explode(',',$articles);
					                        		sort($tmp_art_mass);
					                        		echo implode('<br />',$tmp_art_mass);
					                        	} 
					                        ?>
					                 </td>
					                 <td  valign="top">
					                         <?php 
					                        	$categories = $item->categories;
					                        	if (strlen($categories)>0)
					                        	{
					                        		$tmp_cat_mass = explode(',',$categories);
					                        		sort($tmp_cat_mass);
					                        		echo implode('<br />',$tmp_cat_mass);
					                        	} 
					                        ?>
					                 </td>
					               <td class="center">
										<?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', $canChange);?>
									</td>
									 <td>
					                        <?php echo $item->id; ?>
					              	</td>
					        </tr>
					<?php endforeach; 
					}
					else if (!$this->state->get('filter.search'))
					{
						echo '<tr><td colspan="8" align="center">You have no any tags – <a onclick="javascript:Joomla.submitbutton(\'tag.add\')" href="javascript:void(0)">Create new one?</a></td></tr>'; 
					}else echo '<tr><td colspan="8" align="center">Tags not found</a></td></tr>';
					
					?>
					<tr>
				        <td colspan="8">
				        	<?php echo $this->pagination?$this->pagination->getListFooter():''; ?>
				        </td>
					</tr>
							</tbody>
						</table>
        <div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="layout" value="modal" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>