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
JHtml::_('behavior.modal');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $ordering = $listOrder == 'temp_name';
$user		= JFactory::getUser();
$userId		= $user->get('id');
$extension  = 'com_testimonials';

$saveOrder	= $listOrder == 'ordering';

?>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
	 <?php echo $this->leftmenu;?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="center">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_NAME', 'temp_name', $listDirn, $listOrder); ?>
				</th>
				<th class="center">
					<?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CSS'); ; ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_PREVIEW'); ?>
				</th>
				<th width="1%" class="nowrap center">
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
			$canEdit	= $user->authorise('core.edit',	$extension.'.templates.'.$item->id);
			$canCheckin	= $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	$extension.'.templates.'.$item->id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="nowrap">
				    <div class="pull-left">
					<?php if ($canEdit) : ?>
					    <a href="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.edit&id='.$item->id);?>"><?php echo $this->escape($item->temp_name); ?></a>
					<?php else : ?>
					    <?php echo $this->escape($item->temp_name); ?>
					<?php endif; ?>
				    </div>
				</td>
				<td class="center">
				    <?php 
					$file_path = JPATH_COMPONENT_SITE.'/templates/'.$this->escape($item->temp_name).'/css/style.css';
					if (is_writeable($file_path)) {
					    ?>
						<div class="button2-left">
							<div class="blank">
								<a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 300}, iframeOptions: {scrolling: 'no'}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.editcss&id='.$item->id.'&tmpl=component');?>"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_CSS'); ?></a>
							</div>
						</div>
				    <?php
					}else {
						?>
						<font color="red"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_UNWRITEABLE'); ?></font>
				    <?php
					}
				    ?>
				</td>
				<td class="center">
				    <div class="button2-left">
					    <div class="blank">
						    <a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, iframeOptions: {scrolling: 'no'}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_testimonials&task=template.preview&id='.$item->id.'&tmpl=component');?>"><?php echo JText::_('COM_TESTIMONIALS_TEMPLATE_PREVIEW'); ?></a>
					    </div>
				    </div>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php
			/*
			 * else
					{
						echo '<tr><td colspan="5" align="center">You have no any templates</td></tr>'; 
					}
			 */
			?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>