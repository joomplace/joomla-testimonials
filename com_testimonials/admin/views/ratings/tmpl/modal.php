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
$extension  = 'com_testimonials';
$function	= JFactory::getApplication()->input->getCmd('function', 'jSetSummaryTag');
?>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=ratings'); ?>" method="post" name="adminForm">
    <div id="j-main-container" class="span12">
	<table class="table table-striped" id="articleList">
            		<thead>
				<tr>
					<th class="nowrap center">
						<?php echo JText::_('COM_TESTIMONIALS_NAME'); ?>  
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JText::_('JPUBLISHED'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JText::_('COM_TESTIMONIALS_ID'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			    <?php 
					foreach($this->items as $i => $item): 
					?>
					        <tr class="row<?php echo $i % 2; ?>">
					               
					                <td>
							    <div class="nowrap pull-left">
						               <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $this->escape(addslashes($item->name)); ?>');">
											<?php echo $this->escape($item->name); ?></a>
								</div>
					                </td>
							<td class="center">
							    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'ratings.', false);?>
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
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="layout" value="modal" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>