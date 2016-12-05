<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_testimonials/assets/css/testimonials.css');

?>
<div id="tm-navbar" class="navbar navbar-static navbar-inverse">
    <div class="navbar-inner">
        <div class="container" style="width: auto;">
            <a class="brand" href="<?php JRoute::_('index.php?option=com_testimonials') ?>"><img class="tm-panel-logo" src="<?php echo JURI::root(true) ?>/administrator/components/com_testimonials/assets/images/joomplace-logo.png" /> <?php echo JText::_('COM_TESTIMONIALS_JOOMPLACE')?></a>
            <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a id="control-panel" href="index.php?option=com_testimonials&view=dashboard" role="button" class="dropdown-toggle"><?php echo JText::_('COM_TESTIMONIALS_CONTROL_PANEL')?></a>
                </li>
            </ul>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-testimonials">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse-joomlaquiz nav-collapse collapse">
                <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a href="#" id="drop-quiz-management" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_TESTIMONIALS_MENU_MANAGEMENT') ?><b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="drop-testimonials-management">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_testimonials&view=topics"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_TOPICS');?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_categories&extension=com_testimonials"><?php echo JText::_('COM_TESTIMONIALS_CATEGORIES');?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_testimonials&view=customs"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS');?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_testimonials&view=tags"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_TAGS');?></a></li>
                    </ul>
                </li>
				
				<li class="dropdown">
					<a role="menuitem" tabindex="-1" href="index.php?option=com_config&view=component&component=com_testimonials&return=<?php echo urlencode(base64_encode((string)JUri::getInstance())) ?>"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_SETTINGS')?></a>
				</li>
				
            </ul>
            <ul class="nav pull-right">
                <li id="fat-menu" class="dropdown">
                    <a href="#" id="help" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_TESTIMONIALS_MENU_HELP') ?><b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="help">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/video-tutorials-and-documentation/joomla-testimonials/index.html?description.htm" target="_blank"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_HELP') ?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/forum/testimonials-component/page-1.html" target="_blank"><?php echo JText::_('COM_TESTIMONIALS_ADMINISTRATION_SUPPORT_FORUM') ?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/support/helpdesk" target="_blank"><?php echo JText::_('COM_TESTIMONIALS_ADMINISTRATION_SUPPORT_DESC') ?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/support/helpdesk/department/presale-questions" target="_blank"><?php echo JText::_('COM_TESTIMONIALS_ADMINISTRATION_SUPPORT_REQUEST') ?></a></li>
                    </ul>
                </li>
            </ul>
          </div>
       </div>
    </div>
</div>