<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;
 
/**
 * Topics Controller
 */
class TestimonialsControllerTopics extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     * @since       1.6
     */
    public function getModel($name = 'Topic', $prefix = 'TestimonialsModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function saveOrderAjax()
	{
		$pks = $this->input->get('cid', array(), 'array');
		$order = $this->input->get('order', array(), 'array');

        $pks = ArrayHelper::toInteger($pks);
        $order = ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return) {
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

    public function approve()
    {
        $ids = $this->input->post->get('cid', array(), 'array');

        $db = JFactory::getDbo();
        $query = "UPDATE `#__tm_testimonials` SET `is_approved`='1' WHERE `id` IN (".implode(',', $ids).")";
        $db->setQuery($query);

        if($db->execute()) {
            $msg = 'Selected items were succesfully approved.';
            $this->setMessage($msg, 'message');
            $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
        } else {
            $msg = '';
            $this->setMessage($msg, 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
        }
    }

    public function disapprove()
    {
        $ids = $this->input->post->get('cid', array(), 'array');

        $db = JFactory::getDbo();
        $query = "UPDATE `#__tm_testimonials` SET `is_approved`='0' WHERE `id` IN (".implode(',', $ids).")";
        $db->setQuery($query);

        if($db->execute()) {
            $msg = 'Selected items were succesfully disapproved.';
            $this->setMessage($msg, 'message');
            $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
        } else {
            $msg = '';
            $this->setMessage($msg, 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_testimonials&view=topics', false));
        }
    }
}
