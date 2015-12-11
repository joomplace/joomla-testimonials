<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
$extension  = 'com_joomlaquiz';
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$search 	= $this->escape($this->state->get('filter.search'));

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
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=dashboard_items'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_TESTIMONIALS_FILTER_SEARCH_DESC'); ?></label>
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_TESTIMONIALS_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_TESTIMONIALS_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="clearfix"> </div>
				<table class="table table-striped">
						<thead>
							<tr>
								<th class="center" width="1%">
									<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
								</th>
								<th width="15%">
									<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
								</th>
                                <th width="15%">
                                    <?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_DASHBOARD_ITEM_URL', 'url', $listDirn, $listOrder); ?>
                                </th>
                                <th width="5%">
                                    <?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_DASHBOARD_ITEM_ICON', 'icon', $listDirn, $listOrder); ?>
                                </th>
								<th width="5%">
									<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_PUBLISHED', 'published', $listDirn, $listOrder); ?>
								</th>
								<th width="5%" class="nowrap">
									<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="9"><?php if ($this->pagination) echo $this->pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
						<tbody>
						<?php if (sizeof($this->items)) {foreach($this->items as $i => $item) {
                            $canEdit	= $user->authorise('core.edit', $extension.'.questions.'.$item->id);
                            $canCheckin	= $user->authorise('core.admin', 'com_checkin');
                            $canChange	= $user->authorise('core.edit.state', $extension.'.dashboard_items.'.$item->id) && $canCheckin;
							?>
							<tr class="row<?php echo $i % 2; ?>">
								<td class="center">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>
								<td>
									<a href="<?php echo JRoute::_('index.php?option=com_testimonials&view=dashboard_item&layout=edit&id='.$item->id);?>">
										<?php echo $this->escape($item->title); ?>
									</a>
								</td>
                                <td>
                                    <?php
                                         echo $item->url; ?>
                                </td>
                                <td>
                                    <?php
                                        echo("<img src='".subfolding($item->icon)."'>");
                                    ?>
                                </td>
								<td>
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'dashboard_items.', $canChange);?>
								</td>								
								<td>
									<?php echo $item->id; ?>
								</td>
							</tr>
						<?php }} else { ?>
							<tr>
								<td colspan="9" align="center" >
									<?php echo JText::sprintf('COM_TESTIMONIALS_DASHBOARD_ITEMS_NONE', 'dashboard items'); ?>
									<a href="<?php echo JRoute::_('index.php?option=com_testimonials&task=dashboard_item.add'); ?>" >
										<?php echo JText::_('COM_TESTIMONIALS_DASHBOARD_ITEMS_NONE_A'); ?>
									</a>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<div>
						<input type="hidden" name="task" value="" />
						<input type="hidden" name="boxchecked" value="0" />
						<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
						<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</form>
