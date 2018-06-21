<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');
 
/**
 * Topic Controller
 */
class TestimonialsControllerTopic extends JControllerForm
{
      	protected function allowEdit($data = array(), $key = 'id')
        {
             return JFactory::getUser()->authorise('core.edit', 'com_testimonials');             
        }

	function apply() {

	}
	public function addImage(){

	}

    public function setApproved()
    {
        $isApproved = JFactory::getApplication()->input->get('is_approved');
        $id = JFactory::getApplication()->input->get('id');

        switch($isApproved){
            case '1':
                    $query = "UPDATE `#__tm_testimonials` SET `is_approved`='0' WHERE `id`=".$id;
                break;
            case '0':
                    $query = "UPDATE `#__tm_testimonials` SET `is_approved`='1' WHERE `id`=".$id;
                break;
            default:
                    $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
                return;
        }
        $db = JFactory::getDbo();
        $db->setQuery($query);
        $db->execute();
        $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
        return;
    }

    public function showpicture()
    {
        $settings =  TestimonialHelper::getSettings();
        require_once(JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'Timg.php');
        $image = JFactory::getApplication()->input->getString('image');
        $tid = JFactory::getApplication()->input->getInt('tid',0);
        $width = JFactory::getApplication()->input->getInt('width',300);
        $height = JFactory::getApplication()->input->getInt('height',300);

        $database = JFactory::getDBO();
        $query = $database->getQuery(true);
        if ($tid)
        {
            if ($settings->get('use_cb') == 1)
            {
                $query->select('CONCAT("images/comprofiler/",compr.avatar)');
                $query->join('LEFT','`#__comprofiler` AS `compr` ON compr.user_id = t.user_id_t');

            }
            if ($settings->get('use_jsoc') == 1)
            {
                $query->select('jsoc.thumb as `avatar`');
                $query->join('LEFT','`#__community_users` AS `jsoc` ON jsoc.userid = t.user_id_t');

            }
            else
            {
                $query->select('t.photo');
            }
            $query->from('`#__tm_testimonials` AS `t`');
            $query->where('t.`id`= '.$tid);
            $database->setQuery($query);
            $imagepath = $database->loadResult();
        }

        if (isset($imagepath))
        {
            $imclass= new TimgHelper();
            echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$imagepath,$width,$height));
        }else if (isset($image)) //for BE and old vers
        {
            $imclass= new TimgHelper();
            echo $imclass->show($imclass->resize(JPATH_SITE.'/'.$image,$width,$height));
        }
        exit();
    }
}
