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
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=tags'); ?>" method="post" name="adminForm" id="adminForm">
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
                    <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>
        <div class="clearfix"> </div>
        <table class="table table-striped" id="articleList">
            		<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'COM_TESTIMONIALS_TAGS_NAME', 'tag_name', $listDirn, $listOrder); ?> 
					</th>
					<th>
						<?php echo JText::_('COM_TESTIMONIALS_TAGS_MENU'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JText::_('COM_TESTIMONIALS_TAGS_ARTICLE'); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JText::_('COM_TESTIMONIALS_TAGS_CATEGORY'); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
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
				$canEdit	= $user->authorise('core.edit',			$extension.'.tags.'.$item->id);
				$canCheckin	= $user->authorise('core.admin', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
				$canChange	= $user->authorise('core.edit.state',	$extension.'.tags.'.$item->id) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php if ($item->id!=1) echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
				    <div class="nowrap pull-left">
					<?php if ($canEdit) : ?>
					    <a href="<?php echo JRoute::_('index.php?option=com_testimonials&task=tag.edit&id='.$item->id);?>"><?php echo $this->escape($item->tag_name); ?></a>
					<?php else : ?>
					    <?php echo $this->escape($item->tag_name); ?>
					<?php endif; ?>
				    </div>
				</td>
				<td>
				    <div class="pull-left">
					<?php 
						$menu = $item->menu;
						if (strlen($menu)>0)
						{
							$tmp_menu_mass = explode(',',$menu);
							sort($tmp_menu_mass);
							echo implode('<br />',$tmp_menu_mass);
						} 
					?>
				    </div>
				</td>
				<td>
				    <div class="pull-left">
					<?php 
						$articles = $item->articles;
						if (strlen($articles)>0)
						{
							$tmp_art_mass = explode(',',$articles);
							sort($tmp_art_mass);
							echo implode('<br />',$tmp_art_mass);
						} 
					?>
				    </div>
				</td>
				<td>
				    <div class="pull-left">
					<?php 
						$categories = $item->categories;
						if (strlen($categories)>0)
						{
							$tmp_cat_mass = explode(',',$categories);
							sort($tmp_cat_mass);
							echo implode('<br />',$tmp_cat_mass);
						} 
					?>
				    </div>
				</td>
				<td class="center">
				    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'tags.', $canChange);?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
				<?php endforeach; ?>
				<?php /*
				}
					else if (!$this->state->get('filter.search'))
					{
						echo '<tr><td colspan="8" align="center">You have no any tags – <a onclick="javascript:Joomla.submitbutton(\'tag.add\')" href="javascript:void(0)">Create new one?</a></td></tr>'; 
					}else echo '<tr><td colspan="8" align="center">Tags not found</a></td></tr>';
				*/?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>