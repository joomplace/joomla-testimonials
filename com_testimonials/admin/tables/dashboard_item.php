<?php


defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TestimonialsTableDashboard_item extends JTable
{
    function __construct($db)
    {
        parent::__construct('#__tm_testimonials_dashboard_items', 'id', $db);
    }

    public function store($updateNulls = false) {
        return parent::store($updateNulls);
    }
}