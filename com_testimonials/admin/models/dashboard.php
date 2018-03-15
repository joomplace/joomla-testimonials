<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class TestimonialsModelDashboard extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) { $config['filter_fields'] = array(); }
		parent::__construct($config);


	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('`#__tm_testimonials_dashboard_items` AS `di`');
		$query->where('`di`.published=1');

		return $query;
	}

    public function getCurrDate()
    {
        $result = TestimonialHelper::getSetting('curr_date');
        if (strtotime("+2 month",strtotime($result))<=time()) {
            return true;
        } else {
            return false;
        }
    }
}
