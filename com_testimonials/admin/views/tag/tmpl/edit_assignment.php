<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

// Initiasile related data.
require_once JPATH_ADMINISTRATOR.'/components/com_menus/helpers/menus.php';
$menuTypes = new MenusHelper();
$menuTypes = $menuTypes->getMenuLinks();
?>

		<script type="text/javascript">
			window.addEvent('domready', function(){
				validate();
				document.getElements('select').addEvent('change', function(e){validate();});
			});
			function validate(){
				var value	= document.id('jform_assignment').value;
				var button 	= document.id('jform_toggle');
				var list	= document.id('menu-assignment');
				if(value == '-' || value == '0'){
					button.setProperty('disabled', true);
					list.getElements('input').each(function(el){
						el.setProperty('disabled', true);
						if (value == '-'){
							el.setProperty('checked', false);
						} else {
							el.setProperty('checked', true);
						}
					});
				} else {
					button.setProperty('disabled', false);
					list.getElements('input').each(function(el){
						el.setProperty('disabled', false);
					});
				}
			}
		</script>

		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_TESTIMONIALS_TAG_MENU_ASSINGS'); ?></legend>
			
			<label id="jform_menuselect-lbl" class="hasTip" for="jform_menuselect"><?php echo JText::_('JGLOBAL_MENU_SELECTION'); ?></label>

			<button type="button" id="jform_toggle" class="jform-rightbtn" onclick="$$('.chk-menulink').each(function(el) { el.checked = !el.checked; });">
				<?php echo JText::_('JGLOBAL_SELECTION_INVERT'); ?>
			</button>
			
			<div class="clr"></div>
			
			<div id="menu-assignment">
			
			<?php echo JHtml::_('tabs.start','module-menu-assignment-tabs', array('useCookie'=>1));?> 
			
			<?php foreach ($menuTypes as &$type) : 
				echo JHtml::_('tabs.panel', $type->title ? $type->title : $type->menutype, $type->menutype.'-details');
				
				$count 	= sizeof($type->links);
				$i		= 0;
				if ($count) :
				?>					
				<ul class="menu-links">
					<?php
                    $checked = '';
					foreach ($type->links as $link) :
						if (!empty($this->item->assignment) && trim($this->item->assignment) == '-'):
							$checked = '';
						elseif (!empty($this->item->assignment) && $this->item->assignment == 0):
							//$checked = ' checked="checked"';
                            $checked = '';
						elseif (!empty($this->item->assignment) && $this->item->assignment < 0):
							$checked = in_array(-$link->value, $this->item->assigned) ? ' checked="checked"' : '';
						elseif (!empty($this->item->assignment) && $this->item->assignment > 0) :
							$checked = in_array($link->value, $this->item->assigned) ? ' checked="checked"' : '';
						endif;
					?>
					<li class="menu-link">
						<input type="checkbox" class="chk-menulink" name="jform[assigned][]" value="<?php echo (int) $link->value;?>" id="link<?php echo (int) $link->value;?>"<?php echo $checked;?>/>
						<label for="link<?php echo (int) $link->value;?>">
							<?php echo $link->text; ?>
						</label>
					</li>
					<?php if ($count > 20 && ++$i == ceil($count/2)) :?>
					</ul><ul class="menu-links">
					<?php endif; ?>						
					<?php endforeach; ?>
				</ul>
				<div class="clr"></div>
				<?php endif; ?>					
			<?php endforeach; ?>
			<?php echo JHtml::_('tabs.end');?>
			</div>
		</fieldset>
