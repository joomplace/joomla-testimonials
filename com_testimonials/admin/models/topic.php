<?php
/**
 * Testimonials Component for Joomla 3
 *
 * @package   Testimonials
 * @author    JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Topic model.
 *
 */
class TestimonialsModelTopic extends JModelAdmin
{

    protected $text_prefix = 'COM_TESTIMONIALS';

    public function getTable(
    $type = 'testimonials', $prefix = 'TestimonialsTable', $config = array()
    )
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getCBAvatar()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.avatar');
        $query->from('`#__tm_testimonials` AS `t`');
        $query->join('LEFT', '#__comprofiler AS c ON c.user_id = t.user_id_t');
        $query->where('t.id=' . $id);
        $db->setQuery($query->__toString());

        return $db->loadResult();
    }

    public function getJSAvatar()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('j.avatar');
        $query->from('`#__tm_testimonials` AS `t`');
        $query->join('LEFT', '#__community_users AS j ON j.userid = t.user_id_t');
        $query->where('t.id=' . $id);
        $db->setQuery($query->__toString());

        return $db->loadResult();
    }

    public function getCustomFields()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.id, c.name, c.type, eif.value, c.required, c.descr');
        $query->from('`#__tm_testimonials_custom` AS `c`');
        $query->join('LEFT', '#__tm_testimonials_items_fields AS eif ON eif.field_id = c.id AND eif.item_id='
            . $id);
        $query->group('c.id');
        $query->order('c.ordering');
        $db->setQuery($query->__toString());

        return $db->loadObjectList();
    }

    public function getForm($data = array(), $loadData = true)
    {
        $app = JFactory::getApplication();

        $form = $this->loadForm('com_testimonials.topics', 'topic', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_testimonials.edit.topic.data', array());

        if (empty($data)) {
            $id = $this->getState('topic.id');
            if (!empty($id))
                $data = $this->getItem($id);
        }

        if (empty($data->photo) && !empty($data->user_id_t)) {
            $data->photo = $this->getUserAvatar($data->user_id_t);
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $result = parent::getItem($pk);
        $result->preloadImages = $this->prepareImages($result->images, $result->id);

        return $result;
    }

    public function prepareImages($images, $id)
    {
        $result = array();
        $images = explode("|", $images);
        foreach ($images as $image) {
            if (!empty($image) && file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images'
                    . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR
                    . $image)
            ) {
                $i = count($result);
                $result[$i]['image'] = $image;
                $result[$i]['status'] = 'ok';
                $result[$i]['name'] = $image;
                $result[$i]['size'] = filesize(JPATH_SITE
                    . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
                    . 'testimonials' . DIRECTORY_SEPARATOR . $image);
                $result[$i]['url'] = JUri::root(true)
                    . '/images/testimonials/' . $image;
                $result[$i]['thumbnail_url'] = 'index.php?option=com_testimonials&task=images.imageThumb&id='
                    . $id . '&image=' . urldecode($image);
                $result[$i]['delete_url'] = 'index.php?option=com_testimonials&task=images.deleteImage&id='
                    . $id . '&image=' . urldecode($image);
                $result[$i]['delete_type'] = 'DELETE';
            }
        }

        return $result;
    }

    function getUserAvatar($uId)
    {
        $settings = JComponentHelper::getParams("com_testimonials");
        $avatar = '';
        if ($uId > 0 && ($settings->get('use_cb') == 1 || $settings->get('use_jsoc') == 1))
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            if ($settings->get('use_cb') == 1 && file_exists(JPATH_SITE.'/components/com_comprofiler/comprofiler.php')) {
                $query->select('CONCAT("images/comprofiler/",compr.avatar) as `avatar`');
                $query->from('`#__comprofiler` AS `compr`');
                $query->where('compr.user_id = ' . $uId);
            }
            else if ($settings->get('use_jsoc') == 1 && file_exists(JPATH_SITE.'/components/com_community/community.php')) {
                $query->select('jsoc.thumb as `avatar`');
                $query->from('`#__community_users` AS `jsoc`');
                $query->where('jsoc.userid = ' . $uId);
            }
            $db->setQuery($query);
            $data = $db->loadObject();
            if (!empty($data->avatar) && file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $data->avatar)) {
                $avatar = $data->avatar;
            }
        }

        return $avatar;
    }
}
