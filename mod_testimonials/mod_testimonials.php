<?php

/**
 * Testimonials module for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
//jimport( 'joomla.application.component.helper' );
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helper.php';
require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_testimonials' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'Timg.php';
//JLoader::register('TimgHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'Timg.php');

$helper = new modTestimonialsHelper();

$user = JFactory::getUser();
$document = JFactory::getDocument();
$app = JFactory::getApplication();
$option = JFactory::getApplication()->input->getVar('option');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$iCan = new stdClass();
$iCan->manage = ($user->authorise('core.manage', 'com_testimonials'));
$iCan->create = ($user->authorise('core.create', 'com_testimonials'));

if (!$params->get('all_tags')) {
    $tags_id = $params->get('tags');

    if (count($tags_id) > 1 || (count($tags_id) == 1 && $tags_id[0] != 0))
        $list = $helper->getListByTags($params, $tags_id);
    else
        $list = $helper->getList($params);
}
else
    $list = $helper->getList($params);

if (empty($list)) {
    echo '<small>' . JText::_('Testimonials not found') . '</small>';
    return;
}


if ($params->get('autoItemid')) {
    if (!isset($tm_itemid)) {
        $tm_itemid = $helper->tmItemId();
        if (!isset($tm_itemid)) {
            $tm_itemid = 0;
        }
    }
} else {
    $tm_itemid = $params->get('Itemid', 0);
}
$isStatic = $params->get('isstatic');

$show_add_new = (int) $params->get('show_add_new', 0);

//$document->addStyleSheet(JURI::root() . '/modules/mod_testimonials/tmpl/style.css');

$modal = $params->get('ismodal');

$document = JFactory::getDocument();
//JHtml::script(JURI::base() . 'components/com_testimonials/assets/jplace.jquery.js');
//JHtml::script('components/com_testimonials/assets/jquery-ui/jquery-ui.js');
//JHtml::stylesheet('components/com_testimonials/assets/jquery-ui/jquery-ui.css');
if (($show_add_new && JFactory::getUser()->authorise('core.create', 'com_testimonials')) || $modal) {
	JHtml::_('behavior.modal','a.modtm_modal');
    //JHtml::stylesheet(JURI::root() . 'components/com_testimonials/assets/jquery.fancybox-1.3.4.css');
    //JHtml::script(JURI::base() . 'components/com_testimonials/assets/jquery.fancybox-1.3.4.js');
}

require JModuleHelper::getLayoutPath('mod_testimonials', $params->get('layout', 'default'));
?>