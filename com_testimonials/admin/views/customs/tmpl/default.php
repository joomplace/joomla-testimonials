<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
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
	$saveOrderingUrl = 'index.php?option=com_testimonials&task=customs.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=customs'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
        <table class="table table-striped" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="1%" class="center">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_NAME', 'name', $listDirn, $listOrder); ?> 
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_DISPLAY_IN_LIST', 'display', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_REQUIRED', 'required', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_CUTOMS_TYPE', 'type', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JText::_('COM_TESTIMONIALS_CUTOMS_DESC'); ?>
				</th>
				<th width="10%" class="nowrap center">
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
			$canEdit	= $user->authorise('core.edit',	$extension.'.customs.'.$item->id);
			$canCheckin	= $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	$extension.'.customs.'.$item->id) && $canCheckin;
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
					<input type="text" style="display:none" name="order[]" size="5"
						value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
				<?php else : ?>
					<span class="sortable-handler inactive" >
						<i class="icon-menu"></i>
					</span>
				<?php endif; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="nowrap">
				    <div class="pull-left">
					<?php if ($canEdit) : ?>
					    <a href="<?php echo JRoute::_('index.php?option=com_testimonials&task=custom.edit&id='.$item->id);?>"><?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
					    <?php echo $this->escape($item->name); ?>
					<?php endif; ?>
				    </div>
				</td>
				<td class="center">
				    <div class="pull-left">
				    <?php					
						$states = array(1 => array('undisplay', 'COM_TESTIMONIALS_DISPLAYED', 'COM_TESTIMONIALS_UNDISPLAY_ITEM', 'JPUBLISHED', true, 'publish', 'publish'),
										0 => array('display', 'COM_TESTIMONIALS_UNDISPLAYED', 'COM_TESTIMONIALS_DISPLAY_ITEM', 'JUNPUBLISHED', true, 'unpublish', 'unpublish'));
						echo JHtml::_('jgrid.state', $states, $item->display, $i, 'customs.', $canChange);
					?>
				    </div>
				</td>
				<td class="center">
				    <div class="pull-left">
				    <?php					
						$states = array(1 => array('unsetrequire', 'COM_TESTIMONIALS_REQUIRED', 'COM_TESTIMONIALS_UNREQUIRE_ITEM', 'JPUBLISHED', true, 'publish', 'publish'),
										0 => array('setrequire', 'COM_TESTIMONIALS_UNREQUIRED', 'COM_TESTIMONIALS_REQUIRE_ITEM', 'JUNPUBLISHED', true, 'unpublish', 'unpublish'));
						echo JHtml::_('jgrid.state', $states, $item->required, $i, 'customs.', $canChange);
					?>
				    </div>
				</td>
				<td class="center">
				    <?php
						/* Make with JTEXT */
					$type = array(
							'text' 		=> 'Single input field', 
							'textemail'	=> 'Email input field', 
							'url'		=> 'URL(Link)',
							'date'		=> 'Date',
							'imagelist'	=> 'List of Images',
							'rating'	=> 'Rating'
						);
					echo $type[$item->type];
				 ?>
				</td>
				<td class="center">
				    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'customs.', $canChange);?>
				</td>
				<td class="small">
				    <div class="pull-left">
					<?php echo $item->descr; ?>
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
						echo '<tr><td colspan="8" align="center">You have no any fields – <a onclick="javascript:Joomla.submitbutton(\'custom.add\')" href="javascript:void(0)">Create new one?</a></td></tr>'; 
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