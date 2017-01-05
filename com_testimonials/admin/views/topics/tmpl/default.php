<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
 
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $ordering = $listOrder == 'ordering';
$user		= JFactory::getUser();
$userId		= $user->get('id');
$extension  = 'com_testimonials';

$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_testimonials&task=topics.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'testimonialsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<?php echo $this->loadTemplate('menu');?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=topics'); ?>" method="post" name="adminForm" id="adminForm">
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
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_TESTIMONIALS_FILETERBYTAG');?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_TESTIMONIALS_FILETERBYTAG'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
            </div>
            <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                            <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                            <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                    </select>
            </div>
            <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                    </select>
            </div>
        </div>
        <div class="clearfix"> </div>
        <table class="table table-striped" id="testimonialsList">
            		<thead>
				<tr">
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_NAME', 't_caption', $listDirn, $listOrder); ?> 
					</th>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category', $listDirn, $listOrder); ?> 
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 't_author', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
                    </th>
                    <th width="1%" class="nowrap">
                        <?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_TOPIC_ISAPPROVED', 'is_approved', $listDirn, $listOrder); ?>
                    </th>
					<!--th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
                                        <?php if ($saveOrder) :?>
                                                <?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'topics.saveorder'); ?>
                                        <?php endif; ?>
					</th-->
					<th width="10%" class="nowrap">
						<?php echo JText::_('COM_TESTIMONIALS_ADMINISTRATION_TAGS'); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_ID', 'id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'ordering');
				$canEdit	= $user->authorise('core.edit',			$extension.'.topics.'.$item->id);
                                $canCheckin	= $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                                $canChange	= $user->authorise('core.edit.state',	$extension.'.topics.'.$item->id) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
					<td class="order nowrap center">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					    <input type="text" style="display:none" name="order[]" size="5"
							value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					</td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="nowrap has-context">
                                            <div class="pull-left">
                                            <?php if ($canEdit) : ?>
                                                    <a href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.edit&id='.$item->id);?>"><?php echo $this->escape($item->t_caption); ?></a>
                                            <?php else : ?>
                                                    <?php echo $this->escape($item->t_caption); ?>
                                            <?php endif; ?>
                                            </div>
					</td>
					<td class="nowrap has-context">
						<?php echo $item->category; ?>
					</td>
					<td class="has-context">
                                            <div class="pull-left">
                                            <?php echo $item->t_author; ?>
                                            </div>
					</td>
					<td class="center">
                                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'topics.', $canChange);?>
					</td>
                    <td class="center">
                        <?php if($item->is_approved == 1){?>
                            <a class="btn btn-micro active" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.setApproved&id='.$item->id.'&is_approved=1');?>"><i class="icon-publish"></i></a>
                        <?php } else {?>
                            <a class="btn btn-micro" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=topic.setApproved&id='.$item->id.'&is_approved=0');?>"><i class="icon-unpublish"></i></a>
                        <?php }?>
                    </td>
					<!--td class="center">
                                            <?php if ($canChange) : ?>
                                                <?php if ($saveOrder) :?>
                                                        <?php if ($listDirn == 'asc') : ?>
                                                                <span><?php echo $this->pagination->orderUpIcon($i, true, 'topics.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                                                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'topics.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                                        <?php elseif ($listDirn == 'desc') : ?>
                                                                <span><?php echo $this->pagination->orderUpIcon($i, true, 'topics.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                                                <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'topics.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                                        <?php endif; ?>
                                                <?php endif; ?>
                                                <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
                                                <input type="text" name="order[]" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="input-mini" />
                                            <?php else : ?>
                                                    <?php echo $item->ordering; ?>
                                            <?php endif; ?>
					</td-->
					<td class="small">
						<?php echo $item->tags; ?>
					</td>
					<td class="center">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
        
    </div>

</form>